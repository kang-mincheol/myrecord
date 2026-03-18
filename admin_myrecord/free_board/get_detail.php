<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");
header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['code' => 'INVALID', 'msg' => '잘못된 요청입니다.']);
    exit;
}

$post     = AdminFreeBoard::getDetail($id);
$comments = AdminFreeBoard::getComments($id);

if (!$post) {
    echo json_encode(['code' => 'NOT_FOUND', 'msg' => '게시글을 찾을 수 없습니다.']);
    exit;
}

echo json_encode([
    'code'     => 'SUCCESS',
    'post'     => $post,
    'comments' => $comments,
]);
