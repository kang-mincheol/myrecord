<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/account/create/index.css');
?>


<div class="create_wrap">


<div class="step_box" name="step_1">

    <div class="step_indicator">
        <div class="step_dot on"><span>1</span></div>
        <div class="step_line"></div>
        <div class="step_dot"><span>2</span></div>
    </div>

    <div class="step_title_box">
        <p class="step_title">마이레코드 회원가입</p>
        <p class="step_sub">서비스 이용을 위해 약관에 동의해 주세요</p>
    </div>

    <button class="terms_all_btn" onclick="termsAll();">전체 동의</button>

    <div class="terms_wrap">

        <div class="terms_row">
            <div class="left_box">
                <div class="terms_checkbox_wrap">
                    <input id="terms_service" name="terms_service" type="checkbox" onchange="step1Verify();"/>
                    <p class="checkbox_bg"></p>
                </div>
                <label class="terms_label" for="terms_service">
                    <span class="required_badge">필수</span>
                    서비스 이용약관 동의
                </label>
            </div>
            <div class="right_box">
                <a class="view_btn" href="/policy/terms/" target="_blank">보기</a>
            </div>
        </div>

        <div class="terms_row">
            <div class="left_box">
                <div class="terms_checkbox_wrap">
                    <input id="terms_private" name="terms_private" type="checkbox" onchange="step1Verify();"/>
                    <p class="checkbox_bg"></p>
                </div>
                <label class="terms_label" for="terms_private">
                    <span class="required_badge">필수</span>
                    개인정보 처리방침
                </label>
            </div>
            <div class="right_box">
                <a class="view_btn" href="/policy/privacy/" target="_blank">보기</a>
            </div>
        </div>

        <div class="terms_row">
            <div class="left_box">
                <div class="terms_checkbox_wrap">
                    <input id="terms_marketing" name="terms_marketing" type="checkbox"/>
                    <p class="checkbox_bg"></p>
                </div>
                <label class="terms_label" for="terms_marketing">
                    <span class="optional_badge">선택</span>
                    마케팅 수신 동의
                </label>
            </div>
            <div class="right_box">
                <a class="view_btn" href="/policy/marketing/" target="_blank">보기</a>
            </div>
        </div>

    </div>
    <!-- terms_wrap -->

    <button class="next_btn" onclick="myrecordAlert('on', '필수 약관에 동의해주세요');">다음</button>
</div>
<!-- step_1 -->


<div class="step_box" name="step_2">

    <div class="step_indicator">
        <div class="step_dot done"><span><i class="fa-solid fa-check"></i></span></div>
        <div class="step_line on"></div>
        <div class="step_dot on"><span>2</span></div>
    </div>

    <div class="step_title_box">
        <p class="step_title">마이레코드 회원가입</p>
        <p class="step_sub">계정 정보를 입력해 주세요</p>
    </div>

    <div class="myrecord_input_wrap id_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_id">아이디</label>
        </div>
        <div class="form_value_box">
            <input id="account_id" class="input_text" onkeyup="inputOnkeyupEvent(this); step2Verify();" type="text" placeholder="아이디를 입력해 주세요 (5~20자리)" maxlength="20"/>
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
            <input id="account_password_check" class="input_text" onkeyup="inputOnkeyupEvent(this); step2Verify();" type="password" placeholder="비밀번호를 재입력해 주세요"/>
        </div>
    </div>

    <div class="myrecord_input_wrap name_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_nickname">닉네임</label>
        </div>
        <div class="form_value_box">
            <input id="account_nickname" class="input_text" onkeyup="inputOnkeyupEvent(this); step2Verify();" type="text" placeholder="영문·한글·숫자 2~10자리 (특수문자 불가)"/>
        </div>
    </div>

    <div class="input_divider"></div>

    <div class="myrecord_input_wrap name_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_name">이름 <span class="optional_label">(선택)</span></label>
        </div>
        <div class="form_value_box">
            <input id="account_name" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text" placeholder="한글 또는 영문 2~17자리"/>
        </div>
    </div>

    <div class="myrecord_input_wrap phone_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_phone">휴대폰번호 <span class="optional_label">(선택)</span></label>
        </div>
        <div class="form_value_box">
            <input id="account_phone" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text" placeholder="'-' 포함하여 입력해 주세요"/>
        </div>
    </div>

    <div class="myrecord_input_wrap email_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_email">이메일 <span class="optional_label">(선택)</span></label>
        </div>
        <div class="form_value_box">
            <input id="account_email" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="text" placeholder="이메일을 입력해 주세요"/>
        </div>
    </div>

    <button class="next_btn" onclick="myrecordAlert('on', '필수 입력값을 입력해 주세요');">회원가입</button>
</div>
<!-- step_2 -->


</div>
<!-- create_wrap -->


<?php
echo script_load('/account/create/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
