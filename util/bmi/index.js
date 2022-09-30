function bmiCalc() {
    var height = $("#bmi_height").val();
    var weight = $("#bmi_weight").val();

    if(isNaN(height) || isNaN(weight)) {
        myrecordAlert('on', '숫자만 입력해주세요.');
        return;
    }

    var result = 0;
    var height_10 = height / 100;
    console.log(height_10);

    result = weight / (height_10 * height_10);
    console.log(result);
    result = result.toFixed(1);
    console.log(result);
    var result_text = "";
    if(result <= 18.5) {
        result_text = "저체중";
    } else if (result > 18.5 && result <= 22.9) {
        result_text = "정상";
    } else if (result > 23.0 && result <= 24.9) {
        result_text = "과체중";
    } else {
        result_text = "비만";
    }

    $("#calculator_wrap .result_box .result_value_box .value").text(result);
    $("#calculator_wrap .result_box .result_value_text .value").text(result);
    $("#calculator_wrap .result_box .result_value_text .value_text").text(result_text);
    $("#calculator_wrap .result_box").addClass("on");
}

function bmiReset() {
    $("#bmi_height").val("");
    $("#bmi_weight").val("");

    $("#calculator_wrap .result_box").removeClass("on");
}