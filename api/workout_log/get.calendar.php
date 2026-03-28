<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

if (!$is_member) {
    echo json_encode(["code" => "MEMBER_ONLY", "msg" => "로그인 후 이용해주세요"], JSON_UNESCAPED_UNICODE);
    exit;
}

$data  = json_decode(file_get_contents('php://input'), true) ?? [];
$year  = (int)($data['year']  ?? date('Y'));
$month = (int)($data['month'] ?? date('n'));

if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
    echo json_encode(["code" => "PARAM_ERROR", "msg" => "잘못된 파라미터입니다"], JSON_UNESCAPED_UNICODE);
    exit;
}

$logs = WorkoutLog::getMonthDates($member['id'], $year, $month);

$workout_map = [];
foreach ($logs as $log) {
    $date = $log['workout_date'];
    if (!isset($workout_map[$date])) {
        $workout_map[$date] = [
            'id'    => (int)$log['id'],
            'title' => $log['title'] ?? '',
            'count' => 1,
        ];
    } else {
        $workout_map[$date]['count']++;
    }
}

echo json_encode([
    "code"        => "SUCCESS",
    "year"        => $year,
    "month"       => $month,
    "total_count" => count($logs),
    "data"        => $workout_map,
], JSON_UNESCAPED_UNICODE);
exit;
