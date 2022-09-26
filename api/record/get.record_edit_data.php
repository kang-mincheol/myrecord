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
    $returnArray["msg"] = "로그인 후 이용해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if(!is_number($data["record_id"])) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$record_id = preg_replace("/[^0-9]+/u", "", $data["record_id"]);

$query = "
    Select  *
    From    tb_record_request
    Where   id = :id
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

//본인 글이 아닌경우 원본 데이터 get 불가
if($member["id"] != $record_data["account_id"]) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.<br/>-3";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if($record_data["status"] == '2') {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "승인 완료된 마이레코드는 수정이 불가합니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
} else if($record_data["status"] == '1') {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "현재 심사중으로 수정이 불가합니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}


$returnArray["data"] = array(
    "type" => $record_data["record_type"],
    "weight" => $record_data["record_weight"],
    "status" => $record_data["status"]
);


// 파일 데이터 get
$query = "
    Select  *
    From    tb_record_request_file
    Where   request_id = :request_id
";

$param = array(
    ":request_id" => $record_data["id"]
);

$record_file_data = $PDO -> fetchAll($query, $param);

if($record_file_data) {
    foreach($record_file_data as $row) {
        $returnArray["data"]["file"][] = array(
            "file_name" => $row["file_original_name"],
            "file_id" => $row["file_guid"],
            "file_no" => $row["id"],
            "file_type" => $row["file_type"]
        );
    }
}


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>