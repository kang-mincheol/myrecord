<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>

<style>
.verify_page_header {
    background: linear-gradient(135deg, #0123B4 0%, #0a2fe0 100%);
    padding: 36px 20px 32px;
    text-align: center;
    color: #fff;
}
.verify_page_header .page_title {
    font-size: 22px;
    font-weight: 700;
    letter-spacing: -0.5px;
    margin-bottom: 6px;
}
.verify_page_header .page_subtitle {
    font-size: 14px;
    opacity: 0.85;
}

.verify_page_wrap {
    max-width: 560px;
    margin: 0 auto;
    padding: 40px 20px 80px;
}

/* ── 성공 카드 ── */
.verify_result_card {
    background: #fff;
    border: 2px solid #e0e6f5;
    border-radius: 16px;
    overflow: hidden;
}

.verify_result_card.success {
    border-color: #0123B4;
}

.verify_result_card.fail {
    border-color: #e0e0e0;
}

.verify_card_top {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 20px 24px;
}

.verify_card_top.success {
    background: linear-gradient(135deg, #0123B4 0%, #0a2fe0 100%);
    color: #fff;
}

.verify_card_top.fail {
    background: #f7f8fc;
    color: #888;
}

.verify_card_top .verify_icon {
    font-size: 28px;
}

.verify_card_top .verify_status_text .status_main {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: -0.3px;
    margin-bottom: 2px;
}

.verify_card_top .verify_status_text .status_sub {
    font-size: 13px;
    opacity: 0.85;
}

.verify_card_body {
    padding: 24px;
}

.verify_info_row {
    display: flex;
    align-items: center;
    padding: 13px 0;
    border-bottom: 1px solid #f0f2f9;
}

.verify_info_row:last-child {
    border-bottom: none;
}

.verify_info_label {
    font-size: 12px;
    font-weight: 700;
    color: #999;
    width: 68px;
    flex-shrink: 0;
    letter-spacing: 0.5px;
}

.verify_info_value {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
}

.verify_info_value.weight {
    color: #0123B4;
    font-size: 17px;
}

.verify_cert_code_box {
    margin-top: 20px;
    padding: 14px 18px;
    background: #f7f9ff;
    border: 1px solid #e0e6f5;
    border-radius: 10px;
    text-align: center;
}

.verify_cert_code_box .code_label {
    font-size: 11px;
    font-weight: 700;
    color: #0123B4;
    letter-spacing: 2px;
    margin-bottom: 6px;
}

.verify_cert_code_box .code_value {
    font-size: 16px;
    font-weight: 700;
    color: #1a1a2e;
    letter-spacing: 2px;
    font-family: 'Courier New', Courier, monospace;
}

/* ── 오류 메시지 ── */
.verify_error_msg {
    padding: 20px 24px;
    text-align: center;
    color: #888;
    font-size: 14px;
    line-height: 1.7;
}

.verify_back_link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 28px;
    padding: 12px 28px;
    background: #0123B4;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    border-radius: 50px;
    transition: background 0.15s;
}

.verify_back_link:hover {
    background: #0a2fe0;
    color: #fff;
}

.verify_back_wrap {
    text-align: center;
    margin-top: 28px;
}

.verify_loading {
    text-align: center;
    padding: 60px 0;
    color: #aaa;
    font-size: 15px;
}

.verify_loading i {
    font-size: 28px;
    display: block;
    margin-bottom: 12px;
    color: #0123B4;
}

@media screen and (max-width: 600px) {
    .verify_page_wrap {
        padding: 24px 16px 60px;
    }
}
</style>

<div class="verify_page_header">
    <div>
        <p class="page_title"><i class="fa-solid fa-shield-check"></i> 인증서 검증</p>
        <p class="page_subtitle">마이레코드 공식 인증서의 진위를 확인합니다</p>
    </div>
</div>

<div class="verify_page_wrap">

    <div id="verify_root">
        <div class="verify_loading">
            <i class="fa-solid fa-spinner fa-spin"></i>
            인증 확인 중...
        </div>
    </div>

    <div class="verify_back_wrap">
        <a href="/" class="verify_back_link">
            <i class="fa-solid fa-house"></i> 홈으로
        </a>
    </div>

</div>

<script>
(function () {
    var sp = new URLSearchParams(location.search);
    var id   = parseInt(sp.get('id') || '0');
    var code = sp.get('code') || '';

    if (!id || !code) {
        renderFail('잘못된 접근입니다. 인증서의 QR코드를 스캔해주세요.');
        return;
    }

    fetch('/api/v1/records/' + id + '/verify?code=' + encodeURIComponent(code))
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.code === 'SUCCESS') {
                renderSuccess(res.data);
            } else {
                renderFail(res.msg || '인증에 실패했습니다.');
            }
        })
        .catch(function () {
            renderFail('서버 오류가 발생했습니다.');
        });

    function renderSuccess(d) {
        document.getElementById('verify_root').innerHTML =
            '<div class="verify_result_card success">' +
                '<div class="verify_card_top success">' +
                    '<i class="fa-solid fa-circle-check verify_icon"></i>' +
                    '<div class="verify_status_text">' +
                        '<p class="status_main">인증 완료</p>' +
                        '<p class="status_sub">마이레코드에서 공식 승인된 기록입니다</p>' +
                    '</div>' +
                '</div>' +
                '<div class="verify_card_body">' +
                    '<div class="verify_info_row">' +
                        '<span class="verify_info_label">닉네임</span>' +
                        '<span class="verify_info_value">' + esc(d.nickname) + '</span>' +
                    '</div>' +
                    '<div class="verify_info_row">' +
                        '<span class="verify_info_label">종목</span>' +
                        '<span class="verify_info_value">' + esc(d.record_name) + '</span>' +
                    '</div>' +
                    '<div class="verify_info_row">' +
                        '<span class="verify_info_label">기록</span>' +
                        '<span class="verify_info_value weight">' + esc(String(d.record_weight)) + ' KG</span>' +
                    '</div>' +
                    '<div class="verify_info_row">' +
                        '<span class="verify_info_label">인증일</span>' +
                        '<span class="verify_info_value">' + esc(d.cert_date) + '</span>' +
                    '</div>' +
                    '<div class="verify_cert_code_box">' +
                        '<p class="code_label">인 증 번 호</p>' +
                        '<p class="code_value">' + esc(d.cert_code) + '</p>' +
                    '</div>' +
                '</div>' +
            '</div>';
    }

    function renderFail(msg) {
        document.getElementById('verify_root').innerHTML =
            '<div class="verify_result_card fail">' +
                '<div class="verify_card_top fail">' +
                    '<i class="fa-solid fa-circle-xmark verify_icon"></i>' +
                    '<div class="verify_status_text">' +
                        '<p class="status_main">인증 실패</p>' +
                        '<p class="status_sub">유효하지 않은 인증서입니다</p>' +
                    '</div>' +
                '</div>' +
                '<div class="verify_error_msg">' + esc(msg).replace(/\n/g, '<br>') + '</div>' +
            '</div>';
    }

    function esc(str) {
        return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
}());
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
