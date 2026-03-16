<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

if(!$is_member) {
    echo '<script>myrecordAlert(\'on\', \'로그인 후 이용해주세요\', \'알림\', \'location.href="/account/login/"\');</script>';
    exit;
}

echo css_load('/record/record_certificate/index.css');
?>

<div class="certificate_page_header">
    <div class="header_inner">
        <p class="page_title">마이레코드 인증서</p>
        <p class="page_subtitle">공식 기록 인증서를 저장하고 공유하세요</p>
    </div>
</div>

<div class="certificate_page_wrap">

    <!-- 인증서 스크롤 컨테이너 (모바일에서 좌우 스크롤) -->
    <div class="certificate_scroll_box">
        <div id="certificate_box" class="certificate_box">

            <!-- 상단 헤더 바 -->
            <div class="cert_header_bar">
                <div class="cert_logo_wrap">
                    <img class="cert_logo" src="/img/company/myrecord_logo.png?ver=20230129" alt="MYRECORD"/>
                </div>
                <span class="cert_badge">OFFICIAL</span>
            </div>

            <!-- 타이틀 영역 -->
            <div class="cert_title_area">
                <p class="cert_title_ko">기 록 인 증 서</p>
                <p class="cert_title_en">RECORD CERTIFICATE</p>
                <div class="cert_divider"></div>
            </div>

            <!-- 기록 정보 -->
            <div class="cert_info_area">
                <div class="cert_info_row" name="record_nickname">
                    <span class="cert_info_label">닉 네 임</span>
                    <span class="cert_info_dots"></span>
                    <span class="cert_info_value"></span>
                </div>
                <div class="cert_info_row" name="record_master">
                    <span class="cert_info_label">종 &nbsp;&nbsp;&nbsp;&nbsp; 목</span>
                    <span class="cert_info_dots"></span>
                    <span class="cert_info_value"></span>
                </div>
                <div class="cert_info_row" name="record_weight">
                    <span class="cert_info_label">무 &nbsp;&nbsp;&nbsp;&nbsp; 게</span>
                    <span class="cert_info_dots"></span>
                    <span class="cert_info_value weight_value"></span>
                </div>
            </div>

            <!-- 인증 문구 -->
            <div class="cert_body_text">
                위 회원의 마이레코드 기록을<br>공식 인증합니다.
            </div>

            <!-- 날짜 + 서명 -->
            <div class="cert_signature_area">
                <div class="cert_date"></div>
                <div class="cert_signature_block">
                    <img class="cert_signature_logo" src="/img/company/myrecord_logo.png?ver=20230129" alt="MYRECORD"/>
                    <p class="cert_signature_name">MYRECORD</p>
                </div>
            </div>

            <!-- 하단 바 -->
            <div class="cert_footer_bar">
                <span>myrecord.kr</span>
                <span>공식 기록 인증</span>
            </div>

            <!-- 배경 워터마크 -->
            <div class="cert_watermark" aria-hidden="true">M</div>

        </div>
        <!-- certificate_box -->
    </div>

    <!-- 저장 버튼 -->
    <div class="cert_btn_wrap off">
        <button class="cert_download_btn" onclick="certificateDownload();">
            <i class="fa-solid fa-download"></i> 인증서 저장
        </button>
    </div>

</div>
<!-- certificate_page_wrap -->

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
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
