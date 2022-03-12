<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/community/free_board/edit/index.css');
?>


<div id="page_title">커뮤니티 ＞ 자유게시판</div>


<div id="editor_wrap">
    
</div>
<!-- editor_wrap -->
















<?
echo script_load('/community/free_board/edit/index.js');
?>
<script>
$(function () {
    
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>