<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/assets/owlcarousel/owl.carousel.min.css');
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
        <div class="left_box">
            <div class="info_box type_box">
                <p class="value_box">Record 종목 - <span class="value">-</span></p>
            </div>
            <div class="info_box weight_box">
                <p class="value_box">Record 무게 - <span class="value">-</span>(kg)</p>
            </div>
        </div>
        <div class="right_box">
            <div class="info_box status_box">
                <p class="value_box">
                    <i class="fa-solid fa-circle-check"></i><span class="value">-</span>
                </p>
            </div>
        </div>
    </div>

    <div class="file_row">
        <div class="owl-carousel file_slide_wrap">
<!--
            <div class="item">
                <video controls class="file_video">
                    <source src="/data/record/asdfasdfasdf.m4v" type="video/mp4">
                </video>
            </div>
            <div class="item">
                <img class="file_img" src="/data/record/fdsafdsa"/>
            </div>
            <div class="item">
                <video controls class="file_video">
                    <source src="/data/record/asdfasdfasdf.m4v" type="video/mp4">
                </video>
            </div>
            <div class="item">
                <img class="file_img" src="/data/record/fdsafdsa"/>
            </div>
-->
        </div>
    </div>

    <div class="bottom_btn_wrap">
        <div class="left_btn_wrap">
            <button class="edit_btn prev" onclick="prev();">이전</button>
        </div>
        <div class="right_btn_wrap">
            <button class="edit_btn delete" onclick="">삭제</button>
            <a class="edit_btn edit" href="">수정</a>
        </div>
    </div>

</div>
<!-- view_wrap -->

<a id="certificate_save" class="certificate_save" href="/">
    마이레코드 인증서 보기&nbsp;<i class="fa-solid fa-file-arrow-down"></i>
</a>




<?
echo script_load('/assets/owlcarousel/owl.carousel.min.js');
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