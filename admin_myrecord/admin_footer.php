<?php if (!defined('NO_ALONE')) exit; ?>

        </main>
        <!-- /admin_content -->

    </div>
    <!-- /admin_main -->

</div>
<!-- /admin_layout -->

<script>
// ── 사이드바 활성 메뉴 표시
(function () {
    var path = window.location.pathname;
    document.querySelectorAll('#admin_sidebar_nav .nav_item[data-nav]').forEach(function (el) {
        if (path.indexOf(el.dataset.nav) !== -1) {
            el.classList.add('active');
        }
    });
})();

// ── 어드민 닉네임 표시
fetch('/api/v1/accounts/me')
    .then(function (r) { return r.json(); })
    .then(function (res) {
        if (res.code === 'SUCCESS' && res.data && res.data.account_nickname) {
            document.getElementById('admin_name').textContent = res.data.account_nickname;
        }
    })
    .catch(function () {});
</script>

</body>
</html>
