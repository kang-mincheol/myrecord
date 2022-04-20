<?
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
	"code"=>"SUCCESS",
	"msg"=>"정상적으로 로그인 하였습니다."
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
    Where   user_id = '{$data["id"]}'
")["cnt"];

if($id_check == 0) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "존재하지 않는 아이디 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//회원정보 get
$member = sql_fetch("
    Select  *
    From    Users
    Where   user_id = '{$data["id"]}'
");

//비밀번호 맞는지 체크
if(password_verify($data["password"], $member["user_password"])) {
    //로그인 성공
    // 회원아이디 세션 생성
    set_session('user_id', $data["id"]);
    
    // 로그인 성공로그 생성
    $user_agent = $_SERVER["HTTP_USER_AGENT"];
    sql_query("
        Insert into LoginLog
        (ip_address, user_agent, user_id, is_success, create_date)
        Values
        ('{$ip_address}', '{$user_agent}', '{$data["id"]}', 1, Now())
    ");
} else {
    $returnArray["code"] = "LOGIN_FAIL";
    $returnArray["msg"] = "입력한 아이디와 비밀번호가 일치하지 않습니다. 아이디 또는 비밀번호를 확인 해주세요.";
}




echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>