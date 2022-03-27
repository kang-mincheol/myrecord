<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/account/create/index.css');
?>


<div class="create_wrap">

<div class="step_box" name="step_1">
    <div class="step_title_box">
        <p class="step_title">마이레코드 회원가입</p>
    </div>

    <button class="terms_all_btn" onclick="termsAll();">모두 동의</button>

    <div class="terms_wrap">

        <div class="terms_row">
            <div class="left_box">
                <div class="terms_checkbox_wrap">
                    <input id="terms_service" name="terms_service" type="checkbox" onchange="step1Verify();"/>
                    <p class="checkbox_bg"></p>
                </div>
                <label class="terms_label" for="terms_service">(필수) 서비스 이용약관 동의</label>
            </div>
            <div class="right_box">
                <a class="view_btn" href="/document/">보기</a>
            </div>
        </div>

        <div class="terms_row">
            <div class="left_box">
                <div class="terms_checkbox_wrap">
                    <input id="terms_private" name="terms_service" type="checkbox" onchange="step1Verify();"/>
                    <p class="checkbox_bg"></p>
                </div>
                <label class="terms_label" for="terms_private">(필수) 개인정보 처리방침</label>
            </div>
            <div class="right_box">
                <a class="view_btn" href="/document/">보기</a>
            </div>
        </div>

        <div class="terms_row">
            <div class="left_box">
                <div class="terms_checkbox_wrap">
                    <input id="terms_marketing" name="terms_service" type="checkbox"/>
                    <p class="checkbox_bg"></p>
                </div>
                <label class="terms_label" for="terms_marketing">(선택) 마케팅 동의</label>
            </div>
            <div class="right_box">
                <a class="view_btn" href="/document/">보기</a>
            </div>
        </div>

    </div>
    <!-- terms_wrap -->

    <button class="next_btn" onclick="">다음</button>
</div>
<!-- step_1 -->

<div class="step_box" name="step_2">
    
</div>
<!-- step_2 -->




</div>
<!-- create_wrap -->










<?
echo script_load('/account/create/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>