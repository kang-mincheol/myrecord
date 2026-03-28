<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");

$admin_page_title = '시스템 설정';
$purge_stats = AdminSystem::getPurgeStats();

include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<style>
/* ── 카드 본문 공통 패딩 ── */
.card_body {
    padding: 20px 20px 24px;
}

/* ── 통계 그리드 ── */
.purge_info_grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
.purge_info_item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #f8fafc;
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 14px 16px;
}
.purge_info_item .info_icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.purge_info_item .info_icon.red    { background: #fee2e2; color: #c0392b; }
.purge_info_item .info_icon.orange { background: #fff4e0; color: #c47a00; }
.purge_info_item .info_icon.blue   { background: #e8f0ff; color: #0123B4; }
.purge_info_item .info_label {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-secondary);
    letter-spacing: 0.2px;
    margin-bottom: 3px;
}
.purge_info_item .info_value {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
}
.purge_info_item .info_value span {
    font-size: 12px;
    font-weight: 400;
    color: var(--text-secondary);
    margin-left: 2px;
}

/* ── 안내 문구 ── */
.purge_desc {
    font-size: 13px;
    color: #7a5a00;
    background: #fffbe6;
    border: 1px solid #ffe58f;
    border-radius: 8px;
    padding: 11px 14px;
    margin-bottom: 20px;
    line-height: 1.75;
}
.purge_desc i { margin-right: 5px; color: #c47a00; }

/* ── 실행 버튼 ── */
.purge_btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #c0392b;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 22px;
    font-size: 13px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    transition: background .15s;
}
.purge_btn:hover:not(:disabled) { background: #a93226; }
.purge_btn:disabled { background: #cbd5e1; color: #94a3b8; cursor: not-allowed; }

/* ── 결과 메시지 ── */
.purge_result {
    display: none;
    margin-top: 14px;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 13px;
    line-height: 1.8;
}
.purge_result.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.purge_result.error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

@media (max-width: 768px) {
    .purge_info_grid { grid-template-columns: 1fr; }
}
</style>

<!-- 자유게시판 만료 게시글 영구 삭제 -->
<div class="admin_card" style="margin-bottom: 24px;">
    <div class="card_header">
        <p class="card_title"><i class="fa-solid fa-trash-can" style="color:#c0392b;margin-right:6px;"></i>자유게시판 만료 게시글 영구 삭제</p>
    </div>

    <div class="card_body">

        <div class="purge_info_grid">
            <div class="purge_info_item">
                <div class="info_icon red"><i class="fa-solid fa-file-lines"></i></div>
                <div>
                    <p class="info_label">삭제 대상 게시글</p>
                    <p class="info_value"><?= number_format($purge_stats['board_count']) ?><span>건</span></p>
                </div>
            </div>
            <div class="purge_info_item">
                <div class="info_icon orange"><i class="fa-solid fa-comments"></i></div>
                <div>
                    <p class="info_label">삭제 대상 댓글</p>
                    <p class="info_value"><?= number_format($purge_stats['comment_count']) ?><span>건</span></p>
                </div>
            </div>
            <div class="purge_info_item">
                <div class="info_icon blue"><i class="fa-solid fa-image"></i></div>
                <div>
                    <p class="info_label">삭제 대상 파일</p>
                    <p class="info_value"><?= number_format($purge_stats['file_count']) ?><span>건</span></p>
                </div>
            </div>
        </div>

        <div class="purge_desc">
            <i class="fa-solid fa-circle-info"></i>
            소프트 삭제된 지 <strong>1년 이상</strong> 경과한 게시글을 영구 삭제합니다.<br>
            게시글 · 댓글 · 파일 DB 및 서버에 저장된 이미지 파일이 <strong>모두 복구 불가하게 삭제</strong>됩니다.
        </div>

        <button class="purge_btn" id="purge_btn" onclick="runPurge();"
            <?= $purge_stats['board_count'] === 0 ? 'disabled' : '' ?>>
            <i class="fa-solid fa-trash-can"></i>
            영구 삭제 실행 (<?= number_format($purge_stats['board_count']) ?>건)
        </button>

        <div class="purge_result" id="purge_result"></div>

    </div>
    <!-- /card_body -->
</div>

<script>
function runPurge() {
    if (!confirm('삭제된 지 1년 이상 경과한 게시글을 영구 삭제합니다.\n이 작업은 되돌릴 수 없습니다.\n\n계속하시겠습니까?')) return;

    const $btn    = document.getElementById('purge_btn');
    const $result = document.getElementById('purge_result');

    $btn.disabled = true;
    $btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> 처리 중...';
    $result.style.display = 'none';

    fetch('/api/v1/admin/system/expired-boards', {
        method: 'DELETE',
    })
    .then(r => r.json())
    .then(res => {
        $result.style.display = 'block';
        if (res.code === 'SUCCESS') {
            const d = res.data;
            $result.className = 'purge_result success';
            $result.innerHTML =
                '<i class="fa-solid fa-circle-check"></i> <strong>영구 삭제 완료</strong><br>' +
                '게시글 ' + d.deleted_boards + '건 · ' +
                '댓글 ' + d.deleted_comments + '건 · ' +
                '파일 DB ' + d.deleted_files + '건 · ' +
                '물리 파일 ' + d.deleted_physical + '건 삭제되었습니다.';
            $btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> 영구 삭제 실행 (0건)';
        } else {
            $result.className = 'purge_result error';
            $result.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> ' + (res.msg || '오류가 발생했습니다.');
            $btn.disabled = false;
            $btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> 영구 삭제 실행';
        }
    })
    .catch(() => {
        $result.style.display = 'block';
        $result.className = 'purge_result error';
        $result.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> 서버 오류가 발생했습니다.';
        $btn.disabled = false;
        $btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> 영구 삭제 실행';
    });
}
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_footer.php"); ?>
