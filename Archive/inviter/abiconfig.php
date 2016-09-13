<?
/********************************************************************************
Contacts Importer / Invite Sender Configuration File

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/
define('_ABI_CONFIG_FILE','');

global $_OZCORE_CONFIG;
$_OZCORE_CONFIG = array(

	//Enable debug mode if true
	'debug' => FALSE,

	//Enable GZip compression if true
	'gzip' => TRUE,

	//Enable HTTP 1.1 features where supported if true
	'http1_1' => FALSE,

	//GoDaddy users, please enable this line to make use of their proxy
	//'curl_proxy' => 'http://proxy.shr.secureserver.net:3128',

	//Other proxy settings
	//'curl_proxytype' => CURLPROXY_SOCKS5,
	//'curl_proxyport' => 3128,

	//
	//As a safety precaution, we disable housekeeping of captcha cache folder by default.
	//To turn on housekeeping of captcha cache, set this value to true. Housekeeping involves
	//deleting all files in the captcha folder.
	//
	'housekeep_captcha' => FALSE,


	//File and URI path to the captcha folder. Defaults to a "captcha" directory relative to PHP script
	'captcha_file_path' => './captcha',
	'captcha_uri_path' => './captcha',


	//Take IM address as email where possible
	'im_as_email' => FALSE,

	//Take email as name if name is empty
	'email_as_name' => TRUE,


	//MySpace: Show "all" or "top" contacts
	'myspace.filter' => 'all',
	
	//Gmail: Use 'all' or 'mycontacts'
	'gmail.filter' => 'all',
	


	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! REMOVE ME
//'curl_proxy' => 'http://127.0.0.1:8888',

	
	'x'=>''
);

/*ZL_NOENCRYPT*/