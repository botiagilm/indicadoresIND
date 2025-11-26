<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
?>
<html lang="en" >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Expires" content="0">
	    <meta http-equiv="Last-Modified" content="0">
	    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	    <meta http-equiv="Pragma" content="no-cache">
        <link rel="stylesheet" href="../adminlte/dist/css/adminlte.css">
        <link rel="stylesheet" href="../css/all_kpi.css">
    </head>
    <body class="general_ligth h-100 d-flex align-items-center justify-content-center">
<input type="hidden" value="" id="indicador_id" />
	<img src="../LPA/cumple.jpg" alt="Cumpleanos" width="100%" height="100%">
    </body>
    <script src="../js/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/lpa.js"></script>
    <script src="../js/kpi.js"></script>
</html>