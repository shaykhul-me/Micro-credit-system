<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width initial-scale=1.0">
  <title>NBM | Dashboard</title>
  <!-- GLOBAL MAINLY STYLES-->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
  <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.bootstrap4.min.css">
  <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.9/css/fixedHeader.dataTables.min.css">

  <!-- Font Style Sheet -->
  <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/themify-icons.css" rel="stylesheet" />
  <!-- PLUGINS STYLES-->
  <link href="assets/css/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
  <!-- THEME STYLES-->
  <link href="assets/css/main.min.css" rel="stylesheet" />
  <!-- Jq UI STYLES-->
  <link href="assets/css/jquery-ui.min.css" rel="stylesheet" />
  <!-- Croppie STYLES-->
  <link href="assets/css/cropper.min.css" rel="stylesheet" />
  <!-- Custom CSS-->
  <link href="assets/css/custom.css" rel="stylesheet" />
  <!-- PAGE LEVEL STYLES-->
  <link href="assets/css/pages/auth-light.css" rel="stylesheet" />
  <script src="assets/js/jquery.min.js" type="text/javascript"></script>
  <script src="assets/js/jquery-ui.min.js" type="text/javascript"></script>
  <script src="assets/js/cropper.min.js" type="text/javascript"></script>
  <!-- <script src="assets/js/cropper.common.js" type="text/javascript"></script> -->
  <!-- <script src="assets/js/cropper.esm.js" type="text/javascript"></script> -->
  <script src="assets/js/jquery-cropper.min.js" type="text/javascript"></script>
  <!-- DataTable link -->
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.bootstrap4.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <!-- <script type="text/javascript" language="javascript" src="assets/js/pdfmake.min.js"></script> -->
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <!-- <script type="text/javascript" language="javascript" src="assets/js/vfs_fonts.js"></script> -->
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
  <!-- <script type="text/javascript" language="javascript" src="assets/js/buttons.print.js"></script> -->
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.colVis.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/plug-ins/1.10.24/sorting/natural.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

  <script src="assets/js/custom.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custome Style -->
  <style>
    .dataTables_length {
      display: inline-block;
    }

    .dt-buttons {
      display: inline-block;
      margin-left: 5px;
    }

    .dataTables_filter {
      float: right;
    }

    .dataTables_info {
      display: inline-block;
    }
    .page-sidebar ul li a {
    padding: 7px 15px;
  }

    @media print {
      .noPrint {
        display: none !important;
      }
    }

    @media (min-width: 768px) {
      .collapse.dont-collapse-sm {
        display: block;
        height: auto !important;
        visibility: visible;
      }
    }
  </style>
</head>

<body class="fixed-navbar fixed-layout">
  <div class="page-wrapper">