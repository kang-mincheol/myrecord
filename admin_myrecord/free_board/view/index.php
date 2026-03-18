<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: /admin_myrecord/free_board/");
    exit;
}

$post = AdminFreeBoard::getDetail($id);
if (!$post) {
    header("Location: /admin_myrecord/free_board/");
    exit;
}

$comments = AdminFreeBoard::getComments($id);

// 목록으로 돌아갈 URL (필터/페이지 보존)
$list_params = http_build_query(array_filter([
    'status'     => $_GET['status']     ?? '',
    'search_key' => $_GET['search_key'] ?? '',
    'search_val' => $_GET['search_val'] ?? '',
    'page'       => $_GET['page']       ?? '',
]));
$back_url = '/admin_myrecord/free_board/' . ($list_params ? '?' . $list_params : '');

$admin_page_title = '게시글 상세';

$admin_extra_css = '<style>
/* 뒤로가기 */
.back_btn {
    display: inline-flex; align-items: center; gap: 7px;
    height: 36px; padding: 0 16px; border-radius: 8px;
    background: #f1f5f9; color: var(--text-secondary);
    font-size: 13px; font-weight: 600; text-decoration: none;
    border: 1.5px solid var(--border-color); transition: all 0.15s;
    margin-bottom: 20px;
}
.back_btn:hover { background: #e2e8f0; color: var(--text-primary); border-color: #c8d0e0; }
.view_link_btn {
    display: inline-flex; align-items: center; gap: 7px;
    height: 36px; padding: 0 16px; border-radius: 8px;
    background: #f1f5f9; color: var(--text-secondary);
    font-size: 13px; font-weight: 600; text-decoration: none;
    border: 1.5px solid var(--border-color); transition: all 0.15s;
    margin-bottom: 20px;
}
.view_link_btn:hover { background: #e8f0ff; color: var(--accent); border-color: var(--accent); }
.top_btn_wrap { display: flex; gap: 8px; }

/* 카드 섹션 */
.view_section { margin-bottom: 20px; }
.view_section_title {
    font-size: 11px; font-weight: 700; color: var(--text-secondary);
    letter-spacing: 0.6px; text-transform: uppercase;
    margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border-color);
}

/* 기본 정보 그리드 */
.view_info_grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px 24px; }
.view_info_item { display: flex; flex-direction: column; gap: 4px; }
.view_info_label { font-size: 11px; font-weight: 600; color: var(--text-secondary); }
.view_info_val { font-size: 13px; font-weight: 500; color: var(--text-primary); }

/* 본문 */
.post_title {
    font-size: 18px; font-weight: 700; color: var(--text-primary);
    line-height: 1.4; margin-bottom: 16px;
}
.post_contents {
    font-size: 14px; color: var(--text-primary); line-height: 1.8;
    background: #f8fafc; border-radius: 10px; padding: 18px 20px;
    border: 1px solid var(--border-color); word-break: break-word;
}
.post_contents img { max-width: 100%; border-radius: 6px; }

/* 댓글 */
.comment_list { display: flex; flex-direction: column; gap: 10px; }
.comment_item {
    background: #f8fafc; border: 1px solid var(--border-color);
    border-radius: 10px; padding: 12px 16px;
    display: flex; justify-content: space-between; align-items: flex-start; gap: 14px;
}
.comment_item.deleted { opacity: 0.45; }
.comment_left { flex: 1; min-width: 0; }
.comment_writer {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;
}
.comment_writer i { font-size: 10px; color: var(--accent); }
.comment_text { font-size: 13px; color: var(--text-secondary); line-height: 1.6; word-break: break-word; }
.comment_date { font-size: 11px; color: #bbb; margin-top: 6px; }
.comment_del_btn {
    flex-shrink: 0; height: 28px; padding: 0 12px;
    background: #fee2e2; color: #c0392b; border: none; border-radius: 6px;
    font-size: 11px; font-weight: 700; cursor: pointer; transition: background 0.15s;
    display: inline-flex; align-items: center; gap: 5px;
}
.comment_del_btn:hover { background: #fca5a5; }
.no_comment { font-size: 13px; color: #c0c4d4; padding: 10px 0; }

/* 관리 액션 */
.action_wrap { display: flex; gap: 10px; }
.action_btn {
    flex: 1; height: 46px; border-radius: 10px; font-size: 14px; font-weight: 700;
    border: none; cursor: pointer; display: flex; align-items: center;
    justify-content: center; gap: 8px; transition: background 0.15s; max-width: 240px;
}
.action_btn.del     { background: #fee2e2; color: #c0392b; }
.action_btn.del:hover     { background: #fca5a5; }
.action_btn.restore { background: #e6f7ee; color: #1a8a4a; }
.action_btn.restore:hover { background: #bbf7d0; }
</style>';

include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<div class="top_btn_wrap">
    <a href="<?= htmlspecialchars($back_url) ?>" class="back_btn">
        <i class="fa-solid fa-chevron-left"></i> 목록으로
    </a>
    <a href="/community/free_board/view/?id=<?= $id ?>" target="_blank" class="view_link_btn">
        <i class="fa-solid fa-arrow-up-right-from-square"></i> 글 보기
    </a>
</div>

<!-- ===== 기본 정보 ===== -->
<div class="admin_card" style="margin-bottom:20px;">
    <div style="padding:20px 24px;">
        <div class="view_section">
            <p class="view_section_title">기본 정보</p>
            <div class="view_info_grid">
                <div class="view_info_item">
                    <span class="view_info_label">글 번호</span>
                    <span class="view_info_val">#<?= $post['id'] ?></span>
                </div>
                <div class="view_info_item">
                    <span class="view_info_label">작성자</span>
                    <span class="view_info_val"><?= htmlspecialchars($post['user_nickname'] ?? '-') ?></span>
                </div>
                <div class="view_info_item">
                    <span class="view_info_label">조회수</span>
                    <span class="view_info_val"><?= number_format($post['view_count']) ?></span>
                </div>
                <div class="view_info_item">
                    <span class="view_info_label">작성일</span>
                    <span class="view_info_val"><?= htmlspecialchars($post['create_date'] ?? '-') ?></span>
                </div>
                <div class="view_info_item">
                    <span class="view_info_label">수정일</span>
                    <span class="view_info_val"><?= $post['update_date'] ? htmlspecialchars($post['update_date']) : '-' ?></span>
                </div>
                <div class="view_info_item">
                    <span class="view_info_label">상태</span>
                    <span class="view_info_val">
                        <?php if ($post['is_delete']): ?>
                            <span class="admin_badge reject">삭제됨</span>
                        <?php else: ?>
                            <span class="admin_badge approval">정상</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== 게시글 본문 ===== -->
<div class="admin_card" style="margin-bottom:20px;">
    <div style="padding:20px 24px;">
        <div class="view_section">
            <p class="view_section_title">게시글 내용</p>
            <p class="post_title"><?= htmlspecialchars($post['title']) ?></p>
            <div class="post_contents"><?= stripslashes($post['contents']) ?></div>
        </div>
    </div>
</div>

<!-- ===== 댓글 ===== -->
<div class="admin_card" style="margin-bottom:20px;">
    <div style="padding:20px 24px;">
        <div class="view_section" style="margin-bottom:0;">
            <p class="view_section_title">댓글 (<?= count($comments) ?>)</p>
            <?php if (empty($comments)): ?>
                <p class="no_comment">댓글이 없습니다.</p>
            <?php else: ?>
            <div class="comment_list">
                <?php foreach ($comments as $c): ?>
                <div class="comment_item <?= $c['is_delete'] ? 'deleted' : '' ?>" id="cmt_<?= $c['id'] ?>">
                    <div class="comment_left">
                        <p class="comment_writer">
                            <i class="fa-solid fa-user"></i>
                            <?= htmlspecialchars($c['user_nickname'] ?? '-') ?>
                            <?php if ($c['is_delete']): ?>
                                <span style="font-size:10px; color:#aaa; font-weight:400;">(삭제됨)</span>
                            <?php endif; ?>
                        </p>
                        <p class="comment_text"><?= nl2br(htmlspecialchars($c['contents'])) ?></p>
                        <p class="comment_date"><?= htmlspecialchars($c['create_datetime'] ?? '') ?></p>
                    </div>
                    <?php if (!$c['is_delete']): ?>
                    <button class="comment_del_btn" onclick="deleteComment(<?= $c['id'] ?>, this)">
                        <i class="fa-solid fa-trash"></i> 삭제
                    </button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ===== 관리 액션 ===== -->
<div class="admin_card">
    <div style="padding:20px 24px;">
        <div class="view_section" style="margin-bottom:0;">
            <p class="view_section_title">관리</p>
            <div class="action_wrap">
                <?php if ($post['is_delete']): ?>
                <button class="action_btn restore" onclick="postAction('restore_post')">
                    <i class="fa-solid fa-rotate-left"></i> 게시글 복원
                </button>
                <?php else: ?>
                <button class="action_btn del" onclick="postAction('delete_post')">
                    <i class="fa-solid fa-trash"></i> 게시글 삭제
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
var postId = <?= $id ?>;

function postAction(action) {
    var label = action === 'delete_post' ? '삭제' : '복원';
    if (!confirm('이 게시글을 ' + label + '하시겠습니까?')) return;

    $.ajax({
        type: 'POST',
        url: '/admin_myrecord/free_board/set_action.php',
        contentType: 'application/json',
        data: JSON.stringify({ action: action, id: postId }),
        success: function(res) {
            if (res.code === 'SUCCESS') {
                location.reload();
            } else {
                alert(res.msg || '처리에 실패했습니다.');
            }
        },
        error: function() { alert('서버 오류가 발생했습니다.'); }
    });
}

function deleteComment(id, btn) {
    if (!confirm('이 댓글을 삭제하시겠습니까?')) return;

    $.ajax({
        type: 'POST',
        url: '/admin_myrecord/free_board/set_action.php',
        contentType: 'application/json',
        data: JSON.stringify({ action: 'delete_comment', id: id }),
        success: function(res) {
            if (res.code === 'SUCCESS') {
                var item = document.getElementById('cmt_' + id);
                if (item) {
                    item.classList.add('deleted');
                    btn.remove();
                    var writer = item.querySelector('.comment_writer');
                    if (writer) {
                        writer.insertAdjacentHTML('beforeend', '<span style="font-size:10px; color:#aaa; font-weight:400;">(삭제됨)</span>');
                    }
                }
            } else {
                alert(res.msg || '삭제에 실패했습니다.');
            }
        },
        error: function() { alert('서버 오류가 발생했습니다.'); }
    });
}
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_footer.php");
?>
