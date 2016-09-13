<?
class Twitter
{
  public $twit_def_img = "http://s.twimg.com/a/1252097501/images/default_profile_normal.png";

  function checkTweets($qty=TWITTER_INITIAL_PAGES)
  {
    global $User;
    $to = new TwitterHelper(TWITTER_KEY, TWITTER_SECRET, $User->twitter_token, $User->twitter_secret);
    $all_tweets = array();

    for ($i=1;$i<=$qty;$i++)
    {
      if ($User->twitter_last_tweet > 1) $tweets = $to->OAuthRequest('http://twitter.com/statuses/friends_timeline.json', array('count'=>TWITTER_COUNT_PER_PAGE, 'page'=>$i, 'since_id'=>$User->twitter_last_tweet), 'GET');
      else $tweets = $to->OAuthRequest('http://twitter.com/statuses/friends_timeline.json', array('count'=>TWITTER_INITIAL_PER_PAGE, 'page'=>$i), 'GET');
      $tw = json_decode($tweets, true);

      foreach($tw as $t){ array_push($all_tweets, $t); }
      if (count($all_tweets) < ($i*TWITTER_INITIAL_PER_PAGE)) $i=($qty+1);
    }

    $last_tw = $all_tweets[0]['id'] + 1;
    $ret = Twitter::tweetFactory($all_tweets);
    if ($ret) $ok = Twitter::updateLast($last_tw, 'tweet');
    return $ok;
  }

  function tweetFactory($tweets)
  {
    global $User;
    // Decode tweets JSON object in a hash
    // print_r($tweets);
    foreach ($tweets as $t)
    {
          // $t['userid'] = Twitter::linkToUser($t['user']['id']);
      if (strpos($t['source'], "NuTheory2") < 1) // change NuTheory2 to Miio for production
      {
        $t['subsource'] = $t['source'];

        $t['source'] = 'twitter';

        $t['system'] = 0;
        $t['alert'] = 0;
        $t['type'] = 'twitter';
        $t['link'] = "http://twitter.com/".$t['user']['screen_name']."/status/".$t['id'];
        $t['foreign_sender'] = $t['user']['screen_name'];
        $t['foreign_image'] = $t['user']['profile_image_url'];

        $t['link_type'] = 'link';
        $t['created_at'] = strtotime($t['created_at']);

        if ($t['user']['protected'] == true) $t['sharing'] = 'private';
        else $t['sharing'] = 'public';

        if ($t['in_reply_to_status_id'] != '') $t['parent_id'] = Twitter::linkToPost($t['in_reply_to_status_id']);

        if ($t['recipient']) // direct message check
        {
          if ($t['recipient'] == $User->twitter_id)
          {
            $t['foreign_type'] = 'direct_receive';
            $t['sharing'] = 'private';
            $t['sent_to'] = $User->id;
          }
          elseif ($t['sender'] == $User->twitter_id)
          {
            $t['foreign_type'] = 'direct_sent';
            $t['sharing'] = 'private';
          }
        }
        else
        {
          $t['sharing'] = 'public';
          $t['sent_to'] = 0;

          if ($t['user']['id'] == $User->twitter_id)
          {
            if(strpos($t['text'], "@".$t['user']['screen_name']))
            {
              if(strpos($t['text'], "@".$t['user']['screen_name']) == 0 || $t['in_reply_to_status_id'] != '')
              {
                $t['parent_id'] = Twitter::linkToPost($t['recipient_id']);
                $t['foreign_type'] = 'owner_reply';
              }
              else $t['foreign_type'] = 'owner_mention';
            }
            else $t['foreign_type'] = 'owner_status';
          }
          else
          {
            if(strpos($t['text'], "@".$t['user']['screen_name']))
            {
              if(strpos($t['text'], "@".$t['user']['screen_name']) == 0 || $t['in_reply_to_status_id'] != '')
              {
                $t['parent_id'] = Twitter::linkToPost($t['recipient_id']);
                $t['foreign_type'] = 'reply';
              }
              else $t['foreign_type'] = 'mention';
            }
            else $t['foreign_type'] = 'status';
          }
        }
        if ($t['user']['protected'] == true) $t['sharing'] = 'private';
        else $t['sharing'] = 'public';

        $t['foreign_id'] = $t['id'];
        Post::save($User->id, $t);
      } //end of skip miio exported tweet
    } //end foreach imported tweet
    return true;
  }


  function send($tId, $tweet, $fs='n', $fid=0)
  {
    global $User;
    $to = new TwitterHelper(TWITTER_KEY, TWITTER_SECRET, $User->twitter_token, $User->twitter_secret);
    if ($fid==0) $update = $to->OAuthRequest('http://twitter.com/statuses/update.json', array('status'=>$tweet), 'POST');
    // else
    // {
    //  if (preg_match("/@".$fs."/i", $tweet))
    //  {
    //    $update = $to->OAuthRequest('http://twitter.com/statuses/update.json', array('status'=>$tweet, 'in_reply_to_status_id'=>$fid), 'POST');
    //  }
    //  else
    //  {
    //    $tweet = "@".$fs." ".$tweet;
    //    $update = $to->OAuthRequest('http://twitter.com/statuses/update.json', array('status'=>$tweet, 'in_reply_to_status_id'=>$fid), 'POST');
    //  }
    // }
    $result = json_decode($update, true);
    if ($result[id]) return true;
  }

      function linkToUser($tId)
      {
        global $DBLIST, $User;
        $sql = "SELECT id FROM users WHERE twitter_id=$tId";
        foreach ($DBLIST['users'] as $db)
        {
          $conn = new DB();
          $conn->connect($db['slave'],$db['name']);
          $usr = $conn->query($sql);
          if ($usr) break;
        }
        if ($usr) return $usr['id'];
        else return $User->id;
      }

  function linkToPost($irId)
  {
    global $DBLIST;
    $sql = "SELECT parent_id FROM posts WHERE foreign_id=$irId";
    foreach ($DBLIST['posts'] as $db)
    {
      $conn = new DB();
      $conn->connect($db['slave'],$db['name']);
      $msg = $conn->query($sql);
      if ($msg) break;
    }
    if ($msg) return $msg['parent_id'];
    else return 0;
  }

  function updateTwitterToken($id, $tToken, $tSecret, $tId, $tSn)
  {echo "Update User settings from User object" return false;
    global $Cache;
    $conn = User::connectToShard($id,true);
    $tok = addslashes($tToken);
    $sec = addslashes($tSecret);
    $sql = "UPDATE users SET twitter_token='".($tok)."', twitter_secret='".($sec)."', twitter_id='".($tId)."', twitter_sn='".($tSn)."'  WHERE id=$id";
    $conn->rawquery($sql);
    $cacheid = 'User_'.$id;
    $Cache->delete($cacheid);
    return true;
  }


  function updateLast($last_id,$type)
  {echo "Update User settings from User object" return false;
    global $Cache, $User;

    $conn = User::connectToShard($User->id,true);
    if ($type == 'direct_message')
    {
      $sql = "UPDATE users SET twitter_last_dm=$last_id WHERE id=$User->id";
      $User->twitter_last_dm = $last_id;
    }
    else if($type == 'direct_message_sent')
    {
      $sql = "UPDATE users SET twitter_last_dms=$last_id WHERE id=$User->id";
      $User->twitter_last_dms = $last_id;
    }
    else
    {
      $sql = "UPDATE users SET twitter_last_tweet='".$last_id."' WHERE id=$User->id";
      $User->twitter_last_tweet = $last_id;
    }
    $conn->rawquery($sql);
    $cacheid = 'User_'.$id;
    $Cache->delete($cacheid);
    return true;
  }

}
?>