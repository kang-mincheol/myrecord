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
    Insert Into play_team_person
    Set
        team_id = :team_id,
        person_name = :person_name
";
$param = array(
    ":team_id" => $data["team_id"],
    ":person_name" => $data["person"]
);
$PDO -> execute($person_sql, $param);


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>