<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");
$admin_page_title = '회원 관리';

$admin_extra_css = '<style>
.account_filter_card {
    background: var(--card-bg); border: 1px solid var(--border-color);
    border-radius: 14px; padding: 16px 20px; margin-bottom: 16px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.account_filter_card select,
.account_filter_card input[type="text"] {
    height: 38px; padding: 0 12px;
    border: 1.5px solid var(--border-color); border-radius: 8px;
    background: #fafbff; color: var(--text-primary);
    font-size: 13px; font-family: inherit; outline: none; transition: border-color 0.2s;
}
.account_filter_card select:focus,
.account_filter_card input[type="text"]:focus { border-color: var(--accent); }
.account_filter_card input[type="text"] { width: 240px; }
.account_filter_card .filter_btn {
    height: 38px; padding: 0 18px; background: var(--accent); color: #fff;
    border-radius: 8px; font-size: 13px; font-weight: 700; border: none; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; font-family: inherit;
}
.account_filter_card .filter_btn:hover { background: #0118a0; }
.account_filter_card .reset_btn {
    height: 38px; padding: 0 14px; background: #f1f5f9; color: var(--text-secondary);
    border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s;
}
.account_filter_card .reset_btn:hover { background: #e2e8f0; color: var(--text-primary); }
.list_info_bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px; border-bottom: 1px solid var(--border-color);
    font-size: 12px; color: var(--text-secondary);
}
.list_info_bar .total_count strong { color: var(--accent); font-weight: 700; }
.admin_table tbody tr.is_withdraw td { color: #b0b8cc; }
.admin_paging {
    display: flex; justify-content: center; align-items: center;
    gap: 4px; padding: 20px 0 0;
}
.admin_paging button, .admin_paging span {
    display: inline-flex; justify-content: center; align-items: center;
    width: 32px; height: 32px; border-radius: 8px;
    border: 1.5px solid var(--border-color); font-size: 13px; font-weight: 500;
    color: #666; background: none; cursor: pointer; transition: all 0.15s;
}
.admin_paging button:hover { border-color: var(--accent); color: var(--accent); background: #eef1fc; }
.admin_paging span.current { background: var(--accent); border-color: var(--accent); color: #fff; font-weight: 700; }
.admin_paging span.disabled { color: #ccc; cursor: default; border-color: var(--border-color); }
.member_modal_overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.45); z-index: 999;
    align-items: center; justify-content: center;
}
.member_modal_overlay.open { display: flex; }
.member_modal {
    background: #fff; border-radius: 16px; width: 480px;
    max-width: calc(100vw - 40px); max-height: 80vh; overflow-y: auto;
    box-shadow: 0 8px 40px rgba(0,0,0,0.18);
}
.member_modal .modal_header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px 14px; border-bottom: 1px solid var(--border-color);
}
.member_modal .modal_header .modal_title { font-size: 15px; font-weight: 700; color: var(--text-primary); }
.member_modal .modal_header .modal_close {
    width: 28px; height: 28px; border-radius: 8px; background: #f1f5f9;
    border: none; cursor: pointer; display: flex; align-items: center;
    justify-content: center; color: #666; font-size: 14px;
}
.member_modal .modal_header .modal_close:hover { background: #e2e8f0; }
.member_modal .modal_body { padding: 20px 22px 24px; }
.member_modal .modal_row { display: flex; gap: 8px; margin-bottom: 14px; align-items: flex-start; }
.member_modal .modal_row:last-child { margin-bottom: 0; }
.member_modal .modal_label { flex-shrink: 0; width: 90px; font-size: 12px; font-weight: 600; color: var(--text-secondary); padding-top: 1px; }
.member_modal .modal_val { font-size: 13px; color: var(--text-primary); word-break: break-all; }
.member_modal .modal_val.empty { color: #c0c4d4; }
</style>';

include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<div class="admin_page_header">
    <p class="admin_page_title">회원 관리</p>
    <p class="admin_page_sub">전체 회원 목록을 조회하고 관리합니다</p>
</div>

<!-- Stats -->
<div class="stat_cards_grid" id="stat_cards_wrap">
    <div class="stat_card"><div class="stat_icon blue"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon green"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon orange"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon purple"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
</div>

<!-- Filter -->
<div class="account_filter_card">
    <select id="f_status">
        <option value="all">전체 상태</option>
        <option value="normal">정상</option>
        <option value="withdraw">탈퇴</option>
    </select>
    <select id="f_search_key">
        <option value="all">전체</option>
        <option value="user_id">아이디</option>
        <option value="user_nickname">닉네임</option>
        <option value="user_email">이메일</option>
    </select>
    <input type="text" id="f_search_val" placeholder="검색어를 입력하세요"/>
    <button class="filter_btn" onclick="doSearch()"><i class="fa-solid fa-magnifying-glass"></i> 검색</button>
    <button class="reset_btn" onclick="doReset()"><i class="fa-solid fa-rotate-left"></i> 초기화</button>
</div>

<!-- Table -->
<div class="admin_card">
    <div class="list_info_bar">
        <span class="total_count">총 <strong id="total_count_val">-</strong>명</span>
        <span id="page_info">- / - 페이지</span>
    </div>
    <table class="admin_table">
        <thead>
            <tr>
                <th>#</th><th>아이디</th><th>닉네임</th><th>이메일</th>
                <th>마케팅</th><th>관리자</th><th>가입일</th><th>마지막 로그인</th><th>상태</th>
            </tr>
        </thead>
        <tbody id="list_tbody">
            <tr><td colspan="9" style="text-align:center;color:#aaa;padding:40px;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>
        </tbody>
    </table>
    <div class="admin_paging" id="paging_wrap"></div>
</div>

<!-- 상세 모달 -->
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
var _state = { page: 1, status: 'all', search_key: 'all', search_val: '' };

function init() {
    var sp = new URLSearchParams(location.search);
    _state.page       = parseInt(sp.get('page') || '1');
    _state.status     = sp.get('status')     || 'all';
    _state.search_key = sp.get('search_key') || 'all';
    _state.search_val = sp.get('search_val') || '';

    document.getElementById('f_status').value     = _state.status;
    document.getElementById('f_search_key').value = _state.search_key;
    document.getElementById('f_search_val').value = _state.search_val;

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
        '<tr><td colspan="9" style="text-align:center;color:#aaa;padding:40px;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>';

    fetch('/api/v1/admin/accounts?' + qs)
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
        statCard('blue',   'fa-users',      '총 회원 수',    s.total,    '명') +
        statCard('green',  'fa-user-plus',  '오늘 신규 가입', s.today,   '명') +
        statCard('orange', 'fa-bullhorn',   '마케팅 동의',   s.marketing,'명') +
        statCard('purple', 'fa-user-slash', '탈퇴 회원',     s.withdraw, '명');
}

function renderTable(res) {
    document.getElementById('total_count_val').textContent = res.total_count.toLocaleString();
    document.getElementById('page_info').textContent = res.page + ' / ' + res.total_pages + ' 페이지';

    if (!res.data.length) {
        document.getElementById('list_tbody').innerHTML =
            '<tr><td colspan="9" style="text-align:center;color:#aaa;padding:40px;">검색 결과가 없습니다</td></tr>';
        return;
    }

    var html = '';
    res.data.forEach(function (m, idx) {
        var rowNo = res.total_count - res.offset - idx;
        var info  = JSON.stringify(m).replace(/"/g, '&quot;');
        html += '<tr class="' + (m.is_withdraw ? 'is_withdraw' : '') + '" onclick="openMemberModal(\'' + info + '\')" style="cursor:pointer;">' +
            '<td style="color:var(--text-secondary);font-size:12px;">' + rowNo + '</td>' +
            '<td style="font-weight:600;">' + esc(m.user_id) + '</td>' +
            '<td>' + esc(m.user_nickname) + '</td>' +
            '<td style="color:var(--text-secondary);">' + esc(m.user_email || '-') + '</td>' +
            '<td>' + (m.terms_marketing ? '<span class="admin_badge approval">동의</span>' : '<span style="color:#ccc;font-size:12px;">미동의</span>') + '</td>' +
            '<td>' + (m.is_admin ? '<span class="admin_badge request"><i class="fa-solid fa-shield-halved"></i> 관리자</span>' : '<span style="color:#ccc;font-size:12px;">-</span>') + '</td>' +
            '<td style="color:var(--text-secondary);">' + esc(m.create_datetime.split(' ')[0]) + '</td>' +
            '<td style="color:var(--text-secondary);">' + esc(m.login_date !== '-' ? m.login_date.split(' ')[0] : '-') + '</td>' +
            '<td>' + (m.is_withdraw ? '<span class="admin_badge reject">탈퇴</span>' : '<span class="admin_badge approval">정상</span>') + '</td>' +
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
    if (blockStart > 1) {
        html += '<button onclick="goPage(' + (blockStart - 1) + ')"><i class="fa-solid fa-chevron-left"></i></button>';
    } else {
        html += '<span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>';
    }
    for (var p = blockStart; p <= blockEnd; p++) {
        if (p === page) html += '<span class="current">' + p + '</span>';
        else            html += '<button onclick="goPage(' + p + ')">' + p + '</button>';
    }
    if (blockEnd < totalPages) {
        html += '<button onclick="goPage(' + (blockEnd + 1) + ')"><i class="fa-solid fa-chevron-right"></i></button>';
    } else {
        html += '<span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>';
    }
    document.getElementById('paging_wrap').innerHTML = html;
}

function goPage(p) { _state.page = p; loadData(); }

function doSearch() {
    _state.page       = 1;
    _state.status     = document.getElementById('f_status').value;
    _state.search_key = document.getElementById('f_search_key').value;
    _state.search_val = document.getElementById('f_search_val').value.trim();
    loadData();
}

function doReset() {
    _state = { page: 1, status: 'all', search_key: 'all', search_val: '' };
    document.getElementById('f_status').value     = 'all';
    document.getElementById('f_search_key').value = 'all';
    document.getElementById('f_search_val').value = '';
    loadData();
}

document.getElementById('f_search_val').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') doSearch();
});

function openMemberModal(infoStr) {
    var m = typeof infoStr === 'string' ? JSON.parse(infoStr.replace(/&quot;/g, '"')) : infoStr;
    var fields = [
        ['아이디', m.user_id], ['닉네임', m.user_nickname], ['이름', m.user_name],
        ['이메일', m.user_email], ['휴대폰', m.user_phone],
        ['마케팅 동의', m.terms_marketing ? '동의' : '미동의'],
        ['계정 유형', m.is_admin ? '관리자' : '일반'],
        ['가입일', m.create_datetime], ['마지막 로그인', m.login_date],
        ['상태', m.is_withdraw ? '탈퇴' : '정상'],
    ];
    var html = '';
    fields.forEach(function (f) {
        var empty = !f[1] || f[1] === '';
        html += '<div class="modal_row"><span class="modal_label">' + f[0] + '</span>' +
            '<span class="modal_val ' + (empty ? 'empty' : '') + '">' + esc(f[1] || '없음') + '</span></div>';
    });
    document.getElementById('memberModalBody').innerHTML = html;
    document.getElementById('memberModalOverlay').classList.add('open');
}

function closeMemberModal(e) {
    if (e.target === document.getElementById('memberModalOverlay')) {
        document.getElementById('memberModalOverlay').classList.remove('open');
    }
}
function closeMemberModalDirect() { document.getElementById('memberModalOverlay').classList.remove('open'); }
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') document.getElementById('memberModalOverlay').classList.remove('open');
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
