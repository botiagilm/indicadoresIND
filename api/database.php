<?php
function OpenConnection()
    {
        $serverName = "10.21.10.20";
        $connectionOptions = array("Database"=>"INFOSCREEN",
            "UID"=>"u_infoscreen", "PWD"=>"InfoMii2024","TrustServerCertificate"=>true);
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        return $conn;
    }

function CloseConnection($conn)
    {
        sqlsrv_close($conn);
    }

    function GetResults($conn,$query){
        $getData = sqlsrv_query($conn, $query);
        $data = array();
    	if($getData !== false){
    		while($row = sqlsrv_fetch_array($getData, SQLSRV_FETCH_ASSOC))
    		{
      			$data[] = $row;
    		}
    	}
        //echo json_encode($data);
        return json_decode(json_encode($data));
    }
?>