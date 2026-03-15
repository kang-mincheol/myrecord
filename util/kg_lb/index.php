<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/util/kg_lb/index.css');
?>


<div class="util_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">KG &nbsp;↔&nbsp; LB 변환기</p>
        <p class="page_sub_text">킬로그램과 파운드를 실시간으로 변환합니다</p>
    </div>
</div>


<div id="calculator_wrap">

    <div class="calc_card">
        <div class="calc_row">
            <div class="calc_box">
                <div class="calc_header">
                    <span class="unit_label">KG</span>
                    <span class="unit_sub">킬로그램</span>
                </div>
                <div class="calc_value_box">
                    <input class="calc_value" type="number" name="kg" oninput="numberKeyUp(event)" value="0"/>
                </div>
            </div>

            <div class="repeat_box">
                <i class="fa-sharp fa-solid fa-repeat"></i>
            </div>

            <div class="calc_box">
                <div class="calc_header">
                    <span class="unit_label">LB</span>
                    <span class="unit_sub">파운드</span>
                </div>
                <div class="calc_value_box">
                    <input class="calc_value" type="number" name="lb" oninput="numberKeyUp(event)" value="0"/>
                </div>
            </div>
        </div>
        <!-- calc_row -->

        <div class="calc_info_row">
            <p class="info_text">1 KG = 2.20462 LB</p>
            <p class="info_text">1 LB = 0.453592 KG</p>
        </div>
    </div>
    <!-- calc_card -->

</div>
<!-- calculator_wrap -->


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
