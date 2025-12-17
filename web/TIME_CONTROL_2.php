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
    <link href="../css/oswald.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Oswald', sans-serif;
            background-color: #0f172a;
            /* Slate 900 */
            color: white;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            gap: 1rem;
        }

        /* --- ESTILOS DE TARJETAS --- */
        /* GRIS: Parado */
        .card-gris {
            background: linear-gradient(145deg, #393a3aff, #2c2c2cff);
            border: 3px solid #ffffffff;
            box-shadow: 0 10px 25px -5px rgba(58, 58, 58, 0.4);
        }

        /* VERDE: Producción Óptima */
        .card-verde {
            background: linear-gradient(145deg, #059669, #047857);
            border: 3px solid #34d399;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
        }

        /* ROJO: Alerta (Animación de Respiración) */
        @keyframes breatheRed {
            0% {
                background-color: #991b1b;
                border-color: #f87171;
                box-shadow: 0 0 15px rgba(220, 38, 38, 0.5);
            }

            50% {
                background-color: #7f1d1d;
                border-color: #ef4444;
                box-shadow: 0 0 30px rgba(220, 38, 38, 0.8);
            }

            100% {
                background-color: #991b1b;
                border-color: #f87171;
                box-shadow: 0 0 15px rgba(220, 38, 38, 0.5);
            }
        }

        .card-rojo {
            animation: breatheRed 2.5s infinite ease-in-out;
        }

        /* Tipografía Digital */
        .font-mono {
            font-family: 'Roboto Mono', monospace;
        }

        /* Utilería para fondo de texto */
        .bg-text-shadow {
            text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<!-- HEADER -->
<header class="flex justify-between items-center bg-slate-800/80 backdrop-blur px-6 py-3 rounded-xl border border-slate-700 h-[10%] shadow-lg">
    <input type="hidden" id="indicador_id" value="" />
    <div class="flex items-center gap-4">
        <div class="bg-blue-600 px-4 py-2 rounded text-xl font-bold shadow-lg shadow-blue-900/50"><span id="lbl_area"></span></div>
        <div>
            <h1 class="text-2xl uppercase tracking-widest text-slate-200 leading-none">Time Control</h1>
        </div>
    </div>
    <div class="text-right flex flex-col items-end">
        <span id="reloj" class="text-4xl font-mono font-bold text-white leading-none tracking-tight">00:00:00</span>
        <span id="fecha" class="text-slate-400 uppercase text-sm font-medium tracking-widest mt-1">--/--/----</span>
    </div>
</header>

<!-- GRID DE LÍNEAS -->
<div id="grid-lineas" class="grid grid-cols-4 gap-4 h-[90%] w-full">

    <!-- El contenido se genera con JS -->
</div>

<script>
    // --- RELOJ INDEPENDIENTE ---
    function actualizarReloj() {
        const now = new Date();
        document.getElementById('reloj').innerText = now.toLocaleTimeString('es-MX', {
            hour12: false
        });
        document.getElementById('fecha').innerText = now.toLocaleDateString('es-MX', {
            weekday: 'short',
            day: 'numeric',
            month: 'short'
        }).toUpperCase();
    }

    // Iniciar el reloj al cargar la página y actualizarlo cada segundo
    document.addEventListener('DOMContentLoaded', () => {
        actualizarReloj(); // Primera ejecución inmediata
        setInterval(actualizarReloj, 1000); // Actualizar cada segundo
    });


    // --- 1. DATOS DUMMY (SIMULACIÓN DE BASE DE DATOS) ---
    // Estos son los datos base que usara el sistema

    const formatNum = new Intl.NumberFormat('es-MX');
    let timerActualizacion = null;
    // --- 2. SIMULACIÓN DE API ---
    // Esta función imita el fetch('api.php')
    function exec_getdata() {

        let id = document.getElementById("indicador_id").value;
        let formData = new FormData();
        formData.append("METODO", "get_ind");
        formData.append("INDICADOR", "TIME_CONTROL");
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
                //setInterval(updateDashboard, time, params,url,response.indicador);
                updateDashboard(params, url, response.indicador, time);
            })
            .catch(err => console.error("Error:", err));
    }

    // --- 3. ACTUALIZACIÓN DEL DASHBOARD ---
    function updateDashboard(params, transaction, indicador, time) {
        // limpiar timeout previo
        if (timerActualizacion) {
            clearTimeout(timerActualizacion);
        }
        // A. Obtener Datos
        let formData = new FormData();
        formData.append("METODO", "get_mii_query");
        formData.append("PARAMS", params);
        formData.append("TRANSACTION", transaction);
        formData.append("INDICADOR", indicador);
        // B. Obtener y Renderizar Datos
        const container = document.getElementById('grid-lineas');
        fetch('../api/functions.php', {
                method: "POST",
                body: formData,
                timeout: 10000 // 10 segundos
            })
            .then((response) => response.json())
            .then((response) => {
                container.innerHTML = '';
                const data = response.data;
                data.forEach(line => {
                    let
                        serie = line.META,
                        tiempo = line.NOTIFICACION,
                        workc = line.WORK_CENTER;
                    // Determinar estilos según estado
                    const isVerde = estado === 'GREEN';
                    const cssClass = isVerde ? 'card-verde' : estado === 'BLACK' ? 'card-gris' : 'card-rojo';
                    const textColor = 'text-white';
                    const barBg = 'bg-black/30';
                    const barFill = 'bg-white shadow-[0_0_10px_rgba(255,255,255,0.8)]';


                    // Plantilla HTML de la tarjeta
                    const cardHTML = `
                    <div class="${cssClass} rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden transition-all duration-300 group hover:scale-[1.02]">
                        
                        <!-- Cabecera -->
                        <div class="flex justify-between items-start z-10">
                            <h2 class="text-2xl font-bold uppercase tracking-wider bg-text-shadow opacity-90">${workc}</h2>
                        </div>

                        <!-- Cuerpo Principal (Número) -->
                        <div class="flex-1 flex flex-col justify-center items-center z-10 my-2">
                            <div class="text-6xl lg:text-7xl font-mono font-bold leading-none drop-shadow-xl tracking-tighter">
                                ${formatNum.format(acum_dia)}
                            </div>
                            <div class="text-sm lg:text-base font-bold uppercase tracking-[0.3em] opacity-80 mt-2 border-t border-white/20 pt-1 w-1/2 text-center">
                                Kilogramos
                            </div>

                            <div class="text-6xl lg:text-4xl font-mono font-bold leading-none drop-shadow-xl tracking-tighter">
                                Meta: ${formatNum.format(meta_dia)}
                            </div>
                        </div>

                        <!-- Pie (Barra de Progreso) 
                        <div class="z-10">
                            <div class="flex justify-between text-xs font-bold mb-1 uppercase opacity-90">
                                <span>Progreso</span>
                                <span>Meta: ${formatNum.format(meta_dia)}</span>
                            </div>
                            <div class="w-full ${barBg} h-3 rounded-full overflow-hidden border border-white/10 backdrop-blur-sm">
                                <div class="h-full ${barFill} transition-all duration-700 ease-out" style="width: ${porcentaje}%"></div>
                            </div>
                        </div>-->
                        
                        <!-- Decoración Brillante -->
                        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-white/10 to-transparent opacity-50 pointer-events-none"></div>
                    </div>
                `;

                    container.insertAdjacentHTML('beforeend', cardHTML);
                });

                timerActualizacion = setTimeout(updateDashboard, time, params, transaction, indicador, time);
            })
            .catch(err => {
                console.error("Error:", err)
                let time_new = time * 1.5;
                timerActualizacion = setTimeout(updateDashboard, time_new, params, transaction, indicador, time);
            });
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

</html>