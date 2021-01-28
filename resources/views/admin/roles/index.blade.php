@extends('layouts.base')
@section('title', trans('global.role.title'))
@section('parentPageTitle', trans('global.role.parentPageTitle'))
@section('breadcumb', trans('global.role.breadcumb'))

@section('content')
<div class="row clearfix">
    <div id="table" class="col-md-12">
        @include('admin.roles.table')
    </div>
    <div id="form" class="col-md-12">
        @include('admin.roles.form')
    </div>
</div>
@stop

@push('after-scripts')
    @include('components.alert')
    <script>
        var id = 0;
        var name = '';
        var guard_name = 'web';
        var permissions = [];
        var list_length = 0;

        function initialize() {
            $('#table').show();
            $('#form').hide();
            $('#title span').text( '{{ trans('cruds.formName.show') }}' );
            $('#title small').text( '{{ trans('global.role.subTitle') }}' );
        }

        function createForm() {
            clearForm();
            resetForm();
            $('#title span').text( '{{ trans('cruds.formName.add') }}' );
            $('#table').hide();
            $('#form').show();
        }

        function editForm(data) {
            clearForm();
            id = data.id;
            name = data.name;
            guard_name = data.guard_name;
            data.permissions.forEach(function(temp) {
                permissions.push(temp.name)
            });
            resetForm();
            $('#title span').text( '{{ trans('cruds.formName.edit') }}' );
            $('#table').hide();
            $('#form').show();
        }

        function clearForm() {
            $('#error-message').hide();
            $('#myForm').parsley().reset();
            $("div.form-group").removeClass('text-danger');
            id = 0;
            name = '';
            guard_name = 'web';
            permissions = [];
        }

        function resetForm() {
            $('#id').val(id);
            $('#name').val(name);
            $('#guard_name').val(guard_name);
            permissionsRole(permissions);
        }

        $(document).ready(function () {
            initialize();
        });
    </script>
@endpush
