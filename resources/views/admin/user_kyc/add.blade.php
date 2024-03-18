<?php
$kyc_create = 'kyc_create';
?>

<div class="card kyc_main">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-6 col-xl-6 order-lg-6">
                <div class="mb-3 row">
                    <h5 class="card-title">{{ __( 'user_kyc.personal_info' ) }}</h5>
                    <p>{{ __( 'user_kyc.personal_info_details' ) }}</p>
                    <hr>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_fullname" class="col-sm-12 col-form-label">{{ __( 'user_kyc.fullname' ) }}</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="{{ $kyc_create }}_fullname" placeholder="{{ __( 'user_kyc.enter_x', [ 'title' => Str::singular( __( 'user_kyc.fullname' ) ) ] ) }}" >
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_identification_number" class="col-sm-12 col-form-label">{{ __( 'user_kyc.identification_number' ) }}</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="{{ $kyc_create }}_identification_number" placeholder="{{ __( 'user_kyc.enter_x', [ 'title' => Str::singular( __( 'user_kyc.identification_number' ) ) ] ) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_date_of_birth" class="col-sm-12 col-form-label">{{ __( 'user_kyc.date_of_birth' ) }}</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="{{ $kyc_create }}_date_of_birth"  placeholder="{{ __( 'user_kyc.enter_x', [ 'title' => Str::singular( __( 'user_kyc.date_of_birth' ) ) ] ) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_address" class="col-sm-12 col-form-label">{{ __( 'user_kyc.residential_address' ) }}</label>
                    <div class="col-sm-12">
                        <textarea class="form-control form-control-sm" id="{{ $kyc_create }}_address"  rows="5" placeholder="{{ __( 'user_kyc.enter_x', [ 'title' => Str::singular( __( 'user_kyc.residential_address' ) ) ] ) }}"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

            </div>
            <div class="col-12 col-md-12 col-lg-6 col-xl-6 order-lg-6" style=" border-left: 1px solid #ededed;" >
                <div class="mb-3 row">
                    <div style=" border-left: 2px solid #1a1d56;" ><h5 class="card-title">{{ __( 'user_kyc.beneficiary' ) }}</h5>
                    <p>{{ __( 'user_kyc.beneficiary_details' ) }}</p>
                    </div>
                    <hr>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_beneficiary_fullname" class="col-sm-12 col-form-label">{{ __( 'user_kyc.beneficiary_fullname' ) }}</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="{{ $kyc_create }}_beneficiary_fullname" placeholder="{{ __( 'user_kyc.enter_x', [ 'title' => Str::singular( __( 'user_kyc.beneficiary_fullname' ) ) ] ) }}" >
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_beneficiary_identification_number" class="col-sm-12 col-form-label">{{ __( 'user_kyc.beneficiary_identification_number' ) }}</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="{{ $kyc_create }}_beneficiary_identification_number" placeholder="{{ __( 'user_kyc.enter_x', [ 'title' => Str::singular( __( 'user_kyc.beneficiary_identification_number' ) ) ] ) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_contact_number" class="col-sm-12 col-form-label">{{ __( 'user_kyc.contact_number' ) }}</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="{{ $kyc_create }}_contact_number" placeholder="{{ __( 'user_kyc.enter_x', [ 'title' => Str::singular( __( 'user_kyc.contact_number' ) ) ] ) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="text-center" style="padding: 2%">
                <button id="{{ $kyc_create }}_validate_first_section" type="button" class="btn btn-sm btn-primary">{{ __( 'user_kyc.next' ) }}</button>
            </div>
        </div>
    </div>
</div>

<div class="card kyc_additional hidden">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-6 col-xl-6 order-lg-6">
                <div class="mb-3 row">
                    <h5 class="card-title">{{ __( 'user_kyc.bank_information' ) }}</h5>
                    <p>{{ __( 'user_kyc.bank_information_details' ) }}</p>
                    <hr>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_bank" class="col-sm-12 col-form-label">{{ __( 'user_kyc.choose_bank' ) }}</label>
                    <div class="col-sm-12">
                        <select class="form-select" id="{{ $kyc_create }}_bank" data-placeholder="{{ __( 'datatables.select_x', [ 'title' => __( 'user_kyc.bank' ) ] ) }}">
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_account_holder_name" class="col-sm-12 col-form-label">{{ __( 'user_kyc.account_holder_name' ) }}</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="{{ $kyc_create }}_account_holder_name" placeholder="{{ __( 'user_kyc.enter_x', [ 'title' => Str::singular( __( 'user_kyc.account_holder_name' ) ) ] ) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $kyc_create }}_account_number" class="col-sm-12 col-form-label">{{ __( 'user_kyc.account_number' ) }}</label>
                    <div class="col-sm-12">
                        <input type="email" class="form-control form-control-sm" id="{{ $kyc_create }}_account_number"  placeholder="{{ __( 'user_kyc.enter_x', [ 'title' => Str::singular( __( 'user_kyc.account_number' ) ) ] ) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-6 col-xl-6 order-lg-6" style=" border-left: 1px solid #ededed;" >
                <div class="mb-3 row">
                    <div style=" border-left: 2px solid #1a1d56;" ><h5 class="card-title">{{ __( 'user_kyc.document_upload' ) }}</h5>
                    <p>{{ __( 'user_kyc.document_upload_details' ) }}</p>
                    </div>
                    <hr>
                </div>
                <label>{{ __( 'user_kyc.ic_front' ) }}</label>
                <div class="dropzone mb-3" id="{{ $kyc_create }}_ic_front" style="min-height: 0px;">
                    <div class="dz-message needsclick">
                        <img src="{{ asset( 'member/Element/id_card_font.png' ). Helper::assetVersion() }}{{ date('Y-m-d-H:i:s') }}" alt="Preview Image" id="image-preview" width="250" height="100" class="block mx-auto"/>
                    </div>
                </div>
                <p class="text-center">{{ __( 'template.drop_file_or_click_to_upload' ) }}</p>

                <label>{{ __( 'user_kyc.ic_back' ) }}</label>
                <div class="dropzone mb-3" id="{{ $kyc_create }}_ic_back" style="min-height: 0px;">
                    <div class="dz-message needsclick">
                        <img src="{{ asset( 'member/Element/id_card_back.png' ). Helper::assetVersion() }}{{ date('Y-m-d-H:i:s') }}" alt="Preview Image" id="image-preview" width="250" height="100" class="block mx-auto"/>
                    </div>
                </div>
                <p class="text-center">{{ __( 'template.drop_file_or_click_to_upload' ) }}</p>
                
            </div>
            <div class="text-center" style="padding: 2%">
                <button id="{{ $kyc_create }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
            </div>
            <div class="text-center">
                <button id="{{ $kyc_create }}_back" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'user_kyc.back' ) }}</button>
            </div>
        </div>
    </div>
</div>

<div class="card kyc_complete hidden">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12 order-lg-12">
                <div class="mb-3 row text-center">
                    <img src="{{ asset( 'member/Element/kyc_icon.png' ). Helper::assetVersion() }}{{ date('Y-m-d-H:i:s') }}" alt="complete Image" id="image-complete" class="block mx-auto" style="width: 10%; height: 100%;"/>
                </div>
                <div class="mb-3 row text-center">
                    <h5 class="card-title">{{ __( 'user_kyc.kyc_completed' ) }}</h5>
                    <p>{{ __( 'user_kyc.submitted' ) }}</p>
                    <img src="{{ asset( 'member/Element/kyc_vector.png' ). Helper::assetVersion() }}{{ date('Y-m-d-H:i:s') }}" alt="complete Image" id="image-complete" class="block mx-auto" style="width: 50%; height: 100%;"/>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let kycc = '#{{ $kyc_create }}',
            fileID = '';
            fileID2 = '';

        $( kycc + '_date_of_birth' ).flatpickr();

        $( kycc + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.user_kyc.index' ) }}';
        } );

        $( kycc + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'fullname', $( kycc + '_fullname' ).val() );
            formData.append( 'identification_number', $( kycc + '_identification_number' ).val() );
            formData.append( 'date_of_birth', $( kycc + '_date_of_birth' ).val() );
            formData.append( 'address', $( kycc + '_address' ).val() );
            formData.append( 'beneficiary_fullname', $( kycc + '_beneficiary_fullname' ).val() );
            formData.append( 'beneficiary_identification_number', $( kycc + '_beneficiary_identification_number' ).val() );
            formData.append( 'contact_number', $( kycc + '_contact_number' ).val() );
            formData.append( 'bank', $( kycc + '_bank' ).val() );
            formData.append( 'account_holder_name', $( kycc + '_account_holder_name' ).val() );
            formData.append( 'account_number', $( kycc + '_account_number' ).val() );
            formData.append( 'ic_front', fileID );
            formData.append( 'ic_back', fileID2 );
            formData.append( '_token', '{{ csrf_token() }}' );
            
            $.ajax( {
                url: '{{ route( 'admin.user_kyc.createUserKyc' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        // window.location.href = '{{ route( 'admin.module_parent.user_kyc.index' ) }}';
                        $( '.kyc_additional' ).addClass( 'hidden' );
                        $( '.kyc_complete' ).removeClass( 'hidden' );
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( kycc + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.toggle();       
                    }
                }
            } );
        } );

        $( kycc + '_validate_first_section' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'fullname', $( kycc + '_fullname' ).val() );
            formData.append( 'identification_number', $( kycc + '_identification_number' ).val() );
            formData.append( 'date_of_birth', $( kycc + '_date_of_birth' ).val() );
            formData.append( 'address', $( kycc + '_address' ).val() );
            formData.append( 'beneficiary_fullname', $( kycc + '_beneficiary_fullname' ).val() );
            formData.append( 'beneficiary_identification_number', $( kycc + '_beneficiary_identification_number' ).val() );
            formData.append( 'contact_number', $( kycc + '_contact_number' ).val() );
            // formData.append( 'page', 1 );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.user_kyc.userKycValidate' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );

                    $( '.kyc_main' ).addClass( 'hidden' );
                    $( '.kyc_additional' ).removeClass( 'hidden' );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( kycc + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.toggle();       
                    }
                }
            } );
        } );

        $( kycc + '_back' ).click( function() {
            $( '.kyc_main' ).removeClass( 'hidden' );
            $( '.kyc_additional' ).addClass( 'hidden' );
        } );

        $( kycc + '_bank').select2({

            theme: 'bootstrap-5',
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: true,
            
            ajax: { 
                url: '{{ route( 'admin.bank.allBanks' ) }}',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    return {
                        custom_search: params.term, // search term
                        designation: 1,
                        status: 10,
                        start: ( ( params.page ? params.page : 1 ) - 1 ) * 10,
                        length: 10,
                        _token: '{{ csrf_token() }}',
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
            
                    let processedResult = [];

                    data.banks.map( function( v, i ) {
                        processedResult.push( {
                            id: v.id,
                            text: v.name,
                        } );
                    } );

                    return {
                        results: processedResult,
                        pagination: {
                            more: ( params.page * 10 ) < data.recordsFiltered
                        }
                    };
                },
                cache: true
            }

        });

        Dropzone.autoDiscover = false;
        const dropzone = new Dropzone( kycc + '_ic_front', { 
            url: '{{ route( 'admin.file.upload' ) }}',
            maxFiles: 1,
            acceptedFiles: 'image/jpg,image/jpeg,image/png',
            addRemoveLinks: true,
            removedfile: function( file ) {
                fileID = null;
                file.previewElement.remove();
            },
            success: function( file, response ) {
                console.log( file );
                console.log( response );
                if ( response.status == 200 )  {
                    fileID = response.data.id;
                }
            }
        } );

        const dropzone2 = new Dropzone( kycc + '_ic_back', { 
            url: '{{ route( 'admin.file.upload' ) }}',
            maxFiles: 1,
            acceptedFiles: 'image/jpg,image/jpeg,image/png',
            addRemoveLinks: true,
            removedfile: function( file ) {
                fileID2 = null;
                file.previewElement.remove();
            },
            success: function( file, response ) {
                console.log( file );
                console.log( response );
                if ( response.status == 200 )  {
                    fileID2 = response.data.id;
                }
            }
        } );

    } );
</script>