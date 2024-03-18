<?php
$myTeam = $data['my_team'];
?>

<section class="mx-auto max-w-[90vw] md:max-w-[800px] w-full">

    <!-- Total Direct Sponsor Sales -->
    <div class="flex bg-white border-b-2 border-solid border-[#1A1D56] rounded-t-lg">
        <div class="w-1/2 text-center border-r border-solid border-[#EDEDED] py-4">
            <h1 class="font-bold text-[18px] md:text-[23px] text-[#1A1D56]">{{ $myTeam['direct_sponsors'] }}</h1>
            <div class="text-[11px] text-[#1A1D56]">{{ __( 'member.total_direct_sponsor' ) }}</div>
        </div>
        <div class="w-1/2 text-center py-4">
            <h1 class="font-bold text-[18px] md:text-[23px] text-[#1A1D56]">{{ Helper::numberFormat( $myTeam['direct_sponsor_sales'], 2, true ) }}</h1>
            <div class="text-[11px] text-[#1A1D56]">{{ __( 'member.total_direct_sponsor_sales' ) }}</div>
        </div>
    </div>

    <!-- Total Team Member Sales -->
    <div class="flex bg-white border-b-2 border-solid border-[#1A1D56] rounded-t-lg mt-6">
        <div class="w-1/2 text-center border-r border-solid border-[#EDEDED] py-4">
            <h1 class="font-bold text-[18px] md:text-[23px] text-[#1A1D56]">{{ $myTeam['group_members'] }}</h1>
            <div class="text-[11px] text-[#1A1D56]">{{ __( 'member.total_group_member' ) }}</div>
        </div>
        <div class="w-1/2 text-center py-4">
            <h1 class="font-bold text-[18px] md:text-[23px] text-[#1A1D56]">{{ Helper::numberFormat( $myTeam['group_member_sales'], 2, true ) }}</h1>
            <div class="text-[11px] text-[#1A1D56]">{{ __( 'member.total_group_member_sales' ) }}</div>
        </div>
    </div>
    <form method="GET" action="" id="searchName" class="mt-4 relative">
        <div class="flex-items-center w-100 position-relative">
            <i class="icon-icon3 search_icon absolute right-4 top-3"></i>
            <input type="text" id="searchDownline" name="name" class="border border-solid border-[#1A1D56]" placeholder="{{ __( 'member.search' ) }}" value="{{ @$_GET['name'] ? @$_GET['name'] : '' }}"/>
        </div>
    </form>
    <div style="overflow:auto;" class="mt-4">
        <div class="tree-view-box team_box rounded-b-lg show_downline bg-white w-[90vw] md:w-[100vw] md:max-w-[calc(95vw-200px)] lg:max-w-[800px]">
            <div class="flex items-center gap-x-4 border-b border-solid border-b-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4 w-full py-4">
                <img id="display_ranking_image" src="{{ asset( 'member/Rank/1.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="" class="w-[65px] md:w-[80px] h-auto mr-[0.5rem]">
                <div>
                    <div class="flex gap-x-2">
                        <h4 class="text-[12px] md:text-[16px] font-bold text-[#1A1D56] mb-1" id="display_username">{{ auth()->user()->email }}</h4>
                        <span class="text-[12px] md:text-[16px] font-bold text-[#1A1D56]" id="display_tag">({{ __('member.you') }})</span>
                    </div>
                    <div class="text-[10px] md:text-[12px] bg-[#1A1D56] rounded-md text-center py-1 text-white px-3 md:px-4 w-fit" id="display_ranking_name">Member</div>
                </div>
            </div>
            <div class="team_inner_2 flex justify-between px-0 md:px-6 py-4">
                <div class="w-[30%] text-center">
                    <div class="team_value" id="display_ps">{{ Helper::numberFormat( $myTeam['personal_sales'], 2, true ) }}</div>
                    <div class="team_label mb-[0.25rem]">{{  __( 'member.my_package' ) }}</div>
                </div>
                <div class="w-[30%] text-center">
                    <div class="team_value" id="display_dss">{{ Helper::numberFormat( $myTeam['direct_sponsor_sales'], 2, true ) }}</div>
                    <div class="team_label mb-[0.25rem]">{{ __( 'member.direct_sales' ) }}</div>
                </div>
                <div class="w-[30%] text-center">
                    <div class="team_value" id="display_gms">{{ Helper::numberFormat( $myTeam['group_member_sales'], 2, true ) }}</div> 
                    <div class="team_label mb-[0.25rem]">{{ __( 'member.group_sales' ) }}</div>
                </div>
            </div>
            <div class="flex justify-end items-center px-3 md:px-6 py-2 md:py-4">
                <!-- <p class="text-[12px] font-bold">1st {{ __('member.gen') }}</p> -->
                <div class="team_count block w-fit flex items-center gap-x-2 text-[12px] md:text-[14px]">
                    {{ __('member.group_member') }}: 
                    <span class="text-right font-bold" id="display_group_member">{{ $myTeam['group_members'] }}</span>
                    <i class="icon-icon43 font-normal"></i>
                </div>
            </div>
        </div>

        <div id="before_directsponsors"></div>
        <div id="directsponsors"></div>

    </div>
        
</section>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<style>
    .jstree-icon.jstree-themeicon {
        display: none;
    }
    .jstree-ocl {
        visibility: hidden;
    }
    .jstree-anchor {
        height: auto !important;
        
        margin:auto;
    }
    .jstree-default .jstree-anchor {
        width: 100%;
        min-width: 320px
    }
    .jstree-default .jstree-clicked {
        background: unset !important;
        box-shadow: unset;
    }
    .jstree-default .jstree-hovered {
        background: unset !important;
        box-shadow: unset;
    }
    .jstree-default>.jstree-container-ul>.jstree-node{
        text-align: center;
    }
    #directsponsors{
        text-align:center;
    }
</style>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        $( '#directsponsors' ).jstree( {
            core: {
                themes: {
                    dots: false,
                    icons: false,
                },
                data: {
                    url : function() {
                        return '{{ route( 'web.my_team.myTeamAjax' ) }}';
                    },
                    data : function (node) {
                        return { 
                            id : node.id,
                        };
                    }
                }
            }
        } ).on( 'click', '.jstree-anchor', function ( e ) {
            $( '#directsponsors' ).jstree( true ).toggle_node( e.target );
            $(this).siblings('.jstree-children').html() != undefined ? $(this).find("i.team_arrow").toggleClass('spin_up spin_down'): $(this).find("i.team_arrow").toggleClass('spin_down spin_up');
        } );

        let priceFormatter = new Intl.NumberFormat( 'en', {
            maximumFractionDigits: 2, 
            minimumFractionDigits: 2, 
        } );
        let myself = '{{ auth()->user()->email }}';
        let timeout = null;
        $( '#searchDownline' ).on( 'keyup clear', function() {

            let text = $( this ).val();

            clearTimeout( timeout );
            timeout = setTimeout( function() {

                $( '#directsponsors' ).remove();

                $( '<div id="directsponsors"></div>' ).insertAfter( $( '#before_directsponsors' ) );

                $.ajax( {
                    type: 'POST',
                    url: '{{ route( 'web.my_team.myTeamData' ) }}',
                    data: { 
                        email: text,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function( data ) {

                        $( '#display_username' ).html( data.email );
                        $( '#display_ranking_name' ).html( data?.display_rank );
                        // $( '#display_ranking_image' ).attr( 'src', '{{ asset( 'member/Rank/' ) }}' + data.ranking_id + '.png' );
                        $( '#display_group_member' ).html( data.group_members );

                        console.log( data );

                        $( '#display_ps' ).html( priceFormatter.format( data.personal_sales ) );
                        $( '#display_dss' ).html( priceFormatter.format( data.direct_sponsor_sales ) );
                        $( '#display_gms' ).html( priceFormatter.format( data.group_member_sales ) );

                        if ( data.email != myself ) {
                            $( '#display_tag' ).addClass( 'hidden' );
                        } else {
                            $( '#display_tag' ).removeClass( 'hidden' );
                        }
                    }
                } );

                $( '#directsponsors' ).jstree( {
                    core: {
                        themes: {
                            dots: false,
                            icons: false,
                        },
                        data: {
                            url : function() {
                                return '{{ route( 'web.my_team.myTeamAjax' ) }}?email=' + text;
                            },
                            data : function (node) {
                                return { 
                                    id : node.id
                                };
                            }
                        }
                    }
                } ).on( 'click', '.jstree-anchor', function ( e ) {
                    $( '#directsponsors' ).jstree( true ).toggle_node( e.target );
                } );
            }, 750 );
        } );
    } );
</script>