<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["record_type"])) {
    if(IS_LIVE) {
        $returnArray["code"] = "PARAMS";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$data = cleansingParams($data);

$record_type = preg_replace("/[^A-Za-z]+/u", "", $data["record_type"]);

$record_id_sql = "
    Select  id
    From    tb_record_master
    Where   record_name = :record_name
";
$param = array(
    ":record_name" => $record_type
);
$record_id = $PDO -> fetch($record_id_sql, $param);

if(!$record_id) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "올바르지 않은 값입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$record_id = $record_id["id"];


$ranking_sql = "
    Select	max(T1.record_weight) as weight, T1.account_id, T1.id, T2.user_nickname
    From	tb_record_request T1
    Inner Join Account T2
    On  T1.account_id = T2.id
    Where	T1.record_type = :record_type
    And		T1.status = 2

    Group by T1.account_id, T1.id

    Order by weight Desc
    Limit 0, 10
";
$param = array(
    ":record_type" => $record_id
);
$ranking = $PDO -> fetchAll($ranking_sql, $param);

foreach($ranking as $key => $value) {
    $returnArray["data"][] = array(
        "weight" => $value["weight"],
        "nickname" => $value["user_nickname"],
        "record_id" => $value["id"]
    );
}


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>