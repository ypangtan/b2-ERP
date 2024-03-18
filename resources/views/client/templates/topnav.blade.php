<?php //(@Auth::user()->name): ?>
<nav class="fixed top-0 left-0 right-0 w-full z-[12] bg-white shadow-[0_10px_30px_0_rgba(31,43,93,0.15)] py-4 px-4 flex justify-end items-center">
    <div class="flex gap-x-6 items-center">
            @if(@$header['history_url'])
            <a href="{{ @$header['history_url'] }}" class="BTN-HISTORY">
                <span>{{ __('ms.history') }}</span>
                <i class="icon-icon2 ml-[0.5rem]"></i>
            </a>
            @endif

            @if(@$header['filterNav'])
            <a onclick="open_modal('date-filter-modal')" class="BTN-HISTORY">
                <span>{{ __('ms.filters_by') }}</span>
                <i class="icon-icon2 ml-[0.5rem]"></i>
            </a>
            @endif
            <a href="{{ route( 'web.announcement.index' ) }}" class=""><i class="icon-icon11 text-[#1A1D56] text-[16px]"></i></a>
            {{-- <a href="" class="bg-[#A2ABC1] rounded-md w-[35px] h-[35px] text-center flex items-center justify-center"><span class="text-white text-[16px]">L</span></a> --}}
            <?php
                $path = auth()->user()->userDetail->photo_path ? auth()->user()->userDetail->photo_path : 'https://ui-avatars.com/api/?length=1&background=A2ABC1&color=fff&name='.auth()->user()->userDetail->fullname;
                $defaultClass = $path ? '' : 'bg-[#A2ABC1]' ;
            ?>
            <img src="{{ $path }}" alt="" class="{{ $defaultClass }} rounded-md w-[35px] h-[35px] text-center flex items-center justify-center profile_photo" style="" />
    </div>
</nav>

<div class="w-[70%] text-left">
    <span class="NAV-TITLE">{{ (@$header['title'])? $header['title'] : '' }}</span>
</div>


@if(@$header['re_link'])
<a href="{{ @$header['re_link'] }}">
                <i class="icon-icon1 text-[0.875rem]"></i>
            </a>
@else
@endif
<?php //endif;?>



