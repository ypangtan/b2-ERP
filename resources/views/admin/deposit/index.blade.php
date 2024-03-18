<?php

$deposit = 'deposit';

$columns = [
    [
        'type' => 'default',
        'id' => 'dt_no',
        'title' => 'No.',
    ],
    [
        'type' => 'date',
        'placeholder' => __( 'datatables.search_x', [ 'title' => __( 'datatables.created_date' ) ] ),
        'id' => 'created_date',
        'title' => __( 'datatables.created_date' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'user.user' ) ] ),
        'id' => 'user',
        'title' => __( 'user.user' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'deposit.reference' ) ] ),
        'id' => 'reference',
        'title' => __( 'deposit.reference' ),
    ],
    [
        'type' => 'select',
        'options' => [
            [ 'value' => '', 'title' => __( 'datatables.all_x', [ 'title' => __( 'deposit.payment_method' ) ] ) ],
            [ 'value' => 1, 'title' => __( 'deposit.bank_transfer' ) ],
            [ 'value' => 2, 'title' => __( 'deposit.payment_gateway' ) ],
        ],
        'id' => 'payment_method',
        'title' => __( 'deposit.payment_method' ),
    ],
    [
        'type' => 'select',
        'options' => [
            [ 'value' => '', 'title' => __( 'datatables.all_x', [ 'title' => __( 'datatables.status' ) ] ) ],
            [ 'value' => 1, 'title' => __( 'deposit.pending' ) ],
            [ 'value' => 10, 'title' => __( 'datatables.approved' ) ],
            [ 'value' => 20, 'title' => __( 'datatables.rejected' ) ],
        ],
        'id' => 'status',
        'title' => __( 'datatables.status' ),
        'preAmount' => true,
    ],
    [
        'type' => 'default',
        'id' => 'remarks',
        'title' => __( 'template.remarks' ),
    ],
    [
        'type' => 'default',
        'id' => 'amount',
        'title' => __( 'deposit.amount' ),
        'amount' => true,
    ],
    [
        'type' => 'default',
        'id' => 'dt_action',
        'title' => __( 'datatables.action' ),
    ],
];
?>

<div class="card">
    <div class="card-body">
        <div class="mb-3 text-end">
        </div>
        <x-data-tables id="deposit_table" enableFilter="true" enableFooter="true" columns="{{ json_encode( $columns ) }}" />
    </div>
</div>

<div class="modal fade" id="deposit_detail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __( 'deposit.deposit_details' ) }}</h5>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <strong>{{ __( 'deposit.personal_details' ) }}</strong>
            </div>
            <div class="mb-3 row">
                <label for="{{ $deposit }}_fullname" class="col-sm-5 col-form-label">{{ __( 'user.fullname' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $deposit }}_fullname">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $deposit }}_email" class="col-sm-5 col-form-label">{{ __( 'user.email' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $deposit }}_email">
                </div>
            </div>
            <hr>
            <div class="mb-3">
                <strong>{{ __( 'deposit.deposit_details' ) }}</strong>
            </div>
            <div class="mb-3 row">
                <label for="{{ $deposit }}_payment_method" class="col-sm-5 col-form-label">{{ __( 'deposit.payment_method' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $deposit }}_payment_method">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $deposit }}_amount" class="col-sm-5 col-form-label">{{ __( 'deposit.amount' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $deposit }}_amount">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $deposit }}_attachment" class="col-sm-5 col-form-label">{{ __( 'deposit.attachment' ) }}</label>
                <div class="col-sm-7 d-flex align-items-center documents">
                    <a href="#" id="{{ $deposit }}_attachment" target="_blank" rel="noopener noreferrer"><i class="align-middle" icon-name="link" width="16px" height="16px"></i> {{ __( 'template.view' ) }}</a>
                    <input type="text" readonly class="form-control-plaintext" id="{{ $deposit }}_attachment1" value="-">
                </div>
            </div>
            <div class="action hidden">
                <hr>
                <div class="mb-3">
                    <strong>{{ __( 'datatables.action' ) }}</strong>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $deposit }}_status" class="col-sm-5 col-form-label">{{ __( 'datatables.status' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $deposit }}_status">
                            <option value="1">{{ __( 'deposit.pending' ) }}</option>
                            <option value="10">{{ __( 'datatables.approve' ) }}</option>
                            <option value="20">{{ __( 'datatables.reject' ) }}</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $deposit }}_remarks" class="col-sm-5 col-form-label">{{ __( 'template.remarks' ) }}</label>
                    <div class="col-sm-7">
                        <textarea class="form-control" id="{{ $deposit }}_remarks" placeholder="{{ __( 'template.optional' ) }}"></textarea>
                    </div>
                </div>
            </div>
            <input type="hidden" id="{{ $deposit }}_id">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __( 'template.cancel' ) }}</button>
            <button type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
        </div>
        </div>
    </div>
</div>

<script>

    window['columns'] = @json( $columns );
    window['ids'] = [];
    
    @foreach ( $columns as $column )
    @if ( $column['type'] != 'default' )
    window['{{ $column['id'] }}'] = '';
    @endif
    @endforeach

    var statusMapper = {
            '1': {
                'text': '{{ __( 'deposit.pending' ) }}',
                'color': 'badge rounded-pill bg-secondary',
            },
            '10': {
                'text': '{{ __( 'datatables.approved' ) }}',
                'color': 'badge rounded-pill bg-success',
            },
            '20': {
                'text': '{{ __( 'datatables.rejected' ) }}',
                'color': 'badge rounded-pill bg-danger',
            },
        },
        dt_table,
        dt_table_name = '#deposit_table',
        dt_table_config = {
            language: {
                'lengthMenu': '{{ __( "datatables.lengthMenu" ) }}',
                'zeroRecords': '{{ __( "datatables.zeroRecords" ) }}',
                'info': '{{ __( "datatables.info" ) }}',
                'infoEmpty': '{{ __( "datatables.infoEmpty" ) }}',
                'infoFiltered': '{{ __( "datatables.infoFiltered" ) }}',
                'paginate': {
                    'previous': '{{ __( "datatables.previous" ) }}',
                    'next': '{{ __( "datatables.next" ) }}',
                }
            },
            ajax: {
                url: '{{ route( 'admin.deposit.allDeposits' ) }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                dataSrc: 'deposits',
            },
            lengthMenu: [
                [ 10, 25, 50, 999999 ],
                [ 10, 25, 50, '{{ __( 'datatables.all' ) }}' ]
            ],
            order: [[ 1, 'desc' ]],
            columns: [
                { data: null },
                { data: 'created_at' },
                { data: 'user' },
                { data: 'reference' },
                { data: 'payment_method' },
                { data: 'status' },
                { data: 'remark' },
                { data: 'display_amount' },
                { data: 'encrypted_id' },
            ],
            columnDefs: [
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "dt_no" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {
                        return table_no += 1;
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "user" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {

                        let email = data.email ?? '-';
                            fullname = data.user_detail?.fullname ?? '-',
                            html = '';

                        html +=
                        `
                        <span>
                        <strong>` + fullname + `</strong><br>
                        <strong>{{ __( 'user.email' ) }}</strong>: ` + email + `
                        </span>
                        `;

                        return html;
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "reference" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {
                        return data;
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "payment_method" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {
                        return data == 1 ? '{{ __( 'deposit.bank_transfer' ) }}' : '{{ __( 'deposit.payment_gateway' ) }}';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "status" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {

                        if ( !data ) {
                            return '<span class="' + statusMapper[1].color + '">' + statusMapper[1].text + '</span>';
                        }

                        return '<span class="' + statusMapper[data].color + '">' + statusMapper[data].text + '</span>';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "remarks" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {
                        return data ? data : '-';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "amount" ) }}' ),
                    orderable: false,
                    className: 'text-end',
                    render: function( data, type, row, meta ) {
                        return data;
                    },
                },
                {
                    targets: parseInt( '{{ count( $columns ) - 1 }}' ),
                    orderable: false,
                    width: '10%',
                    className: 'text-center',
                    render: function( data, type, row, meta ) {

                        @canany( [ 'edit deposits', 'view deposits' ] )

                        let view = '',
                            edit = '',
                            status = '';

                        @can( 'edit deposits' )
                        view += '<li class="dropdown-item click-action dt-view" data-id="' + data + '">{{ __( 'datatables.view' ) }}</li>';
                        // status = row.status == 10 ? 
                        // '<li class="dropdown-item click-action dt-suspend" data-id="' + data + '">{{ __( 'datatables.suspend' ) }}</li>':
                        // '<li class="dropdown-item click-action dt-activate" data-id="' + data + '">{{ __( 'datatables.activate' ) }}</li>' ;
                        @endcan

                        let html = 
                        `
                        <div class="dropdown">
                            <i class="text-primary click-action" icon-name="more-horizontal" data-bs-toggle="dropdown"></i>
                            <ul class="dropdown-menu">
                            ` + view + `
                            </ul>
                        </div>
                        `;
                        return html;
                        @else
                        return '<i class="text-secondary" icon-name="more-horizontal" data-bs-toggle="dropdown"></i>';
                        @endcanany
                    },
                },
            ],
        },
        table_no = 0,
        timeout = null;

    document.addEventListener( 'DOMContentLoaded', function() {

        let d = '#{{ $deposit }}',
            modalDepositDetail = new bootstrap.Modal( document.getElementById( 'deposit_detail' ) );

        $( document ).on( 'click', '.dt-view', function() {

            $( '#deposit_detail .form-control-plaintext' ).val( '-' );
            $( '#deposit_detail textarea' ).val();

            $( '.documents > a' ).addClass( 'hidden' );
            $( d + '_attachment' ).removeClass( 'hidden' );

            let id = $( this ).data( 'id' );

            $.ajax( {
                url: '{{ route( 'admin.deposit.oneDeposit' ) }}',
                type: 'POST',
                data: {
                    id,
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    $( d + '_id' ).val( response.encrypted_id );
                    $( d + '_fullname' ).val( response.user.user_detail.fullname );
                    $( d + '_email' ).val( response.user.email );

                    $( d + '_payment_method' ).val( response.display_payment_method );
                    $( d + '_amount' ).val( response.display_amount );
                    $( d + '_status' ).val( response.status );

                    let dd = response.deposit_document;

                    if ( dd ) {
                        $( d + '_attachment' ).attr( 'href', dd.path ).removeClass( 'hidden' );
                        $( d + '_attachment1' ).addClass( 'hidden' );
                    }

                    if ( response.status == 1 ) {
                        $( '.action' ).removeClass( 'hidden' );
                        $( '#deposit_detail .btn-primary' ).removeClass( 'hidden' );
                    } else {
                        $( '.action' ).addClass( 'hidden' );
                        $( '#deposit_detail .btn-primary' ).addClass( 'hidden' );
                    }

                    modalDepositDetail.show();
                },
                error: function( error ) {
                    modalDepositDetail.hide();
                    $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                    modalDanger.show();
                },
            } );
        } );

        $( '#deposit_detail .btn-primary' ).on( 'click', function() {

            let formData = new FormData();
            formData.append( 'id', $( d + '_id' ).val() );
            formData.append( 'status', $( d + '_status' ).val() );
            formData.append( 'remarks', $( d + '_remarks' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.deposit.updateDeposit' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    modalDepositDetail.hide();
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.show();
                    dt_table.draw( false );
                },
                error: function( error ) {
                    modalDepositDetail.hide();
                    $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                    modalDanger.show();
                },
            } );
        } );

        $( '#created_date' ).flatpickr( {
            mode: 'range',
            disableMobile: true,
            onClose: function( selected, dateStr, instance ) {
                window[$( instance.element ).data('id')] = $( instance.element ).val();
                dt_table.draw();
            }
        } );
   } );

</script>

<script src="{{ asset( 'admin/js/dataTable.init.js' ) . Helper::assetVersion() }}"></script>