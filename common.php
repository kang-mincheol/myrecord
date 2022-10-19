<?
ini_set("memory_limit" , -1);


// 설정 파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
// PDO 파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/library/db.lib.php');
// library 파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/library/common.lib.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/library/kmc.lib.php');




/********** PDO 설정 **********/
try {
    $HOST = MYSQL_HOST;
    $DB = MYSQL_DB;
    //$PORT = IS_LIVE ? ($HOST == "SERVER_IP 를 넣어주세요" ? 4000 : 10000) : 3306;
    $PORT = IS_LIVE ? ($HOST == "" ? 4000 : 10000) : 3306;
    $PDO = new DB("mysql:host={$HOST};port={$PORT};dbname={$DB};charset=utf8", MYSQL_USER, MYSQL_PASSWORD);
} catch (PDOException $Exception) {
    die($Exception->getMessage());
}
/********** PDO 설정 END **********/


//Session 설정
session_save_path(SESSION_PATH);
@session_start();

//member 전역 변수
$member = false;


//로그인 설정
if ($_SESSION['user_id']) { // 로그인중이라면
    $member = getMember($_SESSION['user_id']);
}

$is_member = false;
$is_admin = false;

if($member['user_id']) {
    $is_member = true;
    $is_admin = $member["is_admin"] == 1 ? true : false;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/log.php');   // 접속 로그 기록

include_once($_SERVER['DOCUMENT_ROOT'].'/menu.php');   // Menu 파일 로드

$relative_path = preg_replace("`\/[^/]*\.php$`i", "/", $_SERVER['PHP_SELF']);
?>