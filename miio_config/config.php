<?php
//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
set_error_handler("error_handler");
require_once("server_config.php");
$MIIO_OBJECTS = array ( 'User', 'Group', 'Post' );
// restore to absolute path!!!
require_once CONFIG_PATH."miio_config/db.php";
require_once BASE."lib/_lib.php";
// THESE SHOULD BE LOADED WHERE USED, NOT HERE
//require_once BASE."lib/simplepie.inc";
//require_once BASE."lib/idna_convert.class.php";

// MISC USEFUL CONSTANTS
define("MINUTE_IN_SEC",60);
define("HOUR_IN_SEC",60*60);
define("DAY_IN_SEC",HOUR_IN_SEC*24);
define("WEEK_IN_SEC",DAY_IN_SEC*7);
define("PADLOG","           ");
define("DATE_FORMAT",'Y-m-d');
define("TIME_FORMAT",'g:i:s a');
define("24TIME_FORMAT",'H:i:s');
define("DATETIME_FORMAT",'Y-m-d H:i:s');

// RSS FEEDS
define("IMPORT_POST_SIZE", 140);
define("MAX_FEED_COUNT", 5);

// USERLIST ATTRIBUTES
define("USERS_PER_PAGE",10);
define("NUM_PAGE_LINKS",7);

// ALBUM ATTRIBUTES
define("ALBUM_PHOTO_HEIGHT",200);
define("ALBUM_PHOTO_WIDTH",200);

// HEADER PHOTO
define("HEADER_PHOTO_SIZE",25);

// PROFILE
define("PROFILE_PAGE_PHOTO_HEIGHT",208);
define("PROFILE_PAGE_PHOTO_WIDTH",208);
define("PROFILE_PHOTO_UPLOAD_SIZE",300);

// MESSAGE LIST
define("AVATAR_SIZE",50);
define("MESSAGE_AVATAR_SIZE",50);
define("MESSAGES_PER_PAGE",10);
define("MAX_INLINE_REPLIES",3);
define("MAX_INLINE_SHARES",3);

// MESSAGE FORM
define("PHOTO_MAX_FILE_SIZE",8388608);
define("PHOTO_MAX_FILE_SIZE_TEXT","8MB");
define("PHOTO_MAX_HEIGHT",600);
define("PHOTO_MAX_WIDTH",600);
define("PHOTO_MAX_DISPLAY_HEIGHT",405);
define("PHOTO_MAX_DISPLAY_WIDTH",405);
define("MESSAGELIST_VIDEO_MAX_HEIGHT",135);
define("MESSAGELIST_VIDEO_MAX_WIDTH",180);
define("MESSAGELIST_PHOTO_MAX_HEIGHT",135);
define("MESSAGELIST_PHOTO_MAX_WIDTH",180);
define("VIDEO_MAX_HEIGHT",486);
define("VIDEO_MAX_WIDTH",486);
define("VIDEO_MAX_DISPLAY_HEIGHT",400);
define("VIDEO_MAX_DISPLAY_WIDTH",400);
define("MAX_RECIPIENTS",100);

// TWITTER
define("TWITTER_KEY", "8IOv8nchbdhBmfYPhCVM3Q");
define("TWITTER_SECRET","UUqExVyrGMElGAxZedC4aGsD0W2JWfDixI76DjcY");
// I GUESS WE CAN USE EITHER
//define("TWITTER_KEY", "wkL0WR2GOkkKlO3L4Hvw");
//define("TWITTER_SECRET","uFJ4IUrIa4oHK32kuZu2pPWNbsGBdKYstfGARa5Cgo");
define("TWITTER_INITIAL_PAGES", 2); // Twitter Initial Paging Checkout Size
define("TWITTER_INITIAL_PER_PAGE", 20); //Tweets Per Page
define("TWITTER_GET_PAGES", 16); // UNUSED: Twitter GET Paging Checkout Size
define("TWITTER_COUNT_PER_PAGE", 200); //Tweets Per Page

// TXTNATION
define("TXTNATION_KEY","60da599d286873f9feb55bb95609c822");

// GOOGLE MAPS
define("GMAP_KEY",'ABQIAAAAyeLvid6TGq5xkleoVjDj2xTxxenv-5OJOizaKXhK7LQe1e2FFxQ6Jp79ykz1MvdnpOKoX5Ubv9sruQ');

// DEFAULT SETTINGS
define("FOLLOW_DEFAULT",'{"dashboard":{"1":1,"2":1,"3":1,"4":1,"5":1,"6":1,"7":1,"8":1,"9":1,"10":1},"sms":[],"email":{"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":0,"8":0,"9":0,"10":0},"muted":0,"sms_on":0}');
define("MEMBERSHIP_DEFAULT",'{"dashboard":{"1":1,"2":1,"3":1,"4":1,"5":1,"6":1,"7":1,"8":1,"9":1,"10":1},"sms":[],"email":{"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":0,"8":0,"9":0,"10":0},"muted":0,"sms_on":0}');



// CREATE CACHE OBJECT
$Cache = new Memcache;
foreach ($CACHE_SERVERS as $cache)
{
  $x = $Cache->addServer($cache['server'],$cache['port']);
  if (!$x) log_write("failed to add cache server ".$cache['server']);
}

// handle session reconnect & login

$LOGGEDIN = false;

Session::Start();

$suid = Session::Get('userid');
if ($suid)
{
  $User = User::get($suid);
  if ($User->is_confirmed)
  {
    $CONFIRMED = true;
  }
  $LOGGEDIN = true;
}
else if (isset($_COOKIE['Miio']))
{
  $User = User::remember($_COOKIE['Miio']);
  if ($User)
  {
    $LOGGEDIN = true;
    if ($User->is_confirmed)
    {
      $CONFIRMED = true;
    }
    Session::Restart();
    Session::Set('userid',$User->id);
    $remember = $User->rememberMe();
    $exp = time() + (60*60*24*30);
    setcookie('Miio',$remember,$exp,'/',COOKIE_HOST);
  }
}

if ($_GET['controller'] == "user" && $_GET['page'] == "login" && isset($_POST['login_submit']))
{
  $_POST['login_username'] = trim($_POST['login_username']);
  $User = User::login($_POST['login_username'],$_POST['login_password']);
  if ($User)
  {
    // login succeeded
    Session::Restart();
    Session::Set('userid',$User->id);
    if ($_POST['login_remember'] == "on")
    {
      $remember = $User->rememberMe();
      $exp = time() + (60*60*24*30);
      setcookie('Miio',$remember,$exp,'/',COOKIE_HOST);
    }
    header("Location: ".LOC);
    die;
  }
  else $LOGIN_ERR = true;
}
else if ($_GET['controller'] == "user" && $_GET['page'] == "password_reset")
{
  //$RPW = true;
  $LOGGEDIN = false;
  if (!isset($_POST['submit']))
  {
    setcookie('remember',$_COOKIE['remember'],time()-DAY_IN_SEC,'/',COOKIE_HOST);
    Session::Restart();
  }
}

define("LOGGEDIN",$LOGGEDIN);

if (!$User) $User = User::get(0);

function __autoload($class)
{
  global $MIIO_OBJECTS;
  if (in_array($class,$MIIO_OBJECTS)) require_once(BASE."objects/$class.php");
  else require_once(BASE."classes/$class.php");
}

function error_handler($errno, $errstr, $errfile, $errline)
{
  /*$errs = array
  (
    E_ERROR=>"FATAL ERROR:", E_WARNING=>"WARNING:", E_PARSE=>"PARSE:",
    E_NOTICE=>"NOTICE:", E_STRICT=>"STRICT:", E_DEPRECATED=>"DEPRECATED:"
  );*/
  $errs = array
  (
    E_ERROR=>"FATAL ERROR:", E_PARSE=>"PARSE:",
    E_STRICT=>"STRICT:", E_DEPRECATED=>"DEPRECATED:"
  );
  if (array_key_exists($errno,$errs))
  {
    global $ErrorLog;
    if (!isset($ErrorLog)) $ErrorLog = new Log('error');
    $message = $errs[$errno] . " $errstr on line $errline in file $errfile";
    $ErrorLog->write($message);
  }
  return false;
}


?>