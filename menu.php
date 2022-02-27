<?
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

$menuArr = array();

//로그인 전 메뉴를 추가 합니다.
if(is_null($member)) {
    array_push(
        $menuArr, array(
            "name"=>"로그인",
            "url"=>"/account/login"
        ),array(
            "name"=>"회원가입",
            "url"=>"/account/join/"
        )
    );
}

//로그인한 회원에게만 보여줄 메뉴를 추가 합니다.
if(!is_null($member)){
    array_push(
        $menuArr, array(
            "name"=>"마이페이지",
            "url"=>"/mypage/mypage/"
        ), array(
            "name"=>"로그아웃",
            "url"=>"/"
        )
    );
}

//관리자에게만 보여줄 메뉴를 추가 합니다.
if(!is_null($member) && $member["is_admin"]){
    array_push(
        $menuArr, array(
            "name"=>"관리자",
            "url"=>"/admin/"
        )
    );
}

$mainMenuArr = array();

array_push(
    $mainMenuArr, array(
        "name"=>"커뮤니티",
        "url"=>"/community/freeboard/",
        "sub_menu"=>
            array(
                "sub_name"=>"자유게시판",
                "url"=>"/community/free_board/"
            ),
            array(
                "sub_name"=>"득근일지",
                "url"=>"/community/muscle_gain/"
            )
    ), array (
        "name"=>"마이레코드",
        "url"=>"/record/record_main/"
    ), array (
        "name"=>"고객센터",
        "url"=>"/help/notice"
    )
)

?>