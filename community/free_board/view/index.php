<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/community/free_board/view/index.css');
?>


<div class="board_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">자유게시판</p>
        <p class="page_sub_text">자유롭게 이야기를 나눠보세요</p>
    </div>
</div>


<div id="view_wrap">

    <div class="view_card">

        <div id="view_header_wrap">
            <div id="contents_title">제목</div>
            <div class="view_meta_row">
                <p class="info_value writer_value">작성자</p>
                <span class="meta_divider">·</span>
                <p class="info_value write_date">0000.00.00</p>
            </div>
        </div>

        <div id="view_contents_wrap">내용</div>

        <div class="bottom_btn_wrap">
            <button class="bottom_btn list_btn on" onclick="goFreeBoardList();">목록</button>
            <button class="bottom_btn edit_btn off" onclick="">수정</button>
            <button class="bottom_btn delete_btn off" onclick="">삭제</button>
        </div>

    </div>
    <!-- view_card -->


    <!-- 댓글 -->
    <div class="comment_card">

        <div class="comment_card_head">
            <p class="comment_title"><i class="fa-regular fa-comment-dots"></i> 댓글 <span id="comment_count">0</span></p>
        </div>

        <!-- 댓글 목록 -->
        <div id="comment_list"></div>

        <!-- 댓글 입력 -->
        <div class="comment_input_wrap" id="comment_input_wrap" style="display:none;">
            <textarea id="comment_textarea" class="comment_textarea" placeholder="댓글을 입력해주세요 (최대 500자)" maxlength="500" oninput="commentInputCount(this);"></textarea>
            <div class="comment_input_bottom">
                <span class="comment_char_count"><span id="comment_input_count">0</span> / 500</span>
                <button class="comment_submit_btn" onclick="submitComment();"><i class="fa-solid fa-paper-plane"></i> 등록</button>
            </div>
        </div>
        <div class="comment_login_notice" id="comment_login_notice" style="display:none;">
            <i class="fa-solid fa-lock"></i>
            <p>댓글은 <a href="/account/login/">로그인</a> 후 작성할 수 있습니다.</p>
        </div>

    </div>
    <!-- comment_card -->

</div>
<!-- view_wrap -->


<?php
echo script_load('/community/free_board/view/index.js');
?>
<script>
var IS_MEMBER = <?= json_encode($is_member) ?>;

window.addEventListener("DOMContentLoaded", function () {
    // 댓글 입력 영역 / 비로그인 안내 표시
    if (IS_MEMBER) {
        document.getElementById('comment_input_wrap').style.display = '';
    } else {
        document.getElementById('comment_login_notice').style.display = '';
    }
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
