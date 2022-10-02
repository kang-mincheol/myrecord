<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$team_sql = "
    Select  T1.id, T1.team_name, IfNull(T2.team_score, 0) as team_score
    From    play_team_master T1
    Left Outer Join play_team_score T2
    On  T1.id = T2.team_id
";
$team_data = $PDO -> fetchAll($team_sql);

$returnArray["team"] = $team_data;



$person_sql = "
    Select  *
    From    play_team_person
    Order by id Asc
";

$person_data = $PDO -> fetchAll($person_sql);
$returnArray["person"] = $person_data;




echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>