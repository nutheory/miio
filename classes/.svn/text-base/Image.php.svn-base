<?php
/*#####*/
class Image
{
  static function resize($image,$maxht,$maxwd)
  {/*#####*/
    $image = str_replace(LOC,BASE,$image);
    $size = getimagesize($image);
    if (!$size) return false;
    $ht = $size[1];
    $wd = $size[0];
    $ratio = 1;
    if ($ht > $maxht)
    {
      $ratio = $maxht/$ht;
      $w = floor($wd*$ratio);
      if ($w > $maxwd)
      {
        $ratio = $maxwd/$wd;
      }
    }
    else if ($wd > $maxwd)
    {
      $ratio = $maxwd/$wd;
    }
    $h = floor($ratio * $ht);
    $w = floor($ratio * $wd);
    $dims = array("ht"=>$h,"wd"=>$w,"original_ht"=>$size[1],"original_wd"=>$size[0]);
    return $dims;
  }

  static function sizeImage($ht,$wd,$maxht,$maxwd)
  {/*#####*/
    $ratio = 1;
    if ($ht > $maxht)
    {
      $ratio = $maxht/$ht;
      $w = floor($wd*$ratio);
      if ($w > $maxwd)
      {
        $ratio = $maxwd/$wd;
      }
    }
    else if ($wd > $maxwd)
    {
      $ratio = $maxwd/$wd;
    }
    $h = floor($ratio * $ht);
    $w = floor($ratio * $wd);
    $dims = array("ht"=>$h,"wd"=>$w,"original_ht"=>$ht,"original_wd"=>$wd);
    return $dims;
  }

  static function saveFromTemp($photo,$type,$delete=true,$groupid)
  {/*#####*/
    // transfer temp image to permanent storage and get URL
    global $User;
    $ext = strtolower(substr($photo,strripos($photo,'.')));
    $tempfile = $photo;
    if ($type=='profile_avatar')
    {
      $maxheight = AVATAR_SIZE;
      $maxwidth = AVATAR_SIZE;
      $imagefile = BASE . 'file_temp/'.$tempfile;
      $image_info = getimagesize($imagefile);
      if ($image_info['mime'] == "image/jpeg") $newimg = imagecreatefromjpeg($imagefile);
      else if ($image_info['mime'] == "image/gif") $newimg = imagecreatefromgif($imagefile);
      else if ($image_info['mime'] == "image/png") $newimg = imagecreatefrompng($imagefile);

      $newsize = Image::resize($imagefile,$maxheight,$maxwidth);
      $resized = imagecreatetruecolor($maxwidth,$maxheight);
      $backgroundfill = imagecolorallocate($resized,255,255,255);
      imagefill($resized,0,0,$backgroundfill);
      if ($newsize['wd']<$maxwidth) $x = floor(($maxwidth-$newsize['wd'])/2);
      else $x = 0;
      if ($newsize['ht']<$maxheight) $y = floor(($maxheight-$newsize['ht'])/2);
      else $y = 0;
      imagecopyresampled($resized, $newimg, $x, $y, 0, 0, $newsize['wd'], $newsize['ht'], $image_info[0], $image_info[1]);
    }
    if (LOCAL)
    {
      $now = time();
      if ($type=='profile') { $filename = $User->id.'.'.$now.$ext; $dest = 'profile_photos/'.$filename; }
      else if ($type=='group') { $filename = $groupid.'.'.$now.$ext; $dest = 'profile_photos/'.$filename; }
      else if ($type=='group_avatar') { $filename = $groupid.'.'.$now.$ext;  $dest = 'avatars/'.$filename; }
      else if ($type=='avatar' || $type=='profile_avatar') { $filename = $User->id.'.'.$now.$ext; $dest = 'avatars/'.$filename; }
      else { $filename = $User->id.'.'.microtime(true) . $ext; $dest = 'file_storage/'.$filename; }
      if ($type=='profile_avatar')
      {
        if ($image_info['mime'] == "image/jpeg") imagejpeg($resized,$dest,100);
        else if ($image_info['mime'] == "image/gif") imagegif($resized,$dest);
        else if ($image_info['mime'] == "image/png") imagepng($resized,$dest,0);
      }
      else copy (BASE . 'file_temp/'.$tempfile,BASE.$dest);
      if ($delete) unlink(BASE . 'file_temp/'.$tempfile);
    }
    else if (HOST=='beta.ikegger.com')
    {
      $now = time();
      if ($type=='profile') { $filename = $User->id.'.'.$now.$ext; $dest = 'profile_photos/'.$filename; }
      else if ($type=='group') { $filename = $groupid.'.'.$now.$ext; $dest = 'profile_photos/'.$filename; }
      else if ($type=='group_avatar') { $filename = $groupid.'.'.$now.$ext;  $dest = 'avatars/'.$filename; }
      else if ($type=='avatar' || $type=='profile_avatar') { $filename = $User->id.'.'.$now.$ext; $dest = 'avatars/'.$filename; }
      else { $filename = $User->id.'.'.microtime(true) . $ext; $dest = 'file_storage/'.$filename; }
      if ($type=='profile_avatar')
      {
        if ($image_info['mime'] == "image/jpeg") imagejpeg($resized,$dest,100);
        else if ($image_info['mime'] == "image/gif") imagegif($resized,$dest);
        else if ($image_info['mime'] == "image/png") imagepng($resized,$dest,0);
      }
      else copy (BASE . 'file_temp/'.$tempfile,BASE.$dest);
      if ($delete) unlink(BASE . 'file_temp/'.$tempfile);
    }
    else
    {
      // NOT DONE
      // live site upload
      $image_info = TEMP_PHOTO_PATH.$tempfile;
      $dest = PHOTO_PATH.$permfile;
      $ftp = ftp_connect(PHOTO_SERVER,21);
      $login = ftp_login($ftp,'webuser','d#fggkfdXZpz0z');
      // save back to temp file

      $success = ftp_put($ftp,$dest,$filename,FTP_BINARY);
      $filename = $saved_filename;
    }
    return $filename;
  }

  static function save($filename,$filesource,$maxwidth,$maxheight,$imagetype)
  {/*#####*/
    $mimetypes = array("image/jpeg","image/gif","image/png");

    $image_info = getimagesize($filesource['tmp_name']);
    $file_size = filesize($filesource['tmp_name']);
    $response = array();
    if ($filesource['error'] == UPLOAD_ERR_NO_FILE)
    {
      $response['error'] = "No File";
    }
    else if (!in_array($image_info['mime'],$mimetypes))
    {
      $response['error'] = "File is not a valid image format";
    }
    else if ($file_size > PHOTO_MAX_FILE_SIZE)
    {
      $response['error'] = "Image file is larger than " . PHOTO_MAX_FILE_SIZE_TEXT;
    }

    else if ($filesource['error'] > 0)
    {
      $response['error'] = "An unknown error occured";
    }
    else
    {
      $filetype = explode('\.',$filesource['name']);
      $saved_filename = $filename . '.' . $filetype[count($filetype)-1];

      // create a temp image file for use later
      if ($image_info['mime'] == "image/jpeg") $newimg = imagecreatefromjpeg($filesource['tmp_name']);
      else if ($image_info['mime'] == "image/gif") $newimg = imagecreatefromgif($filesource['tmp_name']);
      else if ($image_info['mime'] == "image/png") $newimg = imagecreatefrompng($filesource['tmp_name']);

      if ($imagetype=='temp_profile' || $imagetype=='temp_avatar')
      {
        $resized = imagecreatetruecolor($maxwidth,$maxheight);
        if ($image_info[1]>$image_info[0])
        {
          // ht > wd
          $newsize = Image::resize($filesource['tmp_name'],$image_info[1],$maxwidth);
          imagecopyresampled($resized, $newimg, 0, 0, 0, 0, $newsize['wd'], $newsize['ht'], $image_info[0], $image_info[1]);
        }
        else if ($image_info[0]>$image_info[1])
        {
          // wd > ht
          $newsize = Image::resize($filesource['tmp_name'],$maxheight,$image_info[0]);
          $x = floor(($maxwidth-$newsize['wd'])/2);
          imagecopyresampled($resized, $newimg, $x, 0, 0, 0, $newsize['wd'], $newsize['ht'], $image_info[0], $image_info[1]);
        }
        else
        {
          // ht = wd
          $newsize = Image::resize($filesource['tmp_name'],$maxheight,$maxwidth);
          imagecopyresampled($resized, $newimg, 0, 0, 0, 0, $newsize['wd'], $newsize['ht'], $image_info[0], $image_info[1]);
        }
      }
      // resize image if it's too big
      else if (($image_info[0] > $maxwidth) || ($image_info[1] > $maxheight))
      {
        $newsize = Image::resize($filesource['tmp_name'],$maxheight,$maxwidth);
        $resized = imagecreatetruecolor($newsize['wd'],$newsize['ht']);
        imagecopyresampled($resized, $newimg, 0, 0, 0, 0, $newsize['wd'], $newsize['ht'], $image_info[0], $image_info[1]);
      }
      else $resized = $newimg;
      imagealphablending($resized,false);
      imagesavealpha($resized,true);
      if (LOCAL)
      {
        if ($imagetype=='profile') $dest = BASE . 'profile_photos/'.$saved_filename;
        else if ($imagetype=='avatar') $dest = BASE . 'avatars/'.$saved_filename;
        else if ($imagetype=='album') $dest = BASE . 'albums/'.$saved_filename;
        else $dest = BASE . 'file_temp/'.$saved_filename;
        if ($image_info['mime'] == "image/jpeg") $success = imagejpeg($resized,$dest,100);
        else if ($image_info['mime'] == "image/gif") $success = imagegif($resized,$dest);
        else if ($image_info['mime'] == "image/png") $success = imagepng($resized,$dest,0);
        $response['filename'] = $saved_filename;
      }
      else if (HOST=='beta.ikegger.com')
      {
        if ($imagetype=='profile') $dest = BASE . 'profile_photos/'.$saved_filename;
        else if ($imagetype=='avatar') $dest = BASE . 'avatars/'.$saved_filename;
        else if ($imagetype=='album') $dest = BASE . 'albums/'.$saved_filename;
        else $dest = BASE . 'file_temp/'.$saved_filename;
        if ($image_info['mime'] == "image/jpeg") $success = imagejpeg($resized,$dest,100);
        else if ($image_info['mime'] == "image/gif") $success = imagegif($resized,$dest);
        else if ($image_info['mime'] == "image/png") $success = imagepng($resized,$dest,0);
        $response['filename'] = $saved_filename;
      }
      else
      {
        // live site upload
        $dest = TEMP_PHOTO_PATH.$saved_filename;
        $ftp = ftp_connect($TEMP_PHOTO_SERVER,21);
        $login = ftp_login($ftp,'webuser','d#fggkfdXZpz0z');
        // save back to temp file
        if ($image_info['mime'] == "image/jpeg") imagejpeg($resized,$filesource['tmp_name'],100);
        else if ($image_info['mime'] == "image/gif") imagegif($resized,$filesource['tmp_name']);
        else if ($image_info['mime'] == "image/png") imagepng($resized,$filesource['tmp_name'],0);
        $success = ftp_put($ftp,$dest,$filesource['tmp_name'],FTP_BINARY);
        $response['filename'] = $saved_filename;
      }
    }
    return $response;
  }

  static function deleteProfilePhoto($photofile)
  {/*#####*/
    $avatar = unlink(BASE.'avatars/'.$photofile);
    $photo = unlink(BASE.'profile_photos/'.$photofile);
    return ($photo && $avatar);
  }

  static function deletePhoto($photofile)
  {/*#####*/
    $deleted = unlink(BASE.'/albums/'.$photofile);
    return $deleted;
  }
}

?>