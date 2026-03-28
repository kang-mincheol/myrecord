<?php

class AdminDashboardController {

    private static function guardAdmin(): void {
        global $is_admin;
        if (!$is_admin) {
            http_response_code(403);
            echo json_encode(['code' => 'FORBIDDEN', 'msg' => '관리자 권한이 필요합니다'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/admin_myrecord/class/';
        require_once $dir . 'class.AdminDashboard.php';
    }

    /**
     * GET /api/v1/admin/dashboard
     * 대시보드 통계 + 최근 가입 회원 + 최근 기록 신청
     */
    public static function index(array $params): void {
        self::guardAdmin();

        $memberStats   = AdminDashboard::getMemberStats();
        $recordStats   = AdminDashboard::getRecordStats();
        $postStats     = AdminDashboard::getPostStats();
        $todayAccess   = AdminDashboard::getTodayAccess();
        $recentMembers = AdminDashboard::getRecentMembers(5);
        $recentRecords = AdminDashboard::getRecentRecords(5);

        echo json_encode([
            'code' => 'SUCCESS',
            'data' => [
                'stats' => [
                    'members'      => $memberStats,
                    'records'      => $recordStats,
                    'posts'        => $postStats,
                    'today_access' => $todayAccess,
                ],
                'recent_members' => array_map(function ($m) {
                    return [
                        'user_id'         => $m['user_id'],
                        'user_nickname'   => $m['user_nickname'],
                        'terms_marketing' => (int)$m['terms_marketing'],
                        'create_datetime' => $m['create_datetime']
                            ? date('Y.m.d', strtotime($m['create_datetime'])) : '-',
                    ];
                }, $recentMembers),
                'recent_records' => array_map(function ($r) {
                    return [
                        'record_nickname' => $r['record_nickname'] ?? '-',
                        'record_name'     => $r['record_name']     ?? '-',
                        'record_weight'   => $r['record_weight'],
                        'record_status'   => $r['record_status']   ?? '신청',
                        'status_eng'      => $r['status_eng']      ?? 'request',
                    ];
                }, $recentRecords),
            ],
        ], JSON_UNESCAPED_UNICODE);
    }
}
