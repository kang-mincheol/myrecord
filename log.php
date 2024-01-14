<?
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

$ip_address = getenv('REMOTE_ADDR');

$user_agent = $_SERVER['HTTP_USER_AGENT'];
$url = $_SERVER['REQUEST_URI'];
$referer =  array_key_exists("HTTP_REFERER",$_SERVER) ? $_SERVER['HTTP_REFERER'] : "";
$params = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $params = file_get_contents('php://input');
}

$url = mysqli_real_escape_string($con, $url);
$user_agent = mysqli_real_escape_string($con, $user_agent);
$referer = mysqli_real_escape_string($con, $referer);
$params = mysqli_real_escape_string($con, $params);

$id_users = empty($member) ? "NULL" : $member["id"];
$session_id = empty($_COOKIE["PHPSESSID"]) ? "" : $_COOKIE["PHPSESSID"];
$sql = "
    Insert into AccessLog
    (ip_address, user_agent, url, referer, params, create_date, id_users, session_id)
    Values 
    ('{$ip_address}', '{$user_agent}', '{$url}', '{$referer}', '{$params}', Now(), {$id_users}, '{$session_id}')
";

sql_query($sql);



?>