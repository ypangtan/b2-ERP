<?php
$security_settings_edit = 'security_settings_edit';
?>

<div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full">
    <div class="rounded-lg bg-white pb-6">
        <div class="flex items-center justify-between gap-x-4 md:mt-0 mt-4 pr-4">
            <div class="border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.security_settings') }}</h4>
                <p class="text-[11px] text-[#A2ABC1]">{{ __('member.security_settings_note') }}</p>
            </div>
        </div>
        
        <div class="px-4 py-2 mt-4">
            <input type="hidden" name="{{ $security_settings_edit }}_identifier" id="{{ $security_settings_edit }}_identifier">
            <div class="relative">
                <input type="password" name="{{ $security_settings_edit }}_old_password" id="{{ $security_settings_edit }}_old_password" placeholder="{{ __( 'member.old_password' ) }}" class="w-full mb-4" autocomplete="old-password">
                <i class="icon-icon33 absolute right-4 top-4 cursor-pointer text-[11px]" id="togglePassword"></i>
            </div>
            <div class="relative">
                <input type="password" name="{{ $security_settings_edit }}_password" id="{{ $security_settings_edit }}_password" placeholder="{{ __( 'member.new_password' ) }}" class="w-full" autocomplete="new-password">
                <i class="icon-icon33 absolute right-4 top-4 cursor-pointer text-[11px]" id="toggleRepeatPassword"></i>
            </div>
            <div class="strength-bars mt-4">
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
            </div>
            <p class="message text-[12px] text-[#A1A5B7]">{{ __( 'member.password_note' ) }}</p>
        </div>
        <div class="relative px-4 py-2">
            <input type="password" name="{{ $security_settings_edit }}_confirm_new_password" id="{{ $security_settings_edit }}_confirm_new_password" placeholder="{{ __( 'member.confirm_new_password' ) }}" class="w-full">
            <i class="icon-icon33 absolute right-8 top-6 cursor-pointer text-[11px]" id="toggleConfirmPassword"></i>
        </div>
        <div class="relative px-4 py-2">
            <input type="text" name="{{ $security_settings_edit }}_otp" id="{{ $security_settings_edit }}_otp" placeholder="{{ __( 'member.request_otp' ) }}" class="w-full">
            <div id="{{ $security_settings_edit }}_request_otp" class="w-fit underline block absolute text-[14px] right-6 text-[#1A1D56] top-4" role="button">{{ __( 'member.request_otp' ) }}</div>
            <div id="{{ $security_settings_edit }}_countdown" class="hidden w-fix block absolute text-[14px] right-6 text-[#1A1D56] top-4">01:00</div>
        </div>
        <button id="{{ $security_settings_edit }}_submit" class="primary_btn transition w-full mt-4 mx-auto max-w-[250px] min-w-[120px] block" value="{{ __('member.submit' ) }}">{{ __('member.submit' ) }}</button>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', () => {

        let sse = '#{{ $security_settings_edit }}';

        $( sse + 'otp' ).on('input', function () {
            var inputValue = $(this).val();
            var maxLength = 6;
            if (inputValue.length > maxLength) {
                $(this).val(inputValue.substring(0, maxLength));
            }
        });

        $( sse + '_request_otp' ).on( 'click', function() {
            requestOTP();
        } );

        $( sse + '_submit' ).on( 'click', function() {

            let formData = new FormData();
            formData.append( 'identifier', $( sse + '_identifier' ).val() );
            formData.append( 'otp_code', $( sse + '_otp' ).val() );
            formData.append( 'password', $( sse + '_password' ).val() );
            formData.append( 'password_confirmation', $( sse + '_confirm_new_password' ).val() );
            formData.append( 'old_password', $( sse + '_old_password' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'web.profile.updateMemberSecuritySettings' ) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    
                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.edit_profile' ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.x_updated', [ 'title' => Str::singular( __( 'member.security_settings' ) ) ] ) }}` );
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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => Str::singular( __( 'member.edit_security_settings' ) ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');
                }
            } );
        } );

        //Password strength animation
        $( sse + '_password' ).on('input', function () {
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
                    action: 'edit_security_settings',
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.request_otp' ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.x_y', [ 'action' => Str::singular( __( 'member.request_otp' ) ) ,'title' => Str::singular( __( 'member.success' ) ) ] ) }}` );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                    countdownOTP();

                    $( sse + '_request_otp' ).addClass( 'hidden' );

                    $( sse + '_identifier' ).val( response.data.identifier );
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

            $( sse + '_request_otp' ).addClass( 'hidden' );
            $( sse + '_countdown' ).removeClass( 'hidden' );

            let next60Second = new Date().getTime() + 61000;

            let x = setInterval( function() {

                let now = new Date().getTime();

                let distance = next60Second - now;

                if ( distance <= 0 ) {
                    clearInterval( x );
                    $( sse + '_request_otp' ).removeClass( 'hidden' );
                    $( sse + '_countdown' ).addClass( 'hidden' ).html( '01:00' );
                    return 0;
                }

                let minutes = Math.floor( ( distance % ( 1000 * 60 * 60 ) ) / ( 1000 * 60 ) );
                let seconds = Math.floor( ( distance % ( 1000 * 60 ) ) / 1000 );

                $( sse + '_countdown' ).html( minutes.toString().padStart( 2, 0 ) + ':' + seconds.toString().padStart( 2, 0 ) );

            }, 1000 );
        }

        function togglePassword() {
            var passwordInput = $('#{{ $security_settings_edit }}_old_password');
            var togglePasswordSpan = $('#togglePassword');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                togglePasswordSpan.removeClass('icon-icon33').addClass('icon-icon34');
            } else {
                passwordInput.attr('type', 'password');
                togglePasswordSpan.removeClass('icon-icon34').addClass('icon-icon33');
            }
        }

        function toggleRepeatPassword() {
            var passwordRepeatInput = $('#{{ $security_settings_edit }}_password');
            var toggleRepeatPassword = $('#toggleRepeatPassword');

            if (passwordRepeatInput.attr('type') === 'password') {
                passwordRepeatInput.attr('type', 'text');
                toggleRepeatPassword.removeClass('icon-icon33').addClass('icon-icon34');
            } else {
                passwordRepeatInput.attr('type', 'password');
                toggleRepeatPassword.removeClass('icon-icon34').addClass('icon-icon33');
            }
        }

        function toggleConfirmPassword() {
            var passwordConfirmInput = $('#{{ $security_settings_edit }}_confirm_new_password');
            var toggleConfirmPassword = $('#toggleConfirmPassword');

            if (passwordConfirmInput.attr('type') === 'password') {
                passwordConfirmInput.attr('type', 'text');
                toggleConfirmPassword.removeClass('icon-icon33').addClass('icon-icon34');
            } else {
                passwordConfirmInput.attr('type', 'password');
                toggleConfirmPassword.removeClass('icon-icon34').addClass('icon-icon33');
            }
        }

        $('#togglePassword').on('click', function () {
            togglePassword();
        });

        $('#toggleRepeatPassword').on('click', function () {
            toggleRepeatPassword();
        });

        $('#toggleConfirmPassword').on('click', function () {
            toggleConfirmPassword();
        });
    });
</script>