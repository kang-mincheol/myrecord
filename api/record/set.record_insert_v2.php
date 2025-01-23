<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

if(!$is_member) {
    $returnArray["code"] = "MEMBER_ONLY";
    $returnArray["msg"] = "로그인 후 이용해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = $_POST;
$record_type = $data['record_type'];

$record_type = preg_replace('/[^0-9]+/u', '', $record_type);

if($record_type === "") {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "등록할 기록을 선택해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$has_record_type = Record::hasRecordType($record_type);
if ($has_record_type === false) {
    $returnArray["code"] = "RECORD_TYPE_ERROR";
    $returnArray["msg"] = "존재하지 않는 종목입니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 해당 종목으로 현재 심사 진행중인 레코드 내역이 있는지 확인
// 심사중 status id 값
$auditStatus = 2;

$hasAuditResult = Record::hasRecordDataByStatus($record_type, $auditStatus);


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>