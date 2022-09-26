<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/util/index.css');
?>


<p class="wrap_title">도구</p>

<div id="util_wrap">
    <div class="util_box">
        <a href="/util/kg_lb/" class="util_link">
            <div class="box_icon">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <p class="box_title">
                KG <i class="fa-sharp fa-solid fa-repeat"></i> LB 변환기
            </p>
        </a>
    </div>
<!--
    <div class="util_box">
        <div class="box_icon">
            <i class="fa-solid fa-calculator"></i>
        </div>
        <p class="box_title">
            KG <i class="fa-sharp fa-solid fa-repeat"></i> LB 변환기
        </p>
    </div>
-->
</div>
<!-- util_wrap -->





<?
echo script_load('/util/index.js');
?>

<script>
$(function () {
//    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>