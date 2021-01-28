@extends('layouts.base')
@section('title', trans('global.menu.title'))
@section('parentPageTitle', trans('global.menu.parentPageTitle'))

@section('content')
<div class="row clearfix">
    <div id="table" class="col-md-12">
        @include('admin.menus.table')
    </div>
    <div id="form" class="col-md-12">
        @include('admin.menus.form')
    </div>
</div>
@stop

@push('after-scripts')
    @include('components.alert')
    <script>
        var id = 0;
        var title = '';
        var url = '';
        var icon = 'icon-home';
        var parent_id = '';
        var menu_url = '';
        var parent_url = '';

        function initialize() {
            $('#table').show();
            $('#form').hide();
            $('#title span').text( '{{ trans('cruds.formName.show') }}' );
            $('#title small').text( '{{ trans('global.menu.subTitle') }}' );
        }

        function createForm() {
            clearForm();
            resetForm();
            $('#title span').text( '{{ trans('cruds.formName.add') }}' );
            $("#check_crud").show();
            $('#table').hide();
            $('#form').show();
        }

        function editForm(data) {
            clearForm();
            id = data.id;
            title = data.title;
            url = data.url;
            icon = data.icon;
            parent_id = data.parent_id;
            splitUrl(url);
            resetForm();
            $('#title span').text( '{{ trans('cruds.formName.edit') }}' );
            $("#check_crud").hide();
            $('#table').hide();
            $('#form').show();
        }

        function clearForm() {
            $('#error-message').hide();
            $('#myForm').parsley().reset();
            id = 0;
            title = url = parent_id = menu_url = parent_url = '';
            icon = 'icon-home';
            $("#single_menu").prop("checked", false);
        }

        function resetForm() {
            $('#id').val(id);
            $('#menu_title').val(title);
            $('#url').val(url);
            selectedIcon(icon)
            parentsMenu(id);

            if(parent_id != "") {
                $("#btn_icon").prop('disabled', true);
            }
        }

        function splitUrl(str) {
            str = str.substring(1);
            str = str.split("/");
            parent_url = (str.length > 1) ? '/' + str[0] : '';
            menu_url = (str.length > 1) ? '/' + str[1] : '/' + str[0];
        }

        $(document).ready(function () {
            initialize();
        });
    </script>
@endpush
