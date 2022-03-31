<?
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"200",
    "msg"=>"정상 처리되었습니다"
);


/***** GET *****

account 정보 조회
*/
if($_method == "GET") {
    if(!$member) {
        $returnArray["code"] = "401";
        $returnArray["msg"] = "로그인 후 이용해주세요";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    $returnData = array(
        "id"=>$member["user_id"],
        "name"=>$member["user_name"],
        "nickname"=>$member["user_nickname"],
        "email"=>$member["user_email"],
        "phone"=>$member["user_phone"]
    );

    $returnArray["data"] = $returnData;
}





/***** POST *****

account 생성
*/
if($_method == "POST") {

    $data = json_decode(file_get_contents('php://input'), true);

    if (is_null($data) || !checkParams($data, ["terms_marketing", "account_id", "account_password", "account_nickname"])) {
        $returnArray["code"] = "400";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    $data = cleansingParams($data);

    //아이디 중복확인
    $id_overlap = sql_fetch("
        Select  count(*) as cnt
        From    Users
        Where   user_id = '{$data["account_id"]}'
    ")["cnt"];

    if($id_overlap > 0) {
        $returnArray["code"] = "400";
        $returnArray["msg"] = "이미 사용중인 아이디 입니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    //아이디 정규식 체크
    $id_reg = "/^[0-9a-zA-Z-]+{5,20}$/u";
    if(preg_match($id_reg, $data["account_id"]) == false) {
        $returnArray["code"] = "400";
        $returnArray["msg"] = "아이디를 규칙에 맞게 입력해주세요";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    
    //비밀번호 정규식 체크
    $pw_reg = "/^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+=]).*$/u";
    if(preg_match($pw_reg, $data["account_password"]) == false) {
        $returnArray["code"] = "400";
        $returnArray["msg"] = "비밀번호는 영문, 숫자, 특수문자 포함 8~15자리를 입력하세요.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}



/***** PATCH *****

account 정보 수정
*/
if($_method == "PATCH") {
    
}



/***** DELETE *****

회원탈퇴
*/
if($_method == "DELETE") {
    
}





echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>