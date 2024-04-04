<?php
$supplier_edit = 'supplier_edit';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $supplier_edit }}_name" class="col-sm-5 col-form-label">{{ __( 'supplier.name' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $supplier_edit }}_name">
                        <div class="invalid-feedback"></div>
                    </div>
                </div><div class="mb-3 row">
                    <label for="{{ $supplier_edit }}_email" class="col-sm-5 col-form-label">{{ __( 'supplier.email' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $supplier_edit }}_email">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $supplier_edit }}_age" class="col-sm-5 col-form-label">{{ __( 'supplier.age' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $supplier_edit }}_age">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $supplier_edit }}_phone_number" class="col-sm-5 col-form-label">{{ __( 'supplier.phone_number' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $supplier_edit }}_phone_number">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $supplier_edit }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $supplier_edit }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let se = '#{{ $supplier_edit }}';
        
        $( se + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.supplier.index' ) }}';
        } );

        $( se + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'id', '{{ request( 'id' ) }}' );
            formData.append( 'name', $( se + '_name' ).val() );
            formData.append( 'email', $( se + '_email' ).val() );
            formData.append( 'age', $( se + '_age' ).val() );
            formData.append( 'phone_number', $( se + '_phone_number' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.supplier.updateSupplier' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.module_parent.supplier.index' ) }}';
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
                url: '{{ route( 'admin.supplier.oneSupplier' ) }}',
                type: 'POST',
                data: {
                    'id': '{{ request( 'id' ) }}',
                    '_token': '{{ csrf_token() }}'
                },
                success: function( response ) {

                    $( se + '_name' ).val( response.name );
                    $( se + '_email' ).val( response.email );
                    $( se + '_age' ).val( response.age );
                    $( se + '_phone_number' ).val( response.phone_number );

                    $( 'body' ).loading( 'stop' );
                },
            } );
        }
    } );
</script>