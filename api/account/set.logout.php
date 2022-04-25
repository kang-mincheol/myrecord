<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');

session_unset(); // 모든 세션변수를 언레지스터 시켜줌
session_destroy(); // 세션해제함

header("Location:/");
?>