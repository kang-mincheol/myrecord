function init() {
    getCertificateData();
}

let certificate_date      = "";
let certificate_master    = "";
let certificate_cert_code = "";
let certificate_record_id = "";

function getCertificateData() {
    loadingOn();
    var record_id = getParam('record_id');
    record_id = record_id.replace(/[^0-9]/g, "");

    if(record_id == '' || isNaN(record_id)) {
        loadingOff();
        myrecordAlert('on', '잘못된 값입니다');
        return;
    }

    $.ajax({
        type: "POST",
        url: "/api/record/certificate/get.certificate_data.php",
        data: JSON.stringify({ record_id: record_id }),
        success: function(data) {
            loadingOff();
            if(data["code"] == "SUCCESS") {
                var record = data["data"];

                $(".cert_info_row[name=record_nickname] .cert_info_value").text(record["nickname"]);
                $(".cert_info_row[name=record_master] .cert_info_value").text(record["record_type"]);
                $(".cert_info_row[name=record_weight] .cert_info_value").text(record["record_weight"]);
                $(".cert_date").text(record["date"]);
                $(".cert_code_text").text(record["cert_code"]);

                certificate_date      = record["date"];
                certificate_master    = record["record_type"];
                certificate_cert_code = record["cert_code"];
                certificate_record_id = record["record_id"];

                $("#certificate_box").addClass("on");
                $(".cert_btn_wrap").removeClass("off");

                renderQRCode(record["record_id"], record["cert_code"]);
            } else {
                myrecordAlert('on', data["msg"]);
            }
        },
        error: function(err) {
            loadingOff();
            myrecordAlert('on', '에러가 발생했습니다.');
            console.log(err);
        }
    });
}

function renderQRCode(recordId, certCode) {
    var codeRaw   = certCode.replace(/-/g, '');
    var verifyUrl = location.protocol + '//' + location.host
        + '/record/record_certificate/verify/?id=' + recordId + '&code=' + codeRaw;

    new QRCode(document.getElementById("cert_qr_code"), {
        text          : verifyUrl,
        width         : 80,
        height        : 80,
        colorDark     : "#0123B4",
        colorLight    : "#ffffff",
        correctLevel  : QRCode.CorrectLevel.M
    });
}

const certificateDownload = () => {
    var $btn = $('.cert_download_btn');
    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> 저장 중...');

    html2canvas(document.querySelector("#certificate_box"), {
        scale: 2,           // 고화질 (2x retina)
        useCORS: true,      // 크로스오리진 이미지 허용
        allowTaint: true,
        backgroundColor: '#ffffff',
        logging: false,
    }).then(canvas => {
        const image = canvas.toDataURL("image/png", 1.0);
        const a_tag = document.createElement("a");
        a_tag.href = image;
        a_tag.download = `myrecord_certificate_${certificate_date}_${certificate_master}.png`;
        a_tag.click();

        $btn.prop('disabled', false).html('<i class="fa-solid fa-download"></i> 인증서 저장');
    }).catch(function() {
        $btn.prop('disabled', false).html('<i class="fa-solid fa-download"></i> 인증서 저장');
        myrecordAlert('on', '저장 중 오류가 발생했습니다.');
    });
};
