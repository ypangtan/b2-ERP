<?php
$role_edit = 'role_edit';
?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="{{ $role_edit }}_role_name" class="col-sm-4 col-form-label">{{ __( 'role.role_name' ) }}</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control-plaintext" id="{{ $role_edit }}_role_name">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div>
                @foreach( $data['modules'] as $module )
                <div class="mb-4 role_edit-modules-section" data-module="{{ $module->name . '|' . $module->guard }}">
                    <h5>{{ __( 'role.module_title', [ 'module' => __( 'module.' . $module->name ) ] ) }} ( {{ __( 'role.' . $module->guard ) }} )</h4>
                    @foreach( $module->presetPermissions as $preset )
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="{{ $role_edit }}_permission" value="{{ $preset->action . ' '. $module->name }}">
                        <label class="form-check-label" for="{{ 'role_edit_' . $preset->action . '_' . $module->name . '_' . $module->guard }}">{{ __( 'role.action_module', [ 'action' => __( 'role.' . $preset->action ), 'module' => __( 'module.' . $module->name ) ] ) }}</label>
                    </div>
                    @endforeach
                    @if ( count( $module->presetPermissions ) == 0 )
                    <p class="text-center">{{ __( 'role.no_action_found' ) }}</p>
                    @endif
                </div>
                @endforeach
                @if ( !$data['modules'] )
                <p class="text-center">{{ __( 'role.no_module_found' ) }}</p>
                @endif
                </div>
                <div class="text-end">
                    <button id="{{ $role_edit }}_cancel" type="button" class="btn btn-sm btn-outline-secondary">{{ __( 'template.cancel' ) }}</button>
                    &nbsp;
                    <button id="{{ $role_edit }}_submit" type="button" class="btn btn-sm btn-primary">{{ __( 'template.save_changes' ) }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="role_edit_id" />

<script>
    document.addEventListener( 'DOMContentLoaded', function() {

        let id = '{{ request( 'id' ) }}',
            re = '#{{ $role_edit }}';

        $( 'body' ).loading( { 
            message: '{{ __( 'template.loading' ) }}',
        }, 'start' );
        
        $.ajax( {
            url: '{{ route( 'admin.role.oneRole' ) }}',
            type: 'POST',
            data: { id, '_token': '{{ csrf_token() }}', },
            success: function( response ) {
                
                $( re + '_id' ).val( response.role.id );
                $( re + '_role_name' ).val( response.role.name );
                $( re + '_guard_name' ).val( response.role.guard_name );

                response.permissions.forEach(function(permission) {
                    $('input[value="' + permission.name + '"]').prop('checked', true);
                });

                $( 'body' ).loading( 'stop' );
            },
        } );

        $( re + '_cancel' ).click( function() {
            window.location.href = '{{ route( 'admin.module_parent.role.index' ) }}';
        } );

        $( re + '_submit' ).click( function() {

            resetInputValidation();

            $( 'body' ).loading( {
                message: '{{ __( 'template.loading' ) }}'
            } );

            var permissions = [];

            $( '.role_edit-modules-section input[type="checkbox"]' ).each(function() {
                if ($(this).prop('checked')) {
                    permissions.push($(this).val());
                }
            });

            $.ajax( {
                url: '{{ route( 'admin.role.updateRole' ) }}',
                type: 'POST',
                data: {
                    'id': '{{ request()->input( 'id' ) }}',
                    'permissions' : permissions,
                    '_token': '{{ csrf_token() }}',
                },
                success: function( response ) {
                    $( 'body' ).loading( 'stop' );
                    $( '#modal_success .caption-text' ).html( response.message );
                    modalSuccess.toggle();

                    document.getElementById( 'modal_success' ).addEventListener( 'hidden.bs.modal', function (event) {
                        window.location.href = '{{ route( 'admin.module_parent.role.index' ) }}';
                    } );
                },
                error: function( error ) {
                    $( 'body' ).loading( 'stop' );

                    if ( error.status === 422 ) {
                        let errors = error.responseJSON.errors;
                        $.each( errors, function( key, value ) {
                            $( re + '_' + key ).addClass( 'is-invalid' ).next().text( value );
                        } );
                    } else {
                        $( '#modal_danger .caption-text' ).html( error.responseJSON.message );
                        modalDanger.toggle();       
                    }
                }
            } );
        } );
    } );
</script>