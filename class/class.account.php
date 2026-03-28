<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

class Account {
    private $id;
    private $user_id;
    private $user_password;
    private $user_nickname;
    private $user_name;
    private $user_phone;
    private $user_email;
    private $login_date;
    private $terms_marketing;
    private $is_admin;
    private $create_date;
    private $update_date;
    private $is_withdraw;
    private $is_withdraw_date;

    public static function joinAccount($joinData) {
        global $PDO;

        $returnArray = array(
            "code" => "SUCCESS",
            "msg" => "회원가입이 완료되었습니다."
        );

        $sql = "
            Insert Into Account
            Set
                user_id = :user_id,
                user_password = :user_password,
                user_nickname = :user_nickname,
                terms_marketing = :terms_marketing
        ";

        $param = array(
            ":user_id" => $joinData["account_id"],
            ":user_password" => password_hash($joinData["account_password"], PASSWORD_BCRYPT),
            ":user_nickname" => $joinData["account_nickname"],
            ":terms_marketing" => $joinData["terms_marketing"] ? 1 : 0
        );

        // 선택 값 추가 처리
        // 이름
        if(!empty($joinData["account_name"])) {
            $sql .= "
                ,
                user_name = :user_name
            ";
            $param[":user_name"] = $joinData["account_name"];
        }
        // 휴대폰번호
        if(!empty($joinData["account_phone"])) {
            $sql .= "
                ,
                user_phone = :user_phone
            ";
            $param[":user_phone"] = $joinData["account_phone"];
        }
        // 이메일
        if(!empty($joinData["account_email"])) {
            $sql .= "
                ,
                user_email = :user_email
            ";
            $param[":user_email"] = $joinData["account_email"];
        }

        $result = $PDO->execute($sql, $param);

        if($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 아이디가 존재하는지 확인
     * @param $user_id => 회원 아이디
     * @return boolean
     * true => 해당아이디가 존재
     * false => 해당아이디 비존재
     */
    public static function hasAccountIdCheck($user_id) {
        global $PDO;

        $sql = "
            Select  count(*) as cnt
            From    Account
            Where   user_id = :user_id
        ";
        $param = array(
            ":user_id" => $user_id
        );

        $count = $PDO->fetch($sql, $param)["cnt"];

        if($count) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 아이디로 회원정보를 가져온다
     * @param $user_id => 회원아이디
     * @return void
     * 회원정보가 있을
     */
    public static function getAccount($user_id) {
        global $PDO;

        $sql = "
            Select  *
            From    Account
            Where   user_id = :user_id
            And     is_withdraw = 0
        ";
        $param = array(
            ":user_id" => $user_id
        );

        $account = $PDO->fetch($sql, $param);

        if($account) {
            return $account;
        } else {
            return false;
        }
    }

    /**
     * 동일한 비밀번호 인지 체크하는 함수
     */
    public static function hasPasswordCheck($check_password, $real_password) {
        if(password_verify($check_password, $real_password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 비밀번호 변경 함수
     */
    public static function updatePassword($password) {
        global $PDO;
        global $member;

        $sql = "
            Update  Account
            SET
                user_password = :user_password
            Where   id = :id
        ";
        $param = array(
            ":user_password" => password_hash($password, PASSWORD_BCRYPT),
            ":id" => $member["id"]
        );

        $result = $PDO->fetch($sql, $param);

        return $result;
    }

    /**
     * 닉네임 중복 체크 함수
     */
    public static function overlapCheckNickname($nickname) {
        global $PDO;

        $sql = "
            Select  count(*) as cnt
            From    Account
            Where   user_nickname = :user_nickname
        ";
        $param = array(
            ":user_nickname" => $nickname
        );

        $result = $PDO->fetch($sql, $param)["cnt"];

        if($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 휴대폰번호 중복 체크 함수
     */
    public static function overlapCheckPhoneNumber($phone_number) {
        global $PDO;

        $sql = "
            Select  count(*) as cnt
            From    Account
            Where   user_phone = :user_phone
        ";
        $param = array(
            ":user_phone" => $phone_number
        );

        $result = $PDO->fetch($sql, $param)["cnt"];

        if($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 이메일 중복 체크 함수
     */
    public static function overlapCheckEmail($email) {
        global $PDO;

        $sql = "
            Select  count(*) as cnt
            From    Account
            Where   user_email = :user_email
        ";
        $param = array(
            ":user_email" => $email
        );

        $result = $PDO->fetch($sql, $param)["cnt"];

        if($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 회원 탈퇴 여부 확인
     * @param $user_id => 회원 아이디
     * @return boolean
     * true => 탈퇴 회원
     * false => 정상 회원
     */
    public static function hasWithdrawCheck($user_id) {
        global $PDO;

        $sql = "
            Select  count(*) as cnt
            From    Account
            Where   user_id = :user_id
            And     is_withdraw = 1
        ";
        $param = array(
            ":user_id" => $user_id
        );

        $check = $PDO->fetch($sql, $param)["cnt"];

        if($check) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 내 정보 수정 시 닉네임 중복 체크 (자신 제외)
     */
    public static function checkNicknameForUpdate(string $nickname, int $excludeId): bool {
        global $PDO;
        $sql = "
            Select  count(*) as cnt
            From    Account
            Where   user_nickname = :nickname
            And     id != :id
        ";
        $param = [":nickname" => $nickname, ":id" => $excludeId];
        return (int)($PDO->fetch($sql, $param)["cnt"] ?? 0) > 0;
    }

    /**
     * 내 정보 수정 시 핸드폰 중복 체크 (자신 제외)
     */
    public static function checkPhoneForUpdate(string $phone, int $excludeId): bool {
        global $PDO;
        $sql = "
            Select  count(*) as cnt
            From    Account
            Where   user_phone = :phone
            And     id != :id
        ";
        $param = [":phone" => $phone, ":id" => $excludeId];
        return (int)($PDO->fetch($sql, $param)["cnt"] ?? 0) > 0;
    }

    /**
     * 내 정보 수정 시 이메일 중복 체크 (자신 제외)
     */
    public static function checkEmailForUpdate(string $email, int $excludeId): bool {
        global $PDO;
        $sql = "
            Select  count(*) as cnt
            From    Account
            Where   user_email = :email
            And     id != :id
        ";
        $param = [":email" => $email, ":id" => $excludeId];
        return (int)($PDO->fetch($sql, $param)["cnt"] ?? 0) > 0;
    }

    /**
     * 내 정보(닉네임/이름/전화/이메일) 업데이트
     */
    public static function updateMyAccount(array $data, int $memberId): bool {
        global $PDO;

        $sql   = "Update Account Set user_nickname = :nickname";
        $param = [":nickname" => $data["nickname"]];

        if (!empty($data["name"])) {
            $sql .= ", user_name = :user_name";
            $param[":user_name"] = $data["name"];
        }
        if (!empty($data["phone"])) {
            $sql .= ", user_phone = :user_phone";
            $param[":user_phone"] = $data["phone"];
        }
        if (!empty($data["email"])) {
            $sql .= ", user_email = :user_email";
            $param[":user_email"] = $data["email"];
        }

        $sql .= " Where id = :id";
        $param[":id"] = $memberId;

        return (bool)$PDO->execute($sql, $param);
    }

    /**
     * 숫자 ID로 회원 정보 조회 (JWT 인증용)
     */
    public static function getAccountById(int $id): ?array {
        global $PDO;
        $sql   = "Select * From Account Where id = :id And is_withdraw = 0";
        $account = $PDO->fetch($sql, [':id' => $id]);
        return $account ?: null;
    }

    public static function setLogin(array $member): array {
        global $PDO;

        // Access Token(60분) + Refresh Token(30일) 동시 발급 및 쿠키 설정
        $tokens = jwt_issue_tokens((int)$member['id']);

        // 로그인 성공 로그
        $PDO->execute(
            "Insert Into LoginLog Set ip_address = :ip, user_agent = :ua, user_id = :uid, is_success = 1",
            [
                ':ip'  => $_SERVER['REMOTE_ADDR']     ?? '',
                ':ua'  => $_SERVER['HTTP_USER_AGENT'] ?? '',
                ':uid' => $member['user_id'],
            ]
        );

        // 로그인 일자 업데이트
        $PDO->execute(
            "Update Account Set login_date = :login_date Where id = :id",
            [':login_date' => date('Y-m-d H:i:s'), ':id' => $member['id']]
        );

        return $tokens;  // ['access' => '...', 'refresh' => '...']
    }
}


?>