<?php
$mission_edit = 'mission_edit';
?>

<link rel="stylesheet" href="{{ asset( 'member/font/style.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $mission_edit }}_title" class="col-sm-5 col-form-label">{{ __( 'mission.title' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $mission_edit }}_title">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $mission_edit }}_description" class="col-sm-5 col-form-label">{{ __( 'mission.description' ) }}</label>
                    <div class="col-sm-7">
                        <textarea class="form-control form-control-sm" id="{{ $mission_edit }}_description"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $mission_edit }}_link" class="col-sm-5 col-form-label">{{ __( 'mission.link' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $mission_edit }}_link">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <input type="hidden" id="{{ $mission_edit }}_icon_type" value="">

                <div class="mb-3 row">
                    <label for="{{ $mission_edit }}_icon" class="col-sm-5 col-form-label">{{ __( 'mission.icon' ) }}</label>
                    <div class="col-sm-7">
                        <button class="form-select form-select-sm text-start" id="{{ $mission_edit }}_icon" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #ced4da;">
                            <div>
                                <i class="icon-icon51"></i>
                                <span>Facebook</span>
                            </div>
                        </button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item" data-type="facebook">
                                <div>
                                    <i class="icon-icon51"></i>
                                    <span>Facebook</span>
                                </div>
                            </li>
                            <li class="dropdown-item" data-type="tiktok">
                                <i class="icon-icon53"></i>
                                <span>TikTok</span>
                            </li>
                        </ul>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                @if ( 1 == 2 )
                <div class="mb-3 row">
                    <label for="{{ $mission_edit }}_color" class="col-sm-5 col-form-label">{{ __( 'mission.color' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $mission_edit }}_color">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                @endif
                <div class="mb-3 row">
                    <label for="{{ $mission_edit }}_type" class="col-sm-5 col-form-label">{{ __( 'mission.type' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $mission_edit }}_type">
                            <option value="1">{{ __( 'mission.click' ) }}</option>
                            <option value="2">{{ __( 'mission.social_media' ) }}</option>
                            <option value="10">{{ __( 'mission.internal' ) }}</option>
                        </select>
                        <div class="invalid-feedback"></div>
                        <!-- <div class="mt-1">
                            <small><i>Note: Internal for Deposit, Purchase Membership etc.</i></small>
                        </div> -->
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $mission_edit }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $mission_edit }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        getMission();

        let me = '#{{ $mission_edit }}';

        let icons = {
            'facebook': {
                'class': 'icon-icon51',
                'text': 'Facebook'
            },
            'tiktok': {
                'class': 'icon-icon53',
                'text': 'TikTok'
            },
        };

        $( me + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.mission.index' ) }}';
        } );

        $( '.dropdown-item' ).on( 'click', function() {
            let type = $( this ).data( 'type' );

            let html = 
            `
            <div>
                <i class="` + icons[type].class + `"></i>
                <span>` + icons[type].text + `</span>
            </div>
            `;

            $( me + '_icon' ).html( html );
            $( me + '_icon_type' ).val( icons[type].class );
        } );

        // $( me + '_type' ).change( function() {
        //     if ( $( this ).val() == 10 ) {
        //         $( me + '_link' ).attr( 'placeholder', '{{ __( 'template.optional' ) }}' );
        //     } else {
        //         $( me + '_link' ).attr( 'placeholder', '' );
        //     }
        // } );

        $( me + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'id', '{{ request( 'id' ) }}' );
            formData.append( 'title', $( me + '_title' ).val() );
            formData.append( 'description', $( me + '_description' ).val() );
            formData.append( 'link', $( me + '_link' ).val() );
            formData.append( 'icon', $( me + '_icon_type' ).val() );
            formData.append( 'color', $( me + '_color' ).val() );
            formData.append( 'type', $( me + '_type' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.mission.updateMission' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.module_parent.mission.index' ) }}';
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( me + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.toggle();       
                    }
                }
            } );
        } );
        
        function getMission() {

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            $.ajax( {
                url: '{{ route( 'admin.mission.oneMission' ) }}',
                type: 'POST',
                data: {
                    id: '{{ request( 'id' ) }}',
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    let index = '';
                    for ( let key in icons ) {
                        if ( icons[key].class == response.icon ) {
                            index = key;
                            break;
                        }
                    }

                    let html = 
                    `
                    <div>
                        <i class="` + icons[index].class + `"></i>
                        <span>` + icons[index].text + `</span>
                    </div>
                    `;

                    $( me + '_icon' ).html( html );

                    $( me + '_title' ).val( response.title );
                    $( me + '_description' ).val( response.description );
                    $( me + '_link' ).val( response.link );
                    $( me + '_icon_type' ).val( response.icon );
                    $( me + '_color' ).val( response.color );
                    $( me + '_type' ).val( response.type );

                    $( 'body' ).loading( 'stop' );
                },
            } );
        }
    } );
</script>