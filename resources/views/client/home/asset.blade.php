<?php

$walletInfos = Helper::walletInfos();

?>

<div class="rounded-lg bg-white mx-auto max-w-[90vw] md:max-w-[800px] w-full">
    <a href="{{ route( 'web.asset.history' ) . '?type=' . Helper::encode( 1 ) }}" class="border-b border-solid border-[#EDEDED] text-[#1A1D56] text-[13px] flex items-center justify-between gap-x-4 pr-6">
        <h4 class="border-solid border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4 text-[16px] font-bold text-[#1A1D56]">
            <span>{{ __('member.wallet_1') }}</span>
            <span class="block text-[0.7em] text-[#A1A5B7] font-normal">{{ __('member.wallet_1_note') }}</span>
        </h4>
        <b>{{ Helper::numberFormat( Helper::walletInfos()[1], 2, true ) }}</b> <i class="icon-icon24 text-[12px]"></i>
    </a>
    <div class="px-6 pb-4">
        <div class="grid grid-rows-1 grid-cols-3 px-0 pt-4">
            <a href="{{ route( 'web.deposit.index' ) }}" class="flex items-center justify-between flex-col">
                <i class="icon-icon22 text-[#1A1D56] text-[2em]"></i>
                <span class="text-[#1A1D56] text-[0.8em]">{{ __('member.deposit') }}</span>
            </a>
            <a href="{{ route( 'web.withdrawal.index' ) }}" class="flex items-center justify-between flex-col">
                <i class="icon-icon23 text-[#1A1D56] text-[1.8em]"></i>
                <span class="text-[#1A1D56] text-[0.8em]">{{ __('member.withdraw') }}</span>
            </a>
            <a href="{{ route( 'web.purchase.index' ) }}" class="flex items-center justify-between flex-col">
                <i class="icon-icon26 text-[#1A1D56] text-[1.8em]"></i>
                <span class="text-[#1A1D56] text-[0.8em]">{{ __('member.purchase') }}</span>
            </a>
        </div>
    </div>
</div>
<div class="rounded-lg bg-white mx-auto max-w-[90vw] md:max-w-[800px] w-full mt-8">
    <a href="{{ route( 'web.asset.history' ) . '?type=' . Helper::encode( 2 ) }}" class="text-[#1A1D56] text-[13px] flex items-center justify-between gap-x-4 pr-6">
        <h4 class="border-solid border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4 text-[16px] font-bold text-[#1A1D56]">
            <span>{{ __('member.wallet_2') }}</span>
            <span class="block text-[0.7em] text-[#A1A5B7] font-normal">{{ __('member.wallet_2_note') }}</span>
        </h4>
        <b>{{ Helper::numberFormat( Helper::walletInfos()[2], 2, true ) }}</b> <i class="icon-icon24 text-[12px]"></i>
    </a>
</div>

<!-- Disable for Future Development -->
<!-- <div class="rounded-lg bg-white mx-auto max-w-[90vw] md:max-w-[700px] w-full">
    <div class="flex items-center justify-between gap-x-4">
        <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
            <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.wallet_3') }}</h4>
        </div>
    </div>
    <div class="px-6 pb-4">
        <a href="" class="text-[#1A1D56] text-[13px] py-4 border-b border-solid border-[#EDEDED] flex justify-between items-center"><b>LP 10,000</b> <i class="icon-icon24 text-[12px]"></i></a>
        <div class="grid grid-rows-1 grid-cols-3 px-0 pt-4">
            <a href="" class="flex items-center justify-between flex-col">
                <i class="icon-icon22 text-[#1A1D56] text-[2em]"></i>
                <span class="text-[#1A1D56] text-[0.8em]">{{ __('member.redeem') }}</span>
            </a>
        </div>
    </div>
</div> -->
<script>

    document.addEventListener( 'DOMContentLoaded', () => {
        getUserKYCstatus() //checkUserKycStatus
    } );

</script>