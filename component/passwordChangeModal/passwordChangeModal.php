<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

echo css_load('/component/passwordChangeModal/passwordChangeModal.css');
?>

<div id="passwordChangeModal" class="on">
  <div class="modal-wrapper">
    <div class="modal-header">비밀번호 변경</div>
    <div class="modal-body">
      <div class="myrecord_input_wrap now-password-wrap">
          <div class="label_box">
              <label class="wrap_label" for="now_password">현재 비밀번호</label>
          </div>
          <div class="form_value_box">
              <input id="now_password" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="password" placeholder="현재 비밀번호를 입력해 주세요"/>
          </div>
      </div>
      <div class="myrecord_input_wrap new-password-wrap">
          <div class="label_box">
              <label class="wrap_label" for="new_password">새 비밀번호</label>
          </div>
          <div class="form_value_box">
              <input id="new_password" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="password" placeholder="영문, 숫자, 특수문자 포함 8~15자리"/>
          </div>
      </div>
      <div class="myrecord_input_wrap new-password-check-wrap last">
          <div class="label_box">
              <label class="wrap_label" for="new_password_check">새 비밀번호 확인</label>
          </div>
          <div class="form_value_box">
              <input id="new_password_check" class="input_text" onkeyup="inputOnkeyupEvent(this);" type="password" placeholder="새 비밀번호를 재입력해 주세요"/>
          </div>
      </div>
    </div>
    <div class="modal-btn-wrapper">
      <button class="modal-btn change" onclick="PasswordChangeModal.setPassword();">비밀번호 변경</button>
      <button class="modal-btn close" onclick="PasswordChangeModal.handler(false);">닫기</button>
    </div>
  </div>
</div>

<?php
echo script_load('/component/passwordChangeModal/passwordChangeModal.js');
?>