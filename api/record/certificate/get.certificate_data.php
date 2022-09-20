<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["record_id"])) {
    if(IS_LIVE) {
        $returnArray["code"] = "PARAMS";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$data = cleansingParams($data);

if(!$is_member) {
    $returnArray["code"] = "MEMBER_ONLY";
    $returnArray["msg"] = "로그인 후 이용해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if(!is_number($data["record_id"])) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$record_id = preg_replace("/[^0-9]+/u", "", $data["record_id"]);

$query = "
    Select  T1.account_id, T1.status, T2.user_nickname, T1.record_weight, T3.record_name_ko, T4.create_datetime
    From    tb_record_request T1
    Inner Join (
        Select  id, user_nickname
        From    Account
    ) T2
    On  T1.account_id = T2.id
    Inner Join  tb_record_master T3
    On  T1.record_type = T3.id
    Left Outer Join tb_record_inspection T4
    On  T1.id = T4.request_id
    And     T4.change_status = '2'
    Where   T1.id = :id
";

$param = array(
    ":id" => $record_id
);

$record_data = $PDO -> fetch($query, $param);

if(!$record_data) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.<br/>-2";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//본인 글이 아닌경우 데이터 get 불가
if($member["id"] != $record_data["account_id"]) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.<br/>-3";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//승인 상태인지 확인
if($record_data["status"] != 2) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "마이레코드 인증서는 승인 후 확인가능합니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}



//success
$returnArray["data"] = array(
    "nickname" => $record_data["user_nickname"],
    "record_type" => $record_data["record_name_ko"],
    "record_weight" => $record_data["record_weight"]."KG",
    "date" => $record_data["create_datetime"] ? date("Y.m.d", $record_data["create_datetime"]) : "-"
);


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>