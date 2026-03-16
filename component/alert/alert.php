<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

echo css_load('/component/alert/alert.css');
?>

<div id="myrecord_alert" class="">
    <div class="alert_box">
        <div class="alert_scroll_area">
            <div class="alert_icon_wrap">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div class="alert_header">알림</div>
            <div class="alert_body"></div>
        </div>
        <div class="alert_btn_wrap">
            <button class="alert_btn" onclick="myrecordAlert();">확인</button>
        </div>
    </div>
</div>



<div id="myrecord_confirm" class="">
    <div class="confirm_box">
        <div class="confirm_scroll_area">
            <div class="confirm_icon_wrap">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <div class="confirm_header">확인</div>
            <div class="confirm_body"></div>
        </div>
        <div class="confirm_btn_wrap">
            <button class="cancel_btn">취소</button>
            <button class="confirm_btn">확인</button>
        </div>
    </div>
</div>



<?php
echo script_load('/component/alert/alert.js');
?>