<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["pageIndex", "pageRow"])) {
    $returnArray["code"] = "PARAMS";
    $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = cleansingParams($data);

$freeBoardListData = FreeBoard::getFreeBoardList($data);

if (count($freeBoardListData) === 0) {
    $returnArray["code"] = "EMPTY";
    $returnArray["msg"] = "검색결과가 없습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

foreach($freeBoardListData as $key => $value) {
    $returnArray["data"][] = array(
        "id" => $value["id"],
        "title" => $value["title"],
        "nickname" => $value["user_nickname"],
        "view_count" => $value["view_count"],
        "write_date" => date("Y.m.d", strtotime($value["create_date"]))
    );
}


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>