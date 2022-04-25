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