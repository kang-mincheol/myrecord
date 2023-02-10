<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드

$sFileInfo = '';
$headers = array();
	
foreach($_SERVER as $k => $v) {
	if(substr($k, 0, 9) == "HTTP_FILE") {
		$k = substr(strtolower($k), 5);
		$headers[$k] = $v;
	} 
}

$filename = rawurldecode($headers['file_name']);
$filename_ext = strtolower(array_pop(explode('.',$filename)));
$allow_file = array("jpg", "png", "bmp", "gif"); 

if(!in_array($filename_ext, $allow_file)) {
	echo "NOTALLOW_".$filename;
} else {
	$file = new stdClass;
	$file->name = date("YmdHis").mt_rand().".".$filename_ext;
	$file->content = file_get_contents("php://input");

	$uploadDir = '../../../data/community_free_board';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777);
	}
	
	$newPath = $uploadDir.$file->name;

	if(file_put_contents($newPath, $file->content)) {
		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$filename;
		$sFileInfo .= "&sFileURL=upload/".$file->name;

		$insert_sql = "
			Insert Into	community_free_board_file
			Set
				account_no = :account_no,
				file_guid = :file_guid
		";

		$param = array(
			":account_no" => $member["id"],
			":file_guid" => makeGuid()
		);
		$PDO -> execute($insert_sql, $param);
	}
	
	echo $sFileInfo;
}


?>