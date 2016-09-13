<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

Naver.com contacts importer.

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/
if (!defined('__ABI')) die('Please include abi.php to use this importer!');

global $_OZ_SERVICES;
$_OZ_SERVICES['naver'] = array('type'=>'abi', 'label'=>'Naver.com', 'class'=>'NaverImporter');

//define('NaverImporter_CONTACT_REGEX',"/<option\\s+value=\"&quot;([^\"]*?)&quot;\\s+&lt;([^\"]*?)&gt;\">/ims");
define('NaverImporter_CGROUPS_REGEX',"/<input\\s+type=\"checkbox\"\\s+class=\"[^>]*?_fileExportGroup\"[^>]*?value=\"(8\\d+)\"/ims");

/////////////////////////////////////////////////////////////////////////////////////////
//NaverImporter
/////////////////////////////////////////////////////////////////////////////////////////
//@api
class NaverImporter extends WebRequestor {

	//@api
	function getInfo () {
		return array('id'=>'naver');
	}

	//@api
	function login ($loginemail, $password) {

		$parts = $this->getEmailParts ($loginemail);
		$login = $parts[0];
		$this->setOwnerEmail($loginemail);
		oz_set_domain($parts[1]);

		$form = new HttpForm;
		$form->action = "https://nid.naver.com/nidlogin.login";
		$form->addField("id",$login);
		$form->addField("pw", $password);
		$form->addField("url", "http://m.mail.naver.com/");
		$form->addField("svctype", "262144");
		$form->addField("viewtype", "");
		$form->addField("postDataKey", "");
		$form->addField("encpw", "");
		$form->addField("encnm", "");
		$form->addField("saveID", "0");
		$form->addField("enctp", "1");
		$form->addField("cPW", "");
		$form->addField("sPW", "");
		$form->addField("smart_level", "");
		$html = $this->postForm($form);
		if (strpos($html,"id=\"loginform\"")!==FALSE || strpos($html,"frmNIDLogin")!==FALSE) {
			$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad user name or password');
		}
		
		$html = $this->httpGet("http://m.mail.naver.com/");

		return abi_set_success();
	}
	
	//@api
	function fetchContacts ($loginemail, $password) {
	 
		if ($loginemail!==NULL || $password!==NULL) {
			$res = $this->login($loginemail,$password);
			if ($res!=_ABI_SUCCESS) return $res;
		}

		// Get groups
		$exportGroups = '';
		$html = $this->httpPost("http://contact.naver.com/addressFileDownload.nhn", null);
		preg_match_all(NaverImporter_CGROUPS_REGEX, $html, $matches, PREG_SET_ORDER);
		foreach ($matches as $val) {
		 	if ($exportGroups!=='') $exportGroups.=';';
		 	$exportGroups.=$val[1];
		}

		// Get contacts from all groups
		$form = new HttpForm;
		$form->action = "http://contact.naver.com/addressFileDownload.nhn?m=exportResult";
		$form->addField("fileType", "csv");
		$form->addField("exportFields", "name;repEmailAddress");
		$form->addField("exportGroups", $exportGroups); // "8000101017;8000039350");
		$html = $this->postForm($form);
				
		$reader = new OzCsvReader($html);
		$cols = $reader->nextRow();
		if ($cols===NULL) {
			return abi_set_error(_ABI_FAILED,'Unexpected CSV. Missing header row.');
		}
		$cl = array();
		while (true) {
			$cols = $reader->nextRow();
			if ($cols===null) break;
			if (count($cols)>=2) {
				$name = htmlentities2utf8(trim($cols[0]));
				$email = trim($cols[1]);
				if (abi_valid_email($email)) {
					$cl[]= new Contact($name,$email);
				}
			}
		}
		return $cl;
	}
}

//naver.com
global $_DOMAIN_IMPORTERS;
$_DOMAIN_IMPORTERS["naver.com"] = 'NaverImporter';