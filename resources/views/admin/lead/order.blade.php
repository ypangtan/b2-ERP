<?php
$lead_order = 'lead_order';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $lead_order }}_customer" class="col-sm-5 col-form-label">{{ __( 'lead.customer' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_order }}_customer">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_order }}_inventory" class="col-sm-5 col-form-label">{{ __( 'sale.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_order }}_inventory">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_order }}_quantity" class="col-sm-5 col-form-label">{{ __( 'lead.quantity' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $lead_order }}_quantity">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_order }}_remark" class="col-sm-5 col-form-label">{{ __( 'lead.remark' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_order }}_remark">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $lead_order }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $lead_order }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="{{ $lead_order }}_id">

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let lo = '#{{ $lead_order }}';
        
        $( lo + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.lead.index' ) }}';
        } );

        $( lo + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'id', $( lo + '_id' ).val() );
            formData.append( 'quantity', $( lo + '_quantity' ).val() );
            formData.append( 'remark', $( lo + '_remark' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.lead.createOrder' ) }}',
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
                            $( lo + '_' + key ).addClass( 'is-invalid' ).next().text( value );
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

                    $( lo + '_id' ).val( response.encrypted_id );
                    $( lo + '_customer' ).val( response.customers.name );
                    $( lo + '_inventory' ).val( response.inventories.name );
                    $( lo + '_customer' ).attr('disabled',true);
                    $( lo + '_inventory' ).attr('disabled',true);
                    $( 'body' ).loading( 'stop' );
                },
            } );
        }
    } );
</script>