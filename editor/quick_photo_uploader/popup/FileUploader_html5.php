<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');

$sFileInfo = '';
$headers = array();

foreach ($_SERVER as $k => $v){
    if (substr($k, 0, 9) == "HTTP_FILE") {
        $k = substr(strtolower($k), 5);
        $headers[$k] = $v;
    }
}

$file = new stdClass;
$guid = GUID();
$file->name = $guid;
$file->size = $headers['file_size'];
var_dump($headers);
$file->content = file_get_contents("php://input");

$fileRealName = $headers['file_name'];
$extension = $headers['file_type'];
$fileType = explode('/', $headers['file_type']);
$fileType = $fileType[count($fileType) - 1];
$fileSize = $headers['file_size'];

$newPath = $_SERVER['DOCUMENT_ROOT'].'/files/request/'.iconv("utf-8", "cp949", $file->name);

if (file_put_contents($newPath, $file->content)) {
    $sFileInfo .= "&bNewLine=true";
    $sFileInfo .= "&sFileName=".$file->name;
    $sFileInfo .= "&sFileURL=/files/request/".$file->name;
}

// Files Insert
sql_query("INSERT INTO Files (guid, group_key, file_name, extension, file_type, file_size, create_date, id_users) VALUES ('{$guid}', '1', '{$fileRealName}', '{$extension}', '{$fileType}', '{$fileSize}', NOW(), '{$member['id']}')");

// 파일정보를 출력해줘야 이미지가 들어감
echo $sFileInfo;

function GUID() {
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
?>