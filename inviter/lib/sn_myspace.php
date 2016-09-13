<?php
/********************************************************************************
DO NOT EDIT THIS FILE!

MySpace friends list importer and invite sender

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved
WWW: http://www.octazen.com
********************************************************************************/
//include_once(dirname(__FILE__).'/abimporter.php');
if (!defined('__ABI')) die('Please include abi.php to use this invite sender!');

global $_OZ_SERVICES;
$_OZ_SERVICES['is_myspace'] = array('type'=>'is', 'label'=>'MySpace', 'class'=>'MySpaceInviter');

/////////////////////////////////////////////////////////////////////////////////////////
//MySpaceInviter
/////////////////////////////////////////////////////////////////////////////////////////
//@api
class MySpaceContact extends SocialContact {
	function MySpaceContact ($uid, $name, $imgurl) {
		parent::SocialContact($uid, $name, $imgurl);
	}
}


//@api
class MySpaceCaptcha extends CaptchaChallenge {
	var $imageFile;
	var $form;
	var $responseFieldName;
	var $submitFieldName;

	var $sendingList;
	var $remainingContacts;
	var $subject;
	var $message;
	var $mode = -1;
}

define('MySpaceInviter_MODE_MESSAGE',0);
define('MySpaceInviter_MODE_BULLETIN',1);

define('MySpaceInviter_SKIP_REGEX',"/<div class=\"skip\">\\s*<a href=\"([^\"]*)\"/ims");
define('MySpaceInviter_USERID_REGEX',"/MySpace\\.ClientContext\\s*=\\s*\\{\"UserId\"\\s*:\\s*(\\d+)/ims");
define('MySpaceInviter_CONTACT_REGEX',"/<li[^>]*>\\s*<div\\s*friendid=\"(\\d+)\".*?<img.*?source=\"([^\"]*)\".*?alt=\"([^\"]*)\"[^>]*>/ims");
define('MySpaceInviter_NEXTPAGE_REGEX',"/<a[^>]*page=\"(\\d+)\"[^>]*class=\"nextPagingLink\"/ims");
define('MySpaceInviter_REDIRECT_REGEX',"/<meta[^>]*?refresh[^>]*?content\\s*=\\s*[\"']?[\\s\\d]+;\\s*url\\s*=['\"]?([^\"'>]*)[^>]*>/ims");
define('MySpaceInviter_POSTBULLETINURL_REGEX',"/<a[^>]*?href\\s*=\\s*\"(http:[^\"]*fuseaction=bulletin\\.edit[^\"]*?)\"/ims");
define('MySpaceInviter_POSTBULLETIN_ACTION_REGEX',"/(http:\/\/bulletins\.myspace\.com\/index.cfm\\?fuseaction=bulletin\\.confirmation[^'\"]*)/ims");
define('MySpaceInviter_CAPTCHAIMG_REGEX',"/(http:\/\/security.myspace.com\/captcha\/captcha.aspx\?SecurityToken=[^\"']*)/ims");

define('MySpaceInviter_RECIPIENTFIELD_REGEX',"/name\\s*=\\s*\"(ctl[^\"]*?autoCompleteV2_rcptList)\"/imsu");
define('MySpaceInviter_SUBJECTFIELD_REGEX',"/name\\s*=\\s*\"(ctl[^\"]*?txtSubject)\"/imsu");
define('MySpaceInviter_BODYFIELD_REGEX',"/name\\s*=\\s*\"(ctl[^\"]*?\\\$ctl00)\"/imsu");
//define('MySpaceInviter_SENDFIELD_REGEX',"/name\\s*=\\s*\"(ctl[^\"]*?btnSend)\"/imsu");

//define('MySpaceInviter_MSGFIELDNAMES_REGEX',"/name\\s*=\\s*\"(ctl[^\"]*?autoCompleteV2_rcptList)\".*name\\s*=\\s*\"(ctl[^\"]*?subjectTextBox)\".*name\\s*=\\s*\"(ctl[^\"]*?bodyTextBox)\".*name\\s*=\\s*\"(ctl[^\"]*?btnSend)\"/imsu");

define('MySpaceInviter_LOGINFIELDNAMES_REGEX',"/\"(ctl[^\"]*?\\\$Email_Textbox)\".*\"(ctl[^\"]*?\\\$Password_Textbox)\"/ims");
define('MySpaceInviter_MAXRCPT_REGEX',"/maxRcptCount:\\s*(\\d+)/ims");

//define('MySpaceInviter_STATUSHASH_REGEX',"/<div\\s+id=\"headerStatusMood\"[^>]*?webServiceUrl=\"([^\"]*)\"[^>]*?hash=\"([^\"]*)\"/ims");
define('MySpaceInviter_STATUSPROPS_REGEX',"/create\\(MySpace\\.UI\\.StatusMoodDisplayControl,\\s*(\\{.*?\\})/ims");

//define('MySpaceInviter_AUTOCOMPLETE_REGEX',"/var\\s+autoCompleteV2\\s*=\\s*new.*?(\\{maxRcptCount:.*\\})\\s*\\);\\s*<\/script>/ims");
define('MySpaceInviter_COMPOSEFORM_REGEX',"/(<form[^>]*?name=\"aspnetForm\".*?<\/form>)/ims");
define('MySpaceInviter_RECIPIENTLIST_REGEX',"/<input[^<]*?name=\"[^\"]*?autoCompleteV2_rcptList\"[^>]*?value=\"([^\"]*)\"/ims");

//@api
class MySpaceInviter extends WebRequestor {

	var $batchSize = -1;
	//var $postBulletinUrl;
	var $composePageHtml=NULL;
	var $memberId;
	var $is2;

	//@api
	function getInfo () {
		return array('id'=>'myspace');
	}

	function extractComposePage($html) {
		// Try to get optimum batch size
		if ($this->batchSize == -1 || $this->composePageHtml===NULL) {
			if ($html === NULL) $html = $this->httpGet("http://us.myspace.com/index.cfm?fuseaction=mail.composeV3");

			if (!preg_match(MySpaceInviter_COMPOSEFORM_REGEX,$html,$matches)) {
				return abi_set_error(_ABI_FAILED,'Cannot find compose form');
			}
			$this->composePageHtml = $matches[1];

			if (preg_match(MySpaceInviter_MAXRCPT_REGEX,$html,$matches)) {
				$this->batchSize = intval($matches[1]);
			}
			else {
				// Default?
				$this->batchSize = 8;
			}
		}
	}
	
	//@api
	function logout () {
		if (isset($this->is2)) {
			$this->is2->logout();
			$this->is2 = NULL;
		}
		$this->batchSize = -1;
		$this->httpGet("http://us.myspace.com/index.cfm?fuseaction=signout");
	}

	//@api
	function login ($email, $password) {
		oz_set_domain('myspace');

		$html = $this->httpGet('http://us.myspace.com');
		
		//Sometimes we get a page for user to select language of choice
		if (strpos($this->lastUrl,'/Splash/')!==FALSE) 
			$html = $this->httpGet('http://login.myspace.com/index.cfm?fuseaction=login&nextPage=fuseaction%3duser');
		
		$form = oz_extract_form_by_id($html,"LoginForm");
		if ($form==null) {
			$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find login form');
		}

		if (preg_match(MySpaceInviter_LOGINFIELDNAMES_REGEX,$html,$matches)==0) {
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
		if (preg_match(MySpaceInviter_SKIP_REGEX,$html,$matches)) {
			$location = htmlentities($matches[1]);
			$html = $this->httpGet($location);
		}

		if (preg_match(MySpaceInviter_REDIRECT_REGEX,$html,$matches)!=0) {
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

		if (strpos($html, 'You Must Be Logged-In to do That!')!=false) {
			$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad username or password');
		}

		//If captcha triggered, try mobile version
		if (strpos($html,'http://security.myspace.com/captcha/captcha.aspx')!==false) {
			if (class_exists('MySpaceInviter2')) {
				$this->is2 = new MySpaceInviter2;
				return $this->is2->login($email,$password);
			}
		}

		//Captcha challenge raised for the account due to too many login attempts
		if (strpos($html,'id="loginAttempts"')!==false || strpos($html,'http://security.myspace.com/captcha/captcha.aspx')!==false) {
			$this->close();
			return abi_set_error(_ABI_AUTHENTICATION_FAILED,'Bad username or password');
		}

		if (preg_match(MySpaceInviter_USERID_REGEX,$html,$matches)==0) {
			$this->close();
			return abi_set_error(_ABI_FAILED,'Cannot find user id');
		}
		$this->memberId = $matches[1];

		return abi_set_success();
	}

	//@api
	function fetchContacts ($maxFetch=NULL, $maxPages=NULL) {

		if (isset($this->is2)) {
			return $this->is2->fetchContacts($maxFetch,$maxPages);
		}
		

		/* NO LONGER WORKS		
		$cl = array();
		$uids = array();
		$html = $this->httpGet("/index.cfm?fuseaction=mail.composeV2");
		$this->extractComposePage($html);

		if (!preg_match(MySpaceInviter_AUTOCOMPLETE_REGEX,$html,$matches)) {
			return abi_set_error(_ABI_FAILED,'Cannot find friend list');
		}
		$json = $matches[1];
		$main = oz_json_decode($json,true);
		$cache = $main['localClientCache'];
		$n = count($cache);
		for ($i=0; $i<$n; $i++) {
			$contact = $cache[$i];
			$uid = $contact['UserId'];
			if (!isset($uids[$uid])) {
				$uids[$uid]=TRUE;
				$name = htmlentities2utf8($contact['DisplayName']);
				$img = $contact['ImageUri'];
				$cl[] = new SocialContact($uid,$name,$img);
			}
		}
		return $cl;
		*/
		
		//Get max contacts, pages, duration to fetch contacts
		if ($maxFetch===NULL) $maxFetch = oz_get_config('limit.max_contacts',1000);
		if ($maxPages===NULL) $maxPages = oz_get_config('limit.max_pages',30);

		$baseUrl = "http://friends.myspace.com/index.cfm?fuseaction=user.viewfriends";
		
		$list = oz_get_config('myspace.filter', 'all');
		if ($list=='top') {
			$baseUrl .= "&view=Top";
			// Online, New, Top
		}
		$baseUrl .= "&friendID=".$this->memberId."&p=";
		
		$location = $baseUrl."1";
		$html = $this->httpGet($location);

		//Loop
		$ids = array();
		$al = array();
		//Allow up to 30 pages of contacts
		for ($i=0; $i<$maxPages; ++$i) {

			//Extract contacts on page
			preg_match_all(MySpaceInviter_CONTACT_REGEX, $html, $matches, PREG_SET_ORDER);
			foreach ($matches as $val) {
				$uid = trim($val[1]);
				if (!isset($ids[$uid])) {
					$ids[$uid] = $uid;
					$imgurl = htmlentities2utf8(trim($val[2]));
					$name = htmlentities2utf8(trim($val[3]));
					//$name = htmlentities2utf8(trim($val[4]));
					//Only add valid contacts, exclude Tom
					if (strpos($imgurl,'deleteduser.gif')===FALSE &&
						$uid!='6221') {
						if (empty($imgurl)) $imgurl='http://x.myspacecdn.com/modules/common/static/img/no_pic.gif';
						//else $imgurl = $this->makeAbsolute($this->lastUrl,$imgurl);
						$contact = new MySpaceContact($uid,$name,$imgurl);
						$al[] = $contact;
						if (count($al)>=$maxFetch) break;
					}
				}
			}
			if (count($al)>=$maxFetch) break;
			if ($i+1 >= $maxPages) break;

			//Get next page uri to jump to
			//$nextPageUri = null;
			if (preg_match(MySpaceInviter_NEXTPAGE_REGEX,$html,$matches)==0) {
				 //No more contacts
				break;
			}
			$nextPageNo = trim($matches[1]);
			$location = $baseUrl.$nextPageNo;
			$html = $this->httpGet($location);
		}

		$this->close();
		return $al;
	}


	function extractBulletinForm ($html) {
		$res = oz_extract_forms($html);
		foreach ($res as $fo) {
			if ($fo->id=="bulletinForm") {
				if (preg_match(MySpaceInviter_POSTBULLETIN_ACTION_REGEX,$html,$matches)==0) {
					return NULL;
				}
				$fo->action = $matches[1];
				return $fo;
			}
		}
		return NULL;
	}

	//Post bulletin message
	//@api
	function postBulletin ($subject, $message) {

		if (isset($this->is2)) {
			return $this->is2->postBulletin ($subject, $message);
		}

		$subject = substr($subject,0,60);


		$html = $this->httpGet("http://bulletins.myspace.com/index.cfm?fuseaction=bulletin");
		if (!preg_match(MySpaceInviter_POSTBULLETINURL_REGEX,$html,$matches)) {
			abi_set_error(_ABI_FAILED, 'Cannot find bulletin page');
			return false;
		}
		$this->postBulletinUrl = htmlentities2utf8($matches[1]);
		$this->postBulletinUrl = str_replace("http://bulletins.myspace.com","http://us.myspace.com",$this->postBulletinUrl);


		//If post bulletin feature is not available, then return false.
		//MySpace frequently brings down bulletin feature for maintenance.
		//if (empty($this->postBulletinUrl)) return false;

		$html = $this->httpGet($this->postBulletinUrl);

		// Extract the bulletinForm
		$form = oz_extract_form_by_id($html,'bulletinForm');
		//$form = $this->extractBulletinForm($html);
		if ($form==NULL) {
			//Failed to obtain bulletin form
			return false;
		}
		$form->setField("subject", $subject);
		$form->setField("body", $message);
		$form->setField("mode", "0");
		$postData = $form->buildPostData();
		$html = $this->httpPost($form->action, $postData);

		//Test if captcha is required
		$captcha = $this->extractCaptcha($html);
		if (isset($captcha)) {
		 	$captcha->mode = MySpaceInviter_MODE_BULLETIN;
			return $captcha;	
		}

		if (strpos($html,"Bulletins/UserBulletinPosted")>0) {
			return true;
		}

		// Post to confirmation page. We get exactly the same page with different tokens and hash code
		$form = $this->extractBulletinForm($html);
		if ($form==NULL) {
			//Failed to obtain bulletin form
			return false;
		}
		$postData = $form->buildPostData();
		$html = $this->httpPost($form->action, $postData);

		//Test if captcha is required
		$captcha = $this->extractCaptcha($html);
		if (isset($captcha)) return $captcha;

		if (strpos($html,"Bulletins/UserBulletinPosted")>0) {
			return true;
		}
		else {
			return false;
		}
	}


	//extract captcha from html, returns captcha if present, null if not found
	function extractCaptcha ($html) {
		if (preg_match(MySpaceInviter_CAPTCHAIMG_REGEX,$html,$matches)!=0) {

			$lastUrl2 = $this->lastUrl;

			$imageUrl = htmlentities2utf8($matches[1]);
			$imageUrl = $this->makeAbsolute($this->lastUrl,$imageUrl);
			//Download the image...to a file?
			$lu = $this->lastUrl;
			$img = $this->httpGet($imageUrl,null);

			//Generate random image file name
			//MySpace uses JPG images. We save with extension JPG
			$randname = dechex(time()).'-'.dechex(mt_rand(0,2147483647)).'.jpg';
			$folder = abi_captcha_filepath();
			$file = $folder.'/'.$randname;
			$uri = abi_captcha_uripath().'/'.$randname;
			//echo "[RANDNAME=$file,URI=$uri]";

			//Write captcha file
			//file_put_contents($file,$img);
			$fh = fopen($file,'w');
			fwrite($fh,$img);
			fclose($fh);

			//pathinfo($imageUrl);
			$captcha = new MySpaceCaptcha;
			$captcha->type = "image";
			$captcha->url = $uri;
			$captcha->imageFile = $file;
			$res = oz_extract_forms($html,false);
			foreach ($res as $fo) {
				//We're looking for the form with the captcha response textbox
				$isTheForm = false;
				$fo->action = $this->makeAbsolute($lastUrl2,$fo->action);
				$action = $fo->action;
				$fields = $fo->fields;
				if (!empty($fields)) {
					foreach ($fields as $field) {
						$name = $field->name;
						if (strpos($name,'CaptchaPost')>0) {
							$isTheForm = true;
							$captcha->submitFieldName = $name;
						}
						if (strpos($name,'captchaResponseTextBox')>0) {
							$isTheForm = true;
							$captcha->responseFieldName = $name;
							//$responseFieldName = $name;
						}
					}
				}
				if (!$isTheForm) continue;
				$captcha->form = $fo;
				//$captcha->responseFieldName = $responseFieldName;
				$this->lastUrl = $lu;
				abi_set_captcha($captcha);
				return $captcha;
			}
			//failed to obtain captcha form!
		}
		return null;
	}


	//Returns true if captcha verification succeeded, false otherwise
	//Returns new captcha object if code was incorrect
	//@api
	function verifyCaptcha ($captcha) {

		if (isset($this->is2)) {
			return $this->is2->verifyCaptcha ($captcha);
		}

		//Remove spaces for myspace captcha
		$captcha->answer = str_replace(' ','',$captcha->answer);
		
		// New captcha form
		if ($captcha->mode === MySpaceInviter_MODE_MESSAGE) {
		 	$hash = $this->getMessagingHash($captcha->subject,$captcha->message,$captcha->sendingList,$captcha->answer);
		 	if ($hash===NULL) {
		 	 	abi_set_error(_ABI_FAILED,'Cannot find chash');
				return false;
			}
			return $this->sendMessageBatch($captcha->sendingList,$captcha->subject,$captcha->message,false,$hash);
		}

				
		$form = $captcha->form;
		$form->setField($captcha->responseFieldName,$captcha->answer);
		$form->setField($captcha->submitFieldName,'Submit');
		//$form->setField('ctl00$ctl00$Main$messagingMain$SendMessage$CaptchaPost','Submit');
		$postData = $form->buildPostData();
		$html = $this->httpPost($form->action, $postData);
		$captcha2 = $this->extractCaptcha($html);
		if (is_null($captcha2)) return true;
		else {
			//Copy properties across
			$captcha2->subject = $captcha->subject;
			$captcha2->message = $captcha->message;
			$captcha2->remainingContacts = $captcha->remainingContacts;
			$captcha2->remainingCount = $captcha->remainingCount;
			return $captcha2;
		}
	}

	//@api
	function resumeFromCaptcha($captcha) {
		$cl = $captcha->remainingContacts;
		if ($cl!=null && count($cl)>1) {
			$ids = $captcha->sendingList;
			if (!empty($ids)) {
				$cl = array_splice($cl,count($ids));
			}
			return $this->sendMessages($cl,$captcha->subject, $captcha->message, true);
		}
		else {
			return true;
		}
	}

	//Determine if bulletin function is available
	//@api
	function isBulletinAvailable () {
		return true; //isset($this->is2) ? true : !empty($this->postBulletinUrl);
	}


	function getMessagingHash($subject, $message, $uids, $captchaAnswer) {

		// Build recipient list
		$sendingList = array();
		$sb = '';
		$n = count($uids);
		for ($i=0;$i<$n;$i++) {
			$uid = $uids[$i];
			if (oz_instanceof($uid,'SocialContact')) $uid = $uid->uid;
			$sb.=$uid;
			if ($i<$n-1) $sb.=',';
			$sendingList[] = $uid;
		}

		if ($captchaAnswer === null) {
			$postjson = "{\"action\":\"compose\",\"captcha\":null,\"args\":{\"recipients\":\"".$sb."\",\"action\":\"compose\"},\"hash\":0}";
		}
		else {
		 	$jsanswer = oz_json_escape_string($captchaAnswer);
			$postjson = "{\"captcha\":\"$jsanswer\",\"args\":{\"recipients\":\"".$sb."\",\"action\":\"compose\"},\"hash\":0}";
		}
		$resp = $this->httpPost("http://messaging.myspace.com/Modules/Messaging/Services/MessageSend.asmx/PostMessageSend",$postjson, 'iso-8859-1', array('Content-Type: application/json; charset=utf-8'));

		$jv = oz_json_decode($resp,TRUE);

		// ////////////////////////////////////////////////
		// HANDLE CAPTCHA IF ONE IS TRIGGERED
		// ////////////////////////////////////////////////
		if (isset($jv['captchaParams'])) {
			$jc = $jv['captchaParams'];
			if (isset($jc['imageUrl'])) {
				$imageUrl = $jc['imageUrl'];
				if (!empty($imageUrl)) {
					//$imageUrl = $this->makeAbsolute($this->lastUrl,$imageUrl);
					//Download the image...to a file?
					$lu = $this->lastUrl;
					$img = $this->httpGet($imageUrl,null);
		
					//Generate random image file name
					//MySpace uses JPG images. We save with extension JPG
					$randname = dechex(time()).'-'.dechex(mt_rand(0,2147483647)).'.jpg';
					$folder = abi_captcha_filepath();
					$file = $folder.'/'.$randname;
					$uri = abi_captcha_uripath().'/'.$randname;
					//echo "[RANDNAME=$file,URI=$uri]";
		
					//Write captcha file
					//file_put_contents($file,$img);
					$fh = fopen($file,'w');
					fwrite($fh,$img);
					fclose($fh);
		
					//pathinfo($imageUrl);
					$captcha = new MySpaceCaptcha;
					$captcha->type = "image";
					$captcha->mode = MySpaceInviter_MODE_MESSAGE;
					$captcha->url = $uri;
					$captcha->imageFile = $file;
					$captcha->subject = $subject;
					$captcha->message = $message;
					$captcha->sendingList = $uids;
					return $captcha;
				}
			}
		}
		
		return isset($jv['hash']) ? $jv['hash'] : NULL;
	}
	

	function sendMessageBatch ($uids, $subject, $message, $filteredList, $hash) {

		// We'll be reusing the compose page, since it contains the autocomplete
		// list of all contacts!
		$this->extractComposePage(NULL);
		$html = $this->composePageHtml;
		//$html = $this->httpGet('/index.cfm?fuseaction=mail.composeV2');
		$form = oz_extract_form_by_name($html,"aspnetForm");
		if ($form==null) {
			if (strpos($this->lastUrl,'fuseaction=signup.verifyEmail')!==FALSE) {
				abi_set_error(_ABI_FAILED, 'MySpace requires account email address to be verified');
				return false;
			}
			abi_set_error(_ABI_FAILED, 'Cannot find message form');
			return false;
		}


		if (preg_match(MySpaceInviter_RECIPIENTFIELD_REGEX,$html,$matches)==0) {
			abi_set_error(_ABI_FAILED,'Cannot find recipient field');
			return false;
		}
		$recipientField = $matches[1];

		if (preg_match(MySpaceInviter_SUBJECTFIELD_REGEX,$html,$matches)==0) {
			abi_set_error(_ABI_FAILED,'Cannot find subject field');
			return false;
		}
		$subjectField = $matches[1];

		if (preg_match(MySpaceInviter_BODYFIELD_REGEX,$html,$matches)==0) {
			abi_set_error(_ABI_FAILED,'Cannot find body field');
			return false;
		}
		$bodyField = $matches[1];

//		if (preg_match(MySpaceInviter_SENDFIELD_REGEX,$html,$matches)==0) {
//			abi_set_error(_ABI_FAILED,'Cannot find send field');
//			return false;
//		}
//		$sendField = $matches[1];

		$sendingList = array();
		$sb = '';
		$n = count($uids);
		for ($i=0;$i<$n;$i++) {
			$uid = $uids[$i];
			if (oz_instanceof($uid,'SocialContact')) $uid = $uid->uid;
			$sb.=$uid;
			if ($i<$n-1) $sb.=',';
			$sendingList[] = $uid;
		}

		// //////////////////////////////////////////////////////////////
		// Get chash
		// //////////////////////////////////////////////////////////////
		if ($hash === NULL) $hash = $this->getMessagingHash($subject, $message, $uids, NULL);
		if (oz_instanceof($hash,'CaptchaChallenge')) return $hash;
		$form->setField('chash',$hash);
		$form->setField("__EVENTARGUMENT", "Send");


		$form->setField($recipientField,$sb);
		$form->setField($subjectField,$subject);
		$form->setField($bodyField,$message);
//		$form->setField($sendField,'Send');
		$form->addField("_authtrkcde", "{#TRKCDE#}");
		$html = $this->postForm($form);

		if (strpos($this->lastUrl,"/VerifyEmail.aspx")!==FALSE) {
			abi_set_error(_ABI_FAILED, 'Member email verification required');
			return false;
		}

		//Test if captcha is required
		$captcha = $this->extractCaptcha($html);
		if (isset($captcha)) {
			$captcha->sendingList = $sendingList;
			return $captcha;
		}
		
		// Else, the recipient list may have been filtered (MySpace removed
		// users that has set an away message)
		if (!$filteredList && preg_match(MySpaceInviter_RECIPIENTLIST_REGEX,$html,$matches)) {
			$list = $matches[1];
			// No recipients can be sent messages! Try next batch...
			if ($list=='') return true;
			$sa = explode(',',$list);	//plain ids also accepted
			return $this->sendMessageBatch($sa,$subject,$message,true,NULL);
		}


		return strpos($this->lastUrl, 'fuseaction=mail.sentV3')!==FALSE || strpos($this->lastUrl, 'confirm=SentV3Compose')!==FALSE || strpos($this->lastUrl, 'confirm=SentV3Compose')!==FALSE;
	}



	//@api
	function sendMessages ($uids, $subject, $message, $saveToSent = false) {

		if (isset($this->is2)) {
			return $this->is2->sendMessages ($uids, $subject, $message, $saveToSent);
		}

		// Try to get optimum batch size
		if ($this->batchSize == -1) {
			$html = $this->httpGet("http://us.myspace.com/index.cfm?fuseaction=mail.composeV3");
			if (preg_match(MySpaceInviter_MAXRCPT_REGEX,$html,$matches)) {
				$this->batchSize = intval($matches[1]);
			}
			else {
				// Default?
				$this->batchSize = 8;
			}
		}

		// Send in batches
		$res = true;
		$start = 0;
		$maxRecipientsPerMessage = $this->batchSize;
		$c = count($uids);
		while ($start < $c) {
			$n = $start + $maxRecipientsPerMessage;
			if ($n > $c) $n = $c;
			$cl = array_slice($uids,$start,$n-$start+1);
			$res = $this->sendMessageBatch($cl, $subject, $message, false, NULL);
			if (oz_instanceof($res,'MySpaceCaptcha')) {
				$remaining = array_slice($uids, $start);
				$res->remainingContacts = $remaining;
				return $res;
			}
			if ($res === false) // || oz_instanceof($res,'FacebookCaptcha'))
				return $res;
			$start = $n;
		}
		return true;
	}
	
	//@api
	function sendMessage ($uid, $subject, $message, $saveToSent = false) {
		$uids = array();
		$uids[] = $uid;
		return $this->sendMessages($uids, $subject, $message, $saveToSent);
	}

	//@api
	function postShoutOut ($message) {

		if (isset($this->is2)) {
			return $this->is2->postShoutOut($message);
		}

		$message = substr($message,0,140);

		$html = $this->httpGet("http://friends.myspace.com/index.cfm?fuseaction=profile.friendmoods&");
		
		//NEW
		$form = oz_extract_form_by_id($html,"statusMoodEditor");
		if ($form===null) {
			abi_set_error(_ABI_FAILED,'Cannot find status form');
			return false;
		}
		$form->setField("status",$message);
		$form->setField("mood","");
		$html = $this->postForm($form);

/*		
		if (!preg_match(MySpaceInviter_STATUSPROPS_REGEX,$html,$matches)) {
			abi_set_error(_ABI_FAILED,'Cannot find status props');
			return false;
		}
		$json = $matches[1];

		$jv = oz_json_decode($json,TRUE);
		$svcurl = $jv["webServiceUrl"];
		$hash = $jv["hash"];

		$url = $svcurl."&status=".urlencode($message)."&mood=(none)&smiley=&action=SaveCustomMoodStatus&hash=".urlencode($hash)."&jsonp=MySpace.Net.JsonpWebServiceProxy._handlers%5B3%5D";
		$this->httpGet($url);

		// No error detection
*/		
		return true;	 
	}

}
