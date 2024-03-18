<?php

$withdrawal = 'withdrawal';

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
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'withdrawal.reference' ) ] ),
        'id' => 'reference',
        'title' => __( 'withdrawal.reference' ),
    ],
    [
        'type' => 'select',
        'options' => [
            [ 'value' => '', 'title' => __( 'datatables.all_x', [ 'title' => __( 'withdrawal.payment_method' ) ] ) ],
            [ 'value' => 1, 'title' => __( 'withdrawal.bank_transfer' ) ],
            [ 'value' => 2, 'title' => __( 'withdrawal.payment_gateway' ) ],
        ],
        'id' => 'payment_method',
        'title' => __( 'withdrawal.payment_method' ),
    ],
    [
        'type' => 'select',
        'options' => [
            [ 'value' => '', 'title' => __( 'datatables.all_x', [ 'title' => __( 'datatables.status' ) ] ) ],
            [ 'value' => 1, 'title' => __( 'withdrawal.pending' ) ],
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
        'title' => __( 'withdrawal.amount' ),
        'amount' => true,
    ],
    [
        'type' => 'default',
        'id' => 'service_charge_rate',
        'title' => __( 'withdrawal.service_charge_rate' ),
    ],
    [
        'type' => 'default',
        'id' => 'service_charge_amount',
        'title' => __( 'withdrawal.service_charge_amount' ),
        'amount' => true,
    ],
    [
        'type' => 'default',
        'id' => 'actual_amount',
        'title' => __( 'withdrawal.actual_amount' ),
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
        <x-data-tables id="withdrawal_table" enableFilter="true" enableFooter="true" columns="{{ json_encode( $columns ) }}" />
    </div>
</div>

<div class="modal fade" id="withdrawal_detail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __( 'withdrawal.withdrawal_details' ) }}</h5>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <strong>{{ __( 'withdrawal.personal_details' ) }}</strong>
            </div>
            <div class="mb-3 row">
                <label for="{{ $withdrawal }}_fullname" class="col-sm-5 col-form-label">{{ __( 'user.fullname' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $withdrawal }}_fullname">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $withdrawal }}_email" class="col-sm-5 col-form-label">{{ __( 'user.email' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $withdrawal }}_email">
                </div>
            </div>
            <hr>
            <div class="mb-3">
                <strong>{{ __( 'withdrawal.bank_details' ) }}</strong>
            </div>
            <div class="mb-3 row">
                <label for="{{ $withdrawal }}_bank" class="col-sm-5 col-form-label">{{ __( 'withdrawal.bank' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $withdrawal }}_bank">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $withdrawal }}_account_holder" class="col-sm-5 col-form-label">{{ __( 'withdrawal.account_holder' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $withdrawal }}_account_holder">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $withdrawal }}_account_number" class="col-sm-5 col-form-label">{{ __( 'withdrawal.account_number' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $withdrawal }}_account_number">
                </div>
            </div>
            <hr>
            <div class="mb-3">
                <strong>{{ __( 'withdrawal.withdrawal_details' ) }}</strong>
            </div>
            <div class="mb-3 row">
                <label for="{{ $withdrawal }}_payment_method" class="col-sm-5 col-form-label">{{ __( 'withdrawal.payment_method' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $withdrawal }}_payment_method">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $withdrawal }}_amount" class="col-sm-5 col-form-label">{{ __( 'withdrawal.amount' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext text-end" id="{{ $withdrawal }}_amount">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $withdrawal }}_service_charge_amount" class="col-sm-5 col-form-label">{{ __( 'withdrawal.service_charge_amount' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext text-end" id="{{ $withdrawal }}_service_charge_amount">
                </div>
            </div>
            <hr>
            <div class="mb-3 row">
                <label for="{{ $withdrawal }}_actual_amount" class="col-sm-5 col-form-label">{{ __( 'withdrawal.actual_amount' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext text-end" id="{{ $withdrawal }}_actual_amount">
                </div>
            </div>
            <div class="action hidden">
                <hr>
                <div class="mb-3">
                    <strong>{{ __( 'datatables.action' ) }}</strong>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $withdrawal }}_status" class="col-sm-5 col-form-label">{{ __( 'datatables.status' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $withdrawal }}_status">
                            <option value="1">{{ __( 'withdrawal.pending' ) }}</option>
                            <option value="10">{{ __( 'datatables.approve' ) }}</option>
                            <option value="20">{{ __( 'datatables.reject' ) }}</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $withdrawal }}_remarks" class="col-sm-5 col-form-label">{{ __( 'template.remarks' ) }}</label>
                    <div class="col-sm-7">
                        <textarea class="form-control" id="{{ $withdrawal }}_remarks" placeholder="{{ __( 'template.optional' ) }}"></textarea>
                    </div>
                </div>
            </div>
            <input type="hidden" id="{{ $withdrawal }}_id">
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
                'text': '{{ __( 'withdrawal.pending' ) }}',
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
        dt_table_name = '#withdrawal_table',
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
                url: '{{ route( 'admin.withdrawal.allWithdrawals' ) }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                dataSrc: 'withdrawals',
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
                { data: 'display_service_charge_rate' },
                { data: 'display_service_charge_amount' },
                { data: 'display_actual_amount' },
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
                        return data ? data : '-';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "payment_method" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {
                        return data == 1 ? '{{ __( 'withdrawal.bank_transfer' ) }}' : '{{ __( 'withdrawal.payment_gateway' ) }}';
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
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "service_charge_rate" ) }}' ),
                    orderable: false,
                    className: 'text-end',
                    render: function( data, type, row, meta ) {
                        return data + ( row.service_charge_type == 1 ? '%' : '' );
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "service_charge_amount" ) }}' ),
                    orderable: false,
                    className: 'text-end',
                    render: function( data, type, row, meta ) {
                        return data;
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "actual_amount" ) }}' ),
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

                        @canany( [ 'edit withdrawals', 'view withdrawals' ] )

                        let view = '',
                            edit = '',
                            status = '';

                        @can( 'edit withdrawals' )
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

        let d = '#{{ $withdrawal }}',
            modalWithdrawalDetail = new bootstrap.Modal( document.getElementById( 'withdrawal_detail' ) );

        $( document ).on( 'click', '.dt-view', function() {

            $( '#withdrawal_detail .form-control-plaintext' ).val( '-' );
            $( '#withdrawal_detail textarea' ).val();

            $( '.documents > a' ).addClass( 'hidden' );
            $( d + '_attachment' ).removeClass( 'hidden' );

            let id = $( this ).data( 'id' );

            $.ajax( {
                url: '{{ route( 'admin.withdrawal.oneWithdrawal' ) }}',
                type: 'POST',
                data: {
                    id,
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    $( d + '_id' ).val( response.encrypted_id );
                    $( d + '_fullname' ).val( response.user.user_detail.fullname );
                    $( d + '_email' ).val( response.user.email );

                    $( d + '_bank' ).val( response.withdrawal_meta.bank.name );
                    $( d + '_account_holder' ).val( response.withdrawal_meta.account_holder_name );
                    $( d + '_account_number' ).val( response.withdrawal_meta.account_number );

                    $( d + '_payment_method' ).val( response.display_payment_method );
                    $( d + '_amount' ).val( response.display_amount );

                    if ( response.service_charge_type == 1 ) {
                        console.log(  'he' );
                        $( d + '_service_charge_amount' ).parents( 'div.row' ).children( 'label' ).html( '{{ __( 'withdrawal.service_charge_amount' ) }} (' + response.display_service_charge_rate + '%)' );
                    } else {
                        $( d + '_service_charge_amount' ).parents( 'div.row' ).children( 'label' ).html( '{{ __( 'withdrawal.service_charge_amount' ) }}' )
                    }

                    $( d + '_service_charge_amount' ).val( '-' + response.display_service_charge_amount );
                    $( d + '_actual_amount' ).val( response.display_actual_amount );
                    $( d + '_status' ).val( response.status );

                    let dd = response.withdrawal_document;

                    if ( dd ) {
                        $( d + '_attachment' ).attr( 'href', dd.path ).removeClass( 'hidden' );
                        $( d + '_attachment1' ).addClass( 'hidden' );
                    }

                    if ( response.status == 1 ) {
                        $( '.action' ).removeClass( 'hidden' );
                        $( '#withdrawal_detail .btn-primary' ).removeClass( 'hidden' );
                    } else {
                        $( '.action' ).addClass( 'hidden' );
                        $( '#withdrawal_detail .btn-primary' ).addClass( 'hidden' );
                    }

                    modalWithdrawalDetail.show();
                },
                error: function( error ) {
                    modalWithdrawalDetail.hide();
                    $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                    modalDanger.show();
                },
            } );
        } );

        $( '#withdrawal_detail .btn-primary' ).on( 'click', function() {

            let formData = new FormData();
            formData.append( 'id', $( d + '_id' ).val() );
            formData.append( 'status', $( d + '_status' ).val() );
            formData.append( 'remarks', $( d + '_remarks' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.withdrawal.updateWithdrawal' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    modalWithdrawalDetail.hide();
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.show();
                    dt_table.draw( false );
                },
                error: function( error ) {
                    modalWithdrawalDetail.hide();
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