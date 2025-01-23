<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

class Record {

    /**
     * 레코드 종류 가져오는 함수
     */
    public static function getRecordType() {
        global $PDO;

        $sql = "
            Select  *
            From    tb_record_master
            Order by order_by Asc
        ";
        
        return $PDO->fetchAll($sql);
    }

    /**
     * 레코드 id 값으로 해당 종목이 존재하는지 확인
     */
    public static function hasRecordType($record_id) {
        global $PDO;

        $sql = "
            Select  count(*) as cnt
            From    tb_record_master
            Where   id = :id
        ";

        $param = array(
            ":id" => $record_id
        );

        $result = $PDO->fetch($sql, $param)["cnt"];

        return $result > 0 ? true : false;
    }

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

        // 파일 사이즈 체크
        $postMaxSize = ini_get("post_max_size"); // @M
        $uploadFileMaxSize = ini_get("upload_max_filesize"); // @M
        if ($file["size"] > $uploadFileMaxSize) {
            $returnArray["code"] = "FILE_SIZE_LIMIT";
            $returnArray["msg"] = "파일은 총 " + $uploadFileMaxSize + "MB 이하로 업로드 해주세요";
            return $returnArray;
        }

        // 해당 파일이 허용된 파일인지 확인
        if (!strpos(ALLOW_RECORD_FILES, $file["type"])) {
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
        );


    }

    /**
     * 회원 아이디, 레코드 종목 id값, 심사 상태 id 값으로
     * 현재 데이터가 존재하는지 확인하는 함수
     */
    public static function hasRecordDataByStatus($recordTypeId, $status) {
        global $member;
        global $PDO;

        $returnArray = array(
            "code" => "SUCCESS",
            "msg" => "정상 처리되었습니다"
        );

        if (is_null($member)) {
            
        }

        $sql = "
            Select  count(*) as cnt
            From    tb_record_request
            Where   account_id = :account_id
            And     record_type = :record_type
            And     status = :status
        ";

        $param = array(
            ":account_id" => $member["id"],
            ":record_type" => $recordTypeId,
            ":status" => $status
        )

        $result = $PDO->fetch($sql, $param)["cnt"];

        return $result > 0 ? true : false;
    }
}

?>