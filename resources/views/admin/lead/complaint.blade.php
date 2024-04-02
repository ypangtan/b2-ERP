<?php
$lead_complaint = 'lead_complaint';

?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $lead_complaint }}_customer" class="col-sm-5 col-form-label">{{ __( 'lead.customer' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_complaint }}_customer">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_complaint }}_inventory" class="col-sm-5 col-form-label">{{ __( 'lead.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_complaint }}_inventory">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_complaint }}_comment" class="col-sm-5 col-form-label">{{ __( 'lead.comment' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_complaint }}_comment">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_complaint }}_rating" class="col-sm-5 col-form-label">{{ __( 'lead.rating' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $lead_complaint }}_rating" min="0" max="5">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $lead_complaint }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    <button id="{{ $lead_complaint }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let lc = '#{{ $lead_complaint }}';

        $( lc + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.lead.index' ) }}';
        } );

        $( lc + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();            
            formData.append( 'id', ' {{ request( 'id' ) }} ' );
            formData.append( 'comment', $( lc + '_comment' ).val() );
            formData.append( 'rating', $( lc + '_rating' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.lead.createComplaint' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.module_parent.lead.index' ) }}';
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

        getLead();

        function getLead() {

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            $.ajax( {
                url: '{{ route( 'admin.lead.oneLead' ) }}',
                type: 'POST',
                data: {
                    'id': '{{ request( 'id' ) }}',
                    '_token': '{{ csrf_token() }}'
                },
                success: function( response ) {

                    $( lc + '_customer' ).val( response.customers.name );
                    $( lc + '_inventory' ).val( response.inventories.name );
                    $( lc + '_customer' ).attr('disabled',true);
                    $( lc + '_inventory' ).attr('disabled',true);
                    $( 'body' ).loading( 'stop' );
                },
            } );
        }
    } );
</script>