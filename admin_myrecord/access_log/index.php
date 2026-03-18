<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");

$admin_page_title = '접속 로그';

// ===== 파라미터 =====
$page         = max(1, (int)($_GET['page']       ?? 1));
$page_size    = 30;
$offset       = ($page - 1) * $page_size;
$filter_type  = $_GET['type']       ?? 'all';
$search_key   = $_GET['search_key'] ?? 'url';
$search_val   = trim($_GET['search_val'] ?? '');

if (!in_array($filter_type, ['all', 'member', 'guest'])) $filter_type = 'all';
if (!in_array($search_key, ['url', 'ip', 'user']))       $search_key  = 'url';

// ===== 통계 =====
$stats = AdminAccessLog::getStats();

// ===== 총 건수 / 목록 =====
$total_count = AdminAccessLog::getTotalCount($filter_type, $search_key, $search_val);
$total_pages = max(1, (int)ceil($total_count / $page_size));
if ($page > $total_pages) $page = $total_pages;

$list = AdminAccessLog::getList($page, $page_size, $filter_type, $search_key, $search_val);

$base_query = http_build_query([
    'type'       => $filter_type,
    'search_key' => $search_key,
    'search_val' => $search_val,
]);

$admin_extra_css = '<style>
/* ─── 필터 ─── */
.al_filter_card {
    background: var(--card-bg); border: 1px solid var(--border-color);
    border-radius: 14px; padding: 14px 20px; margin-bottom: 16px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.al_filter_card select,
.al_filter_card input[type="text"] {
    height: 36px; padding: 0 12px;
    border: 1.5px solid var(--border-color); border-radius: 8px;
    background: #fafbff; color: var(--text-primary);
    font-size: 13px; font-family: inherit; outline: none; transition: border-color 0.2s;
}
.al_filter_card select:focus,
.al_filter_card input[type="text"]:focus { border-color: var(--accent); }
.al_filter_card input[type="text"] { width: 200px; }
.al_filter_card .filter_btn {
    height: 36px; padding: 0 16px; background: var(--accent); color: #fff;
    border-radius: 8px; font-size: 13px; font-weight: 700; border: none; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; font-family: inherit;
}
.al_filter_card .filter_btn:hover { background: #0118a0; }
.al_filter_card .reset_btn {
    height: 36px; padding: 0 14px; background: #f1f5f9; color: var(--text-secondary);
    border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
    transition: background 0.15s; font-family: inherit;
}
.al_filter_card .reset_btn:hover { background: #e2e8f0; color: var(--text-primary); }

/* ─── 퀵필터 ─── */
.status_quick_bar { display: flex; gap: 6px; margin-bottom: 16px; flex-wrap: wrap; }
.status_quick_bar a {
    display: inline-flex; align-items: center; gap: 6px;
    height: 34px; padding: 0 14px; border-radius: 8px;
    border: 1.5px solid var(--border-color); background: var(--card-bg);
    color: var(--text-secondary); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all 0.15s;
}
.status_quick_bar a:hover,
.status_quick_bar a.on { border-color: var(--accent); color: var(--accent); background: #eef1fc; }
.status_quick_bar a.on { font-weight: 700; }

/* ─── 목록 info bar ─── */
.list_info_bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 20px; border-bottom: 1px solid var(--border-color);
    font-size: 12px; color: var(--text-secondary);
}
.list_info_bar .total_count strong { color: var(--accent); font-weight: 700; }

/* ─── 테이블 셀 ─── */
.al_url_cell {
    max-width: 340px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    font-size: 12px; color: var(--text-primary); font-family: monospace;
}
.al_ip_cell {
    font-size: 12px; font-family: monospace; color: var(--text-secondary);
    white-space: nowrap;
}
.al_ua_cell {
    font-size: 12px; color: var(--text-secondary);
}
.method_badge {
    display: inline-block; padding: 2px 7px; border-radius: 5px;
    font-size: 10px; font-weight: 700; letter-spacing: 0.3px;
    margin-right: 5px; vertical-align: middle; flex-shrink: 0;
}
.method_badge.get  { background: #e8f0ff; color: #0123B4; }
.method_badge.post { background: #fff4e0; color: #c47a00; }

.member_badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 12px; font-weight: 600; color: var(--text-primary);
}
.guest_badge {
    font-size: 11px; color: #aaa;
}
.device_icon { font-size: 13px; color: var(--text-secondary); }

/* ─── 페이지네이션 ─── */
.admin_paging {
    display: flex; justify-content: center; align-items: center;
    gap: 4px; padding: 18px 0 4px;
}
.admin_paging a, .admin_paging span {
    display: inline-flex; justify-content: center; align-items: center;
    width: 32px; height: 32px; border-radius: 8px;
    border: 1.5px solid var(--border-color); font-size: 13px; font-weight: 500;
    color: #666; text-decoration: none; transition: all 0.15s;
}
.admin_paging a:hover { border-color: var(--accent); color: var(--accent); background: #eef1fc; }
.admin_paging span.current { background: var(--accent); border-color: var(--accent); color: #fff; font-weight: 700; }
.admin_paging span.disabled { color: #ccc; cursor: default; }
</style>';

include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<!-- ===== Page Header ===== -->
<div class="admin_page_header">
    <p class="admin_page_title">접속 로그</p>
    <p class="admin_page_sub">사이트 접속 기록을 조회합니다</p>
</div>

<!-- ===== Stats ===== -->
<div class="stat_cards_grid">
    <div class="stat_card">
        <div class="stat_icon blue"><i class="fa-solid fa-list-check"></i></div>
        <p class="stat_label">총 로그</p>
        <p class="stat_value"><?= number_format($stats['total']) ?><span>건</span></p>
    </div>
    <div class="stat_card">
        <div class="stat_icon green"><i class="fa-solid fa-calendar-day"></i></div>
        <p class="stat_label">오늘 접속</p>
        <p class="stat_value"><?= number_format($stats['today']) ?><span>건</span></p>
    </div>
    <div class="stat_card">
        <div class="stat_icon orange"><i class="fa-solid fa-network-wired"></i></div>
        <p class="stat_label">오늘 고유 IP</p>
        <p class="stat_value"><?= number_format($stats['unique_ip']) ?><span>개</span></p>
    </div>
    <div class="stat_card">
        <div class="stat_icon purple"><i class="fa-solid fa-user-check"></i></div>
        <p class="stat_label">오늘 로그인 접속</p>
        <p class="stat_value"><?= number_format($stats['member']) ?><span>건</span></p>
    </div>
</div>

<!-- ===== 퀵 필터 ===== -->
<?php
$qp = function($type) use ($search_key, $search_val) {
    return http_build_query(['type' => $type, 'search_key' => $search_key, 'search_val' => $search_val, 'page' => 1]);
};
?>
<div class="status_quick_bar">
    <a href="?<?= $qp('all')    ?>" class="<?= $filter_type === 'all'    ? 'on' : '' ?>"><i class="fa-solid fa-globe"></i> 전체</a>
    <a href="?<?= $qp('member') ?>" class="<?= $filter_type === 'member' ? 'on' : '' ?>"><i class="fa-solid fa-user"></i> 로그인</a>
    <a href="?<?= $qp('guest')  ?>" class="<?= $filter_type === 'guest'  ? 'on' : '' ?>"><i class="fa-solid fa-user-slash"></i> 비로그인</a>
</div>

<!-- ===== 검색 ===== -->
<form method="GET" action="">
    <input type="hidden" name="type" value="<?= htmlspecialchars($filter_type) ?>"/>
    <div class="al_filter_card">
        <select name="search_key">
            <option value="url"  <?= $search_key === 'url'  ? 'selected' : '' ?>>URL</option>
            <option value="ip"   <?= $search_key === 'ip'   ? 'selected' : '' ?>>IP</option>
            <option value="user" <?= $search_key === 'user' ? 'selected' : '' ?>>회원</option>
        </select>
        <input type="text" name="search_val" value="<?= htmlspecialchars($search_val) ?>" placeholder="검색어를 입력하세요"/>
        <button type="submit" class="filter_btn"><i class="fa-solid fa-magnifying-glass"></i> 검색</button>
        <a href="/admin_myrecord/access_log/" class="reset_btn"><i class="fa-solid fa-rotate-left"></i> 초기화</a>
    </div>
</form>

<!-- ===== 목록 테이블 ===== -->
<div class="admin_card">
    <div class="list_info_bar">
        <span class="total_count">총 <strong><?= number_format($total_count) ?></strong>건</span>
        <span><?= number_format($page) ?> / <?= number_format($total_pages) ?> 페이지</span>
    </div>
    <table class="admin_table">
        <thead>
            <tr>
                <th>#</th>
                <th>회원</th>
                <th>IP</th>
                <th>URL</th>
                <th style="text-align:center;">기기</th>
                <th>접속일시</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($list)): ?>
            <tr><td colspan="6" style="text-align:center; color:#aaa; padding:40px 0;">검색 결과가 없습니다</td></tr>
        <?php else: ?>
        <?php foreach ($list as $idx => $r):
            $row_no = $total_count - $offset - $idx;
            $method = AdminAccessLog::parseMethod($r['params'] ?? '');
            $device = AdminAccessLog::parseDevice($r['user_agent'] ?? '');
            $device_icon = match($device) {
                'mobile'  => '<i class="fa-solid fa-mobile-screen-button device_icon" title="모바일"></i>',
                'tablet'  => '<i class="fa-solid fa-tablet-screen-button device_icon" title="태블릿"></i>',
                default   => '<i class="fa-solid fa-desktop device_icon" title="데스크톱"></i>',
            };
        ?>
            <tr>
                <td style="color:var(--text-secondary); font-size:12px;"><?= number_format($row_no) ?></td>
                <td>
                    <?php if (!empty($r['user_nickname'])): ?>
                        <span class="member_badge"><i class="fa-solid fa-user" style="font-size:11px; color:var(--accent);"></i><?= htmlspecialchars($r['user_nickname']) ?></span>
                    <?php else: ?>
                        <span class="guest_badge">비로그인</span>
                    <?php endif; ?>
                </td>
                <td class="al_ip_cell"><?= htmlspecialchars($r['ip_address'] ?? '-') ?></td>
                <td>
                    <span class="method_badge <?= strtolower($method) ?>"><?= $method ?></span><span class="al_url_cell" title="<?= htmlspecialchars($r['url'] ?? '') ?>"><?= htmlspecialchars($r['url'] ?? '-') ?></span>
                </td>
                <td style="text-align:center;"><?= $device_icon ?></td>
                <td style="color:var(--text-secondary); font-size:12px; white-space:nowrap;">
                    <?= $r['create_date'] ? date('Y.m.d H:i', strtotime($r['create_date'])) : '-' ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- 페이지네이션 -->
    <?php if ($total_pages > 1):
        $block       = 10;
        $block_start = (int)(floor(($page - 1) / $block) * $block) + 1;
        $block_end   = min($block_start + $block - 1, $total_pages);
    ?>
    <div class="admin_paging">
        <?php if ($block_start > 1): ?>
            <a href="?page=<?= $block_start - 1 ?>&<?= $base_query ?>"><i class="fa-solid fa-chevron-left"></i></a>
        <?php else: ?>
            <span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>
        <?php endif; ?>
        <?php for ($p = $block_start; $p <= $block_end; $p++): ?>
            <?php if ($p === $page): ?>
                <span class="current"><?= $p ?></span>
            <?php else: ?>
                <a href="?page=<?= $p ?>&<?= $base_query ?>"><?= $p ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if ($block_end < $total_pages): ?>
            <a href="?page=<?= $block_end + 1 ?>&<?= $base_query ?>"><i class="fa-solid fa-chevron-right"></i></a>
        <?php else: ?>
            <span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_footer.php"); ?>
