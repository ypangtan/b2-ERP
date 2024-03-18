<?php
$transactions = $data['transactions'];
$walletInfos = Helper::walletInfos();
$walletType = $data['wallet_type'];
?>

<div class="mx-auto max-w-[90vw] md:max-w-[800px] w-full pb-12 relative">
    <div class="rounded-lg bg-white mb-8">
        <div class="flex items-center justify-between gap-x-4">
            <div class="border-solid border-l-[2px] border-l-[#1A1D56] px-4 w-full py-3 flex justify-between items-center">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.wallet_' . $walletType . '_balance') }}</h4>
                <div class="">
                    <h3 class="text-[15px] text-[#1070FF] font-bold text-right">
                        {{ Helper::numberFormat( $walletInfos[$walletType], 2, true ) }}
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-between mb-6">
        <p class="text-[#A1A5B7] text-[12px]"><span class="">{{ count( $transactions ) }}</span> {{ __('member.records') }}</p>
        <a href="" class="bg-[#A1A5B7] text-white text-[14px] rounded-lg px-4 py-2 hover:bg-[#1A1D56] transition">Last 7 days</a>
    </div>

    @foreach ( $transactions as $transaction )
    <?php
    $positive = $transaction->amount > 0 ? '+' : '-';
    ?>
    <!-- Wallet Box -->
    <div class="rounded-lg bg-white mb-6">
        <div class="flex items-center justify-between gap-x-4">
            <div class="border-solid border-l-[2px] border-l-[#1A1D56] px-4 w-full py-3 flex justify-between items-center">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ $transaction->transaction_type_name }}</h4>
                <div class="">
                    <h3 class="text-[15px] text-[#1070FF] font-bold text-right">
                        {{ $positive }} {{ Helper::numberFormat( abs( $transaction->amount ), 2, true ) }}
                    </h3>
                    <p class="text-[12px] text-[#A1A5B7] text-right">{{ strtoupper( \Carbon\Carbon::parse( $transaction->created_at )->timezone( 'Asia/Kuala_Lumpur' )->format( 'd M Y - h:i A' ) ) }}</p>
                </div>
            </div>
        </div>
        <div class="border-t border-[#EDEDED] border-solid px-4 md:px-6 py-4">
            <h4 class="text-[12px] text-[#1A1D56]">{{ __('member.remarks') }}:</h4>
            <p class="text-[12px] text-[#A1A5B7]">{{ $transaction->converted_remark }}</p>
        </div>
    </div>
    @endforeach

    @if ( 1 == 2 )
    <div class="rounded-lg bg-white mb-6">
        <div class="flex items-center justify-between gap-x-4">
            <div class="border-solid border-l-[2px] border-l-[#1A1D56] px-4 w-full py-3 flex justify-between items-center">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.withdraw') }}</h4>
                <div class="">
                    <h3 class="text-[15px] text-[#1070FF] font-bold text-right">- 40,000.00</h3>
                    <p class="text-[12px] text-[#A1A5B7] text-right">02 FEB 2021 - 03:24 PM</p>
                </div>
            </div>
        </div>
        <div class="border-t border-[#EDEDED] border-solid px-4 md:px-6 py-4 hidden">
            <h4 class="text-[12px] text-[#1A1D56]">{{ __('member.remarks') }}:</h4>
            <p class="text-[12px] text-[#A1A5B7]">Shows remarks if there is any</p>
        </div>
    </div>
    @endif

    @if ( count( $transactions ) == 0 )
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