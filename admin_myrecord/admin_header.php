<?php
if (!defined('NO_ALONE')) exit;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MYRECORD 어드민</title>

<!-- Pretendard -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css"/>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Admin CSS -->
<link rel="stylesheet" href="/admin_myrecord/admin.css"/>
<?php if(isset($admin_extra_css)) echo $admin_extra_css; ?>
</head>
<body>

<div id="admin_layout">

    <!-- ===== Sidebar ===== -->
    <aside id="admin_sidebar">

        <div class="sidebar_logo">
            <span class="logo_text">MYRECORD</span>
            <span class="logo_badge">ADMIN</span>
        </div>

        <nav class="sidebar_nav" id="admin_sidebar_nav">

            <div class="nav_section">
                <p class="nav_section_title">대시보드</p>
                <a href="/admin_myrecord/" class="nav_item" data-nav="/admin_myrecord/index">
                    <i class="fa-solid fa-gauge"></i> 대시보드
                </a>
            </div>

            <div class="nav_section">
                <p class="nav_section_title">회원</p>
                <a href="/admin_myrecord/account/" class="nav_item" data-nav="/admin_myrecord/account">
                    <i class="fa-solid fa-users"></i> 회원 관리
                </a>
            </div>

            <div class="nav_section">
                <p class="nav_section_title">기록</p>
                <a href="/admin_myrecord/record/" class="nav_item" data-nav="/admin_myrecord/record">
                    <i class="fa-solid fa-dumbbell"></i> 기록 관리
                </a>
            </div>

            <div class="nav_section">
                <p class="nav_section_title">커뮤니티</p>
                <a href="/admin_myrecord/free_board/" class="nav_item" data-nav="/admin_myrecord/free_board">
                    <i class="fa-solid fa-comments"></i> 자유게시판
                </a>
                <a href="/admin_myrecord/muscle_gain/" class="nav_item" data-nav="/admin_myrecord/muscle_gain">
                    <i class="fa-solid fa-fire"></i> 득근일지
                </a>
            </div>

            <div class="nav_section">
                <p class="nav_section_title">시스템</p>
                <a href="/admin_myrecord/access_log/" class="nav_item" data-nav="/admin_myrecord/access_log">
                    <i class="fa-solid fa-list-check"></i> 접속 로그
                </a>
                <a href="/admin_myrecord/system/" class="nav_item" data-nav="/admin_myrecord/system">
                    <i class="fa-solid fa-gear"></i> 시스템 설정
                </a>
            </div>

        </nav>

        <div class="sidebar_footer">
            <a href="/" class="logout_btn" style="margin-bottom:10px; display:flex;">
                <i class="fa-solid fa-arrow-left"></i> 사이트로 이동
            </a>
        </div>

    </aside>
    <!-- /admin_sidebar -->

    <!-- ===== Main ===== -->
    <div id="admin_main">

        <!-- Topbar -->
        <header id="admin_topbar">
            <div class="topbar_left">
                <p class="page_title" id="admin_page_title"><?= $admin_page_title ?? '' ?></p>
            </div>
            <div class="topbar_right">
                <a href="/" class="site_link" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> 사이트 보기
                </a>
                <div class="admin_info">
                    <div class="admin_avatar">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <span class="admin_name" id="admin_name">관리자</span>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main id="admin_content">
