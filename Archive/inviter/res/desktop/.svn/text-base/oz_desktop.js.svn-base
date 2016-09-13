/********************************************************************************
ActiveX/Desktop Contacts Importer
Utility functions & monitoring of pasted results

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
Email: support@octazen.com
********************************************************************************/

var oz_dabi_pasteMonitorIntervalId = null;
var oz_dabi_textarea = null;
var oz_dabi_textarea_msg = null;
var oz_dabi_window=null;

//--------------------------------------------------------
//Begin monitoring a textarea component for the pasted result.
//Polling is done at 100ms interval. dabiSubmitResult() is 
//called once proper result is detected.
//
//textarea - Textarea DOM object to be monitored
//textarea_message - Instruction text in the text area
//--------------------------------------------------------
function ozDabiStartPasteMonitor(textarea, textarea_message) {
	oz_dabi_textarea = textarea;
	oz_dabi_textarea_msg = textarea_message;
	if (oz_dabi_pasteMonitorIntervalId==null) 
		oz_dabi_pasteMonitorIntervalId = setInterval("ozDabiDetectPaste()",100);
}		

//--------------------------------------------------------
//Stop monitoring a textarea component for the pasted result.
//--------------------------------------------------------
function ozDabiStopPasteMonitor() {
	if (oz_dabi_pasteMonitorIntervalId!=null)
		clearInterval(oz_dabi_pasteMonitorIntervalId);
	oz_dabi_pasteMonitorIntervalId = null;
}

function ozDabiDetectPaste() {
	var v = oz_dabi_textarea.value;
	var i1 = v.indexOf("-----BEGIN CONTACTS-----");
	var i2 = v.indexOf("-----END CONTACTS-----");
	if (i1!=-1 && i1<i2) {dabiSubmitResult(v);}
	else {oz_dabi_textarea.value=oz_dabi_textarea_msg;}
}

function ozDabiIsIE() {return /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent);}
//function ozDabiIsWindows() {return /windows/i.test(navigator.userAgent);}
function ozDabiIsWindows() {return navigator.appVersion.indexOf("Win")!=-1;}
function ozDabiHide(d) {var ele = document.getElementById(d);if (ele!=null && ele!=undefined) ele.style.display = "none";}
function ozDabiShow(d) {var ele = document.getElementById(d);if (ele!=null && ele!=undefined) ele.style.display = "block";}

//Open popup window 
function ozDabiLaunchPopup(url,frame,width,height) {
	if (oz_dabi_window!=null) {oz_dabi_window.close();oz_dabi_window = null;}
	frame = typeof(frame) != 'undefined' ? frame : '_aximport';
	width = typeof(width) != 'undefined' ? width : 500;
	height = typeof(height) != 'undefined' ? height : 250;
    var left = (screen.width) ? (screen.width-width)/2 : 0;
    var top = (screen.height) ? (screen.height-height)/2 : 0;
    oz_dabi_window = open(url,frame,'toolbar=no,resizable=yes,scrollbars=no,directories=no,menubar=no,status=yes,width='+width+',height='+height+',top='+top+',left='+left)
}

