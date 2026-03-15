<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["record_type"])) {
    if(IS_LIVE) {
        $returnArray["code"] = "PARAMS";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$data = cleansingParams($data);

$record_type = preg_replace("/[^A-Za-z]+/u", "", $data["record_type"]);
$record_id   = Record::getMasterIdByName($record_type);

if(!$record_id) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "올바르지 않은 값입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$ranking = Record::getRankingByTypeId($record_id);

if(!$ranking) {
    $returnArray = array(
        "code" => "EMPTY",
        "msg"  => "데이터가 없습니다."
    );
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

foreach($ranking as $value) {
    $returnArray["data"][] = array(
        "weight"    => $value["weight"],
        "nickname"  => $value["user_nickname"],
        "record_id" => $value["id"]
    );
}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
