<?php
$currentUser = $data['current_user'];
$directSponsors = $data['direct_sponsors'];
$groupMembers = $data['group_members'];
$totalDirectBonus = $data['total_direct_bonus'];
$totalManagementBonus = $data['total_management_bonus'];
$recentReferrals = $data['recent_referrals'];
?>
<div class="mx-auto max-w-[90vw] md:max-w-[1200px] w-full">
<div class="grid grid-rows-3 grid-cols-1 md:grid-rows-1 md:grid-cols-3 gap-4">
    <a href="{{ route( 'web.profile.index' ) }}" class="flex items-center justify-between pt-12 pb-6 px-4 rounded-lg text-white bg-[#1A1D56] shadow-[0_0_20px_0_rgba(76,87,125,0.02)]">
        <div>
            <h4 class="text-[16px] font-bold mb-2">{{ __('member.my_profile') }}</h4>
            <p class="text-[11px]">{{ __('member.view_your_profile') }}</p>
        </div>
        <i class="icon-icon4 text-[#A2ABC1] text-[3em]"></i>
    </a>
    <a href="{{ route( 'web.asset.index' ) }}" class="flex items-center justify-between pt-12 pb-6 px-4 rounded-lg text-white bg-[#A2ABC1] shadow-[0_0_20px_0_rgba(76,87,125,0.02)]">
        <div>
            <h4 class="text-[16px] font-bold mb-2">{{ __('member.wallet') }}</h4>
            <p class="text-[11px]">{{ __('member.wallet_transactions_topup') }}</p>
        </div>
        <i class="icon-icon5 text-[#c1c5d5] text-[3em]"></i>
    </a>
    <a href="{{route( 'web.membership' )}}" class="flex items-center justify-between pt-12 pb-6 px-4 rounded-lg text-white bg-[#A1A5B7] shadow-[0_0_20px_0_rgba(76,87,125,0.02)]">
        <div>
            <h4 class="text-[16px] font-bold mb-2">{{ __('member.upgrade_membership') }}</h4>
            <p class="text-[11px]">{{ __('member.upgrade_your_membership') }}</p>
        </div>
        <i class="icon-icon6 text-[#d6d8de] text-[3em]"></i>
    </a>
</div>

<div class="grid grid-rows-3 grid-cols-1 md:grid-rows-1 md:grid-cols-3 gap-4 mt-6">
    <div class="bg-white rounded-lg">
        <div class="flex items-center justify-between gap-x-4 border-b border-solid border-[#EDEDED] pr-4">
            <div class="border-solid border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.statistics_overview') }}</h4>
                <p class="text-[11px] text-[#A2ABC1]">{{ __('member.recent_referral_statistics') }}</p>
            </div>
            <a href="{{route( 'web.my_team.index' )}}" class="text-center px-4 gap-x-2 flex items-center bg-[#F5F8FA] px-6 py-2 rounded-md border border-solid border-[#F5F8FA] hover:border-[#1A1D56] hover:bg-[#1A1D56] transition hover:text-white"><i class="icon-icon7"></i><span class="text-[11px]">{{ __('member.view_full') }}</span></a>
        </div>
        <div class="flex justify-between py-4 px-4 mb-4">
            <div>
                <h5 class="text-[#A1A5B7] font-bold text-[13px]">{{ __('member.total_referral') }}</h5>
                <h4 class="text-[#1A1D56] text-[16px]">{{ $directSponsors }}</h4>
            </div>
            <div class="">
                <h5 class="text-[#A1A5B7] font-bold text-[13px]">{{ __('member.direct_bonus') }}</h5>
                <h4 class="text-[#1A1D56] text-[16px]">{{ Helper::numberFormat( $totalDirectBonus, 2, true ) }}</h4>
                <h5 class="text-[#A1A5B7] font-bold text-[13px] mt-4">{{ __('member.management_bonus') }}</h5>
                <h4 class="text-[#1A1D56] text-[16px]">{{ Helper::numberFormat( $totalManagementBonus, 2, true ) }}</h4>
            </div>
        </div>
        <div class="flex justify-between py-4 px-4">
            <div>
                <h5 class="text-[#A1A5B7] font-bold text-[13px]">{{ __('member.total_group') }}</h5>
                <h4 class="text-[#1A1D56] text-[16px]">{{ $groupMembers }}</h4>
            </div>
            <div class="">
                <h5 class="text-[#A1A5B7] font-bold text-[13px]">{{ __('member.my_rank') }}</h5>
                <!-- Missing design if remove no-rank -->
                <h4 class="px-4 py-2 rounded-lg no-rank">{{ $currentUser->ranking->name }}</h4>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg flex flex-col justify-between">
        <div class="flex items-center justify-between gap-x-4 border-b border-solid border-[#EDEDED] pr-4">
            <div class="border-solid border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.action_needed') }}</h4>
                <p class="text-[11px] text-[#A2ABC1]">{{ __('member.complete_task') }}</p>
            </div>
        </div>
        <div class=" py-4 px-4 flex flex-col gap-4 justify-between" style="height:-webkit-fill-available">
            <!-- <div class="progress-bar" data-percent="{{ $currentUser->mission_completed == 1 ? 100 : 0 }}" data-duration="1000" data-color="#ccc"></div> -->
            <div class="flex gap-x-2 justify-between items-center relative">
                <div class="flex gap-x-2 items-center">
                    <span class="rounded-full w-[20px] h-[20px] flex items-center justify-center text-white text-[0.7rem] bg-[#1A1D56]">1</span>
                    <span class="text-[0.8rem] text-[#1A1D56]">{{ __('member.complete_your_kyc') }}</span>
                </div>
                <!-- Not Submit -->
                <!-- <i class="icon-icon38 text-[#d6d8de]"></i> -->
                <!-- Submit & pending for approval-->
                <i class="icon-icon37 text-[#FF9900]"></i>
                <!-- Approved-->
                <!-- <i class="icon-icon38 text-[#50CD89]"></i> -->
                <!-- Rejected-->
                <!-- <i class="icon-icon40 text-[#FF6D6D]"></i> -->
            </div>
            
            <div class="flex gap-x-2 justify-between items-center relative">
                <div class="flex gap-x-2 items-center">
                    <span class="rounded-full w-[20px] h-[20px] flex items-center justify-center text-white text-[0.7rem] bg-[#1A1D56]">2</span>
                    <span class="text-[0.8rem] text-[#1A1D56]">{{ __('member.purchase_membership_now') }}</span>
                </div>
                <!-- Not Submit -->
                <i class="icon-icon38 text-[#d6d8de]"></i>
            </div>
            <div class="flex gap-x-2 justify-between items-center relative">
                <div class="flex gap-x-2 items-center">
                    <span class="rounded-full w-[20px] h-[20px] flex items-center justify-center text-white text-[0.7rem] bg-[#1A1D56]">3</span>
                    <span class="text-[0.8rem] text-[#1A1D56]">{{ __('member.do_your_mission') }}</span>
                </div>
                <!-- Not Submit -->
                <i class="icon-icon38 text-[#d6d8de]"></i>
            </div>
            <!-- Whatever next thing not done yet, this button will redirect to that page, if all done just disabled it -->
            <a href="{{ route( 'web.mission.index' ) }}" class="primary_btn block mx-auto mt-6 w-full">{{ __('member.take_action') }}</a>
        </div>
    </div>
    <div class="bg-white rounded-lg flex flex-col justify-between pb-4">
        <div class="flex items-center justify-between gap-x-4 border-b border-solid border-[#EDEDED] pr-4">
            <div class="border-solid border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.membership_details') }}</h4>
                <p class="text-[11px] text-[#A2ABC1]">{{ __('member.latest_membership_details') }}</p>
            </div>
        </div>
        <div class="px-4 flex flex-col justify-between relative">
            <!-- if have purchased membership -->
            <!-- <img src="{{ asset( 'member/Element/jdg-card1.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="" class="md:w-[160px] lg:w-[200px] h-auto block mx-auto"/> -->
            
            <!-- If not membership purchased yet -->
            <div class="flex flex-col justify-center gap-2 items-center w-full px-4 mt-2 opacity-50">
                <div class="flex justify-center gap-x-2">
                    <img src="{{ asset( 'member/Element/jdg-card1.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="" class="w-1/3"/>
                    <img src="{{ asset( 'member/Element/jdg-card2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="" class="w-1/3"/>
                </div>
                <img src="{{ asset( 'member/Element/jdg-card3.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="" class=" w-1/3 mx-auto mt-[-30px]"/>
            </div>
            <p class="text-center text-[0.8rem] text-[#1A1D56] mt-2">{{ __('member.no_package_purchased') }}</p>
        </div>
        <div class="px-4">
            <a href="{{route( 'web.membership' )}}" class="primary_btn block mx-auto w-full">{{ __('member.upgrade_membership') }}</a>
        </div>
    </div> 
</div>
<div class="grid grid-rows-3 grid-cols-1 md:grid-rows-1 md:grid-cols-3 gap-4 mt-6">
    <div class="bg-white rounded-lg">
        <div class="flex items-center justify-between gap-x-4 border-b border-solid border-[#EDEDED] pr-4">
            <div class="border-solid border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.recent_referrals') }}</h4>
                <p class="text-[11px] text-[#A2ABC1]">{{ __('member.my_recent_referrals') }}</p>
            </div>
        </div>
        @if ( $recentReferrals->count() == 0 )
        <div class="flex justify-between py-4 px-4 mb-4">
            <div>
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.no_referrals_found') }}</h4>
                <p class="text-[11px] text-[#A2ABC1]">{{ __('member.use_referral_link') }}</p>
            </div>
        </div>
        @else
        <div class="py-4 px-4 mb-4">
            @foreach ( $recentReferrals as $key => $rf )
            <div class="flex items-center {{ $key + 1 < $recentReferrals->count() ? 'mb-4' : '' }}">
                <img class="rounded-full w-10 h-10" src="{{ $rf->userDetail->photo_path ? $rf->userDetail->photo_path : 'https://ui-avatars.com/api/?length=1&background=1A1D56&color=fff&name=' . $rf->userDetail->fullname }}"></img>
                <span class="text-[12px] ms-2">
                    <strong>{{ $rf->userDetail->fullname }}</strong><br>
                    <strong>{{ __( 'auth.email' ) }}</strong>: {{ $rf->email }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    <div class="bg-white rounded-lg col-span-2">
        <div class="flex items-center justify-between gap-x-4 pr-4 w-full">
            <div class="border-b border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
                <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.referral_link') }}</h4>
                <p class="text-[11px] text-[#A2ABC1]">{{ __('member.your_referral_link') }}</p>
            </div>
        </div>
        <div class="py-4 px-4 mb-4">
            <p class="text-[11px] text-[#A2ABC1]">{{ __('member.share_referral_link') }}</p>
            <div class="flex justify-between flex-wrap w-full flex items-center justify-between gap-y-4">
                <div class="flex justify-between flex items-center justify-between relative w-full min-[1280px]:w-3/4 mt-4">
                    <input type="text" id="readonlyInput" class="w-full" readonly value="{{ route( 'web.register' ) . '?referral=' . $currentUser->invitation_code }}">
                    <a href="javascript:void(0)" class="text-[#1A1D56] text-[13px] absolute right-4 " id="copyButton"><i class="icon-icon35"></i></button>
                </div>
                <div class="flex items-center gap-x-4 mt-4">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fjdgventures.com%2Fdashboard%2F%3Frefid%3DZWQyOTFiNjI%3D" target="_blank" class="text-[16px] text-[#1A1D56]"><i class="icon-icon51"></i></a>
                    <a href="https://twitter.com/share?text=My%20JDG%20Referral%20Link&url=https://jdgventures.com/dashboard/?refid=ZWQyOTFiNjI=&hashtags=jdgventures" target="_blank" class="text-[16px] text-[#1A1D56]"><i class="icon-icon52"></i></a>
                    <a href="https://www.tiktok.com/" target="_blank" class="text-[16px] text-[#1A1D56]"><i class="icon-icon53"></i></a>
                    <a href="https://wa.me/?text=https%3A%2F%2Fjdgventures.com%2Fdashboard%2F%3Frefid%3DZWQyOTFiNjI%3D" target="_blank" class="text-[16px] text-[#1A1D56]"><i class="icon-icon54"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="{{ asset( 'member/jQuery-plugin-progressbar.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script>
<script>

    document.addEventListener( 'DOMContentLoaded', () => {
        $(".progress-bar").loading();

        $('#copyButton').on('click', function () {
                var contentToCopy = $('#readonlyInput').val();
                var tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(contentToCopy).select();
                document.execCommand('copy');
                tempInput.remove();
            });

    $( '#test' ).on( 'click', () => {

        $.ajax( {
            url: '{{ route( 'api.membership.getOptions' ) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function( response ) {
                console.log( response );
            }
        } );
    } );
} );

</script>