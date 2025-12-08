<?php
    require 'database.php' ;
    require_once("vendor/autoload.php");
    $ip = $_SERVER['REMOTE_ADDR'];
    //echo $ip;
    try{
        $metodo = $_POST["METODO"];
        $data_params = isset($_POST["PARAMS"])?json_decode($_POST["PARAMS"]):null;
        $area = isset($data_params->AREA)?$data_params->AREA:null;
        $work_center = isset($data_params->WORK_CENTER)?$data_params->WORK_CENTER:null;
	$indid = isset($data_params->IND_ID)?$data_params->IND_ID:(isset($_POST["IND_ID"])?$_POST["IND_ID"]:NULL);
        $indicador = isset($data_params->INDICADOR)?$data_params->INDICADOR:(isset($_POST["INDICADOR"])?$_POST["INDICADOR"]:NULL);
        $transaction = isset($data_params->TRANSACTION)?$data_params->TRANSACTION:(isset($_POST["TRANSACTION"])?$_POST["TRANSACTION"]:NULL);
        switch($metodo){
            case "get_ind_config":
                $conn = OpenConnection();
                $response = GetResults($conn,"EXEC GET_CONFIG '$ip'");
                CloseConnection($conn);
                break;
            case "get_ind":
                $indicador = $_POST["INDICADOR"];
                $conn = OpenConnection();
                $response = GetResults($conn,"EXEC GET_KPI_IP '$ip','$indicador','$indid'");                
                CloseConnection($conn);
                break;
            case "get_data_sql":
                $conn = OpenConnection();
                $response = GetResults($conn,"EXEC GET_IND '$indicador','$area','$work_center'");
                CloseConnection($conn);
                break;
            case "get_mii_query":
                $data =curl_wsdl_mii($transaction,$data_params);
                $response = $data;
                break;
            case "get_servers":
                $conn = OpenConnection();
                $response = GetResults($conn,"EXEC GET_SERVERS");
                CloseConnection($conn);
                break;
            case "ping_server":
                $server_ip = isset($_POST["SERVER_IP"]) ? $_POST["SERVER_IP"] : null;
                $response = ping_server($server_ip);
                break;
            case "ping_all_servers":
                $conn = OpenConnection();
                $servers = GetResults($conn,"EXEC GET_SERVERS");
                CloseConnection($conn);
                $response = array();
                foreach($servers as $server) {
                    $ping_result = ping_server($server->SERVER_IP);
                    $response[] = array(
                        'SERVER_ID' => $server->SERVER_ID,
                        'SERVER_NAME' => $server->SERVER_NAME,
                        'SERVER_IP' => $server->SERVER_IP,
                        'STATUS' => $ping_result['status'],
                        'RESPONSE_TIME' => $ping_result['response_time']
                    );
                }
                break;
        }
        http_response_code(200);
        $res = json_encode(array('data'=>$response,"indicador"=>$indicador));
        echo $res;
        //return json_encode(["data"=>$response]);
    }catch (Exception $e) {
        echo "Exception occured: " . $e;
    }

    function curl_wsdl_mii($transaction,$params = null)
	{
        $GuzzleHttp= new GuzzleHttp\Client();
        $url="http://indsnmiiprd:50000//XMII/Illuminator?service=CombineQueryRowsets&QueryTemplate=".$transaction."&Content-Type=text/json";
        $res = $GuzzleHttp->request('POST', $url, [
            'auth' => ["infoscreen","InfoScr33n2024"],
            'form_params' => $params
        ]);
        if($res->getStatusCode()=="200"){
            //file_put_contents("log.txt", $res->getBody(), FILE_APPEND | LOCK_EX);	
            $json = json_decode($res->getBody(),true);
            if(isset($json['Rowsets']['Rowset'][0]['Row'])){
                $data = json_encode($json['Rowsets']['Rowset'][0]['Row']);
            }else{
                $data = json_encode(array());
            }
            
            return json_decode($data);
        }else{
            return false;
        }
        
    }

    function ping_server($server_ip) {
        if (empty($server_ip)) {
            return array('status' => 'error', 'response_time' => null, 'message' => 'No IP provided');
        }

        // Validate IP address
        if (!filter_var($server_ip, FILTER_VALIDATE_IP)) {
            return array('status' => 'error', 'response_time' => null, 'message' => 'Invalid IP address');
        }

        $start_time = microtime(true);
        
        // Use fsockopen for a quick connectivity check (port 80)
        $timeout = 2;
        $fp = @fsockopen($server_ip, 80, $errno, $errstr, $timeout);
        
        if (!$fp) {
            // Try ping command as fallback
            $ping_result = exec("ping -c 1 -W 2 " . escapeshellarg($server_ip) . " 2>&1", $output, $return_var);
            
            if ($return_var === 0) {
                $end_time = microtime(true);
                $response_time = round(($end_time - $start_time) * 1000, 2);
                return array('status' => 'online', 'response_time' => $response_time);
            } else {
                return array('status' => 'offline', 'response_time' => null, 'message' => 'Server unreachable');
            }
        } else {
            fclose($fp);
            $end_time = microtime(true);
            $response_time = round(($end_time - $start_time) * 1000, 2);
            return array('status' => 'online', 'response_time' => $response_time);
        }
    }

?>