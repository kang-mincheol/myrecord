<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

echo css_load('/component/passwordChangeModal/passwordChangeModal.css');
?>

<div id="passwordChangeModal" class="on">
  <div class="modal-wrapper">
    <div class="modal-header">비밀번호 변경</div>
    <div class="modal-body">내용</div>
    <button class="modal-btn">버튼</button>
  </div>
</div>

<?php
echo script_load('/component/passwordChangeModal/passwordChangeModal.js');
?>