<?php

class AuthController {

    /**
     * POST /api/v1/auth/login
     * Body: { "id": string, "password": string }
     */
    public static function login(array $params): void {
        $returnArray = ["code" => "SUCCESS", "msg" => "정상적으로 로그인 하였습니다."];

        $data = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["id", "password"])) {
            if (IS_LIVE) {
                $returnArray["code"] = "PARAMS";
                $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
                echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
            }
        }

        $data = cleansingParams($data);

        $id_check = Account::hasAccountIdCheck($data["id"]);
        if (!$id_check) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "존재하지 않는 아이디 입니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $withdraw_check = Account::hasWithdrawCheck($data["id"]);
        if ($withdraw_check) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "회원탈퇴한 아이디 입니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $member = Account::getAccount($data["id"]);
        if (!$member) {
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "존재하지 않는 아이디 입니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $password_check = Account::hasPasswordCheck($data["password"], $member["user_password"]);
        if (!$password_check) {
            $returnArray["code"] = "LOGIN_FAIL";
            $returnArray["msg"]  = "입력한 아이디와 비밀번호가 일치하지 않습니다. 아이디 또는 비밀번호를 확인 해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $login = Account::setLogin($member);
        if (!$login) {
            $returnArray["code"] = "LOGIN_FAIL";
            $returnArray["msg"]  = "로그인에 실패했습니다";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * POST /api/v1/auth/logout
     */
    public static function logout(array $params): void {
        session_unset();
        session_destroy();

        echo json_encode(["code" => "SUCCESS", "msg" => "로그아웃 되었습니다."], JSON_UNESCAPED_UNICODE);
    }
}
