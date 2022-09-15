<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드


if(!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}
?>


<?
echo css_load('/record/record_certificate/index.css');
?>














<?
echo script_load('/record/record_certificate/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>