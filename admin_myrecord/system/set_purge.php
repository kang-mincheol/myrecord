<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");

header('Content-Type: application/json; charset=UTF-8');

$returnArray = ['code' => 'SUCCESS', 'msg' => '정상 처리되었습니다.'];

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['action'])) {
    $returnArray['code'] = 'PARAMS';
    $returnArray['msg']  = '필수 파라미터가 존재하지 않습니다.';
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

if ($data['action'] === 'purge_expired_boards') {
    $result = AdminSystem::purgeExpiredBoards();
    $returnArray['data'] = $result;
    echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
}

$returnArray['code'] = 'UNKNOWN_ACTION';
$returnArray['msg']  = '알 수 없는 액션입니다.';
echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); exit;
?>
