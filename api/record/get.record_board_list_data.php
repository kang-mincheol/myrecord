<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["record_type"])) {
    if(IS_LIVE) {
        $returnArray["code"] = "PARAMS";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$data = cleansingParams($data);

// record_type 파라미터 유효성 확인
$master = Record::getMasterByNameLower($data["record_type"]);
if(!$master) {
    $returnArray["code"] = "RECORD_TYPE_ERROR";
    $returnArray["msg"] = "잘못된 값 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}
$record_id = (int)$master["id"];

// 페이징
$page      = empty($data["page"]) ? 1 : (int)$data["page"];
$rows      = 10;
$start_row = ($page - 1) * $rows;

// 검색 키/키워드 유효성
$search_key     = $data["search_key"]     ?? '';
$search_keyword = $data["search_keyword"] ?? '';

if($search_key && $search_keyword) {
    if(strlen($search_keyword) < 1) {
        $returnArray["code"] = "KEYWORD_ERROR";
        $returnArray["msg"] = "검색시 두글자 이상 입력해 주세요";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
    if(!in_array($search_key, ["nickname", "weight"])) {
        $returnArray["code"] = "SEARCH_KEY_ERROR";
        $returnArray["msg"] = "잘못된 값 입니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

// 목록 조회
$list = Record::getBoardList($record_id, $start_row, $rows, $search_key, $search_keyword);

if(!$list) {
    $returnArray["code"] = "EMPTY";
    $returnArray["msg"] = "데이터가 없습니다<br/>데이터가 잘못된 경우 고객센터에 문의해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

foreach($list as $value) {
    $returnArray["data"][] = array(
        "record_id"     => $value["id"],
        "nickname"      => $value["user_nickname"],
        "record_weight" => $value["record_weight"],
        "record_status" => $value["status_text"],
        "date"          => date("Y.m.d", strtotime($value["create_datetime"]))
    );
}

// 페이징 데이터
$total_count = Record::getBoardCount($record_id);
$max_page    = (int)ceil($total_count / $rows);
$start_page  = (int)floor(($page - 1) / $rows) * $rows;

$page_arr = [];
for ($i = 1; $i <= 10; $i++) {
    $this_page = $start_page + $i;
    if ($this_page <= $max_page) {
        $page_arr[] = $this_page;
    }
}
$returnArray["page"] = $page_arr;

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
