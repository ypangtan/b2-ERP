<?php
$announcement = $data['announcement'];
?>

<div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full rounded-lg bg-white px-6 py-4">
    @if ( $announcement->image )
    <img src="{{ $announcement->path }}" alt="Logo Image" width="1350" height="800" class="block w-full mb-4"/>
    @else
    <img src="{{ asset( 'member/Element/announcements-pic.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="1350" height="800" class="block w-full mb-4"/>
    @endif
    <div class="">
        <h3 class="text-[15px] font-bold text-[#1A1D56]">{{ $announcement->title }}</h3>
        <p class="my-2 text-[#A1A5B7] text-[13px]">
        {!! $announcement->content !!}
        </p>
        
        <p class="text-[#A1A5B7] text-[11px] text-right">
            {{ strtoupper( \Carbon\Carbon::parse( $announcement->created_at )->timezone( 'Asia/Kuala_Lumpur' )->format( 'd M Y - h:i A' ) ) }}
        </p>
    </div>
</div>