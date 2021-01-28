@extends('layouts.base')
@section('title', trans('global.permission.title'))
@section('parentPageTitle', trans('global.permission.parentPageTitle'))
@section('breadcumb', trans('global.permission.breadcumb'))

@section('content')
<div class="row clearfix">
    <div id="table" class="col-md-12">
        @include('admin.permissions.table')
    </div>
    <div id="form" class="col-md-12">
        @include('admin.permissions.form')
    </div>
</div>
@stop

@push('after-scripts')
    @include('components.alert')
    <script>
        var id = 0;
        var name = '';
        var guard_name = 'web';

        function initialize() {
            $('#table').show();
            $('#form').hide();
            $('#title span').text( '{{ trans('cruds.formName.show') }}' );
            $('#title small').text( '{{ trans('global.permission.subTitle') }}' );
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
        }

        function resetForm() {
            $('#id').val(id);
            $('#name').val(name);
            $('#guard_name').val(guard_name);
        }

        $(document).ready(function () {
            initialize();
        });
    </script>
@endpush
