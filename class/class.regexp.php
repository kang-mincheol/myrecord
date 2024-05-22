<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

class Regexp {

    /**
     * 아이디 정규식 체크 함수
     */
    public static function id_regexp($id) {
        $id_reg = "/^[0-9a-zA-Z_-]{5,20}/u";
        if(preg_match($id_reg, $id) == false) {
            return false;
        } else {
            $block_id_list = array("admin");
            foreach($block_id_list as $value) {
                if(strpos($id, $value) !== false) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * 비밀번호 정규식 체크 함수
     */
    public static function password_regexp($password) {
        $password_reg = "/^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&*+=]).*$/u";
        if(preg_match($password_reg, $password) == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 닉네임 정규식 체크 함수
     */
    public static function nickname_regexp($nickname) {
        $nickname_reg = "/^([a-zA-Z0-9ㄱ-ㅎ|ㅏ-ㅣ|가-힣]).{2,10}$/u";
        if(preg_match($nickname_reg, $nickname) == false) {
            return false;
        } else {
            $block_nickname_list = array("관리자", "운영자");
            foreach($block_nickname_list as $value) {
                if(strpos($nickname, $value) !== false) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * 이름 정규식 체크 함수
     * 한글, 영문 2~17자리
     */
    public static function name_regexp($name) {
        $name_reg = "/^([ㄱ-ㅎ|ㅏ-ㅣ|가-힣|a-z|A-Z]).{2,17}$/u";
        if(preg_match($name_reg, $name) == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 핸드폰번호 정규식 체크 함수
     */
    public static function phone_regexp($phone_number) {
        $phone_reg = "/^01([0|1|6|7|8|9])-?([0-9]{3,4})-?([0-9]{4})$/u";
        if(preg_match($phone_reg, $phone_number) == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 이메일 정규식 체크 함수
     */
    public static function email_regexp($email) {
        $email_reg = "/^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/u";

        if(preg_match($email_reg, $email) == false) {
            return false;
        } else {
            return true;
        }
    }
}