<?
include_once($_SERVER["DOCUMENT_ROOT"]."/common.php");


function checkParams($params, $keys){
    //$keys = ["ci", "prod_cd"];
    foreach ($keys as $key) {
        if(!array_key_exists($key, $params)){
            return false;
        }
    }

    return true;
}

function cleansingParams($params, $injection_check = false){
    foreach ($params as $param => $value) {
        if(is_array($value)){
            $params[$param] = cleansingParams($value);
        } else {
            //$value = str_replace("==", "#%$@%#%", $value); 
            $value = rawurldecode($value);
            $value = sql_escape_string($value);
            $value = trim($value);
            $value = xss_clean($value);
            if($injection_check){
                $value = sql_injection_clean($value);
            }
            //$value = str_replace("#%$@%#%", "==", $value);
            $params[$param] = $value;
        }
        
    }
    return $params;
}

?>