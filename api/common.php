<?
include_once($_SERVER["DOCUMENT_ROOT"]."/common.php");

//method 값 정의
$_method = $_SERVER['REQUEST_METHOD'];
$methodArray = ["GET", "POST", "PUT", "DELETE"];
if(!in_array($_method, $methodArray)) {
    $returnArray = array(
        "code"=>"400",
        "msg"=>"존재하지 않는 method 타입 입니다"
    );

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

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