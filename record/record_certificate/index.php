<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드


if(!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}
?>


<?php
echo css_load('/record/record_certificate/index.css');
?>


<div id="certificate_wrap">
    <div class="page_title">마이레코드 인증서</div>

    <div id="certificate_box" class="certificate_box">
        <div class="top_logo">
            <img class="logo_img" src="/img/company/myrecord_logo.png?ver=20230129"/>
        </div>

        <div class="record_info_wrap">
            <div class="info_row" name="record_nickname">
                <p class="info_title">닉네임</p>
                <p class="info_value"></p>
            </div>
            <div class="info_row" name="record_master">
                <p class="info_title">종목</p>
                <p class="info_value"></p>
            </div>
            <div class="info_row" name="record_weight">
                <p class="info_title">무게</p>
                <p class="info_value"></p>
            </div>
        </div>

        <div class="certificate_text">
            위 회원의<br>마이레코드 기록을<br>인증합니다.
        </div>

        <div class="myrecord_signature_wrap">
            <div class="certificate_date"></div>
            <div class="myrecord_signature">
                <img class="signature_img" src="/img/company/myrecord_logo.png?ver=20230129"/>
            </div>
        </div>

    </div>
</div>
<!-- certificate_wrap -->

<div class="img_btn_wrap off">
    <button class="certificate_down_btn" onclick="certificateDownload();">인증서 저장&nbsp;<i class="fa-solid fa-download"></i></button>
</div>

<div id="capture_wrapper"></div>

<?php
echo script_load('/assets/html2canvas.min.js');
echo script_load('/record/record_certificate/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>