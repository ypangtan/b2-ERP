<?php
$profile_edit = 'profile_edit';
?>

<div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full">
    <div class="rounded-lg bg-white pb-6">
        <div class="flex items-center justify-between gap-x-4 pr-4">
            <div class="border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.personal_details') }}</h4>
                <p class="text-[11px] text-[#A2ABC1]">{{ __('member.personal_info_note') }}</p>
            </div>
        </div>
        <div class="px-4 py-2">
            <label class="text-[12px] text-[#A1A5B7]" for="{{ $profile_edit }}_fullname">{{ __('member.full_name' ) }}</label>
            <input type="text" id="{{ $profile_edit }}_fullname" placeholder="{{ __('member.enter' ) }}{{ __('member.full_name' ) }}" class="w-full form-control" readonly>
            <div class="invalid-feedback"></div>
        </div>
        <div class="px-4 py-2">
            <label class="text-[12px] text-[#A1A5B7]" for="{{ $profile_edit }}_email">{{ __('auth.email' ) }}</label>
            <input type="text" id="{{ $profile_edit }}_email" placeholder="{{ __('member.enter' ) }}{{ __('auth.email' ) }}" class="w-full form-control">
            <div class="invalid-feedback"></div>
        </div>
        <div class="px-4 py-2">
            <label class="text-[12px] text-[#A1A5B7]" for="{{ $profile_edit }}_address">{{ __('member.residential_address' ) }}</label>
            <textarea name="address" id="{{ $profile_edit }}_address" placeholder="{{ __('member.enter' ) }}{{ __('member.residential_address' ) }}" class="w-full form-control"></textarea>
            <div class="invalid-feedback"></div>
        </div>
        <button id="{{ $profile_edit }}_submit" class="primary_btn transition w-full mt-4 mx-auto max-w-[250px] min-w-[120px] block" value="{{ __('member.submit' ) }}">{{ __('member.submit' ) }}</button>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', () => {

        let pe = '#{{ $profile_edit }}',
            fileID = '',
            fileID2 = ''
            id = '';

        function hideString(str) {
            if (str.length > 4) {
                return '*'.repeat(str.length - 4) + str.slice(-4);
            } else {
                return str;
            }
        }

        $('.asterisks').each(function() {
            var originalString = $(this).text();
            $(this).text(hideString(originalString));
        });

        getMemberProfile()

        getUserKYCstatus() //checkUserKycStatus

        function getMemberProfile() {

            $.ajax( {
                url: '{{ route( 'web.profile.getMemberProfile' ) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    $( pe + '_fullname' ).val( response.user_detail ? response.user_detail.fullname : '' );
                    $( pe + '_email' ).val( response.email );
                    $( pe + '_address' ).text( response.kyc.address );

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
                    $( '#modal_subject' ).html( `{{ __( 'member.x_not_found', [ 'title' => Str::singular( __( 'member.profile' ) ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                }
            } );
        }

        $( pe + '_submit' ).click( function() {

            let formData = new FormData();
            formData.append( 'email', $( pe + '_email' ).val() );
            formData.append( 'address', $( pe + '_address' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'web.profile.updateMemberProfile' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.edit_profile' ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.x_y', [ 'action' => Str::singular( __( 'member.edit_profile' ) ), 'title' => Str::singular( __( 'member.success' ) ) ] ) }}` );
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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => Str::singular( __( 'member.edit_profile' ) ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                }
            } );
        } );

    });
</script>