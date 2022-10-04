function ffmiCalc() {
    var height = $("#ffmi_height").val();
    var weight = $("#ffmi_weight").val();
    var fat = $("#ffmi_fat").val();
    
    var minusFat = weight - (weight * (fat / 100));
    var heightPer = height / 100;
    var height2x = heightPer * heightPer;
    var result = minusFat / height2x;
    result = result.toFixed(3);

    $("#ffmi_wrap .calc_result_wrap").addClass("on");
    $("#ffmi_wrap .calc_result_wrap #ffmi_result_value").text(result);
}

function ffmiReset() {
    $("#ffmi_height").val('');
    $("#ffmi_weight").val('');
    $("#ffmi_fat").val('');

    $("#ffmi_wrap .calc_result_wrap").removeClass("on");
    $("#ffmi_wrap .calc_result_wrap #ffmi_result_value").text('');
}