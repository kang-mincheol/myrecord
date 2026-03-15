<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");

$admin_page_title = '대시보드';

// ===== 통계 & 최근 목록 =====
$member_stats   = AdminDashboard::getMemberStats();
$record_stats   = AdminDashboard::getRecordStats();
$post_stats     = AdminDashboard::getPostStats();
$today_access   = AdminDashboard::getTodayAccess();
$recent_members = AdminDashboard::getRecentMembers(5);
$recent_records = AdminDashboard::getRecentRecords(5);

$total_members = $member_stats['total'];
$today_join    = $member_stats['today'];
$total_records = $record_stats['total'];
$today_records = $record_stats['today'];
$total_posts   = $post_stats['total'];
$today_posts   = $post_stats['today'];

include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<!-- ===== Page Header ===== -->
<div class="admin_page_header">
    <p class="admin_page_title">대시보드</p>
    <p class="admin_page_sub">마이레코드 서비스 현황을 한눈에 확인하세요</p>
</div>

<!-- ===== Stat Cards ===== -->
<div class="stat_cards_grid">

    <div class="stat_card">
        <div class="stat_icon blue"><i class="fa-solid fa-users"></i></div>
        <p class="stat_label">총 회원 수</p>
        <p class="stat_value"><?= number_format($total_members) ?><span>명</span></p>
        <p class="stat_diff today"><i class="fa-solid fa-plus"></i> 오늘 <?= $today_join ?>명 가입</p>
    </div>

    <div class="stat_card">
        <div class="stat_icon green"><i class="fa-solid fa-dumbbell"></i></div>
        <p class="stat_label">총 기록 수</p>
        <p class="stat_value"><?= number_format($total_records) ?><span>건</span></p>
        <p class="stat_diff today"><i class="fa-solid fa-plus"></i> 오늘 <?= $today_records ?>건 등록</p>
    </div>

    <div class="stat_card">
        <div class="stat_icon orange"><i class="fa-solid fa-comments"></i></div>
        <p class="stat_label">자유게시판 게시글</p>
        <p class="stat_value"><?= number_format($total_posts) ?><span>개</span></p>
        <p class="stat_diff today"><i class="fa-solid fa-plus"></i> 오늘 <?= $today_posts ?>개 작성</p>
    </div>

    <div class="stat_card">
        <div class="stat_icon purple"><i class="fa-solid fa-chart-line"></i></div>
        <p class="stat_label">오늘 접속 수</p>
        <p class="stat_value"><?= number_format($today_access) ?><span>회</span></p>
        <p class="stat_diff"><i class="fa-solid fa-circle" style="font-size:7px;"></i> 오늘 기준</p>
    </div>

</div>

<!-- ===== 최근 목록 2-col ===== -->
<div class="admin_grid_2">

    <!-- 최근 가입 회원 -->
    <div class="admin_card">
        <div class="card_header">
            <p class="card_title">최근 가입 회원</p>
            <a href="/admin_myrecord/account/" class="card_link">전체 보기 →</a>
        </div>
        <table class="admin_table">
            <thead>
                <tr>
                    <th>아이디</th>
                    <th>닉네임</th>
                    <th>마케팅</th>
                    <th>가입일</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($recent_members)): ?>
                <tr><td colspan="4" style="text-align:center; color:#aaa; padding:24px;">데이터가 없습니다</td></tr>
                <?php else: ?>
                <?php foreach($recent_members as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['user_id']) ?></td>
                    <td><?= htmlspecialchars($m['user_nickname']) ?></td>
                    <td>
                        <?php if($m['terms_marketing']): ?>
                            <span class="admin_badge approval">동의</span>
                        <?php else: ?>
                            <span style="color:#ccc; font-size:12px;">미동의</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:#64748b;"><?= date('Y.m.d', strtotime($m['create_datetime'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 최근 기록 신청 -->
    <div class="admin_card">
        <div class="card_header">
            <p class="card_title">최근 기록 신청</p>
            <a href="/admin_myrecord/record/" class="card_link">전체 보기 →</a>
        </div>
        <table class="admin_table">
            <thead>
                <tr>
                    <th>닉네임</th>
                    <th>종목</th>
                    <th>무게</th>
                    <th>상태</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($recent_records)): ?>
                <tr><td colspan="4" style="text-align:center; color:#aaa; padding:24px;">데이터가 없습니다</td></tr>
                <?php else: ?>
                <?php foreach($recent_records as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['record_nickname'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['record_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['record_weight']) ?> kg</td>
                    <td>
                        <span class="admin_badge <?= htmlspecialchars(strtolower($r['status_eng'] ?? 'request')) ?>">
                            <?= htmlspecialchars($r['record_status'] ?? '신청') ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_footer.php");
?>
