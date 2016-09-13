<?
/********************************************************************************
DO NOT EDIT THIS FILE!

Unified Inviter Component

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/
if (!defined('_OZ_INVITER')) exit();
//////////////////////////////////////////////////////////////////////////
//Takes in parameter from $_REQUEST
//	oz_contacts
//		Array of contacts. Each contact is an associative array.
//
//Submits the following
//	oz_cid[]
//		Array of select contacts' ID
//////////////////////////////////////////////////////////////////////////
//background-color:#FFFFFF

$svc_id = ozi_get_current_service_id();
$ozi_max_select = function_exists('oz_get_select_limit') ? oz_get_select_limit($svc_id) : ozi_get_default_select_limit($svc_id);

?>
<? if ($_GET['type']=='setup') { ?>
  <h2>
    {{CONTACTS_TITLE}}
    <a href="#" onclick="return top.Signup.NextStep()" style="float:right;margin-left:15px;">Skip to next step</a>
    <a href="#" onclick="ozStartAgain2();return false;">{{START_AGAIN}}</a>
  </h2>
<? } ?>

<script type="text/javascript">
//<![CDATA[
function ozCheckEmailField(input)
{
	emailre = /^([+=&'\/\\?\\^\\~a-zA-Z0-9\._-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+$/
	if (input.value.length==0) input.className="oz_field_input";
	else if (emailre.test(input.value)) input.className="oz_field_input oz_email_valid";
	else input.className="oz_field_input oz_email_invalid";
}
function ozOnContactsSubmit(form)
{
	<? if (ozi_get_config('your_email',TRUE)) { ?>
	emailre = /^([+=&'\/\\?\\^\\~a-zA-Z0-9\._-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+$/
	if (!emailre.test(form.oz_from_email.value)) 
	{
		alert('{{INVALID_EMAIL}}');
		form.oz_from_email.focus();
		return false;
	}
    <? } ?>
	ozOnSubmit();
	return true;
}
//]]>
</script>


<form method="post" name="oz_contacts_form" onsubmit="return ozOnContactsSubmit(this);" style="margin:0px;">
<? echo ozi_render_form_snippet(); ?>
  <input type="hidden" name="oz_state" value="<? echo htmlspecialchars($_REQUEST['oz_state']) ?>"/>
  <script type="text/javascript">
//<![CDATA[
var ozi_max_select = <? echo $ozi_max_select ?>;
var ozi_select_count = 0;

function ozSelectAll(cb)
{
	var c = 0;
	var val = cb.checked;
	var frm = document.forms.oz_contacts_form;
	var len = frm.elements.length;
	for(var i=0 ; i<len ; i++) {
		var cb = frm.elements[i];
		if (cb.name=='oz_cid[]') {
			c++;
			if (ozi_max_select!=-1 && c>ozi_max_select) val=false;
			cb.checked=val;
//			ozUpdateRowStyle(frm.elements[i].parentNode.parentNode,val);
		}
	}
	ozUpdateSelection();
}

function ozUpdateRemainingCount() {
	var remaining = ozi_max_select - ozi_select_count;
	var e = document.getElementById('oz_counter_remaining');
	if (e) {
		if (remaining<=0) {
			e.style.display='none';
			e = document.getElementById('oz_counter_max_selected');
			e.style.display='block';
		}
		else {
			e.style.display='block';
			var e = document.getElementById('oz_counter_max_selected');
			e.style.display='none';
			var s = "<? echo oz_text('CONTACTS_SELECT_UP_TO') ?>";
			s = s.replace(/%count%/,remaining);
			e = document.getElementById('oz_counter_remaining').innerHTML = s;
		}
	}
}

function ozUpdateSelection() {
	ozi_select_count = 0;
	var frm = document.forms.oz_contacts_form;
	var len = frm.elements.length;
	for(var i=0 ; i<len ; i++) {
		var cb = frm.elements[i];
		if (cb.name=='oz_cid[]') {
			if (cb.checked) ozi_select_count++;
			ozUpdateRowStyle(cb.parentNode.parentNode,cb.checked);
		}
	}
	ozUpdateRemainingCount();
}

function ozUpdateCb(cb) {
	var checked = cb.checked;
	
	if (checked) {
		if (ozi_max_select!=-1 && ozi_select_count>=ozi_max_select) {
			cb.checked = false;
			return;
		}
		ozi_select_count++;
	}
	else {
		//Should never be -ve
		ozi_select_count--;
	}
	
	ozUpdateRowStyle(cb.parentNode.parentNode,checked);	
	ozUpdateRemainingCount();
}

function ozUpdateRowStyle(row, checked) {
	var cn = row.className;
	var hascb = cn.indexOf('oz_row_cb')!=-1;
	var evenrow = cn.indexOf('oz_row_even')!=-1;
	var cn2 = evenrow?'oz_row_even':'oz_row_odd';
	if (checked) cn2+='_selected';
	if (hascb) cn2+=' oz_row_cb';
	row.className = cn2;
}

function ozToggleRow(tr)
{
	var nl = tr.parentNode.getElementsByTagName('input');
	var checked = nl[0].checked = !nl[0].checked;

	ozUpdateCb(nl[0]);
	
//	ozUpdateRowStyle(tr.parentNode,checked);	
}

var oz_float_row=null;
//var IE = document.all?true:false

function ozGetFloatDiv() {
	var float = document.getElementById('oz_floating_image');
	if (float) return float;
	
	//Create the necessay div and floating iframe window in page
	var d = document.createElement('div');
	d.setAttribute('id','oz_floating_image');
	d.style.position='absolute';
	d.style.display='none';
	d.style.border='1px solid #FF0000';
	d.style.padding='0px';
	d.style.zIndex=10000;
	document.body.appendChild(d);
	float = document.getElementById('oz_floating_image');
	return float;
}

function ozHideImage()
{
	if (oz_float_row!=null)
	{
		var float = ozGetFloatDiv();
		float.style.display="none";
	}		
	oz_float_row=null;
}

function ozShowImage(obj)
{
	var elements = obj.getElementsByTagName('img');
	if (elements.length==0) return;
	for (var i=0; i<elements.length; i++) {
		var img = elements[i];
		if (img.className && img.className=='oz_contact_img') {
			var imgsrc=img.src;
			if (imgsrc!=undefined && imgsrc.length>0)
			{
				oz_float_row=obj;
				obj.onmouseout=ozHideImage;
				var float = ozGetFloatDiv();
				float.innerHTML='<img src="'+imgsrc+'"/>';
				float.style.display="block";
				//dropmenuobj.style.visibility="visible"
			}
		}
	}
}

function ozUpdateImagePosition(e)
{
	//Get mouse position, relative to document
	var x=0,y=0;
	if (!e) var e = window.event;
	if (e.pageX || e.pageY) {
		x=e.pageX;
		y=e.pageY;
	}
	else if (e.clientX || e.clientY) {
		x=e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
		y=e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
	}

	var float = ozGetFloatDiv();
	float.style.top = y+"px";
	float.style.left = (50+x)+"px";
}

//]]>
</script>

  <div class="oz_contacts_list">
  <?

$contacts = isset($_REQUEST['oz_contacts']) ? $_REQUEST['oz_contacts'] : array();
/*
//if (function_exists('oz_filter_contacts')) oz_filter_contacts($contacts);
global $_OZI_CALLBACKS;
$func = $_OZI_CALLBACKS['filter_contacts'];
if (function_exists($func)) $func($contacts);
*/

//Generate HTML code for contacts list if there isn't one
/*
foreach ($contacts as &$c) {
	if (!isset($c['x-namehtml'])) {
		$name = isset($c['name']) ? $c['name'] : '';
		if (isset($c['uid'])) {
			$c['x-namehtml']='<div class="oz_name">'.htmlspecialchars($name,ENT_COMPAT,'UTF-8').'</div><div style="clear:both"></div>';
			$c['x-emailhtml']='';
		}
		else {
			$email = isset($c['email']) ? $c['email'] : '';
			$c['x-namehtml']='<div class="oz_name">'.htmlspecialchars($name,ENT_COMPAT,'UTF-8').'</div><div class="oz_email">&lt;'.htmlspecialchars($email,ENT_COMPAT,'UTF-8').'&gt;</div><div style="clear:both"></div>';
			//$c['x-namehtml']='<div class="oz_name">'.htmlspecialchars($name,ENT_COMPAT,'UTF-8').'</div><div style="clear:both"></div>';
			//$c['x-emailhtml']='<div class="oz_email">'.htmlspecialchars($email,ENT_COMPAT,'UTF-8').'</div><div style="clear:both"></div>';
			$c['x-emailhtml']='';
		}
	}
}
*/

if (count($contacts)==0)
{
?>
<div id="ozpanel_nocontacts">
<p align='center'>{{CONTACTS_NO_CONTACTS}}</p>
</div>
<?
}
else {
?>

<? if ($ozi_max_select==-1 || $ozi_max_select>=count($contacts)) { ?>
      <label class="select_all">
        <input type="checkbox" name="oz_select_all" value="" onclick="ozSelectAll(this)"/>
        <span class="">{{CONTACTS_SELECT_ALL_NONE}}</span>
	  </label>
<? } else { ?>
<span id="oz_counter_remaining">{{CONTACTS_SELECT_UP_TO}} <span id="oz_remaining_count">0</span></span>
<span id="oz_counter_max_selected"  style="display:none">{{CONTACTS_MAXIMUM_SELECTED}}</span>
<? } ?>        

  <div class="oz_contacts_table">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <?
	$oz_is_email = false;
	$rowc = 0;
	foreach ($contacts as $c) 
	{
		$cid = $c['id'];
		$namehtml = isset($c['x-namehtml']) ? $c['x-namehtml'] : htmlspecialchars(isset($c['name']) ? $c['name'] : '',ENT_COMPAT,'UTF-8');
		$nocb = isset($c['x-nocheckbox']);
		$image = isset($c['image']) ? $c['image'] : '';
		if (isset($c['uid']))
		{
			//Sender social network contact
			$emailhtml = isset($c['x-emailhtml']) ? $c['x-emailhtml'] : htmlspecialchars(isset($c['email']) ? $c['email'] : '',ENT_COMPAT,'UTF-8');
			echo '<tr class="'.(($rowc & 0x1)==0 ? 'oz_row_even':'oz_row_odd').($nocb?'':' oz_row_cb').'">';
			
			//Render checkbox
			echo '<td width="1" class="oz_col_cb">';
			if (!$nocb) echo '<input type="checkbox" name="oz_cid[]" value="'.htmlspecialchars($cid).'" onclick="ozUpdateCb(this);"/>';
			else echo '&nbsp;';
			echo '</td>';

			//Render image
			if (!$nocb) echo '<td width="1" class="oz_col_img" onclick="ozToggleRow(this);">';
			else echo '<td width="1" class="oz_col_img">';
			if (!empty($image)) echo '<div class="oz_contact_image"><div><img src="'.htmlspecialchars($image, ENT_COMPAT, 'UTF-8').'" class="oz_contact_img"/></div></div>';
			else echo '&nbsp;';				
			echo '</td>';
			
			//Render name
			if (!$nocb) echo '<td onclick="ozToggleRow(this);" class="oz_col_name">';
			else echo '<td class="oz_col_name">';
			//if (isset($c['x-profileurl'])) echo '<a href="'.$c['x-profileurl'].'">'.htmlspecialchars($name,ENT_COMPAT,'UTF-8').'</a>';
			//else echo htmlentities($name,ENT_COMPAT,'UTF-8');
			if (!empty($namehtml)) echo '<div class=\'oz_name\'>'.$namehtml.'</div>';
			else echo '&nbsp;';
//			echo '</td>';

			//Render email			
//			if (!$nocb) echo '<td onclick="ozToggleRow(this);" class="oz_col_email">';
//			else echo '<td>';
			//if (isset($c['x-profileurl'])) echo '<a href="'.$c['x-profileurl'].'">'.htmlspecialchars($email,ENT_COMPAT,'UTF-8').'</a>';
			//else echo htmlentities($email,ENT_COMPAT,'UTF-8');
			if (!empty($emailhtml)) echo '<div class=\'oz_email\'>'.$emailhtml.'</div>';
			else echo '&nbsp;';
			echo '</td>';

			//Render additional contact html			
			echo '<td class="oz_col_html">';
			if (isset($c['x-html'])) echo $c['x-html'];
			else echo '&nbsp;';
			echo '</td>';
			
			echo '</tr>';
		}
		else
		{
			$oz_is_email = true;
			
			//Render normal email contact
			$emailhtml = isset($c['x-emailhtml']) ? $c['x-emailhtml'] : htmlspecialchars(isset($c['email']) ? $c['email'] : '',ENT_COMPAT,'UTF-8');
			echo '<tr class="oz_row" onmouseover="ozShowImage(this)">';
			
			//Render checkbox
			echo '<td width="1" class="oz_col_cb">';
			if (!$nocb) echo '<input type="checkbox" name="oz_cid[]" value="'.htmlspecialchars($cid).'" onclick="ozUpdateCb(this);"/>';
			else echo '&nbsp;';
			echo '</td>';

			//Render image
			if (!$nocb) echo '<td width="1" class="oz_col_img" onclick="ozToggleRow(this);">';
			else echo '<td width="1" class="oz_col_img">';
			if (!empty($image)) echo '<div class="oz_contact_image"><div><img src="'.htmlspecialchars($image, ENT_COMPAT, 'UTF-8').'" class="oz_contact_img"/></div></div>';
			else echo '&nbsp;';				
			echo '</td>';
			
			//Render name
			if (!$nocb) echo '<td onclick="ozToggleRow(this);" class="oz_col_name">';
			else echo '<td class="oz_col_name">';
			//if (isset($c['x-profileurl'])) echo '<a href="'.$c['x-profileurl'].'">'.htmlspecialchars($name,ENT_COMPAT,'UTF-8').'</a>';
			//else echo htmlspecialchars($name,ENT_COMPAT,'UTF-8');
			if (!empty($namehtml)) echo '<div class=\'oz_name\'>'.$namehtml.'</div>';
			else echo '&nbsp;';
//			echo '</td>';

			//Render email			
//			if (!$nocb) echo '<td onclick="ozToggleRow(this);" class="oz_col_email">';
//			else echo '<td>';
			//if (isset($c['x-profileurl'])) echo '<a href="'.$c['x-profileurl'].'">'.htmlspecialchars($email,ENT_COMPAT,'UTF-8').'</a>';
			//else echo htmlspecialchars($email,ENT_COMPAT,'UTF-8');
			if (!empty($emailhtml)) echo '<div class=\'oz_email\'>'.$emailhtml.'</div>';
			else echo '&nbsp;';
			echo '</td>';

			//Render additional contact html			
			echo '<td class="oz_col_html">';
			if (isset($c['x-html'])) echo $c['x-html'];
			else echo '&nbsp;';
			echo '</td>';
			
			echo '</tr>';
		}
		$rowc++;
	}
?>
    </table>
  </div>
</div>  
  <?
}

if (count($contacts)>0) 
{
?>
  <div id="ozpanel_submitcontacts">
    <? if ($oz_is_email && (ozi_get_config('your_name',TRUE) || ozi_get_config('your_email',TRUE))) { ?>
    <div>
    <table cellpadding="0" cellspacing="0">
    <? if (ozi_get_config('your_name',TRUE)) { ?>
    <tr><td><span class="oz_field_label">{{YOUR_NAME}}&nbsp;</span></td><td><input type="text" name="oz_from_name" value="<? echo ozi_get_config('from_name','') ?>" size="24" class="oz_field_input" /></td></tr>
    <? } ?>
    <? if (ozi_get_config('your_email',TRUE)) { ?>
    <tr><td><span class="oz_field_label">{{YOUR_EMAIL}}&nbsp;</span></td><td><input type="text" name="oz_from_email" value="<? echo ozi_get_config('from_email','') ?>" size="24" class="oz_field_input" onchange="ozCheckEmailField(this)" /> *</td></tr>
    <? } ?>
    </table>
    </div>

    <? } ?>
	<div class="button_bar">
	<? if ($_GET['type']=='setup') { ?>	
		<button name="ozbtn_contacts" class="short_button">{{CONTACTS_SEND_INVITATION}}</button>
		<!--<button class="short_button">{{CONTACTS_SEND_AND_REPEAT}}</button>-->
	<? } else { ?>
		<button name="ozbtn_contacts" class="short_button">{{CONTACTS_SEND_INVITATION_APP}}</button>
	<? } ?>
	</div>
  </div>
  <?
}
?>
<!--
<img src="http://www.octazen.com/api/usage/?sec=cl&id={#TRKCDE#}" width="1" height="1"/>
-->
</form>
<script type="text/javascript">
//<![CDATA[
<? if (ozi_get_config('select_all_contacts',FALSE)) { ?>
DomReady.ready(function() {
	document.forms.oz_contacts_form.oz_select_all.checked=true;
	ozSelectAll(document.forms.oz_contacts_form.oz_select_all);
});
<? } ?>
DomReady.ready(function() {
	ozUpdateSelection();
	document.onmousemove=ozUpdateImagePosition;
});
ozNotifyViewChange('contacts');
//]]>
</script>
<? 

?>