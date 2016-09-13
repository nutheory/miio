<?
include "../config.php";
//$BASE = ($LOCAL) ? "/websites/kegger/file_storage/" : "/var/www/beta.ikegger.com/file_storage/";
$imagefile = $_GET['file'];
$newwd = $_GET['width'];
$newht = $_GET['height'];

$image_info = getimagesize($imagefile);
if ($image_info['mime'] == "image/jpeg") $newimg = imagecreatefromjpeg($imagefile);
else if ($image_info['mime'] == "image/gif") $newimg = imagecreatefromgif($imagefile);
else if ($image_info['mime'] == "image/png") $newimg = imagecreatefrompng($imagefile);
$resized = imagecreatetruecolor($newwd,$newht);
imagecopyresized($resized,$newimg,0,0,0,0,$newwd,$newht,$image_info[0],$image_info[1]);
header('Content-type: image/jpeg');
imagejpeg($resized);
die;
?>