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




$data = $_POST;
$record_type = $data['record_type'];

$record_type = preg_replace('/[^0-9]+/u', '', $record_type);

if($record_type == '') {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "등록할 기록을 선택해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}


$record_type_sql = "
    Select  *
    From    tb_record_master
";

$record_type_arr = array();
$record_type_data = $PDO -> fetchAll($record_type_sql);

foreach($record_type_data as $key => $value) {
    $record_type_arr[] = $value["id"];
}

if(!in_array($record_type, $record_type_arr)) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "잘못된 파라미터입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}


//해당 회원 해당 종목으로 심사전 상태 데이터가 존재하는지 확인
$overlap_check_sql = "
    Select  *
    From    tb_record_request
    Where   account_id = :account_id
    And     record_type = :record_type
    And     status = :status
";
$param = array(
    ":account_id" => $member["id"],
    ":record_type" => $record_type,
    ":status" => 0
);

$overlap_check = $PDO -> fetch($overlap_check_sql, $param);
if($overlap_check) {
    $returnArray["code"] = "OVERLAP_REQUEST";
    $returnArray["msg"] = "해당 종목으로 심사전 신청건이 존재합니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$overlap_check_sql = "
    Select  *
    From    tb_record_request
    Where   account_id = :account_id
    And     record_type = :record_type
    And     status = :status
";
$param = array(
    ":account_id" => $member["id"],
    ":record_type" => $record_type,
    ":status" => 1
);

$overlap_check = $PDO -> fetch($overlap_check_sql, $param);
if($overlap_check) {
    $returnArray["code"] = "OVERLAP_REQUEST";
    $returnArray["msg"] = "해당 종목으로 관리자가 심사중인 건이 존재합니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}





$record_weight = $data['record_weight'];
$record_weight = preg_replace('/[^0-9]+/u', '', $record_weight);
if($record_weight == '') {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "등록할 무게를 입력해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if($record_weight > 9999) {
    $returnArray["code"] = "PARAM_ERROR";
    $returnArray["msg"] = "Record 무게를 확인해 주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}


$accessType = array("video/mp4", "video/m4v", "video/avi", "video/wmv", "video/mwa", "video/asf", "video/mpg", "video/mpeg", "video/mkv", "video/mov", "video/3gp", "video/3g2", "video/webm", "video/quicktime", "application/octet-stream", "image/jpeg", "image/jpg", "image/png");


if(!$_FILES) {
    $returnArray["code"] = "FILE_EMPTY";
    $returnArray["msg"] = "파일을 등록해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$total_file_size = 0;

//파일 타입 검사
foreach($_FILES as $key => $value) {
    $file_type = $value["type"];
    $file_size = $value["size"];
    $total_file_size += $file_size;

    if(!in_array($file_type, $accessType)) {
        $returnArray["code"] = "FILE_TYPE_LIMIT";
        $returnArray["msg"] = "파일은 이미지 또는 동영상 파일만 업로드 가능합니다<br/>이미지 또는 동영상 파일이 업로드가 안될경우 고객센터에 문의부탁드립니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}


$total_file_size = $total_file_size / 1024 / 1024;
$limit_file_size = 100;

//파일 용량 검사
if($total_file_size > $limit_file_size) {
    $returnArray["code"] = "FILE_SIZE_LIMIT";
    $returnArray["msg"] = "파일은 총 100MB 이하로 업로드 해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}



//데이터 insert
$insert_sql = "
    Insert Into tb_record_request
    Set
        account_id = :account_id,
        record_type = :record_type,
        record_weight = :record_weight,
        status = :status
";
$param = array(
    ":account_id" => $member["id"],
    ":record_type" => $record_type,
    ":record_weight" => $record_weight,
    ":status" => 0
);
$insert_result = $PDO -> execute($insert_sql, $param);

//파일 insert
$videoType = array("video/mp4", "video/m4v", "video/avi", "video/wmv", "video/mwa", "video/asf", "video/mpg", "video/mpeg", "video/mkv", "video/mov", "video/3gp", "video/3g2", "video/webm", "application/octet-stream");
$upload_path = $_SERVER["DOCUMENT_ROOT"]."/data/record/";
foreach($_FILES as $key => $value) {
    $GUID = makeGuid();
    $file_type_text = "";
    if(in_array($value["type"], $videoType)) {
        $file_type_name = explode("/", $file_type);
        $file_type_text = ".".$file_type_name[1];
    }

    $file_insert_sql = "
        Insert Into tb_record_request_file
        Set
            request_id = :request_id,
            file_original_name = :file_original_name,
            file_guid = :file_guid,
            file_type = :file_type
    ";
    $param = array(
        ":request_id" => $insert_result,
        ":file_original_name" => $value["name"],
        ":file_guid" => $GUID.$file_type_text,
        ":file_type" => $value["type"]
    );
    $PDO -> execute($file_insert_sql, $param);

    $this_upload_path = $upload_path.$GUID.$file_type_text;
    move_uploaded_file($value["tmp_name"], $this_upload_path);
}





function makeGuid() {
    return sprintf('%08x-%04x-%04x-%04x-%04x%08x',
        mt_rand(0, 0xffffffff),
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff), mt_rand(0, 0xffffffff)
    );
}


echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>