<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");
$admin_page_title = '기록 관리';

$admin_extra_css = '<style>
.record_filter_card {
    background: var(--card-bg); border: 1px solid var(--border-color);
    border-radius: 14px; padding: 16px 20px; margin-bottom: 16px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.record_filter_card select,
.record_filter_card input[type="text"] {
    height: 38px; padding: 0 12px;
    border: 1.5px solid var(--border-color); border-radius: 8px;
    background: #fafbff; color: var(--text-primary);
    font-size: 13px; font-family: inherit; outline: none; transition: border-color 0.2s;
}
.record_filter_card select:focus,
.record_filter_card input[type="text"]:focus { border-color: var(--accent); }
.record_filter_card input[type="text"] { width: 200px; }
.record_filter_card .filter_btn {
    height: 38px; padding: 0 18px; background: var(--accent); color: #fff;
    border-radius: 8px; font-size: 13px; font-weight: 700; border: none; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; font-family: inherit;
}
.record_filter_card .filter_btn:hover { background: #0118a0; }
.record_filter_card .reset_btn {
    height: 38px; padding: 0 14px; background: #f1f5f9; color: var(--text-secondary);
    border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer;
    text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; font-family: inherit;
}
.record_filter_card .reset_btn:hover { background: #e2e8f0; color: var(--text-primary); }
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
.weight_val { font-size: 14px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; }
.weight_val span { font-size: 11px; font-weight: 500; color: var(--text-secondary); }
.record_type_badge {
    display: inline-flex; align-items: center; height: 22px; padding: 0 8px;
    border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: -0.1px;
}
.record_type_badge.squat      { background: #e8f0ff; color: #0123B4; }
.record_type_badge.benchpress { background: #e6f7ee; color: #1a8a4a; }
.record_type_badge.deadlift   { background: #fff4e0; color: #c47a00; }
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

/* ===== 검증 모달 ===== */
.verify_modal_overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.5); z-index: 999;
    align-items: center; justify-content: center;
}
.verify_modal_overlay.open { display: flex; }
.verify_modal {
    background: #fff; border-radius: 16px;
    width: 640px; max-width: calc(100vw - 32px);
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 8px 40px rgba(0,0,0,0.2);
    display: flex; flex-direction: column;
}
.verify_modal .vm_header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px 14px; border-bottom: 1px solid var(--border-color);
    position: sticky; top: 0; background: #fff; z-index: 1;
}
.verify_modal .vm_header .vm_title { font-size: 15px; font-weight: 700; color: var(--text-primary); }
.verify_modal .vm_header .vm_close {
    width: 28px; height: 28px; border-radius: 8px; background: #f1f5f9;
    border: none; cursor: pointer; display: flex; align-items: center;
    justify-content: center; color: #666; font-size: 14px;
}
.verify_modal .vm_header .vm_close:hover { background: #e2e8f0; }
.verify_modal .vm_body { padding: 20px 24px 24px; }
.vm_section { margin-bottom: 24px; }
.vm_section:last-child { margin-bottom: 0; }
.vm_section_title {
    font-size: 11px; font-weight: 700; color: var(--text-secondary);
    letter-spacing: 0.6px; text-transform: uppercase;
    margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border-color);
}
.vm_info_grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 24px; }
.vm_info_item { display: flex; flex-direction: column; gap: 3px; }
.vm_info_label { font-size: 11px; font-weight: 600; color: var(--text-secondary); }
.vm_info_val { font-size: 13px; color: var(--text-primary); font-weight: 500; }
.vm_info_val.weight { font-size: 20px; font-weight: 800; color: var(--accent); letter-spacing: -1px; }
.vm_info_val.memo { color: var(--text-secondary); font-weight: 400; }
.vm_info_val.empty { color: #c0c4d4; }
.vm_files_wrap { display: flex; flex-wrap: wrap; gap: 10px; }
.vm_file_item {
    position: relative; border-radius: 10px; overflow: hidden;
    border: 2px solid var(--border-color); background: #f8fafc; transition: border-color 0.15s;
}
.vm_file_item:hover { border-color: var(--accent); }
.vm_file_item img { display: block; width: 160px; height: 120px; object-fit: cover; cursor: pointer; }
.vm_file_item .vm_file_video {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    width: 160px; height: 120px; gap: 8px; cursor: pointer; text-decoration: none; color: var(--text-secondary);
}
.vm_file_item .vm_file_video i { font-size: 28px; color: var(--accent); }
.vm_file_item .vm_file_video span { font-size: 11px; font-weight: 600; }
.vm_file_label {
    position: absolute; bottom: 0; left: 0; right: 0;
    padding: 4px 8px; background: rgba(0,0,0,0.55);
    color: #fff; font-size: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.vm_no_file { color: #c0c4d4; font-size: 13px; }
.vm_img_viewer {
    display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9);
    z-index: 1100; align-items: center; justify-content: center;
}
.vm_img_viewer.open { display: flex; }
.vm_img_viewer img { max-width: 90vw; max-height: 90vh; border-radius: 8px; }
.vm_img_viewer .iv_close {
    position: fixed; top: 20px; right: 24px;
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255,255,255,0.15); border: none; cursor: pointer;
    color: #fff; font-size: 16px; display: flex; align-items: center; justify-content: center;
}
.vm_img_viewer .iv_close:hover { background: rgba(255,255,255,0.3); }
.vm_inspection_list { display: flex; flex-direction: column; gap: 10px; }
.vm_inspection_item {
    background: #f8fafc; border: 1px solid var(--border-color); border-radius: 10px; padding: 12px 14px;
}
.vm_inspection_item .insp_top { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap; }
.vm_inspection_item .insp_admin { font-size: 12px; font-weight: 700; color: var(--text-primary); }
.vm_inspection_item .insp_date { font-size: 11px; color: var(--text-secondary); margin-left: auto; }
.vm_inspection_item .insp_comment { font-size: 13px; color: var(--text-secondary); line-height: 1.5; }
.vm_no_inspection { color: #c0c4d4; font-size: 13px; }
.vm_action_wrap {
    background: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px; padding: 16px 18px;
}
.vm_action_row { display: flex; gap: 10px; margin-bottom: 10px; align-items: center; }
.vm_action_wrap select {
    flex: 1; height: 40px; padding: 0 12px;
    border: 1.5px solid var(--border-color); border-radius: 8px;
    background: #fff; color: var(--text-primary); font-size: 13px;
    font-family: inherit; outline: none;
}
.vm_action_wrap select:focus { border-color: var(--accent); }
.vm_action_wrap textarea {
    width: 100%; min-height: 72px; padding: 10px 12px;
    border: 1.5px solid var(--border-color); border-radius: 8px;
    background: #fff; color: var(--text-primary); font-size: 13px;
    font-family: inherit; outline: none; resize: vertical; box-sizing: border-box;
}
.vm_action_wrap textarea:focus { border-color: var(--accent); }
.vm_action_wrap textarea::placeholder { color: #c0c4d4; }
.vm_save_btn {
    width: 100%; height: 44px; background: var(--accent); color: #fff;
    border-radius: 10px; font-size: 14px; font-weight: 700; border: none; cursor: pointer;
    margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; font-family: inherit;
}
.vm_save_btn:hover { background: #0118a0; }
.vm_save_btn:disabled { background: #c0c4d4; cursor: not-allowed; }
.vm_msg { margin-top: 8px; font-size: 12px; font-weight: 600; text-align: center; display: none; }
.vm_msg.success { color: #1a8a4a; display: block; }
.vm_msg.error   { color: #d63030; display: block; }
.vm_loading {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 60px 0; gap: 12px;
    color: var(--text-secondary); font-size: 13px;
}
.vm_loading i { font-size: 24px; color: var(--accent); animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>';

include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<div class="admin_page_header">
    <p class="admin_page_title">기록 관리</p>
    <p class="admin_page_sub">회원의 3대 기록 신청 내역을 조회하고 심사합니다</p>
</div>

<!-- Stats -->
<div class="stat_cards_grid" id="stat_cards_wrap">
    <div class="stat_card"><div class="stat_icon blue"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon blue"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon orange"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon green"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">로딩 중</p><p class="stat_value">-</p></div>
</div>

<!-- Quick Filter -->
<div class="status_quick_bar" id="quick_bar">
    <button class="on" onclick="setStatus('all')">전체 <span class="q_count" id="qc_all">-</span></button>
    <button onclick="setStatus('0')">신청 <span class="q_count" id="qc_0">-</span></button>
    <button onclick="setStatus('1')">심사중 <span class="q_count" id="qc_1">-</span></button>
    <button onclick="setStatus('2')">승인 완료 <span class="q_count" id="qc_2">-</span></button>
    <button onclick="setStatus('9')">반려 <span class="q_count" id="qc_9">-</span></button>
</div>

<!-- Filter -->
<div class="record_filter_card">
    <select id="f_record">
        <option value="0">전체 종목</option>
    </select>
    <input type="text" id="f_search_val" placeholder="닉네임 검색"/>
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
            <tr><th>#</th><th>닉네임</th><th>종목</th><th>무게</th><th>메모</th><th>상태</th><th>신청일</th></tr>
        </thead>
        <tbody id="list_tbody">
            <tr><td colspan="7" style="text-align:center;color:#aaa;padding:40px;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>
        </tbody>
    </table>
    <div class="admin_paging" id="paging_wrap"></div>
</div>

<!-- 검증 모달 -->
<div class="verify_modal_overlay" id="verifyModalOverlay" onclick="handleOverlayClick(event)">
    <div class="verify_modal">
        <div class="vm_header">
            <p class="vm_title"><i class="fa-solid fa-clipboard-check" style="color:var(--accent);margin-right:8px;"></i>기록 검증</p>
            <button class="vm_close" onclick="closeVerifyModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="vm_body" id="verifyModalBody">
            <div class="vm_loading"><i class="fa-solid fa-spinner"></i>불러오는 중...</div>
        </div>
    </div>
</div>

<!-- 이미지 전체보기 -->
<div class="vm_img_viewer" id="imgViewer" onclick="closeImgViewer()">
    <button class="iv_close" onclick="closeImgViewer()"><i class="fa-solid fa-xmark"></i></button>
    <img id="imgViewerSrc" src="" alt="첨부 이미지"/>
</div>

<script>
var _state  = { page: 1, record: 0, status: 'all', search_val: '' };
var _masters = [];
var currentRecordId = null;

function init() {
    var sp = new URLSearchParams(location.search);
    _state.page       = parseInt(sp.get('page')   || '1');
    _state.record     = parseInt(sp.get('record') || '0');
    _state.status     = sp.get('status')     || 'all';
    _state.search_val = sp.get('search_val') || '';

    document.getElementById('f_search_val').value = _state.search_val;
    updateQuickBar();
    loadData();
}

function loadData() {
    var qs = new URLSearchParams({
        page:       _state.page,
        record:     _state.record,
        status:     _state.status,
        search_val: _state.search_val,
    }).toString();

    history.replaceState(null, '', '?' + qs);

    document.getElementById('list_tbody').innerHTML =
        '<tr><td colspan="7" style="text-align:center;color:#aaa;padding:40px;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>';

    fetch('/api/v1/admin/records?' + qs)
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.code !== 'SUCCESS') { alert(res.msg || '오류가 발생했습니다.'); return; }
            _masters = res.masters;
            renderStats(res.stats);
            renderMasterSelect(res.masters);
            renderTable(res);
            renderPaging(res.total_count, res.page, res.page_size, res.total_pages);
        })
        .catch(function () { alert('서버 오류가 발생했습니다.'); });
}

function renderStats(s) {
    document.getElementById('stat_cards_wrap').innerHTML =
        statCard('blue',   'fa-dumbbell',         '총 기록 수',  s.total,    '건') +
        statCard('blue',   'fa-inbox',            '신청',        s.request,  '건') +
        statCard('orange', 'fa-magnifying-glass', '심사중',      s.audit,    '건') +
        statCard('green',  'fa-circle-check',     '승인 완료',   s.approval, '건');
    document.getElementById('qc_all').textContent = s.total;
    document.getElementById('qc_0').textContent   = s.request;
    document.getElementById('qc_1').textContent   = s.audit;
    document.getElementById('qc_2').textContent   = s.approval;
    document.getElementById('qc_9').textContent   = s.reject;
}

function renderMasterSelect(masters) {
    var sel   = document.getElementById('f_record');
    var curVal = String(_state.record);
    var html  = '<option value="0">전체 종목</option>';
    masters.forEach(function (m) {
        html += '<option value="' + m.id + '"' + (String(m.id) === curVal ? ' selected' : '') + '>' + esc(m.record_name_ko) + '</option>';
    });
    sel.innerHTML = html;
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
        var typeKey = (r.record_name || '').toLowerCase().replace(/\s/g, '');
        html += '<tr onclick="openVerifyModal(' + r.id + ')" style="cursor:pointer;">' +
            '<td style="color:var(--text-secondary);font-size:12px;">' + rowNo + '</td>' +
            '<td style="font-weight:600;">' + esc(r.user_nickname) + '</td>' +
            '<td><span class="record_type_badge ' + typeKey + '">' + esc(r.record_name_ko) + '</span></td>' +
            '<td><span class="weight_val">' + esc(r.record_weight) + '<span>kg</span></span></td>' +
            '<td style="color:var(--text-secondary);max-width:180px;">' +
                '<span style="display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">' +
                    (r.memo ? esc(r.memo) : '<span style="color:#d0d5e8;">-</span>') +
                '</span></td>' +
            '<td><span class="admin_badge ' + esc(r.status_value) + '">' + esc(r.status_text) + '</span></td>' +
            '<td style="color:var(--text-secondary);">' + esc(r.create_datetime) + '</td>' +
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

function setStatus(status) { _state.status = status; _state.page = 1; updateQuickBar(); loadData(); }

function updateQuickBar() {
    document.querySelectorAll('.status_quick_bar button').forEach(function (btn) {
        btn.classList.toggle('on', btn.getAttribute('onclick').includes("'" + _state.status + "'"));
    });
}

function goPage(p) { _state.page = p; loadData(); }

function doSearch() {
    _state.page       = 1;
    _state.record     = parseInt(document.getElementById('f_record').value || '0');
    _state.search_val = document.getElementById('f_search_val').value.trim();
    loadData();
}

function doReset() {
    _state = { page: 1, record: 0, status: 'all', search_val: '' };
    document.getElementById('f_search_val').value = '';
    updateQuickBar();
    loadData();
}

document.getElementById('f_search_val').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') doSearch();
});

/* ── 검증 모달 ── */
function openVerifyModal(id) {
    currentRecordId = id;
    document.getElementById('verifyModalBody').innerHTML =
        '<div class="vm_loading"><i class="fa-solid fa-spinner"></i>불러오는 중...</div>';
    document.getElementById('verifyModalOverlay').classList.add('open');

    $.ajax({
        type: 'GET',
        url: '/api/v1/admin/records/' + id,
        success: function (res) {
            if (res.code === 'SUCCESS') renderVerifyModal(res);
            else document.getElementById('verifyModalBody').innerHTML =
                '<p style="color:#d63030;padding:20px;">' + (res.msg || '오류가 발생했습니다.') + '</p>';
        },
        error: function () {
            document.getElementById('verifyModalBody').innerHTML =
                '<p style="color:#d63030;padding:20px;">서버 오류가 발생했습니다.</p>';
        }
    });
}

function renderVerifyModal(res) {
    var r     = res.record;
    var files = res.files       || [];
    var insps = res.inspections || [];

    var infoHtml = '<div class="vm_section"><p class="vm_section_title">기본 정보</p>' +
        '<div class="vm_info_grid">' +
            vmInfoItem('신청 ID', '#' + r.id) +
            vmInfoItem('아이디', r.user_id) +
            vmInfoItem('닉네임', r.user_nickname) +
            vmInfoItem('종목', r.record_name_ko + ' (' + r.record_name + ')') +
            '<div class="vm_info_item"><span class="vm_info_label">무게</span><span class="vm_info_val weight">' + r.record_weight + ' <small style="font-size:13px;font-weight:600;">kg</small></span></div>' +
            vmInfoItem('신청일', r.create_datetime) +
            '<div class="vm_info_item" style="grid-column:1/-1;"><span class="vm_info_label">메모</span>' +
                '<span class="vm_info_val memo ' + (r.memo ? '' : 'empty') + '">' + esc(r.memo || '없음') + '</span></div>' +
        '</div></div>';

    var filesHtml = '<div class="vm_section"><p class="vm_section_title">첨부 파일</p>';
    if (!files.length) {
        filesHtml += '<p class="vm_no_file">첨부 파일이 없습니다.</p>';
    } else {
        filesHtml += '<div class="vm_files_wrap">';
        files.forEach(function (f) {
            if (f.is_image) {
                filesHtml += '<div class="vm_file_item"><img src="' + f.src + '" alt="' + esc(f.original_name) + '" onclick="openImgViewer(\'' + f.src + '\')"/><span class="vm_file_label">' + esc(f.original_name) + '</span></div>';
            } else if (f.is_video) {
                filesHtml += '<div class="vm_file_item"><a href="' + f.src + '" class="vm_file_video" target="_blank"><i class="fa-solid fa-circle-play"></i><span>동영상 재생</span></a><span class="vm_file_label">' + esc(f.original_name) + '</span></div>';
            } else {
                filesHtml += '<div class="vm_file_item"><a href="' + f.src + '" class="vm_file_video" target="_blank" download><i class="fa-solid fa-file-arrow-down"></i><span>파일 다운로드</span></a><span class="vm_file_label">' + esc(f.original_name) + '</span></div>';
            }
        });
        filesHtml += '</div>';
    }
    filesHtml += '</div>';

    var badgeMap = { request: '신청', audit: '심사중', approval: '승인', reject: '반려' };
    var inspHtml = '<div class="vm_section"><p class="vm_section_title">검증 이력</p>';
    if (!insps.length) {
        inspHtml += '<p class="vm_no_inspection">검증 이력이 없습니다.</p>';
    } else {
        inspHtml += '<div class="vm_inspection_list">';
        insps.forEach(function (i) {
            inspHtml += '<div class="vm_inspection_item">' +
                '<div class="insp_top">' +
                    '<span class="insp_admin"><i class="fa-solid fa-user-shield" style="color:var(--accent);margin-right:5px;font-size:11px;"></i>' + esc(i.admin_nickname) + '</span>' +
                    '<span class="admin_badge ' + esc(i.status_value) + '">' + esc(i.status_text) + '</span>' +
                    '<span class="insp_date">' + esc(i.datetime) + '</span>' +
                '</div>' +
                '<p class="insp_comment">' + esc(i.admin_comment) + '</p>' +
            '</div>';
        });
        inspHtml += '</div>';
    }
    inspHtml += '</div>';

    var statusOptions = [{val:0,label:'신청'},{val:1,label:'심사중'},{val:2,label:'승인 완료'},{val:9,label:'반려'}];
    var optHtml = statusOptions.map(function (s) {
        return '<option value="' + s.val + '"' + (r.status_id === s.val ? ' selected' : '') + '>' + s.label + '</option>';
    }).join('');
    var actionHtml = '<div class="vm_section"><p class="vm_section_title">상태 변경</p>' +
        '<div class="vm_action_wrap">' +
            '<div class="vm_action_row"><select id="vm_status_select">' + optHtml + '</select></div>' +
            '<textarea id="vm_comment" placeholder="검증 코멘트를 입력하세요 (필수)"></textarea>' +
            '<button class="vm_save_btn" onclick="saveVerify()"><i class="fa-solid fa-floppy-disk"></i> 저장</button>' +
            '<p class="vm_msg" id="vm_msg"></p>' +
        '</div></div>';

    document.getElementById('verifyModalBody').innerHTML = infoHtml + filesHtml + inspHtml + actionHtml;
}

function vmInfoItem(label, value) {
    return '<div class="vm_info_item"><span class="vm_info_label">' + label + '</span><span class="vm_info_val">' + esc(value) + '</span></div>';
}

function saveVerify() {
    if (!currentRecordId) return;
    var status  = parseInt(document.getElementById('vm_status_select').value);
    var comment = document.getElementById('vm_comment').value.trim();
    var msg     = document.getElementById('vm_msg');
    var btn     = document.querySelector('.vm_save_btn');

    msg.textContent = ''; msg.className = 'vm_msg';

    if (!comment) { msg.textContent = '검증 코멘트를 입력해주세요.'; msg.className = 'vm_msg error'; return; }

    btn.disabled = true;
    $.ajax({
        type: 'PUT',
        url: '/api/v1/admin/records/' + currentRecordId + '/status',
        contentType: 'application/json',
        data: JSON.stringify({ status: status, comment: comment }),
        success: function (res) {
            btn.disabled = false;
            if (res.code === 'SUCCESS') {
                msg.textContent = '상태가 변경되었습니다.';
                msg.className   = 'vm_msg success';
                setTimeout(function () { location.reload(); }, 600);
            } else {
                msg.textContent = res.msg || '변경에 실패했습니다.';
                msg.className   = 'vm_msg error';
            }
        },
        error: function () { btn.disabled = false; msg.textContent = '서버 오류가 발생했습니다.'; msg.className = 'vm_msg error'; }
    });
}

function closeVerifyModal() { document.getElementById('verifyModalOverlay').classList.remove('open'); currentRecordId = null; }
function handleOverlayClick(e) { if (e.target === document.getElementById('verifyModalOverlay')) closeVerifyModal(); }
function openImgViewer(src) { document.getElementById('imgViewerSrc').src = src; document.getElementById('imgViewer').classList.add('open'); }
function closeImgViewer() { document.getElementById('imgViewer').classList.remove('open'); }

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        if (document.getElementById('imgViewer').classList.contains('open')) closeImgViewer();
        else closeVerifyModal();
    }
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
