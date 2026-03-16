<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

echo css_load('/component/passwordChangeModal/passwordChangeModal.css');
?>

<div id="passwordChangeModal">
    <div class="pcm_overlay"></div>
    <div class="pcm_box">

        <div class="pcm_head">
            <p class="pcm_title"><i class="fa-solid fa-key"></i> 비밀번호 변경</p>
            <button class="pcm_close_btn" onclick="PasswordChangeModal.handler(false);" title="닫기">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="pcm_body">

            <div class="pcm_field">
                <label class="pcm_label" for="now_password">현재 비밀번호</label>
                <input id="now_password" class="pcm_input" onkeyup="inputOnkeyupEvent(this);" type="password" placeholder="현재 비밀번호를 입력해 주세요" autocomplete="current-password"/>
            </div>

            <div class="pcm_field">
                <label class="pcm_label" for="new_password">새 비밀번호</label>
                <input id="new_password" class="pcm_input" onkeyup="inputOnkeyupEvent(this);" type="password" placeholder="영문, 숫자, 특수문자 포함 8~15자리" autocomplete="new-password"/>
            </div>

            <div class="pcm_field">
                <label class="pcm_label" for="new_password_check">새 비밀번호 확인</label>
                <input id="new_password_check" class="pcm_input" onkeyup="inputOnkeyupEvent(this);" type="password" placeholder="새 비밀번호를 재입력해 주세요" autocomplete="new-password"/>
            </div>

        </div>

        <div class="pcm_foot">
            <button class="pcm_btn pcm_btn_cancel" onclick="PasswordChangeModal.handler(false);">취소</button>
            <button class="pcm_btn pcm_btn_submit" onclick="PasswordChangeModal.setPassword();">
                <i class="fa-solid fa-check"></i> 변경하기
            </button>
        </div>

    </div>
</div>

<?php
echo script_load('/component/passwordChangeModal/passwordChangeModal.js');
?>
