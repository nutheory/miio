<?php

class Message
{
  static $Log;

  static function queue($id)
  {
    if (LOCAL)
    {
      Message::broadcast($id);
    }
    else if (HOST == "beta.ikegger.com" || HOST == "beta.miio.com")
    {
      Message::broadcast($id);
    }
    else
    {
      echo "Queuing message - this should not happen yet!"; die;
      // save to cache queue
    }
  }

  static function queueForUnbroadcast($id)
  {
    if (LOCAL)
    {
      Message::unbroadcast($id);
    }
    else if (HOST == "beta.ikegger.com" || HOST == "beta.miio.com")
    {
      Message::unbroadcast($id);
    }
    else
    {
      echo "Queuing unbroadcast message - this should not happen yet!"; die;
      // save to cache queue

    }
  }

  static function logwrite($message)
  {
    if (!isset(Message::$Log)) Message::$Log = new Log('broadcast');
    Message::$Log->write($message);
  }

  static function getSMSAds($text)
  {
    return "no ad";
  }

  static function broadcast($postid)
  {
    // get post
    $post = Post::get($postid);
    if (!$post) return;
    Message::logwrite("Message $postid: ".substr($post->text,0,50)."\n");

    // get user object for poster
    $Sender = User::get($post->sent_by);
    if (!$Sender) return;
    $smssaid = $Sender->username;
    $emailsaid = "<a %LINKSTYLE% href='".LOC."$Sender->username'>$Sender->username</a>";
    $broadcast_list = array();
    if ($Sender->id!='a') $broadcast_list[] = $Sender->id;

    if ($post->type==Enum::$message_type['reply'])
    {
      $parent = Post::get($post->original_id);
      if ($parent)
      {
        // send to original sender
        $broadcast_list[] = $parent->sent_by;
        // send to original sent_to
        foreach ($parent->sent_to as $id) $broadcast_list[] = $id;
        // send to all participants
        $replies = $parent->getReplies();
        foreach ($replies as $r)
        {
          $reply = Post::get($r);
          if ($reply) $broadcast_list[] = $reply->sent_by;
        }
        $OP = User::get($parent->sent_by);
        if ($OP)
        {
          $smssaid .= " replied to ".$OP->username;
          $emailsaid .= " replied to <a %LINKSTYLE% href='".LOC."$OP->username'>".$OP->username."</a>";
        }
        if ($parent->sharing==Enum::$sharing['private'])
        {
          $smssaid .= " privately";
          $emailsaid .= " privately";
        }
      }
    }
    else if ($post->sharing==Enum::$sharing['public_group'] || $post->sharing==Enum::$sharing['private_group'] || $post->sharing==Enum::$sharing['admin'])
    {
      // distribute to group
      $Group = Group::get($post->sent_to[0]);
      $members = $Group->getMembers();
      foreach($members as $member)
      {
        $broadcast_list[] = $member;
      }
      $smssaid .= " said in group $Group->username";
      $emailsaid .= " said in group <a %LINKSTYLE% href='".LOC."$Group->username'>$Group->username</a>";
      $post->saveToTimeline($Group->id,true);
    }
    else
    {
      // distribute to individuals
      foreach ($post->sent_to as $recipient)
      {
        $broadcast_list[] = $recipient;
      }
      if (count($post->sent_to)>1)
      {
        $smssaid .= " said to Multiple People";
        $emailsaid .= " said to Multiple People";
      }
      else if (count($post->sent_to)==1)
      {
        $recipient = User::get($post->sent_to[0]);
        if ($recipient)
        {
          $smssaid .= " said to $recipient->username";
          $emailsaid .= " said to <a %LINKSTYLE% href='".LOC."$recipient->username'>$recipient->username</a>";
        }
      }
      else $toall = true;

      if ($post->sharing==Enum::$sharing['private'])
      {
        $smssaid .= " privately";
        $emailsaid .= " privately";
      }
      else
      {
        // distribute to friends
        foreach($Sender->getFriends() as $id) $broadcast_list[] = $id;

        if ($post->sharing==Enum::$sharing['friends'])
        {
          $smssaid .= " said to friends only";
          $emailsaid .= " said to friends only";
        }
        else
        {
          // public - send to all
          foreach($Sender->getFollowers as $id) $broadcast_list[] = $id;
          if ($toall)
          {
            $smssaid .= " said to everyone";
            $emailsaid .= " said to everyone";
          }
          else
          {
            $smssaid .= " publicly";
            $emailsaid .= " publicly";
          }
        }
      }
    }

    $broadcast_list = array_unique($broadcast_list);
    // push to alerts queue
    //if ($post->sharing==Enum::$sharing['public']) Alerts::queue($post->id);

    // now have broadcast list - distribute to each
    foreach ($broadcast_list as $recipientid)
    {
      Message::distribute($Sender,$post,$recipientid,$emailsaid,$smssaid);
    }
  }

  static function distribute($Sender,$post,$id,$emailsaid,$smssaid)
  {
    if ($id==$Sender->id)
    {
      $post->saveToTimeline($id);
    }
    else
    {
      $Recipient = User::get($id);
      if ($Recipient)
      {
        // handle notifications
        if ($post->type==Enum::$message_type['alert'])
        {
          $post->saveToTimeline($Recipient->id);
        }
        else if ($post->type==Enum::$message_type['notification'])
        {
          $post->saveToTimeline($Recipient->id);
        }
        // user message handling
        else if ($Recipient->isFollowing($Sender->id))
        {
          // use specific follow preferences
          $Preferences = $Recipient->getFollowSettings($Sender->id);
          if ($Preferences['dashboard'][$post->type])
          {
            $post->saveToTimeline($Recipient->id);
          }
          // email
          // sms
        }
        else
        {
          // use general distribution settings
        }
      }
    }
  }

  static function sendEmail($PrefFrom,$Sender,$Recipient,$post,$said,$sub)
  {
    if (LOCAL) return;
    if ($Sender->id==$Recipient->id) return;
    $postid = ($post['parent_id']>0) ? $post['parent_id'] : $post['id'];
    $emailfrom = $postid.'@'.SMS_EMAIL_HOST;
    $headers = "From: Miio <$emailfrom>\n";
    $headers .= "Content-type: text/html\n";
    $subject = "Miio Message";
    if ($post['system'])
    {
      $messagetext = "<style type='text/css'> a { color:#666; font-weight: bold; } </style>";
      $messagetext .= $post['text'];
    }
    else
    {
      $messagetext = htmlspecialchars($post['text']);
      $messagetext = preg_replace("/(www\.|http:\/\/|https:\/\/)([^\s]+)/", '<a href="$1$2" %LINKSTYLE%>$1$2</a>', $messagetext);
      $messagetext = str_replace("href=\"www.","href=\"http://www.",$messagetext);
    }

    if ($sub)
    {
      if ($PrefFrom->is_group) $stop = "Stop receiving email updates for the $PrefFrom->username group by clicking <a href='$LOC"."groups/view/$PrefFrom->id"."#manage' %LINKSTYLE%>here</a>";
      else $stop =  "Stop receiving email updates for $PrefFrom->username by clicking <a href='$LOC"."members/profile/$PrefFrom->id"."#manage' %LINKSTYLE%>here</a>";
    }
    else
    {
      $stop =  "Stop receiving email updates for Miio messages by clicking <a href='$LOC"."user#settings/message' %LINKSTYLE%>here</a>";
    }
    if ($LOC=="http://beta.ikegger.com/") $IMG = "http://support.miio.com/";
    else $IMG = $LOC;
    if ($Sender->photo == "") $avatar = 'avatars/default.jpg';
    else $avatar = 'avatars/'.$Sender->photo;
    $msg = Message::$emailHTML;
    $msg = str_replace('%BASE%',$LOC,$msg);
    $msg = str_replace('%LOGO%',$IMG.'images/logo_sm.png',$msg);
    $msg = str_replace('%TOP%',$IMG.'images/messagelist/message_top.png',$msg);
    $msg = str_replace('%MID%',$IMG.'images/messagelist/message_body.png',$msg);
    $msg = str_replace('%BTM%',$IMG.'images/messagelist/message_bottom.png',$msg);
    $msg = str_replace('%PROFILE%',$LOC.$Sender->username,$msg);
    $msg = str_replace('%AVATAR%',$IMG.$avatar,$msg);
    $msg = str_replace('%SAIDTO%',$said,$msg);
    if ($post['system']) $msg = str_replace('%MESSAGELINK%','#',$msg);
    else $msg = str_replace('%MESSAGELINK%',$LOC.'messages/view/'.$post['id'],$msg);
    $msg = str_replace('%MESSAGETYPE%',Options::$messagetype[$post['type']],$msg);
    $msg = str_replace('%MESSAGETEXT%',$messagetext,$msg);
    $msg = str_replace('%STOPLINK%',$stop,$msg);
    //$msg = str_replace('%LINKSTYLE%',"",$msg);
    $msg = str_replace('%LINKSTYLE%','style="font-weight:bold;color:#666"',$msg);
    mail($Recipient->email, $subject, $msg, $headers, "-f $emailfrom");
  }

  static function sendSMS($PrefFrom,$Sender,$Recipient,$post,$said,$sub)
  {
    if (LOCAL) return;
    if ($Sender->id==$Recipient->id) return;

    $postid = ($post['parent_id']>0) ? $post['parent_id'] : $post['id'];
    $emailfrom = $postid.'@'.SMS_EMAIL_HOST;
    $headers = "From: Miio <$emailfrom>\n";
    $headers .= "Priority: normal";
    $subject = "";
    $addr = $Recipient->getSMSEmail();
    $SMSad = Message::getSMSAds('');
    if ($SMSad['web']==1) $ad = $SMSad['text'] . ' ' . $SMSad['url'];
    else $ad = $SMSad['text'] . ' reply 4' . $SMSad['adkey'];
    $msgtext = $said.": ".$post['text'];
    if (strlen($msgtext)>120) $msg = substr($msgtext,0,115) . "...\n" . $ad;
    else $msg = $msgtext . "\n" . $ad;

    mail($addr, $subject, $msg, $headers, "-f $emailfrom");
  }

    /*
    if (LOCAL) return;
    if ($post['alert'] || $post['system'] || $post['source'] == 'twitter') return;
    // send emails


    // send sms
    $msg = "$smssaid ";
    $msg .= $post['text'];
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

    $headers = "From: $User->username\n";
    $headers .= "Priority: normal";
    $subject = "";
    foreach ($sms as $usr)
    {
      $user = User::get($usr);
      $addr = $user->getSMSEmail();
      $SMSad = Message::getSMSAds('');
      if ($SMSad['web']==1) $ad = $SMSad['text'] . ' ' . $SMSad['url'];
      else $ad = $SMSad['text'] . ' reply 4' . $SMSad['adkey'];
      if (stripos($addr,"tmomail.net"))
      {
        // tmobile
        if (!$tmosplit)
        {
          $msg = $tmotext . "\n\n> " . $ad;
          mail($addr, $subject, $msg, $headers, "-f $postid@".SMS_EMAIL_HOST);
        }
        else
        {
          $msg = $tmotext . "...[more]";
          mail($addr, $subject, $msg, $headers, "-f $postid@".SMS_EMAIL_HOST);
          usleep(10000); // pause for 1/100 of a second
          $msg = "[cont]..." . $tmotext2 . "\n\n> " . $ad;
          mail($addr, $subject, $msg, $headers, "-f $postid@".SMS_EMAIL_HOST);
        }
      }
      else
      {
        if (!$smssplit)
        {
          $msg = $smstext . "\n\n> " . $ad;
          mail($addr, $subject, $msg, $headers, "-f $postid@".SMS_EMAIL_HOST);
        }
        else
        {
          $msg = $smstext . "...[more]";
          mail($addr, $subject, $msg, $headers, "-f $postid@".SMS_EMAIL_HOST);
          usleep(10000); // pause for 1/100 of a second
          $msg = "[cont]..." . $smstext2 . "\n\n> " . $ad;
          mail($addr, $subject, $msg, $headers, "-f $postid@".SMS_EMAIL_HOST);
        }
      }
    }
  }*/

  static function unbroadcast($id)
  {
    foreach ($DBLIST['users'] as $db)
    {
      $conn = new DB();
      $conn->connect($db['slave'],$db['name']);
      $sql = "SELECT userid FROM posts WHERE post='$id'";
      $users = $conn->query($sql);
      foreach($users as $user)
      {
        $usr = User::get($user['userid']);
        if ($usr) $usr->removePostFromTimeline($id);
      }
    }
  }

  /*
  %BASE% = http://beta.ikegger.com/
  %LOGO% = http://beta.ikegger.com/images/logo_sm.png
  %TOP% http://beta.ikegger.com/images/messagelist/message_top.png
  %MID% http://beta.ikegger.com/images/messagelist/message_body.png
  %BTM% http://beta.ikegger.com/images/messagelist/message_bottom.png
  %PROFILE% http://beta.ikegger.com/members/profile/2
  %AVATAR% http://beta.ikegger.com/avatars/2.jpg
  %SAIDTO% <a href="%PROFILE%" style="color:#666;font-weight:bold;"></a> said to everyone:
  %MESSAGELINK% http://beta.ikegger.com/messages/view/17816
  %MESSAGETYPE%
  %MESSAGETEXT%
  %STOPLINK% Stop receiving email updates for Random by clicking
    <a href="http://beta.ikegger.com/members/profile#manage" style="color:#666;font-weight:bold">here</a>
  */

  static $emailHTML = "<html><body>
<div style=\"padding:10px;width:483px;font-family: Helvetica, Arial, sans-serif;border:solid #666 1px;\">
  <div style=\"margin-bottom:10px;color:#666;font-size:16px;\">
    <a href=\"%BASE%\"><img src=\"%LOGO%\" style=\"border:none;margin-right:10px;vertical-align:-3px;\" height=20 width=46></a>
    You have received this message from Miio.
  </div>
  <div style=\"background: url('%TOP%');height: 1px;\"></div>
  <div style=\"padding: 0 4px 0 1px;background: url('%MID%') repeat-y;\">
    <a style=\"float:left;margin: 2px;margin-right:7px;\" href=\"%PROFILE%\">
      <img src=\"%AVATAR%\" height=\"50\" width=\"50\" style=\"border: solid white 3px;\">
    </a>
    <div style=\"margin-left:65px;font-size:14px;color:#666;padding-top:5px;\">
      %SAIDTO%
    </div>
    <div style=\"font-size:14px;color: #666;margin-left:65px;margin-right:10px;padding: 6px 0;\">
      <a href=\"%MESSAGELINK%\" %LINKSTYLE%>%MESSAGETYPE%</a>:
      <span>
        %MESSAGETEXT%
      </span>
    </div>
    <div style=\"clear:both;overflow:hidden;height:0px;\"></div>
  </div>
  <div style=\"background: url('%BTM%');height: 3px;\"></div>
  <div style=\"margin-top:10px;font-size:10px;color:#666\">%STOPLINK%</div>
</div>
</body></html>";


}

?>