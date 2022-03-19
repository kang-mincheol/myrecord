<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/community/free_board/view/index.css');
?>


<div id="page_title">커뮤니티 ＞ 자유게시판</div>


<div id="view_wrap">

    <div id="view_header_wrap">
        <div id="contents_title">제목입니다</div>
        <div class="view_header_info_box">
            <p class="info_value writer_value">작성자임</p>
            <p class="info_value write_date">2022.03.13</p>
        </div>
    </div>

    <div id="view_contents_wrap">내용</div>

    <div class="bottom_btn_wrap">
        <button class="bottom_btn list_btn">목록</button>
        <!-- on css -->
        <button class="bottom_btn edit_btn">수정</button>
        <!-- off css -->
    </div>

</div>









<?
echo script_load('/community/free_board/view/index.js');
?>
<script>
$(function () {
    
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>