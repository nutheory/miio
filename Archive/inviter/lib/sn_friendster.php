<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

Friendster friends list importer and invite sender

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/
//include_once(dirname(__FILE__).'/abimporter.php');
if (!defined('__ABI')) die('Please include abi.php to use this invite sender!');

global $_OZ_SERVICES;
$_OZ_SERVICES['is_friendster'] = array('type'=>'is', 'label'=>'Friendster', 'class'=>'FriendsterInviter');

/////////////////////////////////////////////////////////////////////////////////////////
//FriendsterInviter
/////////////////////////////////////////////////////////////////////////////////////////
//@api
class FriendsterContact extends SocialContact {
	function FriendsterContact ($uid, $name, $imgurl) {
		parent::SocialContact($uid, $name, $imgurl);
	}
}

define('FriendsterInviter_SHOUTOUT_TOKEN_REGEX',"/id=\"addshoutout_([0-9a-f]+)_\\d+\">/ims");
define('FriendsterInviter_USERID_REGEX',"/var\\s+pageViewerID\\s*=\\s*\"([^\"]+)\"/ims");
define('FriendsterInviter_CONTACT_REGEX',"/<div\\s+class=\"flogriditem\".*?<div class=\"userThumbBg[^>]*>\\s*<a\\s+href=\"http:\/\/profiles.friendster.com\/(\\d+)\"\\s+title=\"([^\"]*)\"[^>]*?url\\s*\\(([^\\)]+)\\)/ims");

//@api
class FriendsterInviter extends WebRequestor {

	var $bulkQuotaReached = false;
	var $uid;

	//@api
	function getInfo () {
		return array('id'=>'friendster');
	}

	//@api
	function login ($email, $password) {
		oz_set_domain('friendster');

		$this->uid = null;
				
		$form = new HttpForm;
		$form->action = 'http://www.friendster.com/login.php?lang=en-US';
		$form->addField('_submitted','1');
		$form->addField('next','/');
		$form->addField('tzoffset','0');
		$form->addField('email',$email);
		$form->addField('password',$password);
		$form->addField('submit','Sign In');
		$form->addField("_authtrkcde", "{#TRKCDE#}");
		$html = $this->postForm($form);
		if (strpos($html, 'class="errorbox"')!==false ||
			strpos($this->lastUrl,'/login.php')!==false ||
			strpos($html, 'The email address and password you entered did not match')!==false ||
			strpos($html, 'The email address you entered is not a valid Friendster login')!==false) {
			$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad username or password');
		}

		if (strpos($html,'logout.php')==false) {
			$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find logout link');
		}

		if (preg_match(FriendsterInviter_USERID_REGEX,$html,$matches)) {
		 	$this->uid = $matches[1];
		}
		
		$this->bulkQuotaReached = false;

		return abi_set_success();
	}

	//@api
	function logout () {
		$this->httpGet("http://www.friendster.com/logout.php");
	}

	//@api
	function fetchContacts ($maxFetch=NULL, $maxPages=NULL) {

		//Get max contacts, pages, duration to fetch contacts
		if ($maxFetch===NULL) $maxFetch = oz_get_config('limit.max_contacts',1000);
		if ($maxPages===NULL) $maxPages = oz_get_config('limit.max_pages',30);


		//Loop
		$ids = array();
		$al = array();
		//Allow up to 30 pages of contacts
		for ($i=0; $i<$maxPages; ++$i) {

			$url = "http://www.friendster.com/friends.php?page=".$i."&uid=".$this->uid."&lang=en-US";
			$html = $this->httpGet($url);
			
			//Extract contacts on page
			preg_match_all(FriendsterInviter_CONTACT_REGEX, $html, $matches, PREG_SET_ORDER);
			$c = 0;
			foreach ($matches as $val) {
				$uid = trim($val[1]);
				if (!isset($ids[$uid])) {
					$ids[$uid] = $uid;
					$name = htmlentities2utf8(trim($val[2]));
					$imgurl = htmlentities2utf8(trim($val[3]));
					$contact = new FriendsterContact($uid,$name,$imgurl);
					$al[] = $contact;
					$c++;
					if (count($al)>=$maxFetch) break;
				}

			}
			if (count($al)>=$maxFetch) break;
			if ($i+1>=$maxPages) break;
			if ($c==0) break;
		}

		return $al;
	}

	//returns true if successful, false if failed.
	//Remember to keep message short (less than 4000 characters)
	//@api
	function sendMessage ($uid, $subject, $message, $saveToSent = false) {
		return $this->sendSingleMessage($uid,$subejct,$message);
		//$sca = array();
		//$sca[] = $uid;
		//return $this->sendMessages($sca,$subject,$message,$saveToSent);
	}

	function sendSingleMessage ($uid, $subject, $message) {
		if (oz_instanceof($uid,'SocialContact')) $uid = $uid->uid;
		$html = $this->httpGet('http://www.friendster.com/sendmessage.php?uid='.$uid."&firstname=");
		$form = oz_extract_form_by_name($html,'message_form');
		if ($form==null) {
			return false;	//Cannot find message form!
		}
		$form->setField("_submitted", "1");
		$form->setField("msg_type", "");
		$form->setField("uid", $uid);
		$form->setField("subject", $subject);
		$form->setField("message", $message);
		//$form->setField('inputcount',strlen($message));
		$postData = $form->buildPostData();
		$html = $this->httpPost($form->action, $postData);
		return strpos($html,'<div class="errorbox">')===false;
	}

	function sendIndividualMessages($contacts,$subject, $message) {
		$res = true;
		foreach ($contacts as $c) {
			$res &= $this->sendSingleMessage($c->uid, $subject, $message);
			if (!$res) {
				break;
			}
		}
		return $res==true;
	}

	//@api
	function sendMessages ($uids, $subject, $message, $saveToSent = false) {

		$message = substr($message,0,4000);

		// If bulk quota reached, then we need to send individual messages
		// instead...
		$cc = count($uids);
		if ($cc== 0)
			return true;
		if ($cc== 1)
			return $this->sendSingleMessage($uids[0], $subject, $message);
		if ($this->bulkQuotaReached) {
			return $this->sendIndividualMessages($uids, $subject, $message);
		}

		$html = $this->httpGet('/sendmessage.php?action=multiple');
		$form = oz_extract_form_by_name($html,'message_form');
		if ($form==null)
			return false;	//Cannot find message form!
		$form->removeField('afriend[]');
		$form->setField('_submitted','1');
		$form->setField('msg_type','multiple');
		$form->setField('uid','');
		//$form->setField('form_id','');
		$form->setField('subject',$subject);
		$form->setField('message',$message);
		foreach ($uids as $uid) {
			if (oz_instanceof($uid,'SocialContact')) $uid = $uid->uid;
			$form->addField('afriend[]',$uid);
		}
		$form->setField('action','multiple');
		$form->setField('inputcount',strlen($message));
		$form->setField('Submit','Send');
		$postData = $form->buildPostData();
		//'http://www.friendster.com/sendmessage.php'
		$html = $this->httpPost($form->action, $postData);

		$res = strpos($html,'<div class="errorbox">')===false;
		if ($res) return true;
		else {
			// On error, we switch to individual message delivery mode
			$this->bulkQuotaReached = true;
			return $this->sendIndividualMessages($uids,$subject,$message);
		}
	}

	//@api
	function postBulletin ($subject,$message) {

		$message = substr($message,0,4000);
	 
		$html = $this->httpGet("http://www.friendster.com/bulletin.php?lang=en-US");
		$form = oz_extract_form_by_name($html,"bulletin_form");
		if ($form==null) return false;
		$form->addField('subject',$subject);
		$form->addField('message',$message);
		$html = $this->postForm($form);
		return strpos($html,'<div class="errorbox">')===false;
	}

	//@api
	function postShoutOut ($message) {

		$html = $this->httpGet("http://www.friendster.com/shoutoutstream.php");
		if (!preg_match(FriendsterInviter_SHOUTOUT_TOKEN_REGEX,$html,$matches)) {
		 	abi_set_error(_ABI_FAILED,'Cannot find shoutout token');
		 	return false;
		}
		$authcode = $matches[1];

		$form = new HttpForm;
		$form->action = "http://www.friendster.com/rpc.php";
		$form->addField("rpctype", "addshoutout");
		$form->addField("authcode", $authcode);
		$form->addField("uid", $this->uid);
		$form->addField("shoutout", $message);
		$html = $this->postForm($form);

		return strpos($html,"\"status\":\"success\"")!==false;
	}

}