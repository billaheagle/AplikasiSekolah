<div class="card">
    <div class="header row">
        <div class="col-lg-6">
            <h2 id="title"><span></span><small></small></h2>
        </div>
        <div class="col-lg-6 text-right">
            @can('user-import')
            <a onclick="importForm()">
                <button class="btn btn-secondary m-b-15 js-sweetalert">
                    <i class="fa fa-upload p-r-5" aria-hidden="true"></i> {{ trans('global.buttons.import') }}
                </button>
            </a>
            @endcan
            @can('user-create')
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
                        <th>{{ trans('cruds.user.fields.name') }}</th>
                        <th>{{ trans('cruds.user.fields.email') }}</th>
                        @can(['user-update', 'user-delete'])
                            <th>{{ trans('global.tooltips.action') }}</th>
                        @endcan
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>{{ trans('global.tooltips.number') }}</th>
                        <th>{{ trans('cruds.user.fields.name') }}</th>
                        <th>{{ trans('cruds.user.fields.email') }}</th>
                        @can(['user-update', 'user-delete'])
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
            for (i = 0; i < d.roles.length; i++) {
                items += '<span class="badge badge-success m-b-5">' + d.roles[i].name + '</span>';
                ((i + 1) % 6 == 0) ? items += '<br>' : '';
            }

            return '<table class="table-bordered table-active" style="width: 100%;">' +
                '<tr>' +
                    '<td width="20%">{{ trans('cruds.user.fields.role') }}</td>' +
                    '<td>' + items + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td width="25%">{{ trans('cruds.user.fields.phone_number') }}</td>' +
                    '<td>' + d.phone_number + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td>{{ trans('cruds.user.fields.alternative_phone_number') }}</td>' +
                    '<td>' + d.alternative_phone_number + '</td>' +
                '</tr>' +
                '<tr>' +
                    '<td>{{ trans('cruds.user.fields.address') }}</td>' +
                    '<td>' + d.address + '</td>' +
                '</tr>' +
            '</table>';
        }

        var data_table = $('#list_table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            order: [[2, 'asc']],
            ajax: "{{ route('user.table') }}",
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
                {data: 'email', name: 'email'},
                @can(['user-update', 'user-delete'])
                    {data: 'action', name: 'action', width: '10%',
                        orderable: false, searchable: false,
                        render : function(data, type, row) {
                            btn = '<a href="#" onclick="resetPasswordForm(' + row['id'] + ', \'' + row['name'] + '\', \'' + row['email']  + '\')"><button class="btn btn-sm btn-outline-info js-sweetalert" title="{{ trans('global.tooltips.resetPassword') }}"><i class="icon-fire" aria-hidden="true"></i></button></a>\n';

                            return btn + data;
                        },
                    }
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

        function importForm() {
            alert('importForm')
        }
    </script>
@endpush
