<?
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

$menuArr = array();

//로그인 전 메뉴를 추가 합니다.
if(is_null($member)) {
    array_push(
        $menuArr, array(
            "name"=>"로그인",
            "url"=>"javascript:void(0);",
            "event"=>"onclick=\"loginRemote('on');\" "
        ),array(
            "name"=>"회원가입",
            "url"=>"/member/join/"
        )
    );
}

//로그인한 회원에게만 보여줄 메뉴를 추가 합니다.
if(!is_null($member)){
    array_push(
        $menuArr, array(
            "name"=>"내 정보",
            "url"=>"/mypage/mypage/"
        ), array(
            "name"=>"로그아웃",
            "url"=>"javascript:void(0);",
            "event"=>"onclick=\"logoutRemote();\""
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
        "name"=>"샵 찾기",
        "url"=>"/shop/list/"
    ), array (
        "name"=>"샵 리뷰",
        "url"=>"/review/list/"
    ), array (
        "name"=>"공지사항",
        "url"=>"/help/"
    ), array (
        "name"=>"요청하기",
        "url"=>"/board/request/"
    ), array (
        "name"=>"제휴문의",
        "url"=>"/board/contact/"
    )
)

?>