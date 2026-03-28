<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");
$admin_page_title = '접속 로그';

$admin_extra_css = '<style>
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
.status_quick_bar { display: flex; gap: 6px; margin-bottom: 16px; flex-wrap: wrap; }
.status_quick_bar button {
    display: inline-flex; align-items: center; gap: 6px;
    height: 34px; padding: 0 14px; border-radius: 8px;
    border: 1.5px solid var(--border-color); background: var(--card-bg);
    color: var(--text-secondary); font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all 0.15s; font-family: inherit;
}
.status_quick_bar button:hover,
.status_quick_bar button.on { border-color: var(--accent); color: var(--accent); background: #eef1fc; font-weight: 700; }
.list_info_bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 20px; border-bottom: 1px solid var(--border-color);
    font-size: 12px; color: var(--text-secondary);
}
.list_info_bar .total_count strong { color: var(--accent); font-weight: 700; }
.al_url_cell {
    max-width: 340px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    font-size: 12px; color: var(--text-primary); font-family: monospace;
}
.al_ip_cell { font-size: 12px; font-family: monospace; color: var(--text-secondary); white-space: nowrap; }
.method_badge {
    display: inline-block; padding: 2px 7px; border-radius: 5px;
    font-size: 10px; font-weight: 700; letter-spacing: 0.3px;
    margin-right: 5px; vertical-align: middle; flex-shrink: 0;
}
.method_badge.get  { background: #e8f0ff; color: #0123B4; }
.method_badge.post { background: #fff4e0; color: #c47a00; }
.member_badge { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; color: var(--text-primary); }
.guest_badge { font-size: 11px; color: #aaa; }
.device_icon { font-size: 13px; color: var(--text-secondary); }
.admin_paging {
    display: flex; justify-content: center; align-items: center;
    gap: 4px; padding: 18px 0 4px;
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
    <p class="admin_page_title">접속 로그</p>
    <p class="admin_page_sub">사이트 접속 기록을 조회합니다</p>
</div>

<!-- Stats -->
<div class="stat_cards_grid" id="stat_cards_wrap">
    <div class="stat_card"><div class="stat_icon blue"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon green"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon orange"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon purple"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
</div>

<!-- Quick Filter -->
<div class="status_quick_bar">
    <button class="on" onclick="setType('all')"><i class="fa-solid fa-globe"></i> 전체</button>
    <button onclick="setType('member')"><i class="fa-solid fa-user"></i> 로그인</button>
    <button onclick="setType('guest')"><i class="fa-solid fa-user-slash"></i> 비로그인</button>
</div>

<!-- Search -->
<div class="al_filter_card">
    <select id="f_search_key">
        <option value="url">URL</option>
        <option value="ip">IP</option>
        <option value="user">회원</option>
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
                <th>#</th><th>회원</th><th>IP</th><th>URL</th>
                <th style="text-align:center;">기기</th><th>접속일시</th>
            </tr>
        </thead>
        <tbody id="list_tbody">
            <tr><td colspan="6" style="text-align:center;color:#aaa;padding:40px 0;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>
        </tbody>
    </table>
    <div class="admin_paging" id="paging_wrap"></div>
</div>

<script>
var _state = { page: 1, type: 'all', search_key: 'url', search_val: '' };

function init() {
    var sp = new URLSearchParams(location.search);
    _state.page       = parseInt(sp.get('page') || '1');
    _state.type       = sp.get('type')       || 'all';
    _state.search_key = sp.get('search_key') || 'url';
    _state.search_val = sp.get('search_val') || '';

    document.getElementById('f_search_key').value = _state.search_key;
    document.getElementById('f_search_val').value = _state.search_val;
    updateQuickBar();
    loadData();
}

function loadData() {
    var qs = new URLSearchParams({
        page:       _state.page,
        type:       _state.type,
        search_key: _state.search_key,
        search_val: _state.search_val,
    }).toString();

    history.replaceState(null, '', '?' + qs);

    document.getElementById('list_tbody').innerHTML =
        '<tr><td colspan="6" style="text-align:center;color:#aaa;padding:40px 0;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>';

    fetch('/api/v1/admin/access-logs?' + qs)
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.code !== 'SUCCESS') { alert(res.msg || '오류가 발생했습니다.'); return; }
            renderStats(res.stats);
            renderTable(res);
            renderPaging(res.total_count, res.page, res.page_size, res.total_pages);
        })
        .catch(function () { alert('서버 오류가 발생했습니다.'); });
}

function renderStats(s) {
    document.getElementById('stat_cards_wrap').innerHTML =
        statCard('blue',   'fa-list-check',      '총 로그',         s.total,     '건') +
        statCard('green',  'fa-calendar-day',    '오늘 접속',       s.today,     '건') +
        statCard('orange', 'fa-network-wired',   '오늘 고유 IP',    s.unique_ip, '개') +
        statCard('purple', 'fa-user-check',      '오늘 로그인 접속', s.member,   '건');
}

function renderTable(res) {
    document.getElementById('total_count_val').textContent = res.total_count.toLocaleString();
    document.getElementById('page_info').textContent = Number(res.page).toLocaleString() + ' / ' + Number(res.total_pages).toLocaleString() + ' 페이지';

    if (!res.data.length) {
        document.getElementById('list_tbody').innerHTML =
            '<tr><td colspan="6" style="text-align:center;color:#aaa;padding:40px 0;">검색 결과가 없습니다</td></tr>';
        return;
    }

    var deviceIcon = {
        mobile:  '<i class="fa-solid fa-mobile-screen-button device_icon" title="모바일"></i>',
        tablet:  '<i class="fa-solid fa-tablet-screen-button device_icon" title="태블릿"></i>',
        desktop: '<i class="fa-solid fa-desktop device_icon" title="데스크톱"></i>',
    };

    var html = '';
    res.data.forEach(function (r, idx) {
        var rowNo = res.total_count - res.offset - idx;
        html += '<tr>' +
            '<td style="color:var(--text-secondary);font-size:12px;">' + Number(rowNo).toLocaleString() + '</td>' +
            '<td>' + (r.user_nickname
                ? '<span class="member_badge"><i class="fa-solid fa-user" style="font-size:11px;color:var(--accent);"></i>' + esc(r.user_nickname) + '</span>'
                : '<span class="guest_badge">비로그인</span>') + '</td>' +
            '<td class="al_ip_cell">' + esc(r.ip_address) + '</td>' +
            '<td><span class="method_badge ' + r.method.toLowerCase() + '">' + esc(r.method) + '</span>' +
                '<span class="al_url_cell" title="' + esc(r.url) + '">' + esc(r.url) + '</span></td>' +
            '<td style="text-align:center;">' + (deviceIcon[r.device] || deviceIcon.desktop) + '</td>' +
            '<td style="color:var(--text-secondary);font-size:12px;white-space:nowrap;">' + esc(r.create_date) + '</td>' +
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

function setType(type) {
    _state.type = type;
    _state.page = 1;
    updateQuickBar();
    loadData();
}

function updateQuickBar() {
    document.querySelectorAll('.status_quick_bar button').forEach(function (btn) {
        btn.classList.toggle('on', btn.getAttribute('onclick').includes("'" + _state.type + "'"));
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
    _state = { page: 1, type: 'all', search_key: 'url', search_val: '' };
    document.getElementById('f_search_key').value = 'url';
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
