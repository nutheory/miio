#!/usr/bin/php
<?
define("MINUTE_IN_SEC",60);
define("HOUR_IN_SEC",60*60);
define("DAY_IN_SEC",60*60*24);
include "/miio_config/server_config.php";
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
$DB = new DB();

include "/miio_config/lib.php";
include "/miio_config/Post.php";
include "/miio_config/User.php";
include "/miio_config/Tags.php";

//set_error_handler(errorhandler);
date_default_timezone_set('America/Los_Angeles');
// include config, dispatch, processing, etc.



function checkQueue()
{
  global $Cache;
  $Messages = $Cache->get('Messages');
  if (!$Messages) $Messages = array();
  $id = array_shift($Messages);
  $Cache->replace('Messages',$Messages);
  Message::broadcast($id);
  
  /*
  $DB->connect(GENERAL_DB_MASTER,GENERAL_DB);
  $DB->rawquery($this->sql1);
  $res = $DB->query($this->sql2);
  if ($res[0]['MIN(message)']) $DB->rawquery($this->sql3.$res[0]['MIN(message)']);
  // unlock tables
  $DB->rawquery($this->sql4);
  $DB->close();
  if ($res[0]['MIN(message)']) $this->dobroadcast($res[0]['MIN(message)']);
  // pause for 10 milliseconds before moving on
  // this limits each messaging server to 100 queries per second
  usleep(10000);
  */
  
}


function errorhandler($errno, $errstr, $errfile, $errline)
{
  //echo "$errstr at $errline\n";
  if ($errno == 8) return;
  $today = date('Ymd');
  $now = date('H:i:s');
  $ferr = fopen("/logs/broadcast_$today.err","a");
  fwrite($ferr,"$now:  [$errno] $errstr in $errfile at $errline\n\n");
  fclose($ferr);
  chmod("/logs/broadcast_$today.err",0666);
}

$bc = new Broadcast();
$bc->init();
while (true) $bc->checkQueue();

?>
