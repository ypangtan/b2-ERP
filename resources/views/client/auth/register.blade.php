<?php echo view( 'client/templates/header', [ 'header' => @$header ] );?>
<!-- <video autoPlay="true" muted="true" loop="true" playsInline="true" class='fixed z-[-1] left-0 right-0 top-0 w-[100vw] min-h-[300px] h-[100vh] object-cover'>
    <source src="{{ asset('member/Element/jdg-bg-video.mp4') }}" type='video/mp4' />
</video> -->
<div class="absolute left-0 right-0 mx-auto flex flex-col items-center justify-center py-12">
    <div class="mb-10 relative">
        <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="150" height="100" class="block mx-auto"/>
    </div>
    <div class="rounded-lg bg-white px-4 md:px-12 py-4 md:py-6 max-w-[90vw] md:max-w-[500px] w-full">
        <h1 class="text-[2em] text-[#FF446B] font-bold text-center">{{ __( 'member.registration' ) }}</h1>
        <p class="text-[14px] text-[#8C8C8C] text-center mb-8">{{ __( 'member.registration_note' ) }}</p>
        <form action="{{ route( 'web.login' ) }}" method="POST" id="registerform" autocomplete="off" class="flex flex-col gap-4 justify-center w-full" novalidate>
            @csrf
            <input type="text" name="full_name" id="fullname" placeholder="{{ __( 'member.full_name' ) }}" class="w-full">
            <input type="email" name="email" id="email" placeholder="{{ __( 'user.email' ) }}"  autocomplete="off" class="w-full" />
            <div class="flex items-center">
                <button id="dropdown-phone-button" data-dropdown-toggle="dropdown-phone" class="flex-shrink-0 inline-flex items-center text-white py-2.5 px-4 text-sm font-medium text-center bg-[#6754DF] border border-solid border-[#6754DF] rounded-s-md cursor-default focus:outline-none" type="button">
                    +60
                </button>
                <div class="w-full">
                    <input type="text" name="phone_number" id="phone_number" placeholder="{{ __( 'user.phone_number' ) }}" class="rounded-none rounded-r-md">
                </div>
            </div>
            <input type="hidden" id="calling_code">
            <div class="relative">
            <input type="password" name="password" id="password" placeholder="{{ __( 'auth.password' ) }}" class="w-full" autocomplete="new-password">
            <i class="icon-icon33 absolute right-4 top-4 cursor-pointer text-[11px]" id="togglePassword"></i>
            <div class="strength-bars mt-4">
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
            </div>
            <p class="message text-[12px] text-[#A1A5B7]">{{ __( 'member.password_note' ) }}</p>
            </div>
            <div class="relative">
                <input type="password" name="repeat_password" id="repeat_password" placeholder="{{ __( 'member.repeat_password' ) }}" class="w-full">
                <i class="icon-icon33 absolute right-4 top-4 cursor-pointer text-[11px]" id="toggleRepeatPassword"></i>
            </div>
            <div>
                @if ( request( 'referral' ) )
                <input type="text" name="referral_code" id="referral_code" placeholder="{{ __( 'member.referral_code' ) }} ({{ __( 'member.optional' ) }})" class="w-full" value="{{ request( 'referral' ) }}" readonly>
                @else
                <input type="text" name="referral_code" id="referral_code" placeholder="{{ __( 'member.referral_code' ) }} ({{ __( 'member.optional' ) }})" class="w-full">
                @endif
                <div class="mt-2 text-end text-[#6754DF] text-[12px]">{{ __( 'member.referral_s_fullname' ) }}: <strong id="referral_fullname">-</strong></div>
            </div>
            <div class="relative">
                <input type="text" name="otp" id="otp" placeholder="{{ __( 'member.request_otp' ) }}" class="w-full">
                <div id="request_otp" class="w-fit underline block absolute text-[14px] right-4 text-[#6754DF] top-2" role="button">{{ __( 'member.request_otp' ) }}</div>
                <div id="countdown" class="hidden w-fix block absolute text-[14px] right-4 text-[#6754DF] top-2">01:00</div>
            </div>
            <div class="flex items-center gap-x-2">
            <label class="custom-checkbox text-[12px] text-[#A1A5B7]">
                <input type="checkbox" id="termsCheckbox" name="termsCheckbox" class="hidden">
                <span class="checkmark"></span>
                <label for="termsCheckbox" class="">{{ __( 'member.i_accept' ) }} {{ __( 'member.tnc' ) }}</label>
                <!-- <a href="#" id="tnc_btn" class="text-[#6754DF]">{{ __( 'member.tnc' ) }}</a> -->
            </label>
            <input type="hidden" id="tmp_user">
            </div>
            <button type="button" id="submit_btn" class="primary_btn transition w-full mb-2 mt-6">{{ __( 'member.register_now' ) }}</button>
        </form>
        <p class="text-center text-[#8C8C8C] text-[14px] flex gap-x-2 justify-center">{{ __( 'member.login_note_3' ) }}<a href="{{ route( 'web.login' ) }}" class="w-fit text-[14px] mb-4 text-[#6754DF]">{{ __( 'member.sign_in' ) }}</a></p>

    </div>
</div>

<script>
    $(document).ready(function () {
        function togglePassword() {
            var passwordInput = $('#password');
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
            var passwordRepeatInput = $('#repeat_password');
            var toggleRepeatPassword = $('#toggleRepeatPassword');

            if (passwordRepeatInput.attr('type') === 'password') {
                passwordRepeatInput.attr('type', 'text');
                toggleRepeatPassword.removeClass('icon-icon33').addClass('icon-icon34');
            } else {
                passwordRepeatInput.attr('type', 'password');
                toggleRepeatPassword.removeClass('icon-icon34').addClass('icon-icon33');
            }
        }

        $('#togglePassword').on('click', function () {
            togglePassword();
        });

        $('#toggleRepeatPassword').on('click', function () {
            toggleRepeatPassword();
        });

        $('#otp').on('input', function () {
            var inputValue = $(this).val();
            var maxLength = 6;
            if (inputValue.length > maxLength) {
                $(this).val(inputValue.substring(0, maxLength));
            }
        });

        $( '#request_otp' ).on( 'click', function() {
            requestOTP();
        } );

        $("#submit_btn").on('click', function () {

            register();

            return true;

            var modal = true;
            if(modal == true){
                $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                $( '#modal_subject' ).html( `{{ __( 'member.successful_register' ) }}` );
                $( '#modal_desc' ).html( `{{ __( 'member.successful_register_note' ) }}` );
                $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                $('#alert-modal').removeClass('hidden');
            }else{
                $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/failed-icon.png?v=1.2' ) }}` );
                $( '#modal_subject' ).html( `{{ __( 'member.failed_register' ) }}` );
                $( '#modal_desc' ).html( `{{ __( 'member.lost_connection_note' ) }}` );
                $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                $('#alert-modal').removeClass('hidden');
            }
        });

        $("#tnc_btn").on('click', function () {
            $('#disclaimer-modal').removeClass('hidden');
        });

        //Password strength animation
        $('#password').on('input', function () {
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

        let refTimeout = null;
        $( '#referral_code' ).on( 'keyup clear', function() {

            clearTimeout( refTimeout );

            refTimeout = setTimeout( function() {
                getUserByReferralCode();
            }, 750 );
        } );

        if ( $( '#referral_code' ).val() != '' ) {
            getUserByReferralCode();
        }

        function getUserByReferralCode() {

            $.ajax( {
                url: '{{ route( 'web.getReferral' ) }}',
                method: 'POST',
                data: {
                    referral_code: $( '#referral_code' ).val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    if ( response.data == null ) {
                        $( '#referral_fullname' ).html( '-' );
                    } else {
                        $( '#referral_fullname' ).html( response.data.user_detail ? response.data.user_detail.fullname : '-' );
                    }
                },
            } );
        }

        function requestOTP() {

            $.ajax( {
                url: '{{ route( 'web.requestOtp' ) }}',
                method: 'POST',
                data: {
                    calling_code: '+60',
                    phone_number: $( '#phone_number' ).val(),
                    request_type: 1,
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    countdownOTP();

                    $( '#request_otp' ).addClass( 'hidden' );

                    $( '#tmp_user' ).val( response.data.tmp_user );
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

        function register() {

            $.ajax( {
                url: '{{ route( 'web.createUser' ) }}',
                method: 'POST',
                data: {
                    otp_code: $( '#otp' ).val(),
                    tmp_user: $( '#tmp_user' ).val(),
                    email: $( '#email' ).val(),
                    fullname: $( '#fullname' ).val(),
                    calling_code: '+60',
                    phone_number: $( '#phone_number' ).val(),
                    password: $( '#password' ).val(),
                    repeat_password: $( '#repeat_password' ).val(),
                    invitation_code: $( '#referral_code' ).val(),
                    device_type: 3,
                    tnc: $( '#termsCheckbox' ).prop( 'checked' ) ? 1 : 0,
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.successful_register' ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.successful_register_note' ) }}` );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                    $('.close_btn, #modal_btn').on('click', function (){
                        window.location.href = '{{ route( 'web.login' ) }}';
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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_register' ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');
                },
            } );
        }
    });
</script>

<?php echo view( 'client/templates/alert-modal' ); ?>
<?php echo view( 'client/templates/terms' ); ?>