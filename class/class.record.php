<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

class Record {

    public static function insertRecord($recordData) {
        global $PDO;
        global $member;

        $sql = "
            Insert Into tb_record_request
            Set
                account_id = :account_id,
                record_type = :record_type,
                record_weight = :record_weight,
                status = :status
        ";
        $param = array(
            ":account_id" => $member["id"],
            ":record_type" => $recordData["record_type"],
            ":record_weight" => $recordData["record_weight"],
            ":status" => 0
        );

        return $PDO->execute($sql, $param);
    }

    public static function insertRecordFile($file) {
        global $PDO;
        global $member;

        $returnArray = array(
            "code" => "SUCCESS",
            "msg" => "정상 처리되었습니다"
        );

        // 해당 파일이 허용된 파일인지 확인
        if (!strpos(ALLOW_VIDEO_FILES, $file["type"])) {
            $returnArray["code"] = "FILE_NOT_ALLOWED";
            $returnArray["msg"] = "업로드 불가한 파일타입 입니다.";
            return $returnArray;
        }

        /**
         * 해당 파일 파잍 파일타입이 quicktime 일 경우 video/mp4로 저장한다
         * quicktime의 경우 윈도우에 코덱이 없음
         */
        if ($file["type"] === "video/quicktime") {
            $file["type"] = "video/mp4";
        }

        $GUID = makeGuid();

        $fileInsertSql = "
            Insert Into tb_record_request_file
            Set
                request_id = :request_id,
                file_originale_name = :file_original_name,
                file_guid = :file_guid,
                file_type = :file_type
        ";
        $param = array(
            ":request_id" => "",
            ":file_original_name" => $file["name"],
            ":file_guid" => $GUID,
            ":file_type" => $file["type"]
        )
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