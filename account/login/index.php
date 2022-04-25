<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/account/login/index.css');
?>


<div id="login_wrap">
    <div class="login_box">
        <p class="box_title">로그인</p>

        <div class="login_info_wrap">
            <div class="info_data_wrap">
                <div class="info_box top">
                    <div class="article_box"><i class="fa-solid fa-user"></i></div>
                    <div class="input_box">
                        <input id="login_id" class="login_input" type="text" onkeyup="loginInputOnkeyup();" placeholder="아이디"/>
                    </div>
                </div>
                <div class="info_box bottom">
                    <div class="article_box"><i class="fa-solid fa-lock"></i></div>
                    <div class="input_box">
                        <input id="login_password" class="login_input" type="password" onkeyup="loginInputOnkeyup();" placeholder="비밀번호"/>
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




<?
echo script_load('/account/login/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>