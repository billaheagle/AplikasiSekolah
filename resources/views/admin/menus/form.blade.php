<div class="card">
    <div class="header row">
        <div class="col-lg-6">
            <h2 id="title"><span></span><small></small></h2>
        </div>
        <div class="col-lg-6 text-right">
            <a onclick="initialize()">
                <button class="btn btn-secondary m-b-15 js-sweetalert">
                    <i class="icon-action-undo p-r-5" aria-hidden="true"></i> {{ trans('global.buttons.back') }}
                </button>
            </a>
        </div>
    </div>
    <div class="body">
        <form id="myForm" data-parsley-validate novalidate>
            <div class="row">
                <input type="hidden" id="id">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('cruds.menu.fields.title') }}</label>
                        <input type="text" id="menu_title" name="menu_title" class="form-control" required autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('cruds.menu.fields.url') }}</label>
                        <input type="text" id="url" name="url" class="form-control" required autocomplete="off">
                        <div id="code-errors"></div>
                        <div class="alert alert-danger mt-2 align-center" id="error-message"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('cruds.menu.fields.icon') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="current-icon"><i></i></span>
                            </div>
                            <input type="text" id="icon" value="icon-home" class="form-control" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="btn_icon">{{ trans('cruds.menu.fields.check_icon') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('cruds.menu.fields.parent') }} <i class="fa" id="list-parent"></i></label>
                        <select class="custom-select" id="parent_id"></select>
                    </div>
                </div>
                <div class="col-md-12" id="check_crud">
                    <div class="form-group clearfix"><br>
                        <label class="fancy-checkbox element-left">
                            <input type="checkbox" id="single_menu">
                            <span>{{ trans('cruds.menu.fields.single_menu') }}</span>
                        </label>
                    </div>
                </div>
            </div>
            <br>
            <div class="text-right">
                <button type="button" class="btn btn-danger" onclick="resetForm()"><i class="fa fa-refresh p-r-5"></i> <span>{{ trans('global.buttons.reset') }}</span></button>
                <button type="button" class="btn btn-primary" id="btn-save"><i class="fa fa-save p-r-5"></i> <span>{{ trans('global.buttons.storeEdit') }}</span></button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modal_icon" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="title" id="defaultModalLabel">{{ trans('cruds.menu.fields.change_icon') }}</h6>
            </div>
            <div class="modal-body">
                <div class="row clearfix" id="list_icon"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('global.buttons.close') }}</button>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
        $('#myForm').parsley();

        function parentsMenu(id) {
            startLoadList('parent_id', 'list-parent');
            $("#parent_id").empty();
            $('#parent_id').append('<option value="">{{ trans('global.tooltips.choose') }}</option>');
            $.ajax({
                url: '{{ route('menu.parentsMenu', ':id') }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.forEach(function(temp) {
                        var selected = (temp.id == parent_id) ? 'selected' : '';
                        $('#parent_id').append('<option value="' + temp.id + '" ' + selected + '>' + temp.title + '</option>');
                    });
                    finishLoadList('parent_id', 'list-parent');
                }
            })
        }

        $("#btn-save").click(function() {
            var temp_title = $('#menu_title').val();
            var temp_url = $('#url').val();
            var temp_icon = $('#icon').val();
            var temp_parent_id = $('#parent_id').val();
            var temp_parent_crud = $('#single_menu').is(":checked") ? true : '';

            if($("#myForm").valid()) {
                startLoading('btn-save');
                var urls = (id != 0) ? '{{ route('menu.update', ':id') }}'.replace(':id', id) : '{{ route('menu.store') }}';
                var method = (id != 0) ? 'PUT' : 'POST';
                $.ajax({
                    url: urls,
                    type: method,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": method,
                        'title': temp_title,
                        'url': temp_url,
                        'icon': temp_icon,
                        'parent_id': temp_parent_id,
                        'single_menu': temp_parent_crud,
                    },
                    success: function(data) {
                        finishLoading('btn-save', '{{ trans('global.buttons.storeEdit') }}');
                        if(data.success) {
                            data_table.clear().draw();
                            initialize();
                            toastr.success(data.messages);
                        } else {
                            $('#error-message').text(data.messages).show();
                        }
                    }
                })
            }
        });

        $.getJSON("/assets/icon/object.json", function (data) {
            data.forEach(function(temp) {
                $('#list_icon').append(
                '<div class="col-md-1 text-center">' +
                    '<button class="btn m-b-15 selected-icon" id="' + temp.icon + '" data-selected-icon="' + temp.icon + '">' +
                        '<i class="' + temp.icon + '" aria-hidden="true"></i>' +
                    '</button>' +
                '</div>'
                )
            })
        });

        $("#btn_icon").click(function () {
            $("#modal_icon").modal("show");
        });

        $(document).on('click', '.selected-icon', function (e) {
            e.preventDefault();
            var temp_icon = $(this).data('selected-icon');
            selectedIcon(temp_icon);
            $("#modal_icon").modal("hide");
        });

        $('#parent_id').change(function(){
            if($(this).val() != '') {
                $("#check_crud").hide();
                $("#single_menu").prop('checked', false);
                $("#btn_icon").prop('disabled', true);
                $('#current-icon i').removeClass();
                $('#icon').val('');
            } else {
                $("#check_crud").show();
                $("#btn_icon").prop('disabled', false);
                $('#current-icon i').addClass('icon-home');
                selectedIcon('icon-home');
            }
        })

        function selectedIcon(temp_icon) {
            $('.selected-icon').removeClass("btn-secondary");
            $('#current-icon i').removeClass().addClass(temp_icon);
            $('#icon').val(temp_icon);
            if(temp_icon != "") {
                $('#' + temp_icon).addClass("btn-secondary");
            }
        }

        $('#menu_title').on('keyup', function () {
            menu_url = '/' + $("#menu_title").val();
            menu_url = menu_url.toLowerCase().replace(/ /g,'-');
            $("#url").val(parent_url + menu_url);
        });

        $('#parent_id').on('change', function() {
            if(this.value != '') {
                $.ajax({
                    url: '{{ route('menu.parentUrl', ':id') }}'.replace(':id', this.value),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        parent_url = data.url
                        $("#url").val(parent_url + menu_url);
                    }
                });
            } else {
                parent_url = '';
                $("#url").val(parent_url + menu_url);
            }
        });
    </script>
@endpush
