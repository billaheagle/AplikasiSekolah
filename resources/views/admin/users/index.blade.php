@extends('layouts.base')
@section('title', trans('global.user.title'))
@section('parentPageTitle', trans('global.user.parentPageTitle'))
@section('breadcumb', trans('global.user.breadcumb'))

@section('content')
<div class="row clearfix">
    <div id="table" class="col-md-12">
        @include('admin.users.table')
    </div>
    <div id="form" class="col-md-12">
        @include('admin.users.form')
    </div>
</div>
@stop

@push('after-scripts')
    @include('components.alert')
    <script>
        var id = 0;
        var email = '';
        var name = '';
        var password = '';
        var phone_number = '';
        var alternative_phone_number = '';
        var address = '';
        var roles = [];
        var list_length = 0;

        function initialize() {
            $('#table').show();
            $('#form').hide();
            $('#reset-password').hide();
            $('#title span').text( '{{ trans('cruds.formName.show') }}' );
            $('#title small').text( '{{ trans('global.user.subTitle') }}' );
        }

        function createForm() {
            clearForm();
            resetForm();
            $('#title span').text( '{{ trans('cruds.formName.add') }}' );
            $('#btn-save').attr('disabled', true);
            $('.password').show();
            $('#table').hide();
            $('#form').show();
        }

        function editForm(data) {
            clearForm();
            id = data.id;
            email = data.email;
            name = data.name;
            phone_number = data.phone_number;
            alternative_phone_number = data.alternative_phone_number;
            address = data.address;
            data.roles.forEach(function(temp) {
                roles.push(temp.name)
            });
            resetForm();
            $('#title span').text( '{{ trans('cruds.formName.edit') }}' );
            $('#btn-save').attr('disabled', false);
            $('.password').hide();
            $('#table').hide();
            $('#form').show();
        }

        function resetPasswordForm(temp_id, temp_name, temp_email) {
            clearForm();
            id = temp_id;
            name = temp_name;
            email = temp_email;
            resetForm();
            $('#title span').text( '{{ trans('cruds.formName.resetPassword') }}' );
            $('#btn-save').attr('disabled', false);
            $('#email, #name').attr('disabled', true);
            $('.detail-user').hide();
            $('#table').hide();
            $('#form').show();
        }

        function clearForm() {
            $('.password, .detail-user').show();
            $('#email, #name').attr('disabled', false);
            $('#error-message').hide();
            $('#myForm').parsley().reset();
            id = 0;
            email = name = password = phone_number = alternative_phone_number = address = '';
            roles = [];
        }

        function resetForm() {
            $("div.form-group").removeClass('text-danger');
            $('#id').val(id);
            $('#email').val(email);
            $('#name').val(name);
            $('#password, #password-confirmation').val(password);
            $('#phone-number').val(phone_number);
            $('#alternative-phone-number').val(alternative_phone_number);
            $('#address').val(address);
            rolesUser(roles);
        }

        $(document).ready(function () {
            initialize();
        });
    </script>
@endpush
