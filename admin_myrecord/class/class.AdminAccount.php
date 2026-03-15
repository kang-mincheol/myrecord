<?php
if (!defined('NO_ALONE')) exit;

class AdminAccount {

    /**
     * 회원 통계 (전체, 오늘 가입, 마케팅 동의, 탈퇴)
     */
    public static function getStats(): array {
        $total    = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account")['cnt'] ?? 0);
        $today    = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account WHERE DATE(create_datetime) = CURDATE()")['cnt'] ?? 0);
        $marketing = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account WHERE terms_marketing = 1 AND (is_withdraw = 0 OR is_withdraw IS NULL)")['cnt'] ?? 0);
        $withdraw  = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account WHERE is_withdraw = 1")['cnt'] ?? 0);

        return [
            'total'     => $total,
            'today'     => $today,
            'marketing' => $marketing,
            'withdraw'  => $withdraw,
        ];
    }

    /**
     * 검색/필터 조건으로 WHERE 절 생성
     */
    private static function buildWhere(string $filterStatus, string $searchKey, string $searchVal): string {
        global $con;
        $where = "WHERE 1=1";

        if ($filterStatus === 'normal') {
            $where .= " AND (is_withdraw = 0 OR is_withdraw IS NULL)";
        } elseif ($filterStatus === 'withdraw') {
            $where .= " AND is_withdraw = 1";
        }

        if ($searchVal !== '') {
            $safe = mysqli_real_escape_string($con, $searchVal);
            if ($searchKey === 'user_id') {
                $where .= " AND user_id LIKE '%{$safe}%'";
            } elseif ($searchKey === 'user_nickname') {
                $where .= " AND user_nickname LIKE '%{$safe}%'";
            } elseif ($searchKey === 'user_email') {
                $where .= " AND user_email LIKE '%{$safe}%'";
            } else {
                $where .= " AND (user_id LIKE '%{$safe}%' OR user_nickname LIKE '%{$safe}%' OR user_email LIKE '%{$safe}%')";
            }
        }

        return $where;
    }

    /**
     * 검색/필터 조건에 맞는 총 회원 수
     */
    public static function getTotalCount(string $filterStatus, string $searchKey, string $searchVal): int {
        $where = self::buildWhere($filterStatus, $searchKey, $searchVal);
        return (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account {$where}")['cnt'] ?? 0);
    }

    /**
     * 회원 목록 (페이지네이션 + 검색/필터)
     */
    public static function getList(int $page, int $pageSize, string $filterStatus, string $searchKey, string $searchVal): array {
        $offset = ($page - 1) * $pageSize;
        $where  = self::buildWhere($filterStatus, $searchKey, $searchVal);
        $list   = [];

        $result = sql_query("
            SELECT
                id, user_id, user_nickname, user_name,
                user_email, user_phone, terms_marketing,
                create_datetime, login_date, is_admin, is_withdraw
            FROM Account
            {$where}
            ORDER BY create_datetime DESC
            LIMIT {$pageSize} OFFSET {$offset}
        ");
        while ($row = sql_fetch_array($result)) {
            $list[] = $row;
        }
        return $list;
    }
}
