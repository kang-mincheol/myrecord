<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

echo css_load('/workout_log/view/index.css');

if (!$is_member) {
    echo "<script>myrecordAlert('on', '로그인 후 이용해주세요', '알림', 'location.href=\"/account/login/\"');</script>";
    exit;
}

$log_id = (int)preg_replace("/[^0-9]+/u", "", $_GET['id'] ?? '');
if (!$log_id) {
    echo "<script>myrecordAlert('on', '잘못된 접근입니다', '알림', 'location.href=\"/workout_log/list/\"');</script>";
    exit;
}
?>

<div id="workout_view_root"></div>

<?php echo script_load('/workout_log/view/index.js'); ?>
<script>
window.addEventListener('DOMContentLoaded', function () {
    initPage(<?= $log_id ?>);
});
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
