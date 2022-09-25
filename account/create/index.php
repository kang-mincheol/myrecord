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
                <a class="view_btn" href="/document/myrecord_service_terms_1.html" target="_blank">보기</a>
            </div>
        </div>

        <div class="terms_row">
            <div class="left_box">
                <div class="terms_checkbox_wrap">
                    <input id="terms_private" name="terms_private" type="checkbox" onchange="step1Verify();"/>
                    <p class="checkbox_bg"></p>
                </div>
                <label class="terms_label" for="terms_private">(필수) 개인정보 처리방침</label>
            </div>
            <div class="right_box">
                <a class="view_btn" href="/document/myrecord_personal_info_terms.html" target="_blank">보기</a>
            </div>
        </div>

        <div class="terms_row">
            <div class="left_box">
                <div class="terms_checkbox_wrap">
                    <input id="terms_marketing" name="terms_marketing" type="checkbox"/>
                    <p class="checkbox_bg"></p>
                </div>
                <label class="terms_label" for="terms_marketing">(선택) 마케팅 수신 동의</label>
            </div>
            <div class="right_box">
                <a class="view_btn" href="/document/myrecord_marketing_terms.html" target="_blank">보기</a>
            </div>
        </div>

    </div>
    <!-- terms_wrap -->

    <button class="next_btn" onclick="myrecordAlert('on', '필수 약관에 동의해주세요');">다음</button>
</div>
<!-- step_1 -->

<div class="step_box" name="step_2">
    <div class="step_title_box">
        <p class="step_title">마이레코드 회원가입</p>
    </div>

    <div class="myrecord_input_wrap id_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_id">아이디</label>
        </div>
        <div class="form_value_box">
            <input id="account_id" class="input_text" onkeyup="inputOnkeyupEvent(this); step2Verify();" type="text" placeholder="아이디를 입력해주세요 5~20자리" maxlength="20"/>
        </div>
    </div>

    <div class="myrecord_input_wrap password_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_password">비밀번호</label>
        </div>
        <div class="form_value_box">
            <input id="account_password" class="input_text" onkeyup="inputOnkeyupEvent(this); step2Verify();" type="password" placeholder="영문, 숫자, 특수문자 포함 8~15자리"/>
        </div>
        <div class="form_value_box">
            <input id="account_password_check" class="input_text" onkeyup="inputOnkeyupEvent(this); step2Verify();" type="password" placeholder="비밀번호를 재입력해주세요"/>
        </div>
    </div>

    <div class="myrecord_input_wrap name_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_nickname">닉네임</label>
        </div>
        <div class="form_value_box">
            <input id="account_nickname" class="input_text" onkeyup="inputOnkeyupEvent(this); step2Verify();" type="text" placeholder="영문 또는 한글 또는 숫자 2~10자리 (특수문자 불가)"/>
        </div>
    </div>

    <div class="myrecord_input_wrap name_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_name">(선택)이름</label>
        </div>
        <div class="form_value_box">
            <input id="account_name" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text" placeholder="한글 또는 영문 2~17자리"/>
        </div>
    </div>

    <div class="myrecord_input_wrap phone_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_phone">(선택)핸드폰번호</label>
        </div>
        <div class="form_value_box">
            <input id="account_phone" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="number" placeholder="핸드폰번호를 입력해주세요 '-' 포함"/>
        </div>
    </div>

    <div class="myrecord_input_wrap email_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_email">(선택)이메일</label>
        </div>
        <div class="form_value_box">
            <input id="account_email" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text" placeholder="이메일을 입력해주세요"/>
        </div>
    </div>

    <button class="next_btn" onclick="myrecordAlert('on', '필수 입력값을 입력해주세요');">회원가입</button>
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