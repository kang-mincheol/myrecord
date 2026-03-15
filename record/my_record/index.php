<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/common/record_pages.css');
echo css_load('/record/my_record/index.css');

if(!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}
?>


<div class="record_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">내 기록</p>
        <p class="page_sub_text">나의 종목별 최고 기록을 확인해 보세요</p>
    </div>
</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/component/sub_menu/record_sub_menu/record_sub_menu.php');
?>


<div id="my_record_wrap">
    <div class="record_wrap"></div>
</div>
<!-- my_record_wrap -->


<?php
echo script_load('/record/my_record/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
