# Monitor de Servidores - Visualización en Panal

Este módulo agrega funcionalidad de monitoreo de servidores con una visualización en forma de panal hexagonal (honeycomb) que muestra el estado de los servidores en tiempo real mediante ping.

## Características

- ✅ Visualización en panal hexagonal atractiva
- ✅ Detección automática de estado de servidores mediante ping PHP
- ✅ Actualización automática cada 30 segundos
- ✅ Indicadores de color:
  - **Verde**: Servidor en línea
  - **Rojo**: Servidor fuera de línea (con animación pulsante)
  - **Amarillo**: Verificando estado
  - **Gris**: Error en la verificación
- ✅ Tiempo de respuesta en milisegundos para servidores en línea
- ✅ Tooltips informativos al pasar el mouse sobre cada hexágono

## Instalación

### 1. Configurar la Base de Datos

Ejecuta el script SQL para crear la tabla y el procedimiento almacenado:

```bash
sqlcmd -S 10.21.10.20 -U u_infoscreen -P InfoMii2024 -d INFOSCREEN -i api/server_monitor_db.sql
```

O ejecuta el contenido de `api/server_monitor_db.sql` directamente en SQL Server Management Studio.

Este script:
- Crea la tabla `SERVERS` para almacenar información de servidores
- Crea el procedimiento almacenado `GET_SERVERS` para obtener la lista de servidores activos
- Inserta servidores de ejemplo (actualiza las IPs según tu infraestructura)

### 2. Configurar Servidores a Monitorear

Edita el archivo `api/server_monitor_db.sql` o inserta registros directamente en la tabla `SERVERS`:

```sql
INSERT INTO SERVERS (SERVER_NAME, SERVER_IP, DESCRIPTION) 
VALUES ('Mi Servidor', '192.168.1.100', 'Descripción del servidor');
```

Campos de la tabla:
- `SERVER_NAME`: Nombre descriptivo del servidor
- `SERVER_IP`: Dirección IP del servidor
- `DESCRIPTION`: Descripción opcional
- `ACTIVE`: 1 = activo, 0 = inactivo (solo servidores activos se muestran)

### 3. Agregar al Dashboard Principal

Para agregar el monitor de servidores al sistema de indicadores principal:

1. Inserta un registro en la configuración de indicadores:

```sql
-- Ejemplo de inserción en la tabla de configuración (ajustar según tu esquema)
INSERT INTO IND_CONFIG (IND_NAME, IND_DURATION, ACTIVE) 
VALUES ('SERVER_MONITOR', 30, 1);
```

2. El dashboard principal (`kpis.php`) cargará automáticamente el monitor en su rotación

### 4. Acceso Directo

También puedes acceder directamente al monitor:

```
http://tu-servidor/indicadoresIND/web/SERVER_MONITOR.php
```

## Funcionamiento Técnico

### API Endpoints

El módulo agrega tres nuevos endpoints a `api/functions.php`:

1. **get_servers**: Obtiene la lista de servidores desde la base de datos
   ```javascript
   $.ajax({
       type: "POST",
       data: { METODO: "get_servers" },
       url: "../api/functions.php"
   });
   ```

2. **ping_server**: Verifica el estado de un servidor específico
   ```javascript
   $.ajax({
       type: "POST",
       data: { 
           METODO: "ping_server",
           SERVER_IP: "192.168.1.100"
       },
       url: "../api/functions.php"
   });
   ```

3. **ping_all_servers**: Verifica el estado de todos los servidores
   ```javascript
   $.ajax({
       type: "POST",
       data: { METODO: "ping_all_servers" },
       url: "../api/functions.php"
   });
   ```

### Método de Ping

La función `ping_server()` utiliza dos métodos:

1. **fsockopen**: Intenta conectar al puerto 80 del servidor (rápido)
2. **ping command**: Si fsockopen falla, usa el comando ping del sistema (fallback)

Ambos métodos tienen un timeout de 2 segundos para evitar bloqueos.

## Personalización

### Cambiar el Intervalo de Actualización

Edita `web/SERVER_MONITOR.php`, línea ~474 en la sección JavaScript:

```javascript
updateInterval = setInterval(() => {
    checkAllServers();
}, 30000); // 30000 ms = 30 segundos
```

### Cambiar el Puerto de Verificación

Edita `api/functions.php`, función `ping_server()`:

```php
$fp = @fsockopen($server_ip, 80, $errno, $errstr, $timeout);
// Cambia 80 por el puerto deseado
```

### Personalizar Colores

Edita el archivo `web/SERVER_MONITOR.php` en la sección `<style>`:

```css
/* Online status - Green */
.hexagon.online {
    background: linear-gradient(145deg, #059669, #047857);
}

/* Offline status - Red */
.hexagon.offline {
    background: linear-gradient(145deg, #dc2626, #b91c1c);
}
```

## Requisitos

- PHP 7.0+
- SQL Server con la extensión `sqlsrv`
- jQuery (ya incluido en el proyecto)
- Permisos de sistema para ejecutar comandos `ping` (para el método fallback)
- Puerto 80 abierto en los servidores a monitorear (o modificar el puerto en el código)

## Solución de Problemas

### Los servidores aparecen siempre como "offline"

1. Verifica que el puerto 80 esté abierto en los servidores
2. Verifica que PHP tenga permisos para ejecutar comandos del sistema
3. Revisa los logs del servidor web para errores

### Error "Cannot execute GET_SERVERS"

1. Verifica que el script SQL se haya ejecutado correctamente
2. Confirma que el usuario de base de datos tenga permisos de ejecución

### Las actualizaciones no se reflejan

1. Limpia la caché del navegador (Ctrl+F5)
2. Verifica que las cabeceras de no-cache estén funcionando
3. Revisa la consola del navegador para errores JavaScript

## Estructura de Archivos

```
indicadoresIND/
├── api/
│   ├── functions.php              # Funciones API (modificado con endpoints de ping)
│   └── server_monitor_db.sql      # Script de base de datos
└── web/
    └── SERVER_MONITOR.php         # Página de visualización del monitor
```

## Contribuir

Para agregar mejoras:

1. Modifica `api/functions.php` para endpoints adicionales
2. Actualiza `web/SERVER_MONITOR.php` para cambios de UI
3. Actualiza este README con nueva documentación

## Licencia

Parte del proyecto indicadoresIND
