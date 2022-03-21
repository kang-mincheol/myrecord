function recordSubMenuCheck() {
    var url = window.location.href;

    if(url.indexOf('landing') != -1) {
        $("#record_sub_menu_container .record_sub_menu_btn.landing").addClass("on");
    } else if(url.indexOf('squat') != -1) {
        $("#record_sub_menu_container .record_sub_menu_btn.squat").addClass("on");
    } else if(url.indexOf('benchpress') != -1) {
        $("#record_sub_menu_container .record_sub_menu_btn.benchpress").addClass("on");
    } else if(url.indexOf('deadlift') != -1) {
        $("#record_sub_menu_container .record_sub_menu_btn.deadlift").addClass("on");
    } else if(url.indexOf('my_record') != -1) {
        $("#record_sub_menu_container .record_sub_menu_btn.my_record").addClass("on");
    }
}

function recordSubMenuSwiper() {
    var swiper = new Swiper("#record_sub_menu_container", {
        slidesPerView: "auto",
        spaceBetween: 10,
        freeMode: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
}