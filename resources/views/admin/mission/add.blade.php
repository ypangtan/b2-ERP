<?php
$mission_create = 'mission_create';
?>

<link rel="stylesheet" href="{{ asset( 'member/font/style.css?v=' ) }}{{ date('Y-m-d-H:i:s') }}">

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $mission_create }}_title" class="col-sm-5 col-form-label">{{ __( 'mission.title' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $mission_create }}_title">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $mission_create }}_description" class="col-sm-5 col-form-label">{{ __( 'mission.description' ) }}</label>
                    <div class="col-sm-7">
                        <textarea class="form-control form-control-sm" id="{{ $mission_create }}_description"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $mission_create }}_link" class="col-sm-5 col-form-label">{{ __( 'mission.link' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $mission_create }}_link">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <input type="hidden" id="{{ $mission_create }}_icon_type" value="icon-icon51">

                <div class="mb-3 row">
                    <label for="{{ $mission_create }}_icon" class="col-sm-5 col-form-label">{{ __( 'mission.icon' ) }}</label>
                    <div class="col-sm-7">
                        <button class="form-select form-select-sm text-start" id="{{ $mission_create }}_icon" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #ced4da;">
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
                    <label for="{{ $mission_create }}_color" class="col-sm-5 col-form-label">{{ __( 'mission.color' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $mission_create }}_color">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                @endif
                <div class="mb-3 row">
                    <label for="{{ $mission_create }}_type" class="col-sm-5 col-form-label">{{ __( 'mission.type' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $mission_create }}_type">
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
                    <button id="{{ $mission_create }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $mission_create }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let mc = '#{{ $mission_create }}';

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

        $( mc + '_cancel' ).click( function() {
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

            $( mc + '_icon' ).html( html );
            $( mc + '_icon_type' ).val( icons[type].class );
        } );

        // $( mc + '_type' ).change( function() {
        //     if ( $( this ).val() == 10 ) {
        //         $( mc + '_link' ).attr( 'placeholder', '{{ __( 'template.optional' ) }}' );
        //     } else {
        //         $( mc + '_link' ).attr( 'placeholder', '' );
        //     }
        // } );

        $( mc + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'title', $( mc + '_title' ).val() );
            formData.append( 'description', $( mc + '_description' ).val() );
            formData.append( 'link', $( mc + '_link' ).val() );
            formData.append( 'icon', $( mc + '_icon_type' ).val() );
            formData.append( 'color', $( mc + '_color' ).val() );
            formData.append( 'type', $( mc + '_type' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.mission.createMission' ) }}',
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
                            $( mc + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.toggle();       
                    }
                }
            } );
        } );
    } );
</script>