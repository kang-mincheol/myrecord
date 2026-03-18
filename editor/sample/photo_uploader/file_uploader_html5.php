<?php
define('SKIP_ACCESS_LOG', true); // 바이너리 body 로그 방지
include_once($_SERVER['DOCUMENT_ROOT'].'/common.php');
ini_set('gd.jpeg_ignore_warning', 1);
$sFileInfo = '';
$headers = array();
	
foreach($_SERVER as $k => $v) {
	if(substr($k, 0, 9) == "HTTP_FILE") {
		$k = substr(strtolower($k), 5);
		$headers[$k] = $v;
	} 
}

$filename     = rawurldecode($headers['file_name']);
$filename_parts = explode('.', $filename);
$filename_ext = strtolower(array_pop($filename_parts));
$allow_file = array("jpg", "jpeg", "png", "bmp", "gif"); 

if(!in_array($filename_ext, $allow_file)) {
	echo "NOTALLOW_".$filename;
} else {
	$file = new stdClass;
	$file->name = date("YmdHis").mt_rand().".".$filename_ext;
	$file->content = file_get_contents("php://input");

	$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/data/community_free_board/';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777, true);
	}

	$newPath = $uploadDir.$file->name;

	if(file_put_contents($newPath, $file->content)) {
		try { resizeImage($newPath, 1000); } catch (Throwable $e) {}

		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$filename;
		$sFileInfo .= "&sFileURL=/data/community_free_board/".$file->name;

		// DB 기록 (실패해도 업로드 응답에 영향 없음)
		try {
			if(!empty($member['id'])) {
				$insert_sql = "
					Insert Into community_free_board_file
					Set account_no = :account_no,
					    file_guid  = :file_guid
				";
				$PDO->execute($insert_sql, [
					':account_no' => $member['id'],
					':file_guid'  => makeGuid()
				]);
			}
		} catch (Exception $e) {}
	}

	echo $sFileInfo;
}

function resizeImage($filePath, $newWidth) {
    list($originalWidth, $originalHeight, $imageType) = getimagesize($filePath);

    if ($originalWidth < $newWidth) {
        return;
    }

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