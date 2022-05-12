<?php
class DB extends PDO {
    function __construct($dsn, $user, $pw) {
        parent::__construct($dsn, $user, $pw);
        parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    // 단일 row 가져올 때 사용
    public function fetch($sql, $params = array()) {
        $rs = $this->queryAll($sql, $params);
        return $rs->fetch(PDO::FETCH_ASSOC);
    }

    // 여러 row 가져올 때 사용
    public function fetchAll($sql, $params = array()) {
        $rs = $this->queryAll($sql, $params);
        return $rs->fetchAll(PDO::FETCH_ASSOC);
    }

    // SELECT 시 사용 
    private function queryAll($sql, $params = array()) {
        $rs = parent::prepare($sql);
        $rs->execute($params);
        return $rs;
    }

    // INSERT, UPDATE, DELETE 작업 시 사용: return boolean
    public function execute($sql, $params = array()) {
        $rs = parent::prepare($sql);
        $result = $rs->execute($params);
        // transaction 처리 중 쿼리 실패 시 ROLLBACK 처리
        if (!$result && parent::inTransaction()) {
            parent::rollback();
        }
        else{
            $idx = parent::lastInsertId();
            $result = $idx ? $idx : $result;
        }
        return $result;
    }
}