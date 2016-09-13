<?php
$URL = $_GET['url'];
if (substr($URL,0,7)=='http://')
{
  $URL = substr($URL,7);
  $pre = 'http://';
}
else if (substr($URL,0,8)=='https://')
{
  $URL = substr($URL,8);
  $pre = 'https://';
}
else $pre = 'http://';

$imagefile = "thumbs/".urlencode($URL).".jpg";
if (file_exists($imagefile))
{
  $displayimg = imagecreatefromjpeg($imagefile);
  header('Content-type: image/jpeg');
  imagejpeg($displayimg);
}
else
{
  $browser = new COM("InternetExplorer.Application");
  $handle = $browser->HWND;
  $browser->Visible = true;
  $browser->FullScreen = true; 
  $browser->Navigate($pre.$URL);
  
  while ($browser->Busy) {
    com_message_pump(4000);
  }
  $rawimg = imagegrabwindow($handle);
  $browser->Quit();
  
  $displayimg = imagecreatetruecolor(320,240);
  imagecopyresampled ($displayimg, $rawimg, 0, 0, 0, 0, 320, 240, imagesx($rawimg), imagesy($rawimg));
  imagejpeg($displayimg,$imagefile);
  header('Content-type: image/jpeg');
  imagejpeg($displayimg);
}

?>