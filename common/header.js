function headerInit() {
    urlMenuCheck();
}

function mobileMenuRemote(remote) {
    if(remote == undefined) {
        $("#mobile_menu_wrap").fadeOut("fast");
        $("#mobile_menu_container").removeClass("on");
    } else {
        $("#mobile_menu_wrap").fadeIn("fast");
        $("#mobile_menu_container").addClass("on");
    }
}


function loadingOn() {
    var loading = document.querySelector('#loading_wrap');
    loading.classList.add('on');
}

function loadingOff() {
    var loading = document.querySelector('#loading_wrap');
    loading.classList.remove('on');
}

function urlMenuCheck() {
    const menuName = window.location.pathname.split('/');

    if(menuName.length > 2) {
        const ele = document.querySelector('#header .pc_header .left_box .menu_wrap .menu_box[name='+menuName[1]+']');
        if(ele) {
            ele.classList.add("on");
        }
    }
}