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

    }

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

    public static function getAccountDataUserId($user_id) {
        global $PDO;

        $sql = "
            Select  *
            From    Account
            Where   user_id = :user_id
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

    public static function hasPasswordCheck($check_password, $real_password) {
        if(password_verify($check_password, $real_password)) {
            return true;
        } else {
            return false;
        }
    }

    public static function setLogin($member) {
        global $PDO;

        // 회원아이디 세션 생성
        set_session('user_id', $data["id"]);

        // 로그인 성공로그 생성
        $user_agent = $_SERVER["HTTP_USER_AGENT"];
        $sql = "
            Insert Into LoginLog
            Set
                ip_address = :ip_address,
                user_agent = :user_agent,
                user_id = :user_id,
                is_success = 1,
                create_date = :create_date
        ";
        $param = array(
            ":ip_address" => getenv('REMOTE_ADDR'),
            ":user_agent" => $_SERVER['HTTP_USER_AGENT'],
            ":user_id" => $member["user_id"],
            ":create_date" => date("Y-m-d")
        );
        $PDO->execute($sql, $param);

        // 로그인 일자 update
        $login_date_sql = "
            Update  Account
            SET
                login_date = :login_date
            Where   id = :id
        ";
        $param = array(
            ":login_date" => date("Y-m-d H:i:s"),
            ":id" => $member["id"]
        );

        $PDO -> execute($login_date_sql, $param);

        return true;
    }
}


?>