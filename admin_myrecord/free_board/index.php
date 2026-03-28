<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");
$admin_page_title = '자유게시판 관리';

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
    display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; font-family: inherit;
}
.fb_filter_card .filter_btn:hover { background: #0118a0; }
.fb_filter_card .reset_btn {
    height: 38px; padding: 0 14px; background: #f1f5f9; color: var(--text-secondary);
    border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; font-family: inherit;
}
.fb_filter_card .reset_btn:hover { background: #e2e8f0; color: var(--text-primary); }
.status_quick_bar { display: flex; gap: 6px; margin-bottom: 16px; flex-wrap: wrap; }
.status_quick_bar button {
    display: inline-flex; align-items: center; gap: 6px;
    height: 34px; padding: 0 14px; border-radius: 8px;
    border: 1.5px solid var(--border-color); background: var(--card-bg);
    color: var(--text-secondary); font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all 0.15s; font-family: inherit;
}
.status_quick_bar button:hover, .status_quick_bar button.on {
    border-color: var(--accent); color: var(--accent); background: #eef1fc;
}
.status_quick_bar button.on { font-weight: 700; }
.status_quick_bar .q_count {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 18px; height: 18px; padding: 0 5px; border-radius: 9px;
    background: var(--accent); color: #fff; font-size: 10px; font-weight: 700;
}
.status_quick_bar button:not(.on) .q_count { background: #e2e8f0; color: var(--text-secondary); }
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
.admin_paging button, .admin_paging span {
    display: inline-flex; justify-content: center; align-items: center;
    width: 32px; height: 32px; border-radius: 8px;
    border: 1.5px solid var(--border-color); font-size: 13px; font-weight: 500;
    color: #666; background: none; cursor: pointer; transition: all 0.15s; font-family: inherit;
}
.admin_paging button:hover { border-color: var(--accent); color: var(--accent); background: #eef1fc; }
.admin_paging span.current { background: var(--accent); border-color: var(--accent); color: #fff; font-weight: 700; }
.admin_paging span.disabled { color: #ccc; cursor: default; border-color: var(--border-color); }
</style>';

include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<div class="admin_page_header">
    <p class="admin_page_title">자유게시판 관리</p>
    <p class="admin_page_sub">커뮤니티 자유게시판 게시글을 조회하고 관리합니다</p>
</div>

<!-- Stats -->
<div class="stat_cards_grid" id="stat_cards_wrap">
    <div class="stat_card"><div class="stat_icon blue"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon green"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon orange"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon red"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
</div>

<!-- Quick Filter -->
<div class="status_quick_bar" id="quick_bar">
    <button class="on" onclick="setStatus('all')">전체 <span class="q_count" id="qc_all">-</span></button>
    <button onclick="setStatus('deleted')">삭제됨 <span class="q_count" id="qc_deleted">-</span></button>
</div>

<!-- Search Filter -->
<div class="fb_filter_card">
    <select id="f_search_key">
        <option value="title">제목</option>
        <option value="contents">내용</option>
        <option value="writer">닉네임</option>
    </select>
    <input type="text" id="f_search_val" placeholder="검색어를 입력하세요"/>
    <button class="filter_btn" onclick="doSearch()"><i class="fa-solid fa-magnifying-glass"></i> 검색</button>
    <button class="reset_btn" onclick="doReset()"><i class="fa-solid fa-rotate-left"></i> 초기화</button>
</div>

<!-- Table -->
<div class="admin_card">
    <div class="list_info_bar">
        <span class="total_count">총 <strong id="total_count_val">-</strong>건</span>
        <span id="page_info">- / - 페이지</span>
    </div>
    <table class="admin_table">
        <thead>
            <tr>
                <th>#</th><th>제목</th><th>작성자</th>
                <th style="text-align:center;">댓글</th>
                <th style="text-align:center;">조회</th>
                <th>작성일</th>
                <th style="text-align:center;">상태</th>
            </tr>
        </thead>
        <tbody id="list_tbody">
            <tr><td colspan="7" style="text-align:center;color:#aaa;padding:40px;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>
        </tbody>
    </table>
    <div class="admin_paging" id="paging_wrap"></div>
</div>

<script>
var _state = { page: 1, status: 'all', search_key: 'title', search_val: '' };
var _stats = {};

function init() {
    var sp = new URLSearchParams(location.search);
    _state.page       = parseInt(sp.get('page') || '1');
    _state.status     = sp.get('status')     || 'all';
    _state.search_key = sp.get('search_key') || 'title';
    _state.search_val = sp.get('search_val') || '';

    document.getElementById('f_search_key').value = _state.search_key;
    document.getElementById('f_search_val').value = _state.search_val;
    updateQuickBar();
    loadData();
}

function loadData() {
    var qs = new URLSearchParams({
        page:       _state.page,
        status:     _state.status,
        search_key: _state.search_key,
        search_val: _state.search_val,
    }).toString();

    history.replaceState(null, '', '?' + qs);

    document.getElementById('list_tbody').innerHTML =
        '<tr><td colspan="7" style="text-align:center;color:#aaa;padding:40px;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>';

    fetch('/api/v1/admin/boards?' + qs)
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.code !== 'SUCCESS') { alert(res.msg || '오류가 발생했습니다.'); return; }
            _stats = res.stats;
            renderStats(res.stats);
            renderTable(res);
            renderPaging(res.total_count, res.page, res.page_size, res.total_pages);
        })
        .catch(function () { alert('서버 오류가 발생했습니다.'); });
}

function renderStats(s) {
    document.getElementById('stat_cards_wrap').innerHTML =
        statCard('blue',   'fa-comments',     '총 게시글',      s.total,    '건') +
        statCard('green',  'fa-calendar-day', '오늘 작성',      s.today,    '건') +
        statCard('orange', 'fa-comment-dots', '총 댓글',        s.comments, '건') +
        statCard('red',    'fa-trash',        '삭제된 게시글',  s.deleted,  '건');
    document.getElementById('qc_all').textContent     = s.total;
    document.getElementById('qc_deleted').textContent = s.deleted;
}

function renderTable(res) {
    document.getElementById('total_count_val').textContent = res.total_count.toLocaleString();
    document.getElementById('page_info').textContent = res.page + ' / ' + res.total_pages + ' 페이지';

    if (!res.data.length) {
        document.getElementById('list_tbody').innerHTML =
            '<tr><td colspan="7" style="text-align:center;color:#aaa;padding:40px;">검색 결과가 없습니다</td></tr>';
        return;
    }

    var html = '';
    res.data.forEach(function (r, idx) {
        var rowNo   = res.total_count - res.offset - idx;
        var viewUrl = '/admin_myrecord/free_board/view/?id=' + r.id +
            '&status=' + encodeURIComponent(_state.status) +
            '&search_key=' + encodeURIComponent(_state.search_key) +
            '&search_val=' + encodeURIComponent(_state.search_val) +
            '&page=' + res.page;
        html += '<tr onclick="location.href=\'' + viewUrl + '\'">' +
            '<td style="color:var(--text-secondary);font-size:12px;">' + rowNo + '</td>' +
            '<td class="post_title_cell">' + esc(r.title) + '</td>' +
            '<td style="font-weight:600;">' + esc(r.user_nickname) + '</td>' +
            '<td style="text-align:center;color:var(--text-secondary);">' + r.comment_count.toLocaleString() + '</td>' +
            '<td style="text-align:center;color:var(--text-secondary);">' + r.view_count.toLocaleString() + '</td>' +
            '<td style="color:var(--text-secondary);">' + esc(r.create_date) + '</td>' +
            '<td style="text-align:center;">' +
                (r.is_delete ? '<span class="admin_badge reject">삭제됨</span>' : '<span class="admin_badge approval">정상</span>') +
            '</td>' +
        '</tr>';
    });
    document.getElementById('list_tbody').innerHTML = html;
}

function renderPaging(totalCount, page, pageSize, totalPages) {
    if (totalPages <= 1) { document.getElementById('paging_wrap').innerHTML = ''; return; }
    var block = 10;
    var blockStart = Math.floor((page - 1) / block) * block + 1;
    var blockEnd   = Math.min(blockStart + block - 1, totalPages);
    var html = '';
    if (blockStart > 1) html += '<button onclick="goPage(' + (blockStart - 1) + ')"><i class="fa-solid fa-chevron-left"></i></button>';
    else                html += '<span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>';
    for (var p = blockStart; p <= blockEnd; p++) {
        if (p === page) html += '<span class="current">' + p + '</span>';
        else            html += '<button onclick="goPage(' + p + ')">' + p + '</button>';
    }
    if (blockEnd < totalPages) html += '<button onclick="goPage(' + (blockEnd + 1) + ')"><i class="fa-solid fa-chevron-right"></i></button>';
    else                       html += '<span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>';
    document.getElementById('paging_wrap').innerHTML = html;
}

function setStatus(status) {
    _state.status = status;
    _state.page   = 1;
    updateQuickBar();
    loadData();
}

function updateQuickBar() {
    document.querySelectorAll('.status_quick_bar button').forEach(function (btn) {
        btn.classList.toggle('on', btn.getAttribute('onclick').includes("'" + _state.status + "'"));
    });
}

function goPage(p) { _state.page = p; loadData(); }

function doSearch() {
    _state.page       = 1;
    _state.search_key = document.getElementById('f_search_key').value;
    _state.search_val = document.getElementById('f_search_val').value.trim();
    loadData();
}

function doReset() {
    _state = { page: 1, status: 'all', search_key: 'title', search_val: '' };
    document.getElementById('f_search_key').value = 'title';
    document.getElementById('f_search_val').value = '';
    updateQuickBar();
    loadData();
}

document.getElementById('f_search_val').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') doSearch();
});

function statCard(color, icon, label, value, unit) {
    return '<div class="stat_card"><div class="stat_icon ' + color + '"><i class="fa-solid ' + icon + '"></i></div>' +
        '<p class="stat_label">' + label + '</p>' +
        '<p class="stat_value">' + Number(value).toLocaleString() + '<span>' + unit + '</span></p></div>';
}

function esc(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

init();
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_footer.php"); ?>
