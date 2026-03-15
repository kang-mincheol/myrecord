<?php
if (!defined('NO_ALONE')) exit;

class AdminDashboard {

    /**
     * 회원 통계 (활성 회원 수, 오늘 신규 가입)
     */
    public static function getMemberStats(): array {
        $total = (int)(sql_fetch("
            SELECT COUNT(*) AS cnt FROM Account
            WHERE is_withdraw = 0 OR is_withdraw IS NULL
        ")['cnt'] ?? 0);

        $today = (int)(sql_fetch("
            SELECT COUNT(*) AS cnt FROM Account
            WHERE DATE(create_datetime) = CURDATE()
        ")['cnt'] ?? 0);

        return ['total' => $total, 'today' => $today];
    }

    /**
     * 기록 통계 (전체, 오늘 등록)
     */
    public static function getRecordStats(): array {
        $total = (int)(sql_fetch("
            SELECT COUNT(*) AS cnt FROM tb_record_request
        ")['cnt'] ?? 0);

        $today = (int)(sql_fetch("
            SELECT COUNT(*) AS cnt FROM tb_record_request
            WHERE DATE(create_datetime) = CURDATE()
        ")['cnt'] ?? 0);

        return ['total' => $total, 'today' => $today];
    }

    /**
     * 자유게시판 통계 (전체, 오늘 작성)
     */
    public static function getPostStats(): array {
        $total = (int)(sql_fetch("
            SELECT COUNT(*) AS cnt FROM community_free_board
        ")['cnt'] ?? 0);

        $today = (int)(sql_fetch("
            SELECT COUNT(*) AS cnt FROM community_free_board
            WHERE DATE(create_date) = CURDATE()
        ")['cnt'] ?? 0);

        return ['total' => $total, 'today' => $today];
    }

    /**
     * 오늘 접속 수
     */
    public static function getTodayAccess(): int {
        return (int)(sql_fetch("
            SELECT COUNT(*) AS cnt FROM AccessLog
            WHERE DATE(create_date) = CURDATE()
        ")['cnt'] ?? 0);
    }

    /**
     * 최근 가입 회원 N명
     */
    public static function getRecentMembers(int $limit = 5): array {
        $list   = [];
        $result = sql_query("
            SELECT user_id, user_nickname, user_email, terms_marketing, create_datetime
            FROM Account
            ORDER BY create_datetime DESC
            LIMIT {$limit}
        ");
        while ($row = sql_fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }

    /**
     * 최근 기록 신청 N건
     */
    public static function getRecentRecords(int $limit = 5): array {
        $list   = [];
        $result = sql_query("
            SELECT
                T1.id,
                T4.user_nickname  AS record_nickname,
                T2.record_name,
                T1.record_weight,
                T3.status_text    AS record_status,
                T3.status_value   AS status_eng,
                T1.create_datetime
            FROM tb_record_request T1
            LEFT JOIN tb_record_master        T2 ON T1.record_type = T2.id
            LEFT JOIN tb_record_status_master T3 ON T1.status      = T3.id
            LEFT JOIN Account                 T4 ON T1.account_id  = T4.id
            ORDER BY T1.create_datetime DESC
            LIMIT {$limit}
        ");
        while ($row = sql_fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }
}
