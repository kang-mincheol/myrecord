<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

if(!$is_member) {
    $returnArray["code"] = "MEMBER_ONLY";
    $returnArray["msg"] = "로그인 후 이용해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$data        = $_POST;
$record_type = preg_replace('/[^0-9]+/u', '', $data['record_type'] ?? '');

if($record_type === '') {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "등록할 기록을 선택해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 유효한 종목인지 확인
$valid_ids = array_column(Record::getRecordType(), 'id');
if(!in_array($record_type, $valid_ids)) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "잘못된 파라미터입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 동일 종목 심사전/심사중 중복 신청 확인
if(Record::checkOverlapRequest($member["id"], $record_type, 0)) {
    $returnArray["code"] = "OVERLAP_REQUEST";
    $returnArray["msg"] = "해당 종목으로 심사전 신청건이 존재합니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}
if(Record::checkOverlapRequest($member["id"], $record_type, 1)) {
    $returnArray["code"] = "OVERLAP_REQUEST";
    $returnArray["msg"] = "해당 종목으로 관리자가 심사중인 건이 존재합니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 무게 검증
$record_weight = preg_replace('/[^0-9]+/u', '', $data['record_weight'] ?? '');
if($record_weight === '') {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "등록할 무게를 입력해 주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}
if($record_weight > 9999) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "Record 무게를 확인해 주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$record_memo = isset($data['record_memo']) ? trim($data['record_memo']) : '';
if(mb_strlen($record_memo) > 500) {
    $record_memo = mb_substr($record_memo, 0, 500);
}

// 파일 검증
$accessType = ["video/mp4","video/m4v","video/avi","video/wmv","video/mwa","video/asf","video/mpg","video/mpeg","video/mkv","video/mov","video/3gp","video/3g2","video/webm","video/quicktime","application/octet-stream","image/jpeg","image/jpg","image/png"];

if(!$_FILES) {
    $returnArray["code"] = "FILE_EMPTY";
    $returnArray["msg"] = "파일을 등록해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$total_file_size = 0;
foreach($_FILES as $key => $value) {
    if(!in_array($value["type"], $accessType)) {
        $returnArray["code"] = "FILE_TYPE_LIMIT";
        $returnArray["msg"] = "파일은 이미지 또는 동영상 파일만 업로드 가능합니다<br/>이미지 또는 동영상 파일이 업로드가 안될경우 고객센터에 문의부탁드립니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
    $total_file_size += $value["size"];
}

if(($total_file_size / 1024 / 1024) > 100) {
    $returnArray["code"] = "FILE_SIZE_LIMIT";
    $returnArray["msg"] = "파일은 총 100MB 이하로 업로드 해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 기록 신청 Insert
$request_id = Record::insertRecordRequest($member["id"], (int)$record_type, (int)$record_weight, $record_memo);

// 파일 Insert + 물리 저장
$videoType   = ["video/mp4","video/m4v","video/avi","video/wmv","video/mwa","video/asf","video/mpg","video/mpeg","video/mkv","video/mov","video/3gp","video/3g2","video/webm","video/quicktime","application/octet-stream"];
$upload_path = $_SERVER["DOCUMENT_ROOT"] . "/data/record/";

if(!is_dir($_SERVER["DOCUMENT_ROOT"] . "/data"))        @mkdir($_SERVER["DOCUMENT_ROOT"] . "/data");
if(!is_dir($_SERVER["DOCUMENT_ROOT"] . "/data/record")) @mkdir($_SERVER["DOCUMENT_ROOT"] . "/data/record");

foreach($_FILES as $value) {
    $GUID = makeGuid();
    $ext  = "";
    if(in_array($value["type"], $videoType)) {
        if($value["type"] === "video/quicktime") $value["type"] = "video/mp4";
        $ext = "." . explode("/", $value["type"])[1];
    }
    $file_guid = $GUID . $ext;

    Record::insertRecordFile($request_id, $value["name"], $file_guid, $value["type"]);
    move_uploaded_file($value["tmp_name"], $upload_path . $file_guid);
}

Slack::send(SLACK_URL_RECORD_INSERT, "record 신규 신청\n{$_SERVER["REQUEST_SCHEME"]}://{$_SERVER["HTTP_HOST"]}/record/squat/list/");

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
