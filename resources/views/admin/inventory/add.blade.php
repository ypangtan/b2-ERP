<?php
$inventory_create = 'inventory_create';

?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $inventory_create }}_name" class="col-sm-5 col-form-label">{{ __( 'inventory.name' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $inventory_create }}_name">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_create }}_price" class="col-sm-5 col-form-label">{{ __( 'inventory.price' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $inventory_create }}_price">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_create }}_category" class="col-sm-5 col-form-label">{{ __( 'inventory.category' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $inventory_create }}_category">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'inventory.category' ) ] ) }}</option>
                            @foreach( $data['categories'] as $category )
                            <option value="{{ $category['value'] }}">{{ $category['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_create }}_type" class="col-sm-5 col-form-label">{{ __( 'inventory.type' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $inventory_create }}_type">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'inventory.type' ) ] ) }}</option>
                            @foreach( $data['types'] as $type )
                            <option value="{{ $type['value'] }}">{{ $type['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_create }}_desc" class="col-sm-5 col-form-label">{{ __( 'inventory.desc' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $inventory_create }}_desc">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_create }}_stock" class="col-sm-5 col-form-label">{{ __( 'inventory.stock' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $inventory_create }}_stock">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $inventory_create }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    <button id="{{ $inventory_create }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let ic = '#{{ $inventory_create }}';

        $( ic + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.inventory.index' ) }}';
        } );

        $( ic + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'name', $( ic + '_name' ).val() );
            formData.append( 'price', $( ic + '_price' ).val() );
            formData.append( 'category', $( ic + '_category' ).val() );
            formData.append( 'type', $( ic + '_type' ).val() );
            formData.append( 'desc', $( ic + '_desc' ).val() );
            formData.append( 'stock', $( ic + '_stock' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.inventory.createInventory' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.module_parent.inventory.index' ) }}';
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( ic + '_' + key ).addClass( 'is-invalid' ).next().text( value );
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