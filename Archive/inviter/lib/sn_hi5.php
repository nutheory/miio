<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

Hi5 friends list importer and invite sender

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/
//include_once(dirname(__FILE__).'/abimporter.php');
if (!defined('__ABI')) die('Please include abi.php to use this invite sender!');

global $_OZ_SERVICES;
$_OZ_SERVICES['is_hi5'] = array('type'=>'is', 'label'=>'Hi5', 'class'=>'Hi5Inviter');

/////////////////////////////////////////////////////////////////////////////////////////
//Hi5Inviter
/////////////////////////////////////////////////////////////////////////////////////////
//@api
class Hi5Contact extends SocialContact {
	function Hi5Contact ($uid, $name, $imgurl) {
		parent::SocialContact($uid, $name, $imgurl);
	}
}

//define('Hi5Inviter_USERID_REGEX',"/HI5\\.Data\\.register\\s*\\(\\s*'loggedInUserId'\\s*,\\s*'(\\d+)/ims");

define('Hi5Inviter_NEXTPAGE_REGEX',"/<a\s+href=\"([^\"]*?page=next)\"/ims");
define('Hi5Inviter_CONTACT_REGEX',"/<div\\s+class=\"friend-instance before\">.*?<div\\s+class=\"friend-pic\">.*?<img[^>]*src=\"([^\"]*)\".*?<div\\s+class=\"friend-name\">\\s*<a\\s+href=\"[^\"]*\/friend\/p(\\d+)--[^>]*>([^<]*)/ims");
define('Hi5Inviter_BATCH_SIZE',15);


//@api
class Hi5Inviter extends WebRequestor {

	var $userId;
	var $id;

	//@api
	function getInfo () {
		return array('id'=>'hi5');
	}
	
	function Hi5Inviter() {
	 	$this->WebRequestor();
	 
		$this->id=rand(0,100000);		
	}

	//@api
	function login ($email, $password) {
		oz_set_domain('hi5');

		$html = $this->httpGet("http://www.hi5.com/friend/login.do");		

		$form = new HttpForm;
		$form->action = 'http://www.hi5.com/friend/login.do';
		$form->addField('email',$email);
		$form->addField('password',$password);
		//$form->addField('submit','Login');
		$form->addField('type','1');
		$form->addField("_authtrkcde", "{#TRKCDE#}");
		$html = $this->postForm($form);

		if (strpos($html, 'Your login and/or password were incorrect')!==FALSE ||
			strpos($html, 'id="message-error"')!==FALSE ) {
			$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad user name or password');
		}

/*
		if (strpos($html,'/displayImportStep.do')!==FALSE) {
			$html = $this->httpGet('http://www.hi5.com/friend/group/displayGroupHome.do');
		}
		if (!preg_match(Hi5Inviter_USERID_REGEX,$html,$matches)) {
			$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find userid cookie');
		}
		$this->userId = $matches[1];
*/

///*
		//Get User ID
		$ids = $this->cookiejar->getCookieValues('http://www.hi5.com/','Userid');
		if (empty($ids) || count($ids)==0) {
			$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find userid cookie');
		}
		$this->userId = $ids[0];
		if (empty($this->userId)) {
			$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find userid cookie2');
		}
//*/		

//echo '[THIS='.$this->id.']';
//echo "[USERID1=".$this->userId.']';

		return abi_set_success();		
	}

	//@api
	function logout () {
		$this->httpGet('http://www.hi5.com/friend/logoff.do');
	}

	//@api
	function fetchContacts ($maxFetch=NULL, $maxPages=NULL) {

		//Get max contacts, pages, duration to fetch contacts
		if ($maxFetch===NULL) $maxFetch = oz_get_config('limit.max_contacts',1000);
		if ($maxPages===NULL) $maxPages = oz_get_config('limit.max_pages',30);

//echo '[THIS='.$this->id.']';
//echo "[USERID2=".$this->userId.']';

		//$location = "http://www.hi5.com/friend/".'1718316'."--xxx--Friends-html";
		$location = "http://www.hi5.com/friend/".$this->userId."--xxx--Friends-html";
		$html = $this->httpGet($location);

		//Loop
		$ids = array();
		$al = array();
		//Allow up to 30 pages of contacts
		for ($i=0; $i<$maxPages; ++$i) {

			//Extract contacts on page
			preg_match_all(Hi5Inviter_CONTACT_REGEX, $html, $matches, PREG_SET_ORDER);
			if (!empty($matches)) {
				foreach ($matches as $val) {
					$uid = trim($val[2]);
					if (!isset($ids[$uid])) {
						$ids[$uid] = $uid;
						$name = htmlentities2utf8(trim($val[3]));
						$imgurl = $this->makeAbsolute($this->lastUrl, htmlentities2utf8(trim($val[1])));
						$contact = new Hi5Contact($uid,$name,$imgurl);
						$al[] = $contact;
						if (count($al)>=$maxFetch) break;
					}
				}
			}
			/*
			else {
				preg_match_all($this->CONTACT2_REGEX, $html, $matches, PREG_SET_ORDER);
				foreach ($matches as $val) {
					$uid = trim($val[3]);
					if (!isset($ids[$uid])) {
						$ids[$uid] = $uid;
						$name = htmlentities2utf8(trim($val[1]));
						$imgurl = htmlentities2utf8(trim($val[2]));
						$contact = new Hi5Contact($uid,$name,$imgurl);
						$al[] = $contact;
						if (count($al)>=$maxFetch) break;
					}
				}
			}
			*/
			if (count($al)>=$maxFetch) break;
			if ($i+1>=$maxPages) break;

			//Get next page uri to jump to
			$nextPageUri = null;
			if (preg_match(Hi5Inviter_NEXTPAGE_REGEX,$html,$matches)) {
				$nextPageUri = htmlentities2utf8($matches[1]);
				$nextPageUri = $this->makeAbsolute($this->lastUrl, $nextPageUri);
			}
			//If we're at the last/only page, then exit
			else {
				break;
			}
			$html = $this->httpGet($nextPageUri);
		}

		return $al;
	}

	//returns true if successful, false if failed.
	//Remember to keep message short (less than 5000 characters)
	//@api
	function sendMessage ($uid, $subject, $message, $saveToSent = false) {

		$uids = array();
		$uids[] = $uid;
		$res = $this->sendMessages($uids, $subject, $message, $saveToSent);
		//if (oz_instanceof($res,'CaptchaChallenge') && !$returnCaptcha) {
		//	return _ABI_FAILED;
		//}
		return $res;
	}

	//@api
	function postShoutOut ($message) {

		$html = $this->httpGet("/friend/profile/displayScrapbook.do?userid=".$this->userId."&editTopic=1");
		$form = oz_extract_form_by_name($html, 'scrapbookTopicForm');
		if ($form==NULL)
		{
			abi_set_error(_ABI_FAILED, 'Cannot find scrapbook form');
			return false;
		}
		$form->setField("topic",$message);
		$postData = $form->buildPostData();
		$html = $this->httpPost($form->action,$postData);
		abi_set_success();
		return $this->lastStatusCode==200 && strpos($html,'id="Message-Success"')!==false;
	}


	//returns true if successful, false if failed.
	//Send a message to multiple recipients
	function internalSendMessages ($uids, $startIndex, $endIndex, $subject, $message, $saveToSent = false) {

		$message = substr($message,0,5000);

		//Build friends list
		$friends = '';
		for ($i=$startIndex; $i<$endIndex; $i++) {
			if ($i>$startIndex) $friends.='###';
			$uid = $uids[$i];
			if (oz_instanceof($uid,'SocialContact')) $uid = $uid->uid;
			$friends.="F:$uid";
		}

		$html = $this->httpGet("http://www.hi5.com/friend/mail/displayComposeMail.do");
		$form = oz_extract_form_by_name($html, "composeForm");
		if ($form==null) return false;
		$form->setField("toIds",$friends);
		$form->setField("subject",$subject);
		$form->setField("method", "send");
		$form->setField("body",$message);
		$form->setField("mailOp", "");
		$form->setField("senderId", "");
		$form->setField("msgId", "");
		$form->setField("submitSend", "Send Message");
		$postData = $form->buildPostData();
		$html = $this->httpPost($form->action, $postData);
		//If we have the "tick" icon, then its a success
		return strpos($html,'id="Message-Success"')!==false;
	}


	//@api
	function sendMessages ($uids, $subject, $message, $saveToSent = false) {
		// Send in batches of 8
		$res = true;
		$start = 0;
		$maxRecipientsPerMessage = Hi5Inviter_BATCH_SIZE;
		$c = count($uids);
		while ($start < $c) {
			$n = $start + $maxRecipientsPerMessage;
			if ($n > $c) $n = $c;
			$res = $this->internalSendMessages($uids, $start, $n, $subject, $message);
			if ($res === false)
				return $res;
			$start = $n;
		}
		return true;
	}


/*
	function postShoutOut ($message) {
		//Shoutouts are now bulletin posts
		return $this->postBulletin("Shoutout",$message);
	}
*/
/*
	function postBulletin ($subject,$message) {
		return $this->sendMessage('C:100',$subject,$message,false);
	}
*/
}