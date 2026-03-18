<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

class FreeBoard {
    private $id;
    private $account_no;
    private $title;
    private $contents;
    private $view_count;
    private $create_date;
    private $update_date;
    private $is_delete;
    private $delete_date;

    function __construct($freeBoardData) {
        $this->setFreeBoardData($freeBoardData);
    }

    public function setFreeBoardData($freeBoardData) {
        $this->id = $freeBoardData["id"];
        $this->account_no = $freeBoardData["account_no"];
        $this->title = $freeBoardData["title"];
        $this->contents = $freeBoardData["contents"];
        $this->view_count = $freeBoardData["view_count"];
        $this->create_date = $freeBoardData["create_date"];
        $this->update_date = $freeBoardData["update_date"];
        $this->is_delete = $freeBoardData["is_delete"];
        $this->delete_date = $freeBoardData["delete_date"];
    }

    /**
     * 본문 HTML에서 GUID를 추출하여 community_free_board_file.board_id 업데이트
     */
    private static function linkFilesToBoard(int $boardId, string $contents): void {
        global $PDO;

        preg_match_all(
            '#/data/community_free_board/([0-9a-f\-]{36})\.[a-z]+#i',
            $contents,
            $matches
        );

        if (empty($matches[1])) return;

        foreach (array_unique($matches[1]) as $guid) {
            $PDO->execute(
                "Update community_free_board_file
                 Set    board_id = :board_id
                 Where  file_guid = :file_guid
                 And    board_id IS NULL",
                [':board_id' => $boardId, ':file_guid' => $guid]
            );
        }
    }

    public static function insertFreeBoard($data) {
        global $PDO;
        global $member;

        $sql = "
            Insert Into community_free_board
            Set
                account_no = :account_no,
                title = :title,
                contents = :contents,
                view_count = 0
        ";
        $param = array(
            ":account_no" => $member["id"],
            ":title" => $data["title"],
            ":contents" => $data["contents"]
        );

        $boardId = $PDO->execute($sql, $param);

        if ($boardId) {
            FreeBoard::linkFilesToBoard((int)$boardId, $data["contents"]);
        }

        return $boardId;
    }

    /**
     * 자유게시판 update 함수
     * $data
     * id
     * title
     * contents
     */
    public static function updateFreeBoard($data) {
        global $PDO;

        $sql = "
            Update community_free_board
            Set
                title = :title,
                contents = :contents,
                update_date = :update_date
            Where   id = :id
        ";
        $param = array(
            ":title" => $data["title"],
            ":contents" => $data["contents"],
            ":update_date" => date("Y-m-d H:i:s"),
            ":id" => $data["id"]
        );
        $update = $PDO->execute($sql, $param);

        if ($update) {
            FreeBoard::linkFilesToBoard((int)$data["id"], $data["contents"]);
            return true;
        } else {
            return false;
        }
    }

    public static function getFreeBoard($boardId) {
        global $PDO;

        $sql = "
            Select  *
            From    community_free_board
            Where   id = :id
        ";

        $param = array(
            ":id" => $boardId
        );

        return $PDO->fetch($sql, $param);
    }

    /**
     * 자유게시판 글이 존재하는지 확인
     */
    public static function hasFreeBoard($boardId) {
        global $PDO;

        $sql = "
            Select  count(*) as cnt
            From    community_free_board
            Where   id = :id
            And     is_delete = 0
        ";

        $param = array(
            ":id" => $boardId
        );

        $check = $PDO->fetch($sql, $param)["cnt"];

        if ($check > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 자유게시판 작성자 본인인지 확인하는 함수
     */
    public static function writerVerify($boardId) {
        global $PDO;
        global $member;

        if (is_null($member)) {
            return false;
        }
        
        $sql = "
            Select  account_no
            From    community_free_board
            Where   id = :id
            And     is_delete = 0
        ";

        $param = array(
            ":id" => $boardId
        );

        $freeBoardData = $PDO->fetch($sql, $param);

        if ($member["id"] === $freeBoardData["account_no"]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 자유게시판 list data 함수
     * $param
     * (필수) pageIndex
     * (필수) pageRow
     * searchKey
     * searchValue
     */
    public static function getFreeBoardList($param) {
        global $PDO;

        $sql_param = array();

        $start_row = 0;
        if (empty($param["pageIndex"])) {
            $start_row = 0;
        } else {
            if ($param["pageIndex"] === 1) {
                $start_row = 0;
            } else {
                $start_row = ($param["pageIndex"] - 1) * $param["pageRow"];
            }
        }

        $and_query = "";
        if (isset($param["searchKey"]) && isset($param["searchValue"])) {
            if ($param["searchKey"] === "title") {
                $and_query = "
                    And T1.title like :searchValue
                ";
                $sql_param[":searchValue"] = $param["searchValue"];
            } else if ($param["searchKey"] === "contents") {
                $and_query = "
                    And T1.contents like :searchValue
                ";
                $sql_param[":searchValue"] = $param["searchValue"];
            } else if ($param["searchKey"] === "writer") {
                $and_query = "
                    And Acc.nickname = :searchValue
                ";
                $sql_param[":searchValue"] = $param["searchValue"];
            }
        }

        $sql = "
            Select  T1.id, T1.account_no, T1.title, T1.create_date, T1.is_delete,
                    Acc.user_nickname, T1.view_count
            From    community_free_board T1

            Inner Join  Account Acc
            On      T1.account_no = Acc.id
            
            Where   T1.is_delete = 0
            {$and_query}
            Order by T1.id Desc
            Limit   {$start_row}, {$param["pageRow"]}
        ";
        $listData = $PDO->fetchAll($sql, $sql_param);

        $totalCountSql = "
            Select  count(*) as cnt
            From    community_free_board T1

            Inner Join  Account Acc
            On      T1.account_no = Acc.id

            Where   T1.is_delete = 0
            {$and_query}
        ";
        $totalCount = $PDO->fetch($totalCountSql, $sql_param)["cnt"];
        $totalPage = ceil($totalCount / $param["pageRow"]);

        $returnArray = array(
            "list" => $listData,
            "page" => array(
                "totalPage" => $totalPage
            )
        );

        return $returnArray;
    }

    public static function getFreeBoardListPage($data) {

    }

    // =============================================
    // 댓글
    // =============================================

    /**
     * 댓글 목록 조회
     */
    public static function getComments(int $boardId): array {
        global $PDO;

        $sql = "
            Select  C.id, C.contents, C.account_no, C.create_datetime,
                    A.user_nickname
            From    community_free_board_comment C
            Inner Join Account A On C.account_no = A.id
            Where   C.board_id = :board_id
            And     C.is_delete = 0
            Order by C.id Asc
        ";
        $param = [":board_id" => $boardId];

        return $PDO->fetchAll($sql, $param);
    }

    /**
     * 댓글 수 조회
     */
    public static function getCommentCount(int $boardId): int {
        global $PDO;

        $sql = "
            Select count(*) as cnt
            From   community_free_board_comment
            Where  board_id = :board_id
            And    is_delete = 0
        ";
        $param = [":board_id" => $boardId];

        return (int)$PDO->fetch($sql, $param)["cnt"];
    }

    /**
     * 댓글 작성
     */
    public static function insertComment(int $boardId, string $contents): int {
        global $PDO;
        global $member;

        $sql = "
            Insert Into community_free_board_comment
            Set board_id   = :board_id,
                account_no = :account_no,
                contents   = :contents
        ";
        $param = [
            ":board_id"   => $boardId,
            ":account_no" => $member["id"],
            ":contents"   => $contents
        ];

        return $PDO->execute($sql, $param);
    }

    /**
     * 댓글 존재 + 본인 여부 확인
     */
    public static function isCommentOwner(int $commentId): bool {
        global $PDO;
        global $member;

        if (is_null($member)) return false;

        $sql = "
            Select account_no
            From   community_free_board_comment
            Where  id = :id
            And    is_delete = 0
        ";
        $param = [":id" => $commentId];

        $row = $PDO->fetch($sql, $param);
        if (!$row) return false;

        return (int)$member["id"] === (int)$row["account_no"];
    }

    /**
     * 댓글 소프트 삭제
     */
    public static function deleteComment(int $commentId): void {
        global $PDO;

        $sql = "
            Update community_free_board_comment
            Set    is_delete = 1
            Where  id = :id
        ";
        $param = [":id" => $commentId];

        $PDO->execute($sql, $param);
    }


    /**
     * 자유게시판 view data get 함수
     */
    public static function getFreeBoardViewData($boardId) {
        global $PDO;

        $sql = "
            Select  T1.title, T1.contents, T1.create_date, T2.user_nickname, T2.id as account_id
            From    community_free_board T1

            Inner Join Account T2
            On      T1.account_no = T2.id

            Where   T1.id = :id
            And     T1.is_delete = 0
        ";
        $sql_param[":id"] = $boardId;

        return $PDO -> fetch($sql, $sql_param);
    }

    /**
     * 자유게시판 글 삭제
     * - 작성자 본인만 가능
     * - 관련 댓글 소프트 삭제
     * - 게시글 소프트 삭제
     * ※ 물리 파일 삭제는 삭제 후 1년 경과 게시글 일괄 배치 처리에서 수행
     */
    public static function deleteBoard(int $boardId): array {
        global $PDO;

        $returnArray = [
            "code" => "SUCCESS",
            "msg"  => "삭제되었습니다."
        ];

        if (!FreeBoard::writerVerify($boardId)) {
            $returnArray["code"] = "WRITER_ONLY";
            $returnArray["msg"]  = "작성자만 삭제할 수 있습니다.";
            return $returnArray;
        }

        // 1. 관련 댓글 소프트 삭제
        $PDO->execute(
            "Update community_free_board_comment Set is_delete = 1 Where board_id = :board_id",
            [":board_id" => $boardId]
        );

        // 2. 게시글 소프트 삭제
        $PDO->execute(
            "Update community_free_board Set is_delete = 1, delete_date = Now() Where id = :id And is_delete = 0",
            [":id" => $boardId]
        );

        return $returnArray;
    }

    /**
     * 자유게시판 edit data get 함수
     */
    public static function getFreeBoardEditData($boardId) {
        global $PDO;

        $returnArray = array(
            "code"=>"SUCCESS",
            "msg"=>"정상 처리되었습니다"
        );

        // 작성자 본인인지 체크
        $writerCheck = FreeBoard::writerVerify($boardId);

        if ($writerCheck === false) {
            $returnArray["code"] = "WRITER_ONLY";
            $returnArray["msg"] = "작성자만 수정 가능합니다.";
            return $returnArray;
        }

        $sql = "
            Select  *
            From    community_free_board
            Where   id = :id
            And     is_delete = 0
        ";

        $param = array(
            ":id" => $boardId
        );

        $boardEditData = $PDO->fetch($sql, $param);

        if ($boardEditData) {
            $returnArray["data"] = array(
                "title" => $boardEditData["title"],
                "contents" => $boardEditData["contents"]
            );

            return $returnArray;
        }

        $returnArray["code"] = "EMPTY";
        $returnArray["msg"] = "데이터가 없습니다.";
        return $returnArray;
    }
}

?>