<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["boardId"])) {
  $returnArray["code"] = "PARAMS";
  $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
  echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = cleansingParams($data);

$freeBoardViewData = FreeBoard::getFreeBoardViewData($data["boardId"]);

$returnArray["data"] = array(
  "title" => $freeBoardViewData["title"],
  "contents" => stripslashes($freeBoardViewData["contents"]),
  "user_nickname" => $freeBoardViewData["user_nickname"],
  "create_date" => $freeBoardViewData["create_date"]
);

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>