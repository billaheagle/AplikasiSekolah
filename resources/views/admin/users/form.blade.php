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
                        <label>{{ trans('cruds.user.fields.email') }}</label>
                        <input type="text" id="email" name="email" class="form-control" data-parsley-required autocomplete="off" required>
                        <div id="code-errors"></div>
                        <div class="alert alert-danger mt-2 align-center" id="error-message"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ trans('cruds.user.fields.name') }}</label>
                        <input type="text" id="name" name="name" class="form-control" data-parsley-required autocomplete="off" required>
                    </div>
                </div>
                <div class="col-md-6 password">
                    <div class="form-group">
                        <label>{{ trans('cruds.user.fields.password') }}</label>
                        <input type="password" class="form-control" id="password" name="password" data-parsley-equalto="#password-confirmation" data-parsley-required required>
                    </div>
                </div>
                <div class="col-md-6 password">
                    <div class="form-group">
                        <label>{{ trans('cruds.user.fields.password_confirmation') }}</label>
                        <input type="password" class="form-control" id="password-confirmation" name="password_confirmation" data-parsley-equalto="#password" data-parsley-required required>
                    </div>
                </div>
                <div class="col-md-6 detail-user">
                    <div class="form-group">
                        <label>{{ trans('cruds.user.fields.phone_number') }}</label>
                        <input type="text" id="phone-number" name="phone_number" class="form-control" autocomplete="off" required>
                    </div>
                </div>
                <div class="col-md-6 detail-user">
                    <div class="form-group">
                        <label>{{ trans('cruds.user.fields.alternative_phone_number') }}</label>
                        <input type="text" id="alternative-phone-number" name="alternative_phone_number" class="form-control" autocomplete="off" required>
                    </div>
                </div>
                <div class="col-md-12 detail-user">
                    <div class="form-group">
                        <label>{{ trans('cruds.user.fields.address') }}</label>
                        <textarea id="address" name="address" class="form-control" rows="3" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="col-md-12 detail-user">
                    <div class="form-group">
                        <label>{{ trans('cruds.user.fields.role') }} <i class="fa" id="list-role"></i></label>
                        <br>
                        <select id="role" name="role[]" class="custom-select" multiple="multiple" data-parsley-required data-parsley-trigger-after-failure="change" data-parsley-errors-container="#error-multiselect" required></select>
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
                $('#role > option').prop("selected", true);
                $('#role option:eq(0)').prop("selected", false);
                $("#role").trigger("change");
            } else if(data == 'deselectAll') {
                $('#role > option').prop("selected", false);
                $("#role").trigger("change");
            }
            selectControl('role', list_length);
        });

        $('.custom-select').on("select2:unselect", function (e) {
            selectControl('role', list_length);
        });

        function rolesUser(roles) {
            startLoadList('role', 'list-role');
            $("#role").empty();
            $('#role').append('<option value="selectAll">{{ trans('global.tooltips.select_all') }}</option>');
            $.ajax({
                url: '{{ route('user.rolesUser') }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    list_length = data.length;
                    data.forEach(function(temp) {
                        $('#role').append('<option value="' + temp.name + '">' + temp.name + '</option>');
                    });
                    $("#role").val(roles);
                    $("#role").trigger("change");
                    selectControl('role', list_length);
                    finishLoadList('role', 'list-role');
                }
            })
        }

        function emailIsValid (email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
        }

        $("#btn-save").click(function(){
            var temp_email = $('#email').val();
            var temp_name = $('#name').val();
            var temp_password = $('#password').val();
            var temp_phone_number = $('#phone-number').val();
            var temp_alternative_phone_number = $('#alternative-phone-number').val();
            var temp_address = $('#address').val();
            var temp_role = $('#role').val();

            if(id != 0) {
                if($("#myForm").valid() && (temp_password != '' && temp_password != null)) {
                    startLoading('btn-save');
                    $.ajax({
                        url: '{{ route('user.updatePassword', ':id') }}'.replace(':id', id),
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "PUT",
                            'password': temp_password,
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
                } else if($("#myForm").valid()) {
                    startLoading('btn-save');
                    $.ajax({
                        url: '{{ route('user.update', ':id') }}'.replace(':id', id),
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "PUT",
                            'email': temp_email,
                            'name': temp_name,
                            'phone_number': temp_phone_number,
                            'alternative_phone_number': temp_alternative_phone_number,
                            'address': temp_address,
                            'role': temp_role,
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
            } else {
                if($("#myForm").valid()) {
                    startLoading('btn-save');
                    $.ajax({
                        url: '{{ route('user.store') }}',
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "POST",
                            'email': temp_email,
                            'name': temp_name,
                            'password': temp_password,
                            'phone_number': temp_phone_number,
                            'alternative_phone_number': temp_alternative_phone_number,
                            'address': temp_address,
                            'role': temp_role,
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
            }
        });

        $('#email').on('keyup', function () {
            if(id == '' || id == null) {
                var email = $("#email").val();
                var url = '{{ route('user.checkEmail', ':email') }}'.replace(':email', email);
                if (email.length > 0 && emailIsValid(email)) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": 'POST'
                        },
                        success: function(status) {
                            if(status)
                                $('#btn-save').attr( "disabled", false );
                            else {
                                $('#btn-save').attr( "disabled", true );
                            }
                        }
                    });
                } else {
                    $('#btn-save').attr( "disabled", true );
                }
            }
        });
    </script>
@endpush
