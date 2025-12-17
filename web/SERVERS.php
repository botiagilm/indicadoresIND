<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Infraestructura</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #1a1a1a;
            font-family: sans-serif;
            color: white;
            height: 100%;
            width: 100%;
            text-align: center;
        }

        h1 {
            padding: 20px 0 0;
        }

        /* Contenedor principal que ocupa todo el ancho */
        .honeycomb {
            padding: 2vh 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Fila de Contenedor (Necesario para el offset) */
        .honeycomb-row {
            display: flex;
            justify-content: center;
        }

        /* 1. Definición del Tamaño (Usando VW para escalar) */
        .hex {
            /* Anchura de la caja que contiene el hexágono */
            width: 10vw;
            /* Altura basada en la geometría 1:1.1547 para este clip-path */
            height: 11.547vw;
            margin: 0 0.5vw;
            /* Espacio horizontal de 0.5vw a cada lado */
            background-color: #444;
            position: relative;
            /* Recorte mágico para forma hexagonal */
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 1.2vw;
        }

        /* 2. EL ARREGLO CLAVE: El Efecto Panal Entrelazado */
        .honeycomb-row:nth-child(even) {
            /* Aplicamos el offset a filas pares (2, 4, 6...) */
            /* Desplazamiento horizontal: Media anchura del hexágono + margen total entre celdas */
            transform: translateX(5.5vw);
        }

        /* 2. EL ARREGLO CLAVE: El Efecto Panal Entrelazado */
        .honeycomb-row:nth-child(n) {
            /* Solapamiento vertical: El valor clave para el encaje perfecto */
            margin-top: -2.5vw;
        }

        /* Estilos de estado */
        .hex.online {
            background-color: #2ecc71;
            box-shadow: 0 0 10px #2ecc71;
            order:3;
        }

        .hex.offline {
            background-color: #e74c3c;
            order:1;
        }

        .hex.checking {
            background-color: #f1c40f;
            animation: pulse 1s infinite;
            order:2;
        }

        .hex h3 {
            margin: 0;
            font-size: 1em;
            font-weight: bold;
        }

        .hex span {
            font-size: 0.8em;
            opacity: 0.8;
        }

        .hex .ping {
            font-size: 0.7em;
            margin-top: 5px;
            font-weight: bold;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <h1>Monitor de Infraestructura</h1>
    <div id="dashboard" class="honeycomb">
    </div>

    <script>
        // --- CONFIGURACIÓN DE TU INFRAESTRUCTURA ---
        const servidores = [
            { name: "Site 3Piso", ip: "10.21.100.84" },
            { name: "Prensa1", ip: "10.21.100.56" },
            { name: "Fundicion", ip: "10.21.100.57" },
            { name: "Pintura", ip: "10.21.100.58" },
            { name: "Control Room2", ip: "10.21.100.85" },
            { name: "Anodizado", ip: "10.21.100.59" },
            { name: "Cuarentena", ip: "10.21.100.71" },
            { name: "Prensa4y5", ip: "10.21.100.70" },
            { name: "Amarres", ip: "10.21.100.68" },
            { name: "Control Room", ip: "10.21.100.60" },
            { name: "Tubos Conduit", ip: "10.21.100.61" },
            { name: "Comedor", ip: "10.21.100.62" },
            { name: "Suministros", ip: "10.21.100.63" },
            { name: "Ventas", ip: "10.21.100.65" },
            { name: "Prensa12y13", ip: "10.21.100.67" },
            { name: "Prensa6y7", ip: "10.21.100.75" },
            { name: "Calidad", ip: "10.21.100.78" },
            { name: "Prensa2y3", ip: "10.21.100.72" },
            { name: "Prensa9y10", ip: "10.21.100.73" },
            { name: "Prensa8", ip: "10.21.100.74" },
            { name: "Site Operaciones", ip: "10.21.100.53" },
            { name: "Direccion", ip: "10.21.100.54" },
            { name: "Caseta", ip: "10.21.100.81" },
            { name: "Site Pintura", ip: "10.21.100.82" },
            { name: "ZEBRA FUND DESP", ip: "10.21.12.15", puerto: "9100" },
            { name: "ZEBRA AM 3", ip: "10.21.12.192", puerto: "9100" },
            { name: "ZEBRA AM 4", ip: "10.21.12.193", puerto: "9100" },
            { name: "ZEBRA EMP PINTURA", ip: "10.21.12.197", puerto: "9100" },
            { name: "ZEBRA EMP ANODIZ", ip: "10.21.12.198", puerto: "9100" },
            { name: "ZEBRA VG", ip: "10.21.12.200", puerto: "9100" },
            { name: "CAMARA PX1", ip: "10.21.12.170", puerto: "23" },
            { name: "CAMARA PX2", ip: "10.21.12.171", puerto: "23" },
            { name: "CAMARA PX3", ip: "10.21.12.172", puerto: "23" },
            { name: "CAMARA PX4", ip: "10.21.12.173", puerto: "23" },
            { name: "CAMARA PX5", ip: "10.21.12.174", puerto: "23" },
            { name: "CAMARA PX7", ip: "10.21.12.176", puerto: "23" },
            { name: "CAMARA PX8", ip: "10.21.12.177", puerto: "23" },
            { name: "CAMARA PX9", ip: "10.21.12.178", puerto: "23" },
            { name: "CAMARA PX10", ip: "10.21.12.182", puerto: "23" },
            { name: "CAMARA PX11", ip: "10.21.12.183", puerto: "23" },
            { name: "CAMARA MB LARGOS", ip: "10.21.12.184", puerto: "23" },
            { name: "MP B DESPUNTES", ip: "10.21.12.180", puerto: "4001" },
            { name: "CAMARA BC CONSUMO", ip: "10.21.12.185", puerto: "23" },
            { name: "ZEBRA PT", ip: "10.21.12.199", puerto: "9100" },
            { name: "CAMARA MB CORTOS", ip: "10.21.12.186", puerto: "23" },
            { name: "ZEBRA CONDUIT", ip: "10.21.12.196", puerto: "9100" },
            { name: "ZEBRA AM 1", ip: "10.21.12.194", puerto: "9100" },
            { name: "ZEBRA FUND CORTE", ip: "10.21.12.191", puerto: "9100" },
            { name: "ZEBRA AM 2", ip: "10.21.12.195", puerto: "9100" },
            { name: "ZEBRA RECEPCION", ip: "10.21.12.14", puerto: "9100" },
            { name: "MP B CORTOS", ip: "10.21.12.181", puerto: "4001" },
            { name: "BASCULA PX1", ip: "10.21.5.208", puerto: "5000" },
            { name: "BASCULA PX2", ip: "10.21.5.209", puerto: "5000" },
            { name: "BASCULA PX3", ip: "10.21.5.210", puerto: "5000" },
            { name: "BASCULA PX4", ip: "10.21.5.211", puerto: "5000" },
            { name: "BASCULA PX5", ip: "10.21.5.212", puerto: "5000" },
            { name: "BASCULA PX6", ip: "10.21.5.213", puerto: "5000" },
            { name: "BASCULA PX7", ip: "10.21.5.214", puerto: "5000" },
            { name: "BASCULA PX8", ip: "10.21.5.215", puerto: "5000" },
            { name: "BASCULA PX9", ip: "10.21.5.216", puerto: "5000" },
            { name: "BASCULA PX10", ip: "10.21.5.217", puerto: "5000" },
            { name: "BASCULA PX11", ip: "10.21.5.218", puerto: "5000" },
            { name: "BASCULA DESPUNTE", ip: "10.21.5.220", puerto: "5000" },
            { name: "BASCULA PRECORTADOS", ip: "10.21.5.219", puerto: "5000" },
            { name: "BASCULA FUNDICION 01", ip: "10.21.5.224", puerto: "5000" },
            { name: "BASCULA ALEANTES", ip: "10.21.5.223", puerto: "5000" },
            { name: "BASCULA CHATARRERA 01", ip: "10.21.5.221", puerto: "5000" },
            { name: "BASCULA CHATARRERA 02", ip: "10.21.5.222", puerto: "5000" },
            { name: "ZEBRA AM 5", ip: "10.21.12.196", puerto: "9100" },
            { name: "ZEBRA CHAROLAS", ip: "10.21.12.190", puerto: "9100" },
            { name: "ZEBRA RECIBO", ip: "10.21.12.202", puerto: "9100" },
            { name: "CAMARA PX6", ip: "10.21.12.175", puerto: "23" },
            { name: "BASCULA PX13", ip: "10.21.5.226", puerto: "5000" },
            { name: "ZEBRA EMP ANODIZ2", ip: "10.21.12.205", puerto: "9100" },
            { name: "BASCULA PX12", ip: "10.21.5.255", puerto: "5000" }
            // Agrega tantos como quieras...
        ];

        const dashboard = document.getElementById('dashboard');


        // --- NUEVA LÓGICA DE AGRUPACIÓN POR FILAS ---
        // Esto define cuántos hexágonos irán en la fila. Debe coincidir con tu diseño.
        // Si el hexágono mide 11vw (10vw de ancho + 0.5vw de margen a cada lado), 
        // caben unos 9 en una pantalla de 100vw.
        const HEX_PER_ROW = 8;

        let currentRow = document.createElement('div');
        currentRow.className = 'honeycomb-row';

        servidores.forEach((server, index) => {
            // 1. Crear el elemento Hexágono (Mismo código de antes)
            const hex = document.createElement('div');
            hex.className = 'hex checking';
            hex.id = `server-${index}`;
            hex.innerHTML = `
                    <h3>${server.name}</h3>
                    <span>${server.ip}</span>
                    <div class="ping">-- ms</div>
            `;

            // 2. Añadir al contenedor de fila actual
            currentRow.appendChild(hex);

            // 3. Chequear si es hora de iniciar una nueva fila
            if ((index + 1) % HEX_PER_ROW === 0 || index === servidores.length - 1) {
                // Añadir la fila completa al dashboard
                dashboard.appendChild(currentRow);

                // Crear una nueva fila para el próximo ciclo
                currentRow = document.createElement('div');
                currentRow.className = 'honeycomb-row';
            }

            // 4. Iniciar el chequeo (Misma función asíncrona)
            checkServer(server, index);
        });

        // Función que llama a PHP
        async function checkServer(server, index) {
            const hexElement = document.getElementById(`server-${index}`);

            try {
                let formData = new FormData();
                formData.append("METODO", "ping");
                formData.append("IP", server.ip);
                formData.append("PORT", server.puerto || 80);

                // Llamada Fetch al archivo PHP
                const response = await fetch('../api/functions.php', {
                    method: "POST",
                    body: formData,
                    timeout: 10000 // 10 segundos
                });
                const res = await response.json();
                const data = res.data;
                // Limpiar clases anteriores
                hexElement.classList.remove('checking', 'online', 'offline');

                if (data.online) {
                    hexElement.classList.add('online');
                    hexElement.querySelector('.ping').innerText = data.latency + ' ms';
                } else {
                    hexElement.classList.add('offline');
                    hexElement.querySelector('.ping').innerText = 'OFF';
                }

            } catch (error) {
                //console.error("Error de red", error);
                hexElement.classList.remove('checking');
                hexElement.classList.add('offline');
            }
        }

        // OPCIONAL: Refrescar cada 30 segundos
        setInterval(() => {
            servidores.forEach((server, index) => {
                // Volvemos a poner estado "checking" visualmente o solo actualizamos
                checkServer(server, index);
            });
        }, 60000);

    </script>
</body>

</html>