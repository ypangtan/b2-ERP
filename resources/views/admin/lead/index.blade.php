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
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'lead.name' ) ] ),
        'id' => 'name',
        'title' => __( 'lead.name' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'lead.email' ) ] ),
        'id' => 'email',
        'title' => __( 'lead.email' ),
    ],
    [
        'type' => 'range',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'lead.age' ) ] ),
        'id' => 'age',
        'title' => __( 'lead.age' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'lead.phone_number' ) ] ),
        'id' => 'phone_number',
        'title' => __( 'lead.phone_number' ),
    ],
    [
        'type' => 'select',
        'options' => [
            [ 'value' => '', 'title' => __( 'datatables.all_x', [ 'title' => __( 'datatables.status' ) ] ) ],
            [ 'value' => 10, 'title' => __( 'lead.activated' ) ],
            [ 'value' => 20, 'title' => __( 'lead.enquired' ) ],
            [ 'value' => 30, 'title' => __( 'lead.call_back' ) ],
            [ 'value' => 40, 'title' => __( 'lead.order' ) ],
            [ 'value' => 50, 'title' => __( 'lead.complaint' ) ],
            [ 'value' => 60, 'title' => __( 'lead.service' ) ],
            [ 'value' => 70, 'title' => __( 'lead.other' ) ],
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
                'text': '{{ __( 'lead.complaint' ) }}',
                'color': 'badge rounded-pill bg-warning',
            },
            '50': {
                'text': '{{ __( 'lead.service' ) }}',
                'color': 'badge rounded-pill bg-warning',
            },
            '60': {
                'text': '{{ __( 'lead.other' ) }}',
                'color': 'badge rounded-pill bg-warning',
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
                url: '{{ route( 'admin.lead.allLeads' ) }}',
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

                        let view = '',
                            edit = '',
                            status = '';

                        switch( row.status ){
                            case 10 :
                                status = '<li class="dropdown-item click-action dt-enquiry" data-id="' + data + '">{{ __( 'lead.enquiry' ) }}</li>';
                            break;
                            case 20 :
                                status = '<li class="dropdown-item click-action dt-call_back" data-id="' + data + '">{{ __( 'lead.call_back' ) }}</li>';
                                status += '<li class="dropdown-item click-action dt-order" data-id="' + data + '">{{ __( 'lead.order' ) }}</li>';
                            break;
                            case 30 :
                                status = '<li class="dropdown-item click-action dt-enquiry" data-id="' + data + '">{{ __( 'lead.enquiry' ) }}</li>';
                            break;
                            case 40 :
                            break;
                            case 50 :
                            break;
                            case 60 :
                            break;
                        }

                        let html = 
                        `
                        <div class="dropdown">
                            <i class="text-primary click-action" icon-name="more-horizontal" data-bs-toggle="dropdown"></i>
                            <ul class="dropdown-menu">
                            ` + status + `
                            </ul>
                        </div>
                        `;
                        return html;
                        @else
                        return '<i class="text-secondary" icon-name="more-horizontal" data-bs-toggle="dropdown"></i>';
                    },
                },
            ],
        },
        table_no = 0,
        timeout = null;

        document.addEventListener( 'DOMContentLoaded', function() {
       
       $( document ).on( 'click', '.dt-edit', function() {
           window.location.href = '{{ route( 'admin.lead.edit' ) }}?id=' + $( this ).data( 'id' );
       } );

       

       let uid = 0,
            status = '',
            scope = '';

        $( document ).on( 'click', '.dt-delete', function() {
            uid = $( this ).data( 'id' );
            scope = 'delete';

            $( '#modal_confirmation_title' ).html( '{{ __( 'template.x_y', [ 'action' => __( 'datatables.delete' ), 'title' => Str::singular( __( 'template.leads' ) ) ] ) }}' );
            $( '#modal_confirmation_description' ).html( '{{ __( 'template.are_you_sure_to_x_y', [ 'action' => __( 'datatables.delete' ), 'title' => Str::singular( __( 'template.leads' ) ) ] ) }}' );

            modalConfirmation.show();
        } );

        $( document ).on( 'click', '#modal_confirmation_submit', function() {

            switch ( scope ) {
                case 'delete':
                    $.ajax( {
                        url: '{{ route( 'admin.lead.deletelead' ) }}',
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