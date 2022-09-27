<?
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가
?>


<!DOCTYPE html>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="title" content="마이레코드">
<meta name="subject" content="마이레코드">
<meta name="description" content="운동 커뮤니티 마이레코드 입니다.">
<meta name="keywords" content="헬스, 운동, 맨몸운동, 크로스핏, 필라테스, 헬창, 3대, 3대운동">
<meta name="writer" content="마이레코드">
<meta name="author" content="마이레코드">
<meta name="copyright" content="마이레코드">
<meta name="robots" content="ALL">

<meta property="og:type" content="website">
<meta property="og:title" content="마이레코드">
<meta property="og:description" content="운동 정보는 마이레코드">
<meta property="og:image" content="URL:LOGO">
<meta property="og:url" content="url">


<!-- safari 앵커태그 방지 -->
<meta name="format-detection" content="telephone=no" />
<title>마이레코드</title>
<link rel="shortcut icon" href="/favicon.ico">
<!--[if lte IE 8]>

<![endif]-->



<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>

<!-- swiper js -->
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>


<script src="https://kit.fontawesome.com/d56d6b9080.js" crossorigin="anonymous"></script>

<?
echo css_load('/fonts/fonts.css');
echo css_load('/common/common.css');
echo css_load('/common/header.css');
echo css_load('/component/input/input.css');
?>

</head>

<body>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/component/alert/alert.php');
?>

<div id="header">
    <div class="pc_header">

        <div class="left_box">
            <div class="logo_box">
                <a class="logo_btn" href="/">
                    <img class="logo_img" src="/img/company/myrecord_logo.png?ver=20220924"/>
                </a>
            </div>
            <div class="menu_wrap">
<!--
                <div class="menu_box">
                    <a href="/community/free_board/list/" class="menu_btn">커뮤니티</a>

                    <div class="sub_menu_container">
                        <div class="sub_menu_wrap" name="community">
                            <div class="sub_menu_box">
                                <a href="/community/free_board/list/" class="sub_menu_btn">자유게시판</a>
                            </div>
                            <div class="sub_menu_box">
                                <a href="#" class="sub_menu_btn">자유게시판2</a>
                            </div>
                            <div class="sub_menu_box">
                                <a href="#" class="sub_menu_btn">자유게시판3</a>
                            </div>
                        </div>
                    </div>
                </div>
-->
                <!-- 커뮤니티 -->

                <div class="menu_box" name="record">
                    <a href="/record/landing/" class="menu_btn">마이레코드</a>
                </div>

                <div class="menu_box" name="util">
                    <a href="/util/" class="menu_btn">도구</a>
                </div>

            </div>
            <!-- menu_wrap -->
        </div>
        <!-- left_box -->

        <div class="right_box">
            <?
            if($is_member) {
            ?>
            <div class="menu_box">
                <a href="/api/account/set.logout.php" class="menu_btn">로그아웃</a>
            </div>
            <div class="menu_box">
                <a href="/account/myaccount" class="menu_btn">내 정보</a>
            </div>
            <?  
            } else {
            ?>
            <div class="menu_box">
                <a href="/account/login" class="menu_btn">로그인</a>
            </div>
            <div class="menu_box">
                <a href="/account/create/" class="menu_btn">회원가입</a>
            </div>
            <?
            }
            ?>
        </div>
        <!-- right_box -->

    </div>
    <!-- pc_header -->

    <div class="mobile_header">
        <div class="logo_box">
            <a class="logo_link" href="/">
                <img class="logo_img" src="/img/company/myrecord_logo_header.png?ver=20220924"/>
            </a>
        </div>

        <div class="menu_box">
            <button class="menu_btn" title="메뉴" onclick="mobileMenuRemote('on');"><i class="fa-solid fa-bars"></i></button>
        </div>

        <div id="mobile_menu_wrap" onclick="mobileMenuRemote();">
            <div id="mobile_menu_container">
                <div class="account_box">
                    <button class="close_btn" title="닫기" onclick="mobileMenuRemote();"><i class="fa-solid fa-xmark"></i></button>
                    <?
                    if($is_member) {
                    ?>
                    <a href="/api/account/set.logout.php" class="account_btn login"><i class="fa-solid fa-right-from-bracket"></i>로그아웃</a>
                    <a href="/account/myaccount" class="account_btn"><i class="fa-solid fa-user"></i>내 정보</a>
                    <?
                    } else {
                    ?>
                    <a href="/account/login/" class="account_btn login"><i class="fa-solid fa-power-off"></i>로그인</a>
                    <a href="/account/create/" class="account_btn"><i class="fa-solid fa-right-to-bracket"></i>회원가입</a>
                    <?
                    }
                    ?>
                </div>
                <div class="menu_list_container">
                    <div class="menu_wrap">
                        <div class="wrap_title">
                            <a href="/record/landing/" class="menu_title">마이레코드</a>
                        </div>
                        <div class="wrap_body">
                            <div class="menu_row">
                                <a href="/record/landing/" class="menu_btn">랭킹</a>
                            </div>
                            <div class="menu_row">
                                <a href="/record/squat/list/" class="menu_btn">Squat</a>
                            </div>
                            <div class="menu_row">
                                <a href="/record/benchpress/list/" class="menu_btn">BenchPress</a>
                            </div>
                            <div class="menu_row">
                                <a href="/record/deadlift/list/" class="menu_btn">DeadLift</a>
                            </div>
                        </div>
                    </div>
                    <!-- 마이레코드 -->
                    
                    <div class="menu_wrap">
                        <div class="wrap_title">
                            <a href="/util/" class="menu_title">도구</a>
                        </div>
                        <div class="wrap_body">
                            <div class="menu_row">
                                <a href="/util/kg_lb/" class="menu_btn">무게 변환기</a>
                            </div>
                        </div>
                    </div>
                    <!-- 도구 -->
                </div>
                <!-- menu_list_container -->
            </div>
            <!-- mobile_menu_container -->

        </div>
        <!-- mobile_menu_wrap -->

    </div>
    <!-- mobile_header -->

</div>
<!-- header -->


<div id="loading_wrap">
    <div class="loading_img_box">
        <img class="loading_img" src="/common/img/loading.gif" alt="로딩중"/>
    </div>
</div>

<?
echo script_load('/common/header.js');    
?>
<script>
headerInit();
</script>

<div id="container">














