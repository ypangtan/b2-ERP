<?php
$comment_edit = 'comment_edit';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $comment_edit }}_customer_id" class="col-sm-5 col-form-label">{{ __( 'comment.customer' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $comment_edit }}_customer_id" disabled>
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'comment.customer' ) ] ) }}</option>
                            @foreach( $data['customers'] as $customer )
                            <option value="{{ $customer['value'] }}">{{ $customer['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $comment_edit }}_inventory_id" class="col-sm-5 col-form-label">{{ __( 'comment.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $comment_edit }}_inventory_id">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'comment.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $comment_edit }}_comment" class="col-sm-5 col-form-label">{{ __( 'comment.comment' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $comment_edit }}_comment">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $comment_edit }}_rating" class="col-sm-5 col-form-label">{{ __( 'comment.rating' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $comment_edit }}_rating" min="0" max="5">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $comment_edit }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $comment_edit }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let ce = '#{{ $comment_edit }}';
        
        $( ce + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.comment.index' ) }}';
        } );

        $( ce + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'id', '{{ request( 'id' ) }}' );
            formData.append( 'customer_id', $( ce + '_customer_id' ).val() );
            formData.append( 'inventory_id', $( ce + '_inventory_id' ).val() );
            formData.append( 'comment', $( ce + '_comment' ).val() );
            formData.append( 'rating', $( ce + '_rating' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.comment.updateComment' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.module_parent.comment.index' ) }}';
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( ce + '_' + key ).addClass( 'is-invalid' ).next().text( value );
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
                url: '{{ route( 'admin.comment.oneComment' ) }}',
                type: 'POST',
                data: {
                    'id': '{{ request( 'id' ) }}',
                    '_token': '{{ csrf_token() }}'
                },
                success: function( response ) {

                    $( ce + '_customer_id' ).val( response.leads.customers.encrypted_id );
                    $( ce + '_inventory_id' ).val( response.leads.inventories.encrypted_id );
                    $( ce + '_comment' ).val( response.comment );
                    $( ce + '_rating' ).val( response.rating );

                    $( 'body' ).loading( 'stop' );
                },
            } );
        }
    } );
</script>