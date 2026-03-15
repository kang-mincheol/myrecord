<?php
if (!defined('NO_ALONE')) exit;

class AdminRecord {

    /**
     * 기록 상태별 통계
     */
    public static function getStats(): array {
        $total    = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM tb_record_request WHERE is_delete = 0")['cnt'] ?? 0);
        $request  = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM tb_record_request WHERE is_delete = 0 AND status = 0")['cnt'] ?? 0);
        $audit    = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM tb_record_request WHERE is_delete = 0 AND status = 1")['cnt'] ?? 0);
        $approval = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM tb_record_request WHERE is_delete = 0 AND status = 2")['cnt'] ?? 0);
        $reject   = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM tb_record_request WHERE is_delete = 0 AND status = 9")['cnt'] ?? 0);

        return [
            'total'    => $total,
            'request'  => $request,
            'audit'    => $audit,
            'approval' => $approval,
            'reject'   => $reject,
        ];
    }

    /**
     * 종목 목록 (tb_record_master)
     */
    public static function getMasterList(): array {
        $list   = [];
        $result = sql_query("SELECT id, record_name, record_name_ko FROM tb_record_master ORDER BY order_by ASC");
        while ($row = sql_fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }

    /**
     * 검색/필터 조건으로 WHERE 절 생성 (JOIN 포함 쿼리용)
     */
    private static function buildWhere(int $filterRecord, string $filterStatus, string $searchVal): string {
        global $con;
        $where = "WHERE T1.is_delete = 0";

        if ($filterRecord > 0) {
            $where .= " AND T1.record_type = {$filterRecord}";
        }
        if ($filterStatus !== 'all') {
            $where .= " AND T1.status = " . (int)$filterStatus;
        }
        if ($searchVal !== '') {
            $safe   = mysqli_real_escape_string($con, $searchVal);
            $where .= " AND T4.user_nickname LIKE '%{$safe}%'";
        }

        return $where;
    }

    /**
     * 검색/필터 조건에 맞는 총 기록 수
     */
    public static function getTotalCount(int $filterRecord, string $filterStatus, string $searchVal): int {
        $where = self::buildWhere($filterRecord, $filterStatus, $searchVal);
        return (int)(sql_fetch("
            SELECT COUNT(*) AS cnt
            FROM tb_record_request T1
            LEFT JOIN Account T4 ON T1.account_id = T4.id
            {$where}
        ")['cnt'] ?? 0);
    }

    /**
     * 기록 목록 (페이지네이션 + 검색/필터)
     */
    public static function getList(int $page, int $pageSize, int $filterRecord, string $filterStatus, string $searchVal): array {
        $offset = ($page - 1) * $pageSize;
        $where  = self::buildWhere($filterRecord, $filterStatus, $searchVal);
        $list   = [];

        $result = sql_query("
            SELECT
                T1.id, T1.record_weight, T1.memo, T1.status, T1.create_datetime,
                T2.record_name, T2.record_name_ko,
                T3.status_text, T3.status_value,
                T4.user_nickname, T4.user_id
            FROM tb_record_request T1
            LEFT JOIN tb_record_master        T2 ON T1.record_type = T2.id
            LEFT JOIN tb_record_status_master T3 ON T1.status      = T3.id
            LEFT JOIN Account                 T4 ON T1.account_id  = T4.id
            {$where}
            ORDER BY T1.create_datetime DESC
            LIMIT {$pageSize} OFFSET {$offset}
        ");
        while ($row = sql_fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }

    /**
     * 기록 상세 (단건)
     */
    public static function getDetail(int $id): ?array {
        $row = sql_fetch("
            SELECT
                T1.id, T1.record_weight, T1.memo, T1.status, T1.create_datetime,
                T2.record_name, T2.record_name_ko,
                T3.status_text, T3.status_value,
                T4.user_id, T4.user_nickname
            FROM tb_record_request T1
            LEFT JOIN tb_record_master        T2 ON T1.record_type = T2.id
            LEFT JOIN tb_record_status_master T3 ON T1.status      = T3.id
            LEFT JOIN Account                 T4 ON T1.account_id  = T4.id
            WHERE T1.id = {$id} AND T1.is_delete = 0
        ");
        return $row ?: null;
    }

    /**
     * 기록에 첨부된 파일 목록
     */
    public static function getFiles(int $id): array {
        $files  = [];
        $result = sql_query("
            SELECT file_guid, file_original_name, file_type
            FROM tb_record_request_file
            WHERE request_id = {$id}
            ORDER BY create_datetime ASC
        ");
        while ($f = sql_fetch_array($result)) {
            $mime    = $f['file_type'] ?? '';
            $files[] = [
                'guid'          => $f['file_guid'],
                'original_name' => $f['file_original_name'],
                'file_type'     => $mime,
                'is_image'      => strpos($mime, 'image/') === 0,
                'is_video'      => strpos($mime, 'video/') === 0,
                'src'           => RECORD_FILE_DIR . $f['file_guid'],
            ];
        }
        return $files;
    }

    /**
     * 검증 이력 목록
     */
    public static function getInspections(int $id): array {
        $list   = [];
        $result = sql_query("
            SELECT
                T1.id, T1.admin_comment, T1.change_status, T1.create_datetime,
                T2.user_nickname  AS admin_nickname,
                T3.status_text, T3.status_value
            FROM tb_record_inspection T1
            LEFT JOIN Account                 T2 ON T1.admin_id      = T2.id
            LEFT JOIN tb_record_status_master T3 ON T1.change_status = T3.id
            WHERE T1.request_id = {$id}
            ORDER BY T1.create_datetime DESC
        ");
        while ($i = sql_fetch_array($result)) {
            $list[] = [
                'admin_nickname' => $i['admin_nickname'] ?? '-',
                'admin_comment'  => $i['admin_comment'],
                'status_text'    => $i['status_text']  ?? '-',
                'status_value'   => $i['status_value'] ?? 'request',
                'datetime'       => $i['create_datetime'] ? date('Y.m.d H:i', strtotime($i['create_datetime'])) : '-',
            ];
        }
        return $list;
    }

    /**
     * 기록 상태 변경
     */
    public static function updateStatus(int $id, int $status): void {
        sql_query("UPDATE tb_record_request SET status = {$status} WHERE id = {$id}");
    }

    /**
     * 검증 이력 추가
     */
    public static function addInspection(int $requestId, int $adminId, string $comment, int $status): void {
        global $con;
        $safe = mysqli_real_escape_string($con, $comment);
        sql_query("
            INSERT INTO tb_record_inspection
                (request_id, admin_id, admin_comment, change_status)
            VALUES
                ({$requestId}, {$adminId}, '{$safe}', {$status})
        ");
    }
}
