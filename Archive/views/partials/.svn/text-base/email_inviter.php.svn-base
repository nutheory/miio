<?
global $BASE;

include_once("octazen/abimporter/abi.php");
include_once("octazen/inviter/ozinviter.php");

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
  // ?
}

function oz_filter_contacts(&$contacts)
{
  // stuff to filter or sort contacts here
}

?>
<div id="oz_inviter">
  <?
    echo oz_render_inviter('octazen/inviter/res',$BASE.'octazen/inviter/res');
  ?>
</div>