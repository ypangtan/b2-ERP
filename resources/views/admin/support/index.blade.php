<?php
$support_create = 'support_create';
?>

<div class="row">
    <div class="card card-support col-md-4">
        <div class="card-body user-chat-history-container">
            <div class="row">
            @if( auth()->user()->hasrole('user') )
                <div class="new-chat-btn-container">
                    <button class="btn btn-primary new-chat-btn" id="new_support">{{ __( 'support.new_ticket' ) }}</button>
                </div>  
            @endif
                <div class="user-chat-history">
                </div>
            </div>
        </div>
    </div>

    <div class="card card-support col-md-7">
        <h6 class="card-title ticket-title">{{ __( 'support.choose_support_ticket' ) }}</h6>
        <span class="ticket-number"></span>
        <span class="refresh-button fade"><i class="align-middle feather" icon-name="arrow-left-right"></i></span>
        <div class="card-body">
            <div class="chat-container fade">
                <div class="support-chat">
                </div>
                <hr>
            </div>
            <div class="chat-reply row fade">
                <textarea class="form-control-plaintext" placeholder="{{ __( 'support.type_your_message' ) }}" id="{{ $support_create }}_support_reply"></textarea>
                <div class="reply-chat-btn-container">
                    <button class="btn btn-primary" id="{{ $support_create }}_reply">{{ __( 'support.reply' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- new support ticket --}}
<div class="modal fade" id="new_support_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{!! __( 'support.opening_new_support_ticket' ) !!}</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="{{ $support_create }}_title" class="col-sm-5 col-form-label">{{ __( 'support.title' ) }}</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" id="{{ $support_create }}_title" placeholder="{{ __( 'support.title' ) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $support_create }}_content" class="col-sm-5 col-form-label">{{ __( 'support.content' ) }}</label>
                    <div class="col-sm-12">
                        <textarea class="form-control form-control-sm" id="{{ $support_create }}_content" placeholder="{{ __( 'support.describe_your_issues' ) }}"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="id">
            <div class="modal-footer">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button type="button" class="btn btn-sm btn-primary" id="{{ $support_create }}_submit">{{ __( 'template.confirm' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let sc = '#{{ $support_create }}',
            nsm = new bootstrap.Modal( document.getElementById( 'new_support_modal' ) );

        $( sc + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.support.index' ) }}';
        } );

        $( sc + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            let formData = new FormData();
            formData.append( 'title', $( sc + '_title' ).val() );
            formData.append( 'content', $( sc + '_content' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.support.createSupportTicket' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();
                    nsm.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        getUserSupportTickets()
                        getChatHistory( response.ticketId );
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( sc + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        nsm.toggle();
                        modalDanger.toggle();       
                    }
                }
            } );
        } );

        $('#new_support').click(function() {
            nsm.toggle();
        });

        getUserSupportTickets()

        function getUserSupportTickets(){

            $('.user-chat-history').empty();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            $.ajax( {
                url: '{{ route( 'admin.support.userSupportTickets' ) }}',
                type: 'POST',
                data: {
                    id: '{{ request( 'id' ) }}',
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {
                    
                    response.forEach(response => {
                        const chatItem = `
                            <div class="chat-item" id="${response.encrypted_id}">
                                <img src=https://ui-avatars.com/api/?background=3461ff&color=fff&name=${response.user.name} alt="User Icon" class="user-icon">
                                <div class="chat-details">
                                    <div>
                                        <p class="user-name">${response.user.name}</p>
                                        <p class="question">${response.title}</p>
                                    </div>
                                </div>
                                <span class="time-created">${response.created_at}</span>
                            </div>
                        `;
                        
                        $('.user-chat-history').append(chatItem);
                    });

                    $( 'body' ).loading( 'stop' );

                },
            } );

        }

        $('.user-chat-history').on('click', '.chat-item', function() {
            $( '.refresh-button' ).attr('data-value', $(this).attr('id') )
            $( '.refresh-button' ).removeClass('fade');
            getChatHistory( $(this).attr('id') );
        });

        $( sc + '_reply' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            $ticketId = $( '.chat-container' ).attr('data-value');

            let formData = new FormData();
            formData.append( 'id', $ticketId );
            formData.append( 'content', $( sc + '_support_reply' ).val() );
            formData.append( '_token', '{{ csrf_token() }}' );

            $.ajax( {
                url: '{{ route( 'admin.support.createSupportTicketResponse' ) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        getChatHistory( $ticketId );
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( sc + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.toggle();       
                    }
                }
            } );
        } );

        function getChatHistory( $ticketId ){

            $( sc + '_support_reply' ).val('');
            $('.support-chat').empty();
            $('.chat-container').addClass('fade');

            var clickedId = $ticketId;
            var chatItem = '';

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            $.ajax( {
                url: '{{ route( 'admin.support.oneSupportTicket' ) }}',
                type: 'POST',
                data: {
                    id: clickedId,
                    _token: '{{ csrf_token() }}',
                },
                success: function( response ) {
                    
                    ticketResponses = response.ticket_responses;

                    ticketResponses.forEach( ticketResponse => {
                        var user = ticketResponse.user.name;
                        var admin = ticketResponse.admin ? ticketResponse.admin.name : null;
                        var isUser = ticketResponse.user_id == {{ auth()->user()->id }};
                        var isAdmin = ticketResponse.admin_id != null;

                        var date = ticketResponse.created_at;
                        var content = ticketResponse.content;

                        let chatItem;

                        if ( isUser && !isAdmin ) {
                            chatItem = buildOutgoingChatItem( user, date, content );
                        } else if ( isAdmin ) {
                            chatItem = buildOutgoingChatItem( admin, date, content );

                            if ( isUser ) {
                                chatItem = buildIncomingChatItem( admin || user, date, content );
                            }
                        } else {
                            chatItem = buildIncomingChatItem( admin || user, date, content );
                        }

                        $('.support-chat').append( chatItem );
                    });

                    $('.chat-container').removeClass( 'fade' );
                    $('.chat-reply').removeClass( 'fade' );
                    $('.ticket-title').text(response.title);
                    $('.ticket-number').text(response.ticket_reference);
                    $('.chat-container').attr( 'data-value' , response.encrypted_id );

                    $( 'body' ).loading( 'stop' );

                },
            } );
        }

        function buildOutgoingChatItem( user, date, content ) {
            return `
                <div class="chat-item-outgoing" id="o0" style="border-bottom:transparent">
                    <img src="https://ui-avatars.com/api/?background=3461ff&amp;color=fff&amp;name=${user}" alt="User Icon" class="user-icon">
                    <p class="user-name">${user}</p>
                    {!! "&nbsp&nbsp&nbsp;" !!} <p class="ticket-date">${date}</p>
                </div>
                <div class="chat-message outgoing">
                    <p>${content}</p>
                </div>
            `;
        }

        function buildIncomingChatItem( user, date, content ) {
            return `
                <div class="chat-item-incoming" id="o0" style="border-bottom:transparent">
                    <p class="ticket-date">${date}</p>
                    {!! "&nbsp&nbsp&nbsp;" !!} <p class="user-name">${user}</p>{!! "&nbsp&nbsp&nbsp;" !!}
                    <img src="https://ui-avatars.com/api/?background=3461ff&amp;color=fff&amp;name=${user}" alt="User Icon" class="user-icon">
                </div>
                <div class="incoming-message">
                    <div class="chat-message incoming">
                        <p>${content}</p>
                    </div>
                </div>
            `;
        }

        $( '.refresh-button' ).click ( function() {
            $ticketId = $(this).attr('data-value');
            getChatHistory( $ticketId );
            getUserSupportTickets();
        });

    } );
</script>