<?
if ($_SERVER['HTTP_HOST']=='localhost') require_once "/websites/miio/miio_config/config.php";
else require_once "/home/beta.miio.com/miio_config/config.php";
if ((HOST == 'localhost') || (HOST == 'beta.ikegger.com')) error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
else error_reporting(0);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Miio Contact Inviter - powered by Octazen</title>

  <!-- CSS -->
<? if ($_GET['type']=='setup') { ?>
  <link href="res/style.css" rel="stylesheet" type="text/css">
<? } else { ?>
  <link href="res/stylei.css" rel="stylesheet" type="text/css">
<? } ?>

<link href="../css/inviter.css" rel="stylesheet" type="text/css">
</head>
<?
include_once("abi.php");
include_once("ozinviter.php");

if ($_GET['mode']=='email')
{
  $_OZ_CONFIG['show_sn'] = FALSE;
  $_OZ_CONFIG['allow_bookmark'] = FALSE;
}
else if ($_GET['mode']=='network')
{
  $_OZ_CONFIG['show_abi'] = FALSE;
  $_OZ_CONFIG['allow_bookmark'] = FALSE;
}
else if ($_GET['mode']=='share')
{
  $_OZ_CONFIG['show_sn'] = FALSE;
  $_OZ_CONFIG['show_abi'] = FALSE;
  $_OZ_CONFIG['allow_upload'] = FALSE;
}

function oz_get_invite_message($from_name=NULL,$from_email=NULL,$personal_message=NULL)
{
  $resp = array
  (
    "subject"=>"Miio Invitation",
    "text_body"=>"Check out Miio",
    "html_body"=>"Check out <a href='http://miio.com'>Miio</a>",
    "url"=>"http://miio.com"
  );
  return $resp;
}

function oz_send_emails($from_name,$from_email,&$contacts,$personal_message)
{
  foreach($contacts as $contact)
  {
    Notify::SendMiioInvitation($from_name,$from_email,$personal_message,$contact['name'],$contact['email']);
  }

}

function oz_filter_contacts(&$contacts)
{
  global $User;
  // misc info for saving contacts:

  /*
  all contacts: type = email | social
  twitter: type=social, id=twitter user id, name=user's name
  email: name = name set on my list, email = user's email

  */

  // save emails
  if (count($contacts)>0)
  {
    if ($contacts[0]['type']=='email')
    {
      foreach ($contacts as $contact)
      {
        $User->saveEmailContact($contact['email']);
      }
    }
  }

  // any other contact processing goes here
}

?>

<div id="oz_inviter">
  <?
    echo oz_render_inviter('res','res');
  ?>
</div>