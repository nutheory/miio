<?php

include "/miio_config/server_config.php";
date_default_timezone_set("America/Los_Angeles");
set_error_handler("error_handler");
$today = date('Ymd');
$now = date('H:i:s');
$fout = fopen("/logs/msg_$today.log","a");

fwrite($fout,"$now\nStart message_handler\n");
fwrite($fout,$_SERVER['SERVER_NAME']."\n");
//fwrite($fout,print_r($_SERVER,true));
$fd = fopen("php://stdin", "r");
$email = "";
$headersdone = false;
$raw = "";
while (!feof($fd))
{
  $line = fgets($fd,1024);
  $raw .= $line;
}
fclose($fd);
fwrite($fout,"Raw:------------------------------\n");
fwrite($fout,"$raw\n");
fwrite($fout,"------------------------------\n");

// search for content-type mixed
$mixed = strpos($raw,"Content-Type: multipart/mixed;");
if ($mixed)
{
  // get position of boundary
  $bound = strpos($raw,"boundary=",$mixed);
  $bstart = strpos($raw,"\"",$bound)+1;
  $bend = strpos($raw,"\"",$bstart);
  $boundary = substr($raw,$bstart,$bend-$bstart);
  $boundout = substr($raw,$bend+1);
  $plstart = strpos($boundout,$boundary)+strlen($boundary);
  $plend = strpos($boundout,$boundary,$plstart);
  $plaintext = substr($boundout,$plstart,$plend-$plstart);
  
}
else
{
  $plaintext = $raw;
}

// look for Content-Transfer-Encoding:

$encstart = strpos($plaintext,"Content-Transfer-Encoding:");
if ($encstart > 0)
{
  $encend = strpos($plaintext,"\n",$encstart);
  $encoding = substr($plaintext,$encstart,$encend-$encstart);
  $encoding = trim(str_replace("Content-Transfer-Encoding:","",$encoding));
  $text = trim(substr($plaintext,$encend));
  
  if ($encoding=="BASE64")
  {
    // unencode
    $text = trim(base64_decode($text));
    $brk = strpos($text,"\n");
    $text = substr($text,0,$brk);
  }
}

// get TO

$tostart = strpos($raw,"To:")+3;
$toend = strpos($raw,"\n",$tostart);
$to = trim(substr($raw,$tostart,$toend-$tostart));
$temp = explode("@",$to);
$messageid = $temp[0];

// get FROM

$fromstart = strpos($raw,"From:")+5;
$fromend = strpos($raw,"\n",$fromstart);
$from = trim(substr($raw,$fromstart,$fromend-$fromstart));
$temp = explode("@",$from);
$mobilenumber = $temp[0];
$provider = $temp[1];
if (substr($mobilenumber,0,1)=='1') $mobilenumber = substr($mobilenumber,1);

// get SUBJECT

$subjstart = strpos($raw,"Subject:")+8;
$subjend = strpos($raw,"\n",$subjstart);
$subj = trim(substr($raw,$subjstart,$subjend-$subjstart));
if ($subj == 'RE:') $subj="";

// get text from ATT

if ( ($provider == 'txt.att.net') || ($provider == 'cingularme.com') )
{
  $textstart = strpos($raw,"\n\n")+2;
  $textend = strpos($raw,"\n\n",$textstart);
  $text = trim(substr($raw,$textstart,$textend-$textstart));
}
// strip metadata from Verizon
else if (strtolower($provider) == 'vtext.com')
{
  $textstart = strpos($text,")",strpos($text,")"))+1;
  $text = trim(substr($text,$textstart));
  $subj = "";
}


// strip double spaces in message text
$pattern = '/\s+/';
$text = preg_replace($pattern," ",$text);

include "/miio_config/db.php";
$Cache = new Memcache;
$Cache->addServer(CACHE_HOST,CACHE_PORT) or die ("unable to connect to cache");
// add other cache servers
if (CACHE_SERVERS > 1)
{
  foreach ($CACHE as $cache)
  {
    $Cache->addServer($cache['host'],$cache['port']);
  }
}
include "/miio_config/lib.php";
include "/miio_config/User.php";
include "/miio_config/Post.php";
include "/miio_config/PostIndex.php";
$Posts = new Post();

$User = User::getByMobileNumber($mobilenumber);
if ($User)
{
  fwrite($fout,"#####\n");
  fwrite($fout,"The subject is: '$subj'\n");
  fwrite($fout,"The text is: '".substr($text,0,strlen($subj))."'\n");
  fwrite($fout,"#####\n");
  if (trim(substr($text,0,strlen($subj)))!=$subj)
  {
    $text = "(".$subj.") ".$text;
  }
  fwrite($fout,"Got user info. Response='$text'\n");
  fwrite($fout,"From: $from\nTo: $to\nMessage: $messageid\nMobile number: $mobilenumber\nEncoding: $encoding\n\n$text\n");
  
  if ($User->sms_confirmed)
  {
    fwrite($fout,"User is confirmed\n");
    
    if (is_numeric($messageid))
    {
      // message is response to a post
      // check for ad response
      if (substr($text,0,1)=='4')
      {
        // might be an ad
        $sql = "SELECT * FROM sms_ads WHERE adkey='" . substr($text,1) . "'";
        $conn = new DB();
        $conn->connect(GENERAL_DB_SLAVE,GENERAL_DB);
        $ad = $conn->query($sql);
        if ($ad[0])
        {
          // send response
          $adtext = str_replace("\\n","\n",$ad[0]['response']);
          $email = $from;
          $headers = "From: ad_reply@".SMS_EMAIL_HOST."\n";
          $headers .= "Priority: normal";
          $subject = "";
          mail($email, $subject, $adtext, $headers, "-f ad_reply@".SMS_EMAIL_HOST);
          fwrite($fout,"Message is an ad reply\n");
          fwrite($fout,"'$adtext' sent to $email\n");
          $isad = true;
        }
      }
      
      if (!$isad)
      {
        $pst = $Posts->get($messageid);
        if ($pst['id']==0)
        {
          // unexpected original message, do not post
          $skip = true;
          fwrite($fout,"Received at miio by an invalid address\n");
          $email = $from;
          $headers = "From: sms_notify@".SMS_EMAIL_HOST."\n";
          $headers .= "Priority: normal";
          $subject = "";
          $messagetxt = "You have sent a message to an invalid Miio email address.";
          mail($email, $subject, $messagetxt, $headers, "-f sms_notify@".SMS_EMAIL_HOST);
          fwrite($fout,"Response sent\n");
        }
        else if ($pst['parent_id']==0) $parent=$pst;
        else $parent = $Posts->get($pst['parent_id']);
        // check for t-mobile duplicate
        if (!$skip && ($provider=='tmomail.net'))
        {
          fwrite($fout,"Processing mail from t-mobile\n");
          fwrite($fout,"This message:\n");
          fwrite($fout,$User->id." - ".$text."\n");
          fwrite($fout,"total replies: ".count($parent['replies'])."\n");
          foreach ($parent['replies'] as $r)
          {
            $reply = $Posts->get($r['reply_id']);
            fwrite($fout,$reply['userid']." - ".$reply['text']."\n");
            
            if ($reply)
            {
              if ( ($reply['userid']==$User->id) && ($reply['text']==$text) )
              {
                $isdup = true;
                break;
              }
            }
          }
        }
        //fwrite($fout,print_r($User,true));
        if (!$isdup && !$skip)
        {
          fwrite($fout,"Sending message...");
          $post = array(
                        'userid'=>$User->id,
                        'parent_id'=>$parent['id'], // > 0) ? $parent['parent_id'] : $messageid;
                        'sent_to'=>$parent['userid'],
                        'type'=>'text',
                        'sharing'=>$parent['sharing'],
                        'text'=>$text,
                        'source'=>'text'
                       );
          $Posts->save($User->id,$post);
          fwrite($fout,"Sent!\n");
          
          if ($User->sms_confirm_post)
          {
            // send response      
            $email = GetSMSEmail($User);
            $headers = "From: sms_notify@".SMS_EMAIL_HOST."\n";
            $headers .= "Priority: normal";
            $subject = "";
            $response = "Your reply has been posted to Miio";
            mail($email, $subject, $response, $headers, "-f sms_notify@".SMS_EMAIL_HOST);
          }
        }
        else if ($isdup)
        {
          fwrite($fout,"Duplicate T-Mobile message. Message not delivered\n");
        }
      }
    }
    else
    {
      fwrite($fout,"Not a message reply.\n");
      $messageinfo = explode('_',$messageid);
      $messagetype = strtolower($messageinfo[0]);
      switch ($messagetype)
      {
        case 'admininvite':
          admininvite($messageinfo,$User,$text);
          break;
        case 'alert':
          alert($messageinfo,$User,$text);
          break;
        case 'friendrequest':
          friendrequest($messageinfo,$User,$text);
          break;
        case 'groupinvite':
          groupinvite($messageinfo,$User,$text);
          break;
        case 'memberrequest':
          memberrequest($messageinfo,$User,$text);
          break;
        case 'smsadmin':
          smsadmin($messageinfo,$User,$text);
          break;
        case 'smsgroup':
          smsgroup($messageinfo,$User,$text);
          break;
        case 'smsgroupmessage':
          smsgroupmessage($messageinfo,$User,$text);
          break;
        case 'smsmember':
          smsmember($messageinfo,$User,$text);
          break;
        case 'smsmemberrequest':
          smsmemberrequest($messageinfo,$User,$text);
          break;
        case 'smssubscriber':
          smssubscriber($messageinfo,$User,$text);
          break;
        default:
          fwrite($fout,"Unknown request: '$messagetype'\n");
          // by default, do nothing
      }
    }
  }
  else
  {
    // not confirmed
    fwrite($fout,"User not confirmed. Checking if this is a confirmation message\n");
    if ($messageid=='sms_confirm')
    {
      if (strtolower($text)=="ok")
      {
        // confirm user
        $User->confirmSMS();
        fwrite($fout,"Mobile number for $User->username is now confirmed\n");
        $phone = GetSMSEmail($User);
        $subj = "";
        $headers = "From: sms_confirm@".SMS_EMAIL_HOST."\n";
        $headers .= "Priority: normal";
        $msg = "Your phone is confirmed on Miio.\n\nText original messages to 201-238-0827 (save now!). Reply normally.";
        mail($phone, $subj, $msg, $headers, "-f sms_confirm@".SMS_EMAIL_HOST);
      }
      else
      {
        $phone = GetSMSEmail($User);
        $msg = "Sorry, you sent an unrecognized response. To confirm your phone and receive text notifications, reply OK.";
        $headers = "From: sms_confirm@".SMS_EMAIL_HOST."\n";
        $headers .= "Priority: normal";
        $subject = '';
        mail($phone, $subject, $msg, $headers, "-f sms_confirm@".SMS_EMAIL_HOST);
      }
    }
    else
    {
      fwrite($fout,"Invalid response from unconfirmed number\n");
    }
  }
}
else
{
  fwrite($fout,"Unable to retreive user information\n");
}

fwrite($fout,"================================================================================\n\n");
fclose($fout);
chmod("/logs/msg_$today.log",0666);

echo 0;

function error_handler($errno,$errstr,$errfile,$errline)
{
  if ($errno == 8) return;
  global $today, $now;
  $ferr = fopen("/logs/msg_$today.err","a");
  fwrite($ferr,"$now:  [$errno] $errstr in $errfile at $errline\n\n");
  fclose($ferr);
  chmod("/logs/msg_$today.err",0666);
}

/******************************************************************************/
/***************************** MESSAGE  FUNCTIONS *****************************/
/******************************************************************************/

function admininvite($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Admin Invite\n");
  $groupid = $info[1];
  $group = User::get();
  $group->get($groupid);
  if (strtolower($text)=="yes")
  {
    // accept admin invite
    $User->makeAdmin($groupid);
    SendAdminAcceptedNotification($User,$groupid,true);
    $msg = "You are now an administrator for $group->username.";
  }
  else if (strtolower($text)=="no")
  {
    // decline admin invite
    $User->makeAdmin($groupid);
    SendAdminAcceptedNotification($User,$groupid,false);
    $msg = "Invitation to administer $group->username has been declined.";
  }
  else
  {
    $msg = "You have sent an unrecognized response. To accept the invitation to administer $group->username, reply YES. To decline, reply NO.";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: admininvite_$groupid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f admininvite_$groupid@".SMS_EMAIL_HOST);
}

function alert($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Reply to alert\n");
  $addr = GetSMSEmail($User);
  $pid = $info[1];
  if (strtolower($text)=="yes")
  {
    // send original message
    $Posts = new Post();
    $post = $Posts->get($pid);
    //$postid = ($post['parent_id']>0) ? $post['parent_id'] : $post['id'];
    $sender = User::get();
    $sender->get($post['userid']);
    if ($post['sent_to']>0)
    {
      $recipient = User::get();
      $recipient->get($post['sent_to']);
    }
    if ($recipient)
    {
      $saidto = "$sender->username said to $recipient->username publicly:";
    }
    else $saidto = "$sender->username said to everyone:";
    
    $msg = "$saidto " . $post['text'];
    // Calculate split if necessary - tmobile max at 110
    if (strlen($msg)>138)
    {
      $breakpoint = strrpos(substr($msg,0,129),' ');
      $smstext = substr($msg,0,$breakpoint);
      $smstext2 = substr($msg,$breakpoint);
      $smssplit = true;
    }
    else $smstext = $msg;
    if (strlen($msg)>108)
    {
      $breakpoint = strrpos(substr($msg,0,99),' ');
      $tmotext = substr($msg,0,$breakpoint);
      $tmotext2 = substr($msg,$breakpoint);
      $tmosplit = true;
    }
    else $tmotext = $msg;
    
    $headers = "From: $pid@".SMS_EMAIL_HOST."\n";
    $headers .= "Priority: normal";
    $subject = "";
    $SMSad = GetSMSAds('');
    if ($SMSad['web']==1) $ad = $SMSad['text'] . ' ' . $SMSad['url'];
    else $ad = $SMSad['text'] . ' reply 4' . $SMSad['adkey'];
    if (stripos($addr,"tmomail.net"))
    {
      // tmobile
      if (!$tmosplit)
      {
        $msg = $tmotext . "\n\n> " . $ad;
        mail($addr, $subject, $msg, $headers, "-f $pid@".SMS_EMAIL_HOST);
      }
      else
      {
        $msg = $tmotext . "...[more]";
        mail($addr, $subject, $msg, $headers, "-f $pid@".SMS_EMAIL_HOST);
        $msg = "[cont]..." . $tmotext2 . "\n\n> " . $ad;
        mail($addr, $subject, $msg, $headers, "-f $pid@".SMS_EMAIL_HOST);
      }
    }
    else
    {
      if (!$smssplit)
      {
        $msg = $smstext . "\n\n> " . $ad;
        mail($addr, $subject, $msg, $headers, "-f $pid@".SMS_EMAIL_HOST);
      }
      else
      {
        $msg = $smstext . "...[more]";
        mail($addr, $subject, $msg, $headers, "-f $pid@".SMS_EMAIL_HOST);
        $msg = "[cont]..." . $smstext2 . "\n\n> " . $ad;
        mail($addr, $subject, $msg, $headers, "-f $pid@".SMS_EMAIL_HOST);
      }
    }
  }
  else
  {
    $msg = "You have sent an unrecognized response. To view the message you were alerted about, reply YES.";
    $headers = "From: alert_$pid@".SMS_EMAIL_HOST."\n";
    $headers .= "Priority: normal";
    $subject = '';
    mail($addr, $subject, $msg, $headers, "-f alert_$pid@".SMS_EMAIL_HOST);
  }
}

function friendrequest($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Reply to friend request\n");
  $requesterid = $info[1];
  $requester = User::get();
  $requester->get($requesterid);
  if (strtolower($text)=="yes")
  {
    // accept friend request
    $User->acceptFriend($requesterid);
    $msg = "You have accepted $requester->username's request for Friendship.";
  }
  else if (strtolower($text)=="no")
  {
    // decline friend request
    $User->rejectFriend($requesterid);
    $msg = "You have declined $requester->username's request for Friendship.";
  }
  else
  {
    $msg = "You have sent an unrecognized response. To accept $requester->username's request for Friendship, reply YES. To decline, reply NO";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: friendrequest_$requesterid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f friendrequest_$requesterid@".SMS_EMAIL_HOST);
}

function groupinvite($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Reply to group invitation\n");
  $groupid = $info[1];
  $group = User::get();
  $group->get($groupid);
  if (strtolower($text)=="yes")
  {
    // check if user is member
    if ($User->isMember($groupid))
    {
      $msg = "You are already a member of $group->username.";
    }
    else
    {
      if ($group->visibility=='public')
      {
        // if public - just join
        if ($User->joinGroup($groupid)) $msg = "You are now a member of $group->username.";
        else $msg = "An unknown error occurred";
      }
      else
      {
        // otherwise check for an invite
        if ($User->invitedToJoin($groupid))
        {
          $User->cancelMembershipRequest($groupid);
          if ($User->joinGroup($groupid)) $msg = "You are now a member of $group->username.";
          else $msg = "An unknown error occurred";
        }
        else $msg = "You have not been invited to join $group->username.";
      }
    }
  }
  else if (strtolower($text)=="no")
  {
    if ($User->isMember($groupid))
    {
      $msg = "You are already a member of $group->username.";
    }
    else
    {
      if ($group->visibility=='public')
      {
        $msg = "The invitation to join $group->username has been declined.";
      }
      else
      {
        if ($User->invitedToJoin($groupid))
        {
          $User->cancelMembershipRequest($groupid);
          $msg = "The invitation to join $group->username has been declined.";
        }
        else $msg = "You have not been invited to join $group->username.";
      }
    }
  }
  else
  {
    fwrite($fout,"Unknown response\n");
    $msg = "You have sent an unrecognized response. To accept the invitation to join $group->username, reply YES. Otherwise, do nothing.";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: groupinvite_$groupid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f groupinvite_$groupid@".SMS_EMAIL_HOST);
}

function memberrequest($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Reply to membership request accepted\n");
  $groupid = $info[1];
  $group = User::get();
  $group->get($groupid);
  if (strtolower($text)=="stop")
  {
    // check if user is member
    if ($User->isMember($groupid))
    {
      // get current settings
      $conn = User::connectToShard($User->id,true);
      $sql = "SELECT * FROM users_groups WHERE userid=$User->id AND group_id=$groupid";
      $res = $conn->query($sql);
      $current = $res[0];
      $current['sms'] = 0;
      $User->updateMembership($groupid,$current);
      $msg = "You will no longer receive text message updates for $group->username";
    }
    else
    {
      $msg = "You are not a member of $group->username.";
    }
  }
  else
  {
    fwrite($fout,"Unknown response\n");
    $msg = "You have sent an unrecognized response. To turn off text message updates for $group->username, reply STOP. Otherwise, do nothing.";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: memberrequest_$groupid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f memberrequest_$groupid@".SMS_EMAIL_HOST);
}

function smsadmin($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Reply to SMS admin changes\n");
  $groupid = $info[1];
  $group = User::get();
  $group->get($groupid);
  // check if user is member
  if ($User->isMember($groupid))
  {
    if ($User->isAdmin($groupid))
    {
      // get current settings
      $conn = User::connectToShard($User->id,true);
      $sql = "SELECT * FROM users_groups WHERE userid=$User->id AND group_id=$groupid";
      $res = $conn->query($sql);
      $current = $res[0];
      if (strtolower($text)=="stop")
      {
        $current['admin_sms'] = 0;
        $User->updateMembership($groupid,$current);
        $msg = "You will no longer receive text notifications for admin changes to $group->username. Reply START to start text updates again.";
      }
      else if (strtolower($text)=="start")
      {
        $current['admin_sms'] = 1;
        $User->updateMembership($groupid,$current);
        $msg = "You will now receive text notifications for admin changes to $group->username. Reply STOP to stop text updates.";
      }
      else
      {
        fwrite($fout,"Unknown response\n");
        $msg = "You have sent an unrecognized response. To get text notifications of admin changes to $group->username reply START. To stop reply STOP.";
      }
    }
    else
    {
      $msg = "You are not an Administrator of $group->username.";
    }
  }
  else
  {
    $msg = "You are not a member of $group->username.";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: smsadmin_$groupid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f smsadmin_$groupid@".SMS_EMAIL_HOST);
}

function smsgroupmessage($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Changes to SMS notifications for group\n");
  $groupid = $info[1];
  $group = User::get();
  $group->get($groupid);
  // check if user is member
  if ($User->isMember($groupid))
  {
    // get current settings
    $conn = User::connectToShard($User->id,true);
    $sql = "SELECT * FROM users_groups WHERE userid=$User->id AND group_id=$groupid";
    $res = $conn->query($sql);
    $current = $res[0];
    if (strtolower($text)=="stop")
    {
      $current['sms'] = 0;
      $User->updateMembership($groupid,$current);
      $msg = "You will no longer receive messages posted to $group->username by text. Reply START to start text updates again.";
    }
    else if (strtolower($text)=="start")
    {
      $current['sms'] = 1;
      $User->updateMembership($groupid,$current);
      $msg = "You will now receive messages posted to $group->username by text. Reply STOP to stop text updates.";
    }
    else
    {
      fwrite($fout,"Unknown response\n");
      $msg = "You have sent an unrecognized response. To get text message updates for $group->username reply START. To stop reply STOP.";
    }
  }
  else
  {
    $msg = "You are not a member of $group->username.";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: smsgroupmessage_$groupid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f smsgroupmessage_$groupid@".SMS_EMAIL_HOST);
}

function smsgroup($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Reply to SMS group message changes\n");
  $groupid = $info[1];
  $group = User::get();
  $group->get($groupid);
  // check if user is member
  if ($User->isMember($groupid))
  {
    if ($User->isAdmin($groupid))
    {
      // get current settings
      $conn = User::connectToShard($User->id,true);
      $sql = "SELECT * FROM users_groups WHERE userid=$User->id AND group_id=$groupid";
      $res = $conn->query($sql);
      $current = $res[0];
      if (strtolower($text)=="stop")
      {
        $current['group_sms'] = 0;
        $User->updateMembership($groupid,$current);
        $msg = "You will no longer receive text notifications when changes are made to $group->username. Reply START to start text updates again.";
      }
      else if (strtolower($text)=="start")
      {
        $current['group_sms'] = 1;
        $User->updateMembership($groupid,$current);
        $msg = "You will now receive text notifications when changes are made to $group->username. Reply STOP to stop text updates.";
      }
      else
      {
        fwrite($fout,"Unknown response\n");
        $msg = "You have sent an unrecognized response. To get text notifications of changes to $group->username reply START. To stop reply STOP.";
      }
    }
    else
    {
      $msg = "You are not an Administrator of $group->username.";
    }
  }
  else
  {
    $msg = "You are not a member of $group->username.";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: smsgroup_$groupid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f smsgroup_$groupid@".SMS_EMAIL_HOST);
}

function smsmember($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Reply to SMS member changes\n");
  $groupid = $info[1];
  $group = User::get();
  $group->get($groupid);
  // check if user is member
  if ($User->isMember($groupid))
  {
    if ($User->isAdmin($groupid))
    {
      // get current settings
      $conn = User::connectToShard($User->id,true);
      $sql = "SELECT * FROM users_groups WHERE userid=$User->id AND group_id=$groupid";
      $res = $conn->query($sql);
      $current = $res[0];
      if (strtolower($text)=="stop")
      {
        $current['member_sms'] = 0;
        $User->updateMembership($groupid,$current);
        $msg = "You will no longer receive text notifications for membership changes to $group->username. Reply START to start text updates again.";
      }
      else if (strtolower($text)=="start")
      {
        $current['member_sms'] = 1;
        $User->updateMembership($groupid,$current);
        $msg = "You will now receive text notifications for membership changes to $group->username. Reply STOP to stop text updates.";
      }
      else
      {
        fwrite($fout,"Unknown response\n");
        $msg = "You have sent an unrecognized response. To get text notifications about members of $group->username reply START. To stop reply STOP.";
      }
    }
    else
    {
      $msg = "You are not an Administrator of $group->username.";
    }
  }
  else
  {
    $msg = "You are not a member of $group->username.";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: smsmember_$groupid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f smsmember_$groupid@".SMS_EMAIL_HOST);
}

function smsmemberrequest($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Reply to membership request\n");
  $requesterid = $info[1];
  $requester = User::get();
  $requester->get($requesterid);
  $groupid = $info[2];
  $group = User::get();
  $group->get($groupid);
  
  if ($User->isMember($groupid))
  {
    if ($User->isAdmin($groupid))
    {
      $requestok = $requester->membershipRequested($groupid);
      $ismember = $requester->isMember($groupid);
      if (strtolower($text)=="yes")
      {
        // accept member request
        if ($requestok)
        {
          $requester->joinGroup($groupid);
          $requester->cancelMembershipRequest($groupid);
          SendMemberAcceptedNotification($group,$requester,$User);
          $msg = "You have accepted $requester->username's request for Membership in $group->username.";
        }
        else if ($ismember) $msg = "$requester->username's request for Membership in $group->username has already been approved.";
        else $msg = "The request for Membership in $group->username by $requester->username has been canceled or has already been declined.";
      }
      else if (strtolower($text)=="no")
      {
        if ($requestok)
        {
          // decline member request
          $requester->cancelMembershipRequest($groupid);
          SendMemberRejectedNotification($group,$requester,$User);
          $msg = "You have declined $requester->username's request for Membership in $group->username.";
        }
        else if ($ismember) $msg = "$requester->username's request for Membership in $group->username has already been approved.";
        else $msg = "The request for Membership in $group->username by $requester->username has been canceled or has already been declined.";
      }
      else
      {
        $msg = "You have sent an unrecognized response. To let $requester->username's Membership join $group->username, reply YES. To decline, reply NO.";
      }
    }
    else
    {
      $msg = "You are not an Administrator of $group->username.";
    }
  }
  else
  {
    $msg = "You are not a member of $group->username.";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: smsmemberrequest_$requesterid"."_$groupid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f smsmemberrequest_$requesterid"."_$groupid@".SMS_EMAIL_HOST);
}

function smssubscriber($info,$User,$text)
{
  global $fout;
  fwrite($fout,"Changes to SMS notifications for individual\n");
  $subscribedid = $info[1];
  $subscribed = User::get();
  $subscribed->get($subscribedid);
  // check if user is member
  if ($User->isSubscribed($subscribedid))
  {
    // get current settings
    $conn = User::connectToShard($User->id,true);
    $sql = "SELECT * FROM user_subscriptions WHERE userid=$User->id AND subscribed_id=$subscribedid";
    $res = $conn->query($sql);
    $current = $res[0];
    $new = array();
    $new['byemail'] = $current['email'];
    $new['mute'] = $current['mute'];
    if (strtolower($text)=="stop")
    {
      $new['bysms'] = 0;
      $User->updateSubscription($subscribedid,$new);
      $msg = "You will no longer receive $subscribed->username's messages by text. Reply START to start text updates again.";
    }
    else if (strtolower($text)=="start")
    {
      $new['bysms'] = 1;
      $User->updateSubscription($subscribedid,$new);
      $msg = "You will now receive $subscribed->username's messages by text. Reply STOP to stop text updates.";
    }
    else
    {
      fwrite($fout,"Unknown response\n");
      $msg = "You have sent an unrecognized response. To get text message updates for $subscribed->username reply START. To stop reply STOP.";
    }
  }
  else
  {
    $msg = "You are not subscribed to $subscribed->username.";
  }
  $phone = GetSMSEmail($User);
  $headers = "From: smssubscriber_$subscribedid@".SMS_EMAIL_HOST."\n";
  $headers .= "Priority: normal";
  $subject = '';
  mail($phone, $subject, $msg, $headers, "-f smssubscriber_$subscribedid@".SMS_EMAIL_HOST);
}

// autoload models
function __autoload($class)
{
  require_once("/miio_config/$class.php");
}


?>