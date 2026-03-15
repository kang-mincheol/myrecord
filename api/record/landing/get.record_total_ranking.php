<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$total_ranking = Record::getTotalRanking();

if(!$total_ranking) {
    $returnArray = array(
        "code" => "EMPTY",
        "msg"  => "데이터가 없습니다"
    );
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

foreach($total_ranking as $value) {
    $returnArray["data"][] = array(
        "3대"                  => $value["total_sum"],
        "squat"                => $value["squat"],
        "squat_record_id"      => $value["squat_id"],
        "benchpress"           => $value["bench"],
        "benchpress_record_id" => $value["bench_id"],
        "deadlift"             => $value["dead"],
        "deadlift_record_id"   => $value["dead_id"],
        "nickname"             => $value["user_nickname"]
    );
}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
