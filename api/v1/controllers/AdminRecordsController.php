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
