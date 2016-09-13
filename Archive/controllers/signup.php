<?
// signup controller

function submit()
{
  /*SCALABILITY UPDATED OK*/
  include_once BASE."securimage/securimage.php";
  $securimage = new Securimage();
  // validation
  $errors = array();
  // username valid
  if (!Validate::username($_POST['username']))
  {
    $errors[] = "Please enter a valid user name: 3-20 characters, use only letters, numbers, underscore, or hyphen";
  }
  // username not taken
  if (User::exists('username',$_POST['username']))
  {
    $errors[] = "User name is assigned to another Miio member";
  }
  // emails valid
  if (!Validate::email($_POST['email']))
  {
    $errors[] = "Please enter a valid email address";
  }
  // emails match
  if (trim($_POST['email']) != trim($_POST['confirm_email']))
  {
    $errors[] = "Emails do not match";
  }

  // email not taken
  if ($_POST['email']!='aaa@aaa.aaa' && User::exists('email',$_POST['email']))
  {
    $errors[] = "Email address is already assigned to a Miio member";
  }
  // passwords valid
  if (!Validate::password($_POST['password']))
  {
    $errors[] = "Please enter a valid password at least 5 characters long";
  }
  // passwords match
  if ($_POST['password'] != $_POST['password_confirm'])
  {
    $errors[] = "Passwords do not match";
  }

  // captcha ok
  //if (!$LOCAL && $securimage->check($_POST['captcha']) == false)
  if ($securimage->check($_POST['captcha']) == false)
  {
    $errors[] = "Please enter the letters and numbers shown in the image";
  }

  // if errors, re-render page
  if (count($errors)>0)
  {
    $response = array('errors'=>$errors);
    //$response = json_encode($resp);
  }
  // else save user & move on
  else
  {
    global $User, $LOGGEDIN;
    // create new user object
    $User = User::create();
    // save user to database
    if ($_POST['email']=='aaa@aaa.aaa') $code = 'abcdefg';
    else $code = str_random_code(10);

    $User->username = trim($_POST['username']);
    $User->email = $email = trim($_POST['email']);
    // encrypt password
    $User->password = crypt(trim($_POST['password']));
    $User->is_confirmed = false;
    $User->confirmation_code = $code;

    if (isset($_POST['bio_name']))
    {
      $User->name = trim($_POST['bio_name']);
      $User->show_name = true;
    }
    else $User->name = "";
    if (isset($_POST['bio_desc'])) $User->description = trim($_POST['bio_desc']);
    else $User->description = "";
    if (isset($_POST['bio_url']))
    {
      $url = trim($_POST['bio_url']);
      $url = (Validate::url($url)) ? Validate::protocol($url) : "";
      $User->website = $url;
    }
    else $User->website = "";
    if ($_POST['twitter_token']) $User->twitter_token = $_POST['twitter_token'];
    if ($_POST['twitter_secret']) $User->twitter_secret = $_POST['twitter_secret'];
    if ($_POST['twitter_id']) $User->twitter_id = $_POST['twitter_id'];
    if ($_POST['twitter_sn']) $User->twitter_sn = $_POST['twitter_sn'];
    if ($_POST['twitter_push']) $User->twitter_push = $_POST['twitter_push'];
    if ($_POST['twitter_reply']) $User->twitter_reply = $_POST['twitter_reply'];
    if ($_POST['twitter_share']) $User->twitter_share = $_POST['twitter_share'];

    $User->update();
    $User->follow('a');
    // email confirmation code
    if (!$LOCAL)
    {
      Notify::WelcomeToMiio($User);
    }
    // automatically log user in
    $LOGGEDIN = true;

    Session::Set('userid',$User->id);
    // render success message
    $response = array('status'=>'ok');
  }
  echo json_encode($response);
}

function confirm_account()
{
  global $LOGGEDIN, $User, $Cache;

  if ($LOGGEDIN)
  {
    if ($_POST['confirmation_code'] == $User->confirmation_code)
    {
      // confirm & let in
      $User->confirm();
      $response = "ok";
    }
    else $response = "Sorry, we were unable to confirm your account. Please check your confirmation code and try again.";
  }
  else
  {
    $user = User::login($_POST['username'],$_POST['password']);
    if ($user)
    {
      if ($_POST['confirmation_code'] == $User->confirmation_code)
      {
        $User->confirm();
        Session::Set('userid',$User->id);
        $LOGGEDIN = true;
        $response = "ok";
      }
      else $response = "Sorry, we were unable to confirm your account. Please check your confirmation code and try again.";
    }
    else $response = "Sorry, we were unable to log you in. Please check your username and password and try again.";
  }
  echo $response;
}

function resend_confirmation_code()
{
  global $User, $LOGGEDIN, $LOCAL;
  if ($LOGGEDIN)
  {
    $id = $User->id;
    $code = $User->confirmation_code;
    if (!$LOCAL)
    {
      // email confirmation code
      $to = $User->email;
      $headers = "From: Miio <miio@".SMS_EMAIL_HOST.">\n";
      $subject = 'Miio Confirmation';
      $msg = "Congratulations and Welcome to Miio!\n\n";
      $msg .= "Please confirm your account by entering the following code on the confirmation page:\n";
      $msg .= "$code\n(You can copy & paste the code if you want)\n\n";
      $msg .= "Best,\nTeam Miio";
      mail($to, $subject, $msg, $headers);
    }
    $response = "ok";
  }
  else
  {
    $response = "You are not logged in.";
  }
  echo $response;
}

function found_members()
{
  global $LOGGEDIN;
  if (!$LOGGEDIN)
  {
    Render('error','mustlogin');
    return;
  }

  global $User, $USER_LIST, $RANDOM_LIST, $KEYWORDS, $KEYARRAY;
  $tagstr = strtolower(trim(str_replace(',',' ',$_POST['tags'])));
  //$tagstr = str_replace(',',' ',$tagstr);
  //$tagstr = preg_replace('/\s+/',' ',$tagstr);
  $KEYWORDS = $tagstr;
  $tags = explode(' ',$tagstr);
  $KEYARRAY = $tags;
  $USER_LIST = User::getForSignupSuggest($tags,$User->id);
  if (count($USER_LIST)<10) $RANDOM_LIST = User::getRandomExcluding($USER_LIST,$User->id,true);
  Render('signup','found_members');
}

function follow_members()
{
  global $LOGGEDIN;
  if (!$LOGGEDIN)
  {
    Render('error','mustlogin');
    return;
  }

  global $User;
  $follow = explode(',',$_POST['follow']);
  foreach ($follow as $id)
  {
    $User->subscribe($id);
  }
  echo "ok";
}

?>