<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

if(!$is_member) {
    echo "<script>myrecordAlert('on', '로그인 후 이용해주세요.', '알림', 'location.href=\'/account/login\'');</script>"; exit;
}

echo css_load('/community/free_board/edit/index.css');
?>


<div class="board_page_header">
    <div class="page_header_inner">
        <p class="page_title_text">자유게시판</p>
        <p class="page_sub_text">자유롭게 이야기를 나눠보세요</p>
    </div>
</div>


<div class="editor_outer_wrap">
    <div class="editor_card">

        <div id="editor_wrap">

            <div id="board_title_wrap">
                <label class="value_title" for="board_title">제목</label>
                <div class="value_box">
                    <input id="board_title" class="edit_input" type="text" placeholder="제목을 입력해 주세요"/>
                </div>
            </div>

            <div id="board_contents_wrap">
                <label class="value_title" for="">내용</label>
                <div class="value_box">
                    <textarea id="board_editor" name="board_editor" style="display: none;"></textarea>
                </div>
            </div>

            <div class="bottom_btn_wrap">
                <button class="list_btn" onclick="goFreeBoardList();">목록</button>
                <button class="edit_btn" onclick="insertBoard();">등록</button>
            </div>

        </div>
        <!-- editor_wrap -->

    </div>
    <!-- editor_card -->
</div>
<!-- editor_outer_wrap -->


<script src="/editor/js/HuskyEZCreator.js"></script>
<?php
echo script_load('/community/free_board/edit/index.js');
?>
<script>
let requestEditors = [];
smartEditor = () => {
    loadingOn();
    nhn.husky.EZCreator.createInIFrame({
        oAppRef: requestEditors,
        elPlaceHolder: "board_editor",
        sSkinURI: "/editor/SmartEditor2Skin.html",
        fCreator: "createSEditor2",
        fOnAppLoad: () => {
            pageInit();
        }
    });
};
window.addEventListener("DOMContentLoaded", () => {
    smartEditor();
});
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>
