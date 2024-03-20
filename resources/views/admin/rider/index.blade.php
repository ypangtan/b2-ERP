<?php
$columns = [
    [
        'type' => 'default',
        'id' => 'dt_no',
        'title' => 'No.',
    ],
    [
        'type' => 'date',
        'placeholder' => __( 'datatables.search_x', [ 'title' => __( 'datatables.created_date' ) ] ),
        'id' => 'created_at',
        'title' => __( 'datatables.created_date' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'rider.rider_name' ) ] ),
        'id' => 'username',
        'title' => __( 'rider.rider_name' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'rider.fullname' ) ] ),
        'id' => 'fullname',
        'title' => __( 'rider.fullname' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'rider.ic' ) ] ),
        'id' => 'ic',
        'title' => __( 'rider.ic' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'rider.email' ) ] ),
        'id' => 'email',
        'title' => __( 'rider.email' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'rider.phone_number' ) ] ),
        'id' => 'phone_number',
        'title' => __( 'rider.phone_number' ),
    ],
    [
        'type' => 'select',
        'options' => [
            [ 'value' => '', 'title' => __( 'datatables.all_x', [ 'title' => __( 'datatables.status' ) ] ) ],
            [ 'value' => 10, 'title' => __( 'datatables.activated' ) ],
            [ 'value' => 20, 'title' => __( 'datatables.suspended' ) ],
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
        <div class="mb-3 text-end">
            @can( 'add riders' )
            <a class="btn btn-sm btn-primary" href="{{ route( 'admin.rider.add' ) }}">{{ __( 'template.create' ) }}</a>
            @endcan
        </div>
        <x-data-tables id="rider_table" enableFilter="true" enableFooter="false" columns="{{ json_encode( $columns ) }}" />
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
        dt_table_name = '#rider_table',
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
                url: '{{ route( 'admin.rider.allRiders' ) }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                dataSrc: 'riders',
            },
            lengthMenu: [
                [ 10, 25, 50, 999999 ],
                [ 10, 25, 50, '{{ __( 'datatables.all' ) }}' ]
            ],
            order: [[ 1, 'desc' ]],
            columns: [
                { data: null },
                { data: 'created_at' },
                { data: 'username' },
                { data: 'fullname' },
                { data: 'ic' },
                { data: 'email' },
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
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "created_at" ) }}' ),
                    render: function( data, type, row, meta ) {
                        return data ?? '-';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "username" ) }}' ),
                    render: function( data, type, row, meta ) {
                        return data ?? '-';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "fullname" ) }}' ),
                    render: function( data, type, row, meta ) {
                        return data ?? '-';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "ic" ) }}' ),
                    render: function( data, type, row, meta ) {
                        return data ?? '-';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "email" ) }}' ),
                    render: function( data, type, row, meta ) {
                        return data ?? '-';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "phone_number" ) }}' ),
                    render: function( data, type, row, meta ) {
                        return data ? ( row.calling_code + data ) : '-';
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "status" ) }}' ),
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

                        @canany( [ 'edit riders', 'view riders', 'delete riders' ] )

                        let view = '',
                            edit = '',
                            status = '';

                        @can( 'edit riders' )
                        view += '<li class="dropdown-item click-action dt-edit" data-id="' + data + '">{{ __( 'datatables.edit' ) }}</li>';
                        status = row.status == 10 ? 
                        '<li class="dropdown-item click-action dt-suspend" data-id="' + data + '">{{ __( 'datatables.suspend' ) }}</li>':
                        '<li class="dropdown-item click-action dt-activate" data-id="' + data + '">{{ __( 'datatables.activate' ) }}</li>' ;
                        @endcan

                        @can( 'delete riders' )
                        view += '<li class="dropdown-item click-action dt-delete" data-id="' + data + '">{{ __( 'datatables.delete' ) }}</li>';
                        @endcan

                        let html = 
                        `
                        <div class="dropdown">
                            <i class="text-primary click-action" icon-name="more-horizontal" data-bs-toggle="dropdown"></i>
                            <ul class="dropdown-menu">
                            ` + view + `
                            ` + status + `
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
            window.location.href = '{{ route( 'admin.rider.edit' ) }}?id=' + $( this ).data( 'id' );
        } );

        let uid = 0,
            status = '',
            scope = '';

        $( document ).on( 'click', '.dt-suspend', function() {

            uid = $( this ).data( 'id' );
            status = 20,
            scope = 'status';

            $( '#modal_confirmation_title' ).html( '{{ __( 'template.x_y', [ 'action' => __( 'datatables.suspend' ), 'title' => Str::singular( __( 'template.riders' ) ) ] ) }}' );
            $( '#modal_confirmation_description' ).html( '{{ __( 'template.are_you_sure_to_x_y', [ 'action' => __( 'datatables.suspend' ), 'title' => Str::singular( __( 'template.users' ) ) ] ) }}' );

            modalConfirmation.show();
        } );

        $( document ).on( 'click', '.dt-activate', function() {

            uid = $( this ).data( 'id' );
            status = 10,
            scope = 'status';

            $( '#modal_confirmation_title' ).html( '{{ __( 'template.x_y', [ 'action' => __( 'datatables.activate' ), 'title' => Str::singular( __( 'template.riders' ) ) ] ) }}' );
            $( '#modal_confirmation_description' ).html( '{{ __( 'template.are_you_sure_to_x_y', [ 'action' => __( 'datatables.activate' ), 'title' => Str::singular( __( 'template.users' ) ) ] ) }}' );

            modalConfirmation.show();
        } );

        $( document ).on( 'click', '.dt-delete', function() {

            uid = $( this ).data( 'id' );
            status = 10,
            scope = 'delete';

            $( '#modal_confirmation_title' ).html( '{{ __( 'template.x_y', [ 'action' => __( 'datatables.delete' ), 'title' => Str::singular( __( 'rider.rider' ) ) ] ) }}' );
            $( '#modal_confirmation_description' ).html( '{{ __( 'template.are_you_sure_to_x_y', [ 'action' => __( 'datatables.delete' ), 'title' => Str::singular( __( 'rider.rider' ) ) ] ) }}' );

            modalConfirmation.show();
        } );

        $( document ).on( 'click', '#modal_confirmation_submit', function() {

            switch ( scope ) {
                case 'status':
                    $.ajax( {
                        url: '{{ route( 'admin.rider.updateRiderStatus' ) }}',
                        type: 'POST',
                        data: {
                            id: uid,
                            status,
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
                break;
                case 'delete':
                    $.ajax( {
                        url: '{{ route( 'admin.rider.deleteRiderAdmin' ) }}',
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
                break;  
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