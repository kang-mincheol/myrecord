<?php
ini_set("memory_limit" , -1);

function makeGuid() {
    return sprintf('%08x-%04x-%04x-%04x-%04x%08x',
        mt_rand(0, 0xffffffff),
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff), mt_rand(0, 0xffffffff)
    );
}

// 설정 파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
// PDO 파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/library/db.lib.php');
// library 파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/library/common.lib.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/library/kmc.lib.php');
// JWT 라이브러리 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/library/jwt.lib.php');

// class 파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/class/class.account.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/class/class.regexp.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/class/class.slack.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/class/class.freeBoard.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/class/class.record.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/class/class.workoutLog.php');


/********** PDO 설정 **********/
try {
    $HOST = MYSQL_HOST;
    $DB = MYSQL_DB;
    //$PORT = IS_LIVE ? ($HOST == "SERVER_IP 를 넣어주세요" ? 4000 : 10000) : 3306;
    $PORT = "localhost";
    $PDO = new DB("mysql:host={$HOST};port={$PORT};dbname={$DB};charset=utf8", MYSQL_USER, MYSQL_PASSWORD);
} catch (PDOException $Exception) {
    die($Exception->getMessage());
}
/********** PDO 설정 END **********/


//member 전역 변수
$member = false;

// JWT 인증 — 쿠키(httpOnly) 또는 Authorization: Bearer 헤더에서 토큰 추출
// ── JWT 인증 ──────────────────────────────────────────────────────────────────
// 1단계: Access Token 확인
$_access_payload = jwt_decode(jwt_get_access_token(), JWT_SECRET);

if ($_access_payload !== null && ($_access_payload['type'] ?? '') === 'access' && !empty($_access_payload['sub'])) {
    // Access Token 유효 → 회원 정보 로드
    $member = Account::getAccountById((int)$_access_payload['sub']);

} else {
    // 2단계: Access Token 만료 → Refresh Token으로 갱신 시도
    $_refresh_token   = jwt_get_refresh_token();
    $_refresh_payload = jwt_decode($_refresh_token, JWT_SECRET);

    if (
        $_refresh_payload !== null &&
        ($_refresh_payload['type'] ?? '') === 'refresh' &&
        !empty($_refresh_payload['sub']) &&
        jwt_verify_refresh_token($_refresh_token)   // DB 검증
    ) {
        $member = Account::getAccountById((int)$_refresh_payload['sub']);

        if ($member) {
            // Token Rotation: 기존 Refresh Token 폐기 → 새 Access + Refresh 동시 발급
            jwt_delete_refresh_token($_refresh_token);
            $new_access  = jwt_create_access_token((int)$_refresh_payload['sub']);
            $new_refresh = jwt_create_refresh_token((int)$_refresh_payload['sub']);
            jwt_set_access_cookie($new_access);
            jwt_set_refresh_cookie($new_refresh);
            jwt_save_refresh_token((int)$_refresh_payload['sub'], $new_refresh);
            unset($new_access, $new_refresh);
        }
    }
    // Refresh Token이 만료되었거나 DB에 없으면 → $member = false → 비로그인 상태
    unset($_refresh_token, $_refresh_payload);
}

unset($_access_payload);
// ── JWT 인증 END ──────────────────────────────────────────────────────────────

$is_member = false;
$is_admin = false;

if(!empty($member['user_id'])) {
    $is_member = true;
    $is_admin = $member["is_admin"] == 1 ? true : false;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/log.php');   // 접속 로그 기록

include_once($_SERVER['DOCUMENT_ROOT'].'/menu.php');   // Menu 파일 로드

$relative_path = preg_replace("`\/[^/]*\.php$`i", "/", $_SERVER['PHP_SELF']);



//og 정보 정리
$pageTitle = "마이레코드";
$ogTitle = "마이레코드";
$ogDescription = "3대 측정은 마이레코드";
$ogImage = "/img/company/myrecord_og_image.png?ver=2";
$ogUrl = "https://myrecord.kr";


//var_dump($_SERVER);
if(strpos($_SERVER["PHP_SELF"], "/util/") !== false) {
    $pageTitle = "마이레코드 - 도구";
    $ogTitle = "마이레코드 - 도구";
} else {

}
?>