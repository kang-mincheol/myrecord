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

$record_id = preg_replace("/[^0-9]+/u", "", $data["record_id"]);

$record_sql = "
    Select  T4.user_nickname, T2.record_name, T1.record_weight, T3.status_text, DATE_FORMAT(T1.create_date, '%Y.%m.%d') as create_date
    From    tb_record_request T1

    Inner Join	tb_record_master T2
    On	T1.record_type = T2.id

    Inner Join	tb_record_status_master T3
    On	T1.status = T3.id

    Inner Join  Account T4
    On  T1.account_id = T4.id

    Where   T1.id = :id
";
$param = array(
    ":id" => $record_id
);
$record_data = $PDO -> fetch($record_sql, $param);

if(!$record_data) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "올바르지 않은 데이터입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$returnArray["data"] = array(
    "record_nickname" => $record_data["user_nickname"],
    "record_name" => $record_data["record_name"],
    "record_weight" => $record_data["record_weight"],
    "record_status" => $record_data["status_text"],
    "record_create" => $record_data["create_date"]
);

$record_file_sql = "
    Select  *
    From    tb_record_request_file
    Where   request_id = :request_id
";
$param = array(
    ":request_id" => $record_id
);
$record_file_data = $PDO -> fetchAll($record_file_sql, $param);

if($record_file_data) {
    foreach($record_file_data as $row) {
        $returnArray["file"][] = array(
            "file_name" => $row["file_guid"]
        );
    }
} else {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "올바르지 않은 값입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}




echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>