<?php
$deposits = $data['deposits'];
?>

<div class="mx-auto max-w-[90vw] md:max-w-[800px] w-full pb-12">
    <div class="flex items-center justify-between mb-6">
        <p class="text-[#A1A5B7] text-[12px]"><span class="">{{ count( $deposits ) }}</span> {{ __( 'member.records' ) }}</p>
        <a href="" class="bg-[#A1A5B7] text-white text-[14px] rounded-lg px-4 py-2 hover:bg-[#1A1D56] transition">Last 7 days</a>
    </div>

    @foreach ( $deposits as $deposit )
    <!-- Deposit Box -->
    <div class="rounded-lg bg-white pb-4 mb-6">
        <div class="flex items-center justify-between gap-x-4">
            <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4 flex justify-between items-center">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.deposit' ) }}</h4>
                @if ( $deposit->status == 1 )
                <i class="icon-icon37 pending_status"></i>
                @elseif ( $deposit->status == 10 )
                <!-- Approved icon -->
                <i class="icon-icon38 pending_status"></i>
                @else
                <!-- Rejected icon -->
                <i class="icon-icon40 pending_status"></i>
                @endif
            </div>
        </div>
        <div class="px-4 md:px-6">
            <div class="w-full">
                <div class="flex justify-between gap-x-3 border-b border-solid border-[#eaeaea] py-4">
                    <h4 class="text-[12px] text-[#1A1D56]">{{ __( 'member.bank_transfer' ) }}</h4>
                    <div class="">
                        <h3 class="text-[15px] text-[#1070FF] font-bold text-right">{{ Helper::numberFormat( $deposit->amount, 2, true ) }}</h3>
                        <p class="text-[12px] text-[#A1A5B7] text-right">
                            {{ strtoupper( \Carbon\Carbon::parse( $deposit->created_at )->timezone( 'Asia/Kuala_Lumpur' )->format( 'd M Y - h:i A' ) ) }}
                        </p>
                    </div>
                </div>
                <div class="flex justify-between gap-x-1 py-4 items-center">
                    <h4 class="text-[12px] text-[#1A1D56]">{{ __( 'member.total_deposit_amount' ) }}</h4>
                    <h3 class="text-[15px] text-[#1070FF] font-bold">{{ Helper::numberFormat( $deposit->amount, 2, true ) }}</h3>
                </div>
            </div>
            <!-- Reason for rejected -->
            @if ( $deposit->status == 20 )
            <div class="border-t border-solid border-[#eaeaea] py-4">
                <h4 class="text-[12px] text-[#1A1D56]">{{ __( 'member.reason' ) }}</h4>
                <h3 class="text-[15px] text-[#FF6D6D] font-bold">{{ $deposit->remark }}</h3>
            </div>
            @endif

            <!-- Click to open PDF or image in new tab -->
            @if ( $deposit->depositDocument ) 
            <a href="{{ $deposit->depositDocument->path }}" target="_blank" class="primary_btn w-full block mx-auto transition">{{ __( 'member.view_attachment' ) }}</a>
            @endif
        </div>
    </div>
    @endforeach

    @if ( count( $deposits ) == 0 )
    <!-- If Empty Page -->
    <div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full flex items-center h-[60vh] justify-center pb-24">
        <div>
            <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="350" height="100" class="block w-[80px] mb-4 mx-auto opacity-50"/>           
            <p class="text-center text-[14px] text-[#A1A5B7]">{{ __( 'member.no_content' ) }}</p>
        </div>
    </div>
    @endif
</div>
<script>

    document.addEventListener( 'DOMContentLoaded', () => {
       
    } );

</script>