<div class="card">
    <div class="header row">
        <div class="col-lg-6">
            <h2 id="title"><span></span><small></small></h2>
        </div>
        <div class="col-lg-6 text-right">
            @can('permission-create')
            <a onclick="createForm()">
                <button class="btn btn-primary m-b-15 js-sweetalert">
                    <i class="fa fa-plus-square p-r-5" aria-hidden="true"></i> {{ trans('global.buttons.add') }}
                </button>
            </a>
            @endcan
        </div>
    </div>
    <div class="body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover js-basic-example dataTable table-custom" style="width: 100%;" id="list_table">
                <thead>
                    <tr>
                        <th>{{ trans('global.tooltips.number') }}</th>
                        <th>{{ trans('cruds.permission.fields.name') }}</th>
                        <th>{{ trans('cruds.permission.fields.guard') }}</th>
                        @can(['permission-update', 'permission-delete'])
                            <th>{{ trans('global.tooltips.action') }}</th>
                        @endcan
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ trans('global.tooltips.number') }}</th>
                        <th>{{ trans('cruds.permission.fields.name') }}</th>
                        <th>{{ trans('cruds.permission.fields.guard') }}</th>
                        @can(['permission-update', 'permission-delete'])
                            <th>{{ trans('global.tooltips.action') }}</th>
                        @endcan
                    </tr>
                </tfoot>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
        var data_table = $('#list_table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            order: [[1, 'asc']],
            ajax: "{{ route('permission.table') }}",
            columns: [
                {data: 'id', name: 'id', width: '5%', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'guard_name', name: 'guard_name'},
                @can(['permission-update', 'permission-delete'])
                    {data: 'action', name: 'action', width: '10%', orderable: false, searchable: false}
                @endcan
            ],
            "fnCreatedRow": function (row, data, index) {
                $('td', row).eq(0).html(index + 1);
            }
        });
    </script>
@endpush
