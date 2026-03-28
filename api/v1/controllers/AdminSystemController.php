<?php

class AdminSystemController {

    /**
     * 관리자 권한 확인 공통 처리
     * admin이 아닐 경우 403 응답 후 exit
     */
    private static function guardAdmin(): void {
        global $is_admin;
        if (!$is_admin) {
            http_response_code(403);
            echo json_encode(['code' => 'FORBIDDEN', 'msg' => '관리자 권한이 필요합니다'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $adminClassDir = $_SERVER['DOCUMENT_ROOT'] . '/admin_myrecord/class/';
        require_once $adminClassDir . 'class.AdminSystem.php';
    }

    /**
     * GET /api/v1/admin/system/purge-stats
     * 영구 삭제 대상 게시글/댓글/파일 통계 조회
     */
    public static function purgeStats(array $params): void {
        self::guardAdmin();

        $result = AdminSystem::getPurgeStats();

        echo json_encode([
            'code' => 'SUCCESS',
            'data' => $result,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * DELETE /api/v1/admin/system/expired-boards
     * 소프트 삭제된 지 1년 이상 경과한 게시글 영구 삭제
     */
    public static function purgeExpiredBoards(array $params): void {
        self::guardAdmin();

        $result = AdminSystem::purgeExpiredBoards();

        echo json_encode([
            'code' => 'SUCCESS',
            'msg'  => '정상 처리되었습니다.',
            'data' => $result,
        ], JSON_UNESCAPED_UNICODE);
    }
}
