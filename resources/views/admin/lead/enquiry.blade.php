<?php
$lead_enquiry = 'lead_enquiry';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $lead_enquiry }}_customer" class="col-sm-5 col-form-label">{{ __( 'lead.customer' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_enquiry }}_customer">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_enquiry }}_inventory" class="col-sm-5 col-form-label">{{ __( 'lead.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_enquiry }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_enquiry }}_remark" class="col-sm-5 col-form-label">{{ __( 'lead.remark' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_enquiry }}_remark">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $lead_enquiry }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $lead_enquiry }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let le = '#{{ $lead_enquiry }}';
        getLead();

        $( le + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.lead.index' ) }}';
        } );

        $( le + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'customer_id', '{{ request( 'id' ) }}' );
            formData.append( 'inventory_id', $( le + '_inventory' ).val() );
            formData.append( 'remark', $( le + '_remark' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.lead.createEnquiry' ) }}',
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
                            $( le + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.toggle();       
                    }
                }
            } );
        } );

        function getLead() {

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            $.ajax( {
                url: '{{ route( 'admin.lead._oneLead' ) }}',
                type: 'POST',
                data: {
                    'id': '{{ request( 'id' ) }}',
                    '_token': '{{ csrf_token() }}'
                },
                success: function( response ) {
                    console.log(response);
                    $( le + '_customer' ).val( response.name );
                    $( le + '_customer' ).attr('disabled',true);
                    $( 'body' ).loading( 'stop' );
                },
            } );
        }
    } );
</script>