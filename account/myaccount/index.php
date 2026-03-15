<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/account/myaccount/index.css');

if(!$is_member) {
?>
<script>
myrecordAlert('on', '로그인 후 이용해주세요', '알림', 'location.href=\'/account/login\'');
</script>
<?php
exit;
}
?>


<div class="account_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">내 정보</p>
        <p class="page_sub_text">계정 정보를 확인하고 수정하세요</p>
    </div>
</div>


<div id="myaccount_wrap">
    <div class="account_card">

        <!-- 프로필 상단 -->
        <div class="profile_head">
            <div class="avatar_circle">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>

        <!-- 폼 필드 영역 -->
        <div class="fields_wrap">

            <div class="field_item">
                <label class="field_label" for="account_id">
                    아이디
                    <span class="locked_tag"><i class="fa-solid fa-lock"></i> 변경불가</span>
                </label>
                <input id="account_id" class="input_text" type="text" disabled/>
            </div>

            <div class="field_item">
                <label class="field_label" for="account_nickname">닉네임</label>
                <input id="account_nickname" class="input_text" onkeyup="inputOnkeyupEvent(this); accountChangeCheck();" type="text" placeholder="영문 또는 한글 또는 숫자 2~10자리 (특수문자 불가)"/>
            </div>

            <div class="field_item">
                <label class="field_label" for="account_name">
                    이름
                    <span class="optional_tag">선택</span>
                </label>
                <input id="account_name" class="input_text" onkeyup="inputOnkeyupEvent(this); accountChangeCheck();" type="text" placeholder="한글 또는 영문 2~17자리"/>
            </div>

            <div class="field_item">
                <label class="field_label" for="account_phone">
                    핸드폰번호
                    <span class="optional_tag">선택</span>
                </label>
                <input id="account_phone" class="input_text" onkeyup="inputOnkeyupEvent(this); accountChangeCheck();" type="text" placeholder="'-' 포함하여 입력해 주세요"/>
            </div>

            <div class="field_item">
                <label class="field_label" for="account_email">
                    이메일
                    <span class="optional_tag">선택</span>
                </label>
                <input id="account_email" class="input_text" onkeyup="inputOnkeyupEvent(this); accountChangeCheck();" type="text" placeholder="이메일을 입력해 주세요"/>
            </div>

        </div>
        <!-- fields_wrap -->

        <!-- 하단 버튼 -->
        <div class="footer_btn_wrap">
            <button class="password_change_btn" onclick="PasswordChangeModal.handler();">
                <i class="fa-solid fa-key"></i> 비밀번호 변경
            </button>
            <button class="account_change_btn" onclick="myaccountChange();">
                내정보 수정
            </button>
        </div>

    </div>
    <!-- account_card -->
</div>
<!-- myaccount_wrap -->


<?php
echo script_load('/account/myaccount/index.js');
?>
<script>
$(function () {
    init();
});
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/component/passwordChangeModal/passwordChangeModal.php'); // 비밀번호 변경 모달 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
