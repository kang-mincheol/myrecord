<?
//error_reporting(E_ALL);
//ini_set("memory_limit" , -1);
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');  // 설정 파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/library/common.lib.php');             // library 파일 로드


//Session 설정
session_save_path(SESSION_PATH);
@session_start();

//member 전역 변수
$member;

//로그인 설정
if ($_SESSION['user_id']) { // 로그인중이라면
    $member = getMember($_SESSION['user_id']);
}

include_once($_SERVER['DOCUMENT_ROOT'].'/log.php');   // 접속 로그 기록

include_once($_SERVER['DOCUMENT_ROOT'].'/menu.php');   // Menu 파일 로드

$relative_path = preg_replace("`\/[^/]*\.php$`i", "/", $_SERVER['PHP_SELF']);



?>