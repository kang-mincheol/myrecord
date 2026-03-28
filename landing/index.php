<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="스쿼트, 벤치프레스, 데드리프트 3대 기록을 관리자가 직접 검증하고 기록서를 발급해드립니다. 마이레코드에서 나의 기록을 남기고 전국 랭킹을 확인하세요.">
    <title>마이레코드 — 3대 측정 기록 서비스</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/img/company/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/company/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/company/favicon/favicon-16x16.png">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts Fallback -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <!-- Pretendard -->
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css">

    <link rel="stylesheet" href="/landing/index.css">
</head>
<body>

<!-- ===== 네비게이션 ===== -->
<nav id="lp_nav">
    <div class="lp_nav_inner">
        <a href="/" class="lp_logo">
            <img src="/img/company/myrecord_logo_header.png" alt="마이레코드">
        </a>
        <div class="lp_nav_right" id="lp_nav_right">
            <!-- JS에서 로그인 상태에 따라 렌더링 -->
        </div>
    </div>
</nav>


<!-- ===== 히어로 ===== -->
<section id="lp_hero">
    <div class="hero_overlay"></div>
    <div class="hero_inner">
        <span class="hero_badge"><i class="fa-solid fa-shield-check"></i> 관리자 직접 검증 · 전문가 심사 서비스</span>
        <h1 class="hero_title">
            3대 기록을<br>
            <span class="hero_title_point">전문가에게</span> 검증받으세요
        </h1>
        <p class="hero_sub">
            스쿼트 · 벤치프레스 · 데드리프트<br>
            스트롱맨 전문가가 직접 검증하는 국내 유일 플랫폼
        </p>
        <div class="hero_btn_wrap">
            <a href="/account/create/" class="hero_btn_primary">
                <i class="fa-solid fa-dumbbell"></i> 지금 무료로 시작하기
            </a>
            <a href="/record/landing/" class="hero_btn_secondary">
                랭킹 보기 <i class="fa-solid fa-angle-right"></i>
            </a>
        </div>
    </div>
    <a class="hero_scroll_hint" href="#lp_features">
        <i class="fa-solid fa-angle-down"></i>
    </a>
</section>


<!-- ===== 핵심 기능 ===== -->
<section id="lp_features">
    <div class="lp_section_inner">
        <p class="lp_section_badge">Why MyRecord</p>
        <h2 class="lp_section_title">마이레코드를 선택하는 이유</h2>
        <p class="lp_section_sub">단순한 기록 저장이 아닌, 전문가 검증이 필요한 이유가 있습니다</p>

        <div class="feature_grid">

            <div class="feature_card reveal">
                <div class="feature_icon_wrap">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <h3 class="feature_title">관리자 직접 검증</h3>
                <p class="feature_desc">모든 기록은 스트롱맨 전문가가 직접 영상을 확인하고 승인합니다. 체계적인 검증 절차로 신뢰할 수 있는 기록을 만들어드립니다.</p>
            </div>

            <div class="feature_card reveal">
                <div class="feature_icon_wrap">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <h3 class="feature_title">위조방지 기록서 발급</h3>
                <p class="feature_desc">승인된 기록에는 고유 번호와 QR코드가 포함된 디지털 기록서가 발급됩니다. 언제 어디서나 기록서의 진위를 확인할 수 있습니다.</p>
                <span class="feature_new_badge">NEW</span>
            </div>

            <div class="feature_card reveal">
                <div class="feature_icon_wrap">
                    <i class="fa-solid fa-ranking-star"></i>
                </div>
                <h3 class="feature_title">실시간 전국 랭킹</h3>
                <p class="feature_desc">종목별, 3대 종합 랭킹에서 나의 위치를 실시간으로 확인하세요. 승인 완료된 기록만 반영되는 공정한 랭킹 시스템입니다.</p>
            </div>

        </div>
    </div>
</section>


<!-- ===== 3대 종목 ===== -->
<section id="lp_records">
    <div class="lp_section_inner">
        <p class="lp_section_badge">Record Types</p>
        <h2 class="lp_section_title">3대 종목 기록 등록</h2>
        <p class="lp_section_sub">3가지 핵심 종목의 최고 기록을 등록하고 기록서를 받으세요</p>

        <div class="record_card_grid">

            <div class="record_card reveal">
                <div class="record_card_top squat_gradient">
                    <i class="fa-solid fa-person-falling-burst record_card_icon"></i>
                    <span class="record_card_name">Squat</span>
                    <span class="record_card_name_ko">스쿼트</span>
                </div>
                <div class="record_card_bottom">
                    <p class="record_card_desc">하체 근력의 왕, 스쿼트 기록을 등록하고 전국 랭킹에서 나의 위치를 확인하세요.</p>
                    <a href="/record/squat/list/" class="record_card_btn">기록 보기 <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="record_card reveal">
                <div class="record_card_top bench_gradient">
                    <i class="fa-solid fa-dumbbell record_card_icon"></i>
                    <span class="record_card_name">Bench Press</span>
                    <span class="record_card_name_ko">벤치프레스</span>
                </div>
                <div class="record_card_bottom">
                    <p class="record_card_desc">상체 파워의 기준, 벤치프레스 기록을 등록하고 기록서를 발급받으세요.</p>
                    <a href="/record/benchpress/list/" class="record_card_btn">기록 보기 <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="record_card reveal">
                <div class="record_card_top dead_gradient">
                    <i class="fa-solid fa-weight-hanging record_card_icon"></i>
                    <span class="record_card_name">Deadlift</span>
                    <span class="record_card_name_ko">데드리프트</span>
                </div>
                <div class="record_card_bottom">
                    <p class="record_card_desc">전신 근력의 총합, 데드리프트 최고 기록을 마이레코드에서 검증받으세요.</p>
                    <a href="/record/deadlift/list/" class="record_card_btn">기록 보기 <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ===== 등록 방법 ===== -->
<section id="lp_steps">
    <div class="lp_section_inner">
        <p class="lp_section_badge">How It Works</p>
        <h2 class="lp_section_title">간단한 4단계로 검증 완료</h2>
        <p class="lp_section_sub">복잡한 절차 없이 누구나 쉽게 기록을 등록하고 검증받을 수 있습니다</p>

        <div class="steps_wrap">

            <div class="step_item reveal">
                <div class="step_num">01</div>
                <div class="step_icon_wrap">
                    <i class="fa-solid fa-video"></i>
                </div>
                <h3 class="step_title">영상 촬영</h3>
                <p class="step_desc">본인의 리프팅을 영상으로 촬영합니다. 전신이 보이도록 촬영해주세요.</p>
            </div>

            <div class="step_arrow reveal"><i class="fa-solid fa-arrow-right"></i></div>

            <div class="step_item reveal">
                <div class="step_num">02</div>
                <div class="step_icon_wrap">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <h3 class="step_title">원판 촬영</h3>
                <p class="step_desc">사용한 원판을 사진 또는 영상으로 기록해 정확한 무게를 확인시켜주세요.</p>
            </div>

            <div class="step_arrow reveal"><i class="fa-solid fa-arrow-right"></i></div>

            <div class="step_item reveal">
                <div class="step_num">03</div>
                <div class="step_icon_wrap">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <h3 class="step_title">마이레코드 등록</h3>
                <p class="step_desc">촬영한 파일을 마이레코드에 업로드하고 기록 정보를 입력합니다.</p>
            </div>

            <div class="step_arrow reveal"><i class="fa-solid fa-arrow-right"></i></div>

            <div class="step_item reveal">
                <div class="step_num">04</div>
                <div class="step_icon_wrap">
                    <i class="fa-solid fa-award"></i>
                </div>
                <h3 class="step_title">기록서 수령</h3>
                <p class="step_desc">관리자 검토 후 승인되면 기록서가 자동으로 발급됩니다.</p>
            </div>

        </div>
    </div>
</section>


<!-- ===== 추가 기능 ===== -->
<section id="lp_more">
    <div class="lp_section_inner">
        <p class="lp_section_badge" style="color: rgba(255,255,255,0.55);">More Features</p>
        <h2 class="lp_section_title" style="color: #fff;">기록 그 이상의 경험</h2>
        <p class="lp_section_sub" style="color: rgba(255,255,255,0.6);">마이레코드는 기록 등록 외에도 다양한 기능을 제공합니다</p>

        <div class="more_grid">

            <div class="more_card reveal">
                <div class="more_icon"><i class="fa-solid fa-book-open"></i></div>
                <h3 class="more_title">득근일지</h3>
                <p class="more_desc">오늘 운동한 종목과 세트를 기록하세요. 운동 히스토리를 한눈에 확인할 수 있습니다.</p>
                <a href="/workout_log/list/" class="more_link">바로가기 <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="more_card reveal">
                <div class="more_icon"><i class="fa-solid fa-comments"></i></div>
                <h3 class="more_title">커뮤니티</h3>
                <p class="more_desc">운동 정보를 나누고 서로의 기록에 응원 댓글을 남겨보세요. 함께 성장하는 공간입니다.</p>
                <a href="/community/free_board/list/" class="more_link">바로가기 <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="more_card reveal">
                <div class="more_icon"><i class="fa-solid fa-calculator"></i></div>
                <h3 class="more_title">운동 도구</h3>
                <p class="more_desc">KG↔LB 변환기, BMI, FFMI 계산기 등 유용한 운동 관련 도구를 무료로 사용하세요.</p>
                <a href="/util/" class="more_link">바로가기 <i class="fa-solid fa-arrow-right"></i></a>
            </div>

        </div>
    </div>
</section>


<!-- ===== 최종 CTA ===== -->
<section id="lp_cta">
    <div class="lp_cta_overlay"></div>
    <div class="lp_cta_inner">
        <h2 class="lp_cta_title">지금 바로 나의 기록을<br>전문가에게 검증받으세요</h2>
        <p class="lp_cta_sub">무료로 가입하고 3대 기록서를 발급받으세요</p>
        <a href="/account/create/" class="lp_cta_btn">
            <i class="fa-solid fa-dumbbell"></i> 무료로 시작하기
        </a>
    </div>
</section>


<!-- ===== 푸터 ===== -->
<footer id="lp_footer">
    <div class="lp_footer_inner">
        <div class="lp_footer_top">
            <a href="/" class="lp_footer_logo">
                <img src="/img/company/myrecord_logo.png" alt="마이레코드">
            </a>
            <p class="lp_footer_tagline">3대 측정은 마이레코드</p>
        </div>
        <div class="lp_footer_links">
            <a href="/record/landing/">랭킹</a>
            <a href="/workout_log/list/">득근일지</a>
            <a href="/community/free_board/list/">커뮤니티</a>
            <a href="/util/">도구</a>
            <a href="/policy/privacy/">개인정보처리방침</a>
            <a href="/policy/terms/">이용약관</a>
        </div>
        <p class="lp_footer_copy">© 2025 MyRecord. All rights reserved.</p>
    </div>
</footer>


<script>
var IS_MEMBER = <?= json_encode($is_member) ?>;

// 네비게이션 로그인 상태 버튼 렌더링
(function() {
    var $nav = document.getElementById('lp_nav_right');
    if (IS_MEMBER) {
        $nav.innerHTML = '<a href="/record/squat/list/" class="lp_nav_cta">기록 등록하기 <i class="fa-solid fa-arrow-right"></i></a>';
    } else {
        $nav.innerHTML =
            '<a href="/account/login/" class="lp_nav_login">로그인</a>' +
            '<a href="/account/create/" class="lp_nav_cta">무료 가입 <i class="fa-solid fa-arrow-right"></i></a>';
    }
})();

// 스크롤 진입 애니메이션
(function() {
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(function(el) {
        observer.observe(el);
    });

    // 네비 스크롤 그림자
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('lp_nav');
        if (window.scrollY > 20) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
})();
</script>

</body>
</html>
