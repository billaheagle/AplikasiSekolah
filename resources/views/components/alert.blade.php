<script>
    toastr.options.timeOut = "2500";
    toastr.options.closeButton = true;
    toastr.options.positionClass = "toast-top-right";

    @if (session('alert'))
    	@if(session('alert')->type === 'success')
    		toastr.success('{{ session('alert')->message }}');
    	@elseif(session('alert')->type === 'error')
    		toastr.error('{{ session('alert')->message }}');
    	@elseif(session('alert')->type === 'warning')
    		toastr.warning('{{ session('alert')->message }}');
    	@elseif(session('alert')->type === 'info')
    		toastr.info('{{ session('alert')->message }}');
    	@endif
    @endif

    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        var table_id = '#' + document.getElementsByTagName("Table")[0].getAttribute('id');
        swal({
            title: "{{ trans('global.messages.areYouSure') }}",
            text: "{{ trans('global.messages.notBeAbleRecoverData') }}",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: "{{ trans('global.buttons.confirmYes') }}",
            cancelButtonText: "{{ trans('global.buttons.confirmNo') }}",
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            closeOnCancel: true
            },
            function() {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": 'DELETE'
                    },
                    success: function (data) {
                        var alertMsg = "{{ trans('global.messages.deleteSuccessfully') }}";
                        swal("Selesai !", alertMsg, "success");
                        $(table_id).DataTable().row($(this).parents('tr')).remove().draw();
                    },
                    error: function () {
                        var alertMsg = "{{ trans('global.messages.deleteUnsuccessfull') }}";
                        swal("Kesalahan !", alertMsg, "error");
                    }
                });
        });
    });
</script>
