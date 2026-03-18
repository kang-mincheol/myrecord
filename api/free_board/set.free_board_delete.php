<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = ["code" => "SUCCESS", "msg" => "게시글이 삭제되었습니다."];

if (!$is_member) {
    $returnArray["code"] = "LOGIN_REQUIRED";
    $returnArray["msg"] = "로그인 후 이용해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["board_id"])) {
    $returnArray["code"] = "PARAMS";
    $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$boardId = (int)$data["board_id"];

$result = FreeBoard::deleteBoard($boardId);

echo json_encode($result, JSON_UNESCAPED_UNICODE); exit;
?>
