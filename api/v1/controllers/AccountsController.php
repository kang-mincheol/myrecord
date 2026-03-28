<?php

class AccountsController {

    /**
     * GET /api/v1/accounts/me
     */
    public static function getMe(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $returnArray["data"] = [
            "account_id"       => substr($member["user_id"], 0, -3) . "***",
            "account_nickname" => $member["user_nickname"],
            "account_name"     => $member["user_name"],
            "account_phone"    => $member["user_phone"],
            "account_email"    => $member["user_email"],
        ];

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * POST /api/v1/accounts
     * Body: { "terms_marketing": bool, "account_id": string, "account_password": string, "account_nickname": string, ... }
     */
    public static function create(array $params): void {
        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $data = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["terms_marketing", "account_id", "account_password", "account_nickname"])) {
            if (IS_LIVE) {
                $returnArray["code"] = "PARAMS";
                $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        $data = cleansingParams($data);

        $id_overlap_check = Account::hasAccountIdCheck($data["account_id"]);
        if ($id_overlap_check) {
            $returnArray["code"] = "ID_OVERLAP";
            $returnArray["msg"]  = "이미 사용중인 아이디 입니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $id_reg_check = Regexp::id_regexp($data["account_id"]);
        if (!$id_reg_check) {
            $returnArray["code"] = "ID_REGEXP";
            $returnArray["msg"]  = "아이디를 규칙에 맞게 입력해 주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $password_reg_check = Regexp::password_regexp($data["account_password"]);
        if (!$password_reg_check) {
            $returnArray["code"] = "PW_REGEXP";
            $returnArray["msg"]  = "비밀번호는 영문, 숫자, 특수문자 포함 8~15자리를 입력해 주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $nickname_check = Regexp::nickname_regexp($data["account_nickname"]);
        if (!$nickname_check) {
            $returnArray["code"] = "NICKNAME_REGEXP";
            $returnArray["msg"]  = "닉네임은 영문 또는 한글 또는 숫자로 2~10자리로 입력해 주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $nickname_overlap_check = Account::overlapCheckNickname($data["account_nickname"]);
        if ($nickname_overlap_check) {
            $returnArray["code"] = "NICKNAME_OVERLAP";
            $returnArray["msg"]  = "이미 사용중인 닉네임 입니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (!empty($data["account_name"])) {
            $name_check = Regexp::name_regexp($data["account_name"]);
            if (!$name_check) {
                $returnArray["code"] = "NAME_REGEXP";
                $returnArray["msg"]  = "이름은 영문 또는 한글 2~17자리로 입력해 주세요.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        if (!empty($data["account_phone"])) {
            $phone_check = Regexp::phone_regexp($data["account_phone"]);
            if (!$phone_check) {
                $returnArray["code"] = "PHONE_REGEXP";
                $returnArray["msg"]  = "핸드폰번호가 올바르지 않습니다.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }

            $phone_overlap_check = Account::overlapCheckPhoneNumber($data["account_phone"]);
            if ($phone_overlap_check) {
                $returnArray["code"] = "PHONE_OVERLAP";
                $returnArray["msg"]  = "이미 사용중인 핸드폰번호 입니다.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        if (!empty($data["account_email"])) {
            $email_check = Regexp::email_regexp($data["account_email"]);
            if (!$email_check) {
                $returnArray["code"] = "EMAIL_REGEXP";
                $returnArray["msg"]  = "이메일을 올바르게 입력해 주세요.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }

            $email_overlap_check = Account::overlapCheckEmail($data["account_email"]);
            if ($email_overlap_check) {
                $returnArray["code"] = "EMAIL_OVERLAP";
                $returnArray["msg"]  = "이미 사용중인 이메일 입니다.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        $join_account = Account::joinAccount($data);
        if (!$join_account) {
            Slack::send(SLACK_URL_ERROR, "회원가입 실패\n{$_SERVER["REQUEST_URI"]}");
            $returnArray["code"] = "SYSTEM_ERROR";
            $returnArray["msg"]  = "회원가입 실패<br>고객센터에 문의해 주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        http_response_code(201);
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * PUT /api/v1/accounts/me
     * Body: { "nickname": string, "phone": string, "email": string }
     */
    public static function updateMe(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $data = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["nickname"])) {
            if (IS_LIVE) {
                $returnArray["code"] = "PARAMS";
                $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        $data = cleansingParams($data);

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (Account::checkNicknameForUpdate($data["nickname"], $member["id"])) {
            $returnArray["code"] = "OVERLAP";
            $returnArray["msg"]  = "이미 사용중인 닉네임 입니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (!empty($data["phone"])) {
            if (Account::checkPhoneForUpdate($data["phone"], $member["id"])) {
                $returnArray["code"] = "OVERLAP";
                $returnArray["msg"]  = "이미 사용중인 핸드폰번호 입니다";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        if (!empty($data["email"])) {
            if (Account::checkEmailForUpdate($data["email"], $member["id"])) {
                $returnArray["code"] = "OVERLAP";
                $returnArray["msg"]  = "이미 사용중인 이메일 입니다";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        $update = Account::updateMyAccount($data, $member["id"]);
        if (!$update) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "업데이트 중 에러가 발생했습니다</br>고객센터에 문의해 주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * PUT /api/v1/accounts/me/password
     * Body: { "now_password": string, "new_password": string }
     */
    public static function changePassword(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["now_password", "new_password"])) {
            if (IS_LIVE) {
                $returnArray["code"] = "PARAMS";
                $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        $data = cleansingParams($data);

        $nowPasswordCheck = Account::hasPasswordCheck($data["now_password"], $member["user_password"]);
        if ($nowPasswordCheck === false) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "현재 비밀번호를 정확하게 입력해 주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $newPasswordValidate = Regexp::password_regexp($data["new_password"]);
        if ($newPasswordValidate === false) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "새 비밀번호를 규칙에 맞게 입력해 주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        Account::updatePassword($data["new_password"]);

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }
}
