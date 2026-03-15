<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

echo css_load('/workout_log/list/index.css');

if(!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}
?>

<div class="workout_log_header">
    <div class="header_inner">
        <p class="page_title">득근일지</p>
        <p class="page_subtitle">나만의 운동 기록을 쌓아가세요</p>
    </div>
</div>

<div class="workout_log_list_wrap">

    <div class="list_top_bar">
        <p class="total_count_text"><span id="total_count">0</span>개의 기록</p>
        <a href="/workout_log/write/" class="write_btn"><i class="fa-solid fa-plus"></i> 기록 추가</a>
    </div>

    <div id="log_list_box" class="log_list_box">
        <div class="list_loading">
            <i class="fa-solid fa-spinner fa-spin"></i>
        </div>
    </div>

    <div id="pagination_wrap" class="pagination_wrap"></div>

</div>


<?php
echo script_load('/workout_log/list/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>
