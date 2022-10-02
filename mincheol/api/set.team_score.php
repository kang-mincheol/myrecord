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
        Insert Into play_team_score
        Set     team_id = :team_id_1,
                team_score = :team_score_1
        On Duplicate Key Update
                team_score = :team_score_2
    ";
    $param = array(
        ":team_score_1" => $value,
        ":team_id_1" => $key,
        ":team_score_2" => $value,
    );

    $PDO -> execute($update_sql, $param);
}


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>