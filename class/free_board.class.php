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

    public function insertFreeBoard() {
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
}

?>