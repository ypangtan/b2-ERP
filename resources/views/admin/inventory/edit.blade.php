<?php
$inventory_edit = 'inventory_edit';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $inventory_edit }}_name" class="col-sm-5 col-form-label">{{ __( 'inventory.name' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $inventory_edit }}_name">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_edit }}_price" class="col-sm-5 col-form-label">{{ __( 'inventory.price' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $inventory_edit }}_price">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_edit }}_category" class="col-sm-5 col-form-label">{{ __( 'inventory.category' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $inventory_edit }}_category">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'inventory.category' ) ] ) }}</option>
                            @foreach( $data['categories'] as $category )
                            <option value="{{ $category['value'] }}">{{ $category['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_edit }}type" class="col-sm-5 col-form-label">{{ __( 'inventory.type' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $inventory_edit }}_type">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'inventory.type' ) ] ) }}</option>
                            @foreach( $data['types'] as $type )
                            <option value="{{ $type['value'] }}">{{ $type['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_edit }}_desc" class="col-sm-5 col-form-label">{{ __( 'inventory.desc' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $inventory_edit }}_desc">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $inventory_edit }}_stock" class="col-sm-5 col-form-label">{{ __( 'inventory.stock' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $inventory_edit }}_stock">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $inventory_edit }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $inventory_edit }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let ie = '#{{ $inventory_edit }}';
        
        $( ie + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.inventory.index' ) }}';
        } );

        $( ie + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'id', '{{ request( 'id' ) }}' );
            formData.append( 'name', $( ie + '_name' ).val() );
            formData.append( 'price', $( ie + '_price' ).val() );
            formData.append( 'category', $( ie + '_category' ).val() );
            formData.append( 'type', $( ie + '_type' ).val() );
            formData.append( 'desc', $( ie + '_desc' ).val() );
            formData.append( 'stock', $( ie + '_stock' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.inventory.updateInventory' ) }}',
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
                            $( ie + '_' + key ).addClass( 'is-invalid' ).next().text( value );
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
                url: '{{ route( 'admin.inventory.oneInventory' ) }}',
                type: 'POST',
                data: {
                    'id': '{{ request( 'id' ) }}',
                    '_token': '{{ csrf_token() }}'
                },
                success: function( response ) {

                    $( ie + '_name' ).val( response.name );
                    $( ie + '_price' ).val( response.price );
                    $( ie + '_category' ).val( response.category_id );
                    $( ie + '_type' ).val( response.type_id );
                    $( ie + '_desc' ).val( response.desc );
                    $( ie + '_stock' ).val( response.stock );

                    $( 'body' ).loading( 'stop' );
                },
            } );
        }
    } );
</script>