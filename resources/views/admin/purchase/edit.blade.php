<?php
$purchase_edit = 'purchase_edit';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $purchase_edit }}_supplier" class="col-sm-5 col-form-label">{{ __( 'purchase.supplier' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $purchase_edit }}_supplier">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'purchase.supplier' ) ] ) }}</option>
                            @foreach( $data['suppliers'] as $supplier )
                            <option value="{{ $supplier['value'] }}">{{ $supplier['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $purchase_edit }}_inventory" class="col-sm-5 col-form-label">{{ __( 'purchase.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $purchase_edit }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'purchase.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $purchase_edit }}_price" class="col-sm-5 col-form-label">{{ __( 'inventory.price' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $purchase_edit }}_price">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $purchase_edit }}_quantity" class="col-sm-5 col-form-label">{{ __( 'inventory.quantity' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $purchase_edit }}_quantity">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $purchase_edit }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $purchase_edit }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let pe = '#{{ $purchase_edit }}';
        
        $( pe + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.purchase.index' ) }}';
        } );

        $( pe + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'id', '{{ request( 'id' ) }}' );
            formData.append( 'supplier_id', $( pe + '_supplier' ).val() );
            formData.append( 'inventory_id', $( pe + '_inventory' ).val() );
            formData.append( 'price', $( pe + '_price' ).val() );
            formData.append( 'quantity', $( pe + '_quantity' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.purchase.updatePurchase' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.module_parent.purchase.index' ) }}';
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( pe + '_' + key ).addClass( 'is-invalid' ).next().text( value );
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
                url: '{{ route( 'admin.purchase.onePurchase' ) }}',
                type: 'POST',
                data: {
                    'id': '{{ request( 'id' ) }}',
                    '_token': '{{ csrf_token() }}'
                },
                success: function( response ) {

                    $( pe + '_supplier' ).val( response.suppliers.encrypted_id );
                    $( pe + '_inventory' ).val( response.inventories.encrypted_id );
                    $( pe + '_quantity' ).val( response.quantity );
                    $( pe + '_price' ).val( response.price );

                    $( 'body' ).loading( 'stop' );
                },
            } );
        }
    } );
</script>