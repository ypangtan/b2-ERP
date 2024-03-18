<?php
$withdrawals = $data['withdrawals'];
?>

<div class="mx-auto max-w-[90vw] md:max-w-[800px] w-full pb-12">
    <div class="flex items-center justify-between mb-6">
        <p class="text-[#A1A5B7] text-[12px]"><span class="">{{ count( $withdrawals ) }}</span> {{ __('member.records') }}</p>
        <a href="" class="bg-[#A1A5B7] text-white text-[14px] rounded-lg px-4 py-2 hover:bg-[#1A1D56] transition">Last 7 days</a>
    </div>

    @foreach ( $withdrawals as $withdrawal )
    <!-- Withdrawal Box -->
    <div class="rounded-lg bg-white pb-4 mb-6">
        <div class="flex items-center justify-between gap-x-4">
            <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4 flex justify-between items-center">
                @if ( $withdrawal->status == 10 )
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.withdraw') }} <span class="text-[12px] text-[#A1A5B7] pl-2 border-l border-solid border-[#A1A5B7] font-normal">Ref: {{ $withdrawal->reference }}</span></h4>
                @else
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.withdraw') }}</h4>
                @endif

                @if ( $withdrawal->status == 1 )
                <i class="icon-icon37 pending_status"></i>
                @elseif ( $withdrawal->status == 10 )
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
                    <h4 class="text-[12px] text-[#1A1D56]">{{ __('member.request_withdraw_amount') }}</h4>
                    <div class="">
                        <h3 class="text-[15px] text-[#1070FF] font-bold text-right">{{ Helper::numberFormat( $withdrawal->amount, 2, true ) }}</h3>
                        <p class="text-[12px] text-[#A1A5B7] text-right">
                            {{ strtoupper( \Carbon\Carbon::parse( $withdrawal->created_at )->timezone( 'Asia/Kuala_Lumpur' )->format( 'd M Y - h:i A' ) ) }}
                        </p>
                    </div>
                </div>
                <div class="flex justify-between gap-x-1 pt-4 items-center">
                    <h4 class="text-[12px] text-[#1A1D56] flex items-center gap-x-2"><span>{{ __('member.service_charge') }}</span>
                        @if ( $withdrawal->service_charge_type == 1 ) 
                        <span>({{ Helper::numberFormat( $withdrawal->service_charge_rate, 0, true ) }}%)</span>
                        @endif
                    </h4>
                    <h3 class="text-[15px] text-[#1070FF] font-bold">{{ Helper::numberFormat( $withdrawal->service_charge_amount, 2, true ) }}</h3>
                </div>
                <div class="flex justify-between gap-x-1 pt-4 pb-4 items-center">
                    <h4 class="text-[12px] text-[#1A1D56]">{{ __('member.total_receive_amount') }}</h4>
                    <h3 class="text-[15px] text-[#1070FF] font-bold">{{ Helper::numberFormat( $withdrawal->amount - $withdrawal->service_charge_amount, 2, true ) }}</h3>
                </div>
            </div>
            @if ( $withdrawal->withdrawalMeta )
            <div class="border-t border-solid border-[#eaeaea] py-4">
                <p class="flex items-center justify-between mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __('member.bank_name') }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold">{{ $withdrawal->withdrawalMeta->bank->name }}</span>
                </p>
                <p class="flex items-center justify-between mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __('member.account_holder') }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold">{{ $withdrawal->withdrawalMeta->account_holder_name }}</span>
                </p>
                <p class="flex items-center justify-between">
                    <span class="text-[12px] text-[#1A1D56]">{{ __('member.account_number') }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold asterisks">{{ $withdrawal->withdrawalMeta->account_number }}</span>
                </p>
            </div>
            @if ( $withdrawal->status == 20 )
            <!-- Only show when rejected -->
            <div class="border-t border-solid border-[#eaeaea] py-4">
                <h4 class="text-[12px] text-[#1A1D56]">{{ __('member.reason') }}</h4>
                <h3 class="text-[15px] text-[#FF6D6D] font-bold">{{ $withdrawal->remark }}</h3>
            </div>
            @endif
            @endif
        </div>
    </div>
    @endforeach

    @if ( count( $withdrawals ) == 0 )
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