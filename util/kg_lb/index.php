<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/util/kg_lb/index.css');
?>


<p class="wrap_title">KG <i class="fa-sharp fa-solid fa-repeat"></i> LB 변환기</p>


<div id="calculator_wrap">

    <div class="calc_row">
        <div class="calc_box">
            <div class="calc_header">KG</div>
            <div class="calc_value_box">
                <input class="calc_value" type="number" name="kg" oninput="numberKeyUp(event)" value="0"/>
            </div>
        </div>
        <div class="repeat_box">
            <i class="fa-sharp fa-solid fa-repeat"></i>
        </div>
        <div class="calc_box">
            <div class="calc_header">LB(파운드)</div>
            <div class="calc_value_box">
                <input class="calc_value" type="number" name="lb" oninput="numberKeyUp(event)" value="0"/>
            </div>
        </div>
    </div>

</div>



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