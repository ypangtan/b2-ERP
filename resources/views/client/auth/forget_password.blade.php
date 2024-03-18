<?php echo view( 'client/templates/header', [ 'header' => @$header ] );?>
<!-- <video autoPlay="true" muted="true" loop="true" playsInline="true" class='fixed z-[-1] left-0 right-0 top-0 w-[100vw] min-h-[300px] h-[100vh] object-cover'>
    <source src="{{ asset('member/Element/jdg-bg-video.mp4') }}" type='video/mp4' />
</video> -->
<div class="absolute left-0 right-0 mx-auto flex flex-col items-center justify-center py-12">
    <div class="mb-6 relative">
        <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="150" height="100" class="block mx-auto"/>
    </div>
    <div class="rounded-lg bg-white px-4 md:px-12 py-4 md:py-6 max-w-[90vw] md:max-w-[500px] w-full">
        <h1 class="text-[2em] text-[#6754DF] font-bold text-center mb-2">{{ __( 'member.forgot_password' ) }} ?</h1>
        <p class="text-[14px] text-[#8C8C8C] text-center mb-8">{{ __( 'member.forgot_password_note' ) }}</p>
        <input type="email" name="email" placeholder="{{ __( 'user.email' ) }}" id="email" autocomplete="off" class="w-full mb-2" />
        <div class="relative">
            <input type="text" name="otp" id="otp" placeholder="{{ __( 'member.request_otp' ) }}" class="w-full">
            <div id="request_otp" class="w-fit underline block absolute text-[14px] right-4 text-[#6754DF] top-2" role="button">{{ __( 'member.request_otp' ) }}</div>
            <div id="countdown" class="hidden w-fix block absolute text-[14px] right-4 text-[#6754DF] top-2">01:00</div>
        </div>

        <input type="hidden" id="identifier">

        <input type="button" id="submit_btn" class="primary_btn transition w-full mb-2 mt-6 mx-auto max-w-[80%] block" value="{{ __( 'member.submit' ) }}">
    </div>
</div>
<script>
        
    document.addEventListener( 'DOMContentLoaded', function() {

        $( '#request_otp' ).on( 'click', function() {
            requestOTP();
        } );

        $( '#submit_btn' ).on('click', function () {

            forgotPassword();

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

        function requestOTP() {

            $.ajax( {
                url: '{{ route( 'web.forgotPasswordOtp' ) }}',
                method: 'POST',
                data: {
                    email: $( '#email' ).val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    if ( response.data.otp_code != '' ) {
                        countdownOTP();
                        $( '#request_otp' ).addClass( 'hidden' );
                        $( '#identifier' ).val( response.data.identifier );
                    } else {
                        $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/failed-icon.png?v=1.2' ) }}` );
                        $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.forgot_password' ) ] ) }}` );
                        $( '#modal_desc' ).html( `{{ __( 'member.forgot_password_email_not_correct' ) }}` );
                        $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                        $('#alert-modal').removeClass('hidden');
                    }
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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.forgot_password' ) ] ) }}` );
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

        function forgotPassword() {

            $.ajax( {
                url: '{{ route( 'web.verifyForgotPassword' ) }}',
                method: 'POST',
                data: {
                    otp_code: $( '#otp' ).val(),
                    identifier: $( '#identifier' ).val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    window.location.href = response.data.redirect_url;
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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.forgot_password' ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');
                },
            } );
        }
    } );
</script>

<?php echo view( 'client/templates/alert-modal' ); ?>