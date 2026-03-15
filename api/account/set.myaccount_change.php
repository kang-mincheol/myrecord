<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["nickname"])) {
    if(IS_LIVE) {
        $returnArray["code"] = "PARAMS";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$data = cleansingParams($data);

if(!$is_member) {
    $returnArray["code"] = "MEMBER_ONLY";
    $returnArray["msg"] = "로그인 후 이용해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 닉네임 중복 체크 (자신 제외)
if (Account::checkNicknameForUpdate($data["nickname"], $member["id"])) {
    $returnArray["code"] = "OVERLAP";
    $returnArray["msg"] = "이미 사용중인 닉네임 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 핸드폰번호 변경하는지 체크
if(!empty($data["phone"])) {
    if (Account::checkPhoneForUpdate($data["phone"], $member["id"])) {
        $returnArray["code"] = "OVERLAP";
        $returnArray["msg"] = "이미 사용중인 핸드폰번호 입니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

// 이메일 변경하는지 체크
if(!empty($data["email"])) {
    if (Account::checkEmailForUpdate($data["email"], $member["id"])) {
        $returnArray["code"] = "OVERLAP";
        $returnArray["msg"] = "이미 사용중인 이메일 입니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

// 업데이트 실행
$update = Account::updateMyAccount($data, $member["id"]);

if(!$update) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "업데이트 중 에러가 발생했습니다</br>고객센터에 문의해 주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
