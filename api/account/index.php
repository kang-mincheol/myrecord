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
        if(IS_LIVE) {
            $returnArray["code"] = "400";
            $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
        }
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
    $id_reg = "/^[0-9a-zA-Z]{5,20}$/u";
    if(preg_match($id_reg, $data["account_id"]) == false) {
        $returnArray["code"] = "400";
        $returnArray["msg"] = "아이디를 규칙에 맞게 입력해주세요";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    
    //비밀번호 정규식 체크
    $pw_reg = "/^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&*+=]).*$/u";
    if(preg_match($pw_reg, $data["account_password"]) == false) {
        $returnArray["code"] = "400";
        $returnArray["msg"] = "비밀번호는 영문, 숫자, 특수문자 포함 8~15자리를 입력해주세요.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    //닉네임 정규식 체크
    $nickname_reg = "/^([a-zA-Z0-9ㄱ-ㅎ|ㅏ-ㅣ|가-힣]).{2,10}$/u";
    if(preg_match($nickname_reg, $data["account_nickname"]) == false) {
        $returnArray["code"] = "400";
        $returnArray["msg"] = "닉네임은 영문 또는 한글 또는 숫자로 2~10자리로 입력해주세요.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    //닉네임 중복 체크
    $nickname_overlap = sql_fetch("
        Select  count(*) as cnt
        From    Users
        Where   user_nickname = '{$data["account_nickname"]}'
    ")["cnt"];

    if($nickname_overlap > 0) {
        $returnArray["code"] = "400";
        $returnArray["msg"] = "현재 사용중인 닉네임 입니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    //비필수 입력 값 쿼리 변수 선언
    $add_query = "";

    //이름 입력했을 경우 정규식 체크
    if(!empty($data["account_name"])) {
        $name_reg = "/^([ㄱ-ㅎ|ㅏ-ㅣ|가-힣]).{2,17}$/u";
        if(preg_match($name_reg, $data["account_name"]) == false) {
            $returnArray["code"] = "400";
            $returnArray["msg"] = "이름은 한글 2~17자리로 입력해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
        }

        $add_query = "
            , user_name = '{$data["account_name"]}'
        ";

    }

    //핸드폰번호 입력했을 경우
    if(!empty($data["account_phone"])) {
        //핸드폰번호 정규식 체크
        $phone_reg = "/^01([0|1|6|7|8|9])-?([0-9]{3,4})-?([0-9]{4})$/u";
        if(preg_match($phone_reg, $data["account_phone"]) == false) {
            $returnArray["code"] = "400";
            $returnArray["msg"] = "핸드폰번호가 올바르지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
        }

        //핸드폰번호 중복 체크
        $phone_overlap = sql_fetch("
            Select  count(*) as cnt
            From    Users
            Where   user_phone = '{$data["account_phone"]}'
        ")["cnt"];
        if($phone_overlap > 0) {
            $returnArray["code"] = "400";
            $returnArray["msg"] = "이미 사용중인 핸드폰번호 입니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
        }

        $add_query = "
            , user_phone = '{$data["account_phone"]}'
        ";
    }

    //이메일 입력했을 경우
    if(!empty($data["account_email"])) {
        //이메일 정규식 체크
        $email_reg = "/^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/u";
        if(preg_match($email_reg, $data["account_email"]) == false) {
            $returnArray["code"] = "400";
            $returnArray["msg"] = "이메일을 올바르게 입력해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
        }

        //이메일 중복 체크
        $email_overlap = sql_fetch("
            Select  count(*) as cnt
            From    Users
            Where   user_email = '{$data["account_email"]}'
        ")["cnt"];
        if($email_overlap > 0) {
            $returnArray["code"] = "400";
            $returnArray["msg"] = "이미 사용중인 이메일 입니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
        }

        $add_query = "
            , user_email = '{$data["account_email"]}'
        ";
    }

    //회원가입 처리

    //비빌번호 암호화
    $password = password_hash($data["account_password"], PASSWORD_BCRYPT);

    $create = sql_query("
        Insert Into Users
        Set
            user_id = '{$data["account_id"]}',
            user_password = '{$password}',
            user_nickname = '{$data["account_nickname"]}'
            {$add_query}
    ");

    if(!$create) {
        $returnArray["code"] = "400";
        $returnArray["msg"] = "회원가입 중 에러가 발생했습니다.<br/>고객센터에 문의해 주세요.";
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