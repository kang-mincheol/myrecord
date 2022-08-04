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
                <i class="fa-solid fa-circle-check"></i><p class="status_text">신청</p>
            </div>
        </div>
    </div>

    <div class="file_row">
        <div class="owl-carousel owl-theme file_slide_wrap">
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
        </div>
    </div>

    <a class="certificate_btn" href="">인증서 보기</a>


</div>
<!-- view_wrap -->




<?
echo script_load('/assets/owlcarousel/owl.carousel.min.js');
echo script_load('/record/record_view/index.js');
?>

<script>
$(function () {
    init();

    $('.owl-carousel').owlCarousel({
        loop: false,
        margin: 10,
        items: 1,
        center: true,
        autoHeight: true,
        nav: true
    })
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>