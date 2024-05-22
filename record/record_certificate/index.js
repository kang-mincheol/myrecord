function init() {
    getCertificateData();
}

let certificate_date = "";
let certificate_master = "";

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
        data: JSON.stringify({
            record_id: record_id
        }),
        success: function(data) {
            console.log(data);
            loadingOff();
            if(data["code"] == "SUCCESS") {
                var record = data["data"];

                $("#certificate_wrap .certificate_box .record_info_wrap .info_row[name=record_nickname] .info_value").text(record["nickname"]);
                $("#certificate_wrap .certificate_box .record_info_wrap .info_row[name=record_master] .info_value").text(record["record_type"]);
                $("#certificate_wrap .certificate_box .record_info_wrap .info_row[name=record_weight] .info_value").text(record["record_weight"]);

                $("#certificate_wrap .certificate_box .myrecord_signature_wrap .certificate_date").text(record["date"]);

                certificate_date = record["date"];
                certificate_master = record["record_type"];

                $("#certificate_wrap").addClass("on");
                $(".img_btn_wrap").removeClass("off");
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

const certificateDownload = () => {
    html2canvas(document.querySelector("#certificate_box")).then(canvas => {
        canvas.id = "capture_canvas";
        document.querySelector("#capture_wrapper").appendChild(canvas);

        const image = canvas.toDataURL("image/png", 1.0);

        const a_tag = document.createElement("a");
        a_tag.href = image;
        a_tag.download = `myrecord_certificate_${certificate_date}_${certificate_master}.png`;
        a_tag.click();

        const capture_wrapper = document.querySelector("#capture_wrapper");
        while(capture_wrapper.firstChild) {
            capture_wrapper.removeChild(capture_wrapper.firstChild);
        }
    });
}