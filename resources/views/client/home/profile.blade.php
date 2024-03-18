<?php
$profile_view = 'profile_view';
?>

<div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full pb-12"> 
    <div class="rounded-lg bg-white shadow-[0_6px_6px_0_rgba(0,0,0,0.2)] mb-4 flex gap-x-4 items-center px-6 py-4">
        <a href="#" class="relative">
            <img src="https://ui-avatars.com/api/?length=1&background=A2ABC1&color=fff&name={{ auth()->user()->userDetail->fullname }}" alt="Logo Image" width="250" height="100" class="block mx-auto w-[100px] md:w-[120px] rounded-lg" id="{{ $profile_view }}_photo"/>
            <input type="file" id="{{ $profile_view }}_edit_photo_placeholder" style="display: none;" accept=".jpg, .jpeg, .png">
            <i class="icon-icon49 text-[11px] text-[#fff] bg-[#1A1D56] flex items-center justify-center w-[16px] h-[16px] rounded-sm absolute right-[-5px] bottom-[-5px]" id="{{ $profile_view }}_edit_photo"></i>
        </a>
        <div class="py-4 px-4 w-full">
            <!-- only show tick when user KYC is verified -->
            <p class="flex items-center gap-x-4"><span class="text-[#1A1D56] text-[16px] font-bold" id="{{ $profile_view }}_fullname">-</span><i class="icon-icon14 text-[#0066FF] hidden" id="{{ $profile_view }}_verified"></i></p>

            <span class="text-[#50CD89] bg-[#D7FFEA] text-[10px] leading-0 px-3 py-1 mb-3 mt-1 text-center rounded-md block w-fit" id="{{ $profile_view }}_status" >{{ __('member.active') }}</span>

            <p class="flex items-center gap-x-4"><span class="text-[#A1A5B7] text-[13px]"><i class="icon-icon4 pr-2"></i>{{ __('member.join_date') }}</span><span class="text-[#1A1D56] text-[13px] font-bold" id="{{ $profile_view }}_joined_date">-</span></p>
        </div>
    </div>   
    <div class="rounded-lg bg-white shadow-[0_6px_6px_0_rgba(0,0,0,0.2)] mb-4">
        <a href="{{ route( 'web.profile.editMemberProfile' ) }}" class="flex items-center justify-between border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4">
            <span class="text-[#1A1D56] text-[16px] font-bold">{{ __('member.personal_details') }}</span>
            <i class="icon-icon24 text-[11px] text-[#1A1D56]"></i>
        </a>
        <div class="py-4 px-4 grid grid-cols-2 grid-rows-2 gap-4">
            <p class="text-[#A1A5B7] text-[13px] flex items-center"><i class="icon-icon4 text-[14px] text-[#1A1D56] pr-2"></i><span>{{ __('member.user_id') }} :&nbsp;<span id="{{ $profile_view }}_user_id">-</span></p>
            <p class="text-[#A1A5B7] text-[13px] flex items-center"><i class="icon-icon12 text-[14px] text-[#1A1D56] pr-2"></i><span id="{{ $profile_view }}_phone_number">-</span></p>
            <p class="text-[#A1A5B7] text-[13px] flex items-center"><i class="icon-icon13 text-[14px] text-[#1A1D56] pr-2"></i><span id="{{ $profile_view }}_email">-</span></p>
            <p class="text-[#A1A5B7] text-[13px] flex items-center"><i class="icon-icon19 text-[14px] text-[#1A1D56] pr-2"></i><span>{{ __('member.my_referrer') }} :&nbsp;</span><span id="{{ $profile_view }}_my_referrer">-</span></p>
        </div>
    </div>
    <div class="rounded-lg bg-white shadow-[0_6px_6px_0_rgba(0,0,0,0.2)] mb-4 hidden" id="beneficiary_details">
        <a href="{{ route( 'web.profile.editMemberBeneficiary' ) }}" class="flex items-center justify-between border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4">
            <span class="text-[#1A1D56] text-[16px] font-bold">{{ __('member.beneficiary_details') }}</span>
            <i class="icon-icon24 text-[11px] text-[#1A1D56]"></i>
        </a>
        <div class="py-4 px-4">
            <p class="flex items-center justify-between mb-2"><span class="text-[#A1A5B7] text-[13px]">{{ __('member.beneficiary_name') }}</span><span class="text-[#1A1D56] text-[13px] font-bold" id="{{ $profile_view }}_beneficiary_name">-</span></p>
            <p class="flex items-center justify-between mb-2"><span class="text-[#A1A5B7] text-[13px]">{{ __('member.beneficiary_ic') }}</span><span class="text-[#1A1D56] text-[13px] font-bold" id="{{ $profile_view }}_beneficiary_ic">-</span></p>
            <p class="flex items-center justify-between"><span class="text-[#A1A5B7] text-[13px]">{{ __('member.beneficiary_contact_no') }}</span><span class="text-[#1A1D56] text-[13px] font-bold asterisks" id="{{ $profile_view }}_beneficiary_contact_no">-</span></p>
        </div>
    </div>
    <div class="rounded-lg bg-white shadow-[0_6px_6px_0_rgba(0,0,0,0.2)] mb-4 hidden" id="bank_account_details">
        <a href="{{ route( 'web.profile.editMemberBankAccount' ) }}" class="flex items-center justify-between border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4">
            <span class="text-[#1A1D56] text-[16px] font-bold">{{ __('member.bank_account_details') }}</span>
            <i class="icon-icon24 text-[11px] text-[#1A1D56]"></i>
        </a>
        <div class="py-4 px-4">
            <p class="flex items-center justify-between mb-2"><span class="text-[#A1A5B7] text-[13px]">{{ __('member.bank_name') }}</span><span class="text-[#1A1D56] text-[13px] font-bold" id="{{ $profile_view }}_bank_name">-</span></p>
            <p class="flex items-center justify-between mb-2"><span class="text-[#A1A5B7] text-[13px]">{{ __('member.account_holder') }}</span><span class="text-[#1A1D56] text-[13px] font-bold" id="{{ $profile_view }}_account_holder">-</span></p>
            <p class="flex items-center justify-between"><span class="text-[#A1A5B7] text-[13px]">{{ __('member.account_number') }}</span><span class="text-[#1A1D56] text-[13px] font-bold asterisks" id="{{ $profile_view }}_account_number">-</span></p>
        </div>
    </div>
    <a href="{{ route( 'web.profile.editMemberSecuritySettings' ) }}" class="flex items-center justify-between px-4 w-full py-4 bg-white shadow-[0_6px_6px_0_rgba(0,0,0,0.2)] rounded-lg">
        <span class="text-[#1A1D56] text-[16px] font-bold">{{ __('member.security_settings') }}</span>
        <i class="icon-icon24 text-[11px] text-[#1A1D56]"></i>
    </a>
</div>
<script>
    document.addEventListener( 'DOMContentLoaded', () => {
        
        let pv = '#{{ $profile_view }}',
            fileID = '';

        function hideString(str) {
            if (str.length > 4) {
                return '*'.repeat(str.length - 4) + str.slice(-4);
            } else {
                return str;
            }
        }

        getMemberProfile()

        function getMemberProfile() {

            $.ajax( {
                url: '{{ route( 'web.profile.getMemberProfile' ) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    $( pv + '_user_id' ).text( response.uniq ? response.uniq : '-' );
                    $( pv + '_phone_number' ).text( response.calling_code + response.phone_number );
                    $( pv + '_email' ).text( response.email );
                    $( pv + '_joined_date' ).text( response.created_at );

                    if ( response.user_detail ) {

                        $( pv + '_fullname' ).text( response.user_detail.fullname );

                        if ( response.user_detail.photo_path ) {
                            $( pv + '_photo' ).attr( 'src', response.user_detail.photo_path );
                        }
                    }   

                    if ( response.referral ) {
                        $( pv + '_my_referrer' ).text( response.referral.user_detail.fullname );
                    }

                    if ( response.kyc ) {

                        if ( response.kyc.status == 10 ) {
                            $( pv + '_verified' ).removeClass( 'hidden' ); 

                            $( '#beneficiary_details' ).removeClass( 'hidden' );
                            $( '#bank_account_details' ).removeClass( 'hidden' );
                        }
                        
                        if ( response.kyc.user_beneficiary ) {
                            $( pv + '_beneficiary_name' ).text( response.kyc.user_beneficiary.fullname != '' && response.kyc.user_beneficiary.fullname != null ? response.kyc.user_beneficiary.fullname : '-' );
                            $( pv + '_beneficiary_ic' ).text( response.kyc.user_beneficiary.identification_number != '' && response.kyc.user_beneficiary.identification_number != null  ? response.kyc.user_beneficiary.identification_number : '-' );
                            $( pv + '_beneficiary_contact_no' ).text( response.kyc.user_beneficiary.phone_number != '' && response.kyc.user_beneficiary.phone_number != null  ? response.kyc.user_beneficiary.phone_number : '-' );
                        }

                        if ( response.kyc.user_bank ) {
                            $( pv + '_bank_name' ).text( response.kyc.user_bank.bank.name );
                            $( pv + '_account_holder' ).text( response.kyc.user_bank.account_holder_name );
                            $( pv + '_account_number' ).text( response.kyc.user_bank.account_number );
                        }

                    }

                    if ( response.status == 10 ) {
                        $( pv + '_status' ).addClass( "active" );   
                        $( pv + '_status' ).text( "{{ __('member.active') }}" );
                    }else{
                        $( pv + '_status' ).addClass( "suspended" );   
                        $( pv + '_status' ).text( "{{ __('member.suspended') }}" );
                    }

                    $('.asterisks').each(function() {
                        var originalString = $(this).text();
                        $(this).text(hideString(originalString));
                    });

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

        $( pv + '_edit_photo' ).click( function() {
            selectFile();
        });

        function selectFile() {

            let fileId = '';

            $( pv + '_edit_photo_placeholder' ).click();

            $( pv + '_edit_photo_placeholder' ).on('change', function() {
                const selectedFile = this.files[0];
                
                let formData = new FormData();
                formData.append( 'file', selectedFile );
                formData.append( '_token', '{{ csrf_token() }}' );
                
                $.ajax( {
                    url: '{{ route( 'member.file.upload' ) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function( response ) {

                        upadateProfilePhoto( response.data.id )

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
                        $( '#modal_subject' ).html( `{{ __( 'member.x_not_found', [ 'title' => Str::singular( __( 'member.profile_photo' ) ) ] ) }}` );
                        $( '#modal_desc' ).html( errorText );
                        $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                        $('#alert-modal').removeClass('hidden');

                    }
                } );
            });

        }

        function upadateProfilePhoto( fileID ) {
                
            let formData = new FormData();
            formData.append( 'photo', fileID );
            formData.append( '_token', '{{ csrf_token() }}' );
            
            $.ajax( {
                url: '{{ route( 'web.profile.updateMemberProfilePhoto' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.profile_photo' ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.profile_photo_note' ) }}` );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                    $( pv + '_photo' ).attr( 'src', response.photo_path )
                    $( '.profile_photo' ).attr( 'src', response.photo_path )

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
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => Str::singular( __( 'member.edit_profile' ) ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                }
            } );
        }

    });

</script>