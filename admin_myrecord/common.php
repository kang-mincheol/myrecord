<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/common.php");

if(!$is_admin) {
    header("Location: /");
    exit;
}

// ===== Admin Classes =====
$_admin_class_dir = $_SERVER['DOCUMENT_ROOT'] . '/admin_myrecord/class/';
include_once($_admin_class_dir . 'class.AdminDashboard.php');
include_once($_admin_class_dir . 'class.AdminAccount.php');
include_once($_admin_class_dir . 'class.AdminRecord.php');
include_once($_admin_class_dir . 'class.AdminFreeBoard.php');
include_once($_admin_class_dir . 'class.AdminSystem.php');
include_once($_admin_class_dir . 'class.AdminAccessLog.php');
