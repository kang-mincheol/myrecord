<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/record/landing/index.css');
?>

<div id="page_title">마이레코드</div>

<?
include_once($_SERVER['DOCUMENT_ROOT'].'/record/record_sub_menu/record_sub_menu.php');
?>

<div id="record_ranking">
    <div class="ranking_header">
        <div class="header_box rank">순위</div>
        <div class="header_box squat">Squat</div>
        <div class="header_box benchpress">BenchPress</div>
        <div class="header_box deadlift">DeadLift</div>
        <div class="header_box name">닉네임</div>
    </div>

    <div class="ranking_body">
        <div class="body_row">
            <div class="body_box rank">1</div>
            <div class="body_box squat">180</div>
            <div class="body_box benchpress">100</div>
            <div class="body_box deadlift">160</div>
            <div class="body_box name">관리자</div>
        </div>
    </div>
</div>
<!-- record_ranking -->















<?
echo script_load('/record/landing/index.js');
?>
<script>
$(function () {
    
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>