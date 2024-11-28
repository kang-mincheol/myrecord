function insertBoard() {
    var title = $("#board_title").val();
    var contents = $("#board_editor").val();
}

submitPost = () => {
    requestEditors.getById["board_editor"].exec("UPDATE_CONTENTS_FIELD", []);
    let content = document.getElementById("board_editor").value;
  
    if(content == '') {
        alert("내용을 입력해주세요.");
        oEditors.getById["board_editor"].exec("FOCUS");
        return;
    } else {
        console.log(content);
    }
}