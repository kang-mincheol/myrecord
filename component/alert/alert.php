<?
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

echo css_load('/component/alert/alert.css');
?>

<div id="myrecord_alert" class="">
    <div class="alert_box">
        <div class="alert_header">알림</div>
        <div class="alert_body"></div>
        <button class="alert_btn" onclick="myrecordAlert();">확인</button>
    </div>
</div>



<div id="myrecord_confirm" class="">
    <div class="confirm_box">
        <div class="confirm_header">확인</div>
        <div class="confirm_body"></div>
        <div class="confirm_btn_wrap">
            <button class="cancel_btn" onclick="myrecordConfirm();">취소</button>
            <button class="confirm_btn" onclick="myrecordConfirm();">확인</button>
        </div>
    </div>
</div>



<?
echo script_load('/component/alert/alert.js');
?>