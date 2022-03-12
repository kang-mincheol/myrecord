<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/community/free_board/list/index.css');
?>


<div id="page_title">커뮤니티 ＞ 자유게시판</div>


<div id="board_wrap">
    <div class="board_container">
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

        <div class="board_body_wrap">
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
            <div class="board_row">
                <div class="top_box">
                    <div class="body_box title">제목입니다</div>
                </div>
                <div class="bottom_box">
                    <div class="body_box writer">구스만</div>
                    <div class="body_box view">1</div>
                    <div class="body_box date">2022.03.04</div>
                </div>
            </div>
        </div>
        <!-- board_body_wrap -->
    </div>
    <!-- board_container -->
</div>
<!-- board_wrap -->


<div class="paging_wrap">
    <button class="prev_btn" title="이전"><i class="fa-solid fa-angle-left"></i></button>
    <div class="paging_box">
        <button class="page_btn">1</button>
        <button class="page_btn">2</button>
        <button class="page_btn">3</button>
        <button class="page_btn">4</button>
        <button class="page_btn">5</button>
        <button class="page_btn">6</button>
        <button class="page_btn">7</button>
        <button class="page_btn">8</button>
        <button class="page_btn">9</button>
        <button class="page_btn">10</button>
    </div>
    <button class="next_btn" title="다음"><i class="fa-solid fa-angle-right"></i></button>
</div>
<!-- paging_wrap -->


<div class="board_search_wrap">
    <select id="search_key" class="search_select">
        <option value="title">제목</option>
        <option value="contents">내용</option>
        <option value="writer">작성자</option>
    </select>

    <div class="search_keyword">
        <input id="search_keyword" type="text"/>
        <button class="search_btn" title="검색"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
</div>
<!-- board_search_wrap -->

















<?
echo script_load('/community/free_board/list/index.js');
?>
<script>
$(function () {
    
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>