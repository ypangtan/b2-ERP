<?php
$setting = 'setting';
$banks = $data['banks'];
?>

<div class="card">
    <div class="card-body">
        <div class="row gy-3">
            <div class="col-md-2">                
                <div class="list-group" role="tablist">
                    <a class="list-group-item list-group-item-action active" data-bs-toggle="list" href="#dbd" role="tab">{{ __( 'setting.deposit_bank_details' ) }}</a>
                    <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#ws" role="tab">{{ __( 'setting.withdrawal_settings' ) }}</a>
                    <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#ms" role="tab">{{ __( 'setting.maintenance_settings' ) }}</a>
                </div>
            </div>
            <div class="col-md-10">
                <div class="tab-content p-2">
                    <div class="tab-pane fade show active" id="dbd" role="tabpanel">
                        <h5 class="card-title mb-0">{{ __( 'setting.deposit_bank_details' ) }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3 row">
                                    <label for="{{ $setting }}_bank" class="col-sm-5 col-form-label">{{ __( 'setting.bank' ) }}</label>
                                    <div class="col-sm-7">
                                        <select class="form-select form-select-sm" id="{{ $setting }}_bank">
                                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'setting.bank' ) ] ) }}</option>
                                            @foreach ( $banks as $bank )
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="{{ $setting }}_account_holder" class="col-sm-5 col-form-label">{{ __( 'setting.account_holder' ) }}</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" id="{{ $setting }}_account_holder">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="{{ $setting }}_account_no" class="col-sm-5 col-form-label">{{ __( 'setting.account_no' ) }}</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" id="{{ $setting }}_account_no">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-sm btn-primary" id="dbd_save">{{ __( 'template.save_changes' ) }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ws" role="tabpanel">
                        <h5 class="card-title mb-0">{{ __( 'setting.withdrawal_settings' ) }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3 row">
                                    <label for="{{ $setting }}_service_charge_type" class="col-sm-5 col-form-label">{{ __( 'withdrawal.service_charge_type' ) }}</label>
                                    <div class="col-sm-7">
                                        <select class="form-select form-select-sm" id="{{ $setting }}_service_charge_type">
                                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'withdrawal.service_charge_type' ) ] ) }}</option>
                                            <option value="1">{{ __( 'withdrawal.percentage' ) }}</option>
                                            <option value="2">{{ __( 'withdrawal.fixed_amount' ) }}</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="{{ $setting }}_service_charge_rate" class="col-sm-5 col-form-label">{{ __( 'withdrawal.service_charge_rate' ) }}</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control form-control-sm" id="{{ $setting }}_service_charge_rate">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-sm btn-primary" id="ws_save">{{ __( 'template.save_changes' ) }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ms" role="tabpanel">
                        <h5 class="card-title mb-0">{{ __( 'setting.maintenance_settings' ) }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="{{ $setting }}_enable_maintenance">
                                        <label class="form-check-label" for="{{ $setting }}_enable_maintenance">{{ __( 'setting.enable_maintenance' ) }}</label>
                                    </div>
                                </div>
                                @if ( 1 == 2 )
                                <div class="text-end">
                                    <button class="btn btn-sm btn-primary" id="ms_save">{{ __( 'template.save_changes' ) }}</button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        $( '.list-group-item-action' ).click( function() {
            // $( '.list-group-item-action' ).removeClass( 'active' );
            // $( this ).addClass( 'active' );
        } );

        getSettings();

        let s = '#{{ $setting }}';

        $( '#dbd_save' ).on( 'click', function() {

            resetInputValidation();

            $.ajax( {
                url: '{{ route( 'admin.setting.updateDepositBankDetail' ) }}',
                type: 'POST',
                data: {
                    bank: $( s + '_bank' ).val(),
                    account_holder: $( s + '_account_holder' ).val(),
                    account_no: $( s + '_account_no' ).val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.show();
                },
                error: function( error ) {
                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( s + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.show();       
                    }
                }
            } );
        } );

        $( '#ws_save' ).on( 'click', function() {

            resetInputValidation();

            $.ajax( {
                url: '{{ route( 'admin.setting.updateWithdrawalSetting' ) }}',
                type: 'POST',
                data: {
                    service_charge_type: $( s + '_service_charge_type' ).val(),
                    service_charge_rate: $( s + '_service_charge_rate' ).val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.show();
                },
                error: function( error ) {
                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( s + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.show();       
                    }
                }
            } );
        } );

        $( s + '_enable_maintenance' ).on( 'click', function() {

            $.ajax( {
                url: '{{ route( 'admin.setting.updateMaintenanceSetting' ) }}',
                type: 'POST',
                data: {
                    status: $( this ).is( ':checked' ) ? 10 : 20,
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.show();
                },
                error: function( error ) {
                    $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                    modalDanger.show();
                }
            } );
        } );

        function getSettings() {

            $.ajax( {
                url: '{{ route( 'admin.setting.settings' ) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    if ( response.length > 0 ) {

                        response.map( function( v, i ) {

                            switch ( v.option_name ) {
                                case 'DBD_BANK':
                                    $( s + '_bank' ).val( v.option_value );
                                    break;
                                case 'DBD_ACCOUNT_HOLDER':
                                    $( s + '_account_holder' ).val( v.option_value );
                                    break;
                                case 'DBD_ACCOUNT_NO':
                                    $( s + '_account_no' ).val( v.option_value );
                                    break;
                                case 'WD_SERVICE_CHARGE_TYPE':
                                    $( s + '_service_charge_type' ).val( v.option_value );
                                    break;
                                case 'WD_SERVICE_CHARGE_RATE':
                                    $( s + '_service_charge_rate' ).val( v.option_value );
                                    break;
                            }
                        } );
                    }
                },
            } );

            $.ajax( {
                url: '{{ route( 'admin.setting.maintenanceSettings' ) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    if ( response ) {

                        if ( response.status == 10 ) {
                            $( s + '_enable_maintenance' ).prop( 'checked', true );
                        }
                    }
                },
            } );
        }
    } );
</script>

