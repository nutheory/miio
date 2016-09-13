<?
// Messages controller

/*********************************** PAGES  ***********************************/

function view()
{
  global $User, $Sender, $Profile, $PARAMS, $MESSAGE, $REPLY, $SHARE, $GET, $REPLY_PAGE;
  $msg = Post::get($PARAMS);
  $REPLY_PAGE = ($GET['page']) ? $GET['page'] : 1;

  if ($msg->type==Enum::$message_type['reply'])
  {
    $MESSAGE = Post::get($msg->original_id);
    $REPLY = $PARAMS;
  }
  else if ($msg->type==Enum::$message_type['share'])
  {
    $MESSAGE = Post::get($msg->original_id);
    $SHARE = $PARAMS;
  }
  else
  {
    $MESSAGE = $msg;
    $REPLY = $GET['reply'];
    $SHARE = $GET['share'];
  }
  if (!$MESSAGE)
  {
    Render('messages','notfound');
    return;
  }
  $Sender = User::get($MESSAGE->sent_by);
  if ($MESSAGE->sharing==Enum::$sharing['group']) $Group = Group::get($MESSAGE->sent_to);
  else $Profile = $Sender;
  Render('messages','view');
}


/************************************ AJAX ************************************/

function checkreplies()
{
  global  $User, $PARAMS;

  $lastcheck = $_POST['lastcheck'];
  $now = time();

  $post = Post::get($PARAMS);

  $replies = array();
  $shares = array();
  if ($post)
  {
    foreach ($post['replies'] as $rep)
    {
      $reply = Post::get($rep['reply_id']);
      $rsender = User::get($reply['userid']);
      if ($rsender->photo == '') $avatar = AVATAR_URL.'default.jpg';
      else $avatar = AVATAR_URL.$rsender->photo;
      $avatar .= '?x=' . floor(time()/DAY_IN_SEC);
      $html = "<a class='avatar' href='members/profile/$rsender->id'><img src='$avatar' height=".MESSAGE_AVATAR_SIZE." width=".MESSAGE_AVATAR_SIZE." onmouseover='ImageHighlight(this,true)' onmouseout='ImageHighlight(this,false)'></a>";
      $html .= "<div id='messagereply_".$reply['id']."'>";

      // message content
      $html .= "<p><span><a href='members/profile/$rsender->id'>".$rsender->username."</a> replied:</span></p>";

      if (!$reply['system'])
      {
        $messagetext = htmlspecialchars($reply['text']);
        $messagetext = preg_replace('/(www\.|http:\/\/|https:\/\/)([^\s]+)/', "<a href='$1$2' target='_blank'>$1$2</a>", $messagetext);
        $messagetext = str_replace('href=\'www.','href=\'http://www.',$messagetext);
        if ($tw) $messagetext = preg_replace('/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '$1@<a href="http://twitter.com/$2" target="_blank">$2</a>', $messagetext);
      }
      else $messagetext = $reply['text'];

      $html .= "<p>$messagetext</p>";

      $html .= "</div><div class='sent'>";

      $html .= "<span>";
      $time = time() - floor($reply['created_at']);
      if ($time < MINUTE_IN_SEC)
      {
        if ($time==1) $html .= '1 second ago';
        else $html .= $time . ' seconds ago';
      }
      else if ($time < HOUR_IN_SEC)
      {
        $m = floor($time/MINUTE_IN_SEC);
        if ($m==1) $html .= '1 minute ago';
        else $html .=  $m . ' minutes ago';
      }
      else if ($time < DAY_IN_SEC)
      {
        $h = floor($time/HOUR_IN_SEC);
        if ($h==1) $html .= '1 hour ago';
        else $html .=  $h . ' hours ago';
      }
      else
      {
        $d = floor($time/DAY_IN_SEC);
        if ($d==1) $html .= '1 day ago';
        else $html .=  $d . ' days ago';
      }
      if ($reply['system']) $html .=  " by the system";
      else if ($reply['source'] == 'text') $html .= " by text";
      else $html .= " from the web";
      $html .= "</span>";
      if
      (
        (
          $post['isgroup'] &&
          $User->isMember($post['sent_to']) &&
          (
            $post['userid']==$User->id ||
            $reply['userid']==$User->id ||
            $User->isAdmin($post['sent_to'])
          )
        )
        ||
        (
          !$post['isgroup'] &&
          (
            $post['userid']==$User->id ||
            $reply['userid']==$User->id
          )
        )
      )
      {
        $html .= "<a href='#' class='delete' onclick='return Message.Delete(this,".$reply['id'].");'><img src='images/delete.png' title='Delete' alt='delete'></a>";
      }

      $html .= "</div>";

      $Reply = array();
      $Reply['html'] = $html;
      $Reply['id'] = $reply['id'];
      $replies[] = $Reply;
    }

    foreach ($post['shares'] as $shr)
    {
      $share = Post::get($shr['share_id']);
      $rsender = User::get($share['userid']);

      if ($rsender->photo == '') $avatar = AVATAR_URL.'default.jpg';
      else $avatar = AVATAR_URL.$rsender->photo;
      $avatar .= '?x=' . floor(time()/DAY_IN_SEC);

      $html = "<a class='avatar' href='members/profile/$rsender->id'><img src='$avatar' height='".MESSAGE_AVATAR_SIZE."' width='".MESSAGE_AVATAR_SIZE."' onmouseover='ImageHighlight(this,true)' onmouseout='ImageHighlight(this,false)'></a>";

      $html .= "<div><p><span>";
      $html .= "<a href='members/profile/$rsender->id'>$rsender->username</a>";
      $html .= ($share['text']=='') ? " without " : " with ";
      $html .= "comment</span></p>";

      if ($share['text']!='')
      {
        $html .= "<p class='subtext'>";
        $messagetext = htmlspecialchars($share['text']);
        $messagetext = preg_replace('/(www\.|http:\/\/|https:\/\/)([^\s]+)/', "<a href='$1$2' target='_blank'>$1$2</a>", $messagetext);
        $messagetext = str_replace('href=\'www.','href=\'http://www.',$messagetext);
        $html .= $messagetext;
        $html .= "</p>";
      }

      $html .= "</div><div class='sent'>";

      $time = time() - floor($share['created_at']);
      if ($time < MINUTE_IN_SEC)
      {
        if ($time==1) $timestr='1 second ago';
        else $timestr=$time . ' seconds ago';
      }
      else if ($time < HOUR_IN_SEC)
      {
        $m = floor($time/MINUTE_IN_SEC);
        if ($m==1) $timestr='1 minute ago';
        else $timestr=$m . ' minutes ago';
      }
      else if ($time < DAY_IN_SEC)
      {
        $h = floor($time/HOUR_IN_SEC);
        if ($h==1) $timestr='1 hour ago';
        else $timestr=$h . ' hours ago';
      }
      else
      {
        $d = floor($time/DAY_IN_SEC);
        if ($d==1) $timestr='1 day ago';
        else $timestr=$d . ' days ago';
      }
      if ($share['source'] == 'text') $source=' by text';
      else $source=' from the web';

      $html .= "<span>Sent <span>$timestr</span> $source";
      if ($post['userid']==$User->id || $share['userid']==$User->id)
      {
        $html .= "<a href='#' class='delete'onclick='return Message.Delete(this,".$share['id'].",true);'><img src='images/delete.png' title='Delete' alt='delete'></a>";
      }
      $html .= "</span></div>";



      $Share = array();
      $Share['html'] = $html;
      $Share['id'] = $share['id'];
      $shares[] = $Share;
    }
  }
  if (count($replies)>0 || count($shares)>0)
  {
    $response = array();
    $response['lastcheck'] = $now;
    $response['replies'] = $replies;
    $response['shares'] = $shares;
    if ($_POST['mode']) $response['mode'] = $_POST['mode'];
    if ($_POST['init']) $response['init'] = $_POST['init'];
    echo json_encode($response);
  }
  else echo "ok_".$now;
}
?>