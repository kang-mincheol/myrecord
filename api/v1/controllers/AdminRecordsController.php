<?php

class AdminRecordsController {

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
        require_once $adminClassDir . 'class.AdminRecord.php';
    }

    /**
     * GET /api/v1/admin/records?page=1&record=0&status=all&search_val=
     * 기록 목록 + 통계 + 종목 마스터
     */
    public static function list(array $params): void {
        self::guardAdmin();

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/admin_myrecord/class/';
        require_once $dir . 'class.AdminRecord.php';

        $page          = max(1, (int)($_GET['page']       ?? 1));
        $page_size     = 20;
        $filter_record = (int)($_GET['record'] ?? 0);
        $filter_status = $_GET['status']    ?? 'all';
        $search_val    = trim($_GET['search_val'] ?? '');

        $allowed_status = ['all', '0', '1', '2', '9'];
        if (!in_array($filter_status, $allowed_status, true)) $filter_status = 'all';

        $stats   = AdminRecord::getStats();
        $masters = AdminRecord::getMasterList();

        $total_count = AdminRecord::getTotalCount($filter_record, $filter_status, $search_val);
        $total_pages = max(1, (int)ceil($total_count / $page_size));
        if ($page > $total_pages) $page = $total_pages;

        $offset = ($page - 1) * $page_size;
        $list   = AdminRecord::getList($page, $page_size, $filter_record, $filter_status, $search_val);

        $data = array_map(function ($r) {
            return [
                'id'            => (int)$r['id'],
                'user_nickname' => $r['user_nickname']  ?? '-',
                'user_id'       => $r['user_id']        ?? '-',
                'record_name'   => $r['record_name']    ?? '-',
                'record_name_ko'=> $r['record_name_ko'] ?? '-',
                'record_weight' => $r['record_weight'],
                'memo'          => $r['memo'] ?? '',
                'status_text'   => $r['status_text']  ?? '신청',
                'status_value'  => $r['status_value'] ?? 'request',
                'create_datetime' => $r['create_datetime']
                    ? date('Y.m.d', strtotime($r['create_datetime'])) : '-',
            ];
        }, $list);

        echo json_encode([
            'code'        => 'SUCCESS',
            'stats'       => $stats,
            'masters'     => $masters,
            'data'        => $data,
            'total_count' => $total_count,
            'page'        => $page,
            'page_size'   => $page_size,
            'total_pages' => $total_pages,
            'offset'      => $offset,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/admin/records/{id}
     * 기록 상세 조회 (첨부파일, 검증이력 포함)
     */
    public static function view(array $params): void {
        self::guardAdmin();

        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['code' => 'FAIL', 'msg' => '잘못된 요청입니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $record = AdminRecord::getDetail($id);
        if (!$record) {
            http_response_code(404);
            echo json_encode(['code' => 'NOT_FOUND', 'msg' => '존재하지 않는 기록입니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $files       = AdminRecord::getFiles($id);
        $inspections = AdminRecord::getInspections($id);

        echo json_encode([
            'code'   => 'SUCCESS',
            'record' => [
                'id'              => $record['id'],
                'user_id'         => $record['user_id']        ?? '-',
                'user_nickname'   => $record['user_nickname']  ?? '-',
                'record_name'     => $record['record_name']    ?? '-',
                'record_name_ko'  => $record['record_name_ko'] ?? '-',
                'record_weight'   => $record['record_weight'],
                'memo'            => $record['memo']           ?? '',
                'status_id'       => (int)$record['status'],
                'status_text'     => $record['status_text']    ?? '-',
                'status_value'    => $record['status_value']   ?? 'request',
                'create_datetime' => $record['create_datetime']
                    ? date('Y.m.d H:i', strtotime($record['create_datetime']))
                    : '-',
            ],
            'files'       => $files,
            'inspections' => $inspections,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * PUT /api/v1/admin/records/{id}/status
     * 기록 상태 변경 + 검증 이력 추가
     */
    public static function updateStatus(array $params): void {
        global $member;
        self::guardAdmin();

        $id   = (int)($params['id'] ?? 0);
        $data = json_decode(file_get_contents('php://input'), true);

        $status  = isset($data['status'])  ? (int)$data['status']      : -1;
        $comment = isset($data['comment']) ? trim($data['comment'])     : '';

        if ($id <= 0) {
            echo json_encode(['code' => 'FAIL', 'msg' => '잘못된 요청입니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $allowed = [0, 1, 2, 9];
        if (!in_array($status, $allowed)) {
            echo json_encode(['code' => 'FAIL', 'msg' => '유효하지 않은 상태값입니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($comment === '') {
            echo json_encode(['code' => 'FAIL', 'msg' => '검증 코멘트를 입력해주세요.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $row = AdminRecord::getDetail($id);
        if (!$row) {
            http_response_code(404);
            echo json_encode(['code' => 'NOT_FOUND', 'msg' => '존재하지 않는 기록입니다.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        AdminRecord::updateStatus($id, $status);

        $admin_id = (int)($member['id'] ?? 0);
        AdminRecord::addInspection($id, $admin_id, $comment, $status);

        echo json_encode(['code' => 'SUCCESS', 'msg' => '상태가 변경되었습니다.'], JSON_UNESCAPED_UNICODE);
    }
}
