<?php echo view( 'client/templates/header', [ 'header' => @$header ] );?>
<!-- <video autoPlay="true" muted="true" loop="true" playsInline="true" class='fixed z-[-1] left-0 right-0 top-0 w-[100vw] min-h-[300px] h-[100vh] object-cover'>
    <source src="{{ asset('member/Element/jdg-bg-video.mp4') }}" type='video/mp4' />
</video> -->
<div class="absolute left-0 right-0 mx-auto flex flex-col items-center justify-center py-12 h-full">
    <div class="mb-6">
        <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="150" height="100" class="block mx-auto"/>
        <!-- <p class="text-white text-[19px] mt-4">{{ __( 'member.website_desc' ) }}</p> -->
    </div>
    <div class="rounded-lg bg-white px-4 md:px-12 py-4 md:py-6">
        <h1 class="text-[2em] text-[#FF446B] font-bold text-center">{{ __( 'member.sign_in' ) }}</h1>
        <p class="text-[14px] text-[#8C8C8C] text-center mb-8">{{ __( 'member.sign_in_note' ) }}</h1>
        <form id="login_form" action="{{ route( 'web._login' ) }}" method="POST" class="flex flex-col gap-4 justify-center min-w-[90vw] md:min-w-[450px]">
            @csrf
            <div class="flex items-center">
                <button id="dropdown-phone-button" data-dropdown-toggle="dropdown-phone" class="flex-shrink-0 inline-flex items-center text-white py-2.5 px-4 text-sm font-medium text-center bg-[#6754DF] border border-solid border-[#6754DF] rounded-s-md cursor-default focus:outline-none" type="button">
                    +60
                </button>
                <div class="w-full">
                    <input type="text" name="phone_number" id="phone_number" placeholder="{{ __( 'user.phone_number' ) }}" class="rounded-none rounded-r-md">
                </div>
            </div>
            <input type="hidden" name="username" id="username">
            <div class="relative">
                <input type="password" id="passwordInput" name="password" placeholder="{{ __( 'auth.password' ) }}" class="w-full">
                <i class="icon-icon33 absolute right-4 top-4 cursor-pointer text-[11px]" id="togglePassword"></i>
            </div>
            <!-- <a href="{{ route( 'web.forgotPassword' ) }}" class="w-fit text-right block ml-auto text-[14px] mb-4 text-[#6754DF]">{{ __( 'member.forgot_password' ) }} ?</a> -->
            <button type="button" id="sign_in" class="primary_btn transition w-full mb-2 mt-4">{{ __( 'member.sign_in' ) }}</button>
        </form>
        <p class="text-center text-[#8C8C8C] text-[14px] flex gap-x-2 justify-center">{{ __( 'member.login_note_2' ) }}<a href="{{ route( 'web.register' ) }}" class="w-fit text-[14px] mb-4 text-[#6754DF]">{{ __( 'member.register' ) }}</a></p>

    </div>
</div>

<script>
    $( document ).ready(function () {
        function togglePassword() {
            var passwordInput = $('#passwordInput');
            var togglePasswordSpan = $('#togglePassword');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                togglePasswordSpan.removeClass('icon-icon33').addClass('icon-icon34');
            } else {
                passwordInput.attr('type', 'password');
                togglePasswordSpan.removeClass('icon-icon34').addClass('icon-icon33');
            }
        }

        $('#togglePassword').on('click', function () {
            togglePassword();
        });
        
        $( '#sign_in' ).on( 'click', function() {

            let phoneNumber = $( '#phone_number' ).val();

            // Reserved for calling code
            $( '#username' ).val( phoneNumber );

            $( '#login_form' ).submit();
        } );

        let error = '';
        @foreach ( $errors->all() as $error )
        error += '{{ $error }}<br>';
        @endforeach

        @if ( $errors->any() )
        $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/failed-icon.png?v=1.2' ) }}` );
        $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.sign_in' ) ] ) }}` );
        $( '#modal_desc' ).html( error );
        $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
        $('#alert-modal').removeClass('hidden');
        @endif
    } );
</script>

<?php echo view( 'client/templates/alert-modal' ); ?>