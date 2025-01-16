<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

class Record {

    public static function insertRecord($recordData) {
        global $PDO;
        global $member;

        $sql = "

        ";
        $param = array(
            ""
        );
    }

    public static function hasAuditRecord($recordTypeId) {
        global $member;
        global $PDO;

        $returnArray = array(
            "code" => "SUCCESS",
            "msg" => "정상 처리되었습니다"
        );

        if (is_null($member)) {
            
        }

        $sql = "
            Select  *
            From    tb_record_request
            Where   account_id = :account_id
            And     record_type = :record_type
            And     status = :status
        ";
    }
}

?>