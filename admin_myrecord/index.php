<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");
$admin_page_title = '대시보드';
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<div class="admin_page_header">
    <p class="admin_page_title">대시보드</p>
    <p class="admin_page_sub">마이레코드 서비스 현황을 한눈에 확인하세요</p>
</div>

<!-- Stat Cards -->
<div class="stat_cards_grid" id="stat_cards_wrap">
    <div class="stat_card"><div class="stat_icon blue"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">불러오는 중...</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon green"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">불러오는 중...</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon orange"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">불러오는 중...</p><p class="stat_value">-</p></div>
    <div class="stat_card"><div class="stat_icon purple"><i class="fa-solid fa-spinner fa-spin"></i></div><p class="stat_label">불러오는 중...</p><p class="stat_value">-</p></div>
</div>

<!-- 최근 목록 -->
<div class="admin_grid_2">
    <div class="admin_card">
        <div class="card_header">
            <p class="card_title">최근 가입 회원</p>
            <a href="/admin_myrecord/account/" class="card_link">전체 보기 →</a>
        </div>
        <table class="admin_table">
            <thead><tr><th>아이디</th><th>닉네임</th><th>마케팅</th><th>가입일</th></tr></thead>
            <tbody id="recent_members_tbody">
                <tr><td colspan="4" style="text-align:center; color:#aaa; padding:24px;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>
            </tbody>
        </table>
    </div>
    <div class="admin_card">
        <div class="card_header">
            <p class="card_title">최근 기록 신청</p>
            <a href="/admin_myrecord/record/" class="card_link">전체 보기 →</a>
        </div>
        <table class="admin_table">
            <thead><tr><th>닉네임</th><th>종목</th><th>무게</th><th>상태</th></tr></thead>
            <tbody id="recent_records_tbody">
                <tr><td colspan="4" style="text-align:center; color:#aaa; padding:24px;"><i class="fa-solid fa-spinner fa-spin"></i></td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
(function () {
    fetch('/api/v1/admin/dashboard')
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.code !== 'SUCCESS') return;
            var d = res.data;

            // ── Stat cards ──
            document.getElementById('stat_cards_wrap').innerHTML =
                statCard('blue',   'fa-users',      '총 회원 수',       d.stats.members.total,   '명', '오늘 ' + d.stats.members.today + '명 가입') +
                statCard('green',  'fa-dumbbell',   '총 기록 수',       d.stats.records.total,   '건', '오늘 ' + d.stats.records.today + '건 등록') +
                statCard('orange', 'fa-comments',   '자유게시판 게시글', d.stats.posts.total,    '개', '오늘 ' + d.stats.posts.today + '개 작성') +
                statCard('purple', 'fa-chart-line', '오늘 접속 수',      d.stats.today_access,   '회', '오늘 기준');

            // ── Recent members ──
            var mHtml = '';
            if (!d.recent_members.length) {
                mHtml = '<tr><td colspan="4" style="text-align:center;color:#aaa;padding:24px;">데이터가 없습니다</td></tr>';
            } else {
                d.recent_members.forEach(function (m) {
                    mHtml += '<tr>' +
                        '<td>' + esc(m.user_id) + '</td>' +
                        '<td>' + esc(m.user_nickname) + '</td>' +
                        '<td>' + (m.terms_marketing ? '<span class="admin_badge approval">동의</span>' : '<span style="color:#ccc;font-size:12px;">미동의</span>') + '</td>' +
                        '<td style="color:#64748b;">' + esc(m.create_datetime) + '</td>' +
                    '</tr>';
                });
            }
            document.getElementById('recent_members_tbody').innerHTML = mHtml;

            // ── Recent records ──
            var rHtml = '';
            if (!d.recent_records.length) {
                rHtml = '<tr><td colspan="4" style="text-align:center;color:#aaa;padding:24px;">데이터가 없습니다</td></tr>';
            } else {
                d.recent_records.forEach(function (r) {
                    rHtml += '<tr>' +
                        '<td>' + esc(r.record_nickname) + '</td>' +
                        '<td>' + esc(r.record_name) + '</td>' +
                        '<td>' + esc(r.record_weight) + ' kg</td>' +
                        '<td><span class="admin_badge ' + esc(r.status_eng.toLowerCase()) + '">' + esc(r.record_status) + '</span></td>' +
                    '</tr>';
                });
            }
            document.getElementById('recent_records_tbody').innerHTML = rHtml;
        })
        .catch(function () {
            document.getElementById('stat_cards_wrap').innerHTML =
                '<div style="color:#d63030; padding:16px;">데이터를 불러올 수 없습니다.</div>';
        });

    function statCard(color, icon, label, value, unit, sub) {
        return '<div class="stat_card">' +
            '<div class="stat_icon ' + color + '"><i class="fa-solid ' + icon + '"></i></div>' +
            '<p class="stat_label">' + label + '</p>' +
            '<p class="stat_value">' + Number(value).toLocaleString() + '<span>' + unit + '</span></p>' +
            '<p class="stat_diff today"><i class="fa-solid fa-plus"></i> ' + sub + '</p>' +
        '</div>';
    }

    function esc(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
}());
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_footer.php"); ?>
