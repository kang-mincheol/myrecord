<?php
include_once('common.php');   // 기본파일 로드
include_once('header.php');   // 헤더파일 로드
?>
<?php
echo css_load('/common/index.css');
?>




<div id="section_1">
    <div class="bg_layer">
        <div class="section_text_wrap">
            <p class="badge_text">관리자 직접 검증 · 공식 인증 서비스</p>
            <p class="text_1">
                스트롱맨 시청자 출신<br>
                관리자가 직접 검증하는<br>
                3대측정
            </p>
            <a class="record_btn" href="/record/squat/list/">
                3대 레코드 등록하기<i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
<!-- section_1 -->




<div id="section_2">
    <div class="section_inner">
        <p class="section_title">등록 방법</p>
        <p class="section_sub">간단한 4단계로 나의 공식 기록을 인증받으세요</p>
        <div class="section_wrap">
            <div class="article_box" name="box_1">
                <div class="step_icon_wrap">
                    <i class="fa-solid fa-video"></i>
                </div>
                <p class="box_title">Step 1</p>
                <p class="box_text">본인의 기록을 영상으로 기록합니다</p>
            </div>
            <div class="article_box" name="box_2">
                <div class="step_icon_wrap">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <p class="box_title">Step 2</p>
                <p class="box_text">
                    기록시 사용한 원판을<br>
                    사진/영상으로 기록합니다
                </p>
            </div>
            <div class="article_box" name="box_3">
                <div class="step_icon_wrap">
                    <i class="fa-solid fa-upload"></i>
                </div>
                <p class="box_title">Step 3</p>
                <p class="box_text">마이레코드 등록</p>
            </div>
            <div class="article_box" name="box_4">
                <div class="step_icon_wrap">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <p class="box_title">Step 4</p>
                <p class="box_text">
                    관리자 심사 대기<br>
                    (승인시 마이레코드 인증서 생성)
                </p>
            </div>
        </div>
    </div>
</div>
<!-- section_2 -->




<div id="section_3">
    <div class="section_wrap">
        <p class="section_3_title">지금 바로 나의 기록을 인증받으세요</p>
        <p class="section_3_sub">3대 운동 기록을 등록하고 공식 인증서를 발급받으세요</p>
        <a class="section_3_btn" href="/record/squat/list/">
            레코드 등록하기<i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</div>
<!-- section_3 -->




<?php
echo script_load('/common/index.js');
?>
<script>
$(function () {

});
</script>
<?php
include_once('footer.php');   // 푸터파일 로드
?>
