<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/community/free_board/view/index.css');
?>


<div id="page_title">커뮤니티 - 자유게시판</div>


<div id="view_wrap">

    <div id="view_header_wrap">
        <div id="contents_title">제목</div>
        <div class="view_header_info_box">
            <p class="info_value writer_value">작성자</p>
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