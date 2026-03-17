<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/util/index.css');
?>


<div class="util_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">도구</p>
        <p class="page_sub_text">운동에 유용한 계산 도구 모음</p>
    </div>
</div>


<div id="util_wrap">

    <a href="/util/kg_lb/" class="util_card">
        <div class="card_icon_wrap">
            <i class="fa-solid fa-repeat"></i>
        </div>
        <div class="card_info">
            <p class="card_title">KG &nbsp;↔&nbsp; LB 변환기</p>
            <p class="card_desc">킬로그램과 파운드를 실시간으로 변환합니다</p>
        </div>
        <div class="card_arrow">
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </a>

    <a href="/util/bmi/" class="util_card">
        <div class="card_icon_wrap">
            <i class="fa-solid fa-weight-scale"></i>
        </div>
        <div class="card_info">
            <p class="card_title">BMI 계산기</p>
            <p class="card_desc">신장과 체중으로 비만도를 계산합니다</p>
        </div>
        <div class="card_arrow">
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </a>

    <a href="/util/ffmi/" class="util_card">
        <div class="card_icon_wrap">
            <i class="fa-solid fa-dumbbell"></i>
        </div>
        <div class="card_info">
            <p class="card_title">FFMI 계산기</p>
            <p class="card_desc">체지방을 제외한 근육량 지수를 계산합니다</p>
        </div>
        <div class="card_arrow">
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </a>

    <a href="/util/1rm/" class="util_card">
        <div class="card_icon_wrap">
            <i class="fa-solid fa-arrow-up-from-bracket"></i>
        </div>
        <div class="card_info">
            <p class="card_title">1RM 계산기</p>
            <p class="card_desc">무게와 횟수로 최대 중량(1RM)을 예측합니다</p>
        </div>
        <div class="card_arrow">
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    </a>

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
