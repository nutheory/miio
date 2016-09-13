<?
// User controller

/*********************************** PAGES  ***********************************/

function index()
{
  global $User;
  if (!LOGGEDIN) PageRender("user", "login", "ml");
  else if (!CONFIRMED) { PageRender("signup","confirm","ml"); }
  else
  {
    Render("user","index");
  }
}

function timeline()
{
  Show::Messages('user','timeline');
}

function received()
{
  Show::Messages('user','received');
}

function sent()
{
  Show::Messages('user','sent');
}

function rreceived()
{
  Show::Messages('user','rreceived');
}

function rsent()
{
  Show::Messages('user','rsent');
}

function thread()
{
  Show::Messages('user','thread');
}

function notifications()
{
  Show::Messages('user','notifications');
}

function people()
{
  global $PARAMS;
  Show::Users('user',$PARAMS);
}

function groups()
{
  global $PARAMS;
  if ($PARAMS=='create')
  {
    if (!LOGGEDIN)
    {
      Render('user','login','ml');
      return;
    }
    else if (!CONFIRMED)
    {
      Render("signup","confirm","ml");
      return;
    }
    Render('user','creategroup');
  }
  else
  {
    Show::Groups('user',$PARAMS);
  }
}

function invite()
{
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  Render('user','invite');
}

function settings()
{
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  Render('user','settings');
}

function profile()
{
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  Render('user','settings');
}

function photo()
{
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  Render('user','settings');
}

function twitter()
{
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  Render('user','settings','twitter');
}

function rss()
{
  if (!LOGGEDIN)
  {
    Render('user','login','ml');
    return;
  }
  else if (!CONFIRMED)
  {
    Render("signup","confirm","ml");
    return;
  }
  Render('user','settings','rss');
}

function password_reset()
{
  global $User,$PARAMS, $RESET_STATUS;
  $RESET_STATUS = 'bad';
  // test for form submission
  if (isset($_POST['submit']))
  {
    global $err;
    $err = array();

    if (!Validate::password($_POST['settings_newpw'])) $err['validate']=true;
    if ($_POST['settings_newpw'] != $_POST['settings_confirmpw']) $err['match']=true;

    if (count($err)>0)
    {
      $RESET_STATUS = 'err';
    }
    else
    {
      // really save pw
      $User = User::getByResetCode(Session::Get('pw_code'));
      if ($User->changePassword($_POST['settings_newpw']))
      {
        Session::Clear('pw_code');
        $RESET_STATUS = 'sub';
      }
      else
      {
        $err['unknown']=true;
        $RESET_STATUS = 'err';
      }
    }
  }
  else
  {
    if (isset($_POST['submit_code'])) $resetcode = $_POST['reset_code'];
    else if (trim($PARAMS)=="") $RESET_STATUS = 'bln';
    else $resetcode = $PARAMS;

    if ($resetcode)
    {
      $User = User::getByResetCode($resetcode);
      if ($User)
      {
        $now = time() - 1;
        if ($User->password_reset < $now - (DAY_IN_SEC*1000)) $RESET_STATUS = 'exp';
        else
        {
          Session::Set('pw_code',$resetcode);
          $RESET_STATUS = 'acc';
        }
      }
      Session::Set('pw_reset_status',$RESET_STATUS);
    }
  }
  Render('user','password_reset');
}

function forgot_login()
{
  if (isset($_POST['login_submit']))
  {
    global $LOGIN_ERR,$LOGIN_RESET;
    $email = trim($_POST['login_email']);
    $username = trim($_POST['login_username']);
    if ($email=="") $blank1=true; else $ok1 = Validate::email($email);
    if ($username=="") $blank2=true; else $ok2 = Validate::username($username);

    if ($blank1&&$blank2) {$LOGIN_ERR = true;} // both blank
    if ((!$blank1 && !$ok1) || (!$blank2 && !$ok2)) {$LOGIN_ERR = true;} // both invalid
    if (!$LOGIN_ERR)
    {
      if ($ok1) $user1 = User::getByEmail($email); // get users
      if ($ok2) $user2 = User::getByName($username);
      if (!$blank1 && !$user1) {$LOGIN_ERR = true;} // email not blank & not user
      if (!$blank2 && !$user2) {$LOGIN_ERR = true;} // username not blank & not user
      if (!$user1 && !$user2) {$LOGIN_ERR = true;} // no users found
      if ($user1 && $user2 && ($user1 != $user2)) {$LOGIN_ERR = true;} // 2 different users found
      //if ($user1 && $user2 && ($user1->id != $user2->id)) {$LOGIN_ERR = true;} // 2 different users found
    }
    if (!$LOGIN_ERR)
    {
      if ($user1) $User=$user1;
      else $User=$user2;
      $time = time();
      $code = str_random_code(10);
      $User->setPasswordReset($time,$code);
      $LOGIN_RESET = true;
      if (!$LOCAL)
      {
        Notify::PasswordReset($User,$code);
      }
    }
  }
  Render ('user','forgot_login');
}

/************************************ AJAX ************************************/

/*********************************** CREATE ***********************************/

function save_group()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  global $VALUES, $ERRORS;
  $ERRORS = array();
  $error = false;

  $VALUES['groupname'] = trim($_POST['groupname']);
  $VALUES['name'] = trim($_POST['name']);
  $VALUES['show_name'] = $_POST['showname'];
  $VALUES['description'] = trim($_POST['description']);
  $VALUES['category'] = (int)$_POST['category'];
  $VALUES['visibility'] = $_POST['visibility'];
  $VALUES['website'] = trim($_POST['website']);
  if ($VALUES['visibility']=='public') $VALUES['sendannouncement'] = $_POST['announce'];
  else $VALUES['sendannouncement'] = 0;
  $VALUES['country'] = $_POST['country'];
  if ($VALUES['country'] != "") $VALUES['country_code'] = Places::get_country_code($VALUES['country']);
  else($VALUES['country_code'] = "");
  $VALUES['state'] = $_POST['state'];
  if ($VALUES['state'] != "") $VALUES['state_code'] = Places::get_state_code($VALUES['country_code'],$VALUES['state']);
  else($VALUES['state_code'] = "");
  $VALUES['city'] = $_POST['city'];
  if ($VALUES['city'] != "") $VALUES['city_code'] = Places::get_city_code($VALUES['country_code'],$VALUES['state_code'],$VALUES['city']);
  else($VALUES['city_code'] = "");

  // username valid
  if (!Validate::username($VALUES['groupname']))
  {
    $error = true;
    $ERRORS[] = "Please enter a group name";
  }
  // username not taken
  if (Group::exists('groupname',$VALUES['groupname']) || User::exists('username',$VALUES['groupname']))
  {
    $error = true;
    $ERRORS[] = "The selected group name is already in use";
  }
  // description not empty
  if ($VALUES['description']=="")
  {
    $error = true;
    $ERRORS[] = "Please enter a description";
  }
  // category selected
  if ($VALUES['category']==0)
  {
    $error = true;
    $ERRORS[] = "Please select a category";
  }
  // validate website
  if ($VALUES['website']!="" && !Validate::url($VALUES['website']))
  {
    $error = true;
    $ERRORS[] = "Website is not a valid URL";
  }

  if ($error)
  {
    // render form again
    echo json_encode($ERRORS);
  }
  else
  {
    if ($VALUES['website']!="") $VALUES['website'] = Validate::protocol($VALUES['website']);
    $Group = Group::create();
    $Group->groupname = $VALUES['groupname'];
    $Group->name = $VALUES['name'];
    $Group->show_name = $VALUES['show_name'] = $_POST['showname'];
    $Group->description = $VALUES['description'];
    $Group->category = $VALUES['category'];
    $Group->visibility = Enum::$visibility[$VALUES['visibility']];
    $Group->website = $VALUES['website'];
    if ($Group->visibility==Enum::$visibility['public']) $announce_group = $_POST['announce']==1;
    else $announce_group = 0;
    $Group->country = $VALUES['country'];
    $Group->region = $VALUES['region'];
    $Group->city = $VALUES['city'];
    if ($_POST['photo']!="")
    {
      $avatarurl = Image::saveFromTemp($_POST['photo'],'group_avatar',false,$id);
      $filename = Image::saveFromTemp($_POST['photo'],'group',true,$id);
      $Group->photo=$filename;
    }
    $Group->keywords = preg_split('/\s/',$_POST['tags'],null,PREG_SPLIT_NO_EMPTY);
    $Group->save();

    $User->joinGroup($Group->id);
    $Group->makeAdmin($User->id);
    $Group->makeOwner($User->id,true);

    if ($_POST['invite']!='none')
    {
      $list = array();
      if ($_POST['invitelist']) $list=explode(',',$_POST['invitelist']);
      Notify::GroupInvitation($Group,$User,$_POST['invite'],$list);
    }
    if ($announce_group)
    {
      Notify::AnnounceGroup($Group,$User);
    }
    echo "ok_$Group->id";
  }
}


/*********************************** UPLOAD ***********************************/


/*********************************** INVITE ***********************************/

function send_email_invitations()
{
  global $User, $LOCAL, $LOC;
  if (!LOGGEDIN)
  {
    echo "Error: Not logged in";
    die;
  }
  if (!$LOCAL)
  {
    $emails = split(',',$_POST['emails']);
    $message = "Have you seen this? http://miio.com";
    $name = ( ($User->first_name=="") && ($User->last_name=="") ) ? $User->username : trim($User->first_name." ".$User->last_name);
    //$headers = "From: $name <miio_user@".SMS_EMAIL_HOST.">";
    $headers = "From: $name <$User->email>";
    $subject = "Miio";
    foreach($emails as $email)
    {
      if (trim($email)!="")
      {
        mail($email, $subject, $message, $headers);
      }
    }
  }
  echo "ok";
}

function send_sms_invitation()
{
  global $User, $LOCAL;
  if (!LOGGEDIN)
  {
    echo "Error: Not logged in";
    die;
  }
  if ($LOCAL)
  {
    echo "ok";
    die;
  }
  $number = trim($_POST['sms']);
  if ($number == "")
  {
    echo "Please enter a phone number.";
    die;
  }
  $country = $_POST['country'];
  //$provider = $_POST['carrier'];
  $provider = "";
  $sender = $User->first_name . ' ' . $User->last_name;
  if (trim($sender)=="") $sender = $User->username;
  $message = "Have you seen this? http://miio.com";

  $number = str_replace("-","",$number);
  $number = str_replace(" ","",$number);
  $number = str_replace("(","",$number);
  $number = str_replace(")","",$number);
  // is number claimed by an existing member?

  $recipient = User::getByMobileNumber($number);
  if ($recipient)
  {
    if ($recipient == $User)
    {
      // sending to yourself
      $phone = $User->getSMSEmail();
      $headers = "From: sms_notify@".SMS_EMAIL_HOST."\n";
      $headers .= "Priority: normal";
      $subject = '';
      $message = "You just invited yourself to join Miio. Obviously, you're already a member.";
      mail($phone, $subject, $message, $headers, "-f sms_notify@".SMS_EMAIL_HOST);
    }
    else
    {
      // send email
      $message = "$User->username sent you this Miio invite and may not know you're already a member. You can see $User->username's profile at http://miio.com/user/profile/$User->id";
      $subject = "Invitation from Miio User";
      $headers = "From: email_notify@".SMS_EMAIL_HOST."\n";
      mail($recipient->email, $subject, $message, $headers);

      if ($recipient->sms_confirmed && $recipient->sms_friend_request)
      {
        // send sms
        $phone = $recipient->getSMSEmail();
        $headers = "From: smsinvite_$User->id@".SMS_EMAIL_HOST."\n";
        $headers .= "Priority: normal";
        $subject = '';
    $message = "$User->username just invited you to join Miio via SMS. You can check out their profile here: http://miio.com/$User->username";
    // $message = "$User->username sent you this Miio invite and may not know you're already a member. Reply YES to send them a friend request.";
    // $message = "$User->username just tried inviting you to join Miio via SMS. $User->username probably doesn't realize you are already a member. You can check out $User->username's profile here.";
        mail($phone, $subject, $message, $headers, "-f smsinvite_$User->id@".SMS_EMAIL_HOST);
      }
    }
    echo "ok";
  }
  else if ($provider=="")
  {
    // use SMS gateway to send
    $params = "action=ir_gateway";
    $params .= "&cc=miio";
    $params .= "&id=" . str_random_code(8);
    // country code and country network required
    $countrycode = Places::get_sms_code($country);
    $params .= "&number=" . $countrycode . $number;
    $params .= "&network=VS_ALLNETS2US";
    $params .= "&message=" . $message;
    $params .= "&value=0";
    $params .= "&ekey=" . TXTNATION_KEY;
    $url = 'http://client.txtnation.com/ir_response.php';//?'.$params;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $buffer = curl_exec($ch);
    curl_close($ch);
    if (strstr($buffer,"SUCCESS"))
    {
      echo "ok";
    }
    else
    {
      echo "Error:\n\n$buffer";
    }
  }
  else
  {
    // send by email
    // get provider index
    foreach (Options::$carriers as $name=>$carrier)
    {
      if (strtolower($provider) == strtolower($carrier['name']))
      {
        $email = $carrier['email'];
        break;
      }
    }
    $email = str_replace('#',$number,$email);
    // what should we set the headers to?
    $headers = "From: $User->username <$User->username@miio.com>\n";
    $headers .= "Priority: normal";
    $subject = '';
    mail($email, $subject, $message, $headers, "-f $User->username@miio.com");
    echo "ok";
  }
}

/*********************************** ALERTS ***********************************/

function save_alert()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  if ($User->saveAlert($_POST)) echo "ok";
  else echo "Unable to save Alert";
}

function pause_alert()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!ValidID($_POST['id']))
  {
    echo "Invalid Alert ID";
    return false;
  }
  if ($User->pauseAlert($_POST['id'],true)) echo "ok";
  else echo "Unable to pause Alert";
}

function resume_alert()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!ValidID($_POST['id']))
  {
    echo "Invalid Alert ID";
    return false;
  }
  if ($User->pauseAlert($_POST['id'],false)) echo "ok";
  else echo "Unable to resume Alert";
}

function delete_alert()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!ValidID($_POST['id']))
  {
    echo "Invalid Alert ID";
    return false;
  }
  if ($User->deleteAlert($_POST['id'])) echo "ok";
  else echo "Unable to delete Alert";
}

function update_alert()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!ValidID($_POST['id']))
  {
    echo "Invalid Alert ID";
    return false;
  }
  if ($User->updateAlert($_POST)) echo "ok";
  else echo "Unable to update Alert.";
}

/********************************** SETTINGS **********************************/

function update_profile()
{
  global $User;
  if (!LOGGEDIN)
  {
    log_write("user/update_profile not logged in\n".print_r($_SERVER,true));
    echo "Not logged in";
    die;
  }

  $toupdate = array();
  // sanitize all input before saving
  $name = substr(trim($_POST['name']),0,100);
  if ($name!=$User->name) { $User->name = $name; $toupdate[] = 'name'; }
  $showname = ($_POST['show_name']==1) ? 1 : 0;
  if ($showname!=$User->show_name) { $User->show_name = $showname; $toupdate[] = 'show_name'; }
  $description = substr(trim($_POST['description']),0,140);
  if ($description!=$User->description) { $User->description = $description; $toupdate[] = 'description'; }
  // continue in this pattern

  $date = getdate();
  if
  (
    is_numeric($_POST['year']) && $_POST['year']>1900 && $_POST['year']<$date['year'] &&
    is_numeric($_POST['month']) && $_POST['month']>0 && $_POST['month']<13 &&
    is_numeric($_POST['day']) && $_POST['day']>0 && $_POST['day']<32
  )
  {
    $User->birthday = date('Y-m-d',strtotime($_POST['year'].'/'.$_POST['month'].'/'.$_POST['day']));
  }
  $country = $_POST['country'];
  $region = $_POST['state'];
  $city = $_POST['city'];
  /*
  $User->country_name = ;
  if ($User->country_name != "") $User->country = Places::get_country_code($User->country_name);
  else($User->country = "");
  $User->state_name = $_POST['state'];
  if ($User->state_name != "") $User->state = Places::get_state_code($User->country,$User->state_name);
  else($User->state = "");
  $User->city_name = $_POST['city'];
  if ($User->city_name != "") $User->city = Places::get_city_code($User->country,$User->state,$User->city_name);
  else($User->city = "");
  */
  if (array_key_exists($_POST['gender'],Enum::$gender)) $User->gender = Enum::$gender[$_POST['gender']];
  if (in_array($_POST['ethnicity'],Enum::$ethnicity)) $User->ethnicity = $_POST['ethnicity'];
  $url = trim($_POST['website']);
  $User->website = (Validate::url($url)) ? Validate::protocol($url) : "";

  if (!isset($User->looking_for)) $User->looking_for = array();
  foreach (Enum::$looking_for as $opt=>$val)
  {
    $User->looking_for[$val] = $_POST['lf_'.$opt]==1 ? 1 : 0;
  }

  $User->interested_in['male'] = $_POST['interested_male']==1 ? 1 : 0;
  $User->interested_in['female'] = $_POST['interested_female']==1 ? 1 : 0;

  if (in_array($_POST['relationship'],Enum::$relationship)) $User->relationship = $_POST['relationship'];
  if (in_array($_POST['visibility'],Enum::$visibility)) $User->visibility = $_POST['visibility'];
  $User->keywords = preg_split('/\s/',$_POST['tags'],null,PREG_SPLIT_NO_EMPTY);

  $ok = $User->update();
  if ($ok) echo "ok";
  else echo "An unknown error occurred.";
}

function update_mobile()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  foreach (Options::$carriers as $key=>$carrier)
  {
    if ($_POST['sms_provider']==$carrier['name'])
    {
      $smsProvider = $key;
      break;
    }
  }
  // is number in use?
  $usedby = User::getByMobileNumber($_POST['notification_sms']);
  if (!$usedby || ($usedby==$User))
  {
    $User->notification_sms = $_POST['notification_sms'];
    $User->sms_provider = $smsProvider;
    $User->sms_country = Places::get_country_code($_POST['country']);
    $User->sms_confirmed = 0;
    $User->sms_confirmation = str_random_code(8);
    $User->sms_web_enabled = $_POST['sms_web_enabled'];
    $User->sms_accept_charges = $_POST['sms_accept_charges'];
    $ok = $User->update();
    Notify::SMSConfirmation($User);
    if ($ok) echo "ok";
    else echo "An unknown error occurred";
  }
  else
  {
    echo("The number you entered is already assigned to a Miio member");
  }
}

function remove_mobile()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $User->notification_sms = "";
  $User->sms_provider = "";
  $User->sms_country = "";
  $User->sms_confirmed = 0;
  $User->sms_confirmation = "";
  $User->sms_web_enabled = 0;
  $User->sms_accept_charges = 0;
  $ok = $User->update();
  if ($ok) echo "ok";
  else echo "An unknown error occurred";
}

function resend_sms_confirmation()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  Notify::SMSConfirmation($User);
  echo "ok";
}

function update_notifications()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  foreach (Options::$notification_types as $key=>$text)
  {
    $dk = 'dashboard_'.$key;
    $ek = 'email_'.$key;
    $sk = 'sms_'.$key;

    $User->notification_settings[$key]['dashboard'] = (isset($_POST[$dk]) ? $_POST[$dk] : 0);
    $User->notification_settings[$key]['email'] = (isset($_POST[$ek]) ? $_POST[$ek] : 0);
    $User->notification_settings[$key]['sms'] = (isset($_POST[$sk]) ? $_POST[$sk] : 0);
  }

  $ok = $User->update(false);

  if ($ok) echo "ok";
  else echo "An unknown error occurred.";
}

function update_messagesettings()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  foreach (Options::$message_preferences as $key=>$text)
  {
    $ek = 'email_'.$key;
    $sk = 'sms_'.$key;

    $User->message_settings[$key]['email'] = (isset($_POST[$ek]) ? $_POST[$ek] : 0);
    $User->message_settings[$key]['sms'] = (isset($_POST[$sk]) ? $_POST[$sk] : 0);
  }

  $ok = $User->update(false);

  if ($ok) echo "ok";
  else echo "An unknown error occurred.";
}

function update_twitter()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  if($_POST['destroy']=='1')
  {
    unset ($User->twitter);
    $User->update(false);
    echo "destroyed";
  }
  else if ($_POST['updatetwitter']=='1')
  {
    $User->twitter['push']  = ($_POST['push']=='1'?'1':'0');
    $User->twitter['share'] = ($_POST['share']=='1'?'1':'0');
    $User->twitter['reply'] = ($_POST['reply']=='1'?'1':'0');
    $User->update(false);
    if ($_POST['twitter_action'] == 'update') echo "updated";
    else if ($_POST['twitter_action'] == 'add') echo "added";
    else echo "An unknown error occurred.";
  }
  else if ($_POST['savetwitter']=='1')
  {
    // TODO: This is still quite insecure. Need to sanitize input
    $User->twitter = array
    (
      "token"   => $_POST['twitter_token'],
      "secret"  => $_POST['twitter_secret'],
      "id"      => $_POST['twitter_id'],
      "sn"      => $_POST['twitter_sn']
    );
    $User->update(false);
    echo "added";
  }
  else echo "Error: Undefined request to update_twitter";
}

function check_twitter()
{
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  if ($_POST['check'] == 1)
  {
    if (Twitter::checkTweets()) echo "ok";
    else echo "Twitter check failed!";
  }
  else echo "Error: Unexpected request to check_twitter";
}

function check_rss()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  if (Feed::testFeed($_POST['rss_url'])) echo "ok";
  else echo "Error";
}

function save_rss()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  if ($_POST['rss_url'])
  {
    $ok = Feed::saveNewFeed($User->id, $_POST['rss_url']);
  }
  else $ok = "Please Enter a Valid Url";
  echo $ok;
}

function destroy_feed()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  echo Feed::destroyFeed($User->id, $_POST['rss_url']);
}

function update_rss()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  echo "update_rss - don't know what to do";
}

function update_username()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $username = trim($_POST['username']);
  if (strtolower($User->username)==strtolower($username))
  {
    echo "ok";
    die;
  }
  if (User::exists('username',$username) || Group::exists('groupname',$username))
  {
    echo "taken";
    die;
  }
  if (!Validate::username($username))
  {
    echo "invalid";
    die;
  }
  $User->username = $username;
  $User->update();
  echo "ok";
}

function update_password()
{
  global $User;
  if (!LOGGEDIN && !$_POST['code'])
  {
    echo "Not logged in";
    die;
  }
  if ($_POST['oldpw'])
  {
    $usr = User::login($User->username,$_POST['oldpw']);
    if ($usr!=$User)
    {
      echo "wrongpw";
      die;
    }
  }

  if (!Validate::password($_POST['password']))
  {
    echo "invalid";
    die;
  }
  if ($_POST['password'] != $_POST['password_confirm'])
  {
    echo "nomatch";
    die;
  }
  $User->password = crypt(trim($_POST['password']));
  if ($isreset)
  {
    unset($User->reset_code);
    unset($User->reset_expires);
    $User->update();
  }
  else $User->update(false);
  echo "ok";
}

function reset_password()
{
  global $User, $LOC, $LOCAL;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $User->reset_expires = time()+DAY_IN_SECONDS;
  $User->reset_code = str_random_code(10,'ltr_only');
  $User->update();
  if (!$LOCAL)
  {
    Notify::PasswordReset($User->email,$code);
  }
  echo "ok";
}

function update_email()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $email = trim($_POST['email']);
  if ($email==$User->email)
  {
    echo "ok";
    die;
  }
  if (!Validate::email($email))
  {
    echo "invalid";
    die;
  }
  if (User::exists('email',$email))
  {
    echo "taken";
    die;
  }
  $User->email = $email;
  $User->update();
  echo "ok";
}

function update_refresh()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $User->refresh_rate = $_POST['rate'];
  $ok = $User->update(false);
  if ($ok) echo "ok".$_POST['rate'];
  else echo "An unknown error occurred.";
}

function update_profile_photo()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  // delete old photo before saving new one
  Image::deleteProfilePhoto($User->photo);
  $avatarurl = Image::saveFromTemp($_POST['photo'],'profile_avatar',false);
  $photourl = Image::saveFromTemp($_POST['photo'],'profile', false);
  $User->photo = $photourl;
  $ok = $User->update(false);
  if ($ok) echo "ok".$photourl;
  else echo "error";
}

function delete_profile_photo()
{
  global $User, $LOC;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  Image::deleteProfilePhoto($User->photo);
  $User->photo = "";
  $ok = $User->update(false);
  if ($ok) echo "ok";
  else echo "error";
}

function update_album()
{
  global $User, $LOC;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if (!is_numeric($_POST['album_id']))
  {
    echo "The album id is invalid.";
    die;
  }
  $album = $_POST['album_id'];
  if (!$User->albums[$album])
  {
    echo "This is not your album.";
    die;
  }
  $ok = $User->updateAlbum($album,$_POST['title'],$_POST['description']);
  if ($ok) echo "ok$album";
  else echo "unknown error";
}

function upload_album()
{
  global $User, $LOC;
  if (!LOGGEDIN)
  {
    $error = "Not logged in";
  }

  if ($_POST['album_id'] == 'new')
  {
    // new album
    if (count($User->albums)>4)
    {
      $error = "too_many";
    }
    else
    {
      $album = $User->createAlbum($_POST['title'],$_POST['description']);
    }
  }
  else if (is_numeric($_POST['album_id']))
  {
    // update existing album
    $album = $_POST['album_id'];
  }
  else
  {
    $error = "bad_id";
  }

  if ($error)
  {
    echo "<script type='text/javascript'>";
    echo "top.".$_POST['js_error']."('$error');";
    echo "</script>";
  }
  else
  {
    $response = array();
    $response['errors'] = array();
    $response['files'] = array();
    $response['id'] = $album;

    // upload new photos
    foreach ($_FILES as $ord=>$file)
    {
      if ($file['error']==0)
      {
        $photo = array();
        $photo['album'] = $album;
        $photo['order'] = $ord;
        $photo['original_filename'] = $file['name'];
        $photo['title'] = $photo['original_filename'];
        $time = explode(' ',microtime());
        $name = $time[1] . $time[0];
        $result = Image::save($name,$file,PHOTO_MAX_WIDTH,PHOTO_MAX_HEIGHT,'album');
        if ($result['error'])
        {
          $response['errors'][] = $file['name'].": ".$result['error'];
        }
        else
        {
          $photo['saved_filename'] = $result['filename'];
          $User->savePhoto($photo);
          $response['files'][] = $file['name'];
        }
      }
    }
    $r = json_encode($response);
    echo "<script type='text/javascript'>";
    echo "top.".$_POST['js_return']."('$r');";
    echo "</script>";
  }
}

function delete_photo()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  $album = $_POST['album'];
  if (!$User->albums[$album])
  {
    echo "This is not your album.";
    die;
  }
  $photo = $_POST['photo'];
  if (!$User->albums[$album]['photos'][$photo])
  {
    echo "Photo is not in this album.";
    die;
  }
  if ($User->deletePhoto($album,$photo)) echo "ok";
  else echo "Unable to delete album.";
}

function delete_album()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if ($User->deleteAlbum($_POST['album'])) echo "ok";
  else echo "Unable to delete album.";
}

function cancel_account()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  if ($User->remove())
  {
    unset($User);
    echo "ok";
  }
  else echo "Unable to cancel account.";
}

?>