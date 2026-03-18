<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");

$admin_page_title = '자유게시판 관리';

// ===== 파라미터 =====
$page          = max(1, (int)($_GET['page']       ?? 1));
$page_size     = 20;
$offset        = ($page - 1) * $page_size;
$filter_status = $_GET['status']     ?? 'all';
$search_key    = $_GET['search_key'] ?? 'title';
$search_val    = trim($_GET['search_val'] ?? '');

if (!in_array($search_key, ['title', 'writer'])) $search_key = 'title';

// ===== 통계 =====
$stats = AdminFreeBoard::getStats();

// ===== 총 건수 / 목록 =====
$total_count = AdminFreeBoard::getTotalCount($filter_status, $search_key, $search_val);
$total_pages = max(1, (int)ceil($total_count / $page_size));
if ($page > $total_pages) $page = $total_pages;

$list = AdminFreeBoard::getList($page, $page_size, $filter_status, $search_key, $search_val);

$base_query = http_build_query([
    'status'     => $filter_status,
    'search_key' => $search_key,
    'search_val' => $search_val,
]);

$admin_extra_css = '<style>
.fb_filter_card {
    background: var(--card-bg); border: 1px solid var(--border-color);
    border-radius: 14px; padding: 16px 20px; margin-bottom: 16px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.fb_filter_card select,
.fb_filter_card input[type="text"] {
    height: 38px; padding: 0 12px;
    border: 1.5px solid var(--border-color); border-radius: 8px;
    background: #fafbff; color: var(--text-primary);
    font-size: 13px; font-family: inherit; outline: none; transition: border-color 0.2s;
}
.fb_filter_card select:focus,
.fb_filter_card input[type="text"]:focus { border-color: var(--accent); }
.fb_filter_card input[type="text"] { width: 220px; }
.fb_filter_card .filter_btn {
    height: 38px; padding: 0 18px; background: var(--accent); color: #fff;
    border-radius: 8px; font-size: 13px; font-weight: 700; border: none; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
}
.fb_filter_card .filter_btn:hover { background: #0118a0; }
.fb_filter_card .reset_btn {
    height: 38px; padding: 0 14px; background: #f1f5f9; color: var(--text-secondary);
    border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
}
.fb_filter_card .reset_btn:hover { background: #e2e8f0; color: var(--text-primary); }

.status_quick_bar { display: flex; gap: 6px; margin-bottom: 16px; flex-wrap: wrap; }
.status_quick_bar a {
    display: inline-flex; align-items: center; gap: 6px;
    height: 34px; padding: 0 14px; border-radius: 8px;
    border: 1.5px solid var(--border-color); background: var(--card-bg);
    color: var(--text-secondary); font-size: 12px; font-weight: 600;
    text-decoration: none; transition: all 0.15s;
}
.status_quick_bar a:hover, .status_quick_bar a.on {
    border-color: var(--accent); color: var(--accent); background: #eef1fc;
}
.status_quick_bar a.on { font-weight: 700; }
.status_quick_bar .q_count {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 18px; height: 18px; padding: 0 5px; border-radius: 9px;
    background: var(--accent); color: #fff; font-size: 10px; font-weight: 700;
}
.status_quick_bar a:not(.on) .q_count { background: #e2e8f0; color: var(--text-secondary); }

.list_info_bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px; border-bottom: 1px solid var(--border-color);
    font-size: 12px; color: var(--text-secondary);
}
.list_info_bar .total_count strong { color: var(--accent); font-weight: 700; }

.admin_table tbody tr { cursor: pointer; }
.post_title_cell {
    max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    font-weight: 600; color: var(--text-primary);
}

.admin_paging {
    display: flex; justify-content: center; align-items: center;
    gap: 4px; padding: 20px 0 0;
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
    <p class="admin_page_title">자유게시판 관리</p>
    <p class="admin_page_sub">커뮤니티 자유게시판 게시글을 조회하고 관리합니다</p>
</div>

<!-- ===== Stats ===== -->
<div class="stat_cards_grid">
    <div class="stat_card">
        <div class="stat_icon blue"><i class="fa-solid fa-comments"></i></div>
        <p class="stat_label">총 게시글</p>
        <p class="stat_value"><?= number_format($stats['total']) ?><span>건</span></p>
    </div>
    <div class="stat_card">
        <div class="stat_icon green"><i class="fa-solid fa-calendar-day"></i></div>
        <p class="stat_label">오늘 작성</p>
        <p class="stat_value"><?= number_format($stats['today']) ?><span>건</span></p>
    </div>
    <div class="stat_card">
        <div class="stat_icon orange"><i class="fa-solid fa-comment-dots"></i></div>
        <p class="stat_label">총 댓글</p>
        <p class="stat_value"><?= number_format($stats['comments']) ?><span>건</span></p>
    </div>
    <div class="stat_card">
        <div class="stat_icon red"><i class="fa-solid fa-trash"></i></div>
        <p class="stat_label">삭제된 게시글</p>
        <p class="stat_value"><?= number_format($stats['deleted']) ?><span>건</span></p>
    </div>
</div>

<!-- ===== 상태 퀵 필터 ===== -->
<?php
$qp = function($status) use ($search_key, $search_val) {
    return http_build_query(['status' => $status, 'search_key' => $search_key, 'search_val' => $search_val, 'page' => 1]);
};
?>
<div class="status_quick_bar">
    <a href="?<?= $qp('all')     ?>" class="<?= $filter_status === 'all'     ? 'on' : '' ?>">전체 <span class="q_count"><?= $stats['total'] ?></span></a>
    <a href="?<?= $qp('deleted') ?>" class="<?= $filter_status === 'deleted' ? 'on' : '' ?>">삭제됨 <span class="q_count"><?= $stats['deleted'] ?></span></a>
</div>

<!-- ===== 검색 필터 ===== -->
<form method="GET" action="">
    <input type="hidden" name="status" value="<?= htmlspecialchars($filter_status) ?>"/>
    <div class="fb_filter_card">
        <select name="search_key">
            <option value="title"  <?= $search_key === 'title'  ? 'selected' : '' ?>>제목</option>
            <option value="writer" <?= $search_key === 'writer' ? 'selected' : '' ?>>닉네임</option>
        </select>
        <input type="text" name="search_val" value="<?= htmlspecialchars($search_val) ?>" placeholder="검색어를 입력하세요"/>
        <button type="submit" class="filter_btn"><i class="fa-solid fa-magnifying-glass"></i> 검색</button>
        <a href="/admin_myrecord/free_board/" class="reset_btn"><i class="fa-solid fa-rotate-left"></i> 초기화</a>
    </div>
</form>

<!-- ===== 목록 테이블 ===== -->
<div class="admin_card">
    <div class="list_info_bar">
        <span class="total_count">총 <strong><?= number_format($total_count) ?></strong>건</span>
        <span><?= $page ?> / <?= $total_pages ?> 페이지</span>
    </div>
    <table class="admin_table">
        <thead>
            <tr>
                <th>#</th>
                <th>제목</th>
                <th>작성자</th>
                <th style="text-align:center;">댓글</th>
                <th style="text-align:center;">조회</th>
                <th>작성일</th>
                <th style="text-align:center;">상태</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($list)): ?>
            <tr><td colspan="7" style="text-align:center; color:#aaa; padding:40px;">검색 결과가 없습니다</td></tr>
        <?php else: ?>
        <?php foreach ($list as $idx => $r):
            $row_no   = $total_count - $offset - $idx;
            $view_url = '/admin_myrecord/free_board/view/?id=' . $r['id'] . '&' . $base_query . '&page=' . $page;
        ?>
            <tr onclick="location.href='<?= $view_url ?>'">
                <td style="color:var(--text-secondary); font-size:12px;"><?= $row_no ?></td>
                <td class="post_title_cell"><?= htmlspecialchars($r['title']) ?></td>
                <td style="font-weight:600;"><?= htmlspecialchars($r['user_nickname'] ?? '-') ?></td>
                <td style="text-align:center; color:var(--text-secondary);"><?= number_format($r['comment_count']) ?></td>
                <td style="text-align:center; color:var(--text-secondary);"><?= number_format($r['view_count']) ?></td>
                <td style="color:var(--text-secondary);">
                    <?= $r['create_date'] ? date('Y.m.d', strtotime($r['create_date'])) : '-' ?>
                </td>
                <td style="text-align:center;">
                    <?php if ($r['is_delete']): ?>
                        <span class="admin_badge reject">삭제됨</span>
                    <?php else: ?>
                        <span class="admin_badge approval">정상</span>
                    <?php endif; ?>
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

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_footer.php");
?>
