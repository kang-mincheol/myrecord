function init() {
    stepRemote('step_1');
}

function stepRemote(name) {
    if(name != undefined) {
        $(".create_wrap .step_box").removeClass("on");
        $(".create_wrap .step_box[name="+name+"]").addClass("on");
    }
}









/********** step_1 **********/

/***** 모두 동의 *****/
function termsAll() {
    $("#terms_service").prop('checked', true);
    $("#terms_private").prop('checked', true);
    $("#terms_marketing").prop('checked', true);
}
/***** 모두 동의 END *****/

/***** step_1 검증 *****/
function step1Verify() {
    var service = $("#terms_service").prop('checked');
    var private = $("#terms_private").prop('checked');
    if(service && private) {
        $(".create_wrap .step_box[name=step_1] .next_btn").addClass("on").attr('onclick', 'stepRemote(\'step_2\');');
    } else {
        $(".create_wrap .step_box[name=step_1] .next_btn").removeClass("on").attr('onclick', '');
    }
}
/***** step_1 검증 END *****/

/********** step_1 END **********/