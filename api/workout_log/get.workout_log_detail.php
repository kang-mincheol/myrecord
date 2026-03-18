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

if(is_null($data) || !checkParams($data, ["log_id"])) {
    $returnArray["code"] = "PARAMS";
    $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data   = cleansingParams($data);
$log_id = (int)preg_replace("/[^0-9]+/u", "", $data["log_id"]);

$log = WorkoutLog::getById($log_id);

if(!$log) {
    $returnArray["code"] = "NOT_FOUND";
    $returnArray["msg"]  = "데이터를 찾을 수 없습니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 본인 데이터만 조회 가능
if((int)$log["account_id"] !== (int)$member["id"]) {
    $returnArray["code"] = "FORBIDDEN";
    $returnArray["msg"]  = "접근 권한이 없습니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$exercises = WorkoutLog::getDetail($log_id);

$returnArray["data"] = array(
    "id"               => $log["id"],
    "workout_date"     => $log["workout_date"],
    "workout_duration" => $log["workout_duration"],
    "weight_unit"      => $log["weight_unit"] ?? 'kg',
    "memo"             => $log["memo"] ?? '',
    "exercises"        => $exercises,
);

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
