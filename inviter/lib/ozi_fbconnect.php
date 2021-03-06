<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

Unified Inviter Component

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/
if (!defined('__ABI')) die('Please include abi.php to use this inviter component!');

define('OZI_FBCONNECT',TRUE);

function ozi_html_to_fbml ($html) {

	//Remove comment, script, css, etc
    $searches = array (
        "/<!\[CDATA\[(.*)\]\]>/si", // Remove CData
        "/<script[^>]*>.*?<\/script>/si", // Strip out javascript
        "/<style[^>]*>.*?<\/style>/si", // Strip out styles
        "/<code[^>]*>.*?<\/code>/si", // Strip out code chunks
        "/<!--.*?-->/s", // Strip comments
         "/<!.*>/Us", // Strip !Tags
         "/<\?.*>/Us" // Strip !Tags
    );
	$replace = array('', '', '', '', '', '', '');
    $html = preg_replace($searches, $replace, $html);
	
	//Tokenize tags and filter out unallowed ones	
	$sb = '';
	$allowedTags=array('b','i','a','strong','em');
	$a = array();
	foreach ($allowedTags as $v) $a[strtolower($v)]=TRUE;
	preg_match_all("/([^<]*)(<\/?([a-zA-Z0-9_]+)[^>]*>)?/ms", $html, $matches, PREG_SET_ORDER);
	foreach ($matches as $val) {
		if (isset($val[1])) $sb.=$val[1];
		//		echo "[TEXT:".htmlspecialchars($val[1])."]<br/>";
		//		echo "[TEXT:".htmlspecialchars($val[2])."]<br/>";
		//		echo "[TEXT:".htmlspecialchars($val[3])."]<br/>";
		//Has HTML tag?
		if (isset($val[3])) {
			$tag = $val[2];
			$tagname = $val[3];
			$tagname = strtolower($tagname);
			if (isset($a[$tagname])) {
				$sb .= $tag;
				
				//Check if tag is closing tag?
				//if ($tag[1]==='/') {
				//}
				//Check if this is an independant tag? (<x/>
			}
			else {
				//Skip tag!
				switch ($tagname) {
				case 'br': $sb.=" \r\n";break;
				case 'div': $sb.=" \r\n";break;
				case 'p': $sb.=" \r\n\r\n";break;
				case 'table':
				case 'td':
				case 'th':
					$sb.=' ';break;
				}
			}
		}
	}
	return $sb;
}


function ozi_render_fbconnect($msg) {

//	ob_start();

	$ozi_fb_app_key = ozi_get_config('facebook_connect.api_key','');
	$url = $msg['url'];
	$ozi_fb_done_url = oz_get_current_url();

	//Attempt to get web app name (from config, or from message object). If not possible, use the domain name
	//in the invitation url instead.
	$name = ozi_get_config('web_name');
	$parts = ozi_parse_url($url);
	if (empty($name)) $name=isset($msg['web_name'])?$msg['web_name']:$name;
	if (empty($name)) $name=ozi_get_config('web_name',NULL);
	if (empty($name)) {$hostname = strtolower($parts['host']);$name = strpos($hostname,'www.')===0 ? substr($hostname,4) : $hostname;}
	$ozi_fb_appname = $name;
	$ozi_fb_actiontext = str_replace('%appname%',$ozi_fb_appname,oz_text('FACEBOOK_CONNECT_ACTION_TEXT'));
	$ozi_fb_invitation_button = str_replace('%appname%',$ozi_fb_appname,oz_text('FACEBOOK_CONNECT_INVITE_BUTTON'));
	$ozi_fb_invitation_url = $url;
	$fbml = isset($msg['fbml_body']) ? $msg['fbml_body'] : NULL;
	if (empty($fbml)) {
		$fbml = $msg['html_body'];
		//FB will drop <br> tags, <p> tags, <div> tags, causing newlines to be joined. We'll add space to compensate.
		//$fbml = str_replace(array('<br>','<br/>','<p>','<BR>','<BR/>','<P>'),array(' ',' ',' ',' ',' ',' '),$fbml);
		$fbml = ozi_html_to_fbml($fbml);
	}
	$ozi_fb_invitation_fbml = htmlspecialchars($fbml,ENT_QUOTES,'UTF-8');
	$ozi_fb_invitation_fbml.= ' <fb:req-choice url="'.$ozi_fb_invitation_url.'" label="'.htmlspecialchars($ozi_fb_invitation_button,ENT_QUOTES,'UTF-8').'"/>';
	$xd_receiver_relpath = ozi_get_config('facebook_connect.receiver_path',NULL);	//ozi_make_absolute_url(oz_get_current_url(),$_REQUEST['oz_res_uri'].'fbconnect/');
	if (empty($xd_receiver_relpath)) $xd_receiver_relpath='/xd_receiver.htm';
	
	$ozi_fb_publish_text = oz_text('FACEBOOK_CONNECT_PUBLISH_TEXT');
	$ozi_fb_publish_text = str_replace(array('%appname%','%url%'),array(htmlentities($ozi_fb_appname,ENT_COMPAT,'UTF-8'),$url),$ozi_fb_publish_text);
	
	
	//Ensure user not accessing FBConnect via localhost/127.0.0.1
	$parts = ozi_parse_url($ozi_fb_done_url);
	$hostname = strtolower($parts['host']);
	if ($hostname=='localhost' || $hostname=='127.0.0.1') echo '<b>WARNING!! Cannot use Facebook Connect from localhost or 127.0.0.1.  Please access via your actual domain name</b><br/>';
	?>


		<script type="text/javascript">
		//<![CDATA[
		var nl = document.getElementsByTagName('html');
		if (nl.length>0) nl[0].setAttribute('xmlns:fb','http://www.facebook.com/2008/fbml');
		//]]>
		</script>
		<script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"></script>
		<div id="ozi_fb_logged_out" style="display:block">
			<br/>
            <?php echo oz_text('FACEBOOK_CONNECT_INSTRUCTIONS') ?><br/><br/>
			<fb:login-button onlogin="ozi_fb_onConnected();" length="long"></fb:login-button>
		</div>
    
		<div id="ozi_fb_logged_in" style="display:none;border:solid 1px #99CC00;">
			<div id="ozi_fb_greeting"><!--<fb:profile-pic uid=loggedinuser facebook-logo=true></fb:profile-pic>-->
			<?php echo oz_text('FACEBOOK_CONNECT_CONNECTED_AS') ?> <fb:name uid=loggedinuser useyou=false linked=true></fb:name>. [<a href='#' onclick='FB.Connect.logout(ozi_fb_onDisconnected);return false;'><?php echo oz_text('FACEBOOK_CONNECT_LOGOUT') ?></a>]
				<br/><br/>
				<?php echo oz_text('FACEBOOK_CONNECT_YOU_CAN') ?>
                <input type="button" onclick="ozi_fb_publish();return false;" value="<?php echo oz_text('FACEBOOK_CONNECT_PUBLISH') ?>"/>
                <?php echo oz_text('FACEBOOK_CONNECT_OR') ?>
            </div>
			<div id="ozi_fb_serverfbml">
			<fb:serverfbml style="width: 100%;">
			  <script type="text/fbml">
					<fb:fbml>
						<fb:request-form action="<?php echo $ozi_fb_done_url ?>" method="GET" invite="true" type="<?php echo htmlspecialchars($ozi_fb_appname,ENT_QUOTES,'UTF-8') ?>" content="<?php echo htmlspecialchars($ozi_fb_invitation_fbml,ENT_QUOTES,'UTF-8') ?>">
							<fb:multi-friend-selector condensed="false" showborder="false" actiontext="<?php echo htmlspecialchars($ozi_fb_actiontext,ENT_COMPAT,'UTF-8') ?>" cols="5" rows="4" email_invite="false">
						</fb:request-form>
					</fb:fbml>
				</script>
			</fb:serverfbml>
			</div>
		</div>

			
        
		
		
		<script type="text/javascript">
		//<![CDATA[
		function ozi_fb_publish() {
			FB.Connect.streamPublish("<?php echo htmlspecialchars($ozi_fb_publish_text,ENT_COMPAT, 'UTF-8') ?>");
		}
		function ozi_fb_onDisconnected() {
			document.getElementById('ozi_fb_logged_in').style.display="none";
			document.getElementById('ozi_fb_logged_out').style.display="block";
			FB.XFBML.Host.parseDomTree();
			ozNotifyResize();
		}
		function ozi_fb_onConnected() {
			document.getElementById('ozi_fb_logged_in').style.display="block";
			document.getElementById('ozi_fb_logged_out').style.display="none";

//			var attachment = {'media':[{'type':'image','src':'http://www.google.com','href':'http://www.google.com?q=a'}]};
	//		FB.Connect.streamPublish('', attachment);
	
 			
//			load_feed();
//			var attachment = {};
//			var actionLinks = [{ "text": "Watch Video", "href": "http://www.myvideosite/videopage.html"}];
//			FB.Connect.streamPublish('Hey, come!',attachment, actionLinks);
			
			FB.XFBML.Host.parseDomTree();
			ozNotifyResize();
		}
		//]]>
		</script>
		<script type="text/javascript">
		//<![CDATA[
		FB.init("<?php echo $ozi_fb_app_key ?>","<?php echo $xd_receiver_relpath ?>", {"ifUserConnected" : ozi_fb_onConnected});
		//]]>
		</script>
<?php

//	$html = ob_get_contents();
//	ob_end_clean();
//	return $html;
}
?>