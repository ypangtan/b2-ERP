<?php
$purchaseHistories = $data['purchase_histories'];
?>

<div class="mx-auto max-w-[90vw] md:max-w-[800px] w-full pb-12">
    <div class="flex items-center justify-between mb-6">
        <p class="text-[#A1A5B7] text-[12px]"><span class="">{{ count( $purchaseHistories ) }}</span> {{ __( 'member.records' ) }}</p>
        <a href="" class="bg-[#A1A5B7] text-white text-[14px] rounded-lg px-4 py-2 hover:bg-[#1A1D56] transition">Last 7 days</a>
    </div>

    @foreach ( $purchaseHistories as $purchaseHistory )
    <!-- Purchase Box -->
    <div class="rounded-lg bg-white mb-6">
        <div class="flex items-center justify-between gap-x-4">
            <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4 flex justify-between items-center">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.purchase' ) }} </h4>
            </div>
        </div>
        <div class="px-4 md:px-6">
            <div class="w-full">
                <div class="flex justify-between gap-x-3 py-4">
                    <div class="flex items-center gap-x-3">
                        <h4 class="text-[12px] text-[#1A1D56] font-bold">{{ $purchaseHistory->package->name }}</h4>
                        <img src="{{ asset( 'member/Element/jdg-card' . $purchaseHistory->package_id . '.png?v=' ) }}{{ date( 'Y-m-d-H:i:s' ) }}" alt="Silver Card Image" width="50" height="50" class="block mx-auto w-[30px]"/>
                    </div>
                    <div class="">
                        <h3 class="text-[15px] text-[#1070FF] font-bold text-right">PV {{ Helper::numberFormat( $purchaseHistory->amount, 2, true ) }}</h3>
                        <p class="text-[12px] text-[#A1A5B7] text-right">
                            {{ strtoupper( \Carbon\Carbon::parse( $purchaseHistory->created_at )->timezone( 'Asia/Kuala_Lumpur' )->format( 'd M Y - h:i A' ) ) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if ( count( $purchaseHistories ) == 0 )
    <!-- If Empty Page -->
    <div class="mx-auto max-w-[90vw] md:max-w-[700px] w-full flex items-center h-[60vh] justify-center pb-24">
        <div>
            <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date( 'Y-m-d-H:i:s' ) }}" alt="Logo Image" width="350" height="100" class="block w-[80px] mb-4 mx-auto opacity-50"/>           
            <p class="text-center text-[14px] text-[#A1A5B7]">{{ __( 'member.no_content' ) }}</p>
        </div>
    </div>
    @endif

    @if ( 1 == 2 )
    <div class="rounded-lg bg-white mb-6">
        <div class="flex items-center justify-between gap-x-4">
            <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4 flex justify-between items-center">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __( 'member.total_rebate' ) }} </h4>
            </div>
        </div>
        <div class="px-4 md:px-6">
            <div class="w-full">
                <div class="flex justify-between gap-x-3 py-4">
                    <div class="flex items-center gap-x-3">
                        <h4 class="text-[12px] text-[#1A1D56] font-bold">{{ __( 'member.wallet_2' ) }}</h4>
                        <img src="{{ asset( 'member/Element/jdg-card1.png?v=' ) }}{{ date( 'Y-m-d-H:i:s' ) }}" alt="Silver Card Image" width="50" height="50" class="block mx-auto w-[30px]"/>
                    </div>
                    <div class="">
                        <h3 class="text-[15px] text-[#1070FF] font-bold text-right">PV 480.00</h3>
                        <p class="text-[12px] text-[#A1A5B7] text-right">
                        02 FEB 2021 - 03:24 PM
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<script>

    document.addEventListener( 'DOMContentLoaded', () => {
       
    } );

</script>