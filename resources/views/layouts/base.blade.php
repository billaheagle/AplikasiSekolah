<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> <!-- Favicon-->
        <title>@yield('title') - {{ config('app.name') }}</title>
        <meta name="description" content="@yield('meta_description', config('app.name'))">
        <meta name="author" content="@yield('meta_author', config('app.name'))">
        @yield('meta')

        {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
        @stack('before-styles')

        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/jvectormap/jquery-jvectormap-2.0.3.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('assets/vendor/morrisjs/morris.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert/sweetalert.css') }}"/>

        @if (in_array(Request::segment(1), ['menu', 'user-management'])))
            <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}"/>
            <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}"/>
            <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}"/>
            <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}"/>
        @endif

        @if (in_array(Request::segment(1), ['user-management']))
            <link rel="stylesheet" href="{{ asset('assets/vendor/select2/dist/css/select2.min.css') }}"/>
        @endif

        <!-- Custom Css -->
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/icdash.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">

        @stack('after-styles')

        @if (trim($__env->yieldContent('page-styles')))
            @yield('page-styles')
        @endif

    </head>

    <?php
        $setting = !empty($_GET['theme']) ? $_GET['theme'] : '';
        $theme = "theme-cyan";
        $menu = "";
        if ($setting == 'p') {
            $theme = "theme-purple";
        } else if ($setting == 'b') {
            $theme = "theme-blue";
        } else if ($setting == 'g') {
            $theme = "theme-green";
        } else if ($setting == 'o') {
            $theme = "theme-orange";
        } else if ($setting == 'bl') {
            $theme = "theme-blush";
        } else {
             $theme = "theme-cyan";
        }

    ?>

    <body class="<?= $theme ?>">

        <!-- Page Loader -->
        <div class="page-loader-wrapper">
            <div class="loader">
                <div class="m-t-30"><img src="{{url('/')}}/assets/img/logo-icon.svg" width="48" height="48" alt="Lucid"></div>
                <p>Please wait...</p>
            </div>
        </div>

        <div id="wrapper">

            @include('components.navbar')
            @include('components.sidebar')

            <div id="main-content">
                <div class="container-fluid">
                    <div class="block-header">
                        <div class="row">
                            <div class="col-lg-5 col-md-8 col-sm-12">
                                <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> @yield('title')</h2>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="icon-home"></i></a></li>
                                    @if (trim($__env->yieldContent('parentPageTitle')))
                                       <li class="breadcrumb-item">@yield('parentPageTitle')</li>
                                    @endif
                                    @if (trim($__env->yieldContent('breadcumb')))
                                        <li class="breadcrumb-item active">@yield('breadcumb')</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    @yield('content')

                </div>
            </div>

        </div>

        <!-- Scripts -->
        @stack('before-scripts')

        <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

        <script src="{{ asset('assets/bundles/morrisscripts.bundle.js') }}"></script><!-- Morris Plugin Js -->
        <script src="{{ asset('assets/bundles/jvectormap.bundle.js') }}"></script> <!-- JVectorMap Plugin Js -->
        <script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script>
        <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
        <script src="{{ asset('assets/vendor/sweetalert/sweetalert.min.js') }}"></script>

        @if (in_array(Request::segment(1), ['dashboard', 'grafik'])))
            <script src="https://code.highcharts.com/highcharts.js"></script>
            <script src="https://code.highcharts.com/modules/drilldown.js"></script>
        @endif

        @if (in_array(Request::segment(1), ['menu', 'user-management'])))
            <script src="{{ asset('assets/bundles/datatablescripts.bundle.js') }}"></script>
            <script src="{{ asset('assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/jquery-datatable/buttons/buttons.print.min.js') }}"></script>
            <script src="{{ asset('assets/vendor/jquery-validation/jquery.validate.js') }}"></script>
            <script src="{{ asset('assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
            <script src="{{ asset('assets/vendor/parsleyjs/js/parsley.min.js') }}"></script>
            <script>
                var validator = $('#myForm').validate({
                    ignore: ":hidden",
                    errorPlacement: function(error, element) {
                        element.closest("div.form-group").addClass('text-danger');
                    },
                    success: function(error, element) {
                        $(element).closest("div.form-group").removeClass('text-danger');
                    },
                    rules : {
                        password_confirmation : {
                            equalTo : "#password"
                        }
                    }
                });

                function startLoading(tag) {
                    $('#' + tag).attr("disabled", true);
                    $('#' + tag + ' i').removeClass("fa-save").addClass("fa-spinner fa-spin");
                    $('#' + tag + ' span').text( '{{ trans('global.buttons.loading') }}' );
                }

                function finishLoading(tag, btn_text) {
                    $('#' + tag).attr("disabled", false);
                    $('#' + tag + ' i').removeClass("fa-spinner fa-spin").addClass("fa-save");
                    $('#' + tag + ' span').text(btn_text);
                }

                function startLoadList(tag, tag2) {
                    $('#' + tag).attr("disabled", true);
                    $('#' + tag2).addClass("fa-spinner fa-spin")
                }

                function finishLoadList(tag, tag2) {
                    $('#' + tag).attr("disabled", false);
                    $('#' + tag2).removeClass("fa-spinner fa-spin")
                }

                function selectControl(tag, len) {
                    if($('#' + tag + ' :selected').length < len) {
                        $('#' + tag + ' option:eq(0)').text('{{ trans('global.tooltips.select_all') }}').val("selectAll");
                    } else {
                        $('#' + tag + ' option:eq(0)').text('{{ trans('global.tooltips.deselect_all') }}').val("deselectAll");
                    }
                    $('#' + tag).select2().trigger('change'); //MASIH ERROR UNCAUGHT DISINI
                }
            </script>
        @endif

        @if (in_array(Request::segment(1), ['user-management']))
            <script src="{{ asset('assets/vendor/select2/dist/js/select2.min.js') }}"></script>
        @endif

        <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

        @stack('after-scripts')

        @if (trim($__env->yieldContent('page-script')))
            <script>
                @yield('page-script')
            </script>
        @endif
    </body>
</html>
