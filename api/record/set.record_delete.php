<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

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
    $returnArray["msg"] = "로그인 후 이용해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if(!is_number($data["record_id"])) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$record_id = preg_replace("/[^0-9]+/u", "", $data["record_id"]);

$query = "
    Select  *
    From    tb_record_request
    Where   id = :id
";

$param = array(
    ":id" => $record_id
);

$record_data = $PDO -> fetch($query, $param);

if(!$record_data) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.<br/>-2";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//본인 글이 아닌경우 삭제 불가
if($member["id"] != $record_data["account_id"]) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "잘못된 값 입니다.<br/>-3";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//본인일경우 삭제

//등록한 파일 삭제
$get_file_query = "
    Select  *
    From    tb_record_request_file
    Where   request_id = :request_id
    Order by id Asc
";

$param = array(
    ":request_id" => $record_id
);

$file_data = $PDO -> fetchAll($get_file_query, $param);

if($file_data) {
    foreach($file_data as $row) {
        unlink($_SERVER["DOCUMENT_ROOT"]."/data/record/".$row["file_guid"]);
        $file_delete_query = "
            Delete From tb_record_request_file
            Where   id = :id
        ";
        $param = array(
            ":id" => $row["id"]
        );
        $PDO -> execute($file_delete_query, $param);
    }
}


//심사 데이터 삭제
$get_inspection_query = "
    Select  *
    From    tb_record_inspection
    Where   request_id = :request_id
    Order by inspection_id Asc
";
$param = array(
    ":request_id" => $record_id
);
$inspection_data = $PDO -> fetchAll($get_inspection_query, $param);

if($inspection_data) {
    foreach($inspection_data as $row) {
        $inspection_delete_query = "
            Delete From tb_record_inspection
            Where   inspection_id = :inspection_id
        ";
        $param = array(
            ":inspection_id" => $row["inspection_id"]
        );
        $PDO -> execute($inspection_delete_query, $param);
    }
}


//등록한 데이터 삭제
$request_delete_query = "
    Delete From tb_record_request
    Where   id = :id
";
$param = array(
    ":id" => $record_id
);
$PDO -> execute($request_delete_query, $param);




echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>