<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$team_data_sql = "
    Select  *
    From    play_team_master
    Order by team_name Asc
";

$team_data = $PDO -> fetchAll($team_data_sql);

$returnArray["data"]["team"] = $team_data;


$team_score_sql = "
    Select  *
    From    play_team_score
";
$team_score_data = $PDO -> fetchAll($team_score_sql);

$returnArray["data"]["score"] = $team_score_data;


$person_sql = "
    Select  *
    From    play_team_person
    Order by id Desc
";
$person_data = $PDO -> fetchAll($person_sql);
$returnArray["data"]["person"] = $person_data;





echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>