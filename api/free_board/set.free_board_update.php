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

if (is_null($data) || !checkParams($data, ["id", "title", "contents"])) {
    $returnArray["code"] = "PARAMS";
    $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = cleansingParams($data);

// 해당 id 값으로 글이 존재하는지 확인
$hasFreeBoard = FreeBoard::hasFreeBoard($data["id"]);
if ($hasFreeBoard === false) {
	$returnArray["code"] = "EMPTY";
	$returnArray["msg"] = "존재하지 않는 글입니다.";
	echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 본인이 작성한 글인지 체크
$writerCheck = FreeBoard::writerVerify($data["id"]);
if ($writerCheck === false) {
	$returnArray["code"] = "WRITER_ERROR";
	$returnArray["msg"] = "작성자 본인이 아닙니다.";
	echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 빈값 체크
if (empty($data["title"])) {
    $returnArray["code"] = "TITLE";
    $returnArray["msg"] = "제목을 입력해 주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
} else if (empty($data["contents"]) || $data["contents"] === "<p> </p>") {
	$returnArray["code"] = "CONTENTS";
	$returnArray["msg"] = "내용을 입력해 주세요.";
	echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 업데이트 처리
$freeBoardData = FreeBoard::getFreeBoard($data["id"]);
$freeBoardData["title"] = $data["title"];
$freeBoardData["contents"] = $data["contents"];
$update = FreeBoard::updateFreeBoard($freeBoardData);

if ($update === false) {
	$returnArray["code"] = "UPDATE_ERROR";
	$returnArray["msg"] = "자유게시판 수정에 실패했습니다.";
	echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>