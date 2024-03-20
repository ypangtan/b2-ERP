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
        'id' => 'rider',
        'title' => __( 'rider.rider_name' ),
    ],
    [
        'type' => 'default',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'location.source' ) ] ),
        'id' => 'source_location',
        'title' => __( 'location.source' ),
    ],
    [
        'type' => 'default',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'location.destination' ) ] ),
        'id' => 'destination',
        'title' => __( 'location.destination' ),
    ],
    [
        'type' => 'default',
        'id' => 'dt_action',
        'title' => __( 'datatables.action' ),
    ],
]
?>

<div class="card">
    <div class="card-body">
        <div class="mb-3 text-end">
            @can( 'add locations' )
            <a class="btn btn-sm btn-primary" href="{{ route( 'admin.location.add' ) }}">{{ __( 'template.create' ) }}</a>
            @endcan
        </div>
        <x-data-tables id="location_table" enableFilter="true" enableFooter="false" columns="{{ json_encode( $columns ) }}" />
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
    
    var dt_table,
        dt_table_name = '#location_table',
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
                url: '{{ route( 'admin.location.allLocations' ) }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                dataSrc: 'locations',
            },
            lengthMenu: [
                [ 10, 25, 50, 999999 ],
                [ 10, 25, 50, '{{ __( 'datatables.all' ) }}' ]
            ],
            order: [[ 1, 'desc' ]],
            columns: [
                { data: null },
                { data: 'created_at' },
                { data: 'rider' },
                { data: 'source_location' },
                { data: 'destination' },
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
                        return data;
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "rider" ) }}' ),
                    render: function( data, type, row, meta ) {
                        return data.username;
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "source_location" ) }}' ),
                    render: function( data, type, row, meta ) {
                        return data;
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "destination" ) }}' ),
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

                        @canany( [ 'edit locations', 'view locations', 'delete locations' ] )

                        let view = '',
                            edit = '',
                            status = '';

                        @can( 'edit locations' )
                        view += '<li class="dropdown-item click-action dt-edit" data-id="' + data + '">{{ __( 'datatables.edit' ) }}</li>';
                        @endcan

                        @can( 'delete locations' )
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

        let uid = 0; 

        $( document ).on( 'click', '.dt-edit', function() {
            window.location.href = '{{ route( 'admin.location.edit' ) }}?id=' + $( this ).data( 'id' );
        } );

        $( document ).on( 'click', '.dt-delete', function() {

            uid = $( this ).data( 'id' );
            status = 10,
            scope = 'delete';

            $( '#modal_confirmation_title' ).html( '{{ __( 'template.x_y', [ 'action' => __( 'datatables.delete' ), 'title' => Str::singular( __( 'location.location' ) ) ] ) }}' );
            $( '#modal_confirmation_description' ).html( '{{ __( 'template.are_you_sure_to_x_y', [ 'action' => __( 'datatables.delete' ), 'title' => Str::singular( __( 'location.location' ) ) ] ) }}' );

            modalConfirmation.show();
        } );

        $( document ).on( 'click', '#modal_confirmation_submit', function() {
            $.ajax( {
                url: '{{ route( 'admin.location.deleteLocationAdmin' ) }}',
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