function headerMenuHover(name) {
    console.log(name);
    var check = $("#header .sub_menu_container .sub_menu_wrap[name="+name+"]").length;

    $("#header .sub_menu_container .sub_menu_wrap").removeClass("on");
    if(check) {
        $("#header .sub_menu_container .sub_menu_wrap[name="+name+"]").addClass("on");
    }
}

function headerSubMenuOut() {
    $("#header .sub_menu_container .sub_menu_wrap").removeClass("on");
}