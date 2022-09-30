<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/util/bmi/index.css');
?>


<p class="wrap_title">BMI 계산기</p>


<div id="calculator_wrap">

    <div class="info_box">
        <div class="result_info_wrap">
            <div class="result_article" name="1">18.5 이하 저체중</div>
            <div class="result_article" name="2">18.5 ~ 22.9 정상</div>
            <div class="result_article" name="3">23.0 ~ 24.9 과체중</div>
            <div class="result_article" name="4">25.0 이상 비만</div>
        </div>
    </div>

    <div class="calc_box">
        <div class="calc_value_wrap">
            <div class="myrecord_input_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="bmi_height">신장</label>
                </div>
                <div class="form_value_box">
                    <input id="bmi_height" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number"/>
                    <p class="place_text">CM</p>
                </div>
            </div>
            <div class="myrecord_input_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="bmi_weight">체중</label>
                </div>
                <div class="form_value_box">
                    <input id="bmi_weight" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number"/>
                    <p class="place_text">KG</p>
                </div>
            </div>
        </div>
        <!-- calc_value_wrap -->
        <div class="calc_remote_wrap">
            <button class="result_btn" onclick="bmiCalc();">계산하기&nbsp;<i class="fa-solid fa-calculator"></i></button>
            <button class="reset_btn" onclick="bmiReset();">초기화&nbsp;<i class="fa-solid fa-arrow-rotate-right"></i></button>
        </div>
        <!-- calc_remote_wrap -->
    </div>
    <!-- calc_box -->

    <div class="result_box">
        <p class="result_value_box">
            BMI - <span class="value"></span>
        </p>
        <p class="result_value_text">
            BMI는 <span class="value"></span> 이고 <span class="value_text"></span>입니다
        </p>
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