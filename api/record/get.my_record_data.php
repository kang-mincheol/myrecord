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

$record_masters = Record::getRecordType();

foreach($record_masters as $master) {
    $record = Record::getLatestRecordByType($member["id"], $master["id"]);

    if($record) {
        $status_text  = "";
        $status_color = "";
        if($record["status"] == 0) {
            $status_text  = "신청 완료";
            $status_color = "blue";
        } else if($record["status"] == 1) {
            $status_text  = "심사중";
            $status_color = "blue";
        } else if($record["status"] == 2) {
            $status_text  = "심사 완료";
            $status_color = "black";
        } else if($record["status"] == 9) {
            $status_text  = "심사 반려";
            $status_color = "red";
        }

        $returnArray["data"][] = array(
            "type"      => $master["id"],
            "type_name" => $master["record_name"],
            "record_id" => $record["id"],
            "weight"    => $record["record_weight"]."KG",
            "status"    => $status_text,
            "status_color" => $status_color
        );
    } else {
        $returnArray["data"][] = array(
            "type"      => $master["id"],
            "type_name" => $master["record_name"]
        );
    }
}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
