<?php
include_once('common.php');
include_once('header.php');
echo css_load('/common/404.css');
?>

<div id="not_found_wrap">
    <div class="not_found_inner">
        <div class="error_code">404</div>
        <div class="error_icon">
            <i class="fa-solid fa-dumbbell"></i>
        </div>
        <p class="error_title">페이지를 찾을 수 없어요</p>
        <p class="error_desc">
            요청하신 페이지가 존재하지 않거나<br>
            이동되었을 수 있습니다.
        </p>
        <div class="error_btn_wrap">
            <a href="/" class="home_btn"><i class="fa-solid fa-house"></i> 홈으로</a>
            <a href="javascript:history.back();" class="back_btn"><i class="fa-solid fa-angle-left"></i> 이전 페이지</a>
        </div>
    </div>
</div>

<?php include_once('footer.php'); ?>
