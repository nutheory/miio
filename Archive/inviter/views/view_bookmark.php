<?
/********************************************************************************
DO NOT EDIT THIS FILE!

Unified Inviter Component

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/
//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
if (!defined('_OZ_INVITER')) exit();

global $_OZI_CALLBACKS;
$func = $_OZI_CALLBACKS['get_invite_message']	;
$msg = $func(NULL,NULL,NULL);
//$msg = oz_get_invite_message();
$url = $msg['url'];
//Use of 'title' is preferred
if (isset($msg['title'])) $title=$msg['title'];
else $title = $msg['subject'];
//$text_body = str_replace('{PERSONALMESSAGE}','',$msg['text_body']);
$text_body = '';

function oz_render_bookmark($id,$name,$shareurl,$url,$title,$text_body)
{
	$u = str_replace(
		array('{URL}','{TITLE}','{MESSAGE}'), 
		array(urlencode($url),urlencode($title),urlencode($text_body)),
		$shareurl);
	return '<li class="oz_bookmark_link oz_bookmark_'.$id.'"><a href="'.htmlspecialchars($u,ENT_COMPAT,'UTF-8').'" target="_blank"><span>'.htmlspecialchars($name).'</span></a></li>';
}

?>
<? if ($_GET['type'] == 'setup'){ ?>
  <h2>
    {{BOOKMARK_TITLE}}
    <a href="#" onclick="return top.Signup.NextStep()" style="float:right; margin-left: 15px;">Skip to next step</a>
    <a href="#" onclick="ozStartAgain();return false;">{{START_AGAIN}}</a>
  </h2>
<? } ?>
<div id="ozpanel_bookmark">
  <div>{{BOOKMARK_SELECT}}</div>
	<ul class="bm">
<?

echo oz_render_bookmark('digg','Digg',			'http://digg.com/submit?phase=2&url={URL}&title={TITLE}', $url,$title,$text_body);
echo oz_render_bookmark('reddit','Reddit',		'http://reddit.com/submit?url={URL}&title={TITLE}', $url,$title,$text_body);
echo oz_render_bookmark('delicious','del.icio.us',	'http://del.icio.us/post?url={URL}&title={TITLE}', $url,$title,$text_body);
echo oz_render_bookmark('stumbleupon','StumbleUpon',	'http://www.stumbleupon.com/submit?url={URL}&title={TITLE}', $url,$title,$text_body);
?>
</ul>
  <div>{{BOOKMARK_SENDTHISLINK}}</div>
  <form name="ozform_bookmark" style="margin:0px">
<? echo ozi_render_form_snippet(); ?>
  <input type="text" name="oz_link" value="<? echo htmlspecialchars($url) ?>" class="oz_copy_text oz_field_input" onfocus="this.select()" readonly="readonly"  onmouseover="this.select()"/>
  </form>
  <br/>

</div>
