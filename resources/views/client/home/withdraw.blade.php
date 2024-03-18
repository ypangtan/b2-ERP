<?php
$walletInfos = Helper::walletInfos();
$currentUser = $data['user'];
$withdrawalSettings = $data['withdrawal_settings'];
?>

<div class="mx-auto max-w-[90vw] md:max-w-[800px] gap-6 w-full block md:grid md:grid-cols-2 md:grid-rows-1">
    <div>
        <div class="rounded-lg bg-white w-full mb-6">
            <div class="flex items-center justify-between gap-x-4 border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.withdraw_from' ) }}</h4>
                <!-- <h3 class="text-[15px] text-[#1070FF] font-bold text-right">40,000.00</h3> -->
            </div>
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4">
                <!-- No need integrate for this part, in future will have payment gateway -->
                <p class="flex items-center justify-between mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.wallet_1' ) }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold">{{ Helper::numberFormat( $walletInfos[1], 2, true ) }}</span>
                </p>
            </div>
        </div>
        <div class="rounded-lg bg-white w-full mb-6">
            <div class="flex items-center justify-between gap-x-4">
                <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
                    <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.withdraw_to' ) }}</h4>
                </div>
            </div>
            @if ( $currentUser->kyc && $currentUser->kyc->userBank )
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4">
                <p class="flex items-center justify-between mb-2 md:mb-4">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.bank_name' ) }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold">{{ $currentUser->kyc->userBank->bank->name }}</span>
                </p>
                <p class="flex items-center justify-between mb-2 md:mb-4">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.account_holder' ) }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold">{{ $currentUser->kyc->userBank->account_holder_name }}</span>
                </p>
                <p class="flex items-center justify-between mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.account_number' ) }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold asterisks">{{ $currentUser->kyc->userBank->account_number }}</span>
                </p>
            </div>
            @else
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4">
                <p class="flex items-center justify-between mb-2 md:mb-4"><span class="text-[12px] text-[#1A1D56]">{{ __( 'member.bank_name' ) }}</span><span class="text-[#1A1D56] text-[13px] font-bold">-</span></p>
                <p class="flex items-center justify-between mb-2 md:mb-4"><span class="text-[12px] text-[#1A1D56]">{{ __( 'member.account_holder' ) }}</span><span class="text-[#1A1D56] text-[13px] font-bold">-</span></p>
                <p class="flex items-center justify-between mb-2"><span class="text-[12px] text-[#1A1D56]">{{ __( 'member.account_number' ) }}</span><span class="text-[#1A1D56] text-[13px] font-bold asterisks">-</span></p>
            </div>
            @endif
        </div>
    </div>
    <div>
        <div class="rounded-lg bg-white w-full mb-6">
            <div class="flex items-center justify-between gap-x-4">
                <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
                    <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.withdraw_amount' ) }}</h4>
                </div>
            </div>
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4">
                <div class="flex items-center justify-between mb-2 md:mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.amount_myr' ) }}</span>
                    <div class="flex items-center justify-center gap-x-2">
                        <i class="icon-icon31" onclick="deductAmount( '#withdrawal_amount',100)"></i>

                        <div class="text-center">
                            <input type="text" class="max-w-[110px] text-center" style="padding-top:0 !important;padding-bottom:0 !important;" name="amount" id="withdrawal_amount" value="{{ old( 'amount' ) ? old( 'amount' ) : '0.00' }}"/>
                        </div>

                        <i class="icon-icon21" onclick="addAmount( '#withdrawal_amount',100)"></i>
                    </div>
                </div>

                <p class="flex items-center justify-between mb-2 md:mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.service_charge' ) }}
                        @if ( $withdrawalSettings['wd_service_charge_type'] == 1 )
                        <span> ({{ Helper::numberFormat( $withdrawalSettings['wd_service_charge_rate'], 0 ) }}%)</span>
                        @endif
                    </span>
                    <span class="text-[#1A1D56] text-[13px] font-bold" id="service_charge">
                    @if ( $withdrawalSettings['wd_service_charge_type'] == 1 )
                    0.00
                    @else
                    {{ Helper::numberFormat( $withdrawalSettings['wd_service_charge_rate'], 2, true ) }}
                    @endif
                    </span>
                </p>
                <p class="flex items-center justify-between mb-2 md:mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __('member.total_receive_amount') }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold" id="final_amount">0.00</span>
                </p>
            </div>
        </div>
        <div class="rounded-lg bg-white w-full mb-6">
            <div class="flex items-center justify-between gap-x-4">
                <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
                    <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.sms_verification' ) }}</h4>
                </div>
            </div>
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4 relative">
                <input type="text" name="otp" id="otp" placeholder="{{ __( 'member.request_otp' ) }}" class="w-full">
                <div id="request_otp" class="w-fit underline block absolute text-[14px] right-6 text-[#1A1D56] top-6" role="button">{{ __( 'member.request_otp' ) }}</div>
                <div id="countdown" class="hidden w-fix block absolute text-[14px] right-6 text-[#1A1D56] top-6">01:00</div>
                <p class="text-[#1A1D56] text-[12px] mt-4">{{ __( 'member.sms_otp_note' ) }}</p>
            </div>
        </div>
    </div>

    <input type="hidden" id="identifier">

    <div class="md:col-span-2">
        <button type="button" id="submit_btn" class="primary_btn transition w-full mb-2 mt-0 max-w-[200px] mx-auto block">{{ __( 'member.withdraw' ) }}</button>
    </div>
</div>

<script>

    let serviceChargeType = '{{ $withdrawalSettings['wd_service_charge_type'] }}',
        serviceChargeRate = '{{ $withdrawalSettings['wd_service_charge_rate'] }}';

    document.addEventListener( 'DOMContentLoaded', () => {

        $( '#request_otp' ).on( 'click', function() {
            requestOTP();
        } );

        $( '#submit_btn' ).on( 'click', function() {
            withdrawal();
        } );

        getUserKYCstatus() //checkUserKycStatus

        function requestOTP() {

            $.ajax( {
                url: '{{ route( 'web.withdrawal.requestOtp' ) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    countdownOTP();

                    $( '#request_otp' ).addClass( 'hidden' );

                    $( '#identifier' ).val( response.data.identifier );
                },
                error: function( error ) {

                    let errorText = '';
                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors,
                            errorArray = [];
                        $.each( errors, function( key, value ) {
                            errorArray.push( value );
                        } );
                        errorText = errorArray.join( '<br>' );
                    } else {
                        errorText = error.responseJSON.message;
                    }

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/failed-icon.png?v=1.2' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.withdraw' ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $( '#alert-modal' ).removeClass( 'hidden' );
                }
            } );
        }

        function countdownOTP() {

            $( '#request_otp' ).addClass( 'hidden' );
            $( '#countdown' ).removeClass( 'hidden' );

            let next60Second = new Date().getTime() + 61000;

            let x = setInterval( function() {

                let now = new Date().getTime();

                let distance = next60Second - now;

                if ( distance <= 0 ) {
                    clearInterval( x );
                    $( '#request_otp' ).removeClass( 'hidden' );
                    $( '#countdown' ).addClass( 'hidden' ).html( '01:00' );
                    return 0;
                }

                let minutes = Math.floor( ( distance % ( 1000 * 60 * 60 ) ) / ( 1000 * 60 ) );
                let seconds = Math.floor( ( distance % ( 1000 * 60 ) ) / 1000 );

                $( '#countdown' ).html( minutes.toString().padStart( 2, 0 ) + ':' + seconds.toString().padStart( 2, 0 ) );

            }, 1000 );
        }

        function withdrawal() {

            $.ajax( {
                url: '{{ route( 'web.withdrawal.withdrawal' ) }}',
                method: 'POST',
                data: {
                    otp_code: $( '#otp' ).val(),
                    identifier: $( '#identifier' ).val(),
                    amount: $( '#withdrawal_amount' ).val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( response.message );
                    $( '#modal_desc' ).html( '' );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $( '#alert-modal' ).removeClass( 'hidden' );

                    $( '.close_btn, #modal_btn' ).on( 'click', function () {
                        window.location.href = '{{ route( 'web.withdrawal.history' ) }}';
                    } );
                },
                error: function( error ) {

                    let errorText = '';
                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors,
                            errorArray = [];
                        $.each( errors, function( key, value ) {
                            errorArray.push( value );
                        } );
                        errorText = errorArray.join( '<br>' );
                    } else {
                        errorText = error.responseJSON.message;
                    }

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/failed-icon.png?v=1.2' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.withdraw' ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $( '#alert-modal' ).removeClass( 'hidden' );
                },
            } );
        }

        $( '#withdrawal_amount' ).on( 'change', function() {
            changeAmount();
        } );
    } );

    function addAmount(id, val)
    {
        let cur = parseFloat($(id).val());
        if(isNaN(cur))
        {
            cur = 0;
        }
        var num = parseFloat(cur + val);
        $(id).val(num.toFixed(2));
        changeAmount(); 
    }

    function deductAmount(id, val)
    {
        let cur = parseFloat($(id).val());
        if(isNaN(cur))
        {
            cur = 0;
        }
        if((cur - val) < 0)
        {   
            var zero = 0;
            $(id).val(zero.toFixed(2));
        }else{
            var num = parseFloat(cur - val);
            $(id).val(num.toFixed(2));
        }
        changeAmount();
        
    }

    function changeAmount() {

        let amount = $( '#withdrawal_amount' ).val();
        if (isNaN( amount ) ) {
            amount = 0;
        }

        let serviceCharge = parseFloat( calculateServiceCharge( amount ) );
        let finalAmount = parseFloat( amount ) - serviceCharge;

        $( '#service_charge' ).html( serviceCharge.toFixed( 2 ) );
        if ( finalAmount <= 0 ) {
            $( '#final_amount' ).html( '0.00' );
        } else {
            $( '#final_amount' ).html( finalAmount.toFixed( 2 ) );
        }
    }

    function calculateServiceCharge( amount ) {

        if ( serviceChargeType == 1 ) { // By Percentage
            return amount * parseFloat( serviceChargeRate ) / 100;
        } else { // Fixed Amount
            return serviceChargeRate;
        }
    }
</script>