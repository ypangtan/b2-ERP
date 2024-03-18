<div class="fixed left-0 top-0 h-[100vh] z-[20] bg-[#6754DF] gap-4 justify-between md:flex items-center flex-col py-6 px-4 lg:px-10 md:w-[200px] lg:w-[250px] hidden">
    <div>
        <a href="/"><img src="{{ asset( 'member/ekuitas1.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="" class="md:w-[160px] lg:w-[200px] h-auto block mx-auto"/><a>
        <!-- Navigator -->
        <div class="mt-12">
            @foreach($data as $nav)
            <a href="{{ $nav['direct'] }}" class="flex gap-x-4 items-center my-6 text-[14px] {{ (@$nav['key'] == $active) ? 'text-white' : 'text-[#A1A5B7]' }}">
                @if(@$nav['icon'])
                <i class="{{ @$nav['icon'] }} text-[16px]"></i>
                @endif

                <span class="">{{ $nav['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
    <form action="{{ route( 'web._logout' ) }}" method="POST">
        @csrf
        <!-- <input type="submit" value="Logout"> -->
        <button class="flex gap-x-3 px-4 py-1 rounded-lg items-center my-6 text-[14px] transition text-[#A1A5B7] border-solid border border-[#A1A5B7] hover:border-white hover:text-white">
            <i class="icon-icon18 text-[16px]"></i>
            <span class="">{{ __('member.logout') }}</span>
        </button>
    </form>
</div>
