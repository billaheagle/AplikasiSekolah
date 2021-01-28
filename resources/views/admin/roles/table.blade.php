<div class="card">
    <div class="header row">
        <div class="col-lg-6">
            <h2 id="title"><span></span><small></small></h2>
        </div>
        <div class="col-lg-6 text-right">
            @can('role-create')
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
                        <th></th>
                        <th>{{ trans('global.tooltips.number') }}</th>
                        <th>{{ trans('cruds.role.fields.name') }}</th>
                        <th>{{ trans('cruds.role.fields.guard') }}</th>
                        @can(['role-update', 'role-delete'])
                            <th>{{ trans('global.tooltips.action') }}</th>
                        @endcan
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>{{ trans('global.tooltips.number') }}</th>
                        <th>{{ trans('cruds.role.fields.name') }}</th>
                        <th>{{ trans('cruds.role.fields.guard') }}</th>
                        @can(['role-update', 'role-delete'])
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
        function format(d) {
            var items = '';
            data = d.permissions;
            for (i = 0; i < data.length; i++) {
                items += '<span class="badge badge-success m-b-5">' + data[i].name + '</span>';
                ((i + 1) % 6 == 0) ? items += '<br>' : '';
            }
            return '<table class="table-bordered table-active" style="width: 100%;">' +
                '<tr>' +
                    '<td width="20%">{{ trans('cruds.role.fields.permission') }}</td>' +
                    '<td>' + items + '</td>' +
                '</tr>' +
            '</table>';
        }

        var data_table = $('#list_table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            order: [[2, 'asc']],
            ajax: "{{ route('role.table') }}",
            columns: [
                {
                    className: 'details-control',
                    orderable: false,
                    searchable: false,
                    data: null,
                    defaultContent: '',
                    width: '5%'
                },
                {data: 'id', name: 'id', width: '5%', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'guard_name', name: 'guard_name'},
                @can(['role-update', 'role-delete'])
                    {data: 'action', name: 'action', width: '10%', orderable: false, searchable: false}
                @endcan
            ],
            "fnCreatedRow": function (row, data, index) {
                $('td', row).eq(1).html(index + 1);
            }
        });

        $('#list_table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = data_table.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });
    </script>
@endpush
