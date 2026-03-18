<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

echo css_load('/muscle_gain/calendar/index.css');

// ── 연/월 파라미터 ──
$year  = (int)($_GET['year']  ?? date('Y'));
$month = (int)($_GET['month'] ?? date('n'));

if ($month < 1)  { $month = 12; $year--; }
if ($month > 12) { $month = 1;  $year++; }

// ── 이동 URL ──
$prev_month = $month == 1  ? 12 : $month - 1;
$prev_year  = $month == 1  ? $year - 1 : $year;
$next_month = $month == 12 ? 1  : $month + 1;
$next_year  = $month == 12 ? $year + 1 : $year;

// ── 이번 달 운동 데이터 ──
$workout_map = []; // workout_date => ['id' => ..., 'exercise_summary' => ...]
$month_count = 0;

if ($is_member) {
    $logs = WorkoutLog::getMonthDates($member['id'], $year, $month);
    foreach ($logs as $log) {
        $workout_map[$log['workout_date']] = $log;
    }
    $month_count = count($workout_map);
}

// ── 달력 계산 ──
$first_ts   = mktime(0, 0, 0, $month, 1, $year);
$days_total = (int)date('t', $first_ts);   // 이번 달 총 일수
$start_dow  = (int)date('w', $first_ts);   // 1일의 요일 (0=일, 6=토)
$today      = date('Y-m-d');
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
        <a class="nav_btn prev" href="?year=<?= $prev_year ?>&month=<?= $prev_month ?>">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <p class="nav_title"><?= $year ?>년 <?= $month ?>월</p>
        <a class="nav_btn next" href="?year=<?= $next_year ?>&month=<?= $next_month ?>">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
    </div>

    <!-- 이번 달 통계 -->
    <div class="month_stat">
        <i class="fa-solid fa-dumbbell"></i>
        이번 달 운동 <strong><?= $month_count ?>회</strong>
    </div>

    <?php if (!$is_member): ?>
    <div class="login_notice">
        <i class="fa-solid fa-lock"></i>
        <p><a href="/account/login/">로그인</a> 후 득근달력을 확인할 수 있습니다.</p>
    </div>
    <?php endif; ?>

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

        <!-- 날짜 그리드 -->
        <div class="cal_grid">
            <?php
            $cell = 0;

            // 1일 이전 빈 셀
            for ($i = 0; $i < $start_dow; $i++, $cell++) {
                echo '<div class="cal_cell empty"></div>';
            }

            // 날짜 셀
            for ($d = 1; $d <= $days_total; $d++, $cell++) {
                $date_str  = sprintf('%04d-%02d-%02d', $year, $month, $d);
                $is_today  = ($date_str === $today);
                $dow       = ($start_dow + $d - 1) % 7;
                $is_sun    = $dow === 0;
                $is_sat    = $dow === 6;
                $has_log   = isset($workout_map[$date_str]);
                $log_id    = $has_log ? $workout_map[$date_str]['id'] : null;
                $summary   = $has_log ? htmlspecialchars($workout_map[$date_str]['exercise_summary'] ?? '') : '';

                $classes = ['cal_cell'];
                if ($is_today) $classes[] = 'today';
                if ($is_sun)   $classes[] = 'sun';
                if ($is_sat)   $classes[] = 'sat';
                if ($has_log)  $classes[] = 'has_log';

                $inner_open  = $has_log ? "<a href=\"/workout_log/view/?id={$log_id}\">" : '<div>';
                $inner_close = $has_log ? '</a>' : '</div>';
            ?>
                <div class="<?= implode(' ', $classes) ?>">
                    <?= $inner_open ?>
                        <span class="cal_date"><?= $d ?></span>
                        <?php if ($has_log): ?>
                            <span class="workout_dot" title="<?= $summary ?>"></span>
                            <?php if ($summary): ?>
                                <span class="workout_summary"><?= $summary ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?= $inner_close ?>
                </div>
            <?php } ?>

            <!-- 마지막 주 나머지 빈 셀 -->
            <?php
            $remaining = (7 - ($cell % 7)) % 7;
            for ($i = 0; $i < $remaining; $i++) {
                echo '<div class="cal_cell empty"></div>';
            }
            ?>
        </div>
    </div>

</div>
<!-- calendar_wrap -->

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>
