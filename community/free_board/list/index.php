<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/community/free_board/list/index.css');
?>


<div class="board_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">자유게시판</p>
        <p class="page_sub_text">자유롭게 이야기를 나눠보세요</p>
    </div>
</div>


<div class="free_board_wrap">

    <!-- Top bar: search + write btn -->
    <div class="board_top_bar">
        <div class="search_area">
            <select id="search_key" class="search_select">
                <option value="title">제목</option>
                <option value="contents">내용</option>
                <option value="writer">작성자</option>
            </select>
            <div class="search_input_wrap">
                <input id="search_keyword" type="text" placeholder="검색어를 입력하세요"/>
                <button class="search_btn" onclick="listSearch();" title="검색">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
        <a class="write_btn" href="/community/free_board/edit/">
            <i class="fa-solid fa-pen-to-square"></i> 글쓰기
        </a>
    </div>

    <!-- Board List -->
    <div id="board_wrap">
        <div class="board_container">

            <!-- Column headers -->
            <div class="board_head_row">
                <div class="head_col title">제목</div>
                <div class="head_col writer">작성자</div>
                <div class="head_col view">조회</div>
                <div class="head_col date">날짜</div>
            </div>

            <!-- JS renders board_row items here -->
            <div class="board_body_wrap"></div>

        </div>
    </div>

    <div id="pagingWrap" class="paging_wrap"></div>

</div>
<!-- free_board_wrap -->


<?php
echo script_load('/community/free_board/list/index.js');
?>
<script>
const listInfo = {
    pageIndex: 1,
    pageRow: 10,
};

window.addEventListener("DOMContentLoaded", () => {
    pageInit();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
