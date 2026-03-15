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
            <!-- on css -->
            <button class="bottom_btn edit_btn off" onclick="">수정</button>
            <!-- off css -->
        </div>

    </div>
    <!-- view_card -->

</div>
<!-- view_wrap -->


<?php
echo script_load('/community/free_board/view/index.js');
?>
<script>
window.addEventListener("DOMContentLoaded", () => {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
