<?php

$user_kyc = 'user_kyc';

$columns = [
    [
        'type' => 'default',
        'id' => 'dt_no',
        'title' => 'No.',
    ],
    [
        'type' => 'date',
        'placeholder' => __( 'datatables.search_x', [ 'title' => __( 'datatables.submission_date' ) ] ),
        'id' => 'submission_date',
        'title' => __( 'datatables.submission_date' ),
    ],
    [
        'type' => 'input',
        'placeholder' =>  __( 'datatables.search_x', [ 'title' => __( 'user.user' ) ] ),
        'id' => 'user',
        'title' => __( 'user.user' ),
    ],
    [
        'type' => 'default',
        'id' => 'personal_details',
        'title' => __( 'kyc.personal_details' ),
    ],
    [
        'type' => 'default',
        'id' => 'bank_details',
        'title' => __( 'kyc.bank_details' ),
    ],
    [
        'type' => 'select',
        'options' => [
            [ 'value' => '', 'title' => __( 'datatables.all_x', [ 'title' => __( 'kyc.kyc_status' ) ] ) ],
            // [ 'value' => 1, 'title' => __( 'kyc.not_completed' ) ],
            [ 'value' => 2, 'title' => __( 'kyc.waiting_for_verification' ) ],
            [ 'value' => 10, 'title' => __( 'kyc.completed' ) ],
            [ 'value' => 20, 'title' => __( 'kyc.resubmission_required' ) ],
        ],
        'id' => 'status',
        'title' => __( 'kyc.kyc_status' ),
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
        <x-data-tables id="user_kyc_table" enableFilter="true" enableFooter="false" columns="{{ json_encode( $columns ) }}" />
    </div>
</div>

<div class="modal fade" id="modal_kyc_detail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __( 'kyc.kyc_details' ) }}</h5>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <strong>{{ __( 'kyc.personal_details' ) }}</strong>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_nationality" class="col-sm-5 col-form-label">{{ __( 'kyc.nationality' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $user_kyc }}_nationality">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_fullname" class="col-sm-5 col-form-label">{{ __( 'kyc.fullname' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $user_kyc }}_fullname">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_identification_number" class="col-sm-5 col-form-label">{{ __( 'kyc.identification_number' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $user_kyc }}_identification_number">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_date_of_birth" class="col-sm-5 col-form-label">{{ __( 'kyc.date_of_birth' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $user_kyc }}_date_of_birth">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_ic_front" class="col-sm-5 col-form-label">{{ __( 'kyc.ic_front' ) }}</label>
                <div class="col-sm-7 d-flex align-items-center documents">
                    <a href="#" id="{{ $user_kyc }}_ic_front" target="_blank" rel="noopener noreferrer"><i class="align-middle" icon-name="link" width="16px" height="16px"></i> {{ __( 'template.view' ) }}</a>
                    <input type="text" readonly class="form-control-plaintext" id="{{ $user_kyc }}_ic_front1" value="-">
                </div>
            </div>
            {{-- <div class="mb-3 row">
                <label for="{{ $user_kyc }}_ic_back" class="col-sm-5 col-form-label">{{ __( 'kyc.ic_back' ) }}</label>
                <div class="col-sm-7 d-flex align-items-center documents">
                    <a href="#" id="{{ $user_kyc }}_ic_back" target="_blank" rel="noopener noreferrer"><i class="align-middle" icon-name="link" width="16px" height="16px"></i> {{ __( 'template.view' ) }}</a>
                    <input type="text" readonly class="form-control-plaintext" id="{{ $user_kyc }}_ic_back1" value="-">
                </div>
            </div> --}}
            <hr>
            <div class="mb-3">
                <strong>{{ __( 'kyc.bank_details' ) }}</strong>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_bank" class="col-sm-5 col-form-label">{{ __( 'kyc.bank' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $user_kyc }}_bank">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_account_number" class="col-sm-5 col-form-label">{{ __( 'kyc.account_number' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $user_kyc }}_account_number">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_account_holder" class="col-sm-5 col-form-label">{{ __( 'kyc.account_holder' ) }}</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="{{ $user_kyc }}_account_holder">
                </div>
            </div>
            <hr>
            <div class="mb-3">
                <strong>{{ __( 'datatables.action' ) }}</strong>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_kyc_status" class="col-sm-5 col-form-label">{{ __( 'kyc.kyc_status' ) }}</label>
                <div class="col-sm-7">
                    <select class="form-select form-select-sm" id="{{ $user_kyc }}_kyc_status">
                        <option value="2">{{ __( 'kyc.waiting_for_verification' ) }}</option>
                        <option value="10">{{ __( 'kyc.approve_kyc' ) }}</option>
                        <option value="20">{{ __( 'kyc.request_resubmission' ) }}</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="{{ $user_kyc }}_remarks" class="col-sm-5 col-form-label">{{ __( 'kyc.remarks' ) }}</label>
                <div class="col-sm-7">
                    <textarea class="form-control" id="{{ $user_kyc }}_remarks" placeholder="{{ __( 'template.optional' ) }}"></textarea>
                </div>
            </div>
            <input type="hidden" id="{{ $user_kyc }}_id">
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
    
    var kycStatusMapper = {
            '1': {
                'text': '{{ __( 'kyc.not_completed' ) }}',
                'color': 'badge rounded-pill bg-danger',
            },
            '2': {
                'text': '{{ __( 'kyc.waiting_for_verification' ) }}',
                'color': 'badge rounded-pill bg-warning',
            },
            '10': {
                'text': '{{ __( 'kyc.completed' ) }}',
                'color': 'badge rounded-pill bg-success',
            },
            '20': {
                'text': '{{ __( 'kyc.resubmission_required' ) }}',
                'color': 'badge rounded-pill bg-danger',
            },
        },
        dt_table,
        dt_table_name = '#user_kyc_table',
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
                url: '{{ route( 'admin.user_kyc.allUserKycs' ) }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                dataSrc: 'user_kycs',
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
                { data: 'fullname' },
                { data: 'user_bank' },
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
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "personal_details" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {

                        let fullname = row.fullname ?? '-',
                            identificationNumber = row.identification_number ?? '-',
                            dateOfBirth = row.date_of_birth ?? '-',
                            html = '';

                            identificationNumber = identificationNumber.substring( 0, 2 ) + '***' + identificationNumber.substring( identificationNumber.length - 2 );

                        html +=
                        `
                        <span>
                        <strong>{{ __( 'kyc.fullname' ) }}</strong>: ` + fullname + `<br>
                        <strong>{{ __( 'kyc.identification_number' ) }}</strong>: ` + identificationNumber + `<br>
                        <strong>{{ __( 'kyc.date_of_birth' ) }}</strong>: ` + dateOfBirth + `
                        </span>
                        `;

                        return html;
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "bank_details" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {

                        let accountNumber = data.account_number ?? '-',
                            accountName = data.account_holder_name ?? '-',
                            bankName = data.bank?.name ?? '-',
                            html = '';

                        html +=
                        `
                        <span>
                        <strong>{{ __( 'kyc.bank' ) }}</strong>: ` + bankName + `<br>
                        <strong>{{ __( 'kyc.account_number' ) }}</strong>: ` + accountNumber + `<br>
                        <strong>{{ __( 'kyc.account_holder' ) }}</strong>: ` + accountName + `
                        </span>
                        `;

                        return html;
                    },
                },
                {
                    targets: parseInt( '{{ Helper::columnIndex( $columns, "status" ) }}' ),
                    orderable: false,
                    render: function( data, type, row, meta ) {

                        if ( !data ) {
                            return '<span class="' + kycStatusMapper[1].color + '">' + kycStatusMapper[1].text + '</span>';
                        }

                        return '<span class="' + kycStatusMapper[data].color + '">' + kycStatusMapper[data].text + '</span>';
                    },
                },
                {
                    targets: parseInt( '{{ count( $columns ) - 1 }}' ),
                    orderable: false,
                    width: '10%',
                    className: 'text-center',
                    render: function( data, type, row, meta ) {

                        @canany( [ 'edit user_kycs', 'view user_kycs' ] )

                        let view = '',
                            edit = '',
                            status = '';

                        @can( 'edit user_kycs' )
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

        let ukyc = '#{{ $user_kyc }}',
            modalKycDetail = new bootstrap.Modal( document.getElementById( 'modal_kyc_detail' ) );
       
        $( document ).on( 'click', '.dt-view', function() {

            $( '#modal_kyc_detail .form-control-plaintext' ).val( '-' );
            $( '#modal_kyc_detail .form-select' ).val( 2 );
            $( '#modal_kyc_detail textarea' ).val();

            $( '.documents > a' ).addClass( 'hidden' );
            $( ukyc + '_ic_front1' ).removeClass( 'hidden' );
            // $( ukyc + '_ic_back1' ).removeClass( 'hidden' );

            let id = $( this ).data( 'id' );

            $.ajax( {
                url: '{{ route( 'admin.user_kyc.oneUserKyc' ) }}',
                type: 'POST',
                data: {
                    id,
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {

                    $( ukyc + '_id' ).val( response.encrypted_id );
                    $( ukyc + '_nationality' ).val( response.nationality ? response.nationality.country_name : '-' );
                    $( ukyc + '_fullname' ).val( response.fullname );
                    $( ukyc + '_identification_number' ).val( response.identification_number );
                    $( ukyc + '_date_of_birth' ).val( response.date_of_birth );
                    $( ukyc + '_bank' ).val( response.user_bank.bank.name );
                    $( ukyc + '_account_number' ).val( response.user_bank.account_number );
                    $( ukyc + '_account_holder' ).val( response.user_bank.account_holder_name );
                    $( ukyc + '_kyc_status' ).val( response.status );
                    $( ukyc + '_remarks' ).val( response.remarks );

                    let ukds = response.user_kyc_documents;

                    ukds.map( function( v, i ) {

                        if ( v.document_type == 1 ) {
                            $( ukyc + '_ic_front' ).attr( 'href', v.path ).removeClass( 'hidden' );
                            $( ukyc + '_ic_front1' ).addClass( 'hidden' );
                        }
                        
                        // if ( v.document_type == 1 ) {
                        //     $( ukyc + '_ic_front' ).attr( 'href', v.path ).removeClass( 'hidden' );
                        //     $( ukyc + '_ic_front1' ).addClass( 'hidden' );
                        // } else {
                        //     $( ukyc + '_ic_back' ).attr( 'href', v.path ).removeClass( 'hidden' );
                        //     $( ukyc + '_ic_back1' ).addClass( 'hidden' );
                        // }
                    } );
                    
                    modalKycDetail.show();
                },
                error: function( error ) {
                    modalKycDetail.hide();
                    $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                    modalDanger.show();
                },
            } );
        } );

        $( '#modal_kyc_detail .btn-primary' ).on( 'click', function() {

            let formData = new FormData();
            formData.append( 'id', $( ukyc + '_id' ).val() );
            formData.append( 'kyc_status', $( ukyc + '_kyc_status' ).val() );
            formData.append( 'remarks', $( ukyc + '_remarks' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.user_kyc.updateUserKycAdmin' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    modalKycDetail.hide();
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.show();
                    dt_table.draw( false );
                },
                error: function( error ) {
                    modalKycDetail.hide();
                    $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                    modalDanger.show();
                },
            } );
        } );

        let uid = 0,
            status = '',
            scope = '';

        $( document ).on( 'click', '.dt-suspend', function() {

            uid = $( this ).data( 'id' );
            status = 20,
            scope = 'status';

            $( '#modal_confirmation_title' ).html( '{{ __( 'template.x_y', [ 'action' => __( 'datatables.suspend' ), 'title' => Str::singular( __( 'template.users' ) ) ] ) }}' );
            $( '#modal_confirmation_description' ).html( '{{ __( 'template.are_you_sure_to_x_y', [ 'action' => __( 'datatables.suspend' ), 'title' => Str::singular( __( 'template.users' ) ) ] ) }}' );

            modalConfirmation.show();
        } );

        $( document ).on( 'click', '.dt-activate', function() {

            uid = $( this ).data( 'id' );
            status = 10,
            scope = 'status';

            $( '#modal_confirmation_title' ).html( '{{ __( 'template.x_y', [ 'action' => __( 'datatables.activate' ), 'title' => Str::singular( __( 'template.users' ) ) ] ) }}' );
            $( '#modal_confirmation_description' ).html( '{{ __( 'template.are_you_sure_to_x_y', [ 'action' => __( 'datatables.activate' ), 'title' => Str::singular( __( 'template.users' ) ) ] ) }}' );

            modalConfirmation.show();
        } );

        $( document ).on( 'click', '#modal_confirmation_submit', function() {

            switch ( scope ) {
                case 'status':
                    $.ajax( {
                        url: '{{ route( 'admin.user.updateUserStatus' ) }}',
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
            }
        } );

        $( '#submission_date' ).flatpickr( {
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