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
                        <label>{{ trans('cruds.role.fields.name') }}</label>
                        <input type="text" id="name" name="name" class="form-control" required autocomplete="off">
                        <div id="code-errors"></div>
                        <div class="alert alert-danger mt-2 align-center" id="error-message"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('cruds.role.fields.guard') }}</label>
                        <input type="text" id="guard_name" name="guard_name" class="form-control" value="web" readonly required autocomplete="off">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>{{ trans('cruds.role.fields.permission') }} <i class="fa" id="list-permission"></i></label>
                        <br>
                        <select id="permission" name="permission[]" class="custom-select" multiple="multiple" data-parsley-required data-parsley-trigger-after-failure="change" data-parsley-errors-container="#error-multiselect" required></select>
                        <p id="error-multiselect"></p>
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

@push('after-scripts')
    <script>
        $('#myForm').parsley();
        $('.custom-select').select2();

		$('.custom-select').on("select2:select", function (e) {
			var data = e.params.data.id;
			if(data == 'selectAll') {
                $('#permission > option').prop("selected", true);
                $('#permission  option:eq(0)').prop("selected", false);
                $("#permission").trigger("change");
			} else if(data == 'deselectAll') {
                $('#permission > option').prop("selected", false);
                $("#permission").trigger("change");
            }
            selectControl('permission', list_length);
        });

        $('.custom-select').on("select2:unselect", function (e) {
            selectControl('permission', list_length);
        });

        function permissionsRole(permissions) {
            startLoadList('permission', 'list-permission');
            $("#permission").empty();
            $('#permission').append('<option value="selectAll">{{ trans('global.tooltips.select_all') }}</option>');
            $.ajax({
                url: '{{ route('role.permissionsRole') }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    list_length = data.length;
                    data.forEach(function(temp) {
                        $('#permission').append('<option value="' + temp.name + '">' + temp.name + '</option>');
                    });
                    $("#permission").val(permissions);
                    $("#permission").trigger("change");
                    selectControl('permission', list_length);
                    finishLoadList('permission', 'list-permission');
                }
            })
        }

        $("#btn-save").click(function(){
            var temp_name = $('#name').val();
            var temp_guard_name = $('#guard_name').val();
            var temp_permission = $('#permission').val();

            if($("#myForm").valid()) {
                startLoading('btn-save');
                var url = (id != 0) ? '{{ route('role.update', ':id') }}'.replace(':id', id) : '{{ route('role.store') }}';
                var method = (id != 0) ? 'PUT' : 'POST';
                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": method,
                        'name': temp_name,
                        'guard_name': temp_guard_name,
                        'permission': temp_permission,
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
                });
            }
        });
    </script>
@endpush
