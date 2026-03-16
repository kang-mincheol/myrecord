<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = ["code" => "SUCCESS", "msg" => "댓글이 등록되었습니다."];

if (!$is_member) {
    $returnArray["code"] = "LOGIN_REQUIRED";
    $returnArray["msg"] = "로그인 후 이용해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["record_id", "contents"])) {
    $returnArray["code"] = "PARAMS";
    $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = cleansingParams($data);

$recordId = (int)$data["record_id"];
$contents = trim($data["contents"]);

if ($recordId <= 0 || !Record::getRecordById($recordId)) {
    $returnArray["code"] = "NOT_FOUND";
    $returnArray["msg"] = "기록을 찾을 수 없습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if (mb_strlen($contents) === 0) {
    $returnArray["code"] = "EMPTY_CONTENTS";
    $returnArray["msg"] = "댓글 내용을 입력해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if (mb_strlen($contents) > 500) {
    $returnArray["code"] = "TOO_LONG";
    $returnArray["msg"] = "댓글은 500자 이내로 입력해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$commentId = Record::insertComment($recordId, $contents);

$returnArray["comment_id"] = $commentId;

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
