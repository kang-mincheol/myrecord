<?php

class AdminBoardsController {

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
        require_once $adminClassDir . 'class.AdminFreeBoard.php';
    }

    /**
     * GET /api/v1/admin/boards?page=1&status=all&search_key=title&search_val=
     * 게시글 목록 + 통계
     */
    public static function list(array $params): void {
        self::guardAdmin();

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/admin_myrecord/class/';
        require_once $dir . 'class.AdminFreeBoard.php';

        $page          = max(1, (int)($_GET['page']       ?? 1));
        $page_size     = 20;
        $filter_status = $_GET['status']     ?? 'all';
        $search_key    = $_GET['search_key'] ?? 'title';
        $search_val    = trim($_GET['search_val'] ?? '');

        if (!in_array($search_key, ['title', 'contents', 'writer'], true)) $search_key = 'title';
        if (!in_array($filter_status, ['all', 'deleted'], true)) $filter_status = 'all';

        $stats       = AdminFreeBoard::getStats();
        $total_count = AdminFreeBoard::getTotalCount($filter_status, $search_key, $search_val);
        $total_pages = max(1, (int)ceil($total_count / $page_size));
        if ($page > $total_pages) $page = $total_pages;

        $offset = ($page - 1) * $page_size;
        $list   = AdminFreeBoard::getList($page, $page_size, $filter_status, $search_key, $search_val);

        $data = array_map(function ($r) {
            return [
                'id'            => (int)$r['id'],
                'title'         => $r['title'],
                'user_nickname' => $r['user_nickname'] ?? '-',
                'comment_count' => (int)$r['comment_count'],
                'view_count'    => (int)$r['view_count'],
                'is_delete'     => (int)$r['is_delete'],
                'create_date'   => $r['create_date']
                    ? date('Y.m.d', strtotime($r['create_date'])) : '-',
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

    /**
     * GET /api/v1/admin/boards/{id}
     * 게시글 상세 조회 (댓글 포함)
     */
    public static function view(array $params): void {
        self::guardAdmin();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['code' => 'INVALID', 'msg' => '잘못된 요청입니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $post     = AdminFreeBoard::getDetail($id);
        $comments = AdminFreeBoard::getComments($id);

        if (!$post) {
            http_response_code(404);
            echo json_encode(['code' => 'NOT_FOUND', 'msg' => '게시글을 찾을 수 없습니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo json_encode([
            'code'     => 'SUCCESS',
            'post'     => $post,
            'comments' => $comments,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * DELETE /api/v1/admin/boards/{id}
     * 게시글 소프트 삭제
     */
    public static function delete(array $params): void {
        self::guardAdmin();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['code' => 'INVALID', 'msg' => '잘못된 요청입니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $ok = AdminFreeBoard::deletePost($id);
        echo json_encode(
            $ok ? ['code' => 'SUCCESS'] : ['code' => 'FAIL', 'msg' => '처리에 실패했습니다.'],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * POST /api/v1/admin/boards/{id}/restore
     * 게시글 복원
     */
    public static function restore(array $params): void {
        self::guardAdmin();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['code' => 'INVALID', 'msg' => '잘못된 요청입니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $ok = AdminFreeBoard::restorePost($id);
        echo json_encode(
            $ok ? ['code' => 'SUCCESS'] : ['code' => 'FAIL', 'msg' => '처리에 실패했습니다.'],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * DELETE /api/v1/admin/boards/{id}/comments/{cid}
     * 댓글 삭제
     */
    public static function deleteComment(array $params): void {
        self::guardAdmin();

        $cid = (int)($params['cid'] ?? 0);
        if ($cid <= 0) {
            echo json_encode(['code' => 'INVALID', 'msg' => '잘못된 요청입니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $ok = AdminFreeBoard::deleteComment($cid);
        echo json_encode(
            $ok ? ['code' => 'SUCCESS'] : ['code' => 'FAIL', 'msg' => '삭제에 실패했습니다.'],
            JSON_UNESCAPED_UNICODE
        );
    }
}
