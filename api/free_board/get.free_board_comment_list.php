<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

$boardId = isset($_GET["board_id"]) ? (int)$_GET["board_id"] : 0;

if ($boardId <= 0) {
    $returnArray["code"] = "PARAMS";
    $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if (!FreeBoard::hasFreeBoard($boardId)) {
    $returnArray["code"] = "NOT_FOUND";
    $returnArray["msg"] = "게시글을 찾을 수 없습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$comments = FreeBoard::getComments($boardId);
$count    = FreeBoard::getCommentCount($boardId);

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
    "count"   => $count,
    "list"    => $list,
];

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
