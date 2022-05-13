<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);


if(!$is_member) {
    $returnArray["code"] = "MEMBER_ONLY";
    $returnArray["msg"] = "로그인 후 이용해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}



$record_master_data_sql = "
    Select  *
    From    tb_record_master
";

$record_master_data = $PDO -> fetchAll($record_master_data_sql);

foreach($record_master_data as $key => $value) {
    $record_data_sql = "
        Select  *
        From    tb_record_request
        Where   account_id = :account_id
        And     record_type = :record_type
        Order by id Desc
    ";
    $param = array(
        ":account_id" => $member["id"],
        ":record_type" => $value["id"]
    );
    $record_data = $PDO -> fetch($record_data_sql, $param);
    if($record_data) {
        $status_text = "";
        $status_color = "";
        if($record_data["status"] == 0) {
            $status_text = "신청 완료";
            $status_color = "blue";
        } else if($record_data["status"] == 1) {
            $status_text = "심사중";
            $status_color = "blue";
        } else if($record_data["status"] == 2) {
            $status_text = "심사 완료";
            $status_color = "black";
        } else if($record_data["status"] == 9) {
            $status_text = "심사 반려";
            $status_color = "red";
        }

        $returnArray["data"][] = array(
            "type" => $value["id"],
            "type_name" => $value["record_name"],
            "weight" => $record_data["record_weight"]."KG",
            "status" => $status_text,
            "status_color" => $status_color
        );
    } else {
        $returnArray["data"][] = array(
            "type" => $value["id"],
            "type_name" => $value["record_name"]
        );
    }
}



echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>