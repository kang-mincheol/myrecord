<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

echo css_load('/muscle_gain/calendar/index.css');
?>

<div class="calendar_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">득근달력</p>
        <p class="page_sub_text">월별 운동 기록을 한눈에 확인하세요</p>
    </div>
</div>

<div id="calendar_wrap">

    <!-- 월 이동 네비 -->
    <div class="calendar_nav">
        <a class="nav_btn prev" href="#" onclick="navMonth(-1); return false;">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <p class="nav_title" id="nav_title"></p>
        <a class="nav_btn next" href="#" onclick="navMonth(1); return false;">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
    </div>

    <!-- 이번 달 통계 -->
    <div class="month_stat">
        <i class="fa-solid fa-dumbbell"></i>
        이번 달 운동 <strong id="month_stat_count"><i class="fa-solid fa-spinner fa-spin"></i></strong>
    </div>

    <!-- 비로그인 안내 (JS에서 표시) -->
    <div id="login_notice_wrap" class="login_notice" style="display:none;">
        <i class="fa-solid fa-lock"></i>
        <p><a href="/account/login/">로그인</a> 후 득근달력을 확인할 수 있습니다.</p>
    </div>

    <!-- 달력 -->
    <div class="calendar_card">
        <!-- 요일 헤더 -->
        <div class="cal_header">
            <span class="dow sun">일</span>
            <span class="dow">월</span>
            <span class="dow">화</span>
            <span class="dow">수</span>
            <span class="dow">목</span>
            <span class="dow">금</span>
            <span class="dow sat">토</span>
        </div>

        <!-- 날짜 그리드 - JS로 생성 -->
        <div class="cal_grid" id="cal_grid"></div>
    </div>

</div>
<!-- calendar_wrap -->

<?php echo script_load('/muscle_gain/calendar/index.js'); ?>
<script>
$(function () {
    initCalendar();
});
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
