<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

echo css_load('/util/1rm/index.css');
?>


<div class="util_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">1RM 계산기</p>
        <p class="page_sub_text">특정 무게와 횟수로 최대 중량(1RM)을 예측합니다</p>
    </div>
</div>


<div id="calculator_wrap">

    <!-- 공식 안내 -->
    <div class="info_box">
        <p class="info_label">Epley 공식</p>
        <p class="info_formula">1RM = 무게 × (1 + 횟수 ÷ 30)</p>
        <p class="info_desc">직접 최대 중량을 들지 않고도, 특정 무게로 수행한 횟수를 바탕으로 1RM을 예측하는 공식입니다. 1~10회 범위에서 가장 정확합니다.</p>
    </div>

    <!-- 입력 영역 -->
    <div class="calc_box">

        <!-- 단위 토글 -->
        <div class="unit_toggle_row">
            <span class="unit_label_text">무게 단위</span>
            <div class="unit_toggle_wrap">
                <button type="button" class="unit_toggle_btn active" data-unit="kg" onclick="setUnit('kg', this);">KG</button>
                <button type="button" class="unit_toggle_btn" data-unit="lb" onclick="setUnit('lb', this);">LB</button>
            </div>
        </div>

        <div class="calc_value_wrap">
            <div class="myrecord_input_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="rm_weight">무게</label>
                </div>
                <div class="form_value_box">
                    <input id="rm_weight" class="input_text" type="number" placeholder="0" min="1" max="9999" step="0.5" oninput="liveCalc();" />
                    <p class="place_text unit_display">KG</p>
                </div>
            </div>
            <div class="myrecord_input_wrap">
                <div class="label_box">
                    <label class="wrap_label" for="rm_reps">횟수</label>
                </div>
                <div class="form_value_box">
                    <input id="rm_reps" class="input_text" type="number" placeholder="0" min="1" max="30" oninput="liveCalc();" />
                    <p class="place_text">회</p>
                </div>
            </div>
        </div>

        <div class="calc_remote_wrap">
            <button class="reset_btn" onclick="rmReset();">
                <i class="fa-solid fa-arrow-rotate-right"></i>&nbsp; 초기화
            </button>
            <button class="result_btn" onclick="rmCalc();">
                <i class="fa-solid fa-calculator"></i>&nbsp; 계산하기
            </button>
        </div>
    </div>
    <!-- calc_box -->

    <!-- 결과 영역 -->
    <div class="result_box">
        <div class="result_main">
            <p class="result_label">예상 1RM</p>
            <p class="result_value_box">
                <span class="value">0</span><span class="result_unit">KG</span>
            </p>
        </div>

        <div class="percent_table">
            <p class="percent_table_title">훈련 중량 가이드</p>
            <div class="percent_row header_row">
                <span>비율</span>
                <span>중량</span>
                <span>용도</span>
            </div>
            <div class="percent_row" data-pct="100"><span>100%</span><span class="pct_weight">—</span><span>최대 중량</span></div>
            <div class="percent_row" data-pct="95"><span>95%</span><span class="pct_weight">—</span><span>고강도 훈련</span></div>
            <div class="percent_row" data-pct="90"><span>90%</span><span class="pct_weight">—</span><span>근력 훈련</span></div>
            <div class="percent_row" data-pct="85"><span>85%</span><span class="pct_weight">—</span><span>근비대 (저반복)</span></div>
            <div class="percent_row" data-pct="80"><span>80%</span><span class="pct_weight">—</span><span>근비대</span></div>
            <div class="percent_row" data-pct="75"><span>75%</span><span class="pct_weight">—</span><span>근비대 (고반복)</span></div>
            <div class="percent_row" data-pct="70"><span>70%</span><span class="pct_weight">—</span><span>근지구력</span></div>
        </div>
    </div>
    <!-- result_box -->

</div>
<!-- calculator_wrap -->


<?
echo script_load('/util/1rm/index.js');
?>
<script>
$(function () {

});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>
