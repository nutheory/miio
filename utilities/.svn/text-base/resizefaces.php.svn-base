<?
$SIZE = 90;
$imagefile = $_GET['file'];
$image_info = getimagesize($imagefile);
if ($image_info['mime'] == "image/jpeg") $newimg = imagecreatefromjpeg($imagefile);
else if ($image_info['mime'] == "image/gif") $newimg = imagecreatefromgif($imagefile);
else if ($image_info['mime'] == "image/png") $newimg = imagecreatefrompng($imagefile);
$ratio = 1;
if ($image_info[1] > $SIZE)
{
  $ratio = $SIZE / $image_info[1];
}
if ($image_info[0] * $ratio > $SIZE)
{
  $ratio = $SIZE / $image_info[0];
}
$ht = floor($image_info[1] * $ratio);
$wd = floor($image_info[0] * $ratio);
$resized = imagecreatetruecolor($wd,$ht);
imagecopyresized($resized,$newimg,0,0,0,0,$wd,$ht,$image_info[0],$image_info[1]);
header('Content-type: image/jpeg');
imagejpeg($resized);
die;
?>