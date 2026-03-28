<?php

class BoardsController {

    /**
     * GET /api/v1/boards?pageIndex=1&pageRow=10
     */
    public static function list(array $params): void {
        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $data = [
            "pageIndex" => isset($_GET["pageIndex"]) ? (int)$_GET["pageIndex"] : 1,
            "pageRow"   => isset($_GET["pageRow"])   ? (int)$_GET["pageRow"]   : 10,
        ];

        if ($data["pageIndex"] < 1 || $data["pageRow"] < 1) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data = cleansingParams($data);

        $freeBoardListData = FreeBoard::getFreeBoardList($data);

        if (count($freeBoardListData) === 0) {
            $returnArray["code"] = "EMPTY";
            $returnArray["msg"]  = "검색결과가 없습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        foreach ($freeBoardListData["list"] as $value) {
            $returnArray["data"]["list"][] = [
                "id"         => $value["id"],
                "title"      => $value["title"],
                "nickname"   => $value["user_nickname"],
                "view_count" => $value["view_count"],
                "write_date" => date("Y.m.d", strtotime($value["create_date"])),
            ];
        }

        $returnArray["data"]["page"] = $freeBoardListData["page"];

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/boards/{id}
     */
    public static function view(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $boardId = (int)$params["id"];

        if ($boardId <= 0) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $freeBoardViewData = FreeBoard::getFreeBoardViewData($boardId);

        if (!$freeBoardViewData) {
            $returnArray["code"] = "NOT_FOUND";
            $returnArray["msg"]  = "게시글을 찾을 수 없습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $returnArray["data"] = [
            "title"         => $freeBoardViewData["title"],
            "contents"      => stripslashes($freeBoardViewData["contents"]),
            "user_nickname" => $freeBoardViewData["user_nickname"],
            "create_date"   => $freeBoardViewData["create_date"],
            "is_write"      => $is_member ? ($member["id"] === $freeBoardViewData["account_id"]) : false,
        ];

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/boards/{id}/edit
     */
    public static function editData(array $params): void {
        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $boardId = (int)$params["id"];

        if ($boardId <= 0) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $boardEditData = FreeBoard::getFreeBoardEditData($boardId);

        echo json_encode($boardEditData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * POST /api/v1/boards
     * Body: { "title": string, "contents": string }
     */
    public static function create(array $params): void {
        global $is_member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["title", "contents"])) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data = cleansingParams($data);

        if (empty($data["title"])) {
            $returnArray["code"] = "TITLE";
            $returnArray["msg"]  = "제목을 입력해 주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (empty($data["contents"]) || $data["contents"] === "<p> </p>") {
            $returnArray["code"] = "CONTENT";
            $returnArray["msg"]  = "내용을 입력해 주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $insert = FreeBoard::insertFreeBoard($data);

        if (!$insert) {
            Slack::send(SLACK_URL_ERROR, "자유게시판 글 등록 실패\n{$_SERVER["REQUEST_URI"]}");
            $returnArray["code"] = "ERROR";
            $returnArray["msg"]  = "글 등록에 실패했습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        http_response_code(201);
        $returnArray["board_id"] = $insert;
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * PUT /api/v1/boards/{id}
     * Body: { "title": string, "contents": string }
     */
    public static function update(array $params): void {
        global $is_member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        if (!$is_member) {
            $returnArray["code"] = "MEMBER_ONLY";
            $returnArray["msg"]  = "로그인 후 이용해주세요";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["title", "contents"])) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data     = cleansingParams($data);
        $data["id"] = (int)$params["id"]; // 경로 파라미터에서 id 주입

        $hasFreeBoard = FreeBoard::hasFreeBoard($data["id"]);
        if ($hasFreeBoard === false) {
            $returnArray["code"] = "EMPTY";
            $returnArray["msg"]  = "존재하지 않는 글입니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $writerCheck = FreeBoard::writerVerify($data["id"]);
        if ($writerCheck === false) {
            $returnArray["code"] = "WRITER_ERROR";
            $returnArray["msg"]  = "작성자 본인이 아닙니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (empty($data["title"])) {
            $returnArray["code"] = "TITLE";
            $returnArray["msg"]  = "제목을 입력해 주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (empty($data["contents"]) || $data["contents"] === "<p> </p>") {
            $returnArray["code"] = "CONTENTS";
            $returnArray["msg"]  = "내용을 입력해 주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $freeBoardData             = FreeBoard::getFreeBoard($data["id"]);
        $freeBoardData["title"]    = $data["title"];
        $freeBoardData["contents"] = $data["contents"];
        $update = FreeBoard::updateFreeBoard($freeBoardData);

        if ($update === false) {
            $returnArray["code"] = "UPDATE_ERROR";
            $returnArray["msg"]  = "자유게시판 수정에 실패했습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * DELETE /api/v1/boards/{id}
     */
    public static function delete(array $params): void {
        global $is_member;

        $returnArray = ["code" => "SUCCESS", "msg" => "게시글이 삭제되었습니다."];

        if (!$is_member) {
            $returnArray["code"] = "LOGIN_REQUIRED";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $boardId = (int)$params["id"];

        if ($boardId <= 0) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $result = FreeBoard::deleteBoard($boardId);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/boards/{id}/comments
     */
    public static function listComments(array $params): void {
        global $is_member, $member;

        $returnArray = ["code" => "SUCCESS", "msg" => "정상 처리되었습니다"];

        $boardId = (int)$params["id"];

        if ($boardId <= 0) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (!FreeBoard::hasFreeBoard($boardId)) {
            $returnArray["code"] = "NOT_FOUND";
            $returnArray["msg"]  = "게시글을 찾을 수 없습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $comments = FreeBoard::getComments($boardId);
        $count    = FreeBoard::getCommentCount($boardId);

        $list = [];
        foreach ($comments as $c) {
            $list[] = [
                "id"              => (int)$c["id"],
                "contents"        => $c["contents"],
                "user_nickname"   => $c["user_nickname"],
                "create_datetime" => date("Y.m.d H:i", strtotime($c["create_datetime"])),
                "is_mine"         => $is_member ? ((int)$member["id"] === (int)$c["account_no"]) : false,
            ];
        }

        $returnArray["data"] = [
            "count" => $count,
            "list"  => $list,
        ];

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * POST /api/v1/boards/{id}/comments
     * Body: { "contents": string }
     */
    public static function createComment(array $params): void {
        global $is_member;

        $returnArray = ["code" => "SUCCESS", "msg" => "댓글이 등록되었습니다."];

        if (!$is_member) {
            $returnArray["code"] = "LOGIN_REQUIRED";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $boardId = (int)$params["id"];

        $data = json_decode(file_get_contents('php://input'), true);

        if (is_null($data) || !checkParams($data, ["contents"])) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $data     = cleansingParams($data);
        $contents = trim($data["contents"]);

        if ($boardId <= 0 || !FreeBoard::hasFreeBoard($boardId)) {
            $returnArray["code"] = "NOT_FOUND";
            $returnArray["msg"]  = "게시글을 찾을 수 없습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (mb_strlen($contents) === 0) {
            $returnArray["code"] = "EMPTY_CONTENTS";
            $returnArray["msg"]  = "댓글 내용을 입력해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (mb_strlen($contents) > 500) {
            $returnArray["code"] = "TOO_LONG";
            $returnArray["msg"]  = "댓글은 500자 이내로 입력해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $commentId = FreeBoard::insertComment($boardId, $contents);

        http_response_code(201);
        $returnArray["comment_id"] = $commentId;
        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }

    /**
     * DELETE /api/v1/boards/{id}/comments/{cid}
     */
    public static function deleteComment(array $params): void {
        global $is_member;

        $returnArray = ["code" => "SUCCESS", "msg" => "댓글이 삭제되었습니다."];

        if (!$is_member) {
            $returnArray["code"] = "LOGIN_REQUIRED";
            $returnArray["msg"]  = "로그인 후 이용해주세요.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        $commentId = (int)$params["cid"];

        if ($commentId <= 0) {
            $returnArray["code"] = "PARAMS";
            $returnArray["msg"]  = "필수 파라미터가 존재하지 않습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        if (!FreeBoard::isCommentOwner($commentId)) {
            $returnArray["code"] = "FORBIDDEN";
            $returnArray["msg"]  = "본인 댓글만 삭제할 수 있습니다.";
            echo json_encode($returnArray, JSON_UNESCAPED_UNICODE); return;
        }

        FreeBoard::deleteComment($commentId);

        echo json_encode($returnArray, JSON_UNESCAPED_UNICODE);
    }
}
