/********** input text onkeyup event **********/
function inputOnkeyupEvent(obj) {
	$(obj).removeClass('alert');
	$(obj).siblings('.caution_text').remove();
}
/********** input text onkeyup event END **********/


/********** email onkeyup event **********/
function emailKeyEvent(obj) {
    const length = $(obj).val().length;
    const value = $(obj).val();

    if (length >= 3 && value.indexOf('@') == -1) {

        const listLength = $(obj).siblings(".email_list_box").children(".email_list_btn").length;

        for (let i = 1; i <= listLength; i++) {
            const listElement = $(obj).siblings(".email_list_box").children(".email_list_btn:nth-child("+i+")");

            const email = listElement.attr("value");
            const html = value + "@" + email;
            listElement.html(html);
        }

        $(obj).siblings(".email_list_box").addClass("on");
    } else {
        $(obj).siblings(".email_list_box").removeClass("on");
    }
}
/********** email onkeyup event END **********/


/********** email list click event **********/
function emailListClick(obj) {
    const html = $(obj).html();

    $(obj).parent(".email_list_box").siblings("input[type=text]").val(html);
    $(obj).parent(".email_list_box").removeClass("on");
}

$('html').click(function(e) {
    if(!$(e.target).hasClass('email_list_box') && !$(e.target).hasClass('email_list_btn')){
        $('.email_list_box').removeClass('on');
    }
});
/********** email list click event END **********/



/********** myrecord_select_wrap click event **********/
function selectDeviceCheck() {
    // 커스텀 셀렉트박스 사용시 해당 함수를 꼭 호출해야함
    // custom select 사용할건지 일반 select태그 사용할건지 구분
    const agent = navigator.userAgent.toLowerCase();
    if (agent.indexOf("iphone") != -1 || agent.indexOf("ipad") != -1) {
        // IOS
        $(".myrecord_select_wrap").addClass("mobile");
    } else if (agent.indexOf("android") != -1) {
        // 안드로이드
        $(".myrecord_select_wrap").addClass("mobile");
    }
}
function selectListRemote(obj) {
    $(obj).siblings(".select_list_wrap").addClass("on");
}

function optionClick(obj) {
    const value = $(obj).attr("value");
    const html = $(obj).html();

    if (value == "none") {
        $(obj).parent(".select_list_wrap").siblings(".select_remote_btn").removeClass("on");
    } else {
        $(obj).parent(".select_list_wrap").siblings(".select_remote_btn").addClass("on");
    }

    $(obj).parent(".select_list_wrap").siblings(".select_remote_btn").attr("value", value).html(html);
    $(obj).siblings(".select_list_btn").removeClass("active");
    $(obj).addClass("active");
    $(obj).parent(".select_list_wrap").removeClass("on");
    $(obj).parent(".select_list_wrap").siblings(".mobile_select_wrap").children(".mobile_select").children("option[value='"+value+"']").prop("selected", true);
}

function mobileOptionClick(obj) {
    const value = $(obj).val();
    const html = $(obj).children("option:selected").html();

    if (value == "none") {
        $(obj).removeClass("active");
    } else {
        $(obj).addClass("active");
    }

    $(obj).parent(".mobile_select_wrap").siblings(".select_remote_btn").attr("value", value).html(html);
    $(obj).parent(".mobile_select_wrap").siblings(".select_list_wrap").children(".select_list_btn").removeClass("active");
    $(obj).parent(".mobile_select_wrap").siblings(".select_list_wrap").children(".select_list_btn[value='"+value+"']").addClass("active");
}

$('html').click(function(e) {
    if(!$(e.target).hasClass('select_list_wrap') && !$(e.target).hasClass('select_remote_btn')){
        $('.select_list_wrap').removeClass('on');
    }
});
/********** myrecord_select_wrap click event END **********/