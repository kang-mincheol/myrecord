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

$record_id   = (int)preg_replace("/[^0-9]+/u", "", $data["record_id"]);
$record_data = Record::getRecordViewData($record_id);

if(!$record_data) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "올바르지 않은 데이터입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$returnArray["data"] = array(
    "record_nickname"  => $record_data["user_nickname"],
    "record_name"      => $record_data["record_name_ko"],
    "record_weight"    => $record_data["record_weight"],
    "record_memo"      => $record_data["memo"] ?? '',
    "record_status"    => $record_data["status_text"],
    "record_status_eng"=> $record_data["status_value"],
    "record_create"    => $record_data["create_date"],
    "is_recorder"      => false
);

// 신청자 본인 여부 확인
if($is_member && $record_data["account_id"] == $member["id"]) {
    $returnArray["data"]["is_recorder"] = true;
}

// 첨부 파일 조회
$files = Record::getFilesByRequestId($record_id);
if($files) {
    foreach($files as $row) {
        $returnArray["file"][] = array(
            "file_name" => $row["file_guid"],
            "file_src"  => RECORD_FILE_DIR . $row["file_guid"],
            "file_type" => $row["file_type"]
        );
    }
}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
