<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    Crypt,
    DB,
    Hash,
    Http,
    Validator,
    Storage,
};

use App\Models\{
    ApiLog,
    Country,
    FileManager,
    TmpUser,
    ManualManagementBonus,
    OtpAction,
    User,
    UserDetail,
    UserDevice,
    UserOld,
    UserSocial,
    UserStructure,
    UserWallet,
    UserKyc,
    UserBank,
    UserBeneficiary,
    UserWalletTransaction,
};

use Illuminate\Validation\Rules\Password;

use App\Rules\CheckASCIICharacter;

use Helper;

use Carbon\Carbon;

class UserService {

    public static function allUsers( $request ) {

        $user = User::with( [
            'country',
            'kyc',
            'referral',
            'referral.userDetail',
            'userDetail',
        ] )->select( 'users.*' );

        $filterObject = self::filter( $request, $user );
        $user = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $user->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $userCount = $user->count();

        $limit = $request->length;
        $offset = $request->start;

        $users = $user->skip( $offset )->take( $limit )->get();

        $users->append( [
            'encrypted_id',
        ] );

        $users->each( function( $u ) {

            if ( $u->userDetail ) {
                $u->userDetail->append( [
                    'photo_path',
                ] );
            }
        } );

        $user = User::select(
            DB::raw( 'COUNT(users.id) as total'
        ) );

        $filterObject = self::filter( $request, $user );
        $user = $filterObject['model'];
        $filter = $filterObject['filter'];

        $user = $user->first();

        $data = [
            'users' => $users,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $userCount : $user->total,
            'recordsTotal' => $filter ? User::count() : $userCount,
        ];

        return $data;
    }

    private static function filter( $request, $model ) {

        $filter = false;

        if ( !empty( $request->registered_date ) ) {
            if ( str_contains( $request->registered_date, 'to' ) ) {
                $dates = explode( ' to ', $request->registered_date );

                $startDate = explode( '-', $dates[0] );
                $start = Carbon::create( $startDate[0], $startDate[1], $startDate[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                
                $endDate = explode( '-', $dates[1] );
                $end = Carbon::create( $endDate[0], $endDate[1], $endDate[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'users.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->registered_date );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'users.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->user ) ) {
            $model->where( 'users.email', $request->user );
            $model->orWhereHas( 'userDetail', function( $query ) use ( $request ) {
                $query->where( 'user_details.fullname', $request->user );
            } );
            $filter = true;
        }

        if ( !empty( $request->phone_number ) ) {
            $model->where( function( $query ) use ( $request ) {
                $query->where( 'phone_number', $request->phone_number );
                $query->orWhere( DB::raw( "CONCAT( calling_code, phone_number )" ), 'LIKE', '%' . $request->phone_number );
            } );
            $filter = true;
        }

        if ( !empty( $request->referral ) ) {
            $model->whereHas( 'referral', function( $query ) use ( $request ) {
                $query->where( 'email', $request->referral );
                $query->orWhereHas( 'userDetail', function( $query ) use ( $request ) {
                    $query->where( 'user_details.fullname', $request->referral );
                } );
            } );
            $filter = true;
        }

        if ( !empty( $request->kyc_status ) ) {

            if ( $request->kyc_status == 1 ) {
                $model->doesntHave( 'kyc' );
            } else {
                $model->whereHas( 'kyc', function( $query ) use ( $request ) {
                    $query->where( 'status', $request->kyc_status );
                } );
            }
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneUser( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );
        
        $user = User::with( [
            'userDetail'
        ] )->find( $request->id );

        return $user;
    }

    public static function createUserAdmin( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            // 'username' => [ 'required', 'unique:users,username', 'alpha_dash', new CheckASCIICharacter ],
            'fullname' => [ 'required' ],
            'email' => [ 'required', 'unique:users,email', 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'phone_number' => [ 'required', 'digits_between:8,15', function( $attribute, $value, $fail ) use ( $request ) {
                
                if ( mb_substr( $value, 0, 1 ) == 0 ) {
                    $value = mb_substr( $value, 1 );
                }

                $exist = User::where( 'calling_code', $request->calling_code )
                    ->where( 'phone_number', $value )
                    ->first();

                if ( $exist ) {
                    $fail( __( 'validation.exists' ) );
                    return false;
                }
            } ],
            'invitation_code' => [ 'nullable', 'exists:users,invitation_code' ],
            'password' => [ 'required', Password::min( 8 ) ],
        ] );

        $attributeName = [
            'fullname' => __( 'user.fullname' ),
            'email' => __( 'user.email' ),
            'phone_number' => __( 'user.phone_number' ),
            'password' => __( 'user.password' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            $phoneNumber = $request->phone_number;
            if ( mb_substr( $phoneNumber, 0, 1 ) == 0 ) {
                $phoneNumber = mb_substr( $phoneNumber, 1 );
            }

            $createUserObject['user'] = [
                'country_id' => 136,
                'ranking_id' => 1,
                'username' => strtolower( $request->email ),
                'email' => strtolower( $request->email ),
                'calling_code' => $request->calling_code,
                'phone_number' => $phoneNumber,
                'password' => Hash::make( $request->password ),
                'invitation_code' => self::generateInvitationCode(),
                'status' => 10,
            ];

            $createUserObject['user_detail'] = [
                'fullname' => $request->fullname,
            ];

            $referral = User::where( 'invitation_code', $request->invitation_code )->first();

            if ( !$referral )  {
                $referral = User::where( 'email', 'support@jdgventures.com' )->first();
            }

            if ( $referral ) {
                $createUserObject['user']['referral_id'] = $referral->id;
                $createUserObject['user']['referral_structure'] = $referral->referral_structure . '|' . $referral->id;
            } else {
                $createUserObject['user']['referral_id'] = null;
                $createUserObject['user']['referral_structure'] = '-';
            }

            $createUser = self::create( $createUserObject );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.users' ) ) ] ),
        ] );
    }

    public static function updateUserAdmin( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            // 'username' => [ 'required', 'unique:users,username,' . $request->id, 'alpha_dash', new CheckASCIICharacter ],
            'fullname' => [ 'required' ],
            'email' => [ 'required', 'unique:users,email,' . $request->id, 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'phone_number' => [ 'required', 'digits_between:8,15', function( $attribute, $value, $fail ) use ( $request ) {
                
                $exist = User::where( 'calling_code', $request->calling_code )
                    ->where( 'phone_number', $value )
                    ->where( 'id', '!=', $request->id )
                    ->first();

                if ( $exist ) {
                    $fail( __( 'validation.exists' ) );
                    return false;
                }
            } ],
            'password' => [ 'nullable', Password::min( 8 ) ],
        ] );

        $attributeName = [
            'fullname' => __( 'user.fullname' ),
            'email' => __( 'user.email' ),
            'phone_number' => __( 'user.phone_number' ),
            'password' => __( 'user.password' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();    

        try {

            $updateUser = User::with( [
                'userDetail',
            ] )->lockForUpdate()
                ->find( $request->id );

            $updateUser->email = $request->email;
            $updateUser->phone_number = $request->phone_number;
            if ( !empty( $request->password ) ) {
                $updateUser->password = Hash::make( $request->password );
            }
            $updateUser->save();

            $updateUserDetail = UserDetail::where( 'user_id', $request->id )
                ->lockForUpdate()
                ->first();

            $updateUserDetail->fullname = $request->fullname;
            $updateUserDetail->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.users' ) ) ] ),
        ] );
    }

    public static function updateUserStatus( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'status' => 'required',
        ] );
        
        $validator->validate();

        try {

            $updateUser = User::lockForUpdate()->find( $request->id );
            $updateUser->status = $request->status;
            $updateUser->save();

            DB::commit();
            
            return response()->json( [
                'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.users' ) ) ] ),
            ] );

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }
    }

    public static function requestOtp( $request ) {

        $validator = Validator::make( $request->all(), [
            'request_type' => [ 'required', 'in:1,2' ],
        ] );

        $attributeName = [
            'request_type' => __( 'user.request_type' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        DB::beginTransaction();

        // First request
        if ( $request->request_type == 1 ) {

            $validator = Validator::make( $request->all(), [
                'calling_code' => [ 'required', 'in:+60' ],
                'phone_number' => [ 'required', 'digits_between:8,15', function( $attribute, $value, $fail ) use ( $request ) {

                    if ( mb_substr( $value, 0, 1 ) == 0 ) {
                        $value = mb_substr( $value, 1 );
                    }

                    $user = User::where( 'calling_code', $request->calling_code )
                        ->where( 'phone_number', $value )
                        ->first();

                    if ( $user ) {
                        $fail( __( 'validation.unique' ) );
                    }
                } ],
                'request_type' => [ 'required', 'in:1' ],
            ] );
    
            $attributeName = [
                'phone_number' => __( 'user.phone_number' ),
                'request_type' => __( 'user.request_type' ),
            ];
    
            foreach ( $attributeName as $key => $aName ) {
                $attributeName[$key] = strtolower( $aName );
            }
    
            $validator->setAttributeNames( $attributeName )->validate();
    
            $expireOn = Carbon::now()->addMinutes( '15' );
    
            try {

                // $createTmpUser = TmpUser::create( [
                //     'calling_code' => $request->calling_code,
                //     'phone_number' => $request->phone_number,
                //     'otp_code' => mt_rand( 100000, 999999 ),
                //     'status' => 1,
                //     'expire_on' => $expireOn,
                // ] );

                $createTmpUser = Helper::requestOtp( 'register', [
                    'calling_code' => $request->calling_code,
                    'phone_number' => $request->phone_number,
                ] );
    
                DB::commit();
    
                return response()->json( [
                    'message' => $request->calling_code . $request->phone_number,
                    'message_key' => 'request_otp_success',
                    'data' => [
                        'otp_code' => '#DEBUG - ' . $createTmpUser['otp_code'],
                        'tmp_user' => $createTmpUser['identifier'],
                    ]
                ] );
    
            } catch ( \Throwable $th ) {
    
                \DB::rollBack();
                abort( 500, $th->getMessage() . ' in line: ' . $th->getLine() );
            }
        } else { // Resend

            try {
                $request->merge( [
                    'tmp_user' => Crypt::decryptString( $request->tmp_user ),
                ] );
            } catch ( \Throwable $th ) {
                return response()->json( [
                    'message' => __( 'validation.header_message' ),
                    'errors' => [
                        'tmp_user' => [
                            __( 'user.invalid_otp' ),
                        ],
                    ]
                ], 422 );
            }

            $validator = Validator::make( $request->all(), [
                'tmp_user' => [ 'required', function( $attribute, $value, $fail ) {
    
                    $current = TmpUser::find( $value );
                    if ( !$current ) {
                        $fail( __( 'user.invalid_request' ) );
                        return false;
                    }
    
                    $exist = TmpUser::where( 'email', $current->email )->where( 'status', 1 )->count();
                    if ( $exist == 0 ) {
                        $fail( __( 'user.invalid_request' ) );
                        return false;
                    }
                } ],
            ] );

            $attributeName = [
                'tmp_user' => __( 'user.email' ),
            ];
    
            foreach ( $attributeName as $key => $aName ) {
                $attributeName[$key] = strtolower( $aName );
            }
    
            $validator->setAttributeNames( $attributeName )->validate();

            $date = new \DateTime( date( 'Y-m-d H:i:s' ) );
            $date->add( new \DateInterval( 'PT15M' ) );

            $updateTmpUser = TmpUser::find( $request->tmp_user );
            $updateTmpUser->otp_code = mt_rand( 100000, 999999 );
            $updateTmpUser->expire_on = $date->format( 'Y-m-d H:i:s' );
            $updateTmpUser->save();

            return response()->json( [
                'message' => $updateTmpUser->email,
                'message_key' => 'request_resend_otp_success',
                'data' => [
                    'otp_code' => '#DEBUG - ' . $updateTmpUser->otp_code,
                    'tmp_user' => Crypt::encryptString( $updateTmpUser->id ),
                ]
            ] );
        }
    }

    public static function createUser( $request ) {

        try {
            $request->merge( [
                'tmp_user' => Crypt::decryptString( $request->tmp_user ),
            ] );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'message' => __( 'validation.header_message' ),
                'errors' => [
                    'tmp_user' => [
                        __( 'user.invalid_otp' ),
                    ],
                ]
            ], 422 );
        }

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'otp_code' => [ 'required' ],
            'tmp_user' => [ 'required', function( $attribute, $value, $fail ) use ( $request, &$currentTmpUser ) {

                $currentTmpUser = TmpUser::lockForUpdate()->find( $value );

                if ( !$currentTmpUser ) {
                    $fail( __( 'user.invalid_otp' ) );
                    return false;
                }

                if ( $currentTmpUser->status != 1 ) {
                    $fail( __( 'user.invalid_otp' ) );
                    return false;
                }

                if ( $currentTmpUser->otp_code != $request->otp_code ) {
                    $fail( __( 'user.invalid_otp' ) );
                    return false;
                }
            } ],
            'email' => [ 'required', 'unique:users,email', 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'fullname' => [ 'required' ],
            'calling_code' => [ 'required', 'in:+60' ],
            'phone_number' => [ 'required', 'digits_between:8,15', function( $attribute, $value, $fail ) use ( $request ) {

                if ( mb_substr( $value, 0, 1 ) == 0 ) {
                    $value = mb_substr( $value, 1 );
                }

                $user = User::where( 'calling_code', $request->calling_code )
                    ->where( 'phone_number', $value )
                    ->first();

                if ( $user ) {
                    $fail( __( 'validation.unique' ) );
                }
            } ],
            'password' => [ 'required', Password::min( 8 ) ],
            'repeat_password' => [ 'required_with:password', function( $attribute, $value, $fail ) use ( $request ) {
                if ( !empty( $value ) ) {
                    if ( $value != $request->password ) {
                        $fail( __( 'user.repeat_password_not_match' ) );
                        return false;
                    }
                }
            } ],
            'invitation_code' => [ 'nullable', 'exists:users,invitation_code' ],
            'device_type' => [ 'required', 'in:1,2,3' ],
            'tnc' => [ 'required', function( $attribute, $value, $fail ) {
                if ( $value != 1 ) {
                    $fail( __( 'user.agree_to_tnc' ) );
                    return false;
                }
            } ],
        ] );

        $attributeName = [
            'email' => __( 'user.email' ),
            'fullname' => __( 'user.fullname' ),
            'phone_number' => __( 'user.phone_number' ),
            'password' => __( 'user.password' ),
            'repeat_password' => __( 'member.repeat_password' ),
            'invitation_code' => __( 'member.referral_code' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            $phoneNumber = $request->phone_number;
            if ( mb_substr( $phoneNumber, 0, 1 ) == 0 ) {
                $phoneNumber = mb_substr( $phoneNumber, 1 );
            }

            $createUserObject['user'] = [
                'country_id' => 136,
                'ranking_id' => 1,
                'username' => strtolower( $request->email ),
                'email' => strtolower( $request->email ),
                'calling_code' => $request->calling_code,
                'phone_number' => $phoneNumber,
                'password' => Hash::make( $request->password ),
                'invitation_code' => self::generateInvitationCode(),
                'status' => 10,
            ];

            $createUserObject['user_detail'] = [
                'fullname' => $request->fullname,
            ];

            $referral = User::where( 'invitation_code', $request->invitation_code )->first();

            if ( !$referral )  {
                $referral = User::where( 'email', 'support@jdgventures.com' )->first();
            }

            if ( $referral ) {
                $createUserObject['user']['referral_id'] = $referral->id;
                $createUserObject['user']['referral_structure'] = $referral->referral_structure . '|' . $referral->id;
            } else {
                $createUserObject['user']['referral_id'] = null;
                $createUserObject['user']['referral_structure'] = '-';
            }

            $createUser = self::create( $createUserObject );

            $currentTmpUser->status = 10;
            $currentTmpUser->save();

            // Register OneSignal
            if ( !empty( $request->register_token ) ) {
                self::registerOneSignal( $createUser->id, $request->device_type, $request->register_token );
            }

            DB::commit();

            return response()->json( [
                'message_key' => 'register_success',
                'data' => [],
            ] );

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }
    }

    public static function createToken( $request ) {

        $request->merge( [ 'account' => 'test' ] );

        $request->validate( [
            'username' => 'required',
            'password' => 'required',
            'account' => [ 'sometimes', function( $attribute, $value, $fail ) {

                $user = User::where( 'username', request( 'username' ) )->first();
                if ( !$user ) {
                    $fail( __( 'auth.failed' ) );
                    return 0;
                }

                if ( !Hash::check( request( 'password' ), $user->password ) ) {
                    $fail( __( 'auth.failed' ) );
                    return 0;
                }
            } ],
            'device_type' => 'required|in:1,2,3',
        ] );

        $user = User::where( 'username', $request->username )->first();

        // Register OneSignal
        if ( !empty( $request->register_token ) ) {
            self::registerOneSignal( $user->id, $request->device_type, $request->register_token );
        }

        return response()->json( [
            'message_key' => 'login_success',
            'data' => [
                'token' => $user->createToken( 'x_api' )->plainTextToken
            ],
        ] );
    }

    public static function createTokenSocial( $request ) {

        $request->validate( [
            'identifier' => [ 'required', function( $attribute, $value, $fail ) {
                $user = User::where( 'email', $value )->where( 'is_social_account', 0 )->first();
                if ( $user ) {
                    $fail( __( 'user.email_is_taken_not_social' ) );
                }
                $userSocial = UserSocial::where( 'identifier', $value )->first();
                if ( $userSocial ) {
                    if ( $userSocial->platform != request( 'platform' ) ) {
                        $fail( __( 'user.email_is_taken_different_platform' ) );
                    }
                }
            } ],
            'platform' => 'required|in:1,2',
            'device_type' => 'required|in:1,2,3',
        ] );

        $userSocial = UserSocial::where( 'identifier', $request->identifier )->firstOr( function() use ( $request )  {

            \DB::beginTransaction();

            try {
                $createUser = User::create( [
                    'username' => null,
                    'email' => $request->identifier,
                    'country_id' => 136,
                    'phone_number' => null,
                    'is_social_account' => 1,
                    'invitation_code' => self::generateInvitationCode(),
                    'referral_id' => null,
                    'referral_structure' => '-',
                ] );

                $createUserSocial = UserSocial::create( [
                    'platform' => request( 'platform' ),
                    'identifier' => request( 'identifier' ),
                    'uuid' => request( 'uuid' ),
                    'user_id' => $createUser->id,
                ] );
    
                return $createUserSocial;
    
            } catch ( \Throwable $th ) {
    
                \DB::rollBack();
                abort( 500, $th->getMessage() . ' in line: ' . $th->getLine() );
            }
        } );

        \DB::commit();

        $user = User::find( $userSocial->user_id );

        // Register OneSignal
        if ( !empty( $request->register_token ) ) {
            self::registerOneSignal( $user->id, $request->device_type, $request->register_token );
        }

        return response()->json( [ 'data' => $user, 'token' => $user->createToken( 'x_api' )->plainTextToken ] );
    }

    public static function currentUser() {

        $user = User::with( [
            'kyc',
            'kyc.userBank',
            'kyc.userBank.bank',
        ] )->find( auth()->user()->id );

        return $user;
    }

    public static function getUser( $request ) {

        $userID = auth()->user()->id;

        $user = User::find( $userID );

        if ( $user ) {
            $user->makeHidden( [
                'name',
                'email_verified_at',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'is_social_account',
                'birthday',
                'referral_id',
                'referral_structure',
                'status',
                'updated_at',
            ] );
        }

        return response()->json( [
            'message_key' => !$user ? 'get_user_failed' : 'get_user_success',
            'data' => $user,
        ] );
    }

    public static function updateUser( $request ) {

        $request->validate( [
            // 'country' => 'required|exists:countries,id',
            'username' => 'required|unique:users,username,' . auth()->user()->id,
            'email' => 'required|unique:users,email,' . auth()->user()->id . '|min:8',
            'phone_number' => [ 'required', function( $attribute, $value, $fail ) {
                // $user = User::where( 'country_id', request( 'country' ) )->where( 'phone_number', $value )->first();
                $user = User::where( 'phone_number', $value )->first();
                if ( $user ) {
                    if ( $user->id != auth()->user()->id ) {
                        $fail( __( 'validation.unique', [ 'attribute' => 'phone number' ] ) );
                    }
                }
            } ],
            'birthday' => 'required',
        ] );

        $updateUser = User::find( auth()->user()->id );
        // $updateUser->country_id = $request->country;
        $updateUser->username = $request->username;
        $updateUser->email = $request->email;
        $updateUser->phone_number = $request->phone_number;
        $updateUser->birthday = $request->birthday;

        if ( $updateUser->isDirty() ) {
            $updateUser->save();    
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.users' ) ) ] ),
            'data' => $updateUser,
        ] );
    }

    public static function updateUserPassword( $request ) {

        $request->validate( [
            'old_password' => [ 'required', 'min:8', function( $attribute, $value, $fail ) {
                if ( !Hash::check( $value, auth()->user()->password ) ) {
                    $fail( __( 'user.old_password_not_match' ) );
                }
            } ],
            'password' => 'required|min:8|confirmed',
        ] );

        $updateUser = User::find( auth()->user()->id );
        $updateUser->password = Hash::make( $request->password );
        $updateUser->save();

        return response()->json( [
            'message' => __( 'user.user_password_updated' ),
            'data' => '',
        ] );
    }

    public static function deleteUser( $request ) {

        $user = User::find( request()->user()->id );
        $user->delete();

        return response()->json( [
            'message' => __( 'user.user_deleted' ),
        ] );
    }

    public static function forgotPasswordOtp( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'email' => [ 'required', 'regex:/(.+)@(.+)\.(.+)/i' ],
        ] );

        $attributeName = [
            'email' => __( 'user.email' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            $data['otp_code'] = '';
            $data['identifier'] = '';

            $existingUser = User::where( 'email', $request->email )->first();
            if ( $existingUser ) {
                $data = Helper::requestOtp( 'forgot_password', [
                    'id' => $existingUser->id,
                    'calling_code' => $existingUser->calling_code,
                    'phone_number' => $existingUser->phone_number,
                ] );
                DB::commit();
            }

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message_key' => 'request_otp_success',
            'data' => [
                'otp_code' => $data['otp_code'],
                'identifier' => $data['identifier'],
            ],
        ] );
    }

    public static function verifyForgotPassword( $request ) {

        try {
            $request->merge( [
                'identifier' => Crypt::decryptString( $request->identifier ),
            ] );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'message' =>  __( 'user.invalid_otp' ),
            ], 500 );
        }

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'identifier' => [ 'required', function( $attribute, $value, $fail ) use ( $request, &$currentOtpAction ) {

                $currentOtpAction = OtpAction::lockForUpdate()
                    ->find( $value );

                if ( !$currentOtpAction ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( $currentOtpAction->status != 1 ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( $currentOtpAction->otp_code != $request->otp_code ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( Carbon::parse( $currentOtpAction->expire_on )->isPast() ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }
            } ],
        ] )->validate();

        return response()->json( [
            'message_key' => 'verify_success',
            'data' => [
                'redirect_url' => route( 'web.resetPassword' ) . '?token=' . Crypt::encryptString( $request->identifier ),
            ]
        ] );
    }

    public static function resetPassword( $request ) {

        DB::beginTransaction();

        try {
            $request->merge( [
                'identifier' => Crypt::decryptString( $request->identifier ),
            ] );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'message' =>  __( 'user.invalid_otp' ),
            ], 500 );
        }

        $validator = Validator::make( $request->all(), [
            'identifier' => [ 'required', function( $attribute, $value, $fail ) use ( $request, &$currentOtpAction ) {

                $currentOtpAction = OtpAction::lockForUpdate()
                    ->find( $value );

                if ( !$currentOtpAction ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( $currentOtpAction->status != 1 ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( Carbon::parse( $currentOtpAction->expire_on )->isPast() ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }
            } ],
            'password' => [ 'required', Password::min( 8 ) ],
            'repeat_password' => [ 'required_with:password', function( $attribute, $value, $fail ) use ( $request ) {
                if ( !empty( $value ) ) {
                    if ( $value != $request->password ) {
                        $fail( __( 'user.repeat_password_not_match' ) );
                        return false;
                    }
                }
            } ],
        ] );

        $attributeName = [
            'password' => __( 'user.password' ),
            'repeat_password' => __( 'member.repeat_password' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            $updateUser = User::find( $currentOtpAction->user_id );
            $updateUser->password = Hash::make( $request->password );
            $updateUser->save();

            $currentOtpAction->status = 10;
            $currentOtpAction->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message_key' => 'reset_success',
            'data' => [],
        ] );
    }

    public static function myTeam( $request ) {

        $user = auth()->user();

        $direct = DB::table( 'users' )->where( 'referral_id', $user->id )->pluck( 'id' );
        $group = DB::table( 'user_structures' )->where( 'referral_id', $user->id )->pluck( 'user_id' );
        
        $directSponsors = count( $direct );
        $groupMembers = count( $group );

        $directSponsorSales = $directSponsors ? DB::table( 'users' )->whereIn( 'id', $direct )->sum( 'capital' ) : 0;
        $groupMemberSales = $groupMembers ? DB::table( 'users' )->whereIn( 'id', $group )->sum( 'capital' ) : 0;

        return [
            'personal_sales' => $user->capital,
            'direct_sponsors' => $directSponsors,
            'direct_sponsor_sales' => $directSponsorSales,
            'group_members' => $groupMembers,
            'group_member_sales' => $groupMemberSales,
        ];
    }

    public static function myTeamData( $request ) {

        $user = User::where( 'email', $request->email )->first();
        if ( !$user ) {
            $user = auth()->user();
        }
        if ( $user->email != auth()->user()->email ) {
            $structure = explode( '|', $user->referral_structure );
            if ( !in_array( auth()->user()->id, $structure ) ) {
                $user = auth()->user();
            }
        }

        $direct = DB::table( 'users' )->where( 'referral_id', $user->id )->pluck( 'id' );
        $group = DB::table( 'user_structures' )->where( 'referral_id', $user->id )->pluck( 'user_id' );
        
        $directSponsors = count( $direct );
        $groupMembers = count( $group );

        $directSponsorSales = $directSponsors ? DB::table( 'users' )->whereIn( 'id', $direct )->sum( 'capital' ) : 0;
        $groupMemberSales = $groupMembers ? DB::table( 'users' )->whereIn( 'id', $group )->sum( 'capital' ) : 0;

        return [
            'email' => $user->email,
            'ranking_id' => $user->ranking_id,
            'personal_sales' => $user->capital,
            'direct_sponsor_sales' => $directSponsorSales,
            'group_members' => $groupMembers,
            'group_member_sales' => $groupMemberSales,
        ];
    }

    public static function myTeamAjax( $request ) {

        $searcher = [];

        if ( $request->id == '#' ) {
            if ( $request->email ) {
                $username = User::where( 'email', $request->email )->first();
                $username = $username ? $username->id : null;
            } else {
                $username = auth()->user()->id;
            }
        } else {
            $username = $request->id;
            
            if ( $request->email ) {
                $searcher = User::where( 'email', $request->email )->first();
            } else {
                $searcher = User::where( 'email', auth()->user()->email )->first();
            }
        }

        $user = User::where( 'id', $username )->first();
        if ( !$user ) {
            return [];
        }

        if ( !$searcher ) {
            $searcher = $user;
        }

        // Check is upline
        if ( $username != auth()->user()->id ) {
            $structure = explode( '|', $user->referral_structure );
            if ( !in_array( auth()->user()->id, $structure ) ) {
                return [];
            }
        }

        $downlines = UserStructure::with( [
            'user',
            'user.ranking',
            'user.downlines',
        ] )->where( 'referral_id', $user->id )
            ->where( 'level', 1 )->get();

        $data = [];

        foreach ( $downlines as $downline ) {

            $checkLevel = UserStructure::where( 'referral_id', $searcher->id )->where( 'user_id', $downline->user->id )->first();
            $personalSales = DB::table( 'users' )->where( 'id', $downline->user->id )->sum( 'capital' );            

            if ( count( $downline->user->downlines->pluck( 'id' ) ) == 0 ) {
                $directSales = 0;
            } else {
                $directSales = DB::table( 'users' )->whereIn( 'id', $downline->user->downlines->pluck( 'id' ) )->sum( 'capital' );
            }

            if ( count( $downline->user->groups->pluck( 'user_id' ) ) == 0 ) {
                $groupMember = 0;
                $groupSales = 0;
            } else {
                $groupMember = count( $downline->user->groups->pluck( 'user_id' ) );
                $groupSales = DB::table( 'users' )->whereIn( 'id', $downline->user->groups->pluck( 'user_id' ) )->sum( 'capital' );
            }

            $html = '';
            $html .= '
            <br>
            <div class="tree-view-box team_box rounded-b-lg show_downline bg-white  w-[90vw] md:w-[100vw] md:max-w-[calc(95vw-200px)] lg:max-w-[800px]">
                <div class="flex justify-between items-center gap-x-4 border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4 relative">
                    <div class="flex items-center gap-x-4">
                        <img src="' . asset( 'member/Rank/' . ( 1 == 1 ? 1 : $downline->user->package_id ) . '.png?v=' ) . '" alt="" class="w-[65px] md:w-[80px] h-auto mr-[0.5rem]">
                        <div>
                            <div class="flex gap-x-2">
                                <h4 class="text-[12px] md:text-[16px] font-bold text-[#1A1D56] mb-1">' . $downline->user->email . '</h4>
                            </div>
                            <div class="text-[10px] md:text-[12px] bg-[#1A1D56] rounded-md text-center py-0 text-white px-3 md:px-4 w-fit">' . $downline->user->ranking->name . '</div>
                        </div>
                    </div>'.
                    ( $groupMember >= 1 ? '<i class="icon-icon1 team_arrow text-[8px] mr-6"></i> ' : '' ).'
                </div>
                <div class="team_inner_2 flex justify-between px-0 md:px-6 py-4">
                    <div class="w-[30%] text-center">
                        <div class="team_value">' . Helper::numberFormat( $personalSales, 2, true ) . '</div>
                        <div class="team_label mb-[0.25rem]">' . __( 'member.my_package' ) . '</div>
                    </div>
                    <div class="w-[30%] text-center">
                        <div class="team_value">' . Helper::numberFormat( $directSales, 2, true ) . '</div>
                        <div class="team_label mb-[0.25rem]">' . __( 'member.direct_sales' ) . '</div>
                    </div>
                    <div class="w-[30%] text-center">
                        <div class="team_value">' . Helper::numberFormat( $groupSales, 2, true ) . '</div> 
                        <div class="team_label mb-[0.25rem]">' . __( 'member.group_sales' ) . '</div>
                    </div>
                </div>
                <div class="flex justify-between items-center px-3 md:px-6 py-2 md:py-4">
                    <p class="text-[10px] md:text-[12px] font-bold">' . __( 'member.gen', [ 'title' => str_replace( ' ', '', __( 'member.' . Helper::ordinal( $checkLevel->level ), [ 'number' => $checkLevel->level ] ) ) ] ) . '</p>
                    <div class="team_count block w-fit flex items-center gap-x-2 text-[12px] md:text-[14px]">' . __( 'member.group_member' ) . ': <span class="text-right font-bold">' . $groupMember . '</span><i class="icon-icon43 font-normal"></i></div>
                </div>
            </div>
            ';

            $data[] = [
                'id' => $downline->user->id,
                'name' => $downline->user->id,
                'text' => $html,
                'children' => count( $downline->user->downlines ) > 0,
            ];
        }
        
        return $data;
    }

    public function homeData() {

        $currentUser = User::with( [
            'ranking',
        ] )->find( auth()->user()->id );

        $directSponsors = DB::table( 'users' )->where( 'referral_id', $currentUser->id )->count();
        $groupMembers = DB::table( 'user_structures' )->where( 'referral_id', $currentUser->id )->count();

        $totalDirectBonus = UserWalletTransaction::where( 'user_id', $currentUser->id )
            ->where( 'transaction_type', 21 )
            ->where( 'status', 10 )
            ->sum( 'amount' );

        $totalManagementBonus = UserWalletTransaction::where( 'user_id', $currentUser->id )
            ->where( 'transaction_type', 22 )
            ->where( 'status', 10 )
            ->sum( 'amount' );

        $recentReferrals = User::with( [
            'userDetail'
        ] )->where( 'referral_id', $currentUser->id )
            ->orderBy( 'created_at', 'DESC' )
            ->limit( 5 )
            ->get();

        $recentReferrals->each( function( $rr ) {
            $rr->append( [
                'photo_path',
            ] );
        } );

        return [
            'current_user' => $currentUser,
            'direct_sponsors' => $directSponsors,
            'group_members' => $groupMembers,
            'total_direct_bonus' => $totalDirectBonus,
            'total_management_bonus' => $totalManagementBonus,
            'recent_referrals' => $recentReferrals,
        ];
    }

    // Share
    private static function create( $data ) {

        $data['user']['uniq'] = self::generateUniq();

        $createUser = User::create( $data['user'] );

        $data['user_detail']['user_id'] = $createUser->id;

        $createUserDetail = UserDetail::create( $data['user_detail'] );

        if ( $data['user']['referral_id'] ) {
            $referralArray = explode( '|', $data['user']['referral_structure'] );
            $referralLevel = count( $referralArray );
            for ( $i = $referralLevel - 1; $i >= 0; $i-- ) {
                if ( $referralArray[$i] != '-' ) {
                    UserStructure::create( [
                        'user_id' => $createUser->id,
                        'referral_id' => $referralArray[$i],
                        'level' => $referralLevel - $i
                    ] );
                }
            }
        }

        for ( $i = 1; $i <= 3; $i++ ) { 
            UserWallet::create( [
                'user_id' => $createUser->id,
                'type' => $i,
                'balance' => 0,
            ] );
        }

        return $createUser;
    }

    public static function import() {

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load( '/Applications/MAMP/htdocs/b2-jdg/public/storage/import/16dec CV.xlsx' );

        for ( $i = 0; $i < $spreadsheet->getSheetCount();  $i++ ) {

            $sheetData = $spreadsheet->getSheet( $i )->toArray();

            $sql = '';

            foreach ( $sheetData as $key => $row ) {

                if ( $key == 0 ) {
                    continue;
                }

                $user = UserOld::where( 'fname', strtolower( trim( $row[0] ) ) )->first();
                if ( !$user ) {
                    echo $row[0] . '<br>';
                    continue;
                }

                $tid1 = 'JDG-' . self::generate_string( 8 );
                $tid2 = 'JDG-' . self::generate_string( 8 );
                $today = '2023-12-16 00:01:00';
                $amount = $row[6];

                $sql .= "INSERT INTO wallet_trans (uid,trans_id,trans_amount,reg_date,trans_type,status) VALUES ($user->id,'$tid1',$amount,'$today',13,1);" . '<br>' ;
                $sql .= "INSERT INTO wallet_trans (uid,trans_id,trans_amount,reg_date,trans_type,status) VALUES ($user->id,'$tid2',$amount,'$today',14,1);" . '<br>' ;
                $sql .= "UPDATE wallet SET balance_amount = balance_amount - $amount WHERE uid = $user->id;" . '<br>';
            }
        }

        echo $sql;
    }

    public static function import2() {

        $sql = '';
        $mmbs = ManualManagementBonus::where( 'status', 0 )->get();
        foreach ( $mmbs as $mmb ) {
            $tid = 'JDG-' . self::generate_string( 8 );
            $today = '2023-12-05 09:00:00';
            $amount = $mmb->amount;
            $sql .= "INSERT INTO wallet_trans (uid,trans_id,trans_amount,reg_date,trans_type,status) VALUES ($mmb->user_id,'$tid',$amount,'$today',10,1);" . '<br>' ;
        }
        
        echo $sql;
    }

    private function registerOneSignal( $user_id, $device_type, $register_token ) {
        
        UserDeviceOneSignal::updateOrCreate( 
            [ 'user_id' => $user_id, 'device_type' => 1 ],
            [ 'register_token' => $register_token ]
        );
    }

    private static function generate_string( $strength = 16 ) {

        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input = $permitted_chars;
        $input_length = strlen( $input );
        $random_string = '';
        for ( $i = 0; $i < $strength; $i++ ) {
            $random_character = $input[mt_rand( 0, $input_length - 1 )];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    // Member Profile
    public static function getMemberProfile() {
        $userProfile = User::with( [
            'userDetail',
            'kyc',
            'referral',
            'referral.userDetail',
            'kyc.userBeneficiary',
            'kyc.userBank',
            'kyc.userBank.bank'
        ] )->find( auth()->user()->id );

        if( $userProfile ) {

            $userProfile->append([
                'encrypted_id',
            ] );

            if( $userProfile->userDetail ) {
                $userProfile->userDetail->append([
                    'photo_path',
                ] );
            }
        }

        return $userProfile;
    }

    public static function updateMemberProfile( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'email' => [ 'required', 'unique:users,email,' . auth()->user()->id, 'email', 'regex:/(.+)@(.+)\.(.+)/i', new CheckASCIICharacter ],
            'address' => [ 'required' ],
        ] );

        $attributeName = [
            'email' => __( 'auth.email' ),
            'address' => __( 'member.residential_address' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {
            
            $updateUserProfile = User::find( auth()->user()->id );
            $updateUserProfile->email = $request->email;
            $updateUserProfile->save();

            if( $updateUserProfile ) {
                $updateUserKyc = UserKyc::where( 'user_id', $updateUserProfile->id )->first();
                $updateUserKyc->address = $request->address;
                $updateUserKyc->save();
            }

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.users' ) ) ] ),
        ] );

    }

    public static function updateMemberSecuritySettings( $request ) {

        try {
            $request->merge( [
                'identifier' => Crypt::decryptString( $request->identifier ),
            ] );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'message' => __( 'validation.header_message' ),
                'errors' => [
                    'identifier' => [
                        __( 'member.invalid_otp' ),
                    ],
                ]
            ], 422 );
        }

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'identifier' => [ 'required', function( $attribute, $value, $fail ) use ( $request, &$currentOtpAction ) {

                $currentOtpAction = OtpAction::lockForUpdate()
                    ->find( $value );

                if ( !$currentOtpAction ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( $currentOtpAction->status != 1 ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( $currentOtpAction->otp_code != $request->otp_code ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( Carbon::parse( $currentOtpAction->expire_on )->isPast() ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }
            } ],
            // 'otp_code' => [ 'required' ],
            'old_password' => [ 'required', function( $attribute, $value, $fail ) {
                if ( !Hash::check( $value, auth()->user()->password ) ) {
                    $fail( __( 'user.x_does_not_match' ) );
                }
            } ],
            'password' => 'required|min:8|confirmed',
        ] );

        $attributeName = [
            'password' => __( 'member.new_password' ),
            'old_password' => __( 'member.old_password' ),
            'password_confirmation' => __( 'member.password' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {
            
            $updateUser = User::lockForUpdate()
                ->find( auth()->user()->id );

            $updateUser->password = Hash::make( $request->password );
            $updateUser->save();

            $currentOtpAction->status = 10;
            $currentOtpAction->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'member.x_updated', [ 'title' => Str::singular( __( 'member.security_settings' ) ) ] ),
            'data' => '',
        ] );
    }

    public static function updateMemberProfilePhoto( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'photo' => [ 'required' ],
        ] );

        $attributeName = [
            'photo' => __( 'member.profile_photo' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            $user = auth()->user();

            $file = FileManager::find( $request->photo );
            if ( $file ) {
                $fileName = explode( '/', $file->file );
                $fileExtention = pathinfo( $fileName[1] )['extension'];
                $target = 'user_details/' . $user->id . '/' . $fileName[1];
                Storage::disk( 'public' )->move( $file->file, $target );
                $file->status = 10;
                $file->save();
            }

            $updateUserDetail = UserDetail::updateOrCreate(
                [ 'user_id' => $user->id ],
                [ 'photo' => $target ]
            );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'member.profile_photo' ) ) ] ),
            'photo_path' => $updateUserDetail->getPhotoPathAttribute(),
        ] );

    }

    public static function requestOtpMemberProfile( $request ) {

        try {

            $data = Helper::requestOtp( $request->action );
           
            return response()->json( [
                'message' => $request->action,
                'message_key' => 'request_otp_success',
                'data' => $data,
            ] );

        } catch ( \Throwable $th ) {

            abort( 500, $th->getMessage() . ' in line: ' . $th->getLine() );
        }
    }

    public static function updateMemberBeneficiary( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'fullname' => [ 'nullable' ],
            'identification_number' => [ 'nullable' ],
            'phone_number' => [ 'nullable', 'digits_between:8,15' ],
        ] );

        $attributeName = [
            'fullname' => __( 'member.beneficiary_name' ),
            'identification_number' => __( 'member.beneficiary_ic' ),
            'phone_number' => __( 'member.beneficiary_contact_no' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {
            
            $user = User::has( 'kyc' )->find( auth()->user()->id );

            if( $user ) {

                $updateUserBeneficiary = UserBeneficiary::updateOrCreate(
                    [ 'user_id' => $user->id,
                       'user_kyc_id' => $user->kyc->id,
                    ],
                    [ 
                        'fullname' => $request->fullname, 
                        'identification_number' => $request->identification_number,
                        'phone_number' => $request->phone_number,
                    ]
                );
                
            }else{
                $fail( __( 'member.x_not_found', [ 'title' => Str::singular( __( 'member.member' ) ) ]  ) );
            }

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'member.x_updated', [ 'title' => Str::singular( __( 'template.beneficiary' ) ) ] ),
        ] );

    }
    
    public static function updateMemberBankAccount( $request ) {

        try {
            $request->merge( [
                'identifier' => Crypt::decryptString( $request->identifier ),
            ] );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'message' => __( 'validation.header_message' ),
                'errors' => [
                    'identifier' => [
                        __( 'member.invalid_otp' ),
                    ],
                ]
            ], 422 );
        }

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'bank' => [ 'required' ],
            'account_holder_name' => [ 'required' ],
            'account_number' => [ 'required' ],
            'identifier' => [ 'required', function( $attribute, $value, $fail ) use ( $request, &$currentOtpAction ) {

                $currentOtpAction = OtpAction::lockForUpdate()->find( $value );

                if ( !$currentOtpAction ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( $currentOtpAction->status != 1 ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( $currentOtpAction->otp_code != $request->otp_code ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }

                if ( Carbon::parse( $currentOtpAction->expire_on )->isPast() ) {
                    $fail( __( 'member.invalid_otp' ) );
                    return false;
                }
            } ],
            'otp_code' => [ 'required' ],
        ] );

        $attributeName = [
            'bank' => __( 'member.bank' ),
            'account_holder_name' => __( 'member.account_holder_name' ),
            'account_number' => __( 'member.account_number' ),
            'otp' => __( 'member.otp' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {
            
            $user = auth()->user();

            $updateUserBank = UserBank::where( 'user_id', $user->id )->first();
            $updateUserBank->bank_id = $request->bank;
            $updateUserBank->account_holder_name = $request->account_holder_name;
            $updateUserBank->account_number = $request->account_number;
            $updateUserBank->save();

            if( $updateUserBank ) {
                $currentOtpAction->status = 10;
                $currentOtpAction->save();
            }

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'member.x_updated', [ 'title' => Str::singular( __( 'template.beneficiary' ) ) ] ),
        ] );

    }

    public static function getReferral( $request ) {

        $referral = User::select( 'id' )->with( [
            'userDetail:user_id,fullname',
        ] )->where( 'invitation_code', $request->referral_code )
            ->first();

        return [
            'data' => $referral,
        ];
    }

    private static function generateInvitationCode() {

        $invitationCode = '';

        while( empty( $invitationCode) ) {

            $checkExist = strtoupper( Str::random( 6 ) );

            if ( !User::where( 'invitation_code', $checkExist )->first() ) {
                $invitationCode = $checkExist;
            }
        }
        
        return $invitationCode;
    }

    public static function generateUniq() {

        $uniq = '';

        while( empty( $uniq) ) {

            $checkExist = 'JDG-' . strtoupper( Str::random( 8 ) );

            if ( !User::where( 'uniq', $checkExist )->first() ) {
                $uniq = $checkExist;
            }
        }
        
        return $uniq;
    }
}