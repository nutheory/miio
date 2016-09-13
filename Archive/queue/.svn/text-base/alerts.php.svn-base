#!/usr/bin/php
<?
define("MINUTE_IN_SEC",60);
define("HOUR_IN_SEC",60*60);
define("DAY_IN_SEC",60*60*24);
//$SERVERCONFIG="beta";
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

//set_error_handler(errorhandler);
date_default_timezone_set('America/Los_Angeles');

function errorhandler($errno, $errstr, $errfile, $errline)
{
  echo "$errstr at $errline\n";
}

$a = new Alerts();
$a->init();
while (true) $a->checkQueue();

?>
