<?
$today = date('Ymd');
$fout = fopen("/logs/sms_$today.log","a");
fwrite($fout,"\n\n================================================================================\nSMS handler BEGIN\n");
$SERVERCONFIG = "";
error_reporting(E_ALL ^ E_NOTICE);
if ($_POST['test']=='1')
{
  error_reporting(E_ALL);
  $SERVERCONFIG = "beta";
  $dest = "beta.ikegger.com";
  $testing = true;
  fclose($fout);
  $fout = fopen('php://output', 'w');
  fwrite($fout,"<pre>BEGIN SMS HANDLER TEST\n\n");
}
else if (strtolower(substr($_POST['raw_message'],0,2)) == "b ")
{
  $SERVERCONFIG = "beta";
  $_POST['raw_message'] = trim(substr($_POST['raw_message'],2));
  $dest = "beta.ikegger.com";
}
else
{
  $SERVERCONFIG = "live";
  $dest = "miio.com";
}
// USE THIS CODE FOR TESTING ON BETA ONLY
$SERVERCONFIG = "beta";
$dest = "beta.ikegger.com";
// **************************************

include "/miio_config/server_config.php";
define("EKEY","60da599d286873f9feb55bb95609c822");
$allowedServers = array('70.85.155.226','70.85.155.162','70.85.155.163','10.0.0.2','208.127.86.60','74.54.223.228','74.54.223.229','74.54.223.230','74.54.223.231','74.54.223.232','74.54.223.233','74.54.223.234','74.54.223.235','74.54.223.236','74.54.223.237','74.54.223.238','74.54.223.239','74.54.223.240','74.54.223.241','74.54.223.242','74.54.223.243','74.54.223.244','74.54.223.245','74.54.223.246','74.54.223.247','74.54.223.248','74.54.223.249','74.54.223.250','74.54.223.251','74.54.223.252','74.54.223.253');
date_default_timezone_set("America/Los_Angeles");
if (!$testing) set_error_handler("error_handler",(E_ALL^E_NOTICE^E_WARNING));

$now = date('H:i:s');
//$fout = fopen("/logs/sms_$today.log","a");
fwrite($fout,"$now\nStart message_handler\nPosting to $dest\n");
fwrite($fout,print_r($_SERVER,true));
//fwrite($fout,"POST variables: ".print_r($_POST,true)."\n");
$mobilenumber = $_POST['number'];
// we need international handling here!
if (substr($mobilenumber,0,1)=='1') $mobilenumber = substr($mobilenumber,1);
$message = $_POST['raw_message'];
// check sending IP - need correct IP addresses
if ($testing)
{
  fwrite($fout,"Message received from test script\n\n");
}
else if (!in_array($_SERVER['REMOTE_ADDR'],$allowedServers))
{
  fwrite($fout,"Message received from invalid server: ".$_SERVER['REMOTE_ADDR']);
  fwrite($fout, "\n------------------------------------------------------------\n\n");
  fclose($fout);
  die("Invalid Server");
}

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
include "/miio_config/Tags.php";
include "/miio_config/PostIndex.php";

$User = User::getByMobileNumber($mobilenumber);
if (!$User)
{
  // unrecognized number
  fwrite($fout,"Incoming message from non-member at $mobilenumber:\n$message\n");
  fwrite($fout,"$message\n");
}
else
{
  fwrite($fout,"Incoming message from member $User->username (ID: $User->id):\n");
  // message punctuation clean-up by provider
  // networks:
  // CANADA: LG_ALLNETS-MIIO-CA
  // US: VS_ALLNETS2US
  // UK: VS_ALLNETS2UK
  // AUSTRALIA
  if ($User->sms_provider == 34) // 34 is verizon
  {
    if ($_POST['network']=='VS_ALLNETS2US')
    {
      $message = str_replace("ยก","@",$message);
      $message = str_replace("ยง","_",$message);
    }
    else if ($_POST['network']=='VS_ALLNETS2UK')
    {
      $message = str_replace("ง","_",$message);
      $message = str_replace("|","@",$message);
      $message = str_replace("๖","|",$message);
    }
  }
  fwrite($fout,"$message\n");
  //fwrite($fout,"User info:\n" . print_r($User,true) . "\n");
  if ($User->sms_confirmed)
  {
    // post message
    if (strtolower(substr($message,0,2)) == 'f ') $tofriends = true;
    if (strtolower(substr($message,0,2)) == 'p ') $private = true;
    if (
          (strtolower(substr($message,0,2)) == 't ') ||
          (strtolower(substr($message,0,2)) == 'd ') ||
          (substr($message,0,1) == '@')
       )
    {
      $direct = true;
    }
    $post = array(
                    'source'=>'text',
                    'parent_id'=>0,
                    'system'=>false,
                    'alert'=>false,
                    'type'=>'text'
                 );
    if ($tofriends)
    {
      fwrite($fout,"Sent to friends only\n");
      $post['text'] = substr($message,2);
      $post['sharing'] = 'friends';
      $post['sent_to'] = 0;
    }
    else if ($private || $direct)
    {
      if (substr($message,0,1) == '@') $message = substr($message,1);
      else $message = substr($message,2);
      $recipient = substr($message,0,strpos($message," "));
      $message = trim(substr($message,strlen($recipient)));
      $recipientid = User::getByName($recipient);
      fwrite($fout,"Private message to $recipient\n");
      if ($recipientid)
      {
        $post['text'] = $message;
        $post['sharing'] = ($private) ? 'private' : 'public';
        $post['sent_to'] = $recipientid->id;
        fwrite($fout,print_r($post,true));
      }
      else $invalid_recipient = true;
    }
    else
    {
      fwrite($fout,"Public message\n");
      $post['text'] = $message;
      $post['sent_to'] = 0;
      $post['sharing'] = 'public';
    }

    if ($invalid_recipient)
    {
      fwrite($fout,"Invalid recipient\n");
      $response = "Sorry, Miio does not recognize a user named '$recipient'";
    }
    else
    {
      fwrite($fout,"Saving...");
      Post::save($User->id,$post);
      fwrite($fout,"Message saved\n");
      $response = "Your message has been posted to Miio";
      $posted = true;
    }
  }
  else
  {
    // check for confirmation code
    fwrite($fout,"Unconfirmed member\n");
    fwrite($fout,"User sent: '$message', confirmation code is '$User->sms_confirmation'\n");
  }

  // send response
  if (!$testing && (!$posted || $User->sms_confirm_post))
  {
    $email = GetSMSEmail($User);
    $headers = "From: Miio\n";
    $headers .= "Priority: normal";
    $subject = "";
    //mail($email, $subject, $response, $headers, "-f noreply@".SMS_EMAIL_HOST);
    mail($email, $subject, $response, $headers, "-f sms_notify@".SMS_EMAIL_HOST);
    fwrite($fout,"Sent '$response' to $email\n");
  }
}
fwrite($fout, "------------------------------------------------------------\n\n");
fclose($fout);
chmod("/logs/sms_$today.log",0666);

if($testing) echo "<hr><p><a href='sms_test.php'>Return to test form</a></p>";
else echo "OK";

function error_handler($errno,$errstr,$errfile,$errline)
{
  if ($errno == 8) return;
  global $today, $now;
  $ferr = fopen("/logs/sms_$today.err","a");
  fwrite($ferr,"$now:  [$errno] $errstr in $errfile at $errline\n\n");
  fclose($ferr);
  chmod("/logs/sms_$today.err",0666);
}

?>