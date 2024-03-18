<?php
$currentUser = \App\Models\User::with( [
    'package'
] )->find( auth()->user()->id );
?>


<div class="flex items-center justify-between gap-x-4 mx-auto max-w-[90vw] md:max-w-[1200px] w-full">
    <!-- If no membership puchased -->
    <p class="text-[#FF0000] text-[13px] mb-6 mt-0 no_membership_note hidden">{{ __('member.membership_page_note') }}</p>
    <!-- If membership puchased -->
    <div class="rounded-lg bg-[#A1A5B7] text-white flex px-6 py-4 w-full gap-x-6 mb-8 membership_dashboard hidden">
        @if ( $currentUser->package_id == 0 )
        <img src="{{ asset( 'member/Element/jdg-card1.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Silver Card Image" width="350" height="100" class="block mx-auto w-full shadow-[0_4px_4px_0_rgba(0,0,0,0.4)] max-w-[300px] rounded-[16px] my-6"/>
        @else
        <img src="{{ asset( 'member/Element/jdg-card' . $currentUser->package_id . '.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="{{ $currentUser->package->name }} Card Image" width="350" height="100" class="block mx-auto w-full shadow-[0_4px_4px_0_rgba(0,0,0,0.4)] max-w-[300px] rounded-[16px] my-6"/>
        @endif
        <div class="border-l border-solid border-white py-4 pl-6 w-full flex flex-col justify-between">
            <p class="text-white text-[16px] font-bold text-right">{{ __('member.current_membership') }} - <span>{{ $currentUser->package ? $currentUser->package->name : __( 'member.silver' ) }}</span></p>
            <div class="">
                <p class="text-[14px] mb-2">{{ __('member.current_purchased_amount') }}</p>
                <p class="text-[12px] mb-2">PV <b class="text-[2em]">{{ Helper::numberFormat( $currentUser->active_amount, 2, true ) }}</b></p>
                <div class="w-full bg-white h-2 rounded-full overflow-hidden mb-2">
                    <div id="progressBar" class="w-[0] h-full bg-[#1A1D56] transition-all duration-500"></div>
                </div>
                <p class="text-[12px]" id="more_point_note">{{ __('member.purchase_more') }} <span id="more_points"></span>{{ __('member.to_unlock') }} <span id="next_package"></span></p>
            </div>
        </div>
    </div>
</div>
<div class="pb-12 mx-auto max-w-[90vw] md:max-w-[1200px] w-full grid grid-cols-1 md:grid-cols-3 items-start md:justify-between gap-8 px-0">
    <div class="flex flex-col justify-between gap-x-4 rounded-lg bg-white">
        <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
            <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.silver_plan') }}</h4>
        </div>
        <div class="px-4">
            <img src="{{ asset( 'member/Element/jdg-card1.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Silver Card Image" width="350" height="100" class="block mx-auto w-full shadow-[0_4px_4px_0_rgba(0,0,0,0.4)] max-w-[300px] rounded-[16px] my-6"/>
        </div>
        <div class="border-b border-t border-solid border-[#EDEDED] py-6">
            <p class="text-[12px] text-[#1A1D56] px-4 mb-2">{{ __('member.purchase_amount') }}</p>    
            <div class="flex items-end px-4">
                <span class="text-[12px] text-[#1A1D56]">PV</span>
                <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">1,000.00</h4>
                <span class="text-[12px] text-[#1A1D56] pr-1">-</span>
                <span class="text-[12px] text-[#1A1D56]">PV</span>
                <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">29,999.00</h4>
            </div>
        </div>
        <div class="">
            <div class="flex items-center justify-between p-4 cursor-pointer accordion_box">
                <h2 class="font-semibold text-[12px] text-[#1A1D56]">{{ __('member.silver_benefit') }}</h2>
                <i class="icon-icon39 text-[12px] rotate-reset"></i>
            </div>
            <div class="p-4 hidden text-[12px] text-[#1A1D56]">
                <p class="flex gap-x-4 justify-between items-center pb-2"><span>{{ __('member.total_rebate') }}</span><span>24%</span></p>
                <p class="flex gap-x-4 justify-between items-center py-2"><span>{{ __('member.monthly_buyback') }}</span><span>1%</span></p>
                <p class="flex gap-x-4 justify-between items-center py-2"><span>{{ __('member.freebies_lucky_draw') }}</span><span>1x</span></p>
            </div>
        </div>
    </div>

    <div class="flex flex-col justify-between gap-x-4 rounded-lg bg-white">
        <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
            <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.gold_plan') }}</h4>
        </div>
        <div class="px-4">
            <img src="{{ asset( 'member/Element/jdg-card2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Silver Card Image" width="350" height="100" class="block mx-auto w-full max-w-[300px] shadow-[0_4px_4px_0_rgba(0,0,0,0.4)] rounded-[16px] my-6"/>
        </div>
        <div class="border-b border-t border-solid border-[#EDEDED] py-6">
            <p class="text-[12px] text-[#1A1D56] px-4 mb-2">{{ __('member.purchase_amount') }}</p>    
            <div class="flex items-end px-4">
                <span class="text-[12px] text-[#1A1D56]">PV</span>
                <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">30,000.00</h4>
                <span class="text-[12px] text-[#1A1D56] pr-1">-</span>
                <span class="text-[12px] text-[#1A1D56]">PV</span>
                <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">49,999.00</h4>
            </div>
        </div>
        <div class="">
            <div class="flex items-center justify-between p-4 cursor-pointer accordion_box">
                <h2 class="font-semibold text-[12px] text-[#1A1D56]">{{ __('member.gold_benefit') }}</h2>
                <i class="icon-icon39 text-[12px] rotate-reset"></i>
            </div>
            <div class="p-4 hidden text-[12px] text-[#1A1D56]">
                <p class="flex gap-x-4 justify-between items-center pb-2"><span>{{ __('member.total_rebate') }}</span><span>31.2%</span></p>
                <p class="flex gap-x-4 justify-between items-center py-2"><span>{{ __('member.monthly_buyback') }}</span><span>1.3%</span></p>
                <p class="flex gap-x-4 justify-between items-center py-2"><span>{{ __('member.freebies_lucky_draw') }}</span><span>2x</span></p>
            </div>
        </div>
    </div>

    <div class="flex flex-col justify-between gap-x-4 rounded-lg bg-white">
        <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
            <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.platinum_plan') }}</h4>
        </div>
        <div class="px-4">
            <img src="{{ asset( 'member/Element/jdg-card3.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Silver Card Image" width="350" height="100" class="block mx-auto w-full max-w-[300px] shadow-[0_4px_4px_0_rgba(0,0,0,0.4)] rounded-[16px] my-6"/>
        </div>
        <div class="border-b border-t border-solid border-[#EDEDED] py-6">
            <p class="text-[12px] text-[#1A1D56] px-4 mb-2">{{ __('member.purchase_amount') }}</p>    
            <div class="flex items-end px-4">
                <span class="text-[12px] text-[#1A1D56]">PV</span>
                <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">50,000.00</h4>
                <span class="text-[12px] text-[#1A1D56] pr-0">and above</span>
            </div>
        </div>
        <div class="">
            <div class="flex items-center justify-between p-4 cursor-pointer accordion_box">
                <h2 class="font-semibold text-[12px] text-[#1A1D56]">{{ __('member.platinum_benefit') }}</h2>
                <i class="icon-icon39 text-[12px] rotate-reset"></i>
            </div>
            <div class="p-4 hidden text-[12px] text-[#1A1D56]">
                <p class="flex gap-x-4 justify-between items-center pb-2"><span>{{ __('member.total_rebate') }}</span><span>36%</span></p>
                <p class="flex gap-x-4 justify-between items-center py-2"><span>{{ __('member.monthly_buyback') }}</span><span>1.5%</span></p>
                <p class="flex gap-x-4 justify-between items-center py-2"><span>{{ __('member.freebies_lucky_draw') }}</span><span>3x</span></p>
            </div>
        </div>
    </div>
    
</div>
<script>
    $(document).ready(function () {
        $('.accordion_box').on('click', function () {
            var content = $(this).next('.hidden');
            content.slideToggle();
            $(this).find('i').toggleClass('rotate');
            $(this).find('i').toggleClass('rotate-reset');
        });
        getUserKYCstatus() //checkUserKycStatus

        //Update progress value 
        var curr_purchase_amt = parseFloat( '{{ $currentUser->active_amount }}' );
        // var progressValue = 80;
        // progressValue = Math.max(0, Math.min(progressValue, 100));
        // $('#progressBar').css('width', progressValue + '%');
        updateProgress();

        function updateProgress (){
            $('.membership_dashboard').removeClass('hidden').addClass('block');
            $('.no_membership_note').removeClass('block').addClass('hidden');
            if (curr_purchase_amt >= 30000 && curr_purchase_amt < 50000) {
                curr_purchase_amt = Math.max(30000, Math.min(curr_purchase_amt, 49999));
                var progressPercentage = ((curr_purchase_amt - 30000) / 19999) * 100;
                $('#progressBar').css('width', progressPercentage + '%');
                $('#more_point_note').html(`{{ __('member.purchase_more') }} <span id="more_points"></span>{{ __('member.to_unlock') }} <span id="next_package"></span>`);
                $('#more_points').html((50000 - curr_purchase_amt).toLocaleString());
                $('#next_package').html("{{ __('member.platinum') }}");
                $('#progressBar').removeClass('bg-[#adaeaf] bg-[#f2bb3b] bg-[#be7877]').addClass('bg-[#f2bb3b]');
            } else if (curr_purchase_amt >= 50000) {
                curr_purchase_amt = Math.max(50000, Math.min(curr_purchase_amt));
                $('#more_point_note').html("{{ __('member.max_level') }}");
                $('#progressBar').css('width', '100%');
                $('#progressBar').removeClass('bg-[#adaeaf] bg-[#f2bb3b] bg-[#be7877]').addClass('bg-[#be7877]');
            } else if (curr_purchase_amt >= 1000){
                curr_purchase_amt = Math.max(1000, Math.min(curr_purchase_amt, 30000));
                var progressPercentage = ((curr_purchase_amt - 0) / 30000) * 100;
                $('#more_point_note').html(`{{ __('member.purchase_more') }} <span id="more_points"></span>{{ __('member.to_unlock') }} <span id="next_package"></span>`);
                $('#more_points').html((30000 - curr_purchase_amt).toLocaleString());
                $('#next_package').html("{{ __('member.gold') }}");
                $('#progressBar').css('width', progressPercentage + '%');
                $('#progressBar').removeClass('bg-[#adaeaf] bg-[#f2bb3b] bg-[#be7877]').addClass('bg-[#adaeaf]');
            } else{
                $('.membership_dashboard').removeClass('block').addClass('hidden');
                $('.no_membership_note').removeClass('hidden').addClass('block');
            }
        }
    });

    

</script>