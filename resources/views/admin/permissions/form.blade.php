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
                        <label>{{ trans('cruds.permission.fields.name') }}</label>
                        <input type="text" id="name" name="name" class="form-control" required autocomplete="off">
                        <div id="code-errors"></div>
                        <div class="alert alert-danger mt-2 align-center" id="error-message"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('cruds.permission.fields.guard') }}</label>
                        <input type="text" id="guard_name" name="guard_name" class="form-control" value="web" readonly required autocomplete="off">
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

        $("#btn-save").click(function() {
            var temp_name = $('#name').val();
            var temp_guard_name = $('#guard_name').val();

            if($("#myForm").valid()) {
                startLoading('btn-save');
                var url = (id != 0) ? '{{ route('permission.update', ':id') }}'.replace(':id', id) : '{{ route('permission.store') }}';
                var method = (id != 0) ? 'PUT' : 'POST';
                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": method,
                        'name': temp_name,
                        'guard_name': temp_guard_name,
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
