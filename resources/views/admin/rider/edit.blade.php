<?php
$rider_edit = 'rider_edit';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $rider_edit }}_username" class="col-sm-4 col-form-label">{{ __( 'rider.rider_name' ) }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="{{ $rider_edit }}_username">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $rider_edit }}_fullname" class="col-sm-4 col-form-label">{{ __( 'rider.fullname' ) }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="{{ $rider_edit }}_fullname">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $rider_edit }}_ic" class="col-sm-4 col-form-label">{{ __( 'rider.ic' ) }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="{{ $rider_edit }}_ic">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $rider_edit }}_email" class="col-sm-4 col-form-label">{{ __( 'rider.email' ) }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="{{ $rider_edit }}_email">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <input type="hidden" id="{{ $rider_edit }}_calling_code" value="+60">
                <div class="mb-3 row">
                    <label for="{{ $rider_edit }}_phone_number" class="col-sm-4 col-form-label">{{ __( 'rider.phone_number' ) }}</label>
                    <div class="col-sm-8">
                        <div class="input-group phone-number">
                            <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #ced4da;">+60</button>
                            <ul class="dropdown-menu" id="phone_number_country">
                                <li class="dropdown-item" data-call-code="+60">+60</li>
                                <li class="dropdown-item" data-call-code="+65">+65</li>
                            </ul>
                            <input type="text" class="form-control form-control-sm" id="{{ $rider_edit }}_phone_number">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $rider_edit }}_password" class="col-sm-4 col-form-label">{{ __( 'rider.password' ) }}</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control form-control-sm" id="{{ $rider_edit }}_password" autocomplete="new-password">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $rider_edit }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $rider_edit }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="rider_edit_id" />

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let id = '{{ request( 'id' ) }}',
            re = '#{{ $rider_edit }}';
        
        $( 'body' ).loading( { 
            message: '{{ __( 'template.loading' ) }}',
        }, 'start' );
        
        $.ajax( {
            url: '{{ route( 'admin.rider.oneRider' ) }}',
            type: 'POST',
            data: { id, '_token': '{{ csrf_token() }}', },
            success: function( response ) {
                
                $( re + '_username' ).val( response.username );
                $( re + '_fullname' ).val( response.fullname );
                $( re + '_ic' ).val( response.ic );
                $( re + '_email' ).val( response.email );
                $( re + '_phone_number' ).val( response.phone_number );

                $( 'body' ).loading( 'stop' );
            },
        } );

        $( '.dropdown-item' ).on( 'click', function() {
            let callingCode = $( this ).data( 'call-code' );
            $( '.phone-number > button' ).html( callingCode );
            $( re + '_calling_code' ).val( callingCode );
        } );

        $( re + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.rider.index' ) }}';
        } );

        $( re + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            $.ajax( {
                url: '{{ route( 'admin.rider.updateRiderAdmin' ) }}',
                type: 'POST',
                data: {
                    'id': '{{ request()->input( 'id' ) }}',
                    'username': $( re + '_username' ).val(),
                    'fullname': $( re + '_fullname' ).val(),
                    'ic': $( re + '_ic' ).val(),
                    'email': $( re + '_email' ).val(),
                    'calling_code': $( re + '_calling_code' ).val(),
                    'phone_number': $( re + '_phone_number' ).val(),
                    'password': $( re + '_password' ).val(),
                    '_token': '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.rider.index' ) }}';
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( re + '_' + key ).addClass( 'is-invalid' ).next().text( value );
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

