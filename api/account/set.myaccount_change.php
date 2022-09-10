<?
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["nickname"])) {
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

//닉네임 중복 체크
$nickname_check_query = "
    Select  *
    From    Account
    Where   user_nickname = :user_nickname
    And     id != :id
";
$nickname_check_param = array(
    ":user_nickname" => $data["nickname"],
    ":id" => $member["id"]
);
$nickname_check = $PDO -> fetch($nickname_check_query, $nickname_check_param);
if($nickname_check) {
    $returnArray["code"] = "OVERLAP";
    $returnArray["msg"] = "이미 사용중인 닉네임 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

//닉네임 update 쿼리
$update_query = "
    Update  Account
    Set     user_nickname = :nickname
";

$param = array(
    ":nickname" => $data["nickname"]
);

//이름 변경하는지 체크
if($data["name"]) {
    $update_query .= "
        ,
        user_name = :user_name
    ";

    $param[":user_name"] = $data["name"];
}

//핸드폰번호 변경하는지 체크
if($data["phone"]) {
    //핸드폰번호 중복 체크
    $phone_check_query = "
        Select  *
        From    Account
        Where   user_phone = :user_phone
        And     id != :id
    ";
    $phone_check_param = array(
        ":user_phone" => $data["phone"],
        ":id" => $member["id"]
    );
    $phone_check = $PDO -> fetch($phone_check_query, $phone_check_param);
    if($phone_check) {
        $returnArray["code"] = "OVERLAP";
        $returnArray["msg"] = "이미 사용중인 핸드폰번호 입니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }


    $update_query .= "
        ,
        user_phone = :user_phone
    ";

    $param[":user_phone"] = $data["phone"];
}

//이메일 변경하는지 체크
if($data["email"]) {
    //이메일 중복 체크
    $email_check_query = "
        Select  *
        From    Account
        Where   user_email = :user_email
        And     id != :id
    ";
    $email_check_param = array(
        ":user_email" => $data["email"],
        ":id" => $member["id"]
    );
    $email_check = $PDO -> fetch($email_check_query, $email_check_param);
    if($email_check) {
        $returnArray["code"] = "OVERLAP";
        $returnArray["msg"] = "이미 사용중인 이메일 입니다";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }


    $update_query .= "
        ,
        user_email = :user_email
    ";

    $param[":user_email"] = $data["email"];
}


//Where 조건
$update_query .= "
    Where   id = :id
";
$param[":id"] = $member["id"];

$update = $PDO -> execute($update_query, $param);

if(!$update) {
    $returnArray["code"] = "ERROR";
    $returnArray["msg"] = "업데이트 중 에러가 발생했습니다</br>고객센터에 문의해 주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>