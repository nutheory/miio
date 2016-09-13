<?
/********************************************************************************
DO NOT EDIT THIS FILE!

Unified Inviter Component

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/

//////////////////////////////////////////////////////////////////////////
//Takes in parameter from $_POST
//
//Takes in parameter from $_POST
//oz_sel_mode
//	"icon". Renders icons of services, with option to expand
//	"list" (default). Renders dropdown list of services.
//
//oz_service (optional)
//	Contains the service ID if we're to jump straight to login form
//
//oz_auth_login (optional)
//	Contains the user auth ID that is to be prefilled
//
//Submits the following
//	oz_captcha_answer
//	oz_captcha_submit
//////////////////////////////////////////////////////////////////////////

if (!defined('_OZ_INVITER')) exit();

//Set defaults
if (!isset($_REQUEST['oz_sel_mode'])) $_REQUEST['oz_sel_mode']='list';
if (!isset($_REQUEST['oz_service'])) $_REQUEST['oz_service']='';

$oz_abi_visible_count = 0;

global $_OZI_DIR;
?>
<div id="ozframe_login" style="display:none">
<? _oz_eval_template($_OZI_DIR.'/views/view_login.php'); //include(dirname(__FILE__).'/view_login.php'); ?>
</div>
<div id="ozframe_manual" style="display:none">
<? _oz_eval_template($_OZI_DIR.'/views/view_manual.php'); //include(dirname(__FILE__).'/view_manual.php'); ?>
</div>
<div id="ozframe_upload" style="display:none">
<? _oz_eval_template($_OZI_DIR.'/views/view_upload.php'); //include(dirname(__FILE__).'/view_upload.php'); ?>
</div>
<div id="ozframe_bookmark" style="display:none">
<? _oz_eval_template($_OZI_DIR.'/views/view_bookmark.php'); //include(dirname(__FILE__).'/view_bookmark.php'); ?>
</div>

<script type="text/javascript">
//<![CDATA[ 
function ozToggleSection(id,buttonid,morelabel,lesslabel)
{
	var ele = document.getElementById(id);
	var visible = ele.style.display!=undefined && ele.style.display=='inline';
	if (visible) 
	{
		ele.style.display="none";
		document.getElementById(buttonid).innerHTML=morelabel;
	}
	else
	{
		ele.style.display="inline";
		document.getElementById(buttonid).innerHTML=lesslabel;
	}
	ozNotifyResize();
}

function ozStartAgain()
{
	ozShow('ozpanel_selector');
	ozShow('ozpanel_actions');
	ozHide('ozframe_login');
	ozHide('ozframe_upload');
	ozHide('ozframe_manual');
	ozHide('ozframe_bookmark');
	ozHide('oz_error');
	ozNotifyViewChange('selector');
	
	<? 
	$selmode = ozi_get_config('selector_mode',0);
	if ($selmode==1 || $selmode==2) { ?>
	ozSelect('');
	<? } ?>
}

function ozSelect(src)
{
	//Hide selector panel and show the relevant panel
	ozHide('ozpanel_selector');
	ozHide('ozframe_login');
	ozHide('ozframe_upload');
	ozHide('ozframe_manual');
	ozHide('ozframe_bookmark');
	ozHide('ozpanel_actions');
	//ozHide('oz_error');

	if (/file_/.test(src) || src=='file')
	{
		ozShow('ozframe_upload');
		ozUploadInit(src);
		ozNotifyViewChange('upload');
	}
	else if (src=="manual")
	{
		ozShow('ozframe_manual');
		//ozManualInit();
		ozNotifyViewChange('manual');
	}
	else if (src=="bookmark")
	{
		ozShow('ozframe_bookmark');
		//ozBookmarkInit();
		ozNotifyViewChange('bookmark');
	}
	else //webmail and social networks
	{
		ozShow('ozframe_login');
		
		<? 
		$selmode = ozi_get_config('selector_mode',0);
		if ($selmode==2) { ?>
		ozShow('ozpanel_selector');
		<? } ?>		
		<? if ($selmode==1 || $selmode==2) { ?>
		ozShow('ozpanel_actions');
		<? } ?>
		ozLoginInit(src);
	}
	
	//Scroll to top of page, otherwise user may miss out some content.
//	window.scroll(0,0);
	//FIXME
	
	return false;
}
//]]>
</script>

<? 
//##############################################################################################
//ICON SELECTOR BEGIN
//##############################################################################################
?>
<? 
$selmode = ozi_get_config('selector_mode',0);
if ($selmode==0 || $selmode==2) { 
?>

<div id="ozpanel_selector" <? if (isset($_REQUEST['oz_errmsg'])) echo 'style="display:none"' ?> >
  <div id="ozpanel_ab_icons" style="margin-bottom:10px">
    <? if ($_GET['type'] == 'setup'){ ?><h2>Invite friends using your email<a href="#" onclick="ozToggleSection('oz_abilogo_more','oz_abilogo_button','{{SELECTOR_SHOW_ALL}} &raquo;','&laquo; {{SELECTOR_HIDE}}');return false;" id="oz_abilogo_button">{{SELECTOR_SHOW_ALL}} &raquo;</a><a href="#" onclick="return top.Signup.NextStep()">skip</a></h2><? } ?>
    <div class="oz_icons_container">
      <?
	global $oz_visible_count;
	global $oz_invisible_count;
	global $oz_max_visible;
	global $oz_hidden_panel_id;
	global $oz_count;
	$oz_visible_count=0;
	$oz_invisible_count=0;
	$oz_max_visible=4;
	$oz_count=0;
	
	function render_service_logo($id,$name,$logoid=NULL) {
		global $oz_visible_count;
		global $oz_invisible_count;
		global $oz_max_visible;
		global $oz_hidden_panel_id;
		global $oz_count;
	
		$s = '';
		if ($logoid===NULL) $logoid=$id;
		
		$skiptest = strpos($id,'file_')!==FALSE || $id=='manual' || $id=='bookmark';
		if (!ozi_get_config('facebook_classicmode',FALSE)) $skiptest=$skiptest?true:$id=='is_facebook';
		$inviter = isset($_REQUEST['oz_inviter'])?$_REQUEST['oz_inviter']:NULL;
		if (($inviter!==NULL && $inviter->is_service_supported($id)) || empty($id) || $skiptest) {
			if ($oz_visible_count>=$oz_max_visible && $oz_invisible_count==0) $s.='<div id="'.$oz_hidden_panel_id.'" style="display:none">';
			$s .= '<a href="#" onclick="ozSelect(\''.$id.'\');return false;" title="'.htmlspecialchars($name).'"><div class="oz_logo oz_logo_'.$logoid.'">'.htmlspecialchars($name).'<span></span></div></a>'."\r\n";
			if ($oz_visible_count<$oz_max_visible) $oz_visible_count++;
			else $oz_invisible_count++;
			$oz_count++;
			return $s;
		}
		else {
			return $s;
		}
	}
	
	$inviter = isset($_REQUEST['oz_inviter'])?$_REQUEST['oz_inviter']:NULL;
	$orkutEmailSupported = $inviter!==NULL && $inviter->is_service_supported('orkut');
	
		
	$oz_visible_count=0;
	$oz_invisible_count=0;
	$oz_max_visible=ozi_get_config('selector_ab_max_icons',4);
	$oz_hidden_panel_id='oz_abilogo_more';
	$oz_count=0;
	
	//echo render_service_logo('','Any Webmail (autodetect)');
	
	if (ozi_get_config('show_abi',TRUE)) {
		echo render_service_logo(ozi_get_config('prefer_webauth',FALSE)?'wa_gmail':'gmail','GMail');
		echo render_service_logo(ozi_get_config('prefer_webauth',FALSE)?'wa_hotmail':'hotmail','Hotmail');
		echo render_service_logo('yahoo','Yahoo');
		echo render_service_logo('aol','AOL');
		echo render_service_logo('lycos','Lycos');
		echo render_service_logo('medotcom','.Me/.Mac');
		echo render_service_logo('maildotcom','Mail.com');
		echo render_service_logo('fastmail','FastMail');
		echo render_service_logo('icq','IcqMail');
		if ($oz_count>0 && ozi_get_config('selector_group_icons',TRUE)) {$oz_count=0;echo '<div class="oz_separator"></div>';}
		echo render_service_logo('gmx','GMX.net');
		echo render_service_logo('webde','Web.de');
		echo render_service_logo('freenet','Freenet.de');
		//echo render_service_logo('tonlinede','T-Online');
		if ($oz_count>0 && ozi_get_config('selector_group_icons',TRUE)) {$oz_count=0;echo '<div class="oz_separator"></div>';}
		//echo render_service_logo('aliceit','Alice.it');
		echo render_service_logo('emailit','Email.it');
		echo render_service_logo('libero','Libero.it');
		echo render_service_logo('aliceit','Alice.it');
		echo render_service_logo('virgilioit','Virgilio.it');
		if ($oz_count>0 && ozi_get_config('selector_group_icons',TRUE)) {$oz_count=0;echo '<div class="oz_separator"></div>';}
		echo render_service_logo('interia','Interia.pl');
		echo render_service_logo('o2','O2.pl');
		echo render_service_logo('onet','Onet.pl');
		echo render_service_logo('wppl','Wp.pl (Wirtualna Polska)');
		if ($oz_count>0 && ozi_get_config('selector_group_icons',TRUE)) {$oz_count=0;echo '<div class="oz_separator"></div>';}
		echo render_service_logo('mailru','Mail.ru');
		echo render_service_logo('rambler','Rambler');
		echo render_service_logo('yandex','Yandex');
		if ($oz_count>0 && ozi_get_config('selector_group_icons',TRUE)) {$oz_count=0;echo '<div class="oz_separator"></div>';}
		echo render_service_logo('mynet','MyNet');
		echo render_service_logo('sapo','Sapo.pt');
		echo render_service_logo('orangees','Orange.es');
		echo render_service_logo('terra','Terra.es');
		echo render_service_logo(ozi_get_config('prefer_webauth',FALSE)?'wa_databg':'databg','Data.bg','databg');
		echo render_service_logo(ozi_get_config('prefer_webauth',FALSE)?'wa_igcombr':'igcombr','Ig.com.br','igcombr');
		if ($oz_count>0 && ozi_get_config('selector_group_icons',TRUE)) {$oz_count=0;echo '<div class="oz_separator"></div>';}
		echo render_service_logo('daumnet','Daum.net');
		echo render_service_logo('naver','Naver.com');
		echo render_service_logo('indiatimes','Indiatimes');
		echo render_service_logo('rediff','Rediffmail');
	}
	
	//echo render_service_logo('sohu','Sohu');
?>
<div class="oz_selector_actions" style="float:right">
  <a href="#" onclick="ozSelect('');return false;">Don't see yours?</a>
</div>
<?
	
	if ($oz_invisible_count>0) echo '</div>';
	
	$oz_abi_visible_count = $oz_visible_count;
	
	if ($oz_abi_visible_count>0) {
		$tmp = $oz_invisible_count;
		$oz_visible_count = 0;
		$oz_invisible_count = 0;
		if (ozi_get_config('allow_upload',TRUE)) {
			if ($oz_count>0) {$oz_count=0;echo '<div class="oz_separator"></div>';}
			echo render_service_logo('file_olcsv','Outlook');
			echo render_service_logo('file_oecsv','Outlook Express');
			echo render_service_logo('file_wmcsv','Windows Mail');
			echo render_service_logo('file_tbldif','Thunderbird');
			echo render_service_logo('file_vcf','Mac OS');
		}		
		$oz_invisible_count = $tmp;
	}
?>

    </div>
</div>
<? if ($oz_invisible_count==0) { ?>  
<script type="text/javascript">
ozHide('oz_abilogo_button');
</script>  
<? } ?>
<!--
  <img src="http://www.octazen.com/api/usage/?sec=sel&id={#TRKCDE#}" width="1" height="1"/>
-->  
  <!-- SOCIAL NETWORKS -->
  <div id="ozpanel_sn_icons" >
    <? if ($_GET['type'] == 'setup'){ ?><h2>Invite friends using social networks<a href="#" onclick="ozToggleSection('oz_islogo_more','oz_islogo_button','{{SELECTOR_SHOW_ALL}} &raquo;','&laquo; {{SELECTOR_HIDE}}');return false;" id="oz_islogo_button">{{SELECTOR_SHOW_ALL}} &raquo;</a></h2><? } ?>
    <div class="oz_icons_container">
      <?
	$oz_visible_count=0;
	$oz_invisible_count=0;
	$oz_max_visible=ozi_get_config('selector_sn_max_icons',4);
	$oz_hidden_panel_id='oz_islogo_more';

	if (ozi_get_config('show_sn',TRUE)) {
		echo render_service_logo('is_facebook','Facebook');
		echo render_service_logo('is_myspace','MySpace');
		echo render_service_logo('is_twitter','Twitter');
		echo render_service_logo('is_friendster','Friendster');
		echo render_service_logo('is_hi5','Hi5');
		//Prefer email rather than Orkut private messages which incur 1 captcha each.
		echo render_service_logo($orkutEmailSupported?'orkut':'is_orkut','Orkut');
		echo render_service_logo('is_xing','Xing');
		echo render_service_logo('is_bebo','Bebo');
		echo render_service_logo('is_blackplanet','BlackPlanet');
		echo render_service_logo('is_meinvz','MeinVZ');
		echo render_service_logo('is_hyves','Hyves');
		echo render_service_logo('linkedin','LinkedIn');
		echo render_service_logo('plaxo','Plaxo');
	}
	
	if ($oz_invisible_count>0) echo '</div>';
	
	$oz_is_visible_count = $oz_visible_count;
	?>
      <div style="clear:both"></div>
 
    </div>
  </div>
<? if ($oz_invisible_count==0) { ?>  
<script type="text/javascript">
//<![CDATA[
ozHide('oz_islogo_button');
//]]>
</script>  
<? } ?>
<script type="text/javascript">
//<![CDATA[
<? if ($oz_abi_visible_count>0) { ?>
ozShow('ozpanel_ab_icons');<? } else { ?>ozHide('ozpanel_ab_icons');<? } ?>
<? if ($oz_is_visible_count>0) { ?>ozShow('ozpanel_sn_icons');<? } else { ?>ozHide('ozpanel_sn_icons');<? } ?>
//]]>
</script>

</div>

<? } // if (ozi_get_config('selector_mode',0)==0) { ?>
<? 
//##############################################################################################
//ICON SELECTOR END
//##############################################################################################
?>

<div id="ozpanel_actions" <? if ($_GET['mode']=='share' || $_GET['type']=='setup' || $_GET['type']=='app') echo 'style="display:none;"'; ?>>
  <div class="oz_separator" <? if ($_GET['type']=='setup' || $_GET['type']=='app') echo 'style="display:none;"'; ?>></div>
  <!-- WIDTH 100% FOR IE8 BUG. TRY DEFAULT DRUPAL5 THEME -->
  <div class="oz_options_table"  <? if ($_GET['type']=='setup' || $_GET['type']=='app') echo 'style="display:none;"'; ?>>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr>
<? if (ozi_get_config('allow_upload',TRUE)) { ?>
        <td width="33%" align="center"><b><a href="#" onclick="ozSelect('file_olcsv');return false;">{{UPLOAD_FILE}}</a></b></td>
<? } ?>      
<? if (ozi_get_config('allow_manual_invite',TRUE)) { ?>      
        <td width="33%" align="center"><b><a href="#" onclick="ozSelect('manual');return false;">{{MANUAL_INVITE}}</a></b></td>
<? } ?>      
<? if (ozi_get_config('allow_bookmark',TRUE)) { ?>      
        <td width="33%" align="center"><b><a href="#" onclick="ozSelect('bookmark');return false;">{{SHARE_AS_LINK}}</a></b></td>
<? } ?>      
      </tr>
    </table>
  </div>
</div>
</div>
<script type="text/javascript">
//<![CDATA[
<? if (isset($_REQUEST['oz_errmsg'])) { ?>
DomReady.ready(function() {
	ozSelect('<? echo htmlspecialchars($_REQUEST['oz_service']) ?>');
});
//setTimeout("ozSelect('<? echo htmlspecialchars($_REQUEST['oz_service']) ?>');",1);
<? } else if ($_GET['mode']=='share') { ?>
DomReady.ready(function() {
	ozSelect('bookmark');
});
<? } else { ?>
DomReady.ready(function() {
	ozStartAgain();
});

//setTimeout("ozStartAgain()",1);
<? } ?>
//]]>
</script>