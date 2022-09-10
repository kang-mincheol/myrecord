<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

if(!$is_member) {
    $returnArray["code"] = "MEMBER_ONLY";
    $returnArray["msg"] = "로그인 후 이용해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$returnArray["data"] = array(
    "account_id" => substr($member["user_id"], 0, -3)."***",
    "account_nickname" => $member["user_nickname"],
    "account_name" => $member["user_name"],
    "account_phone" => $member["user_phone"],
    "account_email" => $member["user_email"]
);



echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>