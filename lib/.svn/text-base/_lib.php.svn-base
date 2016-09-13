<?php
// general purpose library functions

function PageRender($controller,$page="",$params="",$get="")
{
  global $SHOW_HTML, $CONTROLLER, $PAGE, $PARAMS, $GET;
  $SHOW_HTML = true;
  $CONTROLLER = $controller;
  $PAGE = $page;
  $PARAMS = $params;
  $GET = $get;
  include BASE."/views/$controller/$page.php";
}

function Render($controller,$page,$params="")
{
  global $PARAMS;
  if ($params!="") $PARAMS = $params;
  include BASE."/views/$controller/$page.php";
}

// misc string functions

function str_random_code($length=10,$charset='lc_only')
{
  $char = array
  (
    'lc_only'   => "abcdefghijklmnopqrstuvwxyz",
    'uc_only'   => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
    'ltr_only'  => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",
    'lc_an'     => "abcdefghijklmnopqrstuvwxyz1234567890",
    'uc_an'     => "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890",
    'mixed_an'  => "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ"
    //'all'       => "abcdefghijklmnopqrstuvwxyz1234567890!@#$ABCDEFGHIJKLMNOPQRSTUVWXYZ"
  );

  $chars = $char[$charset];
  $string = "";
  while(strlen($string)<$length)
  {
    $r = mt_rand(0,strlen($chars)-1);
    $string .= substr($chars,$r,1);
  }
  return $string;
}

function str_to_words($str)
{
  $words = array();
  $wds = preg_split('/[^\w\']/',$str,null,PREG_SPLIT_NO_EMPTY);
  foreach ($wds as $word)
  {
    $word = trim($word);
    if ($word!="" && !in_array(strtolower($word),Options::$stopwords)) $words[] = $word;
  }
  return $words;
}


function log_write($message)
{
  global $Log;
  if (!isset($Log)) $Log = new Log();
  $Log->write($message);
}

function ValidID($id)
{
  return !(preg_match('/[^a-p]/',$id));
}

function cache_add($key,$val)
{
  global $Cache;
  return $Cache->add($key,$val);
}

function cache_set($key,$val)
{
  global $Cache;
  return $Cache->set($key,$val);
}

function cache_get($key)
{
  global $Cache;
  return $Cache->get($key);
}

function cache_replace($key,$val)
{
  global $Cache;
  return $Cache->replace($key,$val);
}

function cache_delete($key)
{
  global $Cache;
  return $Cache->delete($key);
}

/************************************************************************************************************/

function undq($string)
{
  $string = str_replace('"','&DQ',$string);
  return $string;
}

function Hour24to12($hr)
{
  $resp = array();
  if (!is_numeric($hr)) return false;
  if ($hr==0) { $resp['hour']=12; $resp['ampm']='am'; }
  else if ($hr==12) { $resp['hour']=12; $resp['ampm']='pm'; }
  else if ($hr<12) { $resp['hour']=$hr; $resp['ampm']='am'; }
  else if ($hr<24) { $resp['hour']=$hr-12; $resp['ampm']='pm'; }
  else return false;
  return $resp;
}

function BuildLocationString($place,$address,$city,$state,$country)
{
  $location = "";
  if ($city!="")
  {
    $location .= $city;
    $comma = true;
  }
  if ($state!="")
  {
    if ($comma)
    {
      $location .= ", ";
      $comma = false;
    }
    $location .= $state;
    $comma = true;
  }
  if ($country!="")
  {
    if ($comma)
    {
      $location .= ", ";
      $comma = false;
    }
    $location .= $country;
    $comma = true;
  }
  if ($address!="")
  {
    if ($comma) $location = $address . ", " . $location;
    else $location = $address;
    $hasaddress = true;
  }
  if ($place!="")
  {
    if ($hasaddress) $location = $place . ", " . $location;
    else if ($comma) $location = $place . ", in " . $location;
    else $location = $place;
  }
  return $location;
}

function BuildUntilString($time)
{
  $dt = getdate(strtotime($time));
  $now = getdate();
  $until = "";
  if ($dt['year']==$now['year'] && $dt['month']==$now['month'] && $dt['mday']==$now['mday'])
  {
    $hr = Hour24to12($dt['hours']);
    $min = ($dt['minutes']<10) ? '0'.$dt['minutes'] : $dt['minutes'];
    $until = $hr['hour'] . ":$min ".$hr['ampm'];
  }
  else
  {
    $until = $dt['mday'] . " " . $dt['month'] . ", " . $dt['year'];
  }
  return $until;
}

function GetMessageUpdateStatus($Messages,$lastcheck)
{
  global $AVATAR_URL, $CONTROLLER, $User, $Profile, $MESSAGE_FILTER;
  include_once ('views/partials/message.php');

  $updates = array();
  foreach ($Messages as $id)
  {
    $msg = Post::get($id);
    if ($msg['updated'] >= $lastcheck-1)
    {
      $update = array();
      $update['id'] = $id;
      $update['viewerid'] = $User->id;
      if ($msg['parent_id']>0) $update['isreply'] = 1;
      else
      {
        $update['replies'] = array();
        $update['all_replies'] = array();
        $update['newreplies'] = 0;
        $update['totalreplies'] = count($msg['replies']);
        $update['showreplies'] = ($msg['userid']==$User->id);
        foreach ($msg['replies'] as $rep)
        {
          $update['all_replies'][] = $rep['reply_id'];
          $r = Post::get($rep['reply_id']);
          if ($r['userid']==$User->id) $update['showreplies'] = true;

          $reply = array();
          $reply['id'] = $r['id'];

          $messagetext = htmlspecialchars($r['text'],ENT_COMPAT,null,false);
          $messagetext = preg_replace("/(www\.|http:\/\/|https:\/\/)([^\s]+)/", '<a href="$1$2" target="_blank">$1$2</a>', $messagetext);
          $messagetext = str_replace("href=\"www.","href=\"http://www.",$messagetext);
          $reply['text'] = $messagetext;
          $timestr = "Sent ";
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

          $reply['candelete'] = ($User->id==$r['userid'] || $User->id==$msg['userid']) ? 1 : 0;

          $update['replies'][] = $reply;
          $update['newreplies'] += 1;
        }

        $update['shares'] = array();
        $update['all_shares'] = array();
        $update['newshares'] = 0;
        $update['totalshares'] = count($msg['shares']);
        $update['showshares'] = ($msg['userid']==$User->id);
        foreach ($msg['shares'] as $sh)
        {
          $update['all_shares'][] = $sh['share_id'];
          $s = Post::get($sh['share_id']);
          if ($s['userid']==$User->id) $update['showshares'] = true;

          $share = array();
          $share['id'] = $s['id'];
          if ($s['text']=="")
          {
            $messagetext = "";
          }
          else
          {
            $messagetext = htmlspecialchars($s['text'],ENT_COMPAT,null,false);
            $messagetext = preg_replace("/(www\.|http:\/\/|https:\/\/)([^\s]+)/", '<a href="$1$2" target="_blank">$1$2</a>', $messagetext);
            $messagetext = str_replace("href=\"www.","href=\"http://www.",$messagetext);
          }
          $share['text'] = $messagetext;
          $share['sharelink'] = $s['link'];
          $timestr = "";
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

          $share['candelete'] = ($User->id==$sh['userid'] || $User->id==$msg['userid']) ? 1 : 0;

          $update['shares'][] = $share;
          $update['newshares'] += 1;
        }
      }
      if ($msg['updated']==$msg['created_at'] || ($msg['type']=='rss' && count($msg['replies']==0) && count($msg['shares']==0)))
      {
        $update['new_post'] = true;
        $post = rendermessage($msg,$User,$Profile,$MESSAGE_FILTER,$CONTROLLER,true);
        $update['scrolled'] = $post['scrolled'];
        $update['html'] = $post['html'];
      }
      $updates[] = $update;
    }
  }
  return $updates;
}

function RenderMessagesForUpdate($Messages,$top)
{
  global $AVATAR_URL, $CONTROLLER, $User, $Profile, $MESSAGE_FILTER;
  include_once ('views/partials/message.php');

  $updates = array();
  foreach ($Messages as $id)
  {
    $msg = Post::get($id);
    $update = array();
    $update['id'] = $id;
    $update['viewerid'] = $User->id;
    $update['new_post'] = true;
    $post = rendermessage($msg,$User,$Profile,$MESSAGE_FILTER,$CONTROLLER,true);
    $update['scrolled'] = $post['scrolled'];
    $update['html'] = $post['html'];
    $updates[] = $update;
  }
  return $updates;
}

function GetNextGroupOfUsers($UserList,$last)
{
  global $User, $Profile, $USER_FILTER;
  include_once ('views/partials/user.php');
  $response = array();
  $updates = array();
  $count = 0;
  $isfiltered = ($USER_FILTER != "");
  $top = $last + USERS_PER_PAGE;
  if ($top > count($UserList))
  {
    $top = count($UserList);
    $response['eol'] = 1;
  }
  else $response['eol'] = 0;
  for ($u=$last;$u<$top;$u++)
  {
    if (is_array($UserList[$u]))
    {
      if ($UserList[$u]['group_id']) $user = User::get($UserList[$u]['group_id']);
      else if ($UserList[$u]['id']) $user = User::get($UserList[$u]['id']);
    }
    else $user = User::get($UserList[$u]);

    $updates[] = renderuser($user,$isfiltered,$USER_FILTER);
  }
  $response['users'] = $updates;
  $response['lastuser'] = $top;
  return $response;
}

function HisHer($gender)
{
  if ($gender=='f') return "her";
  else if ($gender=='m') return "his";
  else return "their";
}

function Match($tomatch,$filter,$filterlength=NULL)
{
  if (!$filterlength) $filterlength = strlen($filter);
  if (is_array($tomatch))
  {
    foreach ($tomatch as $r)
    {
      $To = User::get($r);
      if (strtolower(substr($To->username,0,$filterlength))==$filter)
      {
        return true;
      }
    }
  }
  else
  {
    if (substr($tomatch,0,$filterlength)==$filter) return true;
  }
  return false;
}

function MatchFilter($sentto,$sender,$recipient,$postid,$MESSAGE_FILTER)
{
  if (strtolower(substr($sender,0,strlen($MESSAGE_FILTER)))==$MESSAGE_FILTER) return true;
  else if ($sentto>0 && strtolower(substr($recipient,0,strlen($MESSAGE_FILTER)))==$MESSAGE_FILTER)
  {
    return true;
  }
  else if ($sentto<0)
  {
    $recipients = Post::getRecipients($postid);
    foreach ($recipients as $r)
    {
      $To = User::get($r);
      if (strtolower(substr($To->username,0,strlen($MESSAGE_FILTER)))==$MESSAGE_FILTER)
      {
        return true;
      }
    }
  }
  return false;
}

/******************************************************************************/
/****************************** DEBUG  FUNCTIONS ******************************/

function starttime()
{
  global $stime;
  $stime = microtime(true);
}

function endtime($n)
{
  global $htime,$stime;
  $etime = microtime(true);
  $time = round($etime-$stime,4);
  if ($time>.1) $htime .= "<span style='color:red'>$n: $time</span><br>";
  else $htime .= "$n: $time<br>";
}
function showtime()
{
  global $htime;
  echo "<div style='position:absolute;top:80px;right:3px;background-color:white;border:solid gray 2px;padding:10px 20px;font-weight: bold'>$htime</div>";
}

?>