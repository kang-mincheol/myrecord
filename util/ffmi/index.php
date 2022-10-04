<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/util/ffmi/index.css');
?>


<p class="wrap_title">FFMI 계산기</p>


<div id="ffmi_wrap">
    
    <div class="calc_value_wrap">

        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="ffmi_height">신장(키)</label>
            </div>
            <div class="form_value_box">
                <input id="ffmi_height" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number"/>
                <p class="place_text">CM</p>
            </div>
        </div>

        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="ffmi_weight">체중</label>
            </div>
            <div class="form_value_box">
                <input id="ffmi_weight" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number"/>
                <p class="place_text">KG</p>
            </div>
        </div>

        <div class="myrecord_input_wrap">
            <div class="label_box">
                <label class="wrap_label" for="ffmi_fat">체지방량</label>
            </div>
            <div class="form_value_box">
                <input id="ffmi_fat" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number"/>
                <p class="place_text">%</p>
            </div>
        </div>

    </div>
    <!-- calc_value_wrap -->
    
    <div class="calc_remote_wrap">
        <button class="calc_btn" onclick="ffmiCalc();">계산하기&nbsp;<i class="fa-solid fa-calculator"></i></button>
        <button class="reset_btn" onclick="ffmiReset();">초기화&nbsp;<i class="fa-solid fa-arrow-rotate-right"></i></button>
    </div>
    <!-- calc_remote_wrap -->

    <div class="calc_result_wrap">
        <p class="result_text">
            FFMI - <span id="ffmi_result_value">123</span>
        </p>
    </div>
    <!-- calc_result_wrap -->

    <div class="ffmi_docs_wrap">
        <div class="ffmi_docs_pc">
            <div class="docs_title_row">
                <p class="title_text">FFMI</p>
                <p class="title_text">여자</p>
                <p class="title_text">남자</p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">13</p>
                <p class="row_box">
                    평균 이하</br>
                    가벼운 운동이 필요한 구간
                </p>
                <p class="row_box">-</p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">14</p>
                <p class="row_box">
                    평균 수준</br>
                    일반 성인 여성 평균 구간
                </p>
                <p class="row_box">-</p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">15</p>
                <p class="row_box">
                    평균 수준</br>
                    일반 성인 여성 평균 구간
                </p>
                <p class="row_box">-</p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">16</p>
                <p class="row_box">
                    평균 이상</br>
                    생활 체육인 구간
                </p>
                <p class="row_box">
                    평균 이하</br>
                    가벼운 운동이 필요한 구간
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">17</p>
                <p class="row_box">
                    훌륭</br>
                    엘리트 생활 체육인의 구간
                </p>
                <p class="row_box">
                    평균 이하</br>
                    가벼운 운동이 필요한 구간
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">18</p>
                <p class="row_box">
                    훌륭</br>
                    엘리트 생활 체육인의 구간
                </p>
                <p class="row_box">
                    평균 수준</br>
                    일반 성인 남성 평균 구간
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">19</p>
                <p class="row_box">
                    아주 훌륭</br>
                    일반적으로 알려진 내추럴의 한계점
                </p>
                <p class="row_box">
                    평균 수준</br>
                    일반 성인 남성 평균 구간
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">20</p>
                <p class="row_box">
                    아주 훌륭</br>
                    일반적으로 알려진 내추럴의 한계점
                </p>
                <p class="row_box">
                    평균 이상</br>
                    생활 체육인 구간
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">21</p>
                <p class="row_box">
                    놀라움</br>
                    내추럴로 달성하기 불가능에 가깝고 로이더도 쉽지 않은 영역
                </p>
                <p class="row_box">
                    일반인 상위권</br>
                    생활 체육인 구간
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">22</p>
                <p class="row_box">
                    놀라움</br>
                    내추럴로 달성하기 불가능에 가깝고 로이더도 쉽지 않은 영역
                </p>
                <p class="row_box">
                    훌륭</br>
                    생활 체육인 구간
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">23</p>
                <p class="row_box">
                    -
                </p>
                <p class="row_box">
                    아주 훌륭</br>
                    일반적으로 알려진 내추럴의한계점
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">24</p>
                <p class="row_box">
                    -
                </p>
                <p class="row_box">
                    아주 훌륭</br>
                    일반적으로 알려진 내추럴의한계점
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">25</p>
                <p class="row_box">
                    -
                </p>
                <p class="row_box">
                    아주 훌륭</br>
                    일반적으로 알려진 내추럴의한계점
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">26</p>
                <p class="row_box">
                    -
                </p>
                <p class="row_box">
                    놀라움</br>
                    유전적 재능이 필요한 구간
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">27</p>
                <p class="row_box">
                    -
                </p>
                <p class="row_box">
                    놀라움</br>
                    유전적 재능이 필요한 구간
                </p>
            </div>
            <div class="docs_body_row">
                <p class="row_box">28</p>
                <p class="row_box">
                    -
                </p>
                <p class="row_box">
                    올림피아
                </p>
            </div>
        </div>
    </div>
    <!-- ffmi_docs_wrap -->
    
</div>
<!-- ffmi_wrap -->





<?
echo script_load('/util/ffmi/index.js');
?>

<script>
$(function () {

});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>