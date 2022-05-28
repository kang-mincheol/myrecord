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


$record_master_data_sql = "
    Select  *
    From    tb_record_master
";
$record_master_data = $PDO -> fetchAll($record_master_data_sql);


//record_type 파리미터 체크
$param_check = false;
$record_id = "";
foreach($record_master_data as $key => $value) {
    if($data["record_type"] == $value["record_name_lower"]) {
        $param_check = true;
        $record_id = $value["id"];
        break;
    }
}

if(!$param_check || $record_id == "") {
    $returnArray["code"] = "RECORD_TYPE_ERROR";
    $returnArray["msg"] = "잘못된 값 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//페이징 처리
$start_row = 0;
if(empty($data["page"])) {
    $start_row = 0;
} else {
    if($data["page"] == 1) {
        $start_row = 0;
    } else {
        $start_row = ($data["page"] - 1) * 10;
    }
}
$rows = 10;


$param = array();
//검색 쿼리
$and_query = "";
if($data["search_key"] && $data["search_value"]) {
    if(strlen($data["search_value"]) < 1) {
        $returnArray["code"] = "KEYWORD_ERROR";
        $returnArray["msg"] = "검색시 두글자 이상 입력해주세요";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    if($data["search_key"] == "nickname") {
        $and_query = "
            And account_id = (
                Select  id
                From    Account
                Where   user_nickname like :keyword
            )
        ";
        $param[":keyword"] = "%{$data["search_value"]}%";
    } else if($data["search_key"] == "weight") {
        $keyword = $data["search_value"];
        $keyword = preg_replace("/[^0-9]/u", "", $keyword);
        $and_query = "
            And record_weight = :keyword
        ";
        $param[":keyword"] = $keyword;
    } else {
        $returnArray["code"] = "SEARCH_KEY_ERROR";
        $returnArray["msg"] = "잘못된 값 입니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$get_record_list_sql = "
    Select  T1.id, T1.record_type, T1.record_weight, T1.status, T1.create_date, T2.user_nickname, T3.status_text
    From    tb_record_request T1
    Inner Join  Account T2
    On  T1.account_id = T2.id
    Inner Join  tb_record_status_master T3
    On  T1.status = T3.id

    Where   record_type = :record_type
    {$and_query}
    And     is_delete = 0
    Order by create_date Desc
    Limit   {$start_row}, {$rows}
";

$param[":record_type"] = $record_id;

$get_record_list = $PDO -> fetchAll($get_record_list_sql, $param);

if(!$get_record_list) {
    $returnArray["code"] = "EMPTY";
    $returnArray["msg"] = "데이터가 없습니다<br/>데이터가 잘못된 경우 고객센터에 문의해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}


foreach($get_record_list as $key => $value) {
    $returnArray["data"][] = array(
        "record_id" => $value["id"],
        "nickname" => $value["user_nickname"],
        "record_weight" => $value["record_weight"],
        "record_status" => $value["status_text"],
        "date" => date("Y.m.d", strtotime($value["create_date"]))
    );
}



//페이징 데이터
$start_page = 0;
if(empty($data["page"])) {
    $start_page = 0;
} else {
    $start_page = floor($data["page"] / 10);

    if($start_page == 0) {
        $start_page = 0;
    } else {
        $start_page = parseInt($start_page."0");
    }
}

$total_count_sql = "
    Select  count(*) as cnt
    From    tb_record_request
    Where   record_type = :record_type
";
$param = array(
    ":record_type" => $record_id
);

$total_count = $PDO -> fetch($total_count_sql, $param);
$total_count = $total_count["cnt"];

$max_page = floor($total_count / 10) + 1;

$page_arr = array();
for ($i = 1; $i <= 10; $i++) {
    $this_page = $start_page + $i;

    if ($this_page <= $max_page) {
        $page_arr[] = $this_page;
    }
}

$returnArray["page"] = $page_arr;





echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>