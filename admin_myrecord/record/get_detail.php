<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");

header('Content-Type: application/json; charset=utf-8');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode(['code' => 'FAIL', 'msg' => '잘못된 요청입니다.']);
    exit;
}

// ===== 기본 정보 =====
$record = AdminRecord::getDetail($id);

if (!$record) {
    echo json_encode(['code' => 'FAIL', 'msg' => '존재하지 않는 기록입니다.']);
    exit;
}

// ===== 첨부 파일 =====
$files = AdminRecord::getFiles($id);

// ===== 검증 이력 =====
$inspections = AdminRecord::getInspections($id);

echo json_encode([
    'code' => 'SUCCESS',
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
        'create_datetime' => $record['create_datetime'] ? date('Y.m.d H:i', strtotime($record['create_datetime'])) : '-',
    ],
    'files'       => $files,
    'inspections' => $inspections,
], JSON_UNESCAPED_UNICODE);
exit;
