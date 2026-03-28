<?php

class AdminAccessLogsController {

    private static function guardAdmin(): void {
        global $is_admin;
        if (!$is_admin) {
            http_response_code(403);
            echo json_encode(['code' => 'FORBIDDEN', 'msg' => '관리자 권한이 필요합니다'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/admin_myrecord/class/';
        require_once $dir . 'class.AdminAccessLog.php';
    }

    /**
     * GET /api/v1/admin/access-logs?page=1&type=all&search_key=url&search_val=
     * 접속 로그 목록 + 통계
     */
    public static function list(array $params): void {
        self::guardAdmin();

        $page        = max(1, (int)($_GET['page']       ?? 1));
        $page_size   = 30;
        $filter_type = $_GET['type']       ?? 'all';
        $search_key  = $_GET['search_key'] ?? 'url';
        $search_val  = trim($_GET['search_val'] ?? '');

        if (!in_array($filter_type, ['all', 'member', 'guest'], true)) $filter_type = 'all';
        if (!in_array($search_key,  ['url', 'ip', 'user'], true))      $search_key  = 'url';

        $stats       = AdminAccessLog::getStats();
        $total_count = AdminAccessLog::getTotalCount($filter_type, $search_key, $search_val);
        $total_pages = max(1, (int)ceil($total_count / $page_size));
        if ($page > $total_pages) $page = $total_pages;

        $offset = ($page - 1) * $page_size;
        $list   = AdminAccessLog::getList($page, $page_size, $filter_type, $search_key, $search_val);

        $data = array_map(function ($r) {
            return [
                'id'           => (int)$r['id'],
                'ip_address'   => $r['ip_address']   ?? '-',
                'url'          => $r['url']           ?? '-',
                'user_agent'   => $r['user_agent']    ?? '',
                'user_nickname'=> $r['user_nickname'] ?? null,
                'method'       => AdminAccessLog::parseMethod($r['params'] ?? ''),
                'device'       => AdminAccessLog::parseDevice($r['user_agent'] ?? ''),
                'create_date'  => $r['create_date']
                    ? date('Y.m.d H:i', strtotime($r['create_date'])) : '-',
            ];
        }, $list);

        echo json_encode([
            'code'        => 'SUCCESS',
            'stats'       => $stats,
            'data'        => $data,
            'total_count' => $total_count,
            'page'        => $page,
            'page_size'   => $page_size,
            'total_pages' => $total_pages,
            'offset'      => $offset,
        ], JSON_UNESCAPED_UNICODE);
    }
}
