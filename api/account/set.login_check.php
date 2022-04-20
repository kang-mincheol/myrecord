<?
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["id", "password"])) {
    if(IS_LIVE) {
        $returnArray["code"] = "PARAMS";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$data = cleansingParams($data);

//아이디 존재하는지 체크
$id_check = sql_fetch("
    Select  count(*) as cnt
    From    Users
    Where   user_id = '{$data["user_id"]}'
")["cnt"];

if($id_check == 0) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "존재하지 않는 아이디 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//비밀번호 맞는지 체크




echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>