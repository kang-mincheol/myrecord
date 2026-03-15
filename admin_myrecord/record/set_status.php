<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");

header('Content-Type: application/json; charset=utf-8');

$data    = json_decode(file_get_contents('php://input'), true);
$id      = isset($data['id'])      ? (int)$data['id']      : 0;
$status  = isset($data['status'])  ? (int)$data['status']  : -1;
$comment = isset($data['comment']) ? trim($data['comment']) : '';

// ===== 유효성 검사 =====
if ($id <= 0) {
    echo json_encode(['code' => 'FAIL', 'msg' => '잘못된 요청입니다.']);
    exit;
}

$allowed = [0, 1, 2, 9];
if (!in_array($status, $allowed)) {
    echo json_encode(['code' => 'FAIL', 'msg' => '유효하지 않은 상태값입니다.']);
    exit;
}

if ($comment === '') {
    echo json_encode(['code' => 'FAIL', 'msg' => '검증 코멘트를 입력해주세요.']);
    exit;
}

// ===== 기록 존재 확인 =====
$row = AdminRecord::getDetail($id);
if (!$row) {
    echo json_encode(['code' => 'FAIL', 'msg' => '존재하지 않는 기록입니다.']);
    exit;
}

// ===== 상태 업데이트 =====
AdminRecord::updateStatus($id, $status);

// ===== 검증 이력 저장 =====
$admin_id = (int)($member['id'] ?? 0);
AdminRecord::addInspection($id, $admin_id, $comment, $status);

echo json_encode(['code' => 'SUCCESS', 'msg' => '상태가 변경되었습니다.']);
exit;
