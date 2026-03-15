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


<div id="board_wrap">
    <div class="board_container">

        <div class="board_container_head">
            <div class="board_header_wrap">
                <div class="top_box">
                    <div class="header_box title">제목</div>
                </div>
                <div class="bottom_box">
                    <div class="header_box writer">작성자</div>
                    <div class="header_box view">조회수</div>
                    <div class="header_box date">작성일</div>
                </div>
            </div>
        </div>

        <div class="board_body_wrap">
            <!-- JS renders board_row items here -->
        </div>

    </div>
    <!-- board_container -->
</div>
<!-- board_wrap -->


<div id="pagingWrap" class="paging_wrap"></div>


<div class="write_btn_wrap">
    <a class="write_btn" href="/community/free_board/edit/">
        <i class="fa-solid fa-pen-to-square"></i> 글쓰기
    </a>
</div>


<div class="board_search_wrap">
    <select id="search_key" class="search_select">
        <option value="title">제목</option>
        <option value="contents">내용</option>
        <option value="writer">작성자</option>
    </select>
    <div class="search_keyword_wrap">
        <input id="search_keyword" type="text" placeholder="검색어를 입력하세요"/>
        <button class="search_btn" onclick="listSearch();" title="검색">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
</div>
<!-- board_search_wrap -->


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
