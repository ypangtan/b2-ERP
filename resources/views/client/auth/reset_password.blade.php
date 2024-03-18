<?php echo view( 'client/templates/header', [ 'header' => @$header ] );?>
<video autoPlay="true" muted="true" loop="true" playsInline="true" class='fixed z-[-1] left-0 right-0 top-0 w-[100vw] min-h-[300px] h-[100vh] object-cover'>
    <source src="{{ asset('member/Element/jdg-bg-video.mp4') }}" type='video/mp4' />
</video>
<div class="absolute left-0 right-0 mx-auto flex flex-col items-center justify-center py-12">
    <div class="mb-10 relative">
        <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="250" height="100" class="block mx-auto w-[200px] md:w-[250px]"/>
        <p class="text-white text-[14px] md:text-[19px] mt-4">{{ __( 'member.website_desc' ) }}</p>
    </div>
    <div class="rounded-lg bg-white px-4 md:px-12 py-4 md:py-6 max-w-[90vw] md:max-w-[500px] w-full">
        <h1 class="text-[2em] text-[#1A1D56] font-bold text-center">{{ __( 'member.reset_password' ) }}</h1>
        <p class="text-[14px] text-[#8C8C8C] text-center mb-8">{{ __( 'member.reset_password_note' ) }}</p>
        <div class="relative">
            <input type="password" id="password" placeholder="{{ __( 'auth.password' ) }}" class="w-full" autocomplete="new-password">
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
            <input type="password" id="repeat_password" placeholder="{{ __( 'member.repeat_password' ) }}" class="w-full">
            <i class="icon-icon33 absolute right-4 top-4 cursor-pointer text-[11px]" id="toggleRepeatPassword"></i>
        </div>

        <input type="hidden" id="identifier" value="{{ request( 'token' ) }}">

        <button type="button" id="submit_btn" class="primary_btn transition w-full mb-2 mt-6">{{ __( 'member.reset_now' ) }}</button>
    </div>
</div>

<script>
    $(document).ready(function () {

        $("#submit_btn").on('click', function () {

            resetPassword();

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

        function resetPassword() {

            $.ajax( {
                url: '{{ route( 'web.submitResetPassword' ) }}',
                method: 'POST',
                data: {
                    identifier: $( '#identifier' ).val(),
                    password: $( '#password' ).val(),
                    repeat_password: $( '#repeat_password' ).val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.successfully_x', [ 'title' => __( 'member.reset_password' ) ] ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.successful_register_note' ) }}` );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                    $( '.close_btn, #modal_btn' ).on('click', function () {
                        window.location.href = '{{ route( 'web.login' ) }}';
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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.reset_password' ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');
                },
            } );
        }


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

    });
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
</script>

<?php echo view( 'client/templates/alert-modal' ); ?>