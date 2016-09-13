<?
// MEMBERS controller

/*********************************** PAGES  ***********************************/

function profile()
{
  global $Profile, $PARAMS;
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  if ($Profile->is_suspended)
  {
    Render('members','suspended');
    return;
  }
  Render('members','profile');

}

function timeline()
{
  global $Profile, $PARAMS, $GET;
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  Show::Messages('member',$GET['type']);
}

function albums()
{
  global $Profile, $PARAMS;
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  Render('members','albums');
}

function description()
{
  global $Profile, $PARAMS;
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  Render('members','description');
}

function friends()
{
  global $Profile, $PARAMS;
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  Show::Users('profile','friends');
}

function followers()
{
  global $Profile, $PARAMS;
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  Show::Users('profile','followers');
}

function following()
{
  global $Profile, $PARAMS;
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  Show::Users('profile','following');
}

function groups()
{
  global $Profile, $PARAMS;
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  Show::Groups('profile','publicgroups');
}

function manage()
{
  global $Profile, $PARAMS;
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
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  Render('members','manage_subscription');
}

function report()
{
  global $Profile, $PARAMS;
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
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    Render('members','notfound');
    return;
  }
  Render('members','report_member');
}

/************************************ AJAX ************************************/

function index()
{
  Render('members','index');
}

function featured()
{
  global $FEATURED_LIST, $LIST_PAGE, $TAGLINE_LIST, $PAGING_SIZE, $FEATURED_COUNT, $ITEM_COUNT;
  if ($_POST['page'] || $_POST['page'] > 0) $LIST_PAGE = $_POST['page'];
  else $LIST_PAGE = 0;
  if ($_POST['per_page']) $ITEM_COUNT = $_POST['per_page'];
  $TAGLINE_LIST = User::taglines_users();
  $FEATURED_LIST = User::featured_getall();
  $FEATURED_COUNT = count($FEATURED_LIST);
  $paging = $FEATURED_COUNT / $ITEM_COUNT;
  $PAGING_SIZE = floor($paging);
  if ($PAGING_SIZE == 0) $PAGING_SIZE= 1;
  if ($LIST_PAGE > $PAGING_SIZE) $LIST_PAGE = 1;
  if ($LIST_PAGE < 1 && $PAGING_SIZE > 1) $LIST_PAGE = $PAGING_SIZE;
  if ($LIST_PAGE == 0) $LIST_PAGE = 1;

  Render('partials','members');
}

function update_subscription()
{
  global $User, $Profile, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    echo "Invalid member id";
    die;
  }
  if ($User->updateFollowSettings($PARAMS,$_POST)) echo "ok";
  else echo "Unknown error";
}

function cancel_subscription()
{
  global $User, $Profile, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    echo "Invalid member id";
    die;
  }
  if ($User->unFollow($PARAMS)) echo "ok";
  else echo "Unknown error";
}

function request_subscription()
{
  global $User, $Profile, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    echo "Invalid member id";
    die;
  }
  if ($Profile->is_group)
  {
    echo "Can't follow a group";
    die;
  }
  if ($User->requestSubscription($PARAMS)) echo "ok";
  else echo "Unknown error";
}

function cancel_subscription_request()
{
  global $User, $Profile, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    echo "Invalid member id";
    die;
  }
  if ($Profile->is_group)
  {
    echo "Can't follow a group";
    die;
  }
  if ($User->cancelSubscriptionRequest($PARAMS))
  {
    Notify::FollowCanceled($User,$Profile);
    echo "ok";
  }
  else echo "Unknown error";
}

function subscribe()
{
  global $User, $Profile, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    echo "Invalid member id";
    die;
  }
  if ($User->follow($PARAMS))
  {
    Notify::NewFollower($User,$Profile);
    echo "ok";
  }
  else echo "Unknown error";
}

function block()
{
  global $User, $Profile, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    echo "Invalid member id";
    die;
  }
  if ($User->block($PARAMS)) echo "ok";
  else echo "Unknown error";
}

function unblock()
{
  global $User, $Profile, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    echo "Invalid member id";
    die;
  }
  if ($User->unblock($PARAMS)) echo "ok";
  else echo "Unknown error";
}

function submit_report()
{
  global $User, $Profile, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    echo "Invalid member id";
    die;
  }
  if ($User->reportUser($PARAMS))
  {
    // send report message to appropriate place
    Notify::ReportedMember($User,$Profile,$_POST);
    echo "ok";
  }
  else echo "Unknown error";
}

?>