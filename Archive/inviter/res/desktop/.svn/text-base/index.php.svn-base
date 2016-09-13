<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Import Contacts</title>
</head>
<body>
<style type="text/css">body,input {font-family:Arial, Helvetica, sans-serif;font-size: 11px;}</style>
<script type="text/javascript">
//<![CDATA[
//Callback scripts in this popup simply forward the call to the opener
function dabiSubmitResult(res) {opener.dabiSubmitResult(res);window.close();}
function dabiDownloadImporter() {opener.dabiDownloadImporter();window.close();}
function dabiUnsupportedBrowser() {opener.dabiUnsupportedBrowser();window.close();}
function dabiUnsupportedOS() {opener.dabiUnsupportedOS();window.close();}
//]]>
</script>
<?php
//Include the HTML code of ActiveX
$configstring = $_REQUEST['config'];
$html = file_get_contents(dirname(__FILE__).'/oz_desktop.html');
echo str_replace('{CONFIGSTRING}',$configstring,$html);
?>
</body>
</html>
