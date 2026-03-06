<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>MUYHUMANO</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
<meta name="description" content="Examples of just how powerful ArchitectUI really is!">
<!-- Disable tap highlight on IE -->
<meta name="msapplication-tap-highlight" content="no">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('vendors/@fortawesome/fontawesome-free/css/all.min.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('vendors/@fortawesome/fontawesome-free/css/all.min.css') }}"> --}}
{{-- <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> --}}
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
