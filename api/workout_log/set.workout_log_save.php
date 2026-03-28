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

$data = json_decode(file_get_contents('php://input'), true);

if(is_null($data) || !checkParams($data, ["workout_date", "exercises"])) {
    $returnArray["code"] = "PARAMS";
    $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 제목 (필수)
$title = isset($data["title"]) ? mb_substr(trim($data["title"]), 0, 100) : '';
if($title === '') {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"]  = "제목을 입력해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 운동 날짜 검증
$workout_date = trim($data["workout_date"] ?? '');
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $workout_date)) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"]  = "운동 날짜를 올바르게 입력해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 운동 시간 (선택)
$workout_duration = isset($data["workout_duration"]) && $data["workout_duration"] !== '' && $data["workout_duration"] !== null
    ? (int)preg_replace("/[^0-9]+/u", "", $data["workout_duration"])
    : null;

if($workout_duration !== null && ($workout_duration < 1 || $workout_duration > 1440)) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"]  = "운동 시간은 1분 ~ 1440분 사이로 입력해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 메모 (선택)
$memo = isset($data["memo"]) ? trim($data["memo"]) : '';
if(mb_strlen($memo) > 500) {
    $memo = mb_substr($memo, 0, 500);
}

// 무게 단위 (선택, 기본 kg)
$weight_unit = isset($data["weight_unit"]) && $data["weight_unit"] === 'lb' ? 'lb' : 'kg';

// 종목 검증
$exercises = $data["exercises"] ?? [];
if(empty($exercises) || !is_array($exercises)) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"]  = "운동 종목을 1개 이상 입력해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if(count($exercises) > 20) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"]  = "운동 종목은 최대 20개까지 입력 가능합니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

foreach($exercises as $idx => $ex) {
    $exName = trim($ex["exercise_name"] ?? '');
    if($exName === '') {
        $returnArray["code"] = "PARAM_ERROR";
        $returnArray["msg"]  = ($idx + 1) . "번째 종목명을 입력해주세요";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
    $sets = $ex["sets"] ?? [];
    if(empty($sets) || !is_array($sets)) {
        $returnArray["code"] = "PARAM_ERROR";
        $returnArray["msg"]  = $exName . " 종목의 세트를 1개 이상 입력해주세요";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
    foreach($sets as $sIdx => $set) {
        $weight = $set["weight"] ?? '';
        $reps   = $set["reps"] ?? '';
        if($weight === '' || $reps === '') {
            $returnArray["code"] = "PARAM_ERROR";
            $returnArray["msg"]  = $exName . " " . ($sIdx + 1) . "세트의 무게/횟수를 입력해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
        }
    }
}

// 수정 or 신규
$log_id = isset($data["log_id"]) ? (int)preg_replace("/[^0-9]+/u", "", $data["log_id"]) : 0;

if($log_id > 0) {
    // 수정
    $existing = WorkoutLog::getById($log_id);
    if(!$existing) {
        $returnArray["code"] = "NOT_FOUND";
        $returnArray["msg"]  = "데이터를 찾을 수 없습니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
    if((int)$existing["account_id"] !== (int)$member["id"]) {
        $returnArray["code"] = "FORBIDDEN";
        $returnArray["msg"]  = "접근 권한이 없습니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    WorkoutLog::updateLog($log_id, $title, $workout_date, $workout_duration, $memo, $weight_unit);
    WorkoutLog::deleteExercisesAndSets($log_id);
} else {
    // 신규
    $log_id = WorkoutLog::insertLog($member["id"], $title, $workout_date, $workout_duration, $memo, $weight_unit);
}

// 종목 + 세트 저장
foreach($exercises as $orderNo => $ex) {
    $exName     = mb_substr(trim($ex["exercise_name"]), 0, 100);
    $exerciseId = WorkoutLog::insertExercise($log_id, $exName, $orderNo + 1);
    foreach($ex["sets"] as $sIdx => $set) {
        $weight = (float)preg_replace("/[^0-9.]+/u", "", $set["weight"] ?? '0');
        $reps   = (int)preg_replace("/[^0-9]+/u", "", $set["reps"] ?? '0');
        WorkoutLog::insertSet($exerciseId, $sIdx + 1, $weight, $reps);
    }
}

$returnArray["log_id"] = $log_id;
echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
