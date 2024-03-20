<?php
$location_create = 'location_create';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $location_create }}_rider" class="col-sm-4 col-form-label">{{ __( 'rider.rider_name' ) }}</label>
                    <div class="col-sm-8">
                        <select class="form-select form-select-sm" id="{{ $location_create }}_rider">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'rider.rider_name' ) ] ) }}</option>
                            @foreach ( $data['riders'] as $rider )
                            <option value="{{ $rider->encrypted_id }}">{{ $rider->username }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $location_create }}_source_location" class="col-sm-4 col-form-label">{{ __( 'location.source' ) }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="{{ $location_create }}_source_location">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $location_create }}_destination" class="col-sm-4 col-form-label">{{ __( 'location.destination' ) }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="{{ $location_create }}_destination">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $location_create }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $location_create }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let id = '{{ request( 'id' ) }}',
            lc = '#{{ $location_create }}';

        $( lc + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.location.index' ) }}';
        } );

        $( lc + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            $.ajax( {
                url: '{{ route( 'admin.location.createLocationAdmin' ) }}',
                type: 'POST',
                data: {
                    'rider_id': $( lc + '_rider' ).val(),
                    'source_location': $( lc + '_source_location' ).val(),
                    'destination': $( lc + '_destination' ).val(),
                    '_token': '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.location.index' ) }}';
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( lc + '_' + key ).addClass( 'is-invalid' ).next().text( value );
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