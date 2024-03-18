<?php
$kyc_create = 'kyc_create';
?>

<div class="rounded-lg bg-white mx-auto max-w-[90vw] md:max-w-[1200px] w-full">
        {{-- <form action="#" method="POST" id="wizard" autocomplete="off" class="relative"> --}}
            <div id="page1" class="relative grid md:grid-rows-1 grid-rows-2 md:grid-cols-2 grid-cols-1 pb-8 md:pb-24">
                <div class="pb-6">
                    <div class="flex items-center justify-between gap-x-4 pr-4">
                        <div class="border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                            <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.personal_info') }}</h4>
                            <p class="text-[11px] text-[#A2ABC1]">{{ __('member.personal_info_note') }}</p>
                        </div>
                    </div>
                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_fullname">{{ __('member.full_name' ) }} <span class="text-[#f00]">*</span></label>
                        <input type="text" id="{{ $kyc_create }}_fullname"  name="full_name" placeholder="{{ __('member.enter' ) }}{{ __('member.full_name' ) }}" value="{{ auth()->user()->userDetail->fullname }}" class="w-full form-control" required readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_identification_number">{{ __('member.id_number' ) }} <span class="text-[#f00]">*</span></label>
                        <input type="text" id="{{ $kyc_create }}_identification_number"  id="{{ $kyc_create }}_identification_number" name="id_number" placeholder="{{ __('member.enter' ) }}{{ __('member.id_number' ) }}" class="w-full form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_date_of_birth">{{ __('member.dob' ) }} <span class="text-[#f00]">*</span></label>
                        <input type="date" id="{{ $kyc_create }}_date_of_birth" name="dob" placeholder="{{ __('member.enter' ) }}{{ __('member.dob' ) }}" class="w-full form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_address">{{ __('member.residential_address' ) }} <span class="text-[#f00]">*</span></label>
                        <textarea name="address" id="{{ $kyc_create }}_address" placeholder="{{ __('member.enter' ) }}{{ __('member.residential_address' ) }}" class="w-full form-control" required></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="md:border-l border-solid border-[#EDEDED]">
                    <div class="flex items-center justify-between gap-x-4 md:mt-0 mt-4 pr-4">
                        <div class="border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                            <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.beneficiary') }}</h4>
                            <p class="text-[11px] text-[#A2ABC1]">{{ __('member.beneficiary_note') }}</p>
                        </div>
                    </div>

                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_beneficiary_fullname">{{ __('member.full_name' ) }}</label>
                        <input type="text" id="{{ $kyc_create }}_beneficiary_fullname" name="bene_full_name" placeholder="{{ __('member.enter' ) }}{{ __('member.beneficiary') }} {{ __('member.full_name' ) }}" class="w-full form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_beneficiary_identification_number">{{ __('member.id_number' ) }}</label>
                        <input type="text" id="{{ $kyc_create }}_beneficiary_identification_number" name="bene_id_number" placeholder="{{ __('member.enter' ) }}{{ __('member.beneficiary') }} {{ __('member.id_number' ) }}" class="w-full form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_contact_number">{{ __('member.contact_number' ) }}</label>
                        <input type="text" id="{{ $kyc_create }}_contact_number" name="bene_phone_number" placeholder="{{ __('member.enter' ) }}{{ __('member.contact_number') }}" class="w-full form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <button type="button" class="relative md:absolute primary_btn text-white py-2 px-4 next-button right-0 left-0 mx-auto bottom-4 w-[120px] transition">Next</button>
            </div>
            <div class="relative grid md:grid-rows-1 grid-rows-2 md:grid-cols-2 grid-cols-1 pb-4 hidden auto-rows-auto" id="page2">
                <div class="pb-6">
                    <div class="flex items-center justify-between gap-x-4 mt-0 pr-4">
                        <div class="border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                            <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.bank_information') }}</h4>
                            <p class="text-[11px] text-[#A2ABC1]">{{ __('member.bank_information_note') }}</p>
                        </div>
                    </div>

                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_bank">{{ __('member.choose_bank' ) }} <span class="text-[#f00]">*</span></label>
                        <select class="form-select" id="{{ $kyc_create }}_bank" data-placeholder="{{ __( 'datatables.select_x', [ 'title' => __( 'user_kyc.bank' ) ] ) }}">
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_account_holder_name">{{ __('member.account_holder_name' ) }} <span class="text-[#f00]">*</span></label>
                        <input type="text" id="{{ $kyc_create }}_account_holder_name" name="bene_id_number" placeholder="{{ __('member.enter' ) }}{{ __('member.account_holder_name') }}" class="w-full form-control" value="{{ auth()->user()->userDetail->fullname }}" required readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="{{ $kyc_create }}_account_number">{{ __('member.account_number' ) }} <span class="text-[#f00]">*</span></label>
                        <input type="text" id="{{ $kyc_create }}_account_number" name="bene_phone_number" placeholder="{{ __('member.enter' ) }}{{ __('member.account_number') }}" class="w-full form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="border-t md:border-t-[0] border-solid border-[#EDEDED] md:border-l mb-[150px] md:mb-12">
                    <div class="flex items-center justify-between gap-x-4 md:mt-0 mt-4 pr-4">
                        <div class="border-solid border-[#EDEDED] border-l-[2px] border-l-[#1A1D56] pl-4  py-4">
                            <h4 class="text-[16px] font-bold text-[#1A1D56]">{{ __('member.document_upload') }}</h4>
                            <p class="text-[11px] text-[#A2ABC1]">{{ __('member.document_upload_note') }}</p>
                        </div>
                    </div>

                    {{-- <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" for="full_name">{{ __('member.upload_ic_front' ) }}</label>
                        <div class="flex justify-center items-center w-full bg-[#F5F8FA] relative cursor-pointer" id="drop-area">
                            <div class="remove-button hidden">
                                <span class="flex justify-center items-center cursor-pointer absolute right-4 top-4 bg-[#ff1f1f] rounded-full border border-solid border-[#ff1f1f] hover:bg-white text-white hover:text-[#ff1f1f] transition w-[40px] h-[40px]" onclick="removeFile()">
                                    <i class="icon-icon20"></i>
                                </span>
                            </div>  
                            <div class=" py-6" id="file-preview" onclick="openFileInput(event)" ondrop="handleDrop(event)">
                                <img src="{{ asset( 'member/Element/id_card_font.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Preview Image" id="image-preview" width="250" height="100" class="block mx-auto"/>
                                <span id="file-name" class="mt-2 text-center block"></span>
                            </div>
                            <!-- Will be hidden -->
                            <input type="file" id="file-input" class="absolute bottom-[-100px] hidden" accept="image/*,application/pdf">
                        </div>
                        <p class="text-[14px] text-[#A1A5B7] text-center mt-2">Drag and drop attachment to the box above.</p>
                    </div> --}}

                    <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" >{{ __( 'user_kyc.ic_front' ) }} <span class="text-[#f00]">*</span></label>
                        <div class="dropzone py-6 flex justify-center items-center w-full bg-[#F5F8FA] relative cursor-pointer" id="{{ $kyc_create }}_ic_front" style="min-height: 0px; border:none">
                            <div class="dz-message needsclick">
                                <img src="{{ asset( 'member/Element/id_card_font.png' ). Helper::assetVersion() }}{{ date('Y-m-d-H:i:s') }}" alt="Preview Image" id="image-preview" width="250" height="100" class="block mx-auto"/>
                            </div>
                        </div>
                        <p class="text-[14px] text-[#A1A5B7] text-center mt-2">{{ __('member.drop_file_or_click_to_upload' ) }}</p>
                    </div>
 
                    {{-- <div class="px-4 py-2">
                        <label class="text-[12px] text-[#A1A5B7]" >{{ __( 'user_kyc.ic_back' ) }}</label>
                        <div class="dropzone py-6 flex justify-center items-center w-full bg-[#F5F8FA] relative cursor-pointer" id="{{ $kyc_create }}_ic_back" style="min-height: 0px; border:none">
                            <div class="dz-message needsclick">
                                <img src="{{ asset( 'member/Element/id_card_back.png' ). Helper::assetVersion() }}{{ date('Y-m-d-H:i:s') }}" alt="Preview Image" id="image-preview" width="250" height="100" class="block mx-auto"/>
                            </div>
                        </div>
                        <p class="text-[14px] text-[#A1A5B7] text-center mt-2">{{ __('member.drop_file_or_click_to_upload' ) }}</p>
                    </div> --}}

                    
                </div>
                <div class="absolute left-0 right-0 mx-auto bottom-12 md:bottom-[unset] md:relative col-span-2 flex flex-col justify-center items-center gap-2">
                    <button id="{{ $kyc_create }}_submit" class="primary_btn transition w-full mt-6 mx-auto max-w-[250px] min-w-[120px]" value="{{ __('member.submit' ) }}">{{ __('member.submit' ) }}</button>
                    <button type="button" class="outline-0 border-b border-solid border-[#1A1D56] text-[#1A1D56] px-2 text-[12px] prev-button transition">Back</button>
                </div>
            </div>
            <!-- <div class="flex items-center gap-x-2">
                <label class="custom-checkbox text-[12px] text-[#A1A5B7]">
                    <input type="checkbox" id="termsCheckbox" name="termsCheckbox" class="hidden">
                    <span class="checkmark"></span>
                    <label for="termsCheckbox" class="">{{ __('member.i_accept' ) }}</label>
                    <a href="#" id="tnc_btn" class="text-[#1A1D56]">{{ __('member.tnc' ) }}</a>
                </label>
            </div> -->
        {{-- </form> --}}
</div>

<script src="{{ asset( 'member/jQuery-plugin-progressbar.js?v=' ) }}{{ date('Y-m-d-H:i:s') }}"></script>

<script>
    document.addEventListener( 'DOMContentLoaded', () => {

        let kycc = '#{{ $kyc_create }}',
            fileID = '',
            fileID2 = '';

        $(".progress-bar").loading();

        $('#copyButton').on('click', function () {
            var contentToCopy = $('#readonlyInput').val();
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(contentToCopy).select();
            document.execCommand('copy');
            tempInput.remove();
        });

        $(".next-button").on("click", function () {
            // $("#page1").addClass("hidden");
            // $("#page2").removeClass("hidden");

            resetInputValidation();

            let formData = new FormData();
            formData.append( 'fullname', $( kycc + '_fullname' ).val() );
            formData.append( 'identification_number', $( kycc + '_identification_number' ).val() );
            formData.append( 'date_of_birth', $( kycc + '_date_of_birth' ).val() );
            formData.append( 'address', $( kycc + '_address' ).val() );
            formData.append( 'beneficiary_fullname', $( kycc + '_beneficiary_fullname' ).val() );
            formData.append( 'beneficiary_identification_number', $( kycc + '_beneficiary_identification_number' ).val() );
            formData.append( 'contact_number', $( kycc + '_contact_number' ).val() );
            formData.append( 'page', 1 );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {   
                url: '{{ route( 'web.kyc.memberKycValidate' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( kycc + '_body' ).loading( 'stop' );

                    $( '#page1' ).addClass( 'hidden' );
                    $( '#page2' ).removeClass( 'hidden' );
                },
                error: function( error ) {

                    let errorText = '';
                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors,
                            errorArray = [];
                        $.each( errors, function( key, value ) {
                            errorArray.push( value );
                        } );
                        errorText = errorArray.join( '<br>' );
                    } else {
                        errorText = error.responseJSON.message;
                    }

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/failed-icon.png?v=1.2' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x_validation', [ 'title' => Str::singular( __( 'member.user_kycs' ) ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $( '#alert-modal').removeClass('hidden');

                }
            } );

        });

        $(".prev-button").on("click", function () {
            $("#page2").addClass("hidden");
            $("#page1").removeClass("hidden");
        });

        $( kycc + '_bank').select2({

            theme: 'bootstrap-5',
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: true,

            ajax: { 
                url: '{{ route( 'web.bank.getActiveBank' ) }}',
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
            url: '{{ route( 'member.file.upload' ) }}',
            maxFiles: 1,
            acceptedFiles: 'image/jpg,image/jpeg,image/png,application/pdf',
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

        // const dropzone2 = new Dropzone( kycc + '_ic_back', { 
        //     url: '{{ route( 'member.file.upload' ) }}',
        //     maxFiles: 1,
        //     acceptedFiles: 'image/jpg,image/jpeg,image/png,application/pdf',
        //     addRemoveLinks: true,
        //     removedfile: function( file ) {
        //         fileID2 = null;
        //         file.previewElement.remove();
        //     },
        //     success: function( file, response ) {
        //         console.log( file );
        //         console.log( response );
        //         if ( response.status == 200 )  {
        //             fileID2 = response.data.id;
        //         }
        //     }
        // } );
        
        $( kycc + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __('member.loading' ) }}'
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
                url: '{{ route( 'web.kyc.createKyc' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/sucessful-icon.png' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.successful_submit_kyc' ) }}` );
                    $( '#modal_desc' ).html( `{{ __( 'member.successful_submit_kyc_note' ) }}` );
                    $( '#modal_btn' ).html( `{{ __( 'member.ok' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                    $('.close_btn, #modal_btn').on('click', function (){
                        window.location.href = '{{ route( 'web.kyc.index' ) }}';
                    });

                },
                error: function( error ) {

                    let errorText = '';
                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors,
                            errorArray = [];
                        $.each( errors, function( key, value ) {
                            errorArray.push( value );
                        } );
                        errorText = errorArray.join( '<br>' );
                    } else {
                        errorText = error.responseJSON.message;
                    }

                    $( '#modal_icon' ).attr( 'src', `{{ asset( 'member/Element/failed-icon.png?v=1.2' ) }}` );
                    $( '#modal_subject' ).html( `{{ __( 'member.failed_x_validation', [ 'title' => Str::singular( __( 'member.user_kycs' ) ) ] ) }}` );
                    $( '#modal_desc' ).html( errorText );
                    $( '#modal_btn' ).html( `{{ __( 'member.try_again' ) }}` );
                    $('#alert-modal').removeClass('hidden');

                }
            } );
        } );

        // You can handle form submission here
        $("#submit_btn").on("click", function () {
            alert("Form submitted!");
        });

        const dropArea = $("#drop-area");
        const fileInput = $("#file-input");
        const filePreview = $("#file-preview");
        const fileName = $("#file-name");
        const removeButton = $(".remove-button");
        const imagePreview = $("#image-preview");

        dropArea.on("dragover", function(event) {
            event.preventDefault();
            dropArea.css("border", "2px dashed #333");
        });

        dropArea.on("dragleave", function() {
            dropArea.css("border", "2px dashed #ccc");
        });

        dropArea.on("drop", function(event) {
            event.preventDefault();
            dropArea.css("border", "2px dashed #ccc");

            const files = event.originalEvent.dataTransfer.files;
            handleFiles(files);
            updateFileInput(files)
        });

        fileInput.on("change", function() {
            const files = fileInput[0].files;
            handleFiles(files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                const isImage = file.type.startsWith("image/");
                const isPDF = file.type === "application/pdf";
                const maxSize = 10 * 1024 * 1024; // 10MB

                if ((isImage || isPDF) && file.size <= maxSize) {
                    displayFile(file, file.type);
                    removeButton.removeClass("hidden");
                } else {
                    alert("Please upload a valid image or PDF file (up to 10MB).");
                }
            }
            console.log(files);
        }

        function displayFile(file, drop_item) {
            filePreview.removeClass("hidden");
            // fileContainer.addClass("hidden");
            fileName.text(file.name);

            if (drop_item.includes("image/")) {
                imagePreview.attr("src", URL.createObjectURL(file));
                imagePreview.removeClass("hidden");
            } else if (drop_item == 'application/pdf') {
                imagePreview.attr("src", "{{ asset( 'member/Element/pdf.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}");
                imagePreview.removeClass("hidden");
            } else {
                imagePreview.addClass("hidden");
            }
            // console.log(file.name);
        }

        function updateFileInput(files) {
            if (files.length > 0) {
                fileInput[0].files = files;
            }
        }
    });

        function openFileInput(event) {
            event.preventDefault();
            $("#file-input").click();
        }

        function handleDrop(event) {
            event.preventDefault();
            const files = event.dataTransfer.files;
            handleFiles(files);
            updateFileInput(files);
        }

        function removeFile() {
            const filePreview = $("#file-preview");
            const fileName = $("#file-name");
            const removeButton = $(".remove-button");
            const fileInput = $("#file-input");
            fileInput.val("");
            fileName.html("");
            const imagePreview = $("#image-preview");
            imagePreview.attr("src", "{{ asset( 'member/Element/id_card_font.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}");
            removeButton.addClass("hidden");
        }
        
        function resetInputValidation() {

            $( '.form-control' ).each( function( i, v ) {
                if ( $( this ).hasClass( 'is-invalid' ) ) {
                    $( this ).removeClass( 'is-invalid' ).nextAll( 'div.invalid-feedback' ).html( '' );
                }
            } );

            $( '.form-select' ).each( function( i, v ) {
                if ( $( this ).hasClass( 'is-invalid' ) ) {
                    $( this ).removeClass( 'is-invalid' ).nextAll( 'div.invalid-feedback' ).html( '' );
                }
            } );
        }

</script>