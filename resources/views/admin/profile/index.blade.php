<?php
$profile = 'profile';
?>


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <strong>{{ __( 'profile.account_setting' ) }}</strong>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $profile }}_username" class="col-sm-5 col-form-label">{{ __( 'administrator.username' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $profile }}_username" value="{{ auth()->user()->username }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $profile }}_email" class="col-sm-5 col-form-label">{{ __( 'administrator.email' ) }}</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="{{ $profile }}_email" value="{{ auth()->user()->email }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <strong>{{ __( 'profile.security_setting' ) }}</strong>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $profile }}_current_password" class="col-sm-5 col-form-label">{{ __( 'profile.current_password' ) }}</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control form-control-sm" id="{{ $profile }}_current_password" placeholder="{{ __( 'template.leave_blank' ) }}">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $profile }}_new_password" class="col-sm-5 col-form-label">{{ __( 'profile.new_password' ) }}</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control form-control-sm" id="{{ $profile }}_new_password">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="{{ $profile }}_confirm_new_password" class="col-sm-5 col-form-label">{{ __( 'profile.confirm_new_password' ) }}</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control form-control-sm" id="{{ $profile }}_confirm_new_password">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="text-end">
                    <button id="{{ $profile }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <strong>{{ __( 'profile.language_setting' ) }}</strong>
                </div>
                <div class="row">
                    <label for="{{ $profile }}_switch_language" class="col-sm-5 col-form-label">{{ __( 'profile.language' ) }}</label>
                    <div class="col-sm-7">
                        <select class="form-control form-control-sm" id="{{ $profile }}_switch_language">
@foreach( Config::get( 'languages' ) as $lang => $language )
@if( $lang != App::getLocale() )
                            <option value="{{ $lang }}">{{ $language }}</option>
@else
                            <option value="{{ $lang }}" selected>{{ $language }}</option>
@endif
@endforeach
                        </select>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        $( '#modal_success' ).on( 'hidden.bs.modal', function() {
            window.location.reload();
        } );

        let p = '#profile';


        $( p + '_submit' ).click( function() {

            resetInputValidation();

            $.ajax( {
                url: '{{ route( 'admin.profile.update' ) }}',
                type: 'POST',
                data: {
                    username: $( p + '_username' ).val(),
                    email: $( p + '_email' ).val(),
                    current_password: $( p + '_current_password' ).val(),
                    new_password: $( p + '_new_password' ).val(),
                    confirm_new_password: $( p + '_confirm_new_password' ).val(),
                    '_token': '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.show();
                },
                error: function( error ) {
                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( p + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.show();       
                    }
                }
            } )
        } );

        $( p + '_switch_language' ).change( function() {

            window.location.href = '{{ Helper::baseAdminUrl() }}/lang/' + $( this ).val();
        } );
    } );
</script>