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
    <script src="../js/tailwind.js"></script>
    <!--<script src="../js/kpi.js"></script>-->
    <script src="../js/chart.js"></script>
    <link href="../css/oswald.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Oswald', sans-serif;
            background-color: #0f172a;
            color: white;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }

        .font-mono {
            font-family: 'Roboto Mono', monospace;
        }

        /* Animaciones */
        @keyframes pulseRed {
            0% {
                background-color: #dc2626;
            }

            50% {
                background-color: #991b1b;
            }

            100% {
                background-color: #dc2626;
            }
        }

        .estado-rojo {
            animation: pulseRed 2s infinite;
            border: 4px solid #fca5a5;
            color: white;
        }

        .estado-verde {
            background-color: #16a34a;
            border: 4px solid #86efac;
            color: white;
        }
    </style>
</head>

<body class="p-4 h-full flex flex-col gap-4">
    <input type="hidden" id="indicador_id" value="" />
    <!-- HEADER -->
    <header class="h-[10%] bg-slate-800 rounded-xl flex justify-between items-center px-6 border border-slate-700 shadow-lg">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="text-3xl uppercase tracking-widest">Puesto - <span id="lbl-puesto">---<span></h1>
                <p class="text-slate-400 text-sm font-sans">Monitor de Produccion</p>
            </div>
        </div>
        <div class="text-right">
            <div id="reloj" class="text-5xl font-mono font-bold leading-none">00:00</div>
            <div id="fecha" class="text-slate-400 uppercase text-sm tracking-wider">--/--/----</div>
        </div>
    </header>

    <!-- GRID PRINCIPAL -->
    <div class="h-[90%] grid grid-cols-12 grid-rows-6 gap-4">

        <!-- 1. SEMÁFORO PRODUCCIÓN (Col 1-7, Row 1-4) -->
        <div id="box-produccion" class="col-span-7 row-span-6 rounded-2xl shadow-2xl flex flex-col justify-center items-center relative transition-all duration-500">
            <div class="absolute top-4 left-6 text-xl uppercase tracking-[0.2em] opacity-80">Produccion</div>
            <!--<div class="absolute opacity-10 text-[15rem] font-black select-none pointer-events-none">PROD</div>-->

            <div class="text-[11rem] font-mono font-black leading-none z-10" id="val-prod">0</div>
            <div class="text-5xl font-bold z-10 mt-[-10px]">KILOGRAMOS</div>

            <div class="w-3/4 mt-8 z-10">
                <div class="flex justify-between text-xl font-bold mb-1 opacity-90">
                    <span>Progreso</span>
                    <span>Meta: <span id="val-meta">0</span> kg</span>
                </div>
                <div class="w-full h-6 bg-black/30 rounded-full overflow-hidden border border-white/20">
                    <div id="bar-prod" class="h-full bg-white transition-all duration-1000" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- 2. SCRAP TURNO ACTUAL (Col 8-12, Row 1-4) - UNIFICADO -->
        <div class="col-span-5 row-span-6 bg-slate-800 rounded-2xl border-l-8 border-orange-500 p-6 flex flex-col justify-center items-center relative shadow-lg overflow-hidden">
            <!-- Fondo decorativo -->
            <!--<div class="absolute right-[-20px] top-10 text-orange-500/10 text-[12rem] font-black rotate-12 pointer-events-none">SCRAP</div>-->
            <h3 class="text-orange-400 text-[5rem] uppercase tracking-widest mb-4 font-bold relative z-10">Scrap</h3>
            <div class="relative z-10 bg-slate-900/50 p-8 rounded-3xl border border-orange-500/30 w-full text-center backdrop-blur-sm">
                <div class="text-[10rem] font-mono font-bold leading-none text-white" id="val-scrap-turno">0</div>
                <span class="text-orange-200 text-4xl uppercase tracking-wider">Kilogramos</span>
            </div>

            <!--<div class="mt-8 text-center relative z-10">
                <p class="text-slate-400 text-lg uppercase">Material Degradado</p>
                <div class="inline-block bg-orange-900/40 text-orange-300 px-4 py-1 rounded mt-2 border border-orange-700/50">
                    Turno <span id="lbl-turno-scrap">--</span>
                </div>
            </div>-->
        </div>

        <!-- 3. BARRA INFERIOR (Col 1-12, Row 5-6) 
        <div class="col-span-12 row-span-2 bg-slate-900 rounded-2xl border border-slate-700 flex p-4 shadow-xl divide-x divide-slate-700">

             A. Acumulado Producción Mes (30% ancho) 
            <div class="w-[50%] flex flex-col justify-center px-6">
                <h3 class="text-blue-400 uppercase tracking-widest text-lg flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Produccion Mes
                </h3>
                <div class="text-5xl font-mono font-bold text-white mt-1" id="val-mensual-prod">0</div>
                <div class="w-full bg-slate-800 h-2 mt-3 rounded-full overflow-hidden">
                    <div id="bar-mensual-prod" class="h-full bg-blue-500" style="width: 0%"></div>
                </div>
                <div class="text-right text-slate-500 text-xs mt-1">Meta: <span id="meta-mensual-prod">0</span></div>
            </div>

            
             B. Acumulado Scrap Mes (20% ancho) - NUEVO 
            <div class="w-[50%] flex flex-col justify-center px-6 bg-slate-900/50">
                <h3 class="text-orange-400 uppercase tracking-widest text-lg flex items-center gap-2">
                    <span class="w-2 h-2 bg-orange-500 rounded-full"></span> Scrap Mes
                </h3>
                <div class="text-5xl font-mono font-bold text-white mt-1" id="val-mensual-scrap">0</div>

                 Indicador visual simple
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex-1 bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="h-full bg-orange-500" style="width: 35%"></div> 
                    </div>
                    <span class="text-xs text-orange-300">Total</span>
                </div>
            </div>
        </div>-->
    </div>

    <script>
        // --- ACTUALIZACIÓN ---
        const formatNum = new Intl.NumberFormat('es-MX');

        function exec_getdata() {
            let id = document.getElementById("indicador_id").value;
            let formData = new FormData();
            formData.append("METODO", "get_ind");
            formData.append("INDICADOR", "PRODUCCION");
            formData.append("IND_ID", id);
            fetch('../api/functions.php', {
                    method: "POST",
                    body: formData
                })
                .then((response) => response.json())
                .then((response) => {
                    let time = response.data[0].duracion * 1000;
                    let params = response.data[0].params;
                    let url = response.data[0].T_URL;
                    setInterval(updateDashboard, time, params, url, response.indicador);
                    updateDashboard(params, url, response.indicador);
                })
                .catch(err => console.error("Error:", err));
        }

        function updateDashboard(params, transaction, indicador) {
            // Reloj
            const now = new Date();
            document.getElementById('reloj').innerText = now.toLocaleTimeString('es-MX', {
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('fecha').innerText = now.toLocaleDateString('es-MX', {
                weekday: 'short',
                day: 'numeric',
                month: 'short'
            });

            let formData = new FormData();
            formData.append("METODO", "get_mii_query");
            formData.append("PARAMS", params);
            formData.append("TRANSACTION", transaction);
            formData.append("INDICADOR", indicador);

            fetch('../api/functions.php', {
                    method: "POST",
                    body: formData
                })
                .then((response) => response.json())
                .then((response) => {
                    let meta_dia = response.data[0].META,
                        acum_dia = response.data[0].NOTIFICACION,
                        scrap = response.data[0].SCRAP,
                        workc = response.data[0].WORK_CENTER;
                    let semaforo_prod = valida_meta(acum_dia, meta_dia);
                    let porcentaje = (acum_dia / meta_dia) * 100;
                    // 1. SEMÁFORO / PRODUCCIÓN
                    const box = document.getElementById('box-produccion');
                    if (semaforo_prod === 'RED') {
                        box.className = "col-span-7 row-span-6 rounded-2xl shadow-2xl flex flex-col justify-center items-center relative transition-all duration-500 estado-rojo";
                    } else {
                        box.className = "col-span-7 row-span-6 rounded-2xl shadow-2xl flex flex-col justify-center items-center relative transition-all duration-500 estado-verde";
                    }



                    document.getElementById('lbl-puesto').innerText = workc;
                    document.getElementById('val-prod').innerText = formatNum.format(acum_dia);
                    document.getElementById('val-meta').innerText = formatNum.format(meta_dia);
                    document.getElementById('bar-prod').style.width = Math.min(porcentaje, 100) + '%';

                    // 2. SCRAP (Unificado)
                    document.getElementById('val-scrap-turno').innerText = formatNum.format(scrap);

                    // 3. BARRA INFERIOR (Mensual Prod + Mensual Scrap)
                    // Prod
                    //document.getElementById('val-mensual-prod').innerText = formatNum.format(0);
                    //document.getElementById('bar-mensual-prod').style.width = Math.min(0, 100) + '%';

                    // Scrap Mensual (Nuevo)
                    //document.getElementById('val-mensual-scrap').innerText = formatNum.format(0);

                })
                .catch(err => console.error("Error:", err));
        }


        function valida_meta(valor1, valor2) {
            var color = "";
            if (valor1 == 0) {
                color = "BLACK";
            } else if (parseFloat(valor1) < parseFloat(valor2)) {
                color = "RED";
            } else {
                color = "GREEN";
            }
            return color;
        }
    </script>
</body>
<!--
<body class="general_ligth h-100 d-flex align-items-center justify-content-center">
    <div class="container-fluid">
        <div class="row" id="op_data">
            <div class="col-12 text-center">
                <h1 id="ln_wc" class="all_label2 extra_all2"></h1>
            </div>
            <div class="col-6 text-center">
                <h1 class="all_label2 extra_all2">Acumulado</h1>
                <h1 id="ln_kg_dia" class="all_label2 extra_all2">1</h1>
            </div>
            <div class="col-6 text-center">
                <h1 class="all_label2 extra_all2">Kg / Hr</h1>
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
    -->

</html>