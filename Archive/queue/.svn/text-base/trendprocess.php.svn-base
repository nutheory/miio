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

include "../miio_config/lib.php";
include "../miio_config/PostIndex.php";
include "logToFile.php";

//set_error_handler(errorhandler);
date_default_timezone_set('America/Los_Angeles');


function errorhandler($errno, $errstr, $errfile, $errline)
{
  echo "$errstr at $errline\n";
}

$log = new logToFile("../file_temp/trend.log");
$tt = new Topics();
$tt->init();
$log->write(date("c")." Trends Initialized:");
$trend_today_1 = $Cache->get("trend_today_1");
$log->append_array($trend_today_1);
	
while (true) 
{	
	//update trends in cache and db every half min for now.
	sleep(30);	
	$tt->updateCurrentTrends();
	
	//logging
	$newTrends = $Cache->get("trend_today_1");	
	$log->append(date("c")." updated:");
	$log->append_array($newTrends);	
}

?>
