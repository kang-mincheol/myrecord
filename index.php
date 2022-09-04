<?
include_once('common.php');   // 기본파일 로드
include_once('header.php');   // 헤더파일 로드
?>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<?
echo css_load('/common/index.css');
?>


<div id="slide_wrap">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">Slide 1</div>
            <div class="swiper-slide">Slide 2</div>
            <div class="swiper-slide">Slide 3</div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</div>
<!-- slide_wrap -->





<!--
<div id="free_board_wrap">
    <div class="board_wrap">
        <div class="board_title_wrap">
            <p class="board_title"><a href="/">자유게시판</a></p>
        </div>
        <div class="board_body_wrap">
            <div class="body_row">
                <p class="contents_title"><a href="/">안녕하세요 자유게시판 입니다</a></p>
                <p class="contents_date">2022-03-01</p>
            </div>
        </div>
    </div>
</div>
-->
<!-- free_board_wrap -->





<!--
<div id="myrecord_board_wrap">
    <div class="board_wrap">
        <div class="board_title_wrap">
            <p class="board_title"><a href="/">마이레코드 RANKING</a></p>
        </div>
        <div class="board_body_wrap">
            <div class="rank_wrap">
                <div class="rank_title_box">
                    <p class="rank_title"><a href="/">3대 TOTAL</a></p>
                </div>
                <div class="rank_list_wrap">
                    <div class="rank_list_row">
                        <p class="rank_number">1</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                    <div class="rank_list_row">
                        <p class="rank_number">2</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                    <div class="rank_list_row">
                        <p class="rank_number">3</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                </div>
            </div>

            <div class="rank_wrap">
                <div class="rank_title_box">
                    <p class="rank_title"><a href="/">Squat</a></p>
                </div>
                <div class="rank_list_wrap">
                    <div class="rank_list_row">
                        <p class="rank_number">1</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                    <div class="rank_list_row">
                        <p class="rank_number">2</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                    <div class="rank_list_row">
                        <p class="rank_number">3</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                </div>
            </div>

            <div class="rank_wrap">
                <div class="rank_title_box">
                    <p class="rank_title"><a href="/">Bench Press</a></p>
                </div>
                <div class="rank_list_wrap">
                    <div class="rank_list_row">
                        <p class="rank_number">1</p>
                        <p class="rank_user_name">asdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdf</p>
                    </div>
                    <div class="rank_list_row">
                        <p class="rank_number">2</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                    <div class="rank_list_row">
                        <p class="rank_number">3</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                </div>
            </div>

            <div class="rank_wrap">
                <div class="rank_title_box">
                    <p class="rank_title"><a href="/">Deadlift</a></p>
                </div>
                <div class="rank_list_wrap">
                    <div class="rank_list_row">
                        <p class="rank_number">1</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                    <div class="rank_list_row">
                        <p class="rank_number">2</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                    <div class="rank_list_row">
                        <p class="rank_number">3</p>
                        <p class="rank_user_name">asdf</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
-->
<!-- myrecord_board_wrap -->




<div id="ad_wrap_1">
    <div class="ad_wrap"></div>
</div>








<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<?
echo script_load('/common/index.js');
?>
<script>
$(function () {
    slideRender();
});
</script>
<?
include_once('footer.php');   // 푸터파일 로드
?>