<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다",
);

if(!$is_member) {
    $returnArray["code"] = "MEMBER_ONLY";
    $returnArray["msg"] = "로그인 후 이용해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["title", "contents"])) {
  $returnArray["code"] = "PARAMS";
  $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
  echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = cleansingParams($data);

// 빈값 체크
if (empty($data["title"])) {
  $returnArray["code"] = "TITLE";
  $returnArray["msg"] = "제목을 입력해 주세요.";
  echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
} else if (empty($data["contents"]) || $data["contents"] === "<p> </p>") {
  $returnArray["code"] = "CONTENT";
  $returnArray["msg"] = "내용을 입력해 주세요.";
  echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$insert = FreeBoard::insertFreeBoard($data);

$returnArray["board_id"] = $insert;



echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>