<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/util/bmi/index.css');
?>


<div class="util_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">BMI 계산기</p>
        <p class="page_sub_text">신장과 체중으로 나의 비만도를 확인하세요</p>
    </div>
</div>


<div id="calculator_wrap">

    <!-- BMI 기준표 -->
    <div class="info_box">
        <p class="info_label">BMI 기준</p>
        <div class="result_info_wrap">
            <div class="result_article" name="1">
                <span class="badge underweight">저체중</span>
                <span class="range_text">18.5 이하</span>
            </div>
            <div class="result_article" name="2">
                <span class="badge normal">정상</span>
                <span class="range_text">18.5 ~ 22.9</span>
            </div>
            <div class="result_article" name="3">
                <span class="badge overweight">과체중</span>
                <span class="range_text">23.0 ~ 24.9</span>
            </div>
            <div class="result_article" name="4">
                <span class="badge obese">비만</span>
                <span class="range_text">25.0 이상</span>
            </div>
        </div>
    </div>

    <!-- 입력 영역 -->
    <div class="calc_box">
        <div class="calc_value_wrap">
            <div class="myrecord_input_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="bmi_height">신장(키)</label>
                </div>
                <div class="form_value_box">
                    <input id="bmi_height" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number" placeholder="0"/>
                    <p class="place_text">CM</p>
                </div>
            </div>
            <div class="myrecord_input_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="bmi_weight">체중</label>
                </div>
                <div class="form_value_box">
                    <input id="bmi_weight" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number" placeholder="0"/>
                    <p class="place_text">KG</p>
                </div>
            </div>
        </div>
        <!-- calc_value_wrap -->

        <div class="calc_remote_wrap">
            <button class="reset_btn" onclick="bmiReset();">
                <i class="fa-solid fa-arrow-rotate-right"></i>&nbsp; 초기화
            </button>
            <button class="result_btn" onclick="bmiCalc();">
                <i class="fa-solid fa-calculator"></i>&nbsp; 계산하기
            </button>
        </div>
        <!-- calc_remote_wrap -->
    </div>
    <!-- calc_box -->

    <!-- 결과 영역 -->
    <div class="result_box">
        <div class="result_inner">
            <p class="result_label">나의 BMI</p>
            <p class="result_value_box">
                <span class="value">0.0</span>
            </p>
            <p class="result_value_text">
                BMI <span class="value">0.0</span> — <span class="value_text">-</span>
            </p>
        </div>
    </div>
    <!-- result_box -->

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
