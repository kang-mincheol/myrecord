<?php

class AdminAccountsController {

    private static function guardAdmin(): void {
        global $is_admin;
        if (!$is_admin) {
            http_response_code(403);
            echo json_encode(['code' => 'FORBIDDEN', 'msg' => '관리자 권한이 필요합니다'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/admin_myrecord/class/';
        require_once $dir . 'class.AdminAccount.php';
    }

    /**
     * GET /api/v1/admin/accounts?page=1&status=all&search_key=all&search_val=
     * 회원 목록 + 통계
     */
    public static function list(array $params): void {
        self::guardAdmin();

        $page       = max(1, (int)($_GET['page']       ?? 1));
        $page_size  = 20;
        $search_key = $_GET['search_key'] ?? 'all';
        $search_val = trim($_GET['search_val'] ?? '');
        $filter_st  = $_GET['status']     ?? 'all';

        $allowed_sk = ['all', 'user_id', 'user_nickname', 'user_email'];
        if (!in_array($search_key, $allowed_sk, true)) $search_key = 'all';
        if (!in_array($filter_st, ['all', 'normal', 'withdraw'], true)) $filter_st = 'all';

        $stats       = AdminAccount::getStats();
        $total_count = AdminAccount::getTotalCount($filter_st, $search_key, $search_val);
        $total_pages = max(1, (int)ceil($total_count / $page_size));
        if ($page > $total_pages) $page = $total_pages;

        $offset = ($page - 1) * $page_size;
        $list   = AdminAccount::getList($page, $page_size, $filter_st, $search_key, $search_val);

        $data = array_map(function ($m) {
            return [
                'id'              => (int)$m['id'],
                'user_id'         => $m['user_id'],
                'user_nickname'   => $m['user_nickname'],
                'user_name'       => $m['user_name']  ?? '',
                'user_email'      => $m['user_email'] ?? '',
                'user_phone'      => $m['user_phone'] ?? '',
                'terms_marketing' => (int)$m['terms_marketing'],
                'is_admin'        => (int)$m['is_admin'],
                'is_withdraw'     => (int)$m['is_withdraw'],
                'create_datetime' => $m['create_datetime']
                    ? date('Y.m.d H:i', strtotime($m['create_datetime'])) : '-',
                'login_date'      => $m['login_date']
                    ? date('Y.m.d H:i', strtotime($m['login_date'])) : '-',
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
