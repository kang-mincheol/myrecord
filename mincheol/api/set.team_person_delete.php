<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

$data = cleansingParams($data);


$person_sql = "
    Delete From play_team_person
    Where   id = :id
";

$param = array(
    ":id" => $data["person_id"]
);
$PDO -> execute($person_sql, $param);


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>