 <div class="rounded-lg bg-white mx-auto max-w-[90vw] md:max-w-[1200px] w-full">
    <div class="relative grid md:grid-rows-1 grid-rows-2 md:grid-cols-1 grid-cols-1 py-4 auto-rows-auto" id="page3">
        <div class="card-body">
            @if ( auth()->user()->kyc->status == 10 )
                <div class="flex flex-col items-center text-center gap-3">
                    <img src="{{ asset( 'member/Element/kyc_icon.png' ). Helper::assetVersion() }}{{ date( 'Y-m-d-H:i:s' ) }}" alt="complete Image" id="image-complete" class="block mx-auto max-w-[50px]"/>
                
                    <h5 class="card-title">{{ __( 'user_kyc.kyc_completed' ) }}</h5>
                    <p>{{ __( 'user_kyc.submitted' ) }}</p>
                    <a href="{{route( 'web.membership' )}}" class="primary_btn w-[200px]">{{ __( 'member.buy_membership' ) }}</a>
                    <img src="{{ asset( 'member/Element/kyc_vector.png' ). Helper::assetVersion() }}{{ date( 'Y-m-d-H:i:s' ) }}" alt="complete Image" id="image-complete" class="block mx-auto w-[280px]"/>
                </div>
            @elseif ( auth()->user()->kyc->status == 2 )
                <div class="flex flex-col items-center text-center gap-3">
                    <img src="{{ asset( 'member/Element/kyc_icon.png' ). Helper::assetVersion() }}{{ date( 'Y-m-d-H:i:s' ) }}" alt="complete Image" id="image-complete" class="block mx-auto max-w-[50px]"/>
                
                    <h5 class="card-title">{{ __( 'user_kyc.kyc_submitted' ) }}</h5>
                    <p>{{ __( 'user_kyc.kyc_submitted_note' ) }}</p>
                    <a href="{{route( 'web.home' )}}" class="primary_btn w-[200px]">{{ __( 'member.ok' ) }}</a>
                    <img src="{{ asset( 'member/Element/kyc_vector.png' ). Helper::assetVersion() }}{{ date( 'Y-m-d-H:i:s' ) }}" alt="complete Image" id="image-complete" class="block mx-auto w-[280px]"/>
                </div>
            @endif
        </div>
    </div>
</div>