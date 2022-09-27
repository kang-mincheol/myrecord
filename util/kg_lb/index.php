<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/util/kg_lb/index.css');
?>


<p class="wrap_title">KG <i class="fa-sharp fa-solid fa-repeat"></i> LB 변환기</p>












<?
echo script_load('/util/kg_lb/index.js');
?>

<script>
$(function () {
//    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>