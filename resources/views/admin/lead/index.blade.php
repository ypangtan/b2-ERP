<?php
$lead_index = 'lead_index';
$columns = [
    [
        'type' => 'default',
        'id' => 'dt_no',
        'title' => 'No.',
    ],
    [
        'type' => 'date',
        'placeholder' => __( 'datatables.search_x', [ 'title' => __( 'datatables.registered_date' ) ] ),
        'id' => 'created_at',
        'title' => __( 'datatables.registered_date' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'customer.name' ) ] ),
        'id' => 'name',
        'title' => __( 'customer.name' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'customer.email' ) ] ),
        'id' => 'email',
        'title' => __( 'customer.email' ),
    ],
    [
        'type' => 'range',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'customer.age' ) ] ),
        'id' => 'age',
        'title' => __( 'customer.age' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'customer.phone_number' ) ] ),
        'id' => 'phone_number',
        'title' => __( 'customer.phone_number' ),
    ],
    [
        'type' => 'select',
        'options' => [
            [ 'value' => '', 'title' => __( 'datatables.all_x', [ 'title' => __( 'datatables.status' ) ] ) ],
            [ 'value' => 10, 'title' => __( 'lead.activated' ) ],
            [ 'value' => 20, 'title' => __( 'lead.enquired' ) ],
            [ 'value' => 30, 'title' => __( 'lead.order' ) ],
        ],
        'id' => 'status',
        'title' => __( 'datatables.status' ),
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
        <div class="mb-3 text-center">
            @can( 'add customers' )
            <a class="btn btn-sm btn-primary me-3" href="{{ route( 'admin.customer.add' ) }}">{{ __( 'lead.enquiry' ) }}</a>
            @endcan
            @can( 'add customers' )
            <a class="btn btn-sm btn-primary me-3" href="{{ route( 'admin.customer.add' ) }}">{{ __( 'lead.call_back' ) }}</a>
            @endcan
            @can( 'add customers' )
            <a class="btn btn-sm btn-primary me-3" href="{{ route( 'admin.customer.add' ) }}">{{ __( 'lead.order' ) }}</a>
            @endcan
            @can( 'add customers' )
            <a class="btn btn-sm btn-primary me-3" href="{{ route( 'admin.customer.add' ) }}">{{ __( 'lead.complaint' ) }}</a>
            @endcan
            @can( 'add customers' )
            <a class="btn btn-sm btn-primary me-3" href="{{ route( 'admin.customer.add' ) }}">{{ __( 'lead.service' ) }}</a>
            @endcan
            @can( 'add customers' )
            <a class="btn btn-sm btn-primary me-3" href="{{ route( 'admin.customer.add' ) }}">{{ __( 'lead.other' ) }}</a>
            @endcan
        </div>
        <x-data-tables id="customer_table" enableFilter="true" enableFooter="false" columns="{{ json_encode( $columns ) }}" />
    </div>
</div>

<div class="modal fade" id="enquiry_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __( 'template.lead_enquiry' ) }}</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_customer" class="col-sm-5 col-form-label">{{ __( 'lead.customer' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_customer">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.customer' ) ] ) }}</option>
                            @foreach( $data['customers'] as $customer )
                            <option value="{{ $customer['value'] }}">{{ $customer['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_inventory" class="col-sm-5 col-form-label">{{ __( 'lead.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_remark" class="col-sm-5 col-form-label">{{ __( 'lead.remark' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_index }}_remark">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button type="button" class="btn btn-sm btn-primary">{{ __( 'template.confirm' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="call_back_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __( 'template.update_x', [ 'title' => __( 'datatables.status' ) ] ) }}</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_customer" class="col-sm-5 col-form-label">{{ __( 'lead.customer' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_customer">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.customer' ) ] ) }}</option>
                            @foreach( $data['customers'] as $customer )
                            <option value="{{ $customer['value'] }}">{{ $customer['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_inventory" class="col-sm-5 col-form-label">{{ __( 'lead.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_remark" class="col-sm-5 col-form-label">{{ __( 'lead.remark' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_index }}_remark">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
             
            <div class="modal-footer">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button type="button" class="btn btn-sm btn-primary"  >{{ __( 'template.confirm' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="order_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __( 'template.update_x', [ 'title' => __( 'datatables.status' ) ] ) }}</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_customer" class="col-sm-5 col-form-label">{{ __( 'lead.customer' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_customer">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.customer' ) ] ) }}</option>
                            @foreach( $data['customers'] as $customer )
                            <option value="{{ $customer['value'] }}">{{ $customer['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_inventory" class="col-sm-5 col-form-label">{{ __( 'lead.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_remark" class="col-sm-5 col-form-label">{{ __( 'lead.remark' ) }}</label>
                    <div class="col-sm-7">
                        <input type="number" class="form-control form-control-sm" id="{{ $lead_index }}_remark">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_quantity" class="col-sm-5 col-form-label">{{ __( 'lead.quantity' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_index }}_quantity">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
             
            <div class="modal-footer">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button type="button" class="btn btn-sm btn-primary"  >{{ __( 'template.confirm' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="complaint_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __( 'template.update_x', [ 'title' => __( 'datatables.status' ) ] ) }}</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_customer" class="col-sm-5 col-form-label">{{ __( 'lead.customer' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_customer">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.customer' ) ] ) }}</option>
                            @foreach( $data['customers'] as $customer )
                            <option value="{{ $customer['value'] }}">{{ $customer['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_inventory" class="col-sm-5 col-form-label">{{ __( 'lead.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_remark" class="col-sm-5 col-form-label">{{ __( 'lead.remark' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_index }}_remark">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
             
            <div class="modal-footer">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button type="button" class="btn btn-sm btn-primary"  >{{ __( 'template.confirm' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="service_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __( 'template.update_x', [ 'title' => __( 'datatables.status' ) ] ) }}</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_customer" class="col-sm-5 col-form-label">{{ __( 'lead.customer' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_customer">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.customer' ) ] ) }}</option>
                            @foreach( $data['customers'] as $customer )
                            <option value="{{ $customer['value'] }}">{{ $customer['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_inventory" class="col-sm-5 col-form-label">{{ __( 'lead.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_remark" class="col-sm-5 col-form-label">{{ __( 'lead.remark' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_index }}_remark">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
             
            <div class="modal-footer">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button type="button" class="btn btn-sm btn-primary"  >{{ __( 'template.confirm' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="other_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __( 'template.update_x', [ 'title' => __( 'datatables.status' ) ] ) }}</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_customer" class="col-sm-5 col-form-label">{{ __( 'lead.customer' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_customer">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.customer' ) ] ) }}</option>
                            @foreach( $data['customers'] as $customer )
                            <option value="{{ $customer['value'] }}">{{ $customer['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_inventory" class="col-sm-5 col-form-label">{{ __( 'lead.inventory' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-select form-select-sm" id="{{ $lead_index }}_inventory">
                            <option value="">{{ __( 'datatables.select_x', [ 'title' => __( 'lead.inventory' ) ] ) }}</option>
                            @foreach( $data['inventories'] as $inventory )
                            <option value="{{ $inventory['value'] }}">{{ $inventory['title'] }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $lead_index }}_remark" class="col-sm-5 col-form-label">{{ __( 'lead.remark' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $lead_index }}_remark">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
             
            <div class="modal-footer">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button type="button" class="btn btn-sm btn-primary"  >{{ __( 'template.confirm' ) }}</button>
                </div>
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
            '10': {
                'text': '{{ __( 'datatables.activated' ) }}',
                'color': 'badge rounded-pill bg-success',
            },
            '20': {
                'text': '{{ __( 'datatables.suspended' ) }}',
                'color': 'badge rounded-pill bg-danger',
            },
        },
        dt_table,
        dt_table_name = '#customer_table',
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
                url: '{{ route( 'admin.customer.allCustomers' ) }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                dataSrc: 'customers',
            },
            lengthMenu: [
                [ 10, 25, 50, 999999 ],
                [ 10, 25, 50, '{{ __( 'datatables.all' ) }}' ]
            ],
            order: [[ 1, 'desc' ]],
            columns: [
                { data: null },
                { data: 'created_at' },
                { data: 'name' },
                { data: 'email' },
                { data: 'age' },
                { data: 'phone_number' },
                { data: 'status' },
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
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "status" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {
                        return '<span class="' + statusMapper[data].color + '">' + statusMapper[data].text + '</span>';
                    },
                },
                {
                    targets: parseInt( '{{ count( $columns ) - 1 }}' ),
                    orderable: false,
                    width: '10%',
                    className: 'text-center',
                    render: function( data, type, row, meta ) {

                        @canany( [ 'edit customers', 'view customers', 'delete customers' ] )

                        let view = '',
                            edit = '',
                            status = '';

                        @can( 'edit customers' )
                        view += '<li class="dropdown-item click-action dt-edit" data-id="' + data + '">{{ __( 'datatables.edit' ) }}</li>';
                        @endcan

                        @can( 'delete customers' )
                        view += '<li class="dropdown-item click-action dt-delete" data-id="' + data + '">{{ __( 'datatables.delete' ) }}</li>';
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
       
       $( document ).on( 'click', '.dt-edit', function() {
           window.location.href = '{{ route( 'admin.customer.edit' ) }}?id=' + $( this ).data( 'id' );
       } );

       

       let uid = 0,
            status = '',
            scope = '';

        $( document ).on( 'click', '.dt-delete', function() {
            uid = $( this ).data( 'id' );
            scope = 'delete';

            $( '#modal_confirmation_title' ).html( '{{ __( 'template.x_y', [ 'action' => __( 'datatables.delete' ), 'title' => Str::singular( __( 'template.customers' ) ) ] ) }}' );
            $( '#modal_confirmation_description' ).html( '{{ __( 'template.are_you_sure_to_x_y', [ 'action' => __( 'datatables.delete' ), 'title' => Str::singular( __( 'template.customers' ) ) ] ) }}' );

            modalConfirmation.show();
        } );

        $( document ).on( 'click', '#modal_confirmation_submit', function() {

            switch ( scope ) {
                case 'delete':
                    $.ajax( {
                        url: '{{ route( 'admin.customer.deleteCustomer' ) }}',
                        type: 'POST',
                        data: {
                            id: uid,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function( response ) {
                            modalConfirmation.hide();
                            $( '#modal_success .caption-text' ).html( response.message );
                            modalSuccess.show();
                            dt_table.draw( false );
                        },
                        error: function( error ) {
                            modalConfirmation.hide();
                            $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                            modalDanger.show();
                        },
                    } );        
            }
        } );

       $( '#created_at' ).flatpickr( {
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