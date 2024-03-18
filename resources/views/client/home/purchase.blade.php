<?php
$walletInfos = Helper::walletInfos();
$currentUser = $data['current_user'];
?>

<div class="mx-auto max-w-[90vw] md:max-w-[800px] gap-6 w-full block md:grid md:grid-cols-2 md:grid-rows-1">
    <div>
        <div class="rounded-lg bg-white w-full mb-6">
            @if ( $walletInfos[1] >= 1000 )
            <!-- If have more or equal to MYR 1k in wallet -->
            <div class="flex items-center justify-between gap-x-4 border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.wallet_1') }}</h4>
                <h3 class="text-[15px] text-[#1070FF] font-bold text-right">{{ Helper::numberFormat( $walletInfos[1], 2, true ) }}</h3>
            </div>
            @else
            <!-- If have less than MYR 1k in JDG Wallet -->
            <div class="flex items-center justify-between gap-x-4 border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] px-4 w-full py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.wallet_1') }}</h4>
                <h3 class="text-[15px] text-[#FF0000] font-bold text-right">{{ Helper::numberFormat( $walletInfos[1], 2, true ) }}</h3>
            </div>
            <a href="{{ route( 'web.deposit.index' ) }}" class="primary_btn mx-auto transition max-w-[200px] block mt-4">{{ __('member.deposit') }}</a>
            <p class="text-center text-[11px] text-[#FF0000] py-2">{{ __('member.insufficient_amount') }}</p>
            @endif
        </div>
        <div class="rounded-lg bg-white w-full mb-6">
            <div class="flex items-center justify-between gap-x-4">
                <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
                    <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.purchase_amount') }}</h4>
                    <p class="text-[11px] text-[#A9A8A8]">{{ __('member.min_purchase') }}</p>
                </div>
            </div>
            <div class="flex justify-center items-center gap-x-2 py-4 px-4">
                <a href="javascript:void(0)" class="secondary_btn selected" id="silver_btn">{{ __('member.silver_plan') }}</a>
                <a href="javascript:void(0)" class="secondary_btn" id="gold_btn">{{ __('member.gold_plan') }}</a>
                <a href="javascript:void(0)" class="secondary_btn" id="platinum_btn">{{ __('member.platinum_plan') }}</a>
            </div>
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4">
                <div class="flex items-center justify-between mb-2 md:mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __('member.amount_myr') }}</span>
                    <div class="flex items-center justify-center gap-x-2">
                        <i class="icon-icon31" onclick="deductAmount('#purchase_amount',100)"></i>

                        <div class="text-center">
                            <input type="text" class="max-w-[110px] text-center" placeholder="0.00" style="padding-top:0 !important;padding-bottom:0 !important;" id="purchase_amount"/>
                        </div>

                        <i class="icon-icon21" onclick="addAmount('#purchase_amount',100)"></i>
                    </div>
                </div>
                <p class="flex items-center justify-between mb-2 md:mb-2">
                    <span class="text-[12px] text-[#1A1D56]">{{ __('member.total_purchase_amount') }}</span>
                    <span class="text-[#1A1D56] text-[13px] font-bold" id="final_amount">0.00</span>
                </p>
            </div>
        </div>
        <div class="rounded-lg bg-white w-full mb-6">
            <div class="flex items-center justify-between gap-x-4">
                <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
                    <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.sms_verification') }}</h4>
                </div>
            </div>
            <div class="border-t border-solid border-[#eaeaea] py-4 px-4 relative">
                <input type="text" id="otp" placeholder="{{ __( 'member.request_otp' ) }}" class="w-full">
                <div id="request_otp" class="w-fit underline block absolute text-[14px] right-6 text-[#1A1D56] top-6" role="button">{{ __( 'member.request_otp' ) }}</div>
                <div id="countdown" class="hidden w-fix block absolute text-[14px] right-6 text-[#1A1D56] top-6">01:00</div>
                <p class="text-[#1A1D56] text-[12px] mt-4">{{ __( 'member.sms_otp_note' ) }}</p>
            </div>
        </div>
    </div>
    <div class="relative h-[calc(700px+5vw)] sm:h-[820px] md:h-[750px] md:h-auto">
        <div id="silver_btn_content" class="absolute md:relative w-full">
        <div class="flex flex-col justify-between gap-x-4 rounded-lg bg-white">
            <div class="border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4 relative">
                <h4 class="text-[16px] font-bold text-[#1A1D56] silver_btn_content plan-content transition-opacity duration-300 opacity-100">{{ __('member.silver_plan') }}</h4>
                <h4 class="text-[16px] font-bold text-[#1A1D56] gold_btn_content plan-content absolute transition-opacity duration-300 opacity-0 top-4">{{ __('member.gold_plan') }}</h4>
                <h4 class="text-[16px] font-bold text-[#1A1D56] platinum_btn_content plan-content absolute transition-opacity duration-300 opacity-0 top-4">{{ __('member.platinum_plan') }}</h4>
            </div>
            <div class="relative">
                <img src="{{ asset( 'member/Element/jdg-card1.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Silver Card Image" width="350" height="100" class="block mx-auto w-full shadow-[0_4px_4px_0_rgba(0,0,0,0.4)] max-w-[80%] rounded-[16px] my-6 silver_btn_content plan-content transition-opacity duration-300 opacity-100"/>
                <img src="{{ asset( 'member/Element/jdg-card2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Gold Card Image" width="350" height="100" class="block mx-auto w-full shadow-[0_4px_4px_0_rgba(0,0,0,0.4)] max-w-[80%] left-0 right-0 rounded-[16px] my-6 gold_btn_content plan-content absolute transition-opacity duration-300 opacity-0 top-0"/>
                <img src="{{ asset( 'member/Element/jdg-card3.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Platinum Card Image" width="350" height="100" class="block mx-auto w-full shadow-[0_4px_4px_0_rgba(0,0,0,0.4)] max-w-[80%] left-0 right-0 rounded-[16px] my-6 platinum_btn_content plan-content absolute transition-opacity duration-300 opacity-0 top-0"/>
            </div>
            <div class="relative px-4 border-t border-solid border-[#EDEDED] py-6">
                <p class="text-[12px] text-[#1A1D56] mb-2">{{ __('member.current_purchased_amount') }}</p>
                <div class="w-full bg-[#A1A5B733] h-2 rounded-full overflow-hidden mb-2">
                    <div id="progressBar" class="w-[0] h-full bg-[#1A1D56] transition-all duration-500"></div>
                </div>
                <p class="text-[12px]" id="more_point_note">{{ __('member.purchase_more') }} <span id="more_points"></span>{{ __('member.to_unlock') }} <span id="next_package"></span></p>
            </div>
            <div class="border-b border-t border-solid border-[#EDEDED] py-6 relative">
                <p class="text-[12px] text-[#1A1D56] px-4 mb-2">{{ __('member.purchase_amount') }}</p>    
                <div class="flex items-end px-4 silver_btn_content plan-content transition-opacity duration-300 opacity-100">
                    <span class="text-[12px] text-[#1A1D56]">PV</span>
                    <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">1,000.00</h4>
                    <span class="text-[12px] text-[#1A1D56] pr-1">-</span>
                    <span class="text-[12px] text-[#1A1D56]">PV</span>
                    <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">29,999.00</h4>
                </div>
                <div class="flex items-end px-4 absolute gold_btn_content plan-content transition-opacity duration-300 opacity-0 top-[50px]">
                    <span class="text-[12px] text-[#1A1D56]">PV</span>
                    <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">30,000.00</h4>
                    <span class="text-[12px] text-[#1A1D56] pr-1">-</span>
                    <span class="text-[12px] text-[#1A1D56]">PV</span>
                    <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">49,999.00</h4>
                </div>
                <div class="flex items-end px-4 absolute platinum_btn_content plan-content transition-opacity duration-300 opacity-0 top-[50px]">
                    <span class="text-[12px] text-[#1A1D56]">PV</span>
                    <h4 class="text-[19px] text-[#1A1D56] font-bold px-1 leading-6">50,000.00</h4>
                    <span class="text-[12px] text-[#1A1D56] pr-0">and above</span>
                </div>
            </div>
            <div class="">
                <div class="flex items-center justify-between p-4 cursor-pointer accordion_box relative">
                    <h2 class="font-semibold text-[12px] text-[#1A1D56] silver_btn_content plan-content transition-opacity duration-300 opacity-100">{{ __('member.silver_benefit') }}</h2>
                    <h2 class="font-semibold text-[12px] text-[#1A1D56] gold_btn_content absolute plan-content transition-opacity duration-300 opacity-0">{{ __('member.gold_benefit') }}</h2>
                    <h2 class="font-semibold text-[12px] text-[#1A1D56] platinum_btn_content absolute plan-content transition-opacity duration-300 opacity-0">{{ __('member.platinum_benefit') }}</h2>
                    <!-- <i class="icon-icon39 text-[12px] rotate"></i> -->
                </div>
                <div class="p-4 text-[12px] text-[#1A1D56]">
                    <p class="flex gap-x-4 justify-between items-center pb-2">
                        <span>{{ __('member.total_rebate') }}</span><span class="silver_btn_content plan-content transition-opacity duration-300 opacity-100">24%</span>
                        <span class="gold_btn_content absolute plan-content transition-opacity duration-300 opacity-0 right-4">31.2%</span>
                        <span class="platinum_btn_content absolute plan-content transition-opacity duration-300 opacity-0 right-4">36%</span>
                    </p>
                    <p class="flex gap-x-4 justify-between items-center py-2">
                        <span>{{ __('member.monthly_buyback') }}</span><span class="silver_btn_content plan-content transition-opacity duration-300 opacity-100">1%</span>
                        <span class="gold_btn_content absolute plan-content transition-opacity duration-300 opacity-0 right-4">1.3%</span>
                        <span class="platinum_btn_content absolute plan-content transition-opacity duration-300 opacity-0 right-4">1.5%</span>
                    </p>
                    <p class="flex gap-x-4 justify-between items-center py-2">
                        <span>{{ __('member.freebies_lucky_draw') }}</span><span class="silver_btn_content plan-content transition-opacity duration-300 opacity-100">1x</span>
                        <span class="gold_btn_content absolute plan-content transition-opacity duration-300 opacity-0 right-4">2x</span>
                        <span class="platinum_btn_content absolute plan-content transition-opacity duration-300 opacity-0 right-4">3x</span>
                    </p>
                </div>
            </div>
        </div>
        </div>
    </div>

    <input type="hidden" id="identifier">

    <div class="md:col-span-2">
        <br>
        <button type="button" id="submit_btn" class="primary_btn transition w-full mb-2 mt-0 max-w-[200px] mx-auto block">{{ __( 'member.purchase' ) }}</button>
    </div>
</div>

<script>
    $(document).ready(function () {
        
        /* Change Plan Tab Content */
        $('.secondary_btn').on('click', function () {
            var planId = $(this).attr('id');

            // Update selected plan and content
            updateSelectedPlan(planId);
            updatePlanContent(planId);
            
            // Update input value based on the clicked plan
            switch (planId) {
                case 'silver_btn':
                    updateInputValue(1000);
                    updateProgress();
                    break;
                case 'gold_btn':
                    updateInputValue(30000);
                    updateProgress();
                    break;
                case 'platinum_btn':
                    updateInputValue(50000);
                    updateProgress();
                    break;
                default:
                    break;
            }
        });

        $('.accordion_box').on('click', function () {
            var content = $(this).next('.hidden');
            content.slideToggle();
            $(this).find('i').toggleClass('rotate');
            $(this).find('i').toggleClass('rotate-reset');
        });


        $( '#request_otp' ).on( 'click', function() {
            requestOTP();
        } );

        $( '#submit_btn' ).on( 'click', function() {
            purchase();
        } );

        function requestOTP() {

            $.ajax( {
                url: '{{ route( 'web.purchase.requestOtp' ) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    countdownOTP();

                    $( '#request_otp' ).addClass( 'hidden' );

                    $( '#identifier' ).val( response.data.identifier );
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

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/failed-icon.png?v=1.2' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.withdraw' ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');
                }
            } );
        }

        function countdownOTP() {

            $( '#request_otp' ).addClass( 'hidden' );
            $( '#countdown' ).removeClass( 'hidden' );

            let next60Second = new Date().getTime() + 61000;

            let x = setInterval( function() {

                let now = new Date().getTime();

                let distance = next60Second - now;

                if ( distance <= 0 ) {
                    clearInterval( x );
                    $( '#request_otp' ).removeClass( 'hidden' );
                    $( '#countdown' ).addClass( 'hidden' ).html( '01:00' );
                    return 0;
                }

                let minutes = Math.floor( ( distance % ( 1000 * 60 * 60 ) ) / ( 1000 * 60 ) );
                let seconds = Math.floor( ( distance % ( 1000 * 60 ) ) / 1000 );

                $( '#countdown' ).html( minutes.toString().padStart( 2, 0 ) + ':' + seconds.toString().padStart( 2, 0 ) );

            }, 1000 );
        }

        function purchase() {

            $.ajax( {
                url: '{{ route( 'web.purchase.purchase' ) }}',
                method: 'POST',
                data: {
                    otp_code: $( '#otp' ).val(),
                    identifier: $( '#identifier' ).val(),
                    amount: $( '#purchase_amount' ).val(),
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( response.message );
                    $( '#modal_desc' ).html( '' );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                    $( '.close_btn, #modal_btn' ).on( 'click', function (){
                        window.location.href = '{{ route( 'web.purchase.history' ) }}';
                    });
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

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/failed-icon.png?v=1.2' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x', [ 'title' => __( 'member.purchase' ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');
                },
            } );
        }

        $( '#purchase_amount' ).on( 'change', function() {
            changeAmount();
            updateProgress();
            if ($( '#purchase_amount' ).val() <= 29999) {
                updateSelectedPlan('silver_btn');
                updatePlanContent('silver_btn');
            }else if ($( '#purchase_amount' ).val() >= 30000 && $( '#purchase_amount' ).val() < 50000){
                updateSelectedPlan('gold_btn');
                updatePlanContent('gold_btn');
            }else if ($( '#purchase_amount' ).val() >= 50000){
                updateSelectedPlan('platinum_btn');
                updatePlanContent('platinum_btn');
            }else{
                return ;
            }
        } );

        @if ( 1 == 2 )
        $( '#purchase_amount' ).on( 'input', function() {
            var sanitizedValue = $(this).val().replace(/[^0-9.]/g, '');

            // Restrict decimal places to a maximum of 2
            var decimalParts = sanitizedValue.split('.');
            if (decimalParts.length > 1) {
                decimalParts[1] = decimalParts[1].slice(0, 2);
                sanitizedValue = decimalParts.join('.');
            }

            // Ensure the value is not less than 0
            sanitizedValue = Math.max(parseFloat(sanitizedValue) || 0, 0);

            // Update the input value
            $(this).val(sanitizedValue.toFixed( 2 ));
            $( '#final_amount' ).html( sanitizedValue.toFixed( 2 ) )
            if (sanitizedValue <= 29999) {
                updateSelectedPlan('silver_btn');
                updatePlanContent('silver_btn');
            }else if (sanitizedValue >= 30000 && sanitizedValue < 50000){
                updateSelectedPlan('gold_btn');
                updatePlanContent('gold_btn');
            }else if (sanitizedValue >= 50000){
                updateSelectedPlan('platinum_btn');
                updatePlanContent('platinum_btn');
            }else{
                return ;
            }

        } );
        @endif
    } );

    function addAmount(id, val)
    {
        let cur = parseFloat($(id).val());
        if(isNaN(cur))
        {
            cur = 0;
        }
        var num = parseFloat(cur + val);
        $(id).val(num.toFixed(2));
        changeAmount(); 
        updateProgress();
    }

    function deductAmount(id, val)
    {
        let cur = parseFloat($(id).val());
        if(isNaN(cur))
        {
            cur = 0;
        }
        if((cur - val) < 0)
        {   
            var zero = 0;
            $(id).val(zero.toFixed(2));
        }else{
            var num = parseFloat(cur - val);
            $(id).val(num.toFixed(2));
        }
        changeAmount();
        updateProgress();
    }

    function changeAmount() {

        let amount = $( '#purchase_amount' ).val();
        if (isNaN( amount ) ) {
            amount = 0;
        }

        let finalAmount = parseFloat( amount );
        if ( finalAmount <= 0 ) {
            $( '#final_amount' ).html( '0.00' );
        } else {
            $( '#final_amount' ).html( finalAmount.toFixed( 2 ) );
        }

        if (amount <= 29999) {
            updateSelectedPlan('silver_btn');
            updatePlanContent('silver_btn');
            $( '#final_amount' ).html( finalAmount.toFixed( 2 ) )
        }else if (amount >= 30000 && amount < 50000){
            updateSelectedPlan('gold_btn');
            updatePlanContent('gold_btn');
            $( '#final_amount' ).html( finalAmount.toFixed( 2 ) )
        }else if (amount >= 50000){
            updateSelectedPlan('platinum_btn');
            updatePlanContent('platinum_btn');
            $( '#final_amount' ).html( finalAmount.toFixed( 2 ) )
        }else{
            return ;
        }
    }

    function updatePlanContent(planId) {
        $('.plan-content').removeClass('opacity-100').addClass('opacity-0');
        $('.' + planId + '_content').addClass('opacity-100');
    }

    function updateSelectedPlan(planId) {
        $('.secondary_btn').removeClass('selected');
        $('#' + planId).addClass('selected');
    }

    function updateInputValue(value) {
        $('#purchase_amount').val(value.toFixed(2));
        $( '#final_amount' ).html( value.toFixed( 2 ) )
    }

    /* Update the User Current PV here */
    var user_current_pv = '{{ $currentUser->active_amount }}';
    updateProgress();

    function updateProgress (){
        var inputValue = parseFloat($('#purchase_amount').val()) || 0;
        var curr_purchase_amt = parseFloat( user_current_pv ) + inputValue;
        // Change color based on the input value
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
        } else {
            curr_purchase_amt = Math.max(0, Math.min(curr_purchase_amt, 30000));
            var progressPercentage = ((curr_purchase_amt - 1000) / 30000) * 100;
            $('#more_point_note').html(`{{ __('member.purchase_more') }} <span id="more_points"></span>{{ __('member.to_unlock') }} <span id="next_package"></span>`);
            $('#more_points').html((30000 - curr_purchase_amt).toLocaleString());
            $('#next_package').html("{{ __('member.gold') }}");
            $('#progressBar').css('width', progressPercentage + '%');
            $('#progressBar').removeClass('bg-[#adaeaf] bg-[#f2bb3b] bg-[#be7877]').addClass('bg-[#adaeaf]');
        }
    }

</script>