#!/usr/bin/php
<?

set_error_handler(errorhandler);
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
define("MINUTE_IN_SEC",60);
define("HOUR_IN_SEC",60*60);
define("DAY_IN_SEC",60*60*24);
define("IMPORT_POST_SIZE", 120);
define("MAX_FEED_COUNT", 5);
define("THUMB","http://thumbs.miio.com/?url=");
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

include "/miio_config/lib.php";
include "/miio_config/Post.php";
include "/miio_config/User.php";
include "/miio_config/Tags.php";
include "/miio_config/PostIndex.php";
include "/miio_config/Feed.php";

$RSS = new SimplePie();

function checkFeeds($userid,$url,$lastcheck)
{
  global $RSS,$file;
  fwrite($file,date('H:i:s')." - Initializing RSS reader\n");
  $RSS->set_feed_url($url);
  $RSS->enable_cache(false);
  //$RSS->strip_htmltags(array('a', 'div', 'p', 'b', 'h1', 'h2' , 'h3', 'h4', 'h5', 'h6', 'base', 'blink', 'body', 'doctype', 'embed', 'font', 'form', 'frame', 'frameset', 'html', 'iframe', 'img', 'input', 'marquee', 'meta', 'noscript', 'object', 'param', 'script', 'style'));
  $RSS->init();
  $RSS->handle_content_type();
  $feeds = $RSS->get_items(0, MAX_FEED_COUNT);
  $dt = processFeeds($userid, $feeds, $lastcheck);
  if ($dt == "") return false;//"Nothing processed";
  else return updateFeed($userid, $url, $dt);
}

function processFeeds($userid, $feeds, $dt=NULL)
{
  global $file;
  fwrite($file,date('H:i:s')." - start processFeeds\n");
  $rp = array();
  foreach ($feeds as $item)
  {
    if (strtotime($item->get_date()) > $dt)
    {
      fwrite($file,date('H:i:s')." - Title: ".$item->get_title()."\nPosted: ".$item->get_date()."\n");
      $rp['userid'] = $userid;
      $rp['foreign_sender'] = $item->get_feed()->get_title();
      $rp['source'] = "rss";
      $rp['type'] = "rss";
      $rp['subsource'] = $item->get_feed()->get_link();
      $rp['link'] = $item->get_permalink();
      $rp['linktype'] = "link";
      $rp['foreign_image'] = $item->get_feed()->get_favicon();
      //$rp['created_at'] = strtotime($item->get_date());
      $rp['sharing'] = 'public';
      fwrite($file,"error tracking: 1 ");
      $lat = $item->get_latitude();
      $lon = $item->get_longitude();
      fwrite($file,"2 ");
      $tags = "";
      if ($enclosure = $item->get_enclosure())
      {
        foreach($enclosure->get_keywords() as $keyword) $tags .= "$keyword ";
      }
fwrite($file,"3 ");
      $titleCount = strlen($item->get_title());
fwrite($file,"4 ");
      $title = $item->get_title();
fwrite($file,"5 ");
      $txtsize = IMPORT_POST_SIZE - $titleCount - 7;
      $desc = $item->get_description();
fwrite($file,"6 ");
      $shortdesc = truncate($desc, $txtsize);
      $postbody = $title. " - " .$shortdesc;
      //$rp['text'] = html_entity_decode($postbody);
      $rp['text'] = strip_tags($postbody);
fwrite($file,"7 ");
      //echo "Getting thumbnail from ".THUMB.$rp['link']."...";
      $img = getimagesize(THUMB.$rp['link']);
fwrite($file,"8 \n".print_r($rp,true));
      //echo "got it\nSaving...\n";
      Post::save($userid, $rp,$tags);
fwrite($file,"\n9\nsqlerror: ".mysql_error());
      $dt = strtotime($item->get_date());
      //echo "----------\n";
    }

  }
  fwrite($file,date('H:i:s')." - done processFeeds\n");
  return $dt;
}

function updateFeed($userid, $url, $dt)
{
  global $Cache,$file;
  fwrite($file,date('H:i:s')." - begin updateFeed\n");
  $cacheid = 'User_'.$userid;
  $conn = User::connectToShard($userid,true);
  $sql = "UPDATE user_feeds SET last_dt=$dt WHERE userid=$userid AND url='$url'";
  $conn->rawquery($sql);
  $Cache->delete($cacheid);
  fwrite($file,date('H:i:s')." - end updateFeed\n");
  return "updated";
}

function truncate($str, $length=10, $trailing='...')
{
  $length-=strlen($trailing);
  if (strlen($str) > $length) return substr($str,0,$length).$trailing;
  else return $str;
}

function getFeeds()
{
  global $file;
  $today = date('Ymd');
  $logfile = "/logs/rss_$today.log";
  if (!file_exists($logfile))
  {
    touch($logfile);
    chmod($logfile,0660);
  }
  $file = fopen($logfile,"a");


  global $DBLIST;//,$fout;
  $sql = "SELECT * FROM user_feeds";
  foreach ($DBLIST['users'] as $db)
  {
    fwrite($file,date('H:i:s')." - Starting getFeeds loop\n");
    $conn = new DB();
    $conn->connect($db['slave'],$db['name']);
    $Feeds = $conn->query($sql);
    if (!$Feeds) fwrite($file,date('H:i:s').": - ERROR: ".mysql_error()."\n");
    else fwrite($file,date('H:i:s').": - got feeds list\n");
    fwrite($file,"--------------------------------------------------\n\n");
    foreach($Feeds as $Feed)
    {
      fwrite($file,date('H:i:s').": - Retreiving RSS feed for ".$Feed['userid'].":\n");
      fwrite($file,"            URL: ".$Feed['url']."\nLast check: ".date("j F Y, g:i a",$Feed['last_dt'])."\n");
      $f = checkFeeds($Feed['userid'],$Feed['url'],$Feed['last_dt']);
      if ($f) fwrite($file,date('H:i:s').": - Got feed\n");
      else fwrite($file,date('H:i:s').": - Got nothing\n");
      fwrite($file,"------------------------------\n\n");

    }
  }
  fclose($logfile);
  //fwrite($fout,'done');
  //sleep(60);
}

function errorhandler($errno, $errstr, $errfile, $errline)
{
  //if ($errno == 8) return;
  $today = date('Ymd');
  $now = date('H:i:s');
  $ferr = fopen("/logs/broadcast_$today.err","a");
  fwrite($ferr,"ERROR: ($now) - [$errno] $errstr in $errfile at $errline\n\n");
  fclose($ferr);
  chmod("/logs/broadcast_$today.err",0666);
}

//getFeeds();
while (true) getFeeds();

?>