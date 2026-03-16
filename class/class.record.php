<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

class Record {

    // =========================================================
    // 종목 마스터
    // =========================================================

    /**
     * 전체 종목 목록 (order_by ASC)
     */
    public static function getRecordType(): array {
        global $PDO;
        $sql = "Select * From tb_record_master Order by order_by Asc";
        return $PDO->fetchAll($sql) ?: [];
    }

    /**
     * ID로 종목 존재 여부 확인
     */
    public static function hasRecordType(int $recordId): bool {
        global $PDO;
        $sql   = "Select count(*) as cnt From tb_record_master Where id = :id";
        $param = [":id" => $recordId];
        return (int)($PDO->fetch($sql, $param)["cnt"] ?? 0) > 0;
    }

    /**
     * record_name_lower 값으로 종목 마스터 조회
     */
    public static function getMasterByNameLower(string $nameLower): ?array {
        global $PDO;
        $sql    = "Select * From tb_record_master Where record_name_lower = :name_lower";
        $param  = [":name_lower" => $nameLower];
        $result = $PDO->fetch($sql, $param);
        return $result ?: null;
    }

    /**
     * record_name(영문) 으로 종목 ID 조회
     */
    public static function getMasterIdByName(string $name): ?int {
        global $PDO;
        $sql    = "Select id From tb_record_master Where record_name = :name";
        $param  = [":name" => $name];
        $result = $PDO->fetch($sql, $param);
        return $result ? (int)$result["id"] : null;
    }

    // =========================================================
    // 기록 조회
    // =========================================================

    /**
     * ID로 기록 단건 조회 (tb_record_request)
     */
    public static function getRecordById(int $id): ?array {
        global $PDO;
        $sql    = "Select * From tb_record_request Where id = :id";
        $param  = [":id" => $id];
        $result = $PDO->fetch($sql, $param);
        return $result ?: null;
    }

    /**
     * 회원의 종목별 최신 기록 1건 조회
     */
    public static function getLatestRecordByType(int $memberId, int $recordTypeId): ?array {
        global $PDO;
        $sql = "
            Select  *
            From    tb_record_request
            Where   account_id  = :account_id
            And     record_type = :record_type
            Order by id Desc
            Limit 1
        ";
        $param  = [":account_id" => $memberId, ":record_type" => $recordTypeId];
        $result = $PDO->fetch($sql, $param);
        return $result ?: null;
    }

    /**
     * 기록 보드 목록 조회 (페이지네이션 + 검색)
     */
    public static function getBoardList(int $recordTypeId, int $startRow, int $rows, string $searchKey = '', string $searchKeyword = ''): array {
        global $PDO;
        $param    = [":record_type" => $recordTypeId];
        $andQuery = "";

        if ($searchKey !== '' && $searchKeyword !== '') {
            if ($searchKey === "nickname") {
                $andQuery          = "And account_id = (Select id From Account Where user_nickname Like :keyword)";
                $param[":keyword"] = "%{$searchKeyword}%";
            } elseif ($searchKey === "weight") {
                $andQuery          = "And record_weight = :keyword";
                $param[":keyword"] = preg_replace("/[^0-9]/u", "", $searchKeyword);
            }
        }

        $sql = "
            Select  T1.id, T1.record_type, T1.record_weight, T1.status, T1.create_datetime,
                    T2.user_nickname, T3.status_text
            From    tb_record_request T1
            Inner Join  Account                 T2 On T1.account_id = T2.id
            Inner Join  tb_record_status_master T3 On T1.status     = T3.id
            Where   T1.record_type = :record_type
            {$andQuery}
            And     T1.is_delete = 0
            Order by T1.create_datetime Desc
            Limit   {$startRow}, {$rows}
        ";
        return $PDO->fetchAll($sql, $param) ?: [];
    }

    /**
     * 기록 보드 총 건수 (is_delete = 0 기준)
     */
    public static function getBoardCount(int $recordTypeId): int {
        global $PDO;
        $sql   = "Select count(*) as cnt From tb_record_request Where record_type = :record_type And is_delete = 0";
        $param = [":record_type" => $recordTypeId];
        return (int)($PDO->fetch($sql, $param)["cnt"] ?? 0);
    }

    /**
     * 기록 view 데이터 조회 (4-table JOIN)
     */
    public static function getRecordViewData(int $recordId): ?array {
        global $PDO;
        $sql = "
            Select  T4.id as account_id, T4.user_nickname,
                    T2.record_name, T2.record_name_ko,
                    T1.record_weight, T1.memo,
                    T3.status_text, T3.status_value,
                    DATE_FORMAT(T1.create_datetime, '%Y.%m.%d') as create_date
            From    tb_record_request T1
            Inner Join  tb_record_master        T2 On T1.record_type = T2.id
            Inner Join  tb_record_status_master T3 On T1.status      = T3.id
            Inner Join  Account                 T4 On T1.account_id  = T4.id
            Where   T1.id = :id
        ";
        $param  = [":id" => $recordId];
        $result = $PDO->fetch($sql, $param);
        return $result ?: null;
    }

    /**
     * 기록 첨부 파일 목록 조회
     */
    public static function getFilesByRequestId(int $requestId): array {
        global $PDO;
        $sql   = "Select * From tb_record_request_file Where request_id = :request_id";
        $param = [":request_id" => $requestId];
        return $PDO->fetchAll($sql, $param) ?: [];
    }

    /**
     * 인증서 데이터 조회 (승인된 기록)
     */
    public static function getCertificateData(int $recordId): ?array {
        global $PDO;
        $sql = "
            Select  T1.account_id, T1.status, T2.user_nickname,
                    T1.record_weight, T3.record_name_ko,
                    T1.create_datetime as request_datetime,
                    T4.create_datetime as certificate_datetime
            From    tb_record_request T1
            Inner Join (
                Select  id, user_nickname From Account
            ) T2 On T1.account_id = T2.id
            Inner Join  tb_record_master T3 On T1.record_type = T3.id
            Left Outer Join tb_record_inspection T4
                On  T1.id = T4.request_id
                And T4.change_status = '2'
            Where   T1.id = :id
        ";
        $param  = [":id" => $recordId];
        $result = $PDO->fetch($sql, $param);
        return $result ?: null;
    }

    // =========================================================
    // 기록 등록 / 삭제
    // =========================================================

    /**
     * 동일 종목 중복 신청 여부 확인
     */
    public static function checkOverlapRequest(int $accountId, int $recordType, int $status): bool {
        global $PDO;
        $sql = "
            Select  count(*) as cnt
            From    tb_record_request
            Where   account_id  = :account_id
            And     record_type = :record_type
            And     status      = :status
        ";
        $param = [":account_id" => $accountId, ":record_type" => $recordType, ":status" => $status];
        return (int)($PDO->fetch($sql, $param)["cnt"] ?? 0) > 0;
    }

    /**
     * 기록 신청 Insert — 생성된 request_id 반환
     */
    public static function insertRecordRequest(int $accountId, int $recordType, int $weight, string $memo): int {
        global $PDO;
        $sql = "
            Insert Into tb_record_request
            Set
                account_id    = :account_id,
                record_type   = :record_type,
                record_weight = :record_weight,
                memo          = :memo,
                status        = :status
        ";
        $param = [
            ":account_id"    => $accountId,
            ":record_type"   => $recordType,
            ":record_weight" => $weight,
            ":memo"          => $memo,
            ":status"        => 0,
        ];
        return (int)$PDO->execute($sql, $param);
    }

    /**
     * 기록 첨부 파일 Insert
     */
    public static function insertRecordFile(int $requestId, string $originalName, string $fileGuid, string $fileType): void {
        global $PDO;
        $sql = "
            Insert Into tb_record_request_file
            Set
                request_id         = :request_id,
                file_original_name = :file_original_name,
                file_guid          = :file_guid,
                file_type          = :file_type
        ";
        $param = [
            ":request_id"         => $requestId,
            ":file_original_name" => $originalName,
            ":file_guid"          => $fileGuid,
            ":file_type"          => $fileType,
        ];
        $PDO->execute($sql, $param);
    }

    /**
     * 기록 삭제 (파일 물리 삭제 + DB 삭제 + 검증이력 삭제 + 기록 요청 삭제)
     */
    public static function deleteRecord(int $recordId): void {
        global $PDO;

        // 첨부 파일 물리 삭제 + DB 삭제
        $files = self::getFilesByRequestId($recordId);
        foreach ($files as $file) {
            @unlink($_SERVER["DOCUMENT_ROOT"] . "/data/record/" . $file["file_guid"]);
            $PDO->execute(
                "Delete From tb_record_request_file Where file_guid = :guid",
                [":guid" => $file["file_guid"]]
            );
        }

        // 검증 이력 삭제
        $PDO->execute(
            "Delete From tb_record_inspection Where request_id = :id",
            [":id" => $recordId]
        );

        // 기록 요청 삭제
        $PDO->execute(
            "Delete From tb_record_request Where id = :id",
            [":id" => $recordId]
        );
    }

    // =========================================================
    // 랭킹
    // =========================================================

    /**
     * 종목별 랭킹 (상위 10명, 승인 완료 기준)
     */
    public static function getRankingByTypeId(int $recordTypeId): array {
        global $PDO;
        $sql = "
            Select  max(T1.record_weight) as weight, T1.account_id, T1.id, T2.user_nickname
            From    tb_record_request T1
            Inner Join Account T2 On T1.account_id = T2.id
            Where   T1.record_type = :record_type
            And     T1.status = 2
            Group by T1.account_id, T1.id
            Order by weight Desc
            Limit 0, 10
        ";
        $param = [":record_type" => $recordTypeId];
        return $PDO->fetchAll($sql, $param) ?: [];
    }

    /**
     * 3대 통합 랭킹 (상위 10명, 승인 완료 기준)
     */
    public static function getTotalRanking(): array {
        global $PDO;
        $sql = "
            Select  Account.id, Account.user_id, Account.user_nickname,
                    IfNull(Squat.weight, 0)      as squat,
                    IfNull(Squat.id, '-')        as squat_id,
                    IfNull(Benchpress.weight, 0) as bench,
                    IfNull(Benchpress.id, '-')   as bench_id,
                    IfNull(Deadlift.weight, 0)   as dead,
                    IfNull(Deadlift.id, '-')     as dead_id,
                    Sum(
                        IfNull(Squat.weight, 0) +
                        IfNull(Benchpress.weight, 0) +
                        IfNull(Deadlift.weight, 0)
                    ) as total_sum
            From    Account
            Left Outer Join (
                Select  max(record_weight) as weight, account_id, id
                From    tb_record_request
                Where   record_type = 1 And status = 2
                Group by account_id, id
            ) Squat       On Account.id = Squat.account_id
            Left Outer Join (
                Select  max(record_weight) as weight, account_id, id
                From    tb_record_request
                Where   record_type = 2 And status = 2
                Group by account_id, id
            ) Benchpress  On Account.id = Benchpress.account_id
            Left Outer Join (
                Select  max(record_weight) as weight, account_id, id
                From    tb_record_request
                Where   record_type = 3 And status = 2
                Group by account_id, id
            ) Deadlift    On Account.id = Deadlift.account_id
            Where (Squat.weight > 0 Or Benchpress.weight > 0 Or Deadlift.weight)
            Group by Account.id, Account.user_id, Account.user_nickname,
                     squat, bench, dead, squat_id, bench_id, dead_id
            Order by total_sum Desc
            Limit 0, 10
        ";
        return $PDO->fetchAll($sql) ?: [];
    }

    // =============================================
    // 댓글
    // =============================================

    /**
     * 댓글 목록 조회
     */
    public static function getComments(int $recordId): array {
        global $PDO;

        $sql = "
            Select  C.id, C.contents, C.account_no, C.create_datetime,
                    A.user_nickname
            From    record_comment C
            Inner Join Account A On C.account_no = A.id
            Where   C.record_id = :record_id
            And     C.is_delete = 0
            Order by C.id Asc
        ";
        $param = [":record_id" => $recordId];

        return $PDO->fetchAll($sql, $param);
    }

    /**
     * 댓글 수 조회
     */
    public static function getCommentCount(int $recordId): int {
        global $PDO;

        $sql = "
            Select count(*) as cnt
            From   record_comment
            Where  record_id = :record_id
            And    is_delete = 0
        ";
        $param = [":record_id" => $recordId];

        return (int)$PDO->fetch($sql, $param)["cnt"];
    }

    /**
     * 댓글 작성
     */
    public static function insertComment(int $recordId, string $contents): int {
        global $PDO;
        global $member;

        $sql = "
            Insert Into record_comment
            Set record_id  = :record_id,
                account_no = :account_no,
                contents   = :contents
        ";
        $param = [
            ":record_id"  => $recordId,
            ":account_no" => $member["id"],
            ":contents"   => $contents
        ];

        return $PDO->execute($sql, $param);
    }

    /**
     * 댓글 본인 여부 확인
     */
    public static function isCommentOwner(int $commentId): bool {
        global $PDO;
        global $member;

        if (is_null($member)) return false;

        $sql = "
            Select account_no
            From   record_comment
            Where  id = :id
            And    is_delete = 0
        ";
        $param = [":id" => $commentId];

        $row = $PDO->fetch($sql, $param);
        if (!$row) return false;

        return (int)$member["id"] === (int)$row["account_no"];
    }

    /**
     * 댓글 소프트 삭제
     */
    public static function deleteComment(int $commentId): void {
        global $PDO;

        $sql = "
            Update record_comment
            Set    is_delete = 1
            Where  id = :id
        ";
        $param = [":id" => $commentId];

        $PDO->execute($sql, $param);
    }
}
