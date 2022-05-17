<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/record/my_record/index.css');

if(!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}
?>


<div id="page_title">마이레코드 ＞ 내 기록</div>

<?
include_once($_SERVER['DOCUMENT_ROOT'].'/component/sub_menu/record_sub_menu/record_sub_menu.php');
?>


<div id="my_record_wrap">

    <div class="record_wrap">

<!--
        <div class="record_box">
            <div class="box_title">DeadLift</div>
            <div class="record_info_box">
                <p class="empty_line">기록 없음</p>
                <div class="record_weight">100KG</div>
                <div class="record_status">
                    <i class="fa-solid fa-circle-check blue"></i>
                    <p class="status_text">심사중</p>
                </div>
            </div>
        </div>
-->

    </div>






</div>
<!-- my_record_wrap -->






<?
echo script_load('/record/my_record/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>