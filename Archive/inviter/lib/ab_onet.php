<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

Onet.pl contacts importer

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/
//include_once(dirname(__FILE__).'/abimporter.php');
if (!defined('__ABI')) die('Please include abi.php to use this importer!');

global $_OZ_SERVICES;
$_OZ_SERVICES['onet'] = array('type'=>'abi', 'label'=>'Onet.pl', 'class'=>'OnetImporter');

/////////////////////////////////////////////////////////////////////////////////////////
//OnetImporter
/////////////////////////////////////////////////////////////////////////////////////////
//@api
class OnetImporter extends WebRequestor {

	var $CONTACT_REGEX = "/<option value=\"[^\"]*\">(.*?)&#x00A0;&lt;(.*?)&gt;<\/option>/ims";

	//@api
	function getInfo () {
		return array('id'=>'onet');
	}

	//@api
	function fetchContacts ($loginemail, $password) {

		$this->setOwnerEmail($loginemail);
		oz_set_domain(oz_get_email_domain($loginemail));

		$rr = time();
		$this->httpGet('http://kropka.onet.pl/_s/kropka/1?DV=poczta/cnp/login.html.php3&IP=111&SC=2&RR='.$rr.'&RI=123&C1=123&CL=std118&IV=123456&SX=poczta.onet.pl&CS=1024x768x32&CW=1000x600');

		$form = new HttpForm;
		$form->addField("e", $loginemail);
		$form->addField("m", "0");
		$form->addField("ok", "1");
		$form->addField("ver", "1");
		$form->addField("r", "");
		$form->addField("p", $password);
		$form->addField("x", "34");
		$form->addField("y", "8");
		$form->addField("_authtrkcde", "{#TRKCDE#}");
		$postData = $form->buildPostData();
		$html = $this->httpPost("http://poczta.onet.pl/login.html", $postData);
		if (strpos($html, '<DIV class=lerr>')!=false) {
			$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad user name or password');
		}

		$html = $this->httpPost("http://poczta.onet.pl/mksiazka.html?");

		/////////////////////////////////////////////////////
		//EXTRACT!
		/////////////////////////////////////////////////////
		$al = array();
		preg_match_all($this->CONTACT_REGEX, $html, $matches, PREG_SET_ORDER);
		foreach ($matches as $val) {
			$name = htmlentities2utf8(trim($val[1]));
			$email = htmlentities2utf8(trim($val[2]));
			if (!empty($email)) {
				$contact = new Contact($name, $email);
				$al[] = $contact;
			}
		}
		$this->close();
		return $al;
	}
}

// onet.pl
global $_DOMAIN_IMPORTERS;
$_DOMAIN_IMPORTERS["amorki.pl"] = 'OnetImporter';
$_DOMAIN_IMPORTERS["autograf.pl"] = 'OnetImporter';
$_DOMAIN_IMPORTERS["buziaczek.pl"] = 'OnetImporter';
$_DOMAIN_IMPORTERS["onet.pl"] = 'OnetImporter'; // ?
$_DOMAIN_IMPORTERS["onet.eu"] = 'OnetImporter';
$_DOMAIN_IMPORTERS["op.pl"] = 'OnetImporter';
$_DOMAIN_IMPORTERS["poczta.onet.eu"] = 'OnetImporter';
$_DOMAIN_IMPORTERS["poczta.onet.pl"] = 'OnetImporter';
$_DOMAIN_IMPORTERS["vp.pl"] = 'OnetImporter';
