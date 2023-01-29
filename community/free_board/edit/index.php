<?
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
include_once($_SERVER['DOCUMENT_ROOT'].'/header.php');   // 헤더파일 로드

if(!$is_member) {
    echo "<script>myrecordAlert('on', '로그인 후 이용해주세요.', '알림', 'location.href=\'/account/login\'');</script>"; exit;
}

echo css_load('/community/free_board/edit/index.css');
?>


<div id="page_title">커뮤니티 ＞ 자유게시판</div>


<div id="editor_wrap">

    <div id="board_title_wrap">
        <label class="value_title" for="board_title">제목</label>
        <div class="value_box">
            <input id="board_title" class="edit_input" type="text" placeholder="제목을 입력해주세요"/>
        </div>
    </div>

    <div id="board_contents_wrap">
        <label class="value_title" for="">내용</label>
        <div class="value_box">
            <textarea id="board_editor" name="board_editor" style="display: none;"></textarea>
        </div>
    </div>

    <div class="bottom_btn_wrap">
        <button class="list_btn" onclick="">목록</button>
        <button class="edit_btn" onclick="">등록</button>
    </div>
</div>
<!-- editor_wrap -->















<script src="/editor/js/HuskyEZCreator.js"></script>
<?
echo script_load('/community/free_board/edit/index.js');
?>
<script>
var requestEditors = [];
nhn.husky.EZCreator.createInIFrame({
    oAppRef: board_editor,
    elPlaceHolder: "board_editor",
    sSkinURI: "/editor/SmartEditor2Skin.html",
    fCreator: "createSEditor2"
});
$(function () {
    
});
</script>
<?
include_once($_SERVER['DOCUMENT_ROOT'].'/footer.php');   // 푸터파일 로드
?>