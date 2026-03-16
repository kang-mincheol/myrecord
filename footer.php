<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

echo css_load('/common/footer.css');
?>

</div><!-- container -->

<div id="footer">
    <div class="footer_inner">

        <div class="footer_top">

            <!-- 브랜드 -->
            <div class="footer_brand">
                <p class="footer_logo">MYRECORD</p>
                <p class="footer_tagline">3대 측정은 마이레코드</p>
                <p class="footer_desc">스쿼트 · 벤치프레스 · 데드리프트<br>나의 기록을 관리하고 성장을 확인하세요.</p>
            </div>

            <!-- 네비게이션 -->
            <nav class="footer_nav">
                <div class="nav_col">
                    <p class="nav_col_title">득근일지</p>
                    <a href="/workout_log/list/" class="nav_link">내 운동 기록</a>
                    <a href="/workout_log/write/" class="nav_link">기록 추가</a>
                </div>
                <div class="nav_col">
                    <p class="nav_col_title">커뮤니티</p>
                    <a href="/community/free_board/" class="nav_link">자유게시판</a>
                </div>
                <div class="nav_col">
                    <p class="nav_col_title">마이레코드</p>
                    <a href="/record/record_main/" class="nav_link">기록 등록</a>
                    <a href="/record/squat/list/" class="nav_link">스쿼트</a>
                    <a href="/record/benchpress/list/" class="nav_link">벤치프레스</a>
                    <a href="/record/deadlift/list/" class="nav_link">데드리프트</a>
                </div>
                <div class="nav_col">
                    <p class="nav_col_title">도구</p>
                    <a href="/util/bmi/" class="nav_link">BMI 계산기</a>
                    <a href="/util/ffmi/" class="nav_link">FFMI 계산기</a>
                    <a href="/util/kg_lb/" class="nav_link">KG / LB 변환기</a>
                </div>
            </nav>

        </div>
        <!-- footer_top -->

        <div class="footer_bottom">
            <p class="copyright">Copyright &copy; 2022 MYRECORD. All rights reserved.</p>
        </div>

    </div>
    <!-- footer_inner -->
</div>
<!-- footer -->

<?php
echo script_load('/common/common.js');
echo script_load('/component/input/input.js');
?>
</body>

</html>
