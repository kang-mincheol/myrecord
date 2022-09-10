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
    $update_query .= "
        ,
        user_phone = :user_phone
    ";

    $param[":user_phone"] = $data["phone"];
}

//이메일 변경하는지 체크
if($data["email"]) {
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