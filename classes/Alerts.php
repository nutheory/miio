<?php

class Alerts
{
  public $AlertList;
  /*
  function __construct()
  {
    global $Cache;
    $this->AlertList = $Cache->get('AlertList');
    if (!$this->AlertList) $this->getAlertList();
  }
  */
  static function queue($id)
  {
    global $LOCAL, $HOST;
    if ($LOCAL)
    {
      //$A = new Alerts();
      Alerts::sendAlert($id);
    }
    else if ($HOST == "beta.ikegger.com")
    {
      //$A = new Alerts();
      Alerts::sendAlert($id);
    }
    else
    {
      echo "Queuing alert - this should not happen yet!"; die;
      // save to cache queue
      global $Cache;
      $Alerts = $Cache->get('Alerts');
      if ($Alerts)
      {
        $Alerts[] = $id;
        $Cache->replace('Alerts',$Alerts);
      }
      else
      {
        $Alerts = array();
        $Alerts[] = $id;
        $Cache->add('Alerts',$Alerts);
      }
    }
  }

  function checkQueue()
  {
    global $Cache;
    $Refresh = $Cache->get('RefreshAlert');
    $Cache->delete('RefreshAlert');
    if ($Refresh)
    {
      $this->AlertList = $Cache->get('AlertList');
      if (!$this->AlertList) $this->getAlertList();
    }
    $Alerts = $Cache->get('Alerts');
    if ($Alerts)
    {
      $alert = array_shift($Alerts);
      $Cache->replace('Alerts',$Alerts);
      $this->sendAlert($alert);
    }
  }

  /*
  function getAlertList()
  {
    global $DB,$Cache;
    $DB->connect(GENERAL_DB_MASTER,GENERAL_DB);
    $sql = "SELECT * FROM alerts";
    $alertlist = $DB->query($sql);
    $this->AlertList = $DB->query($sql);
    $Cache->set('AlertList',$this->AlertList);
  }
  */

  function sendAlert($id)
  {
    global $LOC, $DBLIST, $LOCAL, $DB;
    $alert = Post::get($id);
    //echo "testing ".$alert['text']." for matches:\n";
    $DB->connect(GENERAL_DB_MASTER,GENERAL_DB);
    $sql = "SELECT * FROM alerts";
    $alertlist = $DB->query($sql);
    foreach ($alertlist as $match)
    {
      //echo "-matching ".$match['text']."\n";
      $matches = strpos(strtolower($alert['text']),strtolower($match['text']));
      if ($matches!==false)
      {
        //echo "--Found a match!\n";
        // found a match
        // build array of users
        $dblist = $DBLIST['users'];
        $list = array();
        foreach ($dblist as $db)
        {
          $conn = new DB();
          $conn->connect($db['slave'],$db['name']);
          $sql = "SELECT userid FROM user_alerts WHERE alert_id=".$match['id']." AND paused=0";
          $res = $conn->query($sql);
          foreach ($res as $u) $list[] = $u['userid'];
        }

        if (count($list)>0)
        {
          $postid = $alert['id'];
          $miiotext = "This is a match for your alert <span class='bold_big_text'>'%ALERT%'</span>";

          $emailtext = "<html><body>\n";
          $emailtext .= "A message matching your alert '%ALERT%' has been posted.<br>";
          $emailtext .= "You can see the message <a href='$LOC"."messages/view/$postid'>here</a>";
          $emailtext .= "</body></html>";
          $emailheaders = "From: email_notify@".SMS_EMAIL_HOST."\n";
          $emailheaders .= "Content-type: text/html\n";
          $emailsubject = "Miio Alert for '%ALERT%'";

          $smstext = "A message has been posted matching your alert for '%ALERT%'\n\nReply YES to receive this message by text.";
          $smsid = "alert_".$alert['id'];
          $smsheaders = "From: $smsid@".SMS_EMAIL_HOST."\n";
          $smsheaders .= "Priority: normal";

          $popuptext = "<h3 class='alert'>Alert</h3>";
          $popuptext .= "<p><b>".$alert['sender_name']."</b> said:</p>";
          $popuptext .= "<p><a href='messages/view/$postid'>".$alert['text']."</a></p>";
        }
        foreach ($list as $usr)
        {
          //echo "getting user $usr\n";
          if ($usr != $alert['userid'])
          {
            $user = User::get($usr);// echo "sending to $user->username\n";
            foreach($user->alerts as $ualert)
            {
              if ($ualert['alert_id']==$match['id'])
              {
                // do types match?
                if ($ualert['type_all'] || $ualert['type_'.$alert['type']])
                {
                  if ($ualert['by_miio'])
                  {
                    // send as timeline message
                    $msg = array();
                    $msg['text'] = str_replace('%ALERT%',$ualert['text'],$miiotext);
                    $msg['sent_to'] = $ualert['userid'];
                    $msg['sharing'] = 'private';
                    $msg['parent_id'] = 0;
                    $msg['system'] = true;
                    $msg['alert'] = true;
                    $msg['link'] = $postid.'_'.$ualert['text'];
                    Post::save(1,$msg);
                  }

                  if ($ualert['by_email'])
                  {
                    // send by email
                    mail($user->email,str_replace('%ALERT%',$ualert['text'],$emailsubject),str_replace('%ALERT%',$ualert['text'],$emailtext),$emailheaders);
                  }

                  if ($ualert['by_sms'])
                  {
                    // send by sms
                    $addr = $user->getSMSEmail();
                    mail($addr, '', str_replace('%ALERT%',$ualert['text'],$smstext), $smsheaders, "-f $smsid@".SMS_EMAIL_HOST);
                  }

                  if ($ualert['by_popup'])
                  {
                    // show toaster popup
                    $user->addPopup($popuptext);
                  }
                }
              }
            }
          }
        }
      }
    }
  }


}

?>