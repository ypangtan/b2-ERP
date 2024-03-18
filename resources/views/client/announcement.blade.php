<?php
$announcements = $data['announcements'];
?>

<div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full">
    @foreach ( $announcements as $announcement )
    <a href="{{ route( 'web.announcement.detail', [ 'id' => $announcement->encrypted_id ] ) }}" class="rounded-lg bg-white px-6 py-4 flex gap-x-4 items-start mb-4">
        <!-- This Image no need integrate -->
        <img src="{{ asset( 'member/Element/announcements-pic.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="350" height="100" class="block w-[60px]"/>           
        <div class="">
            <h3 class="text-[15px] font-bold text-[#1A1D56]">{{ $announcement->title }}</h3>
            <p class="text-[#A1A5B7] text-[11px]">
                {{ strtoupper( \Carbon\Carbon::parse( $announcement->created_at )->timezone( 'Asia/Kuala_Lumpur' )->format( 'd M Y - h:i A' ) ) }}
            </p>
            <p class="mt-2 text-[#A1A5B7] text-[13px] announcement_content">
            {!! $announcement->content !!}
            </p>
        </div>
    </a>
    @endforeach

    @if ( count( $announcements ) == 0 )
    <!-- If Empty Page -->
    <div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full flex items-center h-[60vh] justify-center pb-24">
        <div>
            <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="350" height="100" class="block w-[80px] mb-4 mx-auto opacity-50"/>           
            <p class="text-center text-[14px] text-[#A1A5B7]">{{ __( 'member.no_content' ) }}</p>
        </div>
    </div>
    @endif
</div>