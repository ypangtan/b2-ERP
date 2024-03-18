<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
    Storage,
};

use App\Models\{
    FileManager,
    User,
    UserBank,
    UserBeneficiary,
    UserKyc,
    UserKycDocument,
};

use Helper;

use Carbon\Carbon;

class UserKycService {

    public static function allUserKycs( $request ) {

        $userKyc = UserKyc::with( [
            'nationality',
            'user',
            'user.userDetail',
            'userBank',
            'userBank.bank',
        ] )->select( 'user_kycs.*' );

        $filterObject = self::filter( $request, $userKyc );
        $userKyc = $filterObject['model'];
        $filter = $filterObject['filter'];

        if ( $request->input( 'order.0.column' ) != 0 ) {
            $dir = $request->input( 'order.0.dir' );
            switch ( $request->input( 'order.0.column' ) ) {
                case 1:
                    $userKyc->orderBy( 'created_at', $dir );
                    break;
            }
        }

        $userKycCount = $userKyc->count();

        $limit = $request->length;
        $offset = $request->start;

        $userKycs = $userKyc->skip( $offset )->take( $limit )->get();

        $userKycs->append( [
            'encrypted_id',
        ] );

        $userKyc = UserKyc::select(
            DB::raw( 'COUNT(user_kycs.id) as total'
        ) );

        $filterObject = self::filter( $request, $userKyc );
        $userKyc = $filterObject['model'];
        $filter = $filterObject['filter'];

        $userKyc = $userKyc->first();

        $data = [
            'user_kycs' => $userKycs,
            'draw' => $request->draw,
            'recordsFiltered' => $filter ? $userKycCount : $userKyc->total,
            'recordsTotal' => $filter ? UserKyc::count() : $userKycCount,
        ];

        return $data;
    }

    private static function filter( $request, $model ) {

        $filter = false;

        if ( !empty( $request->submission_date ) ) {
            if ( str_contains( $request->submission_date, 'to' ) ) {
                $dates = explode( ' to ', $request->submission_date );

                $startDate = explode( '-', $dates[0] );
                $start = Carbon::create( $startDate[0], $startDate[1], $startDate[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                
                $endDate = explode( '-', $dates[1] );
                $end = Carbon::create( $endDate[0], $endDate[1], $endDate[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'user_kycs.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            } else {

                $dates = explode( '-', $request->submission_date );

                $start = Carbon::create( $dates[0], $dates[1], $dates[2], 0, 0, 0, 'Asia/Kuala_Lumpur' );
                $end = Carbon::create( $dates[0], $dates[1], $dates[2], 23, 59, 59, 'Asia/Kuala_Lumpur' );

                $model->whereBetween( 'user_kycs.created_at', [ date( 'Y-m-d H:i:s', $start->timestamp ), date( 'Y-m-d H:i:s', $end->timestamp ) ] );
            }
            $filter = true;
        }

        if ( !empty( $request->user ) ) {
            $model->whereHas( 'user', function( $query ) use ( $request ) {
                $query->where( 'users.email', $request->user );
            } );
            $model->orWhereHas( 'user.userDetail', function( $query ) use ( $request ) {
                $query->where( 'user_details.fullname', $request->user );
            } );
            $filter = true;
        }

        if ( !empty( $request->status ) ) {
            $model->where( 'user_kycs.status', $request->status );
            $filter = true;
        }

        return [
            'filter' => $filter,
            'model' => $model,
        ];
    }

    public static function oneUserKyc( $request ) {

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $userKyc = UserKyc::with( [
            'nationality',
            'user',
            'user.userDetail',
            'userBank',
            'userBank.bank',
            'userKycDocuments',
        ] )->find( $request->id );

        if ( $userKyc ) {
            $userKyc->append( [
                'encrypted_id',
            ] );

            $userKyc->userKycDocuments->each( function( $query ) {
                $query->append( [
                    'path',
                ] );
            } );
        }

        return $userKyc;
    }

    public static function updateUserKycAdmin( $request ) {

        DB::beginTransaction();

        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        $validator = Validator::make( $request->all(), [
            'kyc_status' => [ 'required' ],
            'remarks' => [ 'nullable' ],
        ] );

        $attributeName = [
            'kyc_status' => __( 'kyc.kyc_status' ),
            'remarks' => __( 'kyc.remarks' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();    

        try {

            $updateUserKyc = UserKyc::find( $request->id );

            if ( $request->kyc_status == 10 ) { // Approve
                $updateUserKyc->approved_by = auth()->user()->id;
                $updateUserKyc->approved_at = Carbon::now();
            } else if ( $request->status == 20 ) { // Reject
                $updateUserKyc->rejected_by = auth()->user()->id;
                $updateUserKyc->rejected_at = Carbon::now();
            }
            
            $updateUserKyc->status = $request->kyc_status;
            $updateUserKyc->remarks = $request->remarks;
            $updateUserKyc->save();

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.user_kycs' ) ) ] ),
        ] );
    }

    public static function getMemberKyc( $request ) {

        $userKyc = UserKyc::with(
            'user',
            'user.userDetail',
            'userBank',
            'userBank.Bank',
            'userKycDocuments',
            'userBeneficiary',
        )
        ->where( 'user_id', auth()->user()->id )->latest()->first();
        
        if ( $userKyc ) {

            $userKyc->append( [
                'encrypted_id',
            ] );

            if ( $userKyc->userKycDocuments ) {
                $userKyc->userKycDocuments->append( [
                    'path',
                ] );
            }
        }

        return $userKyc;
    }


    public static function createUserKyc( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'fullname' => [ 'required' ],
            'identification_number' => [ 'required' ],
            'date_of_birth' => [ 'required' ],
            'address' => [ 'required' ],
            'beneficiary_fullname' => [ 'nullable' ],
            'beneficiary_identification_number' => [ 'nullable' ],
            'contact_number' => [ 'nullable' , 'digits_between:8,15' ],
            'bank' => [ 'required' ],
            'account_holder_name' => [ 'required' ],
            'account_number' => [ 'required' ],
            'ic_front' => [ 'required' ],
            // 'ic_back' => [ 'required' ],
        ] );

        $attributeName = [
            'fullname' => __( 'user_kyc.fullname' ),
            'identification_number' => __( 'user_kyc.identification_number' ),
            'date_of_birth' => __( 'user_kyc.date_of_birth' ),
            'address' => __( 'user_kyc.address' ),
            'beneficiary_fullname' => __( 'user_kyc.beneficiary_fullname' ),
            'beneficiary_identification_number' => __( 'user_kyc.beneficiary_identification_number' ),
            'contact_number' => __( 'user_kyc.contact_number' ),
            'bank' => __( 'user_kyc.bank' ),
            'account_holder_name' => __( 'user_kyc.account_holder_name' ),
            'account_number' => __( 'user_kyc.account_number' ),
            'ic_front' => __( 'user_kyc.ic_front' ),
            // 'ic_back' => __( 'user_kyc.ic_back' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }
        
        $validator->setAttributeNames( $attributeName )->validate();

        $checkKycExist = UserKyc::where( 'user_id', auth()->user()->id )->first();

        if( $checkKycExist ) {
            return response()->json( [
                'message' => __( 'user_kyc.kyc_exist' )
            ], 500 );
        }

        try {

            $createUserKycObject['kyc'] = [
                'user_id' => auth()->user()->id,
                'fullname' => $request->fullname,
                'identification_number' => $request->identification_number,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'status' => 2,
            ];

            $createUserKyc = UserKyc::create( $createUserKycObject['kyc'] );
     
            if ( $createUserKyc ) {

                $createUserKycObject['bank'] = [
                    'user_id' => auth()->user()->id,
                    'user_kyc_id' => $createUserKyc->id,
                    'bank_id' => $request->bank,
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                    'status' => 10,
                ];
    
                $createUserKycObject['beneficiary'] = [
                    'user_id' => auth()->user()->id,
                    'user_kyc_id' => $createUserKyc->id,
                    'fullname' => $request->beneficiary_fullname,
                    'identification_number' => $request->beneficiary_identification_number,
                    'phone_number' => $request->contact_number,
                    'status' => 10,
                ];

                if ($request->ic_front) {
                    $createUserKycObject['kyc_document'] = self::processFile($request->ic_front, $createUserKyc);
                    $createUserKycObject['kyc_document']['document_type'] = 1;
                    UserKycDocument::create( $createUserKycObject['kyc_document'] );
                }
                
                // if ($request->ic_back) {
                //     $createUserKycObject['kyc_document'] = self::processFile($request->ic_back, $createUserKyc);
                //     $createUserKycObject['kyc_document']['document_type'] = 2;
                //     UserKycDocument::create( $createUserKycObject['kyc_document'] );
                // }            

                UserBank::create( $createUserKycObject['bank'] );
                UserBeneficiary::create( $createUserKycObject['beneficiary'] );
                
            }

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.user_kycs' ) ) ] ),
        ] );
    }

    public static function updateUserKyc( $request ) {
      
        $request->merge( [
            'id' => Helper::decode( $request->id ),
        ] );

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'id' => [ 'required' ],
            'fullname' => [ 'required' ],
            'identification_number' => [ 'required' ],
            'date_of_birth' => [ 'required' ],
            'address' => [ 'required' ],
            'beneficiary_fullname' => [ 'nullable' ],
            'beneficiary_identification_number' => [ 'nullable' ],
            'contact_number' => [ 'nullable' , 'digits_between:8,15' ],
            'bank' => [ 'required' ],
            'account_holder_name' => [ 'required' ],
            'account_number' => [ 'required' ],
            'ic_front' => [ 'required' ],
            // 'ic_back' => [ 'required' ],
        ] );

        $attributeName = [
            'fullname' => __( 'user_kyc.fullname' ),
            'identification_number' => __( 'user_kyc.identification_number' ),
            'date_of_birth' => __( 'user_kyc.date_of_birth' ),
            'address' => __( 'user_kyc.address' ),
            'beneficiary_fullname' => __( 'user_kyc.beneficiary_fullname' ),
            'beneficiary_identification_number' => __( 'user_kyc.beneficiary_identification_number' ),
            'contact_number' => __( 'user_kyc.contact_number' ),
            'bank' => __( 'user_kyc.bank' ),
            'account_holder_name' => __( 'user_kyc.account_holder_name' ),
            'account_number' => __( 'user_kyc.account_number' ),
            'ic_front' => __( 'user_kyc.ic_front' ),
            // 'ic_back' => __( 'user_kyc.ic_back' ),
        ];

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {
            
            $updateUserKyc = UserKyc::find( $request->id );
            $updateUserKyc->fullname = $request->fullname; 
            $updateUserKyc->identification_number = $request->identification_number; 
            $updateUserKyc->date_of_birth = $request->date_of_birth; 
            $updateUserKyc->address = $request->address;
            $updateUserKyc->status = 2;
            $updateUserKyc->save();
     
            if ( $updateUserKyc ) {

                $updateUserKycBeneficiary = UserBeneficiary::where( 'user_kyc_id', $updateUserKyc->id )->first();
                
                if( $updateUserKycBeneficiary ) {
                    $updateUserKycBeneficiary->fullname = $request->beneficiary_fullname ?? ''; 
                    $updateUserKycBeneficiary->identification_number = $request->beneficiary_identification_number ?? ''; 
                    $updateUserKycBeneficiary->phone_number = $request->contact_number ?? ''; 
                    $updateUserKycBeneficiary->save();
                }

                $updateUserKycBank = UserBank::where( 'user_kyc_id', $updateUserKyc->id )->first();
                $updateUserKycBank->bank_id = $request->bank; 
                $updateUserKycBank->account_holder_name = $request->account_holder_name; 
                $updateUserKycBank->account_number = $request->account_number; 
                $updateUserKycBank->save();

                if ( $request->ic_front ) {
                    $file = FileManager::find( $request->ic_front );
                    if ( $file ) {

                        $updateUserKycIcFront = UserKycDocument::where( 'user_kyc_id', $updateUserKyc->id )->where( 'document_type', 1 )->first();

                        Storage::disk( 'public' )->delete( $updateUserKycIcFront->file );
                        
                        $updateUserKycDocument['kyc_document'] = self::processFile( $request->ic_front, $updateUserKyc );
                        $updateUserKycIcFront->file = $updateUserKycDocument['kyc_document']['file'];
                        $updateUserKycIcFront->file_extension = $updateUserKycDocument['kyc_document']['file_extension'];
                        $updateUserKycIcFront->save();
        
                        $file->status = 10;
                        $file->save();
                    }
                }

                // if ( $request->ic_back ) {
                //     $file = FileManager::find( $request->ic_back );
                //     if ( $file ) {
                        
                //         $updateUserKycIcBack = UserKycDocument::where( 'user_kyc_id', $updateUserKyc->id )->where( 'document_type', 2 )->first();

                //         Storage::disk( 'public' )->delete( $updateUserKycIcBack->file );
                              
                //         $updateUserKycDocument['kyc_document'] = self::processFile( $request->ic_back, $updateUserKyc );
                //         $updateUserKycIcBack->file = $updateUserKycDocument['kyc_document']['file'];
                //         $updateUserKycIcBack->file_extension = $updateUserKycDocument['kyc_document']['file_extension'];
                //         $updateUserKycIcBack->save();
        
                //         $file->status = 10;
                //         $file->save();
                //     }
                // }
                
            }

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollBack();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine()
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.new_x_created', [ 'title' => Str::singular( __( 'template.user_kycs' ) ) ] ),
        ] );
    }

    public static function processFile( $fileId, $createUserKyc ) {
        $target = '';
        $file = FileManager::find( $fileId );
        if ( $file ) {
            $fileName = explode( '/', $file->file );
            $fileExtention = pathinfo($fileName[1])['extension'];
            $target = 'user_kyc_documents/' . $createUserKyc->id . '/' . $fileName[1];
            Storage::disk( 'public' )->move( $file->file, $target );
            $file->status = 10;
            $file->save();
        }

        $kycDocument = [
            'user_id' => auth()->user()->id,
            'user_kyc_id' => $createUserKyc->id,
            'file' => $target,
            'file_extension' => $fileExtention,
            'status' => 10,
        ];
    
        return $kycDocument;
    }

    public static function userKycValidate( $request ) {

        $rules = [
            'fullname' => [ 'required' ],
            'identification_number' => [ 'required' ],
            'date_of_birth' => [ 'required', function( $attribute, $value, $fail ) use ( $request ) {
                
                $inputDate = Carbon::createFromFormat( 'Y-m-d', $value );
                $eighteenYearsAgo = Carbon::now();

                if ( $eighteenYearsAgo->diffInYears( $inputDate ) < 18) {
                    $fail( __( 'user_kyc.date_of_birth_restriction_note' ) );
                    return false;
                }

            } ],
            'address' => [ 'required' ],
            'beneficiary_fullname' => [ 'nullable' ],
            'beneficiary_identification_number' => [ 'nullable' ],
            'contact_number' => [ 'nullable' , 'digits_between:8,15' ],
        ];

        $attributeName = [
            'fullname' => __( 'user_kyc.fullname' ),
            'identification_number' => __( 'user_kyc.identification_number' ),
            'date_of_birth' => __( 'user_kyc.date_of_birth' ),
            'address' => __( 'user_kyc.address' ),
            'beneficiary_fullname' => __( 'user_kyc.beneficiary_fullname' ),
            'beneficiary_identification_number' => __( 'user_kyc.beneficiary_identification_number' ),
            'contact_number' => __( 'user_kyc.contact_number' ),
            'bank' => __( 'user_kyc.bank' ),
            'account_holder_name' => __( 'user_kyc.account_holder_name' ),
            'account_number' => __( 'user_kyc.account_number' ),
            'ic_front' => __( 'user_kyc.ic_front' ),
            // 'ic_back' => __( 'user_kyc.ic_back' ),
        ];

        $page = $request->page;

        if ( $page !== '1' ) {

            $additionalRules = [
                'bank' => [ 'required' ],
                'account_holder_name' => [ 'required' ],
                'account_number' => [ 'required' ],
                'ic_front' => [ 'required' ],
                // 'ic_back' => [ 'required' ],
            ];

            $rules = array_merge( $rules, $additionalRules );
        }

        $validator = Validator::make( $request->all(), $rules );

        foreach( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();
    }
}