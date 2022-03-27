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








<?
echo script_load('/component/alert/alert.js');
?>