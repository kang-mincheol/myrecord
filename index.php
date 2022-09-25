<?
include_once('common.php');   // 기본파일 로드
include_once('header.php');   // 헤더파일 로드
?>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<?
echo css_load('/common/index.css');
?>




<div id="section_1">
    <div class="bg_layer">
        <div class="section_text_wrap">
            <p class="text_1">
                스트롱맨 시청자 출신</br>
                관리자가 직접 검증하는</br>
                3대측정
            </p>
            <a class="record_btn" href="/record/squat/list/">
                3대 레코드 등록하기<i class="fa-solid fa-circle-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
<!-- section_1 -->





<div id="section_2">
    <div class="section_wrap">
        <div class="article_box" name="box_1">
            <p class="box_title">Step 1</p>
            <p class="box_text">
                본인의 기록을 영상으로 기록합니다
            </p>
        </div>
        <div class="article_box" name="box_2">
            <p class="box_title">Step 2</p>
            <p class="box_text">
                기록시 사용한 원판을</br>
                사진/영상으로 기록합니다
            </p>
        </div>
        <div class="article_box" name="box_3">
            <p class="box_title">Step 3</p>
            <p class="box_text">
                마이레코드 등록
            </p>
        </div>
        <div class="article_box" name="box_4">
            <p class="box_title">Step 4</p>
            <p class="box_text">
                관리자 심사 대기</br>
                (승인시 마이레코드 인증서 생성)
            </p>
        </div>
    </div>
</div>
<!-- section_2 -->



<div id="section_3">
    <div class="section_wrap"></div>
</div>
<!-- section_3 -->




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








<?
echo script_load('/common/index.js');
?>
<script>
$(function () {
    
});
</script>
<?
include_once('footer.php');   // 푸터파일 로드
?>