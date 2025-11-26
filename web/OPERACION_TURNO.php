<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
?>
<html lang="en">

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
    <div class="container-fluid h-100">
        <input type="hidden" value="" id="indicador_id" />
        <div class="row">
            <div class="col-8 text-center" id="LineaWC">
                <h2 id="ln_wc2" class="all_label extra1">PRODUCCION</h2>
            </div>
                <div class="col text-right fotter_label">
                    <span id="fecha"></span>
                    <span id="hora"></span>
                </div>
        </div>
        <div class="row" id="op_data">
            <div class="table-responsive">
                <table class="" width="100%">
                    <thead id="tbl_head">
                        <tr class="table_tr">
                            <th class="all_label extra2">Puesto</th>
                            <th class="all_label extra2">Dia</th>
                            <th class="all_label extra2">Noche</th>
                            <th class="all_label extra2">Acum Dia</th>
                            <th class="all_label extra2">Kg / Hr</th>
                            <th class="all_label extra2">Acum Mes</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery/jquery.min.js"></script>
<script src="../js/bootstrap/js/bootstrap.min.js"></script>
<script src="../js/operacion_turno.js"></script>
<script src="../js/kpi.js"></script>

</html>