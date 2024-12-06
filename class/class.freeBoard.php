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

    public static function insertFreeBoard() {
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
            ":title" => $this->title,
            ":contents" => $this->contents
        );

        $insert = $PDO->execute($sql, $param);
        if($insert) {
            return true;
        } else {
            return false;
        }
    }

    public function updateFreeBoard() {
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
            ":title" => $this->title,
            ":contents" => $this->contents,
            ":update_date" => date("Y-m-d H:i:s"),
            ":id" => $this->id
        );
        $update = $PDO->execute($sql, $param);

        if($update) {
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
            Select  T1.id, T1.account_no, T1.title, T1.create_date, T1.is_delete
            From    community_free_board T1

            Inner Join  Account Acc
            On      T1.account_no = Acc.id
            
            Where   T1.is_delete = 0
            {$and_query}
            Order by T1.id Desc
            Limit   {$start_row}, {$param["pageRow"]}
        ";

        return $get_free_board_list = $PDO -> fetchAll($sql, $sql_param);
    }
}

?>