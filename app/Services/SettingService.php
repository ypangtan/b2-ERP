<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
    DB,
    Validator,
};

use App\Models\{
    Option,
    Maintenance,
};

use PragmaRX\Google2FAQRCode\Google2FA;

class SettingService {

    public static function settings() {

        $settings = Option::whereIn( 'option_name', [
            'DBD_BANK',
            'DBD_ACCOUNT_HOLDER',
            'DBD_ACCOUNT_NO',
            'WD_SERVICE_CHARGE_TYPE',
            'WD_SERVICE_CHARGE_RATE',
        ] )->get();

        return $settings;
    }

    public static function maintenanceSettings() {

        $maintenance = Maintenance::where( 'type', 3 )->first();

        return $maintenance;
    }

    public static function updateDepositBankDetail( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'bank' => [ 'required' ],
            'account_holder' => [ 'required' ],
            'account_no' => [ 'required' ],
        ] );

        $attributeName = [
            'bank' => __( 'setting.bank' ),
            'account_holder' => __( 'setting.account_holder' ),
            'account_no' => __( 'setting.account_no' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            Option::lockForUpdate()->updateOrCreate( [
                'option_name' => 'DBD_BANK'
            ], [
                'option_value' => $request->bank,
            ] );
    
            Option::lockForUpdate()->updateOrCreate( [
                'option_name' => 'DBD_ACCOUNT_HOLDER'
            ], [
                'option_value' => $request->account_holder,
            ] );
    
            Option::lockForUpdate()->updateOrCreate( [
                'option_name' => 'DBD_ACCOUNT_NO'
            ], [
                'option_value' => $request->account_no,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.settings' ) ) ] ),
        ] );
    }

    public static function updateWithdrawalSetting( $request ) {

        DB::beginTransaction();

        $validator = Validator::make( $request->all(), [
            'service_charge_type' => [ 'required' ],
            'service_charge_rate' => [ 'required', 'numeric', 'gte:0' ],
        ] );

        $attributeName = [
            'service_charge_type' => __( 'withdrawal.service_charge_type' ),
            'service_charge_rate' => __( 'withdrawal.service_charge_rate' ),
        ];

        foreach ( $attributeName as $key => $aName ) {
            $attributeName[$key] = strtolower( $aName );
        }

        $validator->setAttributeNames( $attributeName )->validate();

        try {

            Option::lockForUpdate()->updateOrCreate( [
                'option_name' => 'WD_SERVICE_CHARGE_TYPE'
            ], [
                'option_value' => $request->service_charge_type,
            ] );
    
            Option::lockForUpdate()->updateOrCreate( [
                'option_name' => 'WD_SERVICE_CHARGE_RATE'
            ], [
                'option_value' => $request->service_charge_rate,
            ] );

            DB::commit();

        } catch ( \Throwable $th ) {

            DB::rollback();

            return response()->json( [
                'message' => $th->getMessage() . ' in line: ' . $th->getLine(),
            ], 500 );
        }

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.settings' ) ) ] ),
        ] );
    }

    public static function updateMaintenanceSetting( $request ) {

        Maintenance::lockForUpdate()->updateOrCreate( [
            'type' => 3
        ], [
            'status' => $request->status,
        ] );

        return response()->json( [
            'message' => __( 'template.x_updated', [ 'title' => Str::singular( __( 'template.settings' ) ) ] ),
        ] );
    }

    public static function depositSettings() {

        $settings = Option::whereIn( 'option_name', [
            'DBD_BANK',
            'DBD_ACCOUNT_HOLDER',
            'DBD_ACCOUNT_NO',
        ] )->get();

        $data['dbd_bank'] = $settings->where( 'option_name', 'DBD_BANK' )->first();
        $data['dbd_account_holder'] = $settings->where( 'option_name', 'DBD_ACCOUNT_HOLDER' )->first();
        $data['dbd_account_no'] = $settings->where( 'option_name', 'DBD_ACCOUNT_NO' )->first();

        $data['dbd_bank'] = $data['dbd_bank'] ? $data['dbd_bank']->option_value : '-';
        $data['dbd_account_holder'] = $data['dbd_account_holder'] ? $data['dbd_account_holder']->option_value : '-';
        $data['dbd_account_no'] = $data['dbd_account_no'] ? $data['dbd_account_no']->option_value : '-';

        return $data;
    }

    public static function withdrawalSettings() {

        $settings = Option::whereIn( 'option_name', [
            'WD_SERVICE_CHARGE_TYPE',
            'WD_SERVICE_CHARGE_RATE',
        ] )->get();

        $data['wd_service_charge_type'] = $settings->where( 'option_name', 'WD_SERVICE_CHARGE_TYPE' )->first();
        $data['wd_service_charge_rate'] = $settings->where( 'option_name', 'WD_SERVICE_CHARGE_RATE' )->first();

        $data['wd_service_charge_type'] = $data['wd_service_charge_type'] ? $data['wd_service_charge_type']->option_value : 1;
        $data['wd_service_charge_rate'] = $data['wd_service_charge_rate'] ? $data['wd_service_charge_rate']->option_value : 0;

        return $data;
    }
}