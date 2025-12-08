<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT");
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
    <link href="../css/oswald.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Oswald', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            padding: 2rem;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        }

        .header p {
            font-size: 1.2rem;
            color: #94a3b8;
            margin-top: 0.5rem;
        }

        .honeycomb-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .hexagon-wrapper {
            position: relative;
            width: 200px;
            height: 230px;
            margin: 10px;
        }

        .hexagon {
            position: relative;
            width: 200px;
            height: 115px;
            background: #334155;
            margin: 57.5px 0;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .hexagon:before,
        .hexagon:after {
            content: "";
            position: absolute;
            width: 0;
            border-left: 100px solid transparent;
            border-right: 100px solid transparent;
        }

        .hexagon:before {
            bottom: 100%;
            border-bottom: 57.5px solid #334155;
        }

        .hexagon:after {
            top: 100%;
            width: 0;
            border-top: 57.5px solid #334155;
        }

        /* Online status - Green */
        .hexagon.online {
            background: linear-gradient(145deg, #059669, #047857);
            animation: pulseGreen 2s ease-in-out infinite;
        }

        .hexagon.online:before {
            border-bottom-color: #059669;
        }

        .hexagon.online:after {
            border-top-color: #047857;
        }

        /* Offline status - Red */
        .hexagon.offline {
            background: linear-gradient(145deg, #dc2626, #b91c1c);
            animation: pulseRed 1.5s ease-in-out infinite;
        }

        .hexagon.offline:before {
            border-bottom-color: #dc2626;
        }

        .hexagon.offline:after {
            border-top-color: #b91c1c;
        }

        /* Checking status - Yellow/Orange */
        .hexagon.checking {
            background: linear-gradient(145deg, #f59e0b, #d97706);
            animation: pulseYellow 1s ease-in-out infinite;
        }

        .hexagon.checking:before {
            border-bottom-color: #f59e0b;
        }

        .hexagon.checking:after {
            border-top-color: #d97706;
        }

        /* Error status - Gray */
        .hexagon.error {
            background: linear-gradient(145deg, #6b7280, #4b5563);
        }

        .hexagon.error:before {
            border-bottom-color: #6b7280;
        }

        .hexagon.error:after {
            border-top-color: #4b5563;
        }

        @keyframes pulseGreen {
            0%, 100% {
                box-shadow: 0 0 20px rgba(5, 150, 105, 0.5);
            }
            50% {
                box-shadow: 0 0 40px rgba(5, 150, 105, 0.8);
            }
        }

        @keyframes pulseRed {
            0%, 100% {
                box-shadow: 0 0 20px rgba(220, 38, 38, 0.5);
            }
            50% {
                box-shadow: 0 0 40px rgba(220, 38, 38, 0.8);
            }
        }

        @keyframes pulseYellow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(245, 158, 11, 0.5);
            }
            50% {
                box-shadow: 0 0 40px rgba(245, 158, 11, 0.8);
            }
        }

        .hexagon-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 1rem;
        }

        .server-name {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
        }

        .server-ip {
            font-size: 0.9rem;
            color: #e2e8f0;
            margin-bottom: 0.3rem;
        }

        .server-status {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .response-time {
            font-size: 0.75rem;
            color: #cbd5e1;
            margin-top: 0.2rem;
        }

        .legend {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: rgba(30, 41, 59, 0.9);
            padding: 1.5rem;
            border-radius: 10px;
            border: 2px solid #475569;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .legend-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 0.8rem;
        }

        .legend-color.online {
            background: #059669;
            box-shadow: 0 0 10px rgba(5, 150, 105, 0.5);
        }

        .legend-color.offline {
            background: #dc2626;
            box-shadow: 0 0 10px rgba(220, 38, 38, 0.5);
        }

        .legend-color.checking {
            background: #f59e0b;
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
        }

        .legend-color.error {
            background: #6b7280;
        }

        .last-update {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            background: rgba(30, 41, 59, 0.9);
            padding: 1rem 1.5rem;
            border-radius: 10px;
            border: 2px solid #475569;
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .tooltip {
            position: absolute;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 0.8rem;
            border-radius: 5px;
            font-size: 0.85rem;
            z-index: 1000;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            max-width: 250px;
        }

        .tooltip.show {
            opacity: 1;
        }
    </style>
</head>
<body>
    <input type="hidden" id="indicador_id" />
    
    <div class="header">
        <h1>Monitor de Servidores</h1>
        <p>Visualización en Tiempo Real del Estado de Servidores</p>
    </div>

    <div class="honeycomb-container" id="honeycomb-container">
        <!-- Hexagons will be generated here -->
    </div>

    <div class="legend">
        <div class="legend-title">Estado</div>
        <div class="legend-item">
            <div class="legend-color online"></div>
            <span>En Línea</span>
        </div>
        <div class="legend-item">
            <div class="legend-color offline"></div>
            <span>Fuera de Línea</span>
        </div>
        <div class="legend-item">
            <div class="legend-color checking"></div>
            <span>Verificando...</span>
        </div>
        <div class="legend-item">
            <div class="legend-color error"></div>
            <span>Error</span>
        </div>
    </div>

    <div class="last-update" id="last-update">
        Última actualización: --
    </div>

    <div class="tooltip" id="tooltip"></div>

    <script src="../js/jquery/jquery.min.js"></script>
    <script>
        let servers = [];
        let updateInterval;

        function exec_getdata() {
            loadServers();
            startAutoRefresh();
        }

        function loadServers() {
            $.ajax({
                type: "POST",
                data: { METODO: "get_servers" },
                dataType: 'json',
                url: "../api/functions.php",
                success: function(response) {
                    if (response.data) {
                        servers = response.data;
                        createHoneycomb();
                        checkAllServers();
                    }
                },
                error: function(err) {
                    console.error("Error loading servers:", err);
                }
            });
        }

        function createHoneycomb() {
            const container = document.getElementById('honeycomb-container');
            container.innerHTML = '';

            servers.forEach((server, index) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'hexagon-wrapper';

                const hexagon = document.createElement('div');
                hexagon.className = 'hexagon checking';
                hexagon.id = 'server-' + server.SERVER_ID;
                hexagon.setAttribute('data-server-id', server.SERVER_ID);
                hexagon.setAttribute('data-server-ip', server.SERVER_IP);

                const content = document.createElement('div');
                content.className = 'hexagon-content';
                content.innerHTML = `
                    <div class="server-name">${server.SERVER_NAME}</div>
                    <div class="server-ip">${server.SERVER_IP}</div>
                    <div class="server-status">Verificando...</div>
                    <div class="response-time" id="time-${server.SERVER_ID}"></div>
                `;

                hexagon.appendChild(content);
                wrapper.appendChild(hexagon);
                container.appendChild(wrapper);

                // Add hover events
                hexagon.addEventListener('mouseenter', (e) => showTooltip(e, server));
                hexagon.addEventListener('mouseleave', hideTooltip);
            });
        }

        function checkAllServers() {
            $.ajax({
                type: "POST",
                data: { METODO: "ping_all_servers" },
                dataType: 'json',
                url: "../api/functions.php",
                success: function(response) {
                    if (response.data) {
                        updateServerStatus(response.data);
                        updateLastUpdateTime();
                    }
                },
                error: function(err) {
                    console.error("Error checking servers:", err);
                }
            });
        }

        function updateServerStatus(serverStatuses) {
            serverStatuses.forEach(server => {
                const hexagon = document.getElementById('server-' + server.SERVER_ID);
                if (hexagon) {
                    const statusDiv = hexagon.querySelector('.server-status');
                    const timeDiv = document.getElementById('time-' + server.SERVER_ID);

                    // Remove all status classes
                    hexagon.classList.remove('online', 'offline', 'checking', 'error');

                    // Add new status class
                    hexagon.classList.add(server.STATUS);

                    // Update status text
                    switch(server.STATUS) {
                        case 'online':
                            statusDiv.textContent = 'EN LÍNEA';
                            if (server.RESPONSE_TIME) {
                                timeDiv.textContent = server.RESPONSE_TIME + ' ms';
                            }
                            break;
                        case 'offline':
                            statusDiv.textContent = 'FUERA DE LÍNEA';
                            timeDiv.textContent = '';
                            break;
                        case 'error':
                            statusDiv.textContent = 'ERROR';
                            timeDiv.textContent = '';
                            break;
                    }
                }
            });
        }

        function showTooltip(event, server) {
            const tooltip = document.getElementById('tooltip');
            tooltip.innerHTML = `
                <strong>${server.SERVER_NAME}</strong><br>
                IP: ${server.SERVER_IP}<br>
                ${server.DESCRIPTION || 'Sin descripción'}
            `;
            tooltip.style.left = event.pageX + 15 + 'px';
            tooltip.style.top = event.pageY + 15 + 'px';
            tooltip.classList.add('show');
        }

        function hideTooltip() {
            const tooltip = document.getElementById('tooltip');
            tooltip.classList.remove('show');
        }

        function updateLastUpdateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-ES');
            document.getElementById('last-update').textContent = 'Última actualización: ' + timeString;
        }

        function startAutoRefresh() {
            // Refresh every 30 seconds
            updateInterval = setInterval(() => {
                checkAllServers();
            }, 30000);
        }

        // Initial load when called from parent
        // exec_getdata();
    </script>
</body>
</html>
