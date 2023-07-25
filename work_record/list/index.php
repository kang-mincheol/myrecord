<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/work_record/list/index.css');
?>

<div class="page_title_wrap">
    <p class="page_title">운동일지</p>
</div>

<div id="work_record_sub_menu_wrap">
    <a class="record_type_btn on" attr="all" href="/work_record/list/">전체</a>
    <a class="record_type_btn" attr="1" href="/work_record/list/?type=1">등</a>
    <a class="record_type_btn" attr="2" href="/work_record/list/?type=2">가슴</a>
    <a class="record_type_btn" attr="3" href="/work_record/list/?type=3">하체</a>
    <a class="record_type_btn" attr="4" href="/work_record/list/?type=4">어깨</a>
    <a class="record_type_btn" attr="5" href="/work_record/list/?type=5">복합</a>
</div>

<div id="work_reocrd_list_wrap">
    <a class="work_record_list_box">

    </a>
</div>
<!-- work_reocrd_list_wrap -->


<?
echo script_load('/work_record/list/index.js');
?>

<script>
$(function () {
    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>