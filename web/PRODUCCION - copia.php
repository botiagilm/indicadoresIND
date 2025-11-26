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
        <div class="container-fluid">
	<input type="hidden" value="" id="indicador_id" />
            <div class="row" id="op_data">
                <div class="col-12 text-center">
                    <h1 id="ln_wc" class="all_label2 extra_all2"></h1>
                </div>
                <div class="col-6 text-center">
                    <h1 class="all_label2 extra_all2">Acumulado</h1>
                    <h1 id="ln_kg_dia" class="all_label2 extra_all2" >1</h1>
                </div>
                <div class="col-6 text-center">
                    <h1  class="all_label2 extra_all2">Kg / Hr</h1>
                    <h1 id="ln_kg_hora" class="all_label2 extra_all2">1</h1>
                </div>                
            </div>
        </div> 
        <div class="footer_transp">
            <div class="row">                
                <div class="col text-right fotter_label">
                    <span id="fecha"></span>
                    <span id="hora"></span>
                </div>                
            </div>
        </div>
    </body>
    <script src="../js/jquery/jquery.min.js"></script>
    <script src="../js/bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/kpi.js"></script>
    <script src="../js/produccion.js"></script>
</html>