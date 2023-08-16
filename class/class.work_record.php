<?php
if (!defined('NO_ALONE')) exit; // 개별 페이지 접근 불가

class WorkRecord {
    private $id;
    private $account_id;
    private $record_type;
    private $record_weight_type;
    private $create_datetime;
    private $update_datetime;

    private function __construct($data) {
        $this -> id = $data["id"];
        $this -> account_id = $data["account_id"];
        $this -> record_type = $data["record_type"];
        $this -> record_weight_type = $data["record_weight_type"];
        $this -> create_datetime = $data["create_datetime"];
        $this -> update_datetime = $data["update_datetime"];
    }

    
}


?>