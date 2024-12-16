<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["now_password", "new_password"])) {
    if(IS_LIVE) {
        $returnArray["code"] = "PARAMS";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$data = cleansingParams($data);

// 현재 비밀번호 검증
$nowPasswordCheck = Account::hasPasswordCheck($data["now_password"], $member["user_password"]);
if ($nowPasswordCheck === false) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "현재 비밀번호를 정확하게 입력해 주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 새 비밀번호 정규식 검증
$newPasswordValidate = Regexp::password_regexp($data["new_password"]);
if ($newPasswordValidate === false) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "새 비밀번호를 규칙에 맞게 입력해 주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 비밀번호 업데이트
$result = Account::updatePassword($data["new_password"]);

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>