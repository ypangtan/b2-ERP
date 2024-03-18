<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
};

use App\Models\{
    Bank,
    Country,
    ProfileOld,
    User,
    UserBank,
    UserBeneficiary,
    UserDetail,
    UserKyc,
    UserKycDocument,
    UserOld,
    UserWallet,
};

use Carbon\Carbon;

class MigrateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate user from old platform';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        activity()->disableLogging();

        $banks = Bank::get();

        $countries = Country::get();

        $userOlds = UserOld::where( 'groupid', 4 )->get();

        $this->info( 'Total User: ' . count( $userOlds ) . PHP_EOL );

        $this->output->progressStart( count( $userOlds ) );

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ( $userOlds as $userOld ) {

            $profileOld = ProfileOld::where( 'uid', $userOld->id )->first();

            $newUser = new User();
            $newUserArray = [
                'country_id' => 136,
                'id' => $userOld->id,
                'old_id' => $userOld->id,
                'uniq' => strtoupper( 'JDG-' . $userOld->uniq ),
                'email' => strtolower( $userOld->email ),
                'calling_code' => '+60',
                'phone_number' => $userOld->phone,
                'password' => $userOld->password,
                'invitation_code' => strtoupper( Str::random( 6 ) ),
                'referral_id' => $userOld->refid,
                'referral_structure' => '-',
                'created_at' => Carbon::createFromFormat( 'Y-m-d H:i:s', $userOld->reg_date, 'Asia/Kuala_Lumpur' )->timezone( 'UTC' )->format( 'Y-m-d H:i:s' ),
                'updated_at' => Carbon::createFromFormat( 'Y-m-d H:i:s', $userOld->reg_date, 'Asia/Kuala_Lumpur' )->timezone( 'UTC' )->format( 'Y-m-d H:i:s' ),
            ];

            if ( $userOld->id != 397 && $userOld->refid == null ) {
                $newUserArray['referral_id'] = 397;
            }

            if ( $profileOld ) {
                $newUserArray['ranking_id'] = empty( $profileOld->rankid ) ? 1 : ( $profileOld->rankid + 1 );
            } else {
                $newUserArray['ranking_id'] = 1;
            }

            $newUser->forceFill( $newUserArray );
            $newUser->save();

            $remove = 'https://jdgventures.com/dashboard/panel/uploads/';

            UserDetail::create( [
                'user_id' => $newUser->id,
                'fullname' => $userOld->fname,
                'photo' => $userOld->img ? str_replace( $remove, '', $userOld->img ) : null,
            ] );

            if ( $profileOld ) {

                $settings = json_decode( $profileOld->settings );

                $createUserKyc = new UserKyc();
                $createUserKycArray = [
                    'user_id' => $newUser->id,
                    'nationality_id' => $countries->where( 'iso_alpha2_code', strtoupper( $settings->nationality_type ) )->first()->id,
                    'fullname' => $settings->full_name_personalinfo,
                    'identification_number' => $settings->ic_no_personalinfo,
                    'date_of_birth' => $settings->dob_personalinfo_view,
                    'address' => $settings->residential_address_personalinfo,
                    'status' => $profileOld->status == 1 ? 2 : 10,
                    'created_at' => Carbon::createFromFormat( 'Y-m-d H:i:s', $profileOld->update_time, 'Asia/Kuala_Lumpur' )->timezone( 'UTC' )->format( 'Y-m-d H:i:s' ),
                    'updated_at' => Carbon::createFromFormat( 'Y-m-d H:i:s', $profileOld->update_time, 'Asia/Kuala_Lumpur' )->timezone( 'UTC' )->format( 'Y-m-d H:i:s' ),
                ];
                $createUserKyc->forceFill( $createUserKycArray );
                $createUserKyc->save();

                UserKycDocument::create( [
                    'user_id' => $newUser->id,
                    'user_kyc_id' => $createUserKyc->id,
                    'file' => $settings->ic_front ? str_replace( $remove, '', $settings->ic_front ) : null,
                    'document_type' => 1,
                ] );

                UserKycDocument::create( [
                    'user_id' => $newUser->id,
                    'user_kyc_id' => $createUserKyc->id,
                    'file' => $settings->ic_back ? str_replace( $remove, '', $settings->ic_back ) : null,
                    'document_type' => 2,
                ] );

                UserBank::create( [
                    'user_id' => $newUser->id,
                    'user_kyc_id' => $createUserKyc->id,
                    'bank_id' => $banks->where( 'key', $settings->bank_specify_bankinfo )->first()->id,
                    'account_holder_name' => $settings->bank_acc_holder_bankinfo,
                    'account_number' => $settings->bank_acc_no_bankinfo,
                ] );

                UserBeneficiary::create( [
                    'user_id' => $newUser->id,
                    'user_kyc_id' => $createUserKyc->id,
                    'fullname' => isset( $settings->beneficiary_name_personalinfo ) ? $settings->beneficiary_name_personalinfo : null,
                    'identification_number' => isset( $settings->beneficiary_ic_personalinfo ) ? $settings->beneficiary_ic_personalinfo : null,
                    'phone_number' => isset( $settings->beneficiary_contact_personalinfo ) ? $settings->beneficiary_contact_personalinfo : null,
                ] );
            }

            for ( $i = 1; $i <= 3; $i++ ) { 
                UserWallet::create( [
                    'user_id' => $newUser->id,
                    'type' => $i,
                    'balance' => 0,
                ] );
            }

            $this->output->progressAdvance();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->output->progressFinish();

        $this->info( 'Migration Completed!' );

        return 0;
    }
}
