<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

function generateCertCode(int $id): string {
    $raw = strtoupper(substr(hash('sha256', 'mr_cert_f7e2_' . $id), 0, 16));
    return implode('-', str_split($raw, 4));
}

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["record_id"])) {
    if(IS_LIVE) {
        $returnArray["code"] = "PARAMS";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$data = cleansingParams($data);

if(!$is_member) {
    $returnArray["code"] = "MEMBER_ONLY";
    $returnArray["msg"] = "로그인 후 이용해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if(!is_number($data["record_id"])) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$record_id   = (int)preg_replace("/[^0-9]+/u", "", $data["record_id"]);
$record_data = Record::getCertificateData($record_id);

if(!$record_data) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.<br/>-2";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 본인 글이 아닌 경우 접근 불가
if($member["id"] != $record_data["account_id"]) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.<br/>-3";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

// 승인 상태 확인
if($record_data["status"] != 2) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "마이레코드 인증서는 승인 후 확인가능합니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$cert_code = generateCertCode($record_id);

$returnArray["data"] = array(
    "record_id"     => $record_id,
    "nickname"      => $record_data["user_nickname"],
    "record_type"   => $record_data["record_name_ko"],
    "record_weight" => $record_data["record_weight"] . "KG",
    "date"          => $record_data["certificate_datetime"]
        ? date("Y.m.d", strtotime($record_data["certificate_datetime"]))
        : date("Y.m.d", strtotime($record_data["request_datetime"])),
    "cert_code"     => $cert_code
);

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
