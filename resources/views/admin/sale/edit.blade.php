<?php
$sale_edit = 'sale_edit';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $sale_edit }}_customer_id" class="col-sm-5 col-form-label">{{ __( 'sale.customer' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $sale_edit }}_customer_id" disabled>
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'sale.customer' ) ] ) }}</option>
                            @foreach( $data['customers'] as $customer )
                            <option value="{{ $customer['value'] }}">{{ $customer['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $sale_edit }}_inventory_id" class="col-sm-5 col-form-label">{{ __( 'sale.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $sale_edit }}_inventory_id">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'sale.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $sale_edit }}_remark" class="col-sm-5 col-form-label">{{ __( 'sale.remark' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $sale_edit }}_remark">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $sale_edit }}_quantity" class="col-sm-5 col-form-label">{{ __( 'inventory.quantity' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $sale_edit }}_quantity">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $sale_edit }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $sale_edit }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let se = '#{{ $sale_edit }}';
        
        $( se + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.sale.index' ) }}';
        } );

        $( se + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'id', '{{ request( 'id' ) }}' );
            formData.append( 'customer_id', $( se + '_customer_id' ).val() );
            formData.append( 'inventory_id', $( se + '_inventory_id' ).val() );
            formData.append( 'remark', $( se + '_remark' ).val() );
            formData.append( 'quantity', $( se + '_quantity' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.sale.updateSale' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.module_parent.sale.index' ) }}';
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( se + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.toggle();       
                    }
                }
            } );
        } );

        getAdministrator();

        function getAdministrator() {

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            $.ajax( {
                url: '{{ route( 'admin.sale.oneSale' ) }}',
                type: 'POST',
                data: {
                    'id': '{{ request( 'id' ) }}',
                    '_token': '{{ csrf_token() }}'
                },
                success: function( response ) {

                    $( se + '_customer_id' ).val( response.leads.customers.encrypted_id );
                    $( se + '_inventory_id' ).val( response.leads.inventories.encrypted_id );
                    $( se + '_remark' ).val( response.remark );
                    $( se + '_quantity' ).val( response.quantity );

                    $( 'body' ).loading( 'stop' );
                },
            } );
        }
    } );
</script>