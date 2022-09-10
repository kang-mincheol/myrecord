<?
include_once($_SERVER["DOCUMENT_ROOT"]."/common.php");


function checkParams($params, $keys){
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


//Log 기록
$MEMBER_ID = $is_member ? $member["id"] : null;
$METHOD = $_SERVER["REQUEST_METHOD"];
$REQUEST_USER_AGENT = $_SERVER["HTTP_USER_AGENT"];
$REQUEST_URL = $_SERVER["REQUEST_URI"];
$REQUEST_BODY = $_POST ? $_POST : null;
if($_POST) {
    $REQUEST_BODY = "";
    foreach($_POST as $key => $value) {
        $REQUEST_BODY = $key;
    }
}
$HTTP_REFERER = $_SERVER["HTTP_REFERER"];

$log_query = "
    Insert Into RequestLog
    Set
        request_member = :request_member,
        method = :method,
        user_agent = :user_agent,
        request_url = :request_url,
        request_body = :request_body,
        http_referer = :http_referer
";
$log_param = array(
    ":request_member" => $MEMBER_ID,
    ":method" => $METHOD,
    ":user_agent" => $REQUEST_USER_AGENT,
    ":request_url" => $REQUEST_URL,
    ":request_body" => $REQUEST_BODY,
    ":http_referer" => $HTTP_REFERER
);
$PDO->execute($log_query, $log_param);

?>