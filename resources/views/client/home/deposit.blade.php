<?php
$walletInfos = Helper::walletInfos();
$depositSettings = $data['deposit_settings'];
$banks = $data['banks'];
?>

<div class="mx-auto max-w-[90vw] md:max-w-[800px] gap-6 w-full block md:grid md:grid-cols-2 md:grid-rows-1">
    <div>
        <div class="rounded-lg bg-white w-full mb-6">
            <div class="flex items-center justify-between gap-x-4 border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.wallet_1' ) }}</h4>
                <h3 class="text-[15px] text-[#1070FF] font-bold text-right">{{ Helper::numberFormat( $walletInfos[1], 2, true ) }}</h3>
            </div>
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4">
                <!-- No need integrate for this part, in future will have payment gateway -->
                <p class="flex items-center justify-between mb-2"><span class="text-[12px] text-[#1A1D56]">{{ __( 'member.deposit_method' ) }}</span><span class="text-[#1A1D56] text-[13px] font-bold">{{ __( 'member.bank_transfer' ) }}</span></p>
            </div>
        </div>
        <div class="rounded-lg bg-white w-full mb-6">
            <div class="flex items-center justify-between gap-x-4">
                <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
                    <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.deposit_to' ) }}</h4>
                </div>
            </div>
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4">
                <p class="flex items-center justify-between mb-2 md:mb-4">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.bank_name' ) }}</span>
                    <?php
                    $currentBank = $banks->where( 'id', $depositSettings['dbd_bank'] )->first();
                    ?>
                    @if ( $currentBank )
                    <span class="text-[#1A1D56] text-[13px] font-bold">{{ $currentBank->name }}</span>
                    @else
                    <span class="text-[#1A1D56] text-[13px] font-bold">-</span>
                    @endif 
                </p>
                <p class="flex items-center justify-between mb-2 md:mb-4">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.account_holder' ) }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold">{{ $depositSettings['dbd_account_holder'] }}</span>
                </p>
                <p class="flex items-center justify-between mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.account_number' ) }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold asterisks">{{ $depositSettings['dbd_account_no'] }}</span>
                </p>
            </div>
        </div>
        <div class="rounded-lg bg-white w-full mb-6">
            <div class="flex items-center justify-between gap-x-4">
                <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
                    <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.deposit_amount' ) }}</h4>
                </div>
            </div>
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4">
                <div class="flex items-center justify-between mb-2 md:mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __( 'member.amount_myr' ) }}</span>
                    <div class="flex items-center justify-center gap-x-2">
                        <i class="icon-icon31" onclick="deductAmount( '#deposit_amount',100 )"></i>

                        <div class="text-center">
                            <input type="text" class="max-w-[110px] text-center" style="padding-top:0 !important;padding-bottom:0 !important;" name="amount" id="deposit_amount" value="{{ old( 'amount' ) ? old( 'amount' ) : '0.00' }}"/>
                        </div>

                        <i class="icon-icon21" onclick="addAmount( '#deposit_amount',100 )"></i>
                    </div>
                </div>
            
                
                <p class="flex items-center justify-between mb-2 md:mb-2"><span class="text-[12px] text-[#1A1D56]">{{ __( 'member.total_deposit_amount' ) }}</span><span class="text-[#1A1D56] text-[13px] font-bold" id="final_amount">0.00</span></p>
            </div>
        </div>
    </div>
    <div>
        <div class="rounded-lg bg-white w-full mb-6">
            
            <div class="flex items-center justify-between gap-x-4 md:mt-0 mt-4 pr-4">
                <div class="border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] border-b border-b-[#eaeaea] pl-4  py-4">
                    <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.attachment' ) }}</h4>
                    <p class="text-[11px] text-[#A2ABC1]">{{ __( 'member.attachment_note' ) }}</p>
                </div>
            </div>
            <div class="px-4 py-2">
                <div class="dropzone py-0 flex justify-center items-center w-full bg-[#F5F8FA] relative cursor-pointer" id="attachment" style="min-height: 0px; border:none">
                    <div class="dz-message needsclick">
                        <img src="{{ asset( 'member/Element/upload vector.png' ) }}" alt="Preview Image" id="image-preview" width="350" height="200" class="block mx-auto px-6 w-full"/>
                    </div>
                </div>
                <p class="text-[12px] text-[#A1A5B7] text-center mt-2">{{ __( 'member.drop_file_or_click_to_upload' ) }}</p>
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
        <button type="button" id="submit_btn" class="primary_btn transition w-full mb-2 mt-0 max-w-[200px] mx-auto block">{{ __( 'member.deposit' ) }}</button>
    </div>
</div>

<script>

    document.addEventListener( 'DOMContentLoaded', () => {

        let fileID = '';

        $( '#request_otp' ).on( 'click', function() {
            requestOTP();
        } );

        $( '#submit_btn' ).on( 'click', function() {
            deposit();
        } );

        getUserKYCstatus() //checkUserKycStatus

        function requestOTP() {

            $.ajax( {
                url: '{{ route( 'web.deposit.requestOtp' ) }}',
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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.deposit' ) ] ) }}` );
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

        function deposit() {

            $.ajax( {
                url: '{{ route( 'web.deposit.deposit' ) }}',
                method: 'POST',
                data: {
                    otp_code: $( '#otp' ).val(),
                    identifier: $( '#identifier' ).val(),
                    amount: $( '#deposit_amount' ).val(),
                    attachment: fileID,
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( response.message );
                    $( '#modal_desc' ).html( '' );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $( '#alert-modal' ).removeClass( 'hidden' );
                    
                    $( '.close_btn, #modal_btn' ).on( 'click', function () {
                        window.location.href = '{{ route( 'web.deposit.history' ) }}';
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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.deposit' ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $( '#alert-modal' ).removeClass( 'hidden' );
                },
            } );
        }

        $( '#deposit_amount' ).on( 'change', function() {
            changeAmount();
        } );

        Dropzone.autoDiscover = false;
        const dropzone = new Dropzone( '#attachment', { 
            url: '{{ route( 'member.file.upload' ) }}',
            maxFiles: 1,
            acceptedFiles: 'image/jpg,image/jpeg,image/png,application/pdf',
            addRemoveLinks: true,
            removedfile: function( file ) {
                fileID = null;
                file.previewElement.remove();
            },
            success: function( file, response ) {
                if ( response.status == 200 )  {
                    fileID = response.data.id;
                }
            }
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

        let amount = $( '#deposit_amount' ).val();
        if (isNaN( amount ) ) {
            amount = 0;
        }

        $( '#final_amount' ).html( parseFloat( amount ).toFixed( 2 ) );
    }
</script>