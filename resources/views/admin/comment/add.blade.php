<?php
$comment_create = 'comment_create';

?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $comment_create }}_customer" class="col-sm-5 col-form-label">{{ __( 'comment.customer' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $comment_create }}_customer">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'comment.customer' ) ] ) }}</option>
                            @foreach( $data['customers'] as $customer )
                            <option value="{{ $customer['value'] }}">{{ $customer['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $comment_create }}_inventory" class="col-sm-5 col-form-label">{{ __( 'comment.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $comment_create }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'comment.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $comment_create }}_comment" class="col-sm-5 col-form-label">{{ __( 'comment.comment' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $comment_create }}_comment">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $comment_create }}_rating" class="col-sm-5 col-form-label">{{ __( 'comment.rating' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $comment_create }}_rating" min="0" max="5">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $comment_create }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    <button id="{{ $comment_create }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let cc = '#{{ $comment_create }}';

        $( cc + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.comment.index' ) }}';
        } );

        $( cc + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'customer_id', $( cc + '_customer' ).val() );
            formData.append( 'inventory_id', $( cc + '_inventory' ).val() );
            formData.append( 'comment', $( cc + '_comment' ).val() );
            formData.append( 'rating', $( cc + '_rating' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.comment.createComment' ) }}',
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
                            $( cc + '_' + key ).addClass( 'is-invalid' ).next().text( value );
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