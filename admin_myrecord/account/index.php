<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");

$admin_page_title = '회원 관리';

// ===== 파라미터 =====
$page      = max(1, (int)($_GET['page']       ?? 1));
$page_size = 20;
$offset    = ($page - 1) * $page_size;

$search_key = $_GET['search_key'] ?? 'all';
$search_val = trim($_GET['search_val'] ?? '');
$filter_st  = $_GET['status'] ?? 'all'; // all / normal / withdraw

// ===== WHERE 조건 =====
$where = "WHERE 1=1";

if ($filter_st === 'normal') {
    $where .= " AND (is_withdraw = 0 OR is_withdraw IS NULL)";
} elseif ($filter_st === 'withdraw') {
    $where .= " AND is_withdraw = 1";
}

if ($search_val !== '') {
    global $conn;
    $safe = mysqli_real_escape_string($conn, $search_val);
    if ($search_key === 'user_id') {
        $where .= " AND user_id LIKE '%{$safe}%'";
    } elseif ($search_key === 'user_nickname') {
        $where .= " AND user_nickname LIKE '%{$safe}%'";
    } elseif ($search_key === 'user_email') {
        $where .= " AND user_email LIKE '%{$safe}%'";
    } else {
        $where .= " AND (user_id LIKE '%{$safe}%' OR user_nickname LIKE '%{$safe}%' OR user_email LIKE '%{$safe}%')";
    }
}

// ===== 통계 =====
$stat_total    = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account")['cnt'] ?? 0);
$stat_today    = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account WHERE DATE(create_datetime) = CURDATE()")['cnt'] ?? 0);
$stat_mkt      = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account WHERE terms_marketing = 1 AND (is_withdraw = 0 OR is_withdraw IS NULL)")['cnt'] ?? 0);
$stat_withdraw = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account WHERE is_withdraw = 1")['cnt'] ?? 0);

// ===== 페이지네이션용 총 건수 =====
$total_count = (int)(sql_fetch("SELECT COUNT(*) AS cnt FROM Account {$where}")['cnt'] ?? 0);
$total_pages = max(1, (int)ceil($total_count / $page_size));
if ($page > $total_pages) $page = $total_pages;

// ===== 목록 조회 =====
$list = [];
$result = sql_query("
    SELECT
        id, user_id, user_nickname, user_name,
        user_email, user_phone, terms_marketing,
        create_datetime, login_date, is_admin, is_withdraw
    FROM Account
    {$where}
    ORDER BY create_datetime DESC
    LIMIT {$page_size} OFFSET {$offset}
");
while ($row = sql_fetch_array($result)) {
    $list[] = $row;
}

// ===== 검색 파라미터 → URL 쿼리 (페이지네이션 링크용) =====
$base_query = http_build_query([
    'search_key' => $search_key,
    'search_val' => $search_val,
    'status'     => $filter_st,
]);

$admin_extra_css = '<style>
/* ===== Account Page ===== */
.account_filter_card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.account_filter_card select,
.account_filter_card input[type="text"] {
    height: 38px;
    padding: 0 12px;
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    background: #fafbff;
    color: var(--text-primary);
    font-size: 13px;
    font-family: inherit;
    outline: none;
    transition: border-color 0.2s;
}
.account_filter_card select:focus,
.account_filter_card input[type="text"]:focus {
    border-color: var(--accent);
}
.account_filter_card input[type="text"] {
    width: 240px;
}
.account_filter_card .filter_btn {
    height: 38px;
    padding: 0 18px;
    background: var(--accent);
    color: #fff;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.15s;
}
.account_filter_card .filter_btn:hover { background: #0118a0; }
.account_filter_card .reset_btn {
    height: 38px;
    padding: 0 14px;
    background: #f1f5f9;
    color: var(--text-secondary);
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.15s;
}
.account_filter_card .reset_btn:hover { background: #e2e8f0; color: var(--text-primary); }

/* 결과 정보 */
.list_info_bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    border-bottom: 1px solid var(--border-color);
    font-size: 12px;
    color: var(--text-secondary);
}
.list_info_bar .total_count strong {
    color: var(--accent);
    font-weight: 700;
}

/* 탈퇴 행 dim */
.admin_table tbody tr.is_withdraw td {
    color: #b0b8cc;
}

/* 페이지네이션 */
.admin_paging {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 4px;
    padding: 20px 0 0;
}
.admin_paging a,
.admin_paging span {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1.5px solid var(--border-color);
    font-size: 13px;
    font-weight: 500;
    color: #666;
    text-decoration: none;
    transition: all 0.15s;
}
.admin_paging a:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: #eef1fc;
}
.admin_paging span.current {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
    font-weight: 700;
}
.admin_paging span.disabled {
    color: #ccc;
    cursor: default;
}

/* 상세 모달 */
.member_modal_overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 999;
    align-items: center;
    justify-content: center;
}
.member_modal_overlay.open {
    display: flex;
}
.member_modal {
    background: #fff;
    border-radius: 16px;
    width: 480px;
    max-width: calc(100vw - 40px);
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 8px 40px rgba(0,0,0,0.18);
}
.member_modal .modal_header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px 14px;
    border-bottom: 1px solid var(--border-color);
}
.member_modal .modal_header .modal_title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.3px;
}
.member_modal .modal_header .modal_close {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #f1f5f9;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 14px;
    transition: background 0.15s;
}
.member_modal .modal_header .modal_close:hover { background: #e2e8f0; }
.member_modal .modal_body { padding: 20px 22px 24px; }
.member_modal .modal_row {
    display: flex;
    gap: 8px;
    margin-bottom: 14px;
    align-items: flex-start;
}
.member_modal .modal_row:last-child { margin-bottom: 0; }
.member_modal .modal_label {
    flex-shrink: 0;
    width: 90px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    padding-top: 1px;
}
.member_modal .modal_val {
    font-size: 13px;
    color: var(--text-primary);
    font-weight: 400;
    word-break: break-all;
}
.member_modal .modal_val.empty { color: #c0c4d4; }
</style>';

include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<!-- ===== Page Header ===== -->
<div class="admin_page_header">
    <p class="admin_page_title">회원 관리</p>
    <p class="admin_page_sub">전체 회원 목록을 조회하고 관리합니다</p>
</div>

<!-- ===== Stats ===== -->
<div class="stat_cards_grid">
    <div class="stat_card">
        <div class="stat_icon blue"><i class="fa-solid fa-users"></i></div>
        <p class="stat_label">총 회원 수</p>
        <p class="stat_value"><?= number_format($stat_total) ?><span>명</span></p>
    </div>
    <div class="stat_card">
        <div class="stat_icon green"><i class="fa-solid fa-user-plus"></i></div>
        <p class="stat_label">오늘 신규 가입</p>
        <p class="stat_value"><?= number_format($stat_today) ?><span>명</span></p>
    </div>
    <div class="stat_card">
        <div class="stat_icon orange"><i class="fa-solid fa-bullhorn"></i></div>
        <p class="stat_label">마케팅 동의</p>
        <p class="stat_value"><?= number_format($stat_mkt) ?><span>명</span></p>
    </div>
    <div class="stat_card">
        <div class="stat_icon purple"><i class="fa-solid fa-user-slash"></i></div>
        <p class="stat_label">탈퇴 회원</p>
        <p class="stat_value"><?= number_format($stat_withdraw) ?><span>명</span></p>
    </div>
</div>

<!-- ===== Filter ===== -->
<form method="GET" action="">
    <div class="account_filter_card">
        <select name="status">
            <option value="all"      <?= $filter_st === 'all'      ? 'selected' : '' ?>>전체 상태</option>
            <option value="normal"   <?= $filter_st === 'normal'   ? 'selected' : '' ?>>정상</option>
            <option value="withdraw" <?= $filter_st === 'withdraw' ? 'selected' : '' ?>>탈퇴</option>
        </select>
        <select name="search_key">
            <option value="all"          <?= $search_key === 'all'          ? 'selected' : '' ?>>전체</option>
            <option value="user_id"      <?= $search_key === 'user_id'      ? 'selected' : '' ?>>아이디</option>
            <option value="user_nickname"<?= $search_key === 'user_nickname' ? 'selected' : '' ?>>닉네임</option>
            <option value="user_email"   <?= $search_key === 'user_email'   ? 'selected' : '' ?>>이메일</option>
        </select>
        <input type="text" name="search_val" value="<?= htmlspecialchars($search_val) ?>" placeholder="검색어를 입력하세요"/>
        <button type="submit" class="filter_btn"><i class="fa-solid fa-magnifying-glass"></i> 검색</button>
        <a href="/admin_myrecord/account/" class="reset_btn"><i class="fa-solid fa-rotate-left"></i> 초기화</a>
    </div>
</form>

<!-- ===== Table ===== -->
<div class="admin_card">
    <div class="list_info_bar">
        <span class="total_count">총 <strong><?= number_format($total_count) ?></strong>명</span>
        <span><?= $page ?> / <?= $total_pages ?> 페이지</span>
    </div>
    <table class="admin_table">
        <thead>
            <tr>
                <th>#</th>
                <th>아이디</th>
                <th>닉네임</th>
                <th>이메일</th>
                <th>마케팅</th>
                <th>관리자</th>
                <th>가입일</th>
                <th>마지막 로그인</th>
                <th>상태</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($list)): ?>
            <tr><td colspan="9" style="text-align:center; color:#aaa; padding:40px;">검색 결과가 없습니다</td></tr>
        <?php else: ?>
        <?php foreach ($list as $idx => $m):
            $row_no      = $total_count - $offset - $idx;
            $is_withdraw = (int)$m['is_withdraw'];
            $info_json   = htmlspecialchars(json_encode([
                'user_id'        => $m['user_id'],
                'user_nickname'  => $m['user_nickname'],
                'user_name'      => $m['user_name'] ?? '',
                'user_email'     => $m['user_email'] ?? '',
                'user_phone'     => $m['user_phone'] ?? '',
                'terms_marketing'=> (int)$m['terms_marketing'] ? '동의' : '미동의',
                'is_admin'       => (int)$m['is_admin']       ? '관리자' : '일반',
                'create_datetime'=> $m['create_datetime'] ? date('Y.m.d H:i', strtotime($m['create_datetime'])) : '-',
                'login_date'     => $m['login_date']     ? date('Y.m.d H:i', strtotime($m['login_date']))     : '-',
                'status'         => $is_withdraw         ? '탈퇴' : '정상',
            ], JSON_UNESCAPED_UNICODE), ENT_QUOTES);
        ?>
            <tr class="<?= $is_withdraw ? 'is_withdraw' : '' ?>"
                onclick="openMemberModal(this)" data-info="<?= $info_json ?>"
                style="cursor:pointer;">
                <td style="color:var(--text-secondary); font-size:12px;"><?= $row_no ?></td>
                <td style="font-weight:600;"><?= htmlspecialchars($m['user_id']) ?></td>
                <td><?= htmlspecialchars($m['user_nickname']) ?></td>
                <td style="color:var(--text-secondary);"><?= htmlspecialchars($m['user_email'] ?? '-') ?></td>
                <td>
                    <?php if ($m['terms_marketing']): ?>
                        <span class="admin_badge approval">동의</span>
                    <?php else: ?>
                        <span style="color:#ccc; font-size:12px;">미동의</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($m['is_admin']): ?>
                        <span class="admin_badge request"><i class="fa-solid fa-shield-halved"></i> 관리자</span>
                    <?php else: ?>
                        <span style="color:#ccc; font-size:12px;">-</span>
                    <?php endif; ?>
                </td>
                <td style="color:var(--text-secondary);">
                    <?= $m['create_datetime'] ? date('Y.m.d', strtotime($m['create_datetime'])) : '-' ?>
                </td>
                <td style="color:var(--text-secondary);">
                    <?= $m['login_date'] ? date('Y.m.d', strtotime($m['login_date'])) : '-' ?>
                </td>
                <td>
                    <?php if ($is_withdraw): ?>
                        <span class="admin_badge reject">탈퇴</span>
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
        $block = 10;
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

<!-- ===== 상세 모달 ===== -->
<div class="member_modal_overlay" id="memberModalOverlay" onclick="closeMemberModal(event)">
    <div class="member_modal">
        <div class="modal_header">
            <p class="modal_title"><i class="fa-solid fa-user" style="color:var(--accent);margin-right:7px;"></i>회원 상세 정보</p>
            <button class="modal_close" onclick="closeMemberModalDirect()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal_body" id="memberModalBody"></div>
    </div>
</div>

<script>
function openMemberModal(row) {
    const info = JSON.parse(row.dataset.info);
    const fields = [
        ['아이디',        info.user_id],
        ['닉네임',        info.user_nickname],
        ['이름',          info.user_name],
        ['이메일',        info.user_email],
        ['휴대폰',        info.user_phone],
        ['마케팅 동의',   info.terms_marketing],
        ['계정 유형',     info.is_admin],
        ['가입일',        info.create_datetime],
        ['마지막 로그인', info.login_date],
        ['상태',          info.status],
    ];
    let html = '';
    fields.forEach(([label, val]) => {
        const empty = !val || val === '' || val === '0';
        html += `
            <div class="modal_row">
                <span class="modal_label">${label}</span>
                <span class="modal_val ${empty ? 'empty' : ''}">${val || '없음'}</span>
            </div>`;
    });
    document.getElementById('memberModalBody').innerHTML = html;
    document.getElementById('memberModalOverlay').classList.add('open');
}

function closeMemberModal(e) {
    if (e.target === document.getElementById('memberModalOverlay')) {
        document.getElementById('memberModalOverlay').classList.remove('open');
    }
}

function closeMemberModalDirect() {
    document.getElementById('memberModalOverlay').classList.remove('open');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('memberModalOverlay').classList.remove('open');
    }
});
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_footer.php");
?>
