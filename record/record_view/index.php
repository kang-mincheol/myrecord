<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/assets/owlcarousel/owl.carousel.min.css');
echo css_load('/common/record_pages.css');
echo css_load('/record/record_view/index.css');
?>


<div class="record_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">기록 상세</p>
        <p class="page_sub_text">등록된 기록과 인증 파일을 확인할 수 있습니다</p>
    </div>
</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/component/sub_menu/record_sub_menu/record_sub_menu.php');
?>


<div id="view_wrap">
    <div class="view_card">

        <div class="view_meta_row">
            <div class="view_nickname" id="view_nickname">-</div>
            <div class="view_meta_right">
                <div class="view_date" id="view_date">-</div>
                <div class="view_status_badge" id="view_status_badge">-</div>
            </div>
        </div>

        <div class="view_stats_row">
            <div class="stat_card">
                <p class="stat_label">종목</p>
                <p class="stat_value" id="view_record_name">-</p>
            </div>
            <div class="stat_card">
                <p class="stat_label">무게</p>
                <p class="stat_value" id="view_record_weight">-</p>
            </div>
        </div>

        <div class="view_memo_wrap" id="view_memo_wrap" style="display:none;">
            <div class="memo_label"><i class="fa-regular fa-comment-dots"></i> 한마디</div>
            <div class="memo_text" id="view_memo_text"></div>
        </div>

        <div class="view_file_wrap">
            <div class="owl-carousel file_slide_wrap"></div>
        </div>

        <div class="certificate_wrap" id="certificate_wrap" style="display:none;">
            <a class="certificate_btn" id="certificate_save" href="/">
                <i class="fa-solid fa-award"></i> 마이레코드 인증서 보기
            </a>
        </div>

        <div class="view_bottom_wrap">
            <button class="prev_btn" onclick="prev();">
                <i class="fa-solid fa-angle-left"></i> 이전
            </button>
            <div class="right_btn_wrap" id="right_btn_wrap" style="display:none;">
                <button class="delete_btn" id="delete_btn">
                    <i class="fa-regular fa-trash-can"></i> 삭제
                </button>
            </div>
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
        <?php if ($is_member): ?>
        <div class="comment_input_wrap">
            <textarea id="comment_textarea" class="comment_textarea" placeholder="댓글을 입력해주세요 (최대 500자)" maxlength="500" oninput="commentInputCount(this);"></textarea>
            <div class="comment_input_bottom">
                <span class="comment_char_count"><span id="comment_input_count">0</span> / 500</span>
                <button class="comment_submit_btn" onclick="submitComment();"><i class="fa-solid fa-paper-plane"></i> 등록</button>
            </div>
        </div>
        <?php else: ?>
        <div class="comment_login_notice">
            <i class="fa-regular fa-lock"></i>
            <p>댓글은 <a href="/account/login/">로그인</a> 후 작성할 수 있습니다.</p>
        </div>
        <?php endif; ?>

    </div>
    <!-- comment_card -->

</div>
<!-- view_wrap -->


<?php
echo script_load('/assets/owlcarousel/owl.carousel.min.js');
echo script_load('/record/record_view/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
