<?

class Feed
{
  private $htmltags = array ('a', 'div', 'p', 'b', 'h1', 'h2' , 'h3', 'h4', 'h5', 'h6', 'base', 'blink', 'body', 'doctype', 'img', 'embed', 'font', 'form', 'frame', 'frameset', 'html', 'iframe', 'input', 'marquee', 'meta', 'noscript', 'object', 'param', 'script', 'style');

  function checkFeeds()
  {
    // is this being used?
    echo "checkFeeds: is this being used?";
    global $User;

    $feed = new SimplePie();
    $feed->set_feed_url($User->rss['url']);
    $feed->enable_cache(false);
    $feed->strip_htmltags($htmltags);
    $feed->init();
    $feed->handle_content_type();

    $feeds = $feed->get_items(0, MAX_FEED_COUNT);

    $dt = Feed::processFeeds($feeds, $User->rss['last_dt']);
    if ($dt != "") $ok = Feed::updateFeed($User->id, $User->rss['url'], $dt);
    else $ok = "failed to process feed!";
  }

  function testFeed($url)
  {
    $feed = new SimplePie();
    $feed->set_feed_url($url);
    $feed->enable_cache(false);
    $feed->init();
    $feed->handle_content_type();

    $feeds = $feed->get_items(0, 1);

    if($feeds[0]->get_title() == '') return "fail";
    else return "ok";
  }

  function processFeeds($feeds, $dt=NULL)
  {
    global $User;
    $rp = array();
    foreach ($feeds as $item)
    {
      if (strtotime($item->get_date()) > $dt)
      {
        $rp['userid'] = $User->id;
        $rp['foreign_sender'] = $item->get_feed()->get_title();
        $rp['source'] = "rss";
        $rp['type'] = "rss";
        $rp['subsource'] = $item->get_feed()->get_link();
        $rp['link'] = $item->get_permalink();
        $rp['linktype'] = "link";
        $rp['foreign_image'] = $item->get_feed()->get_favicon();
        $rp['created_at'] = strtotime($item->get_date());
        $rp['sharing'] = 'public';

        $titleCount = strlen($item->get_title());
        $title = $item->get_title();
        $txtsize = IMPORT_POST_SIZE - $titleCount - 7;
        $desc = $item->get_description();
        $shortdesc = Feed::truncate($desc, $txtsize);
        $postbody = $title. " - " .$shortdesc;

        $rp['text'] = $postbody;
        Post::save($User->id, $rp);
      }
    }
    $newDt = time();
    return $newDt;
  }

  function updateFeed($userid, $url, $dt)
  {
    echo "USE USER OBJECT TO UPDATE USER OBJECT"; return false;
    global $User;
    $sql = "UPDATE user_feeds SET last_dt=$dt WHERE userid=$userid AND url='$url'";
    $conn->rawquery($sql);
      $Cache->delete($cacheid);
      return "updated";
  }

  function saveNewFeed($userid, $url)
  {
    $feed = new SimplePie();
    $feed->set_feed_url($url);
    $feed->enable_cache(false);
    $feed->strip_htmltags($htmltags);
    $feed->init();
    $feed->handle_content_type();

    $feeds = $feed->get_items(0, 1);
    $dt = strtotime($feeds[0]->get_date());
    if($feed->get_title() == '')
    {
      return "Feed URL failed in its attempt to validate";
    }
    else
    {
      global $User;
      if (isset($User->rss) && $User->rss['url'] != '') return "Already added RSS feed!";
      $User->saveRSS($feed->get_title(),$url,$feed->get_favicon(),$dt);
      // TODO: Save feed info to feed database?
    }
  }

  function destroyFeed()
  {
    echo "Use User::destroyRSS, not Feed::destroyFeed"; return false;

    global $Cache, $User;
    $cacheid = 'User_'.$User->id;
    $conn = User::connectToShard($userid,true);
    $sql = "DELETE FROM user_feeds WHERE userid=$userid AND url='$url'";
    $conn->rawquery($sql);
    $Cache->delete($cacheid);
    return "destroyed";
  }

  function truncate($str, $length=10, $trailing='...')
  {
    $length-=strlen($trailing);
    if (strlen($str) > $length) return substr($str,0,$length).$trailing;
    else $res = $str;
  }
}
?>