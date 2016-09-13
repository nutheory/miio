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
if (!isset($_REQUEST['oz_file_format'])) $_REQUEST['oz_file_format']='olcsv';

?>
<? if ($_GET['type']=='setup') { ?><h2>{{UPLOAD_TITLE}}<? } ?>
  <? if ($_GET['type']=='setup') { ?>
    <a href="#" onclick="return top.Signup.NextStep()" style="float:right; margin-left: 15px;">Skip to next step</a>
  <? } ?>
<? if ((ozi_get_config('selector_mode',0)==0) && ($_GET['type']=='setup')) { ?><a href='#' onclick='ozStartAgain();return false;'>{{START_AGAIN}}</a><? } ?>

<? if ($_GET['type']=='setup') { ?></h2><? } ?>

<script type="text/javascript">
//<![CDATA[
var oz_dabi_window = null;

function ozIsIE() {return /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent);}

function ozToggleVisibility(ids,display) {
	var ida = ids.split(',');
	for (var i=0; i<ida.length; i++)
	{
		var ele = document.getElementById(ida[i]);
		if (ele!=null && ele!=undefined) {
			if (ele.style!=undefined && ele.style.display!=undefined && ele.style.display=="none") {
				ele.style.display= display=="table-row" && ozIsIE() ? 'block':display;
			}
			else ele.style.display="none";
		}
	}
	ozNotifyResize();
}

function ozSelectRadio(radiofield, newvalue) {
	if(radiofield) {
		var len = radiofield.length;
		if(len == undefined) {
			radiofield.checked = (radiofield.value == newvalue.toString());
		}
		else {
			for(var i = 0; i < len; i++) {
				radiofield[i].checked = false;
				if(radiofield[i].value == newvalue.toString()) {
					radiofield[i].checked = true;
				}
			}
		}
	}
}

//]]>
</script>

<div id="ozpanel_upload">
  <form method="post" enctype="multipart/form-data" name="ozform_upload" style="margin:0px; padding:0px">
<? echo ozi_render_form_snippet(); ?>
    <input type="hidden" name="oz_state" value="<? echo htmlspecialchars($_REQUEST['oz_state']) ?>"/>
    <input type="hidden" name="oz_service" value="file"/>
    <?
if (ozi_get_config('desktopimporter_present',0)!=0) 
{
?>
    <div id="ozpanel_dabi">
      <div id="ozpanel_dabi_instructions" style="display:none">
        <div><b>{{UPLOAD_FASTIMPORT}}</b></div>
        <p>
          <input type="button" onclick="ozDabiLaunchPopup('<? echo oz_get_resource_path() ?>desktop/index.php?config=<? echo urlencode(ozi_get_config('desktopimporter_config_string','')) ?>');return false;" value="{{UPLOAD_LAUNCH_DESKTOP_IMPORTER}}" class="oz_field_button"/>
        </p>
        <p>{{UPLOAD_NOT_WORKING}} <a href="#" onclick="dabiDownloadImporter();return false;">{{UPLOAD_TRY_DOWNLOADING}}</a></p>
      </div>
      <div id="ozpanel_dabi_paste" style="display:none">
        <div><b>{{UPLOAD_FASTIMPORT}}</b></div>
        <p> {{UPLOAD_STEP1}}:<br/>
          <input type="button" onclick="location.href='<? echo oz_get_resource_path().'desktop/'?>ImportContacts.exe';return false;" value="{{UPLOAD_DOWNLOAD_AND_RUN}}" class="ozf_field_button"/>
          <br/>
          <br/>
          {{UPLOAD_STEP2}}:<br/>
          <textarea id="oz_dabi_result" name="oz_dabi_result" style="height: 4em; width: 300px; font-family:'Courier New', Courier, monospace; margin-top: 5px; margin-bottom:5px; " class="oz_field_input"></textarea>
        </p>
      </div>
      <br/>
      <br/>
      <div align="center" style="border-top: dashed 1px #CCCCCC">{{UPLOAD_OR}}</div>
      <br/>
      <br/>
      
      <script type="text/javascript" src="<? echo htmlspecialchars(oz_get_resource_path()) ?>desktop/oz_desktop.js"></script>
      <script type="text/javascript">
//<![CDATA[
//Callbacks for Desktop contacts importer
function dabiSubmitResult(res) {
	ozDabiStopPasteMonitor();
	ozOnSubmit();	//Show progress bar...
	document.forms.ozform_upload.oz_dabi_result.value = res;
	document.forms.ozform_upload.submit();
}
function dabiDownloadImporter() {
	ozHide('ozpanel_dabi_instructions');
	ozShow('ozpanel_dabi_paste');
	if (oz_dabi_window!=null)
	{
		oz_dabi_window.close();
		oz_dabi_window=null;
	}
}
function dabiUnsupportedOS() {}
function dabiUnsupportedBrowser() {}
function ozDabiIsWindows() {return navigator.appVersion.indexOf("Win")!=-1;}

function ozDabiInit() 
{
	if (ozDabiIsWindows())	//Test for Windows
	{
<? if (ozi_get_config('desktopimporter_present',0)==1) { ?>
		//Test for IE
		if (/msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent)) 
		{
			ozShow('ozpanel_dabi_instructions');
			ozHide('ozpanel_dabi_paste');
		}
		else
		{
			ozHide('ozpanel_dabi_instructions');
			ozShow('ozpanel_dabi_paste');
		}
<? } else { ?>		
		ozHide('ozpanel_dabi_instructions');
		ozShow('ozpanel_dabi_paste');
<? } ?>

		ozShow('ozpanel_dabi');

		//Start
		if (typeof(ozDabiStartPasteMonitor)=='undefined')
			alert('Resource path not defined correctly. Unable to load <? echo htmlspecialchars(oz_get_resource_path()) ?>desktop/oz_desktop.js');
		else
			ozDabiStartPasteMonitor(document.forms.ozform_upload.oz_dabi_result, '{{UPLOAD_PASTE_HERE}}');
	}
}
//]]>
</script>
    </div>
    <?
}
?>
<script type="text/javascript">
//<![CDATA[

function ozUploadInit(src)
{
	if (oz_dabi_window!=null)
	{
		oz_dabi_window.close();
		oz_dabi_window = null;
	}

	//If selecting file upload, then select the appropriate radio button
	if (src=="file") src="file_olcsv";
	var ff = /file_(.*)/.exec(src);
	if (ff!=null)
	{
		ozSelectRadio(document.forms.ozform_upload.oz_file_format,ff[1]);
	}

/*
	var opts = document.forms.ozform_select.oz_service.options;
	for (var i=0; i<opts.length; i++) 
	{
		if (opts[i].value==baseval)
		{		
			document.forms.ozform_select.oz_service.selectedIndex=i;
			break;
		}
	}
*/
	
	ozHide('ozpanel_olcsv,ozpanel_oecsv,ozpanel_wmcsv,ozpanel_tbldif,ozpanel_dabi');

//	document.getElementById('oz_header_label').innerHTML=document.forms.ozform_select.oz_service.options[document.forms.ozform_select.oz_service.selectedIndex].innerHTML;
//	document.forms.ozform_manual.oz_service.value=val;
//	document.forms.ozform_upload.oz_service.value=val;
//	document.forms.ozform_login.oz_service.value=val;
	
<? if (ozi_get_config('desktopimporter_present',0)!=0) { ?>
	//Launch popup window if we're selecting ActiveX and we're on IE on windows
	if ((src=='file_olcsv' || src=='file_oecsv' || src=='file_wmcsv')) 
	{
		if (ozDabiIsWindows()) 
		{
			ozDabiInit();
		}
	}	
<? } ?>
	
	//Scroll to top of page, otherwise user may miss out some content.
	window.scroll(0,0);
}
//]]>
</script>
    <div><b>{{UPLOAD_SELECT_FORMAT}}</b></div>
    <br/>
    <table cellpadding="0" cellspacing="4px" width="100%" style="font-size:14px">
      <tr>
        <td><label>
          <input type="radio" name="oz_file_format" value="olcsv" <? if (ozi_get_param('oz_file_format')=='olcsv') echo 'checked="checked"' ?> />
          {{UPLOAD_OUTLOOK_CSV}}</label></td>
        <td width="10">&nbsp;</td>
        <td align="right"><a href="#" onclick="ozToggleVisibility('ozpanel_olcsv','table-row');return false;">{{UPLOAD_SEE_INSTRUCTIONS}}</a></td>
      </tr>
      <tr  id="ozpanel_olcsv" style="display:none;">
        <td colspan="3">{{UPLOAD_OUTLOOK_CSV_INSTRUCTIONS}}</td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="oz_file_format" value="oecsv" <? if (ozi_get_param('oz_file_format')=='oecsv') echo 'checked="checked"' ?>/>
          {{UPLOAD_OUTLOOKEXPRESS_CSV}}</label></td>
        <td width="10">&nbsp;</td>
        <td align="right"><a href="#" onclick="ozToggleVisibility('ozpanel_oecsv','table-row');return false;">{{UPLOAD_SEE_INSTRUCTIONS}}</a></td>
      </tr>
      <tr id="ozpanel_oecsv" style="display:none;">
        <td colspan="3">{{UPLOAD_OUTLOOKEXPRESS_CSV_INSTRUCTIONS}}</td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="oz_file_format" value="wmcsv" <? if (ozi_get_param('oz_file_format')=='wmcsv') echo 'checked="checked"' ?>/>
          {{UPLOAD_WINDOWSCONTACTS_CSV}}</label></td>
        <td width="30">&nbsp;</td>
        <td align="right"><a href="#" onclick="ozToggleVisibility('ozpanel_wmcsv','table-row');return false;">{{UPLOAD_SEE_INSTRUCTIONS}}</a></td>
      </tr>
      <tr id="ozpanel_wmcsv" style="display:none;">
        <td colspan="3">{{UPLOAD_WINDOWSCONTACTS_CSV_INSTRUCTIONS}}</td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="oz_file_format" value="tbldif" <? if (ozi_get_param('oz_file_format')=='tbldif') echo 'checked="checked"' ?>/>
          {{UPLOAD_THUNDERBIRD_LDIF}}</label></td>
        <td width="10">&nbsp;</td>
        <td align="right"><a href="#" onclick="ozToggleVisibility('ozpanel_tbldif','table-row');return false;">{{UPLOAD_SEE_INSTRUCTIONS}}</a></td>
      </tr>
      <tr id="ozpanel_tbldif" style="display:none;">
        <td colspan="3">{{UPLOAD_THUNDERBIRD_LDIF_INSTRUCTIONS}}</td>
      </tr>
      <tr>
        <td><label>
          <input type="radio" name="oz_file_format" value="vcf" <? if (ozi_get_param('oz_file_format')=='vcf') echo 'checked="checked"' ?>/>
          {{UPLOAD_VCF}}</label></td>
        <td width="10">&nbsp;</td>
        <td align="right"><a href="#" onclick="ozToggleVisibility('ozpanel_vcf','table-row');return false;">{{UPLOAD_SEE_INSTRUCTIONS}}</a></td>
      </tr>
      <tr id="ozpanel_vcf" style="display:none;">
        <td colspan="3">{{UPLOAD_VCF_INSTRUCTIONS}}</td>
      </tr>

      
    </table>
    <br/>
    {{UPLOAD_SELECT_FILE}}<br/>
    <input type="file" name="oz_file" size="40"/>
    <br/>
    <br/>
    <input type="submit" name='ozbtn_upload' value=" {{UPLOAD_UPLOAD}} " id="ozbtn_upload" class="oz_field_button"/>
  </form>
</div>
