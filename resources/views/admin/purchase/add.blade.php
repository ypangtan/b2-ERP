<?php
$purchase_create = 'purchase_create';

?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $purchase_create }}_supplier" class="col-sm-5 col-form-label">{{ __( 'purchase.supplier' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $purchase_create }}_supplier">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'purchase.supplier' ) ] ) }}</option>
                            @foreach( $data['suppliers'] as $supplier )
                            <option value="{{ $supplier['value'] }}">{{ $supplier['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $purchase_create }}_inventory" class="col-sm-5 col-form-label">{{ __( 'purchase.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $purchase_create }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'purchase.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $purchase_create }}_price" class="col-sm-5 col-form-label">{{ __( 'purchase.price' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $purchase_create }}_price">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $purchase_create }}_quantity" class="col-sm-5 col-form-label">{{ __( 'purchase.quantity' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $purchase_create }}_quantity">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $purchase_create }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    <button id="{{ $purchase_create }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let pc = '#{{ $purchase_create }}';

        $( pc + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.purchase.index' ) }}';
        } );

        $( pc + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'supplier_id', $( pc + '_supplier' ).val() );
            formData.append( 'inventory_id', $( pc + '_inventory' ).val() );
            formData.append( 'price', $( pc + '_price' ).val() );
            formData.append( 'quantity', $( pc + '_quantity' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.purchase.createPurchase' ) }}',
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
                            $( pc + '_' + key ).addClass( 'is-invalid' ).next().text( value );
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