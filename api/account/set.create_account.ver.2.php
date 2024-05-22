<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/api/common.php");

header("Content-Type: application/json; charset=UTF-8");

$returnArray = array(
    "code"=>"SUCCESS",
    "msg"=>"정상 처리되었습니다"
);

$data = json_decode(file_get_contents('php://input'), true);

if (is_null($data) || !checkParams($data, ["terms_marketing", "account_id", "account_password", "account_nickname"])) {
    if(IS_LIVE) {
        $returnArray["code"] = "PARAMS";
        $returnArray["msg"] = "필수 파라미터가 존재하지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$data = cleansingParams($data);

/**
 * 아이디 체크
 * 
 * 1. 중복 체크
 * 2. 정규식 체크
 */
$id_overlap_check = Account::hasAccountIdCheck($data["account_id"]);
if($id_overlap_check) {
    $returnArray["code"] = "ID_OVERLAP";
    $returnArray["msg"] = "이미 사용중인 아이디 입니다";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}
$id_reg_check = Regexp::id_regexp($data["account_id"]);
if(!$id_reg_check) {
    $returnArray["code"] = "ID_REGEXP";
    $returnArray["msg"] = "아이디를 규칙에 맞게 입력해주세요";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

/**
 * 비밀번호 체크
 * 
 * 1. 정규식 체크
 */
$password_reg_check = Regexp::password_regexp($data["account_password"]);
if(!$password_reg_check) {
    $returnArray["code"] = "PW_REGEXP";
    $returnArray["msg"] = "비밀번호는 영문, 숫자, 특수문자 포함 8~15자리를 입력해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

/**
 * 닉네임 체크
 * 
 * 1. 정규식 체크
 * 2. 중복 체크
 */
$nickname_check = Regexp::nickname_regexp($data["account_nickname"]);
if(!$nickname_check) {
    $returnArray["code"] = "NICKNAME_REGEXP";
    $returnArray["msg"] = "닉네임은 영문 또는 한글 또는 숫자로 2~10자리로 입력해주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}
$nickname_overlap_check = Account::overlapCheckNickname($data["account_nickname"]);
if($nickname_overlap_check) {
    $returnArray["code"] = "NICKNAME_OVERLAP";
    $returnArray["msg"] = "이미 사용중인 닉네임 입니다.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

/**
 * 이름 체크
 */
if(!empty($data["account_name"])) {
    // 정규식 체크
    $name_check = Regexp::name_regexp($data["account_name"]);
    if(!$name_check) {
        $returnArray["code"] = "NAME_REGEXP";
        $returnArray["msg"] = "이름은 영문 또는 한글 2~17자리로 입력해주세요.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

/**
 * 휴대폰번호 체크
 */
if(!empty($data["account_phone"])) {
    // 정규식 체크
    $phone_check = Regexp::phone_regexp($data["account_phone"]);
    if(!$phone_check) {
        $returnArray["code"] = "PHONE_REGEXP";
        $returnArray["msg"] = "핸드폰번호가 올바르지 않습니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    // 중복 체크
    $phone_overlap_check = Account::overlapCheckPhoneNumber($data["account_phone"]);
    if($phone_overlap_check) {
        $returnArray["code"] = "PHONE_OVERLAP";
        $returnArray["msg"] = "이미 사용중인 핸드폰번호 입니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

/**
 * 이메일 체크
 */
if(!empty($data["account_email"])) {
    // 정규식 체크
    $email_check = Regexp::email_regexp($data["account_email"]);
    if(!$email_check) {
        $returnArray["code"] = "EMAIL_REGEXP";
        $returnArray["msg"] = "이메일을 올바르게 입력해주세요.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }

    // 중복 체크
    $email_overlap_check = Account::overlapCheckEmail($data["account_email"]);
    if($email_overlap_check) {
        $returnArray["code"] = "EMAIL_OVERLAP";
        $returnArray["msg"] = "이미 사용중인 이메일 입니다.";
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
    }
}

$join_account = Account::joinAccount($data);

if(!$join_account) {
    $returnArray["code"] = "SYSTEM_ERROR";
    $returnArray["msg"] = "회원가입 실패<br>고객센터에 문의해 주세요.";
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>