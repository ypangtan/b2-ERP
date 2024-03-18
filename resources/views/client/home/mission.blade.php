<?php
$missions = $data['data'];
?>

@if ( auth()->user()->package_id > 0 )
<div class="mission_container mx-auto max-w-[90vw] md:max-w-[1200px] w-full pb-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 grid-rows-auto gap-4">
    @foreach ( $missions as $mission )
    <div class="rounded-lg bg-white shadow-[0_6px_6px_0_rgba(0,0,0,0.2)]">
        <div class="flex items-center justify-between border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4">
            <i class="{{ $mission->icon }} text-[2em]"></i>
            @if ( $mission->currentMonthCompleted )
            <a class="px-3 py-1 flex items-center gap-x-2 primary_btn transition disabled" style="cursor: not-allowed;"><i class="icon-icon41 text-[7px]"></i><span>{{ __( 'member.done' ) }}</span></a>
            @else
            <a href="{{ $mission->link }}" target="_blank" class="px-3 py-1 flex items-center gap-x-2 primary_btn transition" data-id="{{ $mission->encrypted_id }}"><i class="hidden"></i><span>{{ __( 'member.open' ) }}</span></a>
            @endif
        </div>
        <div class="py-4 px-4">
            <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ $mission->title }}</h4>
            <p class="text-[#A1A5B7] text-[13px]">{{ $mission->description }}</p>
        </div>
    </div>
    @endforeach

    @if ( $missions->count() == 0 )
    <!-- If Empty Page -->
    <div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full flex items-center h-[60vh] justify-center pb-24">
        <div>
            <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="350" height="100" class="block w-[80px] mb-4 mx-auto opacity-50"/>           
            <p class="text-center text-[14px] text-[#A1A5B7]">{{ __( 'member.no_content' ) }}</p>
        </div>
    </div>
    @endif
</div>
@else
<!-- If no task shows this -->
<div class="rounded-lg bg-white mx-auto max-w-[90vw] md:max-w-[700px] w-full flex flex-col text-center justify-center items-center gap-4 py-8">
    <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date( 'Y-m-d-H:i:s' ) }}" alt="Logo Image" width="80" height="100" class="block mx-auto"/>
    <h1 class="font-bold text-[#181C32] text-center text-[19px] mb-0">{{ __( 'member.no_package_purchased' ) }}</h1>
    <h3 class="font-bold text-[#A1A5B7] text-center text-[15px] max-w-[300px]">{{ __( 'member.no_package_purchased_note' ) }}</h1>
    <a href="{{route( 'web.membership' )}}" class="primary_btn w-[300px] my-2">{{ __( 'member.membership' ) }}</a>
    <img src="{{ asset( 'member/Element/membership-vector.png?v=' ) }}{{ date( 'Y-m-d-H:i:s' ) }}" alt="Logo Image" width="250" height="200" class="block mx-auto"/>  
</div>
@endif

<script>
    document.addEventListener( 'DOMContentLoaded', () => {
        $( '.mission_container .primary_btn' ).on( 'click', function( e ) {
            e.preventDefault();
            if ( $( this ).hasClass( 'disabled' ) ) {
                return true;
            }
            doMission( $( this ), $( this ).data( 'id' ) );
        } );

        function doMission( that, id ) {

            $.ajax( {
                url: '{{ route( 'web.mission.doMission' ) }}',
                method: 'POST',
                data: {
                    id,
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    if ( response.status ) {
                        that.find( 'span' ).text( '{{ __( 'member.done' ) }}' );
                        that.find( 'i' ).removeClass( 'hidden' ).addClass( 'icon-icon41 text-[7px]' );
                        that.addClass( 'disabled' );
                    }

                    window.open( that.attr( 'href' ) );
                }
            } )
        }
    } );
</script>