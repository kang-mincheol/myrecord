<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/common/record_pages.css');
echo css_load('/record/squat/list/index.css');
?>


<div class="record_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">Squat</p>
        <p class="page_sub_text">스쿼트 기록 현황</p>
    </div>
</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/component/sub_menu/record_sub_menu/record_sub_menu.php');
?>

<div id="board_wrap">

    <div class="write_btn_wrap">
        <a class="write_btn" href="/record/record_regist/"><i class="fa-solid fa-plus"></i> 내 기록 등록</a>
    </div>

    <div class="board_container">
        <div class="board_header_wrap">
            <div class="header_box writer">닉네임</div>
            <div class="header_box weight">무게</div>
            <div class="header_box audit">심사</div>
            <div class="header_box date">신청일</div>
        </div>
        <div class="board_body_wrap"></div>
    </div>

</div>
<!-- board_wrap -->


<div class="paging_wrap">
    <button class="prev_btn" title="이전" onclick="prevPage();"><i class="fa-solid fa-angle-left"></i></button>
    <div class="paging_box"></div>
    <button class="next_btn" title="다음" onclick="nextPage();"><i class="fa-solid fa-angle-right"></i></button>
</div>
<!-- paging_wrap -->


<div class="board_search_wrap">
    <select id="search_key" class="search_select">
        <option value="nickname">닉네임</option>
        <option value="weight">무게</option>
    </select>
    <div class="search_keyword">
        <input id="search_keyword" type="text" placeholder="검색어를 입력해 주세요"/>
        <button class="search_btn" title="검색" onclick="recordSearch();"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>
</div>
<!-- board_search_wrap -->


<?php
echo script_load('/record/squat/list/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
