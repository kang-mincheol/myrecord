<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");
header('Content-Type: application/json');

$body   = json_decode(file_get_contents('php://input'), true);
$action = $body['action'] ?? '';
$id     = (int)($body['id'] ?? 0);

if ($id <= 0 || !in_array($action, ['delete_post', 'restore_post', 'delete_comment'])) {
    echo json_encode(['code' => 'INVALID', 'msg' => '잘못된 요청입니다.']);
    exit;
}

switch ($action) {
    case 'delete_post':
        $ok = AdminFreeBoard::deletePost($id);
        break;
    case 'restore_post':
        $ok = AdminFreeBoard::restorePost($id);
        break;
    case 'delete_comment':
        $ok = AdminFreeBoard::deleteComment($id);
        break;
    default:
        $ok = false;
}

echo json_encode($ok
    ? ['code' => 'SUCCESS']
    : ['code' => 'FAIL', 'msg' => '처리에 실패했습니다.']
);
