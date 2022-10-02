<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

$data = cleansingParams($data);


foreach($data as $key => $value) {
    $update_sql = "
        Update  play_team_master
        Set     team_name = :team_name
        Where   id = :id
    ";
    $param = array(
        ":team_name" => $value,
        ":id" => $key
    );

    $PDO -> execute($update_sql, $param);
}


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>