<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/util/bmi/index.css');
?>


<p class="wrap_title">BMI 계산기</p>


<div id="calculator_wrap">

    <div class="info_box">
        <div class="info_article"></div>
        <div class="info_article"></div>
        <div class="info_article"></div>
        <div class="info_article"></div>
    </div>


</div>
<!-- calculator_wrap -->




<?
echo script_load('/util/bmi/index.js');
?>

<script>
$(function () {

});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>