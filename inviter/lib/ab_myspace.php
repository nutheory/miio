<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

MySpace address book importer

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/
if (!defined('__ABI')) die('Please include abi.php to use this invite sender!');

global $_OZ_SERVICES;
$_OZ_SERVICES['myspace'] = array('type'=>'abi', 'label'=>'MySpace', 'class'=>'MySpaceImporter');

/////////////////////////////////////////////////////////////////////////////////////////
//MySpaceImporter
/////////////////////////////////////////////////////////////////////////////////////////

define('MySpaceImporter_SKIP_REGEX',"/<div class=\"skip\">\\s*<a href=\"([^\"]*)\"/ims");
define('MySpaceImporter_USERID_REGEX',"/MySpace\\.ClientContext\\s*=\\s*\\{\"UserId\"\\s*:\\s*(\\d+)/ims");
//define('MySpaceImporter_CONTACT_REGEX',"/hashJsonContacts.add\\(\\d+,\\s*('.*?')\\);\\r\\n/ims");
//define('MySpaceImporter_NEXTPAGE_REGEX',"/<a\\s+class=\"pagingLink\"\\s+href=\"javascript:__doPostBack\\('([^']*?\\\$pagerHeader)','(\\d+)'\\)\">[^<]*&rsaquo;<\/a>/ims");
define('MySpaceImporter_REDIRECT_REGEX',"/<meta[^>]*?refresh[^>]*?content\\s*=\\s*[\"']?[\\s\\d]+;\\s*url\\s*=['\"]?([^\"'>]*)[^>]*>/ims");
define('MySpaceImporter_LOGINFIELDNAMES_REGEX',"/\"(ctl[^\"]*?\\\$Email_Textbox)\".*\"(ctl[^\"]*?\\\$Password_Textbox)\"/ims");

define('MySpaceImporter_HASH_REGEX',"/MySpace\\.Messaging\\.pageHash\\s*=\\s*\"([^\"]*)\"/ims");

//@api
class MySpaceImporter extends WebRequestor {

	var $memberId;

	//@api
	function getInfo () {
		return array('id'=>'myspace');
	}

	//@api
	function logout () {
		$this->httpGet("http://us.myspace.com/index.cfm?fuseaction=signout");
	}

	//@api
	function login ($email, $password) {


		//If login email ends with ".yahoo" only, then we remove it
		$email = preg_replace("/^(.*?)(\.myspace)$/ims", '${1}', $email);
		$this->setOwnerEmail($email);
		oz_set_domain('myspaceab');

		$html = $this->httpGet('http://us.myspace.com');
		
		//Sometimes we get a page for user to select language of choice
		if (strpos($this->lastUrl,'/Splash/')!==FALSE) 
			$html = $this->httpGet('http://login.myspace.com/index.cfm?fuseaction=login&nextPage=fuseaction%3duser');
		
		$form = oz_extract_form_by_id($html,"LoginForm");
		if ($form==null) {
			$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find login form');
		}

		if (preg_match(MySpaceImporter_LOGINFIELDNAMES_REGEX,$html,$matches)==0) {
			$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find login field names');
		}
		$emailField = $matches[1];
		$passField = $matches[2];
		$form->setField($emailField,$email);
		$form->setField($passField,$password);
		$form->setField("dlb", "Log In");
		$postData = $form->buildPostData();
		$html = $this->httpPost($form->action, $postData);
		if (strpos($html, 'You Must Be Logged-In to do That!')!==false ||
			strpos($html, 'populateInvalidLogin')!==false) {
			$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad username or password');
		}

		//May get advert. Just jump to main page
		if (preg_match(MySpaceImporter_SKIP_REGEX,$html,$matches)) {
			$location = htmlentities($matches[1]);
			$html = $this->httpGet($location);
		}

		if (preg_match(MySpaceImporter_REDIRECT_REGEX,$html,$matches)!=0) {
			$location = $matches[1];
			//Handle bad MySpace advert redirect ("fuseaction=user" instead of "index.cfm?fuseaction=user")
			if (strpos($location,"action=user")>0) {
				$location = 'http://home.myspace.com/index.cfm?fuseaction=user';
			}
			$html = $this->httpGet($location);
		}
		else {
		}

		//Check for intersitials
		if (strpos($this->lastUrl,'fuseaction=CampaignInterstitial')!==FALSE) {
			$location = 'http://home.myspace.com/index.cfm?fuseaction=user';
			$html = $this->httpGet($location);
		}

		if (strpos($html, 'You Must Be Logged-In to do That!')!==false ||
			strpos($html, 'populateInvalidLogin')!==false) {
			$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad username or password');
		}

		//Captcha challenge raised for the account due to too many login attempts
		if (strpos($html,'id="loginAttempts"')!==false || strpos($html,'http://security.myspace.com/captcha/captcha.aspx')!==false) {
			$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad username or password');
		}

		if (preg_match(MySpaceImporter_USERID_REGEX,$html,$matches)==0) {
			$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find user id');
		}
		$this->memberId = $matches[1];

		return abi_set_success();
	}


	//@api
	function fetchContacts ($loginemail=NULL,$password=NULL) {

		if ($loginemail!=NULL && $password!=NULL) {
			$res = $this->login($loginemail,$password);
			if ($res!=_ABI_SUCCESS) return $res;
		}

		// Get hash
		$html = $this->httpGet("http://messaging.myspace.com/index.cfm?fuseaction=mail.addressbookV3");
		if (!preg_match(MySpaceImporter_HASH_REGEX,$html,$matches)) {
			return abi_set_error(_ABI_FAILED, 'Cannot find hash');
		}
		$hash = $matches[1];
		
		// Get addressbook
		$postjson = "{\"hash\":\"".$hash."\",\"jsonParams\":{\"pageOffset\":0,\"fetchLimit\":2000}}";
		$resp = $this->httpPost("/Modules/Invites/Services/AddressBookService.asmx/GetAddresBookContacts",$postjson, 'iso-8859-1', array('Content-Type: application/json; charset=utf-8'));
		$jv = oz_json_decode($resp,TRUE);
		$jv = @$jv['Data'];
		$jv = @$jv['items'];
		if ($jv===NULL || $jv===FALSE) {
			return abi_set_error(_ABI_FAILED, 'Cannot parse json');
		}

		$al = array();
		foreach ($jv as $v) {
		 	if (isset($v['email'])) {
			 	$email = $v['email'];
			 	$name = NULL;
			 	if (isset($v['name'])) {
			 	 	$v2 = $v['name'];
			 	 	if (isset($v2['contactFullName'])) $name=$v2['contactFullName'];
				}
				if (abi_valid_email($email)) {
					$al[]= new Contact($name,$email);
				}
			}
		}
		return $al;
	}

}

// MySpace
global $_DOMAIN_IMPORTERS;
$_DOMAIN_IMPORTERS["myspace"]='MySpaceImporter';
