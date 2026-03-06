<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="description" content="Examples of just how powerful ArchitectUI really is!">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('vendors/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ionicons-npm/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/linearicons-master/dist/web-font/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/pixeden-stroke-7-icon-master/pe-icon-7-stroke/dist/pe-icon-7-stroke.css') }}">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-cntk.css') }}" rel="stylesheet">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    @vite('resources/css/app.css')
    @cloudinaryJS

    <title>MUYHUMANO</title>
</head>
<body>
    <div id="app"></div>

    {{-- scripts comunes --}}
    {{-- @include('back.partials.back-scripts') --}}

    {{-- scripts específicos --}}
    {{-- @yield('scripts') --}}

    <!-- plugin dependencies -->
    <script type="text/javascript" src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/moment/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/metismenu/dist/metisMenu.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/bootstrap4-toggle/js/bootstrap4-toggle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/jquery-circle-progress/dist/circle-progress.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/toastr/build/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/jquery.fancytree/dist/jquery.fancytree-all-deps.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/@atomaras/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendors/chart.js/dist/Chart.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- custome.js -->
    <script type="text/javascript" src="{{ asset('js/circle-progress.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/demo.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/scrollbar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/toastr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/treeview.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/form-components/toggle-switch.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/form-components/input-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

    @vite('resources/js/app.js')
</body>
</html>
