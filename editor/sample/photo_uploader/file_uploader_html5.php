<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');   // 기본파일 로드
ini_set('gd.jpeg_ignore_warning', 1);
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
$allow_file = array("jpg", "jpeg", "png", "bmp", "gif"); 

if(!in_array($filename_ext, $allow_file)) {
	echo "NOTALLOW_".$filename;
} else {
	$file = new stdClass;
	$file->name = date("YmdHis").mt_rand().".".$filename_ext;
	$file->content = file_get_contents("php://input");

	$uploadDir = '../../../data/community_free_board/';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777);
	}
	
	$newPath = $uploadDir.$file->name;

	if(file_put_contents($newPath, $file->content)) {
		// 이미지 리사이징 추가
		resizeImage($newPath, 1000); // 1000px로 리사이즈

		// echo "1"; exit;
		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$filename;
		$sFileInfo .= "&sFileURL=/data/community_free_board/".$file->name;

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

function makeGuid() {
    return sprintf('%08x-%04x-%04x-%04x-%04x%08x',
        mt_rand(0, 0xffffffff),
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff), mt_rand(0, 0xffffffff)
    );
}

function resizeImage($filePath, $newWidth) {
    list($originalWidth, $originalHeight, $imageType) = getimagesize($filePath);

    $newHeight = ($originalHeight / $originalWidth) * $newWidth;

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($filePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($filePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($filePath);
            break;
        default:
            return false; // 지원하지 않는 이미지 형식
    }

    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled(
        $resizedImage, $sourceImage,
        0, 0, 0, 0,
        $newWidth, $newHeight,
        $originalWidth, $originalHeight
    );

    // 원본 파일에 덮어쓰기
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($resizedImage, $filePath, 90); // 품질 90
            break;
        case IMAGETYPE_PNG:
            imagepng($resizedImage, $filePath);
            break;
        case IMAGETYPE_GIF:
            imagegif($resizedImage, $filePath);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($resizedImage);
}
?>