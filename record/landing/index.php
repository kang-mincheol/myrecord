<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

echo css_load('/common/record_pages.css');
echo css_load('/record/landing/index.css');
?>


<div class="record_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">랭킹</p>
        <p class="page_sub_text">마이레코드 종합 · 종목별 순위</p>
    </div>
</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/component/sub_menu/record_sub_menu/record_sub_menu.php');
?>

<div id="record_ranking">

    <div class="ranking_menu_wrap">
        <button class="ranking_menu_btn" name="total" onclick="rankingMenuRemote('total');">종합</button>
        <button class="ranking_menu_btn" name="Squat" onclick="rankingMenuRemote('Squat');">Squat</button>
        <button class="ranking_menu_btn" name="BenchPress" onclick="rankingMenuRemote('BenchPress');">BenchPress</button>
        <button class="ranking_menu_btn" name="DeadLift" onclick="rankingMenuRemote('DeadLift');">DeadLift</button>
    </div>

    <div class="ranking_contents_wrap">

        <div class="ranking_contents_box" name="total">
            <div class="ranking_contents_header">
                <div class="header_box rank">순위</div>
                <div class="header_box total">3대</div>
                <div class="header_box squat">Squat</div>
                <div class="header_box benchpress">BenchPress</div>
                <div class="header_box deadlift">DeadLift</div>
                <div class="header_box name">닉네임</div>
            </div>
            <div class="ranking_contents_body"></div>
        </div>
        <!-- total -->

        <div class="ranking_contents_box" name="Squat">
            <div class="ranking_contents_header">
                <div class="header_box rank">순위</div>
                <div class="header_box weight">무게</div>
                <div class="header_box name">닉네임</div>
            </div>
            <div class="ranking_contents_body"></div>
        </div>
        <!-- squat -->

        <div class="ranking_contents_box" name="BenchPress">
            <div class="ranking_contents_header">
                <div class="header_box rank">순위</div>
                <div class="header_box weight">무게</div>
                <div class="header_box name">닉네임</div>
            </div>
            <div class="ranking_contents_body"></div>
        </div>
        <!-- bench -->

        <div class="ranking_contents_box" name="DeadLift">
            <div class="ranking_contents_header">
                <div class="header_box rank">순위</div>
                <div class="header_box weight">무게</div>
                <div class="header_box name">닉네임</div>
            </div>
            <div class="ranking_contents_body"></div>
        </div>
        <!-- dead -->

    </div>
    <!-- ranking_contents_wrap -->

</div>
<!-- record_ranking -->


<?php
echo script_load('/record/landing/index.js');
?>
<script>
$(function () {
    init();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
