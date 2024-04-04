<?php
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
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'lead.user_name' ) ] ),
        'id' => 'user',
        'title' => __( 'lead.user_name' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'lead.customer_name' ) ] ),
        'id' => 'customer',
        'title' => __( 'lead.customer_name' ),
    ],
    [
        'type' => 'select',
        'options' => [
            [ 'value' => '', 'title' => __( 'datatables.all_x', [ 'title' => __( 'datatables.status' ) ] ) ],
            [ 'value' => 10, 'title' => __( 'lead.activated' ) ],
            [ 'value' => 20, 'title' => __( 'lead.enquired' ) ],
            [ 'value' => 30, 'title' => __( 'lead.order' ) ],
            [ 'value' => 40, 'title' => __( 'lead.done' ) ],
        ],
        'id' => 'status',
        'title' => __( 'datatables.status' ),
    ],
    [
        'type' => 'default',
        'id' => 'remark',
        'title' => __( 'lead.remark' ),
    ],
];
?>

<div class="card">
    <div class="card-body">
        <div class="mb-3 text-center">
        </div>
        <x-data-tables id="lead_table" enableFilter="true" enableFooter="false" columns="{{ json_encode( $columns ) }}" />
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
                'text': '{{ __( 'lead.activated' ) }}',
                'color': 'badge rounded-pill bg-success',
            },
            '20': {
                'text': '{{ __( 'lead.enquired' ) }}',
                'color': 'badge rounded-pill bg-warning',
            },
            '30': {
                'text': '{{ __( 'lead.order' ) }}',
                'color': 'badge rounded-pill bg-warning',
            },
            '40': {
                'text': '{{ __( 'lead.done' ) }}',
                'color': 'badge rounded-pill bg-success',
            },
        },
        dt_table,
        dt_table_name = '#lead_table',
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
                url: '{{ route( 'admin.lead._allLeads' ) }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                dataSrc: 'leads',
            },
            lengthMenu: [
                [ 10, 25, 50, 999999 ],
                [ 10, 25, 50, '{{ __( 'datatables.all' ) }}' ]
            ],
            order: [[ 1, 'desc' ]],
            columns: [
                { data: null },
                { data: 'created_at' },
                { data: 'users' },
                { data: 'customers' },
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
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "user" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {
                        return data ? data.name : '';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "customer" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {
                        return data ? data.name : '';
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
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "remark" ) }}' ),
                    orderable: false,
                    width: '30%',
                    render: function( data, type, row, meta ) {
                        html = '';

                        if( row.enquiries ){
                            for( let i in row.enquiries ){
                                html +=
                                `
                                <span>
                                <strong>{{ __( 'lead.enquiry' ) }}</strong>: ` + row.enquiries[i].remark + ` <br>
                                </span>
                                `;
                            }
                        }

                        if( row.call_backs ){
                            for( let i in row.call_backs ){
                                html +=
                                `
                                <span>
                                <strong>{{ __( 'lead.call_back' ) }}</strong>: ` + row.call_backs[i].remark + ` <br>
                                </span>
                                `;
                            }
                        }

                        if( row.sales ){
                            for( let i in row.sales ){
                                html +=
                                `
                                <span>
                                <strong>{{ __( 'lead.order' ) }}</strong>: ` + row.sales[i].remark + ` <br>
                                </span>
                                `;
                            }
                        }

                        if( row.complaint ){
                            for( let i in row.complaint ){
                                html +=
                                `
                                <span>
                                <strong>{{ __( 'lead.complaint' ) }}</strong>: ` + row.complaint[i].comment + ` <br>
                                </span>
                                `;
                            }
                        }

                        if( row.services ){
                            for( let i in row.service ){
                                html +=
                                `
                                <span>
                                <strong>{{ __( 'lead.service' ) }}</strong>: ` + row.services[i].remark + ` <br>
                                </span>
                                `;
                            }
                        }

                        if( row.other ){
                            for( let i in row.other ){
                                html +=
                                `
                                <span>
                                <strong>{{ __( 'lead.other' ) }}</strong>: ` + row.other[i].remark + ` <br>
                                </span>
                                `;
                            }
                        }

                        return html;
                    },
                },
            ],
        },
        table_no = 0,
        timeout = null;

    document.addEventListener( 'DOMContentLoaded', function() {

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