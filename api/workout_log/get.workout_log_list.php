<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code" => "SUCCESS",
    "msg"  => "정상 처리되었습니다"
);

if(!$is_member) {
    $returnArray["code"] = "MEMBER_ONLY";
    $returnArray["msg"]  = "로그인 후 이용해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?? [];
$data = cleansingParams($data);

$page      = max(1, (int)($data['page'] ?? 1));
$page_size = 10;
$start_row = ($page - 1) * $page_size;

$list        = WorkoutLog::getList($member["id"], $start_row, $page_size);
$total_count = WorkoutLog::getCount($member["id"]);

if(empty($list)) {
    $returnArray["code"] = "EMPTY";
    $returnArray["msg"]  = "등록된 득근일지가 없습니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$returnArray["total_count"] = $total_count;
$returnArray["page"]        = $page;
$returnArray["page_size"]   = $page_size;
$returnArray["data"]        = [];

foreach($list as $row) {
    $returnArray["data"][] = array(
        "id"               => $row["id"],
        "workout_date"     => $row["workout_date"],
        "workout_duration" => $row["workout_duration"],
        "memo"             => $row["memo"] ?? '',
        "exercise_count"   => $row["exercise_count"],
        "exercise_summary" => $row["exercise_summary"] ?? '',
        "create_datetime"  => $row["create_datetime"],
    );
}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
