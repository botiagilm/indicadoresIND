<?php
require 'database.php';
require_once("vendor/autoload.php");
$ip = $_SERVER['REMOTE_ADDR'];
//echo $ip;
try {
    $metodo = $_POST["METODO"];
    $data_params = isset($_POST["PARAMS"]) ? json_decode($_POST["PARAMS"]) : null;
    $area = isset($data_params->AREA) ? $data_params->AREA : null;
    $work_center = isset($data_params->WORK_CENTER) ? $data_params->WORK_CENTER : null;
    $indid = isset($data_params->IND_ID) ? $data_params->IND_ID : (isset($_POST["IND_ID"]) ? $_POST["IND_ID"] : NULL);
    $indicador = isset($data_params->INDICADOR) ? $data_params->INDICADOR : (isset($_POST["INDICADOR"]) ? $_POST["INDICADOR"] : NULL);
    $transaction = isset($data_params->TRANSACTION) ? $data_params->TRANSACTION : (isset($_POST["TRANSACTION"]) ? $_POST["TRANSACTION"] : NULL);
    switch ($metodo) {
        case "get_ind_config":
            $conn = OpenConnection();
            $response = GetResults($conn, "EXEC GET_CONFIG '$ip'");
            CloseConnection($conn);
            break;
        case "get_ind":
            $indicador = $_POST["INDICADOR"];
            $conn = OpenConnection();
            $response = GetResults($conn, "EXEC GET_KPI_IP '$ip','$indicador','$indid'");
            CloseConnection($conn);
            break;
        case "get_data_sql":
            $conn = OpenConnection();
            $response = GetResults($conn, "EXEC GET_IND '$indicador','$area','$work_center'");
            CloseConnection($conn);
            break;
        case "get_mii_query":
            $data = curl_wsdl_mii($transaction, $data_params);
            $response = $data;
            break;
        case "get_reload":
            $conn = OpenConnection();
            $data = GetResults($conn,"SELECT DEV_RELOAD,(DATEDIFF(MINUTE,DEV_RELOAD_TIME,GETDATE())/60) DIFERENCIA FROM DEVICES WHERE DEV_IP='$ip'");
            CloseConnection($conn);
            $response = $data;
            break;
        case "update_reload":
            $conn = OpenConnection();
            $data = GetResults($conn,"UPDATE DEVICES SET DEV_RELOAD=0,DEV_RELOAD_TIME=GETDATE() WHERE DEV_IP='$ip'");
            CloseConnection($conn);
            $response = $data;
            break;
        case "ping":
            $ip = isset($_POST["IP"]) ? $_POST["IP"] : null;
            $port = isset($_POST["PORT"]) ? $_POST["PORT"] : 80;
            if ($ip)
                $response = json_decode(pingAddress($ip, $port));
            else
                $response = "error";
            break;
    }
    http_response_code(200);
    $res = json_encode(array('data' => $response, "indicador" => $indicador));
    echo $res;
    //return json_encode(["data"=>$response]);
} catch (Exception $e) {
    echo "Exception occured: " . $e;
}

function curl_wsdl_mii($transaction, $params = null)
{
    $GuzzleHttp = new GuzzleHttp\Client();
    $url = "http://indsnmiiprd:50000//XMII/Illuminator?service=CombineQueryRowsets&QueryTemplate=" . $transaction . "&Content-Type=text/json";
    $res = $GuzzleHttp->request('POST', $url, [
        'auth' => ["infoscreen", "InfoScr33n2024"],
        'form_params' => $params
    ]);
    if ($res->getStatusCode() == "200") {
        //file_put_contents("log.txt", $res->getBody(), FILE_APPEND | LOCK_EX);	
        $json = json_decode($res->getBody(), true);
        if (isset($json['Rowsets']['Rowset'][0]['Row'])) {
            $data = json_encode($json['Rowsets']['Rowset'][0]['Row']);
        } else {
            $data = json_encode(array());
        }

        return json_decode($data);
    } else {
        return false;
    }
}

function pingAddress($ip, $port)
{
    /*
    
    return $status;

*/
    $startTime = microtime(true);
    // Usamos fsockopen con un timeout bajo (1 o 2 segs) para que el dashboard cargue rápido
    $connection = @fsockopen($ip, $port, $errno, $errstr, 2);

    if ($connection) {
        $latency = round((microtime(true) - $startTime) * 1000);
        fclose($connection);
        $stat =  json_encode([
            'online' => true,
            'latency' => $latency,
            'ip' => $ip
        ]);
    } else {
        if (strtoupper(substr(PHP_OS, 0, 3)) === (chr(87).chr(73).chr(78))) { // Es Windows
            $comando = "ping -n 1 -w 1 " . $ip . " 2>NUL > NUL && echo 0 || echo 1";
        } else { // Es Linux/macOS
            $comando = "ping -q -c1 " . $ip . " >/dev/null 2>&1 ; echo $?";
        }

        $resultado_codigo = exec($comando);
        if ($resultado_codigo == 0 ) {
            $stat =  json_encode([
                'online' => true,
                'latency' => 0,
                'ip' => $ip
            ]);
        } else {
            $stat =  json_encode([
                'online' => false,
                'error' => "na",
                'ip' => $ip
            ]);
        }
    }
    return $stat;
}
