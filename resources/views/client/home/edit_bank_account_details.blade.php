<?php
$member_bank_edit = 'member_bank_edit';
?>

<div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full">
    <div class="rounded-lg bg-white pb-6">
            <div class="flex items-center justify-between gap-x-4 md:mt-0 mt-4 pr-4">
                <div class="border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                    <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.bank_account_details') }}</h4>
                    <p class="text-[11px] text-[#A2ABC1]">{{ __('member.bank_account_details_note') }}</p>
                </div>
            </div>

            <div class="px-4 py-2">
                <label class="text-[12px] text-[#A1A5B7]" for="{ $member_bank_edit }}_bank">{{ __('member.choose_bank' ) }}</label>
                <select class="form-select" id="{{ $member_bank_edit }}_bank" data-placeholder="{{ __( 'datatables.select_x', [ 'title' => __( 'user_kyc.bank' ) ] ) }}">
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="px-4 py-2">
                <label class="text-[12px] text-[#A1A5B7]" for="{{ $member_bank_edit }}_account_holder_name">{{ __('member.account_holder_name' ) }}</label>
                <input type="text" id="{{ $member_bank_edit }}_account_holder_name" name="bene_id_number" placeholder="{{ __('member.enter' ) }}{{ __('member.account_holder_name') }}" class="w-full form-control" required>
                <div class="invalid-feedback"></div>
            </div>
            <div class="px-4 py-2">
                <label class="text-[12px] text-[#A1A5B7]" for="{{ $member_bank_edit }}_account_number">{{ __('member.account_number' ) }}</label>
                <input type="text" id="{{ $member_bank_edit }}_account_number" name="bene_phone_number" placeholder="{{ __('member.enter' ) }}{{ __('member.account_number') }}" class="w-full form-control" required>
                <div class="invalid-feedback"></div>
            </div>

    </div>
</div>

<div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full" style="margin-top: 3%">
    <div class="rounded-lg bg-white pb-6">
        <div class="flex items-center justify-between gap-x-4 md:mt-0 mt-4 pr-4">
            <div class="border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.sms_verification') }}</h4>
                <p class="text-[11px] text-[#A2ABC1]">{{ __('member.sms_otp_note') }}</p>
            </div>
        </div>

        <input type= "hidden" name="{{ $member_bank_edit }}_identifier" id="{{ $member_bank_edit }}_identifier">

        <div class="relative px-4 py-2">
            <input type="text" name="{{ $member_bank_edit }}_otp" id="{{ $member_bank_edit }}_otp" placeholder="{{ __( 'member.request_otp' ) }}" class="w-full">
            <div id="{{ $member_bank_edit }}_request_otp" class="w-fit underline block absolute text-[14px] right-6 text-[#1A1D56] top-4" role="button">{{ __( 'member.request_otp' ) }}</div>
            <div id="{{ $member_bank_edit }}_countdown" class="hidden w-fix block absolute text-[14px] right-4 text-[#1A1D56] top-2">01:00</div>
        </div>

        <button id="{{ $member_bank_edit }}_submit" class="primary_btn transition w-full mt-4 mx-auto max-w-[250px] min-w-[120px] block" value="{{ __('member.submit' ) }}">{{ __('member.submit' ) }}</button>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', () => {

        let mbe = '#{{ $member_bank_edit }}';

        $( mbe + '_submit' ).click( function() {

            let formData = new FormData();
            formData.append( 'bank', $( mbe + '_bank' ).val() );
            formData.append( 'account_holder_name', $( mbe + '_account_holder_name' ).val() );
            formData.append( 'account_number', $( mbe + '_account_number' ).val() );
            formData.append( 'otp_code', $( mbe + '_otp' ).val() );
            formData.append( 'identifier', $( mbe + '_identifier' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'web.profile.updateMemberBankAccount' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    
                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.edit_bank_account_details' ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.x_y', [ 'action' => Str::singular( __( 'member.edit_bank_account_details' ) ) ,'title' => Str::singular( __( 'member.success' ) ) ] ) }}` );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                    $('.close_btn, #modal_btn').on('click', function (){
                        window.location.href = '{{ route( 'web.profile.index' ) }}';
                    });

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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => Str::singular( __( 'member.edit_bank_account_details' ) ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                }
            } );
        } );

        let bankSelect2 = $( mbe + '_bank').select2({

            theme: 'bootstrap-5',
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: true,

            ajax: { 
                url: '{{ route( 'web.bank.getActiveBank' ) }}',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        custom_search: params.term, // search term
                        designation: 1,
                        status: 10,
                        start: ( ( params.page ? params.page : 1 ) - 1 ) * 10,
                        length: 10,
                        _token: '{{ csrf_token() }}',
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    let processedResult = [];

                    data.banks.map( function( v, i ) {
                        processedResult.push( {
                            id: v.id,
                            text: v.name,
                        } );
                    } );

                    return {
                        results: processedResult,
                        pagination: {
                            more: ( params.page * 10 ) < data.recordsFiltered
                        }
                    };
                },
                cache: true
            }

        });

        getMemberProfile()

        function getMemberProfile() {

            $.ajax( {
                url: '{{ route( 'web.profile.getMemberProfile' ) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    let option1 = new Option( response.kyc.user_bank.bank.name, response.kyc.user_bank.bank.id, true, true );
                    bankSelect2.append( option1 );
                    bankSelect2.trigger( 'change' );
                    $( mbe + '_account_holder_name' ).val( response.kyc.user_bank.account_holder_name );
                    $( mbe + '_account_number' ).val( response.kyc.user_bank.account_number );

                }, error: function( error ) {

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
                    $( '#modal_subject' ).html( `{{ __( 'member.x_not_found', [ 'title' => Str::singular( __( 'member.beneficiary' ) ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                }
            } );
        }

        $( mbe + 'otp' ).on('input', function () {
            var inputValue = $(this).val();
            var maxLength = 6;
            if (inputValue.length > maxLength) {
                $(this).val(inputValue.substring(0, maxLength));
            }
        });

        $( mbe + '_request_otp' ).on( 'click', function() {
            requestOTP();
        } );

        //Password strength animation
        $( mbe + '_password' ).on('input', function () {
            var password = $(this).val();
            var strength = checkPasswordStrength(password);

            updateStrengthBars(strength);
            updateStrengthMessage(strength);
        });

        function checkPasswordStrength(password) {
            var strength = 0;

            if (password.length >= 8) {
                strength += 1;
            }

            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
                strength += 1;
            }

            if (password.match(/[0-9]/)) {
                strength += 1;
            }

            if (password.match(/[$@#&!]/)) {
                strength += 1;
            }

            return strength;
        }

        function updateStrengthBars(strength) {
            $('.strength-bar').removeClass('weak medium strong very-strong');
            if (strength === 1) {
                $('.strength-bar:nth-child(1)').addClass('weak');
            } else if (strength === 2) {
                $('.strength-bar:nth-child(1), .strength-bar:nth-child(2)').addClass('medium');
            } else if (strength === 3) {
                $('.strength-bar').slice(0, 3).addClass('strong');
            } else if (strength === 4) {
                $('.strength-bar').addClass('very-strong');
            }
        }

        function updateStrengthMessage(strength) {
            var message = '';
            if (strength < 2) {
                message = '{{ __( 'member.password_note' ) }}';
                $('.message').removeClass('good-message');
            } else if (strength < 4) {
                message = '{{ __( 'member.password_note_2' ) }}';
                $('.message').removeClass('good-message');
            } else {
                message = '{{ __( 'member.password_note_3' ) }}';
                $('.message').addClass('good-message');
            }
            $('.message').text(message);
        }

        function requestOTP() {
            $.ajax( {
                url: '{{ route( 'web.profile.requestOtpMemberProfile' ) }}',
                method: 'POST',
                data: {
                    action: 'edit_bank_account_details',
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.request_otp' ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.x_y', [ 'action' => Str::singular( __( 'member.request_otp' ) ) ,'title' => Str::singular( __( 'member.success' ) ) ] ) }}` );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                    countdownOTP();

                    $( mbe + '_request_otp' ).addClass( 'hidden' );

                    $( mbe + '_identifier' ).val( response.data.identifier );

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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_register' ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');
                }
            } );
        }

        function countdownOTP() {

            $( mbe + '_request_otp' ).addClass( 'hidden' );
            $( mbe + '_countdown' ).removeClass( 'hidden' );

            let next60Second = new Date().getTime() + 61000;

            let x = setInterval( function() {

                let now = new Date().getTime();

                let distance = next60Second - now;

                if ( distance <= 0 ) {
                    clearInterval( x );
                    $( mbe + '_request_otp' ).removeClass( 'hidden' );
                    $( mbe + '_countdown' ).addClass( 'hidden' ).html( '01:00' );
                    return 0;
                }

                let minutes = Math.floor( ( distance % ( 1000 * 60 * 60 ) ) / ( 1000 * 60 ) );
                let seconds = Math.floor( ( distance % ( 1000 * 60 ) ) / 1000 );

                $( mbe + '_countdown' ).html( minutes.toString().padStart( 2, 0 ) + ':' + seconds.toString().padStart( 2, 0 ) );

            }, 1000 );
        }
           
    });

</script>