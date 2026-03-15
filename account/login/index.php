<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/account/login/index.css');
?>


<div id="login_wrap">
    <div class="login_box">

        <p class="box_title">로그인</p>
        <p class="box_sub">마이레코드에 오신 것을 환영합니다</p>

        <div class="login_info_wrap">

            <div class="info_data_wrap">
                <div class="input_field">
                    <label class="field_label" for="login_id">아이디</label>
                    <div class="input_inner">
                        <i class="fa-solid fa-user field_icon"></i>
                        <input id="login_id" class="login_input" type="text" onkeyup="loginInputOnkeyup();" placeholder="아이디를 입력하세요"/>
                    </div>
                </div>
                <div class="input_field">
                    <label class="field_label" for="login_password">비밀번호</label>
                    <div class="input_inner">
                        <i class="fa-solid fa-lock field_icon"></i>
                        <input id="login_password" class="login_input" type="password" onkeyup="loginInputOnkeyup();" placeholder="비밀번호를 입력하세요"/>
                    </div>
                </div>
            </div>
            <!-- info_data_wrap -->

            <div class="bottom_btn_wrap">
                <button class="login_btn" onclick="loginSubmit();">로그인</button>
                <a class="join_btn" href="/account/create/">회원가입</a>
            </div>

        </div>
        <!-- login_info_wrap -->

    </div>
    <!-- login_box -->
</div>
<!-- login_wrap -->


<?php
echo script_load('/account/login/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
