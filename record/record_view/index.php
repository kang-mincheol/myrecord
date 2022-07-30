<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/record/record_view/index.css');
?>


<div id="page_title">마이레코드</div>

<?
include_once($_SERVER['DOCUMENT_ROOT'].'/component/sub_menu/record_sub_menu/record_sub_menu.php');
?>



<div id="view_wrap">

    <div class="status_row">
        <div class="status_box">
            
        </div>
    </div>

    <div class="nickname_row">
        <div class="nickname_box">닉네임 - <span class="value">-</span></div>
        <div class="create_date"><span class="title">등록일자 - </span><span class="value">-</span></div>
    </div>

    <div class="record_info_row">
        <div class="info_box type_box">
            <p class="value_box">Record 종목 - <span class="value">-</span></p>
        </div>
        <div class="info_box weight_box">
            <p class="value_box">Record 무게 - <span class="value">-</span>(kg)</p>
        </div>
    </div>

    <div class="file_row">
        
    </div>
    <!-- file_row -->
<!--
    <div class="file_wrap">
        <div class="file_row">
            <div class="row_title">첨부파일_1</div>
            <video controls class="file_video">
                <source src="/data/record/asdfasdfasdf.m4v" type="video/mp4">
            </video>
        </div>

        <div class="file_row">
            <div class="row_title">첨부파일_2</div>
            <img class="file_img" src="/data/record/fdsafdsa"/>
        </div>
    </div>
-->


</div>
<!-- view_wrap -->




<?
echo script_load('/record/record_view/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>