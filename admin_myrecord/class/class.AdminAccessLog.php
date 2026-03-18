<?php
if (!defined('NO_ALONE')) exit;

class AdminAccessLog {

    public static function getStats(): array {
        $total      = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM AccessLog")['cnt'] ?? 0);
        $today      = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM AccessLog WHERE DATE(create_date) = CURDATE()")['cnt'] ?? 0);
        $unique_ip  = (int)(sql_fetch("SELECT COUNT(DISTINCT ip_address) AS cnt FROM AccessLog WHERE DATE(create_date) = CURDATE()")['cnt'] ?? 0);
        $member     = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM AccessLog WHERE id_users IS NOT NULL AND DATE(create_date) = CURDATE()")['cnt'] ?? 0);

        return [
            'total'     => $total,
            'today'     => $today,
            'unique_ip' => $unique_ip,
            'member'    => $member,
        ];
    }

    private static function buildWhere(string $filterType, string $searchKey, string $searchVal): string {
        global $con;

        $where = "WHERE 1=1";

        if ($filterType === 'member') {
            $where .= " AND L.id_users IS NOT NULL";
        } elseif ($filterType === 'guest') {
            $where .= " AND L.id_users IS NULL";
        }

        if ($searchVal !== '') {
            $safe = mysqli_real_escape_string($con, $searchVal);
            if ($searchKey === 'url') {
                $where .= " AND L.url LIKE '%{$safe}%'";
            } elseif ($searchKey === 'ip') {
                $where .= " AND L.ip_address LIKE '%{$safe}%'";
            } elseif ($searchKey === 'user') {
                $where .= " AND A.user_nickname LIKE '%{$safe}%'";
            }
        }

        return $where;
    }

    public static function getTotalCount(string $filterType, string $searchKey, string $searchVal): int {
        $where = self::buildWhere($filterType, $searchKey, $searchVal);
        return (int)(sql_fetch("
            SELECT COUNT(*) AS cnt
            FROM AccessLog L
            LEFT JOIN Account A ON L.id_users = A.id
            {$where}
        ")['cnt'] ?? 0);
    }

    public static function getList(int $page, int $pageSize, string $filterType, string $searchKey, string $searchVal): array {
        $offset = ($page - 1) * $pageSize;
        $where  = self::buildWhere($filterType, $searchKey, $searchVal);
        $list   = [];

        $result = sql_query("
            SELECT L.id, L.ip_address, L.url, L.user_agent, L.params, L.create_date,
                   A.user_nickname
            FROM AccessLog L
            LEFT JOIN Account A ON L.id_users = A.id
            {$where}
            ORDER BY L.id DESC
            LIMIT {$offset}, {$pageSize}
        ");

        while ($row = sql_fetch_array($result)) {
            $list[] = $row;
        }

        return $list;
    }

    /**
     * User-Agent에서 기기 종류 추출
     */
    public static function parseDevice(string $ua): string {
        $ua = strtolower($ua);
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * URL에서 메서드 판단 (params가 있으면 POST)
     */
    public static function parseMethod(string $params): string {
        return trim($params) !== '' ? 'POST' : 'GET';
    }
}
