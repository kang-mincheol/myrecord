<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/account/myaccount/index.css');

if(!$is_member) {
?>
<script>
myrecordAlert('on', '로그인 후 이용해주세요', '알림', 'location.href=\'/account/login\'');
</script>
<?
exit;
}
?>

<div id="myaccount_wrap">

    <p class="wrap_title">내 정보</p>

    <div class="myrecord_input_wrap id_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_id">아이디</label>
        </div>
        <div class="form_value_box">
            <input id="account_id" class="input_text" type="text" disabled/>
        </div>
    </div>

    <div class="myrecord_input_wrap name_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_nickname">닉네임</label>
        </div>
        <div class="form_value_box">
            <input id="account_nickname" class="input_text" onkeyup="inputOnkeyupEvent(this); accountChangeCheck();" type="text" placeholder="영문 또는 한글 또는 숫자 2~10자리 (특수문자 불가)"/>
        </div>
    </div>

    <div class="myrecord_input_wrap name_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_name">(선택)이름</label>
        </div>
        <div class="form_value_box">
            <input id="account_name" class="input_text" onkeyup="inputOnkeyupEvent(this); accountChangeCheck();" type="text" placeholder="한글 또는 영문 2~17자리"/>
        </div>
    </div>

    <div class="myrecord_input_wrap phone_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_phone">(선택)핸드폰번호</label>
        </div>
        <div class="form_value_box">
            <input id="account_phone" class="input_text" onkeyup="inputOnkeyupEvent(this); accountChangeCheck();" type="number" placeholder="핸드폰번호를 입력해주세요 '-' 포함"/>
        </div>
    </div>

    <div class="myrecord_input_wrap email_wrap">
        <div class="label_box">
            <label class="wrap_label" for="account_email">(선택)이메일</label>
        </div>
        <div class="form_value_box">
            <input id="account_email" class="input_text" onkeyup="inputOnkeyupEvent(this); accountChangeCheck();" type="text" placeholder="이메일을 입력해주세요"/>
        </div>
    </div>

    <div class="footer_btn_wrap">
        <button class="password_change_btn" onclick="passwordChangeView();">비밀번호 변경</button>
        <button class="account_change_btn" onclick="myaccountChange();">내정보 수정</button>
    </div>

</div>



<?
echo script_load('/account/myaccount/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>