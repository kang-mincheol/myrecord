<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["record_id"])) {
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

if(!is_number($data["record_id"])) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$record_id   = (int)preg_replace("/[^0-9]+/u", "", $data["record_id"]);
$record_data = Record::getRecordById($record_id);

if(!$record_data) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.<br/>-2";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 본인 글이 아닌 경우 삭제 불가
if($member["id"] != $record_data["account_id"]) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.<br/>-3";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 기록 삭제 (파일 + 검증이력 + 요청)
Record::deleteRecord($record_id);

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
