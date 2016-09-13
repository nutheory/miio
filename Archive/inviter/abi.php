<?
/********************************************************************************
DO NOT EDIT THIS FILE!

Main include file that includes all other necessary PHP files for the
contacts importer and invite sender libraries.

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/

//@api
function oz_set_config($key,$value) {
	global $_OZCORE_CONFIG;
	if (!isset($_OZCORE_CONFIG)) $_OZCORE_CONFIG=array();
	$_OZCORE_CONFIG[$key]=$value;
}
//@api
function oz_get_config($key,$default=NULL) {
	global $_OZCORE_CONFIG;
	if (!isset($_OZCORE_CONFIG)) return $default;
	return isset($_OZCORE_CONFIG[$key]) ? $_OZCORE_CONFIG[$key] : $default;
}
//@api
function oz_defined_config($key) {
	global $_OZCORE_CONFIG;
	if (!isset($_OZCORE_CONFIG)) return false;
	return isset($_OZCORE_CONFIG[$key]) && $_OZCORE_CONFIG[$key]!=='' && $_OZCORE_CONFIG[$key]!==NULL;
}


include(dirname(__FILE__)."/abiconfig.php");

//Migrate from legacy values if defined
if (defined('_ABI_DEBUG')) oz_set_config('debug',_ABI_DEBUG==1);
if (defined('_ABI_GZIP')) oz_set_config('gzip',_ABI_GZIP==1);
if (defined('_ABI_HTTP1_1')) oz_set_config('http1_1',_ABI_HTTP1_1==TRUE);
if (defined('_ABI_PROXY')) oz_set_config('curl_proxy',_ABI_PROXY);
if (defined('_ABI_PROXYPORT')) oz_set_config('curl_proxyport',_ABI_PROXYPORT);
if (defined('_ABI_PROXYTYPE')) oz_set_config('curl_proxytype',_ABI_PROXYTYPE);
if (defined('_ABI_HOUSEKEEP_CACHE')) oz_set_config('housekeep_captcha',_ABI_HOUSEKEEP_CACHE==1);
if (defined('_ABI_CAPTCHA_FILE_PATH')) oz_set_config('captcha_file_path',_ABI_CAPTCHA_FILE_PATH);
if (defined('_ABI_CAPTCHA_URI_PATH')) oz_set_config('captcha_uri_path',_ABI_CAPTCHA_URI_PATH);
if (defined('_ABI_IM_AS_EMAIL')) oz_set_config('im_as_email',_ABI_IM_AS_EMAIL==TRUE);
if (defined('_ABI_EMAIL_AS_NAME')) oz_set_config('email_as_name',_ABI_EMAIL_AS_NAME==TRUE);


include(dirname(__FILE__)."/lib/oz_main.php");