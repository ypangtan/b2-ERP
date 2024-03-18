<?php echo view( 'client/templates/header', [ 'header' => @$header ] );?>
    <!-- Web Navigation -->
    <!-- Left -->
    <?php echo view( 
        'client/templates/sitenav', 
        [ 
            'header' => @$header, 
            'active' => @$header['active'] ,
            'data' => [
                [
                    'key' => "home",
                    'direct' => route( 'web.home' ),
                    'icon' => "icon-icon45",
                    'label' => __( 'member.home' ),
                ],
                [
                    'key' => "kyc",
                    'direct' => route( 'web.kyc.index' ),
                    'icon' => "icon-icon46",
                    'label' => __( 'member.kyc' ),
                ],
                [
                    'key' => "membership",
                    'direct' => route( 'web.membership' ),
                    'icon' => "icon-icon6",
                    'label' => __( 'member.membership' ),
                ],
                [
                    'key' => "asset",
                    'direct' => route( 'web.asset.index' ),
                    'icon' => "icon-icon5",
                    'label' => __( 'member.asset' ),
                ],
                [
                    'key' => "profile",
                    'direct' => route( 'web.profile.index' ),
                    'icon' => "icon-icon4 px-[2px]",
                    'label' => __( 'member.my_profile' ),
                ],
                [
                    'key' => "my_team",
                    'direct' => route( 'web.my_team.index' ),
                    'icon' => "icon-icon43",
                    'label' => __( 'member.my_team' ),
                ]
            ],
        ] 
    ); 
    ?>
    <!-- Left -->

    <!-- Top Nav -->
    <?php echo view( 'client/templates/topnav' );?>
    <div class="relative w-full max-w-[100%] min-h-[90vh] md:max-w-[calc(100vw-200px)] lg:max-w-[calc(100vw-250px)] mr-0 ml-auto bg-[#f5f6fa] mt-16 pt-6 pb-16 px-4">
        <div class="mx-auto {{ @$header['active'] }}_page max-w-[90vw] md:max-w-[1200px] gap-6 w-full flex mb-4 justify-between items-center">
            @if ( @$header['re_link'] )
            <a href="{{$header['re_link']}}" class="text-[1.3em] text-[#1A1D56] font-bold flex items-center gap-x-3"><i class="icon-icon24 scale-x-[-1] text-[16px]"></i><span>{{ @$header['title'] }}</span></a>
                @if ( @$header['second_link'] )
                <a href="{{$header['second_link']}}" class="primary_btn transition"><span>{{ @$header['button_title'] }}</span></a>
                @endif
            @else
            <h3 class="text-[1.3em] text-[#1A1D56] font-bold">{{ @$header['title'] }}</h3>
                @if ( @$header['second_link'] )
                <a href="{{$header['second_link']}}" class="primary_btn transition"><span>{{ @$header['button_title'] }}</span></a>
                @endif
            @endif
        </div>
        <?php echo view( $content, [ 'data' => @$data ] ); ?>
        
        <?php echo view( 'client/templates/alert-modal' ); ?>
        <form id="logout_form" action="{{ route( 'web._logout' ) }}" method="POST">
        @csrf
        </form>
        <div id="footer" class="text-center mt-12 absolute bottom-6 left-0 right-0 mx-auto text-[12px]">
            <p>&copy; <span id="copyrightYear"></span> Ekuitas. All Rights Reserved.</p>
        </div>
    </div>


    <!-- Whatsapp Float Button -->
    <a href="#" class="fixed bottom-4 right-4 flex justify-center items-center rounded-full border border-solid border-[#1A1D56] bg-[#1A1D56] w-[40px] h-[40px] text-[#fff]"> 
        <i class="icon-icon54 text-[1.5em]"></i>
    </a>
<script>
    $(document).ready(function () {
        var currentYear = new Date().getFullYear();
        $( '#copyrightYear' ).text(currentYear);
    });

    function getUserKYCstatus() {

        let formData = new FormData();
        formData.append( '_token', '{{ csrf_token() }}' );

        $.ajax( {
            url: '{{ route( 'web.kyc.getMemberKyc' ) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function( response ) {

                let kycStatus = false;

                if( !$.isEmptyObject( response ) ) {
                    if( response.status == 10 ) {
                        kycStatus = true;
                    }
                }
                
                if( !kycStatus ) {
                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/kyc_icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.kyc_required' ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.kyc_required_note' ) }}` );
                    $( '#modal_btn' ).html( `{{ __( 'member.complete_kyc' ) }}` );
                    $( '#alert-modal' ).removeClass( 'hidden' );

                    $( '.close_btn, #modal_btn' ).on( 'click', function (){
                        window.location.href = '{{ route( 'web.kyc.index' ) }}';
                    });
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

                $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/kyc_icon.png' ) }}` );
                $( '#modal_subject' ).html( `{{ __( 'member.kyc_required' ) }}` );
                $( '#modal_desc' ).html( `{{ __( 'member.kyc_required_note' ) }}` );
                $( '#modal_btn' ).html( `{{ __( 'member.complete_kyc' ) }}` );
                $( '#alert-modal' ).removeClass( 'hidden' );

                $( '.close_btn, #modal_btn' ).on( 'click', function (){
                    window.location.href = '{{ route( 'web.kyc.index' ) }}';
                });

            }
        } );

    }

    
</script>
<!-- <?php //echo view( 'member/footer' ); ?> -->

<script src="{{ asset( 'member/Scripts/js/flatpickr-4.6.13.js' ) . Helper::assetVersion() }}"></script>
<script src="{{ asset( 'member/Scripts/js/select2.min.js' ) . Helper::assetVersion() }}"></script>
<script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>