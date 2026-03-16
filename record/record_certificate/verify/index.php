<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');

// ── 인증번호 생성 함수 (API와 동일 로직)
function generateCertCode(int $id): string {
    $raw = strtoupper(substr(hash('sha256', 'mr_cert_f7e2_' . $id), 0, 16));
    return implode('-', str_split($raw, 4));
}

// ── 파라미터 수신
$record_id  = isset($_GET['id'])   ? (int)preg_replace('/[^0-9]/', '', $_GET['id'])   : 0;
$input_code = isset($_GET['code']) ? preg_replace('/[^A-F0-9]/', '', strtoupper($_GET['code'])) : '';

$is_valid   = false;
$error_msg  = '';
$record_data = null;

if ($record_id > 0 && $input_code !== '') {

    // 코드 검증
    $expected_raw = strtoupper(substr(hash('sha256', 'mr_cert_f7e2_' . $record_id), 0, 16));
    if ($input_code === $expected_raw) {

        // 레코드 조회
        $sql = "
            Select  T1.id, T1.status, T2.user_nickname,
                    T1.record_weight, T3.record_name_ko,
                    T1.create_datetime  as request_datetime,
                    T4.create_datetime  as certificate_datetime
            From    tb_record_request T1
            Inner Join Account T2 On T1.account_id = T2.id
            Inner Join tb_record_master T3 On T1.record_type = T3.id
            Left Outer Join tb_record_inspection T4
                On  T1.id = T4.request_id
                And T4.change_status = '2'
            Where   T1.id = :id
            And     T1.status = 2
        ";
        $record_data = $PDO->fetch($sql, [':id' => $record_id]);

        if ($record_data) {
            $is_valid = true;
            $cert_date = $record_data['certificate_datetime']
                ? date('Y년 m월 d일', strtotime($record_data['certificate_datetime']))
                : date('Y년 m월 d일', strtotime($record_data['request_datetime']));
            $cert_code_fmt = generateCertCode($record_id);
        } else {
            $error_msg = '해당 기록을 찾을 수 없거나 아직 승인되지 않은 기록입니다.';
        }

    } else {
        $error_msg = '인증번호가 올바르지 않습니다. QR코드를 다시 스캔해주세요.';
    }

} else {
    $error_msg = '잘못된 접근입니다. 인증서의 QR코드를 스캔해주세요.';
}
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

    <?php if ($is_valid): ?>

    <div class="verify_result_card success">
        <div class="verify_card_top success">
            <i class="fa-solid fa-circle-check verify_icon"></i>
            <div class="verify_status_text">
                <p class="status_main">인증 완료</p>
                <p class="status_sub">마이레코드에서 공식 승인된 기록입니다</p>
            </div>
        </div>
        <div class="verify_card_body">
            <div class="verify_info_row">
                <span class="verify_info_label">닉네임</span>
                <span class="verify_info_value"><?php echo htmlspecialchars($record_data['user_nickname']); ?></span>
            </div>
            <div class="verify_info_row">
                <span class="verify_info_label">종목</span>
                <span class="verify_info_value"><?php echo htmlspecialchars($record_data['record_name_ko']); ?></span>
            </div>
            <div class="verify_info_row">
                <span class="verify_info_label">기록</span>
                <span class="verify_info_value weight"><?php echo htmlspecialchars($record_data['record_weight']); ?> KG</span>
            </div>
            <div class="verify_info_row">
                <span class="verify_info_label">인증일</span>
                <span class="verify_info_value"><?php echo $cert_date; ?></span>
            </div>
            <div class="verify_cert_code_box">
                <p class="code_label">인 증 번 호</p>
                <p class="code_value"><?php echo $cert_code_fmt; ?></p>
            </div>
        </div>
    </div>

    <?php else: ?>

    <div class="verify_result_card fail">
        <div class="verify_card_top fail">
            <i class="fa-solid fa-circle-xmark verify_icon"></i>
            <div class="verify_status_text">
                <p class="status_main">인증 실패</p>
                <p class="status_sub">유효하지 않은 인증서입니다</p>
            </div>
        </div>
        <div class="verify_error_msg">
            <?php echo nl2br(htmlspecialchars($error_msg)); ?>
        </div>
    </div>

    <?php endif; ?>

    <div class="verify_back_wrap">
        <a href="/" class="verify_back_link">
            <i class="fa-solid fa-house"></i> 홈으로
        </a>
    </div>

</div>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>
