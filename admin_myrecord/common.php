<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/common.php");

if(!$is_admin) {
    header("Location: /");
    exit;
}
?>
