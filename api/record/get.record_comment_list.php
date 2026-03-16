<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

$recordId = isset($_GET["record_id"]) ? (int)$_GET["record_id"] : 0;

if ($recordId <= 0) {
    $returnArray["code"] = "PARAMS";
    $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if (!Record::getRecordById($recordId)) {
    $returnArray["code"] = "NOT_FOUND";
    $returnArray["msg"] = "기록을 찾을 수 없습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$comments = Record::getComments($recordId);
$count    = Record::getCommentCount($recordId);

$list = [];
foreach ($comments as $c) {
    $list[] = [
        "id"              => (int)$c["id"],
        "contents"        => $c["contents"],
        "user_nickname"   => $c["user_nickname"],
        "create_datetime" => date("Y.m.d H:i", strtotime($c["create_datetime"])),
        "is_mine"         => $is_member ? ((int)$member["id"] === (int)$c["account_no"]) : false,
    ];
}

$returnArray["data"] = [
    "count" => $count,
    "list"  => $list,
];

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
