<?
// Controller for non-specific AJAX calls

function upload_photo()
{
  // this uploads a TEMPORARY photo, stored using the session ID
  if (!LOGGEDIN)
  {
    $error = "Not logged in";
  }
  else
  {
    $sid = Session::Get('id');
    if ($_POST['profile_photo'])
    {
      $result = Image::save($sid,$_FILES['photo_file_source'],PROFILE_PAGE_PHOTO_WIDTH,PROFILE_PAGE_PHOTO_HEIGHT,'temp_profile');
    }
    else if ($_POST['is_attachment'])
    {
      $result = Image::save($sid,$_FILES['photo_file'],PHOTO_MAX_WIDTH,PHOTO_MAX_HEIGHT);
    }
    else if ($_POST['is_extra'])
    {
      $result = Image::save($sid,$_FILES['photo_extra_file'],PHOTO_MAX_WIDTH,PHOTO_MAX_HEIGHT);
    }
    else
    {
      $result = Image::save($sid,$_FILES['photo_file_source'],PHOTO_MAX_WIDTH,PHOTO_MAX_HEIGHT);
    }
    if ($result['filename'])
    {
      echo "ok";
      $filename = $result['filename'];
      $success = true;
    }
    else
    {
      echo "error";
      $error = $result['error'];
    }
  }
  if ($success)
  {
    echo "<script type='text/javascript'>";
    echo "top.".$_POST['js_url']." = '$filename';";
    if ($_POST['is_attachment']) echo "top.".$_POST['js_return']."(true);";
    else echo "top.".$_POST['js_return']."(false);";
    echo "</script>";
  }
  else
  {
    if (!$error) $error = "An unknown error occured";
    echo "<script type='text/javascript'>";
    echo "top.".$_POST['js_error']."('$error');";
    echo "</script>";
  }
}

function get_states()
{
  $states = Places::get_states($_POST['country']);
  $statelist = array();
  foreach ($states as $state)
  {
    $statelist[$state['region']] = $state['name'];
  }
  echo json_encode($statelist);
}

function get_cities()
{
  $cities = Places::get_cities($_POST['country'],$_POST['state']);
  $citylist = array();
  foreach ($cities as $city)
  {
    $citylist[$city['city']] = $city['city_name'];
  }
  echo json_encode($citylist);
}

function get_sms_code()
{
  $countrycode = Places::get_country_code($_POST['country']);
  $code = Places::get_sms_code($countrycode);
  if ($code) echo $code;
  else echo "1";
}

function check_name()
{
  if (User::exists('username',$_POST['name'])) echo "no";
  else if (Group::exists('groupname',$_POST['name'])) echo "no";
  else if (Validate::username($_POST['name'])) echo "ok";
  else echo "no";
}

function get_filetype()
{
  if (strpos($_POST['url'],'<embed ')!==false)
  {
    echo "embed";
  }
  else
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_POST['url']);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    $ok = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    if ($ok=='404')
    {
      echo "notfound";
      die;
    }
    $content_type = curl_getinfo( $ch, CURLINFO_CONTENT_TYPE );
    preg_match( '@([\w/+]+)(;\s+charset=(\S+))?@i', $content_type, $matches );
    if (isset($matches[1])) $mime = $matches[1];
    curl_close($ch);
    $type = explode('/',$mime);
    echo $type[0];
  }
}

function send_reply()
{
  global $User, $LOC;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $parent = Post::get($_POST['parent']);
  if (!$parent)
  {
    echo "deleted";
    die;
  }
  if ($parent->type==Enum::$message_type['reply'])
  {
    echo "Sorry, you cannot reply to a reply.";
    die;
  }
  if ($parent->sharing==Enum::$sharing['group'] && !$User->isMemberOf($parent->sent_to))
  {
    echo "You must be a member to reply in a group";
    die;
  }

  $recipient = array($parent->sent_by);

  $Post = Post::create();
  $Post->text           = $_POST['text'];
  $Post->sent_to        = $recipient;
  $Post->sent_by        = $User->id;
  $Post->source         = Enum::$source['web'];
  $Post->sharing        = $parent->sharing;
  $Post->type           = Enum::$message_type['reply'];
  $Post->original_type  = $parent->type;
  $Post->category       = $parent->category;
  $Post->original_id    = $parent->id;
  $ok = $Post->save();

  if ($ok) echo "ok_$Post->id";
  else echo "Unable to post reply";
}

function get_reply()
{
  global $User, $LOC, $AVATAR_URL;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $reply = Post::get($_POST['id']);
  if ($reply['parent_id']==0)
  {
    echo "not a reply";
    die;
  }
  $parent = Post::get($reply['parent_id']);
  $messagetext = htmlspecialchars($reply['text'],ENT_COMPAT,null,false);
  $messagetext = preg_replace("/(www\.|http:\/\/|https:\/\/)([^\s]+)/", '<a href="$1$2" target="_blank">$1$2</a>', $messagetext);
  $messagetext = str_replace("href=\"www.","href=\"http://www.",$messagetext);
  $reply['text'] = $messagetext;
  $timestr = "Sent ";
  $time = time() - floor($reply['created_at']);
  if ($time < MINUTE_IN_SEC) $timestr .= $time . " seconds ago";
  else if ($time < HOUR_IN_SEC) $timestr .= floor($time/MINUTE_IN_SEC) . " minutes ago";
  else if ($time < DAY_IN_SEC) $timestr .= floor($time/HOUR_IN_SEC) . " hours ago";
  else $timestr .= floor($time/DAY_IN_SEC) . " days ago";
  if ($reply['source'] == 'text') $timestr .= " by text";
  else $timestr .= " from the web";
  $reply['time_posted'] = $timestr;
  $ruser = User::get($reply['userid']);
  $reply['username'] = $ruser->username;
  if ($ruser->photo == "") $reply['avatar'] = $AVATAR_URL.'default.jpg';
  else $reply['avatar'] = $AVATAR_URL.$ruser->photo;
  $reply['candelete'] = ($User->id==$reply['userid'] || $User->id==$parent['userid']) ? 1 : 0;
  echo json_encode($reply);
}

function get_share()
{
  global $User, $LOC, $AVATAR_URL;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $share = Post::get($_POST['id']);
  if (!is_numeric($share['link']))
  {
    echo "not a share";
    die;
  }
  $parent = Post::get($share['link']);
  if ($share['text']!="")
  {
    $messagetext = htmlspecialchars($share['text'],ENT_COMPAT,null,false);
    $messagetext = preg_replace("/(www\.|http:\/\/|https:\/\/)([^\s]+)/", '<a href="$1$2" target="_blank">$1$2</a>', $messagetext);
    $messagetext = str_replace("href=\"www.","href=\"http://www.",$messagetext);
    $share['text'] = $messagetext;
  }
  $share['sharelink'] = $share['link'];
  $timestr = "Sent ";
  $time = time() - floor($share['created_at']);
  if ($time < MINUTE_IN_SEC) $timestr .= $time . " seconds ago";
  else if ($time < HOUR_IN_SEC) $timestr .= floor($time/MINUTE_IN_SEC) . " minutes ago";
  else if ($time < DAY_IN_SEC) $timestr .= floor($time/HOUR_IN_SEC) . " hours ago";
  else $timestr .= floor($time/DAY_IN_SEC) . " days ago";
  $share['time_posted'] = $timestr;
  if ($share['source'] == 'text') $share['msgsource'] = "by text";
  else $share['msgsource'] = "from the web";
  $ruser = User::get($share['userid']);
  $share['username'] = $ruser->username;
  if ($ruser->photo == "") $share['avatar'] = $AVATAR_URL.'default.jpg';
  else $share['avatar'] = $AVATAR_URL.$ruser->photo;
  $share['candelete'] = ($User->id==$share['userid'] || $User->id==$parent['userid']) ? 1 : 0;
  echo json_encode($share);
}

function delete_message()
{
  global $User, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  $post = Post::get($PARAMS);
  if ($post->sent_by=='a' && $post->sentTo()===$User->id) $candelete = true;
  else
  {
    $Sender = User::get($post->sent_by);
    $candelete = false; // delete from system
    //print_r($post);
    if ($User->id===$post->sent_by) $candelete = true;
    else if ($post->sharing==Enum::$sharing['public_group'] || $post->sharing==Enum::$sharing['private_group'])
    {
      if ($post->type==Enum::$message_type['reply']) $ref = Post::get($post->original_id);
      else $ref = $post;
      if ($User->isOwnerOf($ref->sent_to[0])) $candelete = true; // owner can delete anything
      else if ($User->isAdminOf($ref->sent_to[0]) && !$Sender->isAdmin($ref->sent_to[0])) $candelete = true; // admin can delete member posts
    }
    else if ($post->type==Enum::$message_type['reply'])
    {
      $parent = Post::get($post->original_id);
      if ($User->id===$parent->sent_by) $candelete = true;
    }
  }

  // got priviliges
  if ($candelete) $ok = $post->remove();
  else $ok = $User->removePostFromTimeline($post->id);
  if ($ok) echo "ok$PARAMS";
  else echo "Error: Unable to delete message - unknown error.";
}

function get_new_replies()
{
  global $User, $PARAMS, $AVATAR_URL;
  $parent = Post::get($PARAMS);
  if (!$parent || $parent['parent_id']>0)
  {
    echo "Invalid message ID";
    die;
  }

  $replies = array();
  foreach ($parent['replies'] as $rep)
  {
    $r = Post::get($rep['reply_id']);
    if ($r['userid']==$User->id) $update['showreplies'] = true;
    if ($r['updated'] > $lastcheck-1)
    {
      $reply = array();
      $reply['id'] = $r['id'];
      $reply['text'] = $r['text'];

      $timestr = " ";
      $time = time() - floor($r['created_at']);
      if ($time < MINUTE_IN_SEC) $timestr .= $time . " seconds ago";
      else if ($time < HOUR_IN_SEC) $timestr .= floor($time/MINUTE_IN_SEC) . " minutes ago";
      else if ($time < DAY_IN_SEC) $timestr .= floor($time/HOUR_IN_SEC) . " hours ago";
      else $timestr .= floor($time/DAY_IN_SEC) . " days ago";
      if ($r['source'] == 'text') $timestr .= " by text";
      else $timestr .= " from the web";
      $reply['time_posted'] = $timestr;

      $reply['userid'] = $r['userid'];
      $ruser = User::get($r['userid']);
      $reply['username'] = $ruser->username;
      if ($ruser->photo == "") $reply['avatar'] = $AVATAR_URL.'default.jpg';
      else $reply['avatar'] = $AVATAR_URL.$ruser->photo;
      $reply['avatar'] .= "?x=" . floor(time()/DAY_IN_SEC);

      $replies[] = $reply;
    }
  }
  $response = array();
  $response['viewerid'] = $User->id;
  $response['parentposter'] = $parent['userid'];
  $response['replies'] = $replies;
  $response['parentid'] = $PARAMS;
  $response['totalreplies'] = count($parent['replies']);
  echo json_encode($response);
}

function get_new_shares()
{
  global $User, $PARAMS, $AVATAR_URL;
  $parent = Post::get($PARAMS);
  if (!$parent || $parent['parent_id']>0)
  {
    echo "Invalid message ID";
    die;
  }

  $shares = array();
  foreach ($parent['shares'] as $sh)
  {
    $s = Post::get($sh['share_id']);
    if ($s['userid']==$User->id) $update['showshares'] = true;
    if ($s['updated'] > $lastcheck-1)
    {
      $share = array();
      $share['id'] = $s['id'];
      $share['text'] = $s['text'];

      $timestr = " ";
      $time = time() - floor($s['created_at']);
      if ($time < MINUTE_IN_SEC) $timestr .= $time . " seconds ago";
      else if ($time < HOUR_IN_SEC) $timestr .= floor($time/MINUTE_IN_SEC) . " minutes ago";
      else if ($time < DAY_IN_SEC) $timestr .= floor($time/HOUR_IN_SEC) . " hours ago";
      else $timestr .= floor($time/DAY_IN_SEC) . " days ago";
      $share['time_posted'] = $timestr;
      if ($s['source'] == 'text') $share['msgsource'] = "by text";
      else $share['msgsource'] = "from the web";
      $share['userid'] = $s['userid'];
      $ruser = User::get($s['userid']);
      $share['username'] = $ruser->username;
      if ($ruser->photo == "") $share['avatar'] = $AVATAR_URL.'default.jpg';
      else $share['avatar'] = $AVATAR_URL.$ruser->photo;
      $share['avatar'] .= "?x=" . floor(time()/DAY_IN_SEC);

      $shares[] = $share;
    }
  }
  $response = array();
  $response['viewerid'] = $User->id;
  $response['parentposter'] = $parent['userid'];
  $response['shares'] = $shares;
  $response['parentid'] = $PARAMS;
  $response['totalshares'] = count($parent['shares']);
  echo json_encode($response);
}

function save_message()
{
  global $User;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }

  if ($_POST['link'])
  {
    if ($_POST['islink']==0)
    {
      $filename = Image::saveFromTemp($_POST['link']);
      $photourl = LOC."file_storage/".$filename;
    }
    else
    {
      $photourl = $_POST['link'];
    }
    $linktype = $_POST['linktype'];
    if ($linktype=='image')
    {
      $size = getimagesize($photourl);
      $ht = $size[1];
      $wd = $size[0];
    }
    else
    {
      $ht = 0;
      $wd = 0;
    }
  }
  else $photourl = "";

  $recipients = array();
  $failed = array();
  if ($_POST['sendto'])
  {
    $users = explode(',',$_POST['sendto']);
    if (count($users)>MAX_RECIPIENTS)
    {
      echo "toomany";
      die;
    }
    foreach ($users as $name)
    {
      if ($name != "")
      {
        $u = User::getByName($name);
        if (!$u) $failed[] = $name;
        else $recipients[] = $u->id;
      }
    }
  }

  if (count($failed)>0) Notify::MessageFailed($User,$failed,count($recipients),$text);

  $Post = Post::create();
  $Post->text       = $_POST['text'];
  $Post->sent_to    = $recipients;
  $Post->sent_by    = $User->id;
  $Post->source     = Enum::$source['web'];
  $Post->sharing    = Enum::$sharing[$_POST['sharing']];
  $Post->type       = Enum::$message_type[$_POST['type']];
  $category         = $_POST['category'];
  if (is_numeric($category) && $category>0 && $category<count(Options::$category))
  {
    $Post->category   = Enum::$category[$category];
  }
  if ($_POST['country'] || $_POST['address'] || $_POST['location'])
  {
    $Post->location = array
    (
      'country'     => trim($_POST['country']),
      'region'      => trim($_POST['state']),
      'city'        => trim($_POST['city']),
      'address'     => trim($_POST['address']),
      'place_name'  => trim($_POST['location'])
    );
  }
  if ($_POST['tags'])
  {
    $Post->keywords = explode(' ',$_POST['tags']);
  }
  if ($photourl)
  {
    $Post->link = array
    (
      'type'    => Enum::$link_type[$linktype],
      'uri'     => $photourl,
      'height'  => $ht,
      'width'   => $wd
    );
  }
  $ok = $Post->save();
  if ($ok) echo "ok_$Post->id";
  else echo "Unable to post message";
}

function accept_follower()
{
  global $Profile, $User, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile->id)
  {
    echo "User not found";
    die;
  }
  if ($Profile->subscriptionRequestSent($User->id))
  {
    $Profile->cancelSubscriptionRequest($User->id);
    $ok = $Profile->subscribe($User->id);
    if ($ok)
    {
      Notify::FollowAccepted($User,$Profile);
      Notify::NewFollower($Profile,$User);
      echo "ok".$Profile->username;
    }
    else echo "Unknown error";
  }
  else
  {
    echo "gone";
  }
}

function decline_follower()
{
  global $Profile, $User, $PARAMS;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $Profile = User::get($PARAMS);
  if (!$Profile->id)
  {
    echo "User not found";
    die;
  }
  if ($Profile->subscriptionRequestSent($User->id))
  {
    $Profile->cancelSubscriptionRequest($User->id);
    Notify::FollowDeclined($User,$Profile);
    echo "ok".$Profile->username;
  }
  else
  {
    echo "gone";
  }
}

function send_share()
{
  global $User, $LOC;
  if (!LOGGEDIN)
  {
    echo "Not logged in";
    die;
  }
  $message = Post::get($_POST['message']);
  if (!$message)
  {
    echo "deleted";
    die;
  }
  if ($message['parent_id']>0)
  {
    echo "Sorry, you cannot share a reply.";
    die;
  }
  if ($message['isgroup'])
  {
    if (!$User->isMember($message['sent_to']))
    {
      echo "You must be a member to share a group message";
      die;
    }
    $group = User::get($message['sent_to']);
    if ($group->visibility=='private')
    {
      echo "You cannot share messages from a private group";
      die;
    }
  }
  if ($message['sharing'] != 'public')
  {
    echo "You may only share public messages";
    die;
  }

  $share = array(
                  "text"=>$_POST['text'],
                  "source"=>"web",
                  "sent_to"=>0,
                  "system"=>false,
                  "alert"=>false,
                  "sharing"=>'public',
                  "type"=>'share',
                  "link"=>$message['id']
                );
  $id = Post::save($User->id,$share,'',true);
  $now = time() - 1;

  if ($id)
  {
    if ($User->twitter_token && $User->twitter_push && $message["sharing"]=='public')
    {
      $saved = Post::get($id);
      $tweet = "Share: ".$LOC."_".$saved['code']." - ".$saved['text'];
      Twitter::send($User->id, $tweet);
    }
  }
  $msg = Post::get($message['id']);
  if ($id) echo "ok".$id;
  else echo "Unable to share";
}

function get_shares()
{
  global $User, $PARAMS, $AVATAR_URL;
  $message = Post::get($PARAMS);
  if (!$message)
  {
    echo "Invalid message ID";
    die;
  }

  $html = "";

  foreach ($message['shares'] as $sh)
  {
    $share = Post::get($sh['share_id']);
    $ssender = User::get($share['userid']);
    if ($ssender->photo == '') $avatar = $AVATAR_URL.'default.jpg';
    else $avatar = $AVATAR_URL.$ssender->photo;
    $avatar .= '?x=' . floor(time()/DAY_IN_SEC);

    $html .= "<div id='messagesharecontainer_".$share['id']."'>";
    $html .= "<table class='message inlinereply' id='messageshare_".$share['id']."'><tr><td class='tl'></td><td class='top' colspan=2></td><td class='tr'></td></tr>";
    $html .= "<tr><td class='left' rowspan=2></td><td class='avatar'><a href='members/profile/$ssender->id'><img src='$avatar' height=".MESSAGE_AVATAR_SIZE." width=".MESSAGE_AVATAR_SIZE." onmouseover='ImageHighlight(this,true)' onmouseout='ImageHighlight(this,false)'></a></td>";
    $html .= "<td class='message'><a href='members/profile/$ssender->id'>".$ssender->username."</a>: ";
    $html .= " <span class='messagetext'>Shared this</span></td><td class='right' rowspan=2></td></tr>";
    $html .= "<tr><td class='links' colspan=2>";

    $html .= "<span>";
    $time = time() - floor($share['created_at']);
    if ($time < MINUTE_IN_SEC) $html .= $time . ' seconds ago';
    else if ($time < HOUR_IN_SEC) $html .= floor($time/MINUTE_IN_SEC) . ' minutes ago';
    else if ($time < DAY_IN_SEC) $html .= floor($time/HOUR_IN_SEC) . ' hours ago';
    else $html .= floor($time/DAY_IN_SEC) . ' days ago';
    $html .= "</span>";

    if ($share['userid']==$User->id)
    {
      $html .= "Hey, this is you!";
    }
    else if (!$User->isSubscribed($share['userid']))
    {
      $html .= "<a href='#' id='subscribe_".$share['userid']."_".$share['id']."' onclick='return Messages.Subscribe(".$share['id'].",".$share['userid'].",\"$ssender->username\");'>Follow</a>";
    }

    $html .= "</td></tr>";
    $html .= "<tr><td class='bl'></td><td class='bottom' colspan=2></td><td class='br'></td></tr>";
    $html .= "</table>";
    $html .= "</div>";
  }
  echo $html;
}

function profile_update()
{
  global $PARAMS;
  $Profile = User::get($PARAMS);
  if (!$Profile)
  {
    echo "badid";
    die;
  }
  $profile = array (
                    'albums'=>count($Profile->albums),
                    'friends'=>count($Profile->friends),
                    'followers'=>count($Profile->followers),
                    'following'=>count($Profile->following),
                    'groups'=>count($Profile->public_groups)
                   );
  echo json_encode($profile);
}

function group_update()
{
  global $PARAMS;
  $Group = Group::get($PARAMS);
  if (!$Group)
  {
    echo "badid";
    die;
  }
  $group = array (
                  'albums'=>count($Group->albums),
                  'members'=>count($Group->group_members),
                  'requests'=>count($Group->requested_memberships)
                 );
  echo json_encode($group);
}

function user_update()
{
  global $User;
  include_once('user.php');
  $last_received = $_POST['last_received'];
  $last_rreceived = $_POST['last_rreceived'];
  $last_thread= $_POST['last_thread'];
  $last_notifications = $_POST['last_notifications'];
  $current = $_POST['current'];
  //$received = messagelist($received,true,true);
  $user = array (
                  'albums'=>count($User->albums),
                  'friends'=>count($User->friends),
                  'followers'=>count($User->followers),
                  'following'=>count($User->following),
                  'public'=>count($User->public_groups),
                  'private'=>count($User->private_groups)
                );
  if ($current != 'received') $user['received']=messagelist('received',true,true,$last_received);
  if ($current != 'rreceived') $user['rreceived']=messagelist('rreceived',true,true,$last_rreceived);
  if ($current != 'thread') $user['thread']=messagelist('thread',true,true,$last_thread);
  if ($current != 'notifications') $user['notifications']=messagelist('notifications',true,true,$last_notifications);
  echo json_encode($user);
}

?>