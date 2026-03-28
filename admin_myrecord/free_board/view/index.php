<?php
include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/common.php");
$admin_page_title = '게시글 상세';

$admin_extra_css = '<style>
.back_btn {
    display: inline-flex; align-items: center; gap: 7px;
    height: 36px; padding: 0 16px; border-radius: 8px;
    background: #f1f5f9; color: var(--text-secondary);
    font-size: 13px; font-weight: 600; text-decoration: none;
    border: 1.5px solid var(--border-color); transition: all 0.15s; margin-bottom: 20px;
}
.back_btn:hover { background: #e2e8f0; color: var(--text-primary); border-color: #c8d0e0; }
.view_link_btn {
    display: inline-flex; align-items: center; gap: 7px;
    height: 36px; padding: 0 16px; border-radius: 8px;
    background: #f1f5f9; color: var(--text-secondary);
    font-size: 13px; font-weight: 600; text-decoration: none;
    border: 1.5px solid var(--border-color); transition: all 0.15s; margin-bottom: 20px;
}
.view_link_btn:hover { background: #e8f0ff; color: var(--accent); border-color: var(--accent); }
.top_btn_wrap { display: flex; gap: 8px; }
.view_section { margin-bottom: 20px; }
.view_section_title {
    font-size: 11px; font-weight: 700; color: var(--text-secondary);
    letter-spacing: 0.6px; text-transform: uppercase;
    margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border-color);
}
.view_info_grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px 24px; }
.view_info_item { display: flex; flex-direction: column; gap: 4px; }
.view_info_label { font-size: 11px; font-weight: 600; color: var(--text-secondary); }
.view_info_val { font-size: 13px; font-weight: 500; color: var(--text-primary); }
.post_title { font-size: 18px; font-weight: 700; color: var(--text-primary); line-height: 1.4; margin-bottom: 16px; }
.post_contents {
    font-size: 14px; color: var(--text-primary); line-height: 1.8;
    background: #f8fafc; border-radius: 10px; padding: 18px 20px;
    border: 1px solid var(--border-color); word-break: break-word;
}
.post_contents img { max-width: 100%; border-radius: 6px; }
.comment_list { display: flex; flex-direction: column; gap: 10px; }
.comment_item {
    background: #f8fafc; border: 1px solid var(--border-color);
    border-radius: 10px; padding: 12px 16px;
    display: flex; justify-content: space-between; align-items: flex-start; gap: 14px;
}
.comment_item.deleted { opacity: 0.45; }
.comment_left { flex: 1; min-width: 0; }
.comment_writer { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
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
.action_wrap { display: flex; gap: 10px; }
.action_btn {
    flex: 1; height: 46px; border-radius: 10px; font-size: 14px; font-weight: 700;
    border: none; cursor: pointer; display: flex; align-items: center;
    justify-content: center; gap: 8px; transition: background 0.15s; max-width: 240px; font-family: inherit;
}
.action_btn.del     { background: #fee2e2; color: #c0392b; }
.action_btn.del:hover     { background: #fca5a5; }
.action_btn.restore { background: #e6f7ee; color: #1a8a4a; }
.action_btn.restore:hover { background: #bbf7d0; }
.page_loading { display: flex; align-items: center; justify-content: center; padding: 80px 0; color: var(--text-secondary); gap: 12px; }
.page_loading i { font-size: 22px; color: var(--accent); animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>';

include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_header.php");
?>

<div id="page_root">
    <div class="page_loading"><i class="fa-solid fa-spinner"></i> 불러오는 중...</div>
</div>

<script>
var postId = parseInt(new URLSearchParams(location.search).get('id') || '0');
var backParams = (function () {
    var sp = new URLSearchParams(location.search);
    var p  = {};
    ['status','search_key','search_val','page'].forEach(function (k) { if (sp.get(k)) p[k] = sp.get(k); });
    return new URLSearchParams(p).toString();
}());
var backUrl = '/admin_myrecord/free_board/' + (backParams ? '?' + backParams : '');

if (!postId) {
    location.href = '/admin_myrecord/free_board/';
}

fetch('/api/v1/admin/boards/' + postId)
    .then(function (r) { return r.json(); })
    .then(function (res) {
        if (res.code === 'NOT_FOUND' || res.code === 'INVALID') {
            location.href = '/admin_myrecord/free_board/';
            return;
        }
        if (res.code !== 'SUCCESS') {
            document.getElementById('page_root').innerHTML =
                '<p style="color:#d63030;padding:20px;">' + esc(res.msg || '오류가 발생했습니다.') + '</p>';
            return;
        }
        renderPage(res.post, res.comments);
    })
    .catch(function () {
        document.getElementById('page_root').innerHTML =
            '<p style="color:#d63030;padding:20px;">서버 오류가 발생했습니다.</p>';
    });

function renderPage(post, comments) {
    var isDelete = post.is_delete;

    // Top buttons
    var html = '<div class="top_btn_wrap">' +
        '<a href="' + esc(backUrl) + '" class="back_btn"><i class="fa-solid fa-chevron-left"></i> 목록으로</a>' +
        '<a href="/community/free_board/view/?id=' + post.id + '" target="_blank" class="view_link_btn"><i class="fa-solid fa-arrow-up-right-from-square"></i> 글 보기</a>' +
    '</div>';

    // Info card
    html += '<div class="admin_card" style="margin-bottom:20px;"><div style="padding:20px 24px;">' +
        '<div class="view_section"><p class="view_section_title">기본 정보</p>' +
        '<div class="view_info_grid">' +
            infoItem('글 번호', '#' + post.id) +
            infoItem('작성자', post.user_nickname || '-') +
            infoItem('조회수', Number(post.view_count).toLocaleString()) +
            infoItem('작성일', post.create_date || '-') +
            infoItem('수정일', post.update_date || '-') +
            infoItem('상태', isDelete ? '<span class="admin_badge reject">삭제됨</span>' : '<span class="admin_badge approval">정상</span>') +
        '</div></div>' +
    '</div></div>';

    // Content card
    html += '<div class="admin_card" style="margin-bottom:20px;"><div style="padding:20px 24px;">' +
        '<div class="view_section"><p class="view_section_title">게시글 내용</p>' +
        '<p class="post_title">' + esc(post.title) + '</p>' +
        '<div class="post_contents">' + (post.contents || '') + '</div>' +
        '</div></div></div>';

    // Comments card
    html += '<div class="admin_card" style="margin-bottom:20px;"><div style="padding:20px 24px;">' +
        '<div class="view_section" style="margin-bottom:0;">' +
        '<p class="view_section_title">댓글 (' + (comments ? comments.length : 0) + ')</p>';

    if (!comments || !comments.length) {
        html += '<p class="no_comment">댓글이 없습니다.</p>';
    } else {
        html += '<div class="comment_list">';
        comments.forEach(function (c) {
            html += '<div class="comment_item ' + (c.is_delete ? 'deleted' : '') + '" id="cmt_' + c.id + '">' +
                '<div class="comment_left">' +
                    '<p class="comment_writer"><i class="fa-solid fa-user"></i>' + esc(c.user_nickname || '-') +
                        (c.is_delete ? '<span style="font-size:10px;color:#aaa;font-weight:400;">(삭제됨)</span>' : '') +
                    '</p>' +
                    '<p class="comment_text">' + esc(c.contents || '').replace(/\n/g,'<br>') + '</p>' +
                    '<p class="comment_date">' + esc(c.create_datetime || '') + '</p>' +
                '</div>';
            if (!c.is_delete) {
                html += '<button class="comment_del_btn" onclick="deleteComment(' + c.id + ', this)"><i class="fa-solid fa-trash"></i> 삭제</button>';
            }
            html += '</div>';
        });
        html += '</div>';
    }
    html += '</div></div></div>';

    // Action card
    html += '<div class="admin_card"><div style="padding:20px 24px;">' +
        '<div class="view_section" style="margin-bottom:0;"><p class="view_section_title">관리</p>' +
        '<div class="action_wrap">';
    if (isDelete) {
        html += '<button class="action_btn restore" onclick="postAction(\'restore_post\')"><i class="fa-solid fa-rotate-left"></i> 게시글 복원</button>';
    } else {
        html += '<button class="action_btn del" onclick="postAction(\'delete_post\')"><i class="fa-solid fa-trash"></i> 게시글 삭제</button>';
    }
    html += '</div></div></div></div>';

    document.getElementById('page_root').innerHTML = html;
}

function infoItem(label, value) {
    return '<div class="view_info_item"><span class="view_info_label">' + label + '</span><span class="view_info_val">' + value + '</span></div>';
}

function postAction(action) {
    var label = action === 'delete_post' ? '삭제' : '복원';
    if (!confirm('이 게시글을 ' + label + '하시겠습니까?')) return;

    var isDelete = action === 'delete_post';
    $.ajax({
        type: isDelete ? 'DELETE' : 'POST',
        url:  isDelete ? '/api/v1/admin/boards/' + postId : '/api/v1/admin/boards/' + postId + '/restore',
        success: function (res) {
            if (res.code === 'SUCCESS') { location.reload(); }
            else { alert(res.msg || '처리에 실패했습니다.'); }
        },
        error: function () { alert('서버 오류가 발생했습니다.'); }
    });
}

function deleteComment(id, btn) {
    if (!confirm('이 댓글을 삭제하시겠습니까?')) return;

    $.ajax({
        type: 'DELETE',
        url:  '/api/v1/admin/boards/' + postId + '/comments/' + id,
        success: function (res) {
            if (res.code === 'SUCCESS') {
                var item = document.getElementById('cmt_' + id);
                if (item) {
                    item.classList.add('deleted');
                    btn.remove();
                    var writer = item.querySelector('.comment_writer');
                    if (writer) {
                        writer.insertAdjacentHTML('beforeend', '<span style="font-size:10px;color:#aaa;font-weight:400;">(삭제됨)</span>');
                    }
                }
            } else {
                alert(res.msg || '삭제에 실패했습니다.');
            }
        },
        error: function () { alert('서버 오류가 발생했습니다.'); }
    });
}

function esc(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/admin_myrecord/admin_footer.php"); ?>
