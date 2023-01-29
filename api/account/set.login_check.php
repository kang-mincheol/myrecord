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
$id_check = Account::hasAccountIdCheck($data["id"]);
if(!$id_check) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "존재하지 않는 아이디 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//탈퇴한 회원인지 체크
$withdraw_check = Account::hasWithdrawCheck($data["id"]);
if($withdraw_check) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "회원탈퇴한 아이디 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//회원정보 get
$member = Account::getAccount($data["id"]);
if(!$member) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "존재하지 않는 아이디 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//비밀번호 맞는지 체크
$password_check = Account::hasPasswordCheck($data["password"], $member["user_password"]);

if(!$password_check) {
    $returnArray["code"] = "LOGIN_FAIL";
    $returnArray["msg"] = "입력한 아이디와 비밀번호가 일치하지 않습니다. 아이디 또는 비밀번호를 확인 해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//로그인 성공 처리
$login = Account::setLogin($member);
if(!$login) {
    $returnArray["code"] = "LOGIN_FAIL";
    $returnArray["msg"] = "로그인에 실패했습니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}



echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>