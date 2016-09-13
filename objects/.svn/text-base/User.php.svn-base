<?

class User
{
  protected static $_users = array();

  protected function __clone() {}

  public static function get($id)
  {
    // if user object defined, return it
    if (array_key_exists($id,self::$_users)) { return self::$_users['$id']; }
    if ($id!='0')
    {
      // does user exist?
      $exists = self::exists('user',$id);
      if (!$exists) { return false; }
    }
    //create and return User object
    return new self($id);
  }

  public static function getByName($username)
  {
    // check name index for userid
    $username = addslashes(trim($username));
    $userid = DB::getUnique('userid','username',"username='$username'");
    if ($userid) return self::get($userid);
    else return false;
  }

  public static function getByEmail($email)
  {
    $email = addslashes(trim($email));
    $userid = DB::getUnique('userid','email',"email='$email'");
    if ($userid) return self::get($userid);
    else return false;
  }

  public static function remember($remember)
  {
    $remember_key = addslashes(trim($remember));
    $userid = DB::getUnique('userid','remember',"remember='$remember'");
    if ($userid) return self::get($userid);
    else return false;
  }

  public static function login($username,$password)
  {
    $logstr = "LOGIN: '$username' -> ";
    $user = self::getByName($username);
    if ($user)
    {
      if (crypt($password,$user->password) == $user->password)
      {
        log_write($logstr."OK");
        return $user;
      }
      else log_write($logstr."password fail");
    }
    else log_write($logstr."username fail");
    return false;
  }

  public static function create()
  {
    // get new ID
    $id = DB::getID('user');
    // create new user object
    $user = new self($id,true);
    $user->created = microtime(true);
    $user->setDefaults();
    // write new User to User table
    $user->save();
    // return user object
    return $user;
  }

  protected function __construct($id,$new=false)
  {
    // register self with class
    self::$_users[$id]==$this;
    if ($new || $id=='0')
    {
      $this->id = $id;
      return;
    }

    // check cache first
    $user = cache_get('User_'.$id);

    if ($user)
    {
      foreach ($user as $key=>$value)
      {
        $this->$key = $value;
      }
      return;
    }
    else
    {
      $this->id = $id;
      $ok = $this->loadFromDB();
      if (!$ok) return false;
    }
  }

  private function loadFromDB()
  {
    // do not load null user - this should never happen
    if ($this->id=='0')
    {
      log_write("ERROR in User::loadFromDB: Can't load NULL user");
      return false;
    }

    $conn = new DB('user',$this->id);
    $user = $conn->get('user','users',"id='$this->id'");
    if (!$user)
    {
      log_write("ERROR in User::loadFromDB: unable to retreive user from database (using id='$this->id')");
      return false;
    }
    else
    {
      $usr = unserialize(stripslashes($user));
      foreach ($usr as $key=>$val) $this->$key=$val;
      cache_set('User_'.$this->id,$this);
      return true;
    }
  }


/******************************************************************************/
/*********************** BOOLEAN STATUS TEST  FUNCTIONS ***********************/
/******************************************************************************/

  function isFriendOf($userid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('people',"userid='$this->id' AND id='$userid' AND friend=1");
    return $res>0;
  }

  function isFollowing($userid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('people',"userid='$this->id' AND id='$userid' AND (following=1 OR friend=1)");
    return $res>0;
  }

  function isFollowedBy($userid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('people',"userid='$this->id' AND id='$userid' AND (follower=1 OR friend=1)");
    return $res>0;
  }

  function followRequested($userid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('people',"userid='$this->id' AND id='$userid' AND follow_request IS NOT NULL");
    return $res>0;
  }

  function isMemberOf($groupid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('groups',"userid='$this->id' AND groupid='$groupid' AND member=1");
    return $res>0;
  }

  function membershipRequested($groupid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('groups',"userid='$this->id' AND groupid='$groupid' AND membership_request IS NOT NULL");
    return $res>0;
  }

  function isAdminOf($groupid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('groups',"userid='$this->id' AND groupid='$groupid' AND admin=1");
    return $res>0;
  }

  function isOwnerOf($groupid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('groups',"userid='$this->id' AND groupid='$groupid' AND owner=1");
    return $res>0;
  }

  function hasOnMute($id,$isgroup=false)
  {
    $conn = new DB('userindex',null,0);
    if ($isgroup) $res = $conn->countRows('groups',"userid='$this->id' AND groupid='$id' AND muted=1");
    else $res = $conn->countRows('people',"userid='$this->id' AND id='$id' AND muted=1");
    return $res==1;
  }

  function hasSMSOn($id,$isgroup=false)
  {
    $conn = new DB('userindex',null,0);
    if ($isgroup) $res = $conn->countRows('groups',"userid='$this->id' AND groupid='$id' AND sms_on=1");
    else $res = $conn->countRows('people',"userid='$this->id' AND id='$id' AND sms_on=1");
    return $res==1;
  }

  function visibleTo($userid)
  {
    if ($this->visibility==Enum::$visibility['public'])
    {
      // TODO: need to check blocked status
      return true;
    }
    else if ($this->visibility==Enum::$visibility['private'] && $this->isFollowedBy($userid))
    {
      return true;
    }
    else return false;
  }

  function isBlockingUser($userid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('blocked',"userid='$this->id' AND blocked_group='$userid'");
    return $res>0;
  }

  function isBlockingGroup($userid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('blocked',"userid='$this->id' AND blocked_user='$userid'");
    return $res>0;
  }

  function isBlockedBy($userid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('blocked',"blocked_user='$this->id' AND userid='$userid'");
    return $res>0;
  }

  function hasReportedUser($userid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('reports',"reported_user='$userid' AND userid='$this->id'");
    return $res>0;
  }

  function hasReportedGroup($groupid)
  {
    $conn = new DB('userindex',null,0);
    $res = $conn->countRows('reports',"reported_group='$groupid' AND userid='$this->id'");
    return $res>0;
  }


/******************************************************************************/
/***************************** FRIEND AND  FOLLOW *****************************/
/******************************************************************************/

  private function updateFollowCounts($followed)
  {
    $this->number_of[Enum::$number_of['friends']] = count($this->getFriends(true));
    $this->number_of[Enum::$number_of['following']] = count($this->getFollowing(true));
    $this->number_of[Enum::$number_of['followers']] = count($this->getFollowers(true));
    $this->update(false);
    $Followed = user::get($followed);
    $Followed->number_of[Enum::$number_of['friends']] = count($Followed->getFriends(true));
    $Followed->number_of[Enum::$number_of['following']] = count($Followed->getFollowing(true));
    $Followed->number_of[Enum::$number_of['followers']] = count($Followed->getFollowers(true));
    $Followed->update(false);
  }

  function follow($followed)
  {
    $Followed = User::get($followed);
    if ($Followed)
    {
      if ($this->isFollowing($followed))
      {
        log_write("$this->username ($this->id) requested to follow already followed user $followed");
        return true;
      }
      if ($this->isFollowedBy($followed))
      {
        // make friend
        $conn = new DB('userindex',null,0);
        $sql = "UPDATE people SET friend=1,following=0,follower=0 WHERE userid='$this->id' AND id='$followed'";
        $conn->query($sql);
        $sql = "UPDATE people SET friend=1,following=0,follower=0 WHERE id='$this->id' AND userid='$followed'";
        $conn->query($sql);
      }
      else
      {
        $conn = new DB('userindex',null,0);
        $sql = "INSERT INTO people (userid,id,username,following,follow_settings) VALUES ('$this->id','$followed','$Followed->username',1,'".FOLLOW_DEFAULT."')";
        $conn->query($sql);
        $sql = "INSERT INTO people (userid,id,username,follower,follow_settings) VALUES ('$followed','$this->id','$this->username',1,'".FOLLOW_DEFAULT."')";
        $conn->query($sql);
      }
      $this->updateFollowCounts($followed);
      return true;
    }
    else return false;
  }

  function unfollow($followed)
  {
    if (!$this->isFollowing($followed))
    {
      log_write("$this->username ($this->id) requested to unfollow not followed user $followed");
      return true;
    }
    if ($this->isFollowedBy($followed))
    {
      // remove friend
      $conn = new DB('userindex',null,0);
      $sql = "UPDATE people SET friend=0,following=0,follower=1 WHERE userid='$this->id' AND id='$followed'";
      $conn->query($sql);
      $sql = "UPDATE people SET friend=0,following=1,follower=0 WHERE id='$this->id' AND userid='$followed'";
      $conn->query($sql);
    }
    else
    {
      $conn = new DB('userindex',null,0);
      $sql = "DELETE FROM people WHERE userid='$this->id' AND id='$followed'";
      $conn->query($sql);
      $sql = "DELETE FROM people WHERE id='$this->id' AND userid='$followed'";
      $conn->query($sql);
    }
    $this->updateFollowCounts($followed);
    return true;
  }

  function getFriends($refresh=false)
  {
    $cacheid = "friends_$this->id";
    if (!$refresh) $friends = cache_get($cacheid);
    if (!$friends)
    {
      $conn = new DB('userindex',null,0);
      $sql = "SELECT id FROM people WHERE userid='$this->id' AND friend=1";
      $res = $conn->query($sql);
      $friends = array();
      while ($r = mysql_fetch_assoc($res))
      {
        $friends[] = $r['id'];
      }
      cache_set($cacheid,$friends);
    }
    return $friends;
  }

  function getFollowing($refresh=false,$all=false)
  {
    $cacheid = "following_$this->id";
    if (!$refresh) $following = cache_get($cacheid);
    if (!$following)
    {
      $conn = new DB('userindex',null,0);
      $sql = "SELECT id FROM people WHERE userid='$this->id' AND";
      if ($all) $sql .= " (following=1 OR friend=1)";
      else $sql .= " following=1";
      $res = $conn->query($sql);
      $following = array();
      while ($r = mysql_fetch_assoc($res))
      {
        $following[] = $r['id'];
      }
      cache_set($cacheid,$following);
    }
    return $following;
  }

  function getFollowers($refresh=false)
  {
    $cacheid = "followers_$this->id";
    if (!$refresh) $followers = cache_get($cacheid);
    if (!$followers)
    {
      $conn = new DB('userindex',null,0);
      $sql = "SELECT id FROM people WHERE userid='$this->id' AND follower=1 AND friend=0";
      $res = $conn->query($sql);
      $followers = array();
      while ($r = mysql_fetch_assoc($res))
      {
        $followers[] = $r['id'];
      }
      cache_set($cacheid,$followers);
    }
    return $followers;
  }

  function getFollowSettings($followed)
  {
    $cacheid = "follow_".$this->id."_".$followed;
    $settings = cache_get($cacheid);
    if (!$settings)
    {
      $sql = "SELECT follow_settings FROM people WHERE userid='$this->id' AND id='$followed'";
      $conn = new DB('userindex',$this->id);
      $res = $conn->query($sql);
      if (!$res)
      {
        log_write("ERROR in User::getFollowSettings: Can't load settings for userid=$this->id and id=$followed");
        return false;
      }
      $row = mysql_fetch_assoc($res);
      $settings = json_decode($row['follow_settings'],true);
      cache_set($cacheid,$settings);
    }
    return $settings;
  }

  function updateFollowSettings($followed,$settings)
  {
    $muted = ($settings['mute']==1) ? 1 : 0;
    $sms_on = 0;
    $Preferences = array('dashboard'=>array(),'sms'=>array(),'email'=>array());
    foreach(Enum::$follow_settings as $setting)
    {
      $Preferences['dashboard'][$setting] = ($settings['dashboard'][$setting]==1) ? 1 : 0;
      if ($settings['sms'][$setting]==1)
      {
        $Preferences['sms'][$setting] = 1;
        $sms_on = 1;
      }
      $Preferences['email'][$setting] = ($settings['email'][$setting]==1) ? 1 : 0;
    }
    $Preferences['muted'] = $muted;
    $Preferences['sms_on'] = $sms_on;
    $preferences = json_encode($Preferences);
    $sql = "UPDATE people SET muted=$muted, sms_on=$sms_on, follow_settings='$preferences' WHERE userid='$this->id' AND id='$followed'";
    $conn = new DB('userindex',$this->id);
    $res = $conn->query($sql);
    if (!$res) return false;
    $cacheid = "follow_".$this->id."_".$followed;
    cache_set($cacheid,$Preferences);
    return true;
  }

  function block($id)
  {
    $conn = new DB('userindex',null,0);
    $sql = "INSERT INTO blocked (blocked,userid) VALUES ('$id','$this->id')";
    return $conn->query($sql);
  }

  function unblock($id)
  {
    $conn = new DB('userindex',null,0);
    $sql = "DELETE FROM blocked WHERE blocked='$id' AND userid='$this->id'";
    return $conn->query($sql);
  }

  function reportUser($id)
  {
    $conn = new DB('userindex',null,0);
    $sql = "INSERT INTO reports (reported_user,userid) VALUES ('$id','$this->id')";
    return $conn->query($sql);
  }

  function reportGroup($id)
  {
    $conn = new DB('userindex',null,0);
    $sql = "INSERT INTO reports (reported_group,userid) VALUES ('$id','$this->id')";
    return $conn->query($sql);
  }

/******************************************************************************/
/****************************** GROUP MEMBERSHIP ******************************/
/******************************************************************************/

  function joinGroup($group)
  {
    if ($this->isMemberOf($group))
    {
      log_write("$this->username ($this->id) tried to join already joined group $group");
      return true;
    }
    $Group = Group::get($group);
    if ($Group)
    {
      $private = ($Group->visibility==Enum::$visibility['private']) ? 1 : 0;
      $conn = new DB('userindex',null,0);
      $sql = "INSERT INTO groups (userid,groupid,groupname,private,member,member_settings) VALUES ('$this->id','$group','$Group->groupname',$private,1,'".MEMBERSHIP_DEFAULT."')";
      $conn->query($sql);
      $conn = new DB('groupindex',null,0);
      $sql = "INSERT INTO members (userid,groupid,username,private,member) VALUES ('$this->id','$group','$this->username',$private,1)";
      $conn->query($sql);
      $groups = $this->getAllGroups(true);
      $this->number_of[Enum::$number_of['public_groups']] = count($groups['public']);
      $this->number_of[Enum::$number_of['private_groups']] = count($groups['private']);
      $this->update(false);
      $Group->number_of[Enum::$number_of['members']] = count($Group->getMembers(true));
      $Group->update(false);
      return true;
    }
    else return false;
  }

  function leaveGroup($group)
  {
    if (!$this->isMemberOf($group))
    {
      log_write("$this->username ($this->id) tried to leave group $group and is not a member");
      return true;
    }
    $Group = Group::get($group);
    $private = ($Group->visibility==Enum::$visibility['private']) ? 1 : 0;
    $conn = new DB('userindex',null,0);
    $sql = "DELETE FROM groups WHERE userid='$this->id' AND groupid='$group'";
    $conn->query($sql);
    $conn = new DB('groupindex',null,0);
    $sql = "DELETE FROM members WHERE userid='$this->id' AND groupid='$group'";
    $conn->query($sql);
    $groups = $this->getAllGroups(true);
    $this->number_of[Enum::$number_of['public_groups']] = count($groups['public']);
    $this->number_of[Enum::$number_of['private_groups']] = count($groups['private']);
    $this->update(false);
    $Group->number_of[Enum::$number_of['members']] = count($Group->getMembers(true));
    $Group->update(false);
    return true;
  }

  function getAllGroups($refresh=false)
  {
    $cacheid = "groups_$this->id";
    if (!$refresh) $groups = cache_get($cacheid);
    if (!$groups)
    {
      $conn = new DB('userindex',null,0);
      $sql = "SELECT groupid,private FROM groups WHERE userid='$this->id' AND member=1";
      $res = $conn->query($sql);
      $groups = array('public'=>array(),'private'=>array());
      while ($r = mysql_fetch_assoc($res))
      {
        if ($r['private']) $groups['private'][] = $r['id'];
        else $groups['public'][] = $r['id'];
      }
      cache_set($cacheid,$groups);
    }
    return $groups;
  }

  function getMemberSettings($groupid)
  {
    $cacheid = "member_".$this->id."_".$groupid;
    $settings = cache_get($cacheid);
    if (!$settings)
    {
      $sql = "SELECT member_settings FROM groups WHERE userid='$this->id' AND groupid='$groupid'";
      $conn = new DB('userindex',$this->id);
      $res = $conn->query($sql);
      if (!$res)
      {
        log_write("ERROR in User::getFollowSettings: Can't load settings for userid=$this->id and groupid=$groupid");
        return false;
      }
      $row = mysql_fetch_assoc($res);
      $settings = json_decode($row['member_settings'],true);
      cache_set($cacheid,$settings);
    }
    return $settings;
  }

  function updateMemberSettings($groupid,$settings)
  {
    $muted = ($_POST['mute']==1) ? 1 : 0;
    $sms_on = 0;
    $Preferences = array('dashboard'=>array(),'sms'=>array(),'email'=>array());
    foreach(Enum::$member_settings as $setting)
    {
      $Preferences['dashboard'][$setting] = ($_POST['dashboard'][$setting]==1) ? 1 : 0;
      if ($_POST['sms'][$setting]==1)
      {
        $Preferences['sms'][$setting] = 1;
        $sms_on = 1;
      }
      $Preferences['email'][$setting] = ($_POST['email'][$setting]==1) ? 1 : 0;
    }
    $Preferences['muted'] = $muted;
    $Preferences['sms_on'] = $sms_on;
    $preferences = json_encode($Preferences);
    $sql = "UPDATE groups SET muted=$muted, sms_on=$sms_on, member_settings='$preferences' WHERE userid='$this->id' AND groupid='$groupid'";
    $conn = new DB('userindex',$this->id);
    $res = $conn->query($sql);
    if (!$res) return false;
    $cacheid = "member_".$this->id."_".$groupid;
    cache_set($cacheid,$Preferences);
    return true;
  }

/******************************************************************************/
/************************ ACCOUNT MANAGEMENT FUNCTIONS ************************/
/******************************************************************************/

/******************************************************************************/
/************************* ALBUM AND  PHOTO FUNCTIONS *************************/
/******************************************************************************/


/******************************************************************************/
/****************************** ALERTS FUNCTIONS ******************************/
/******************************************************************************/

  function saveAlert($vals)
  {
    $keyword    = addslashes(trim(substr($vals['keyword'],0,30)));
    $dashboard  = ($vals['dashboard']==1) ? 1 : 0;
    $email      = ($vals['email']==1) ? 1 : 0;
    $sms        = ($vals['sms']==1) ? 1 : 0;
    $text       = ($vals['text']==1) ? 1 : 0;
    $photo      = ($vals['photo']==1) ? 1 : 0;
    $video      = ($vals['video']==1) ? 1 : 0;
    $link       = ($vals['link']==1) ? 1 : 0;
    $review     = ($vals['review']==1) ? 1 : 0;
    $question   = ($vals['question']==1) ? 1 : 0;
    $location   = ($vals['location']==1) ? 1 : 0;
    $share      = ($vals['share']==1) ? 1 : 0;
    $rss        = ($vals['rss']==1) ? 1 : 0;
    $paused     = 0;
    $id         = DB::getID('alert');

    $conn = new DB('userindex',$this->id);
    $sql = "INSERT INTO alerts (keyword,alert_id,userid,dashboard,email,sms,text,photo,video,link,review,question,location,rss,share,paused) VALUES ('$keyword','$id','$this->id',$dashboard,$email,$sms,$text,$photo,$video,$link,$review,$question,$location,$rss,$share,$paused)";
    $ok = $conn->query($sql);
    if ($ok) cache_delete("alerts_".$this->id);
    return $ok;
  }

  function updateAlert($vals)
  {
    $id         = $vals['id'];
    $dashboard  = ($vals['dashboard']==1) ? 1 : 0;
    $email      = ($vals['email']==1) ? 1 : 0;
    $sms        = ($vals['sms']==1) ? 1 : 0;
    $text       = ($vals['text']==1) ? 1 : 0;
    $photo      = ($vals['photo']==1) ? 1 : 0;
    $video      = ($vals['video']==1) ? 1 : 0;
    $link       = ($vals['link']==1) ? 1 : 0;
    $review     = ($vals['review']==1) ? 1 : 0;
    $question   = ($vals['question']==1) ? 1 : 0;
    $location   = ($vals['location']==1) ? 1 : 0;
    $share      = ($vals['share']==1) ? 1 : 0;
    $rss        = ($vals['rss']==1) ? 1 : 0;

    $conn = new DB('userindex',$this->id);
    $sql = "UPDATE alerts SET dashboard=$dashboard,email=$email,sms=$sms,text=$text,photo=$photo,video=$video,link=$link,review=$review,question=$question,location=$location,rss=$rss,share=$share WHERE alert_id='$id'";
    $ok = $conn->query($sql);
    if ($ok) cache_delete("alerts_".$this->id);
    return $ok;
  }

  function pauseAlert($id,$pause)
  {
    $paused = ($pause) ? 1 : 0;
    $conn = new DB('userindex',$this->id);
    $sql = "UPDATE alerts SET paused=$paused WHERE alert_id='$id'";
    $ok = $conn->query($sql);
    if ($ok) cache_delete("alerts_".$this->id);
    return $ok;
  }

  function deleteAlert($id)
  {
    $conn = new DB('userindex',$this->id);
    $sql = "DELETE FROM alerts WHERE alert_id='$id'";
    $ok = $conn->query($sql);
    if ($ok) cache_delete("alerts_".$this->id);
    return $ok;
  }

  function getAlerts()
  {
    $cacheid = "alerts_".$this->id;
    $alerts = cache_get($cacheid);
    if (!$alerts)
    {
      $conn = new DB('userindex',$this->id);
      $sql = "SELECT * FROM alerts WHERE userid='$this->id'";
      $res = $conn->query($sql);
      $alerts = array();
      while ($r = mysql_fetch_assoc($res))
      {
        $r['keyword'] = stripslashes($r['keyword']);
        $alerts[] = $r;
      }
      cache_set($cacheid,$alerts);
    }
    return $alerts;
  }

/******************************************************************************/
/***************************** MESSAGE  FUNCTIONS *****************************/
/******************************************************************************/

/************************************ RSS  ************************************/

  function saveRSS($title,$url,$favicon,$dt)
  {
    // TODO: currently just a placeholder. need to get working
  }

  function destroyRSS()
  {
    unset($this->rss);
    $this->update('rss');
  }

/******************************************************************************/
/******************************* HTML FUNCTIONS *******************************/
/******************************************************************************/

  function getProfilePhoto()
  {
    if (!$this->photo || $this->photo == "") $photo = PROFILE_PHOTO_URL.'default.jpg';
    else $photo = PROFILE_PHOTO_URL.$this->photo;
    return $photo;
  }

  function getAvatar($full=true)
  {
    if (!$this->photo || $this->photo == "") $avatar = AVATAR_URL.'default.jpg';
    else $avatar = AVATAR_URL.$this->photo;
    if (!$full) return $avatar;

    $html = "<a class='avatar' href='".$this->getProfileLink(false)."'><img src='$avatar' height=".AVATAR_SIZE." width=".AVATAR_SIZE." onmouseover='ImageHighlight(this,true)' onmouseout='ImageHighlight(this,false)'></a>";
    return $html;
  }

  function getProfileLink($full=false)
  {
    if ($full) return "<a href='members/profile/$this->id'>$this->username</a>";
    else return "members/profile/$this->id";
  }

  function getAge()
  {
    if ($this->birthday && $this->birthday!='0000-00-00')
    {
      $now = explode('-',date('Y-m-d'));
      $bd = explode('-',$this->birthday);
      $age = $now[0] - $bd[0];
      if ($bd[1]>$now[1] || ($bd[1]==$now[1] && $bd[2]>$now[2])) $age-=1;
      return $age;
    }
    else return false;
  }

  function getLocation($home=true)
  {
    $str = "";
    if ($home) $location = $this->home_location;
    else $location = $this->current_location;
    if ($location)
    {
      if (!$home)
      {
        $str .= trim($location['place_name']);
        if ($location['address'])
        {
          if ($str != '') $str .= ', ';
          $str .= trim($location['address']);
        }
      }
      if ($location['city'])
      {
        if ($str != '') $str .= ', ';
        $str .= trim($location['city']);
      }
      if ($location['region'])
      {
        if ($str != '') $str .= ', ';
        $str .= trim($location['region']);
      }
      if ($location['country'])
      {
        if ($str != '') $str .= ', ';
        $str .= trim($location['country']);
      }
    }
    return $str;
  }

/******************************************************************************/
/******************************* DATA  HANDLING *******************************/
/******************************************************************************/

  function save()
  {
    $conn = new DB('user',$this->id);
    $ser = addslashes(serialize($this));
    $sql = "INSERT INTO users (id,user) VALUES ('$this->id','$ser')";
    $res = $conn->query($sql);
    if (!$res)
    {
      return false;
    }
    else
    {
      $x=cache_add('User_'.$this->id,$this);
      return true;
    }
  }

  function update($reindex=true)
  {
    $conn = new DB('user',$this->id);
    $ser = addslashes(serialize($this));
    $sql = "UPDATE users SET user='$ser' WHERE id='$this->id'";
    $res = $conn->query($sql);
    if (!$res)
    {
      return false;
    }
    else
    {
      cache_set('User_'.$this->id,$this);
      // update indices
      if ($reindex) $this->index();
      return true;
    }
  }

  function index()
  {
    $conn = new DB('userindex',$this->id);

    // TODO: break into smaller functions
    // TODO: test values to determine if they NEED updating

    $sql = "INSERT INTO username (username,userid) VALUES ('".addslashes($this->username)."','$this->id') ON DUPLICATE KEY UPDATE username='".addslashes($this->username)."', userid='$this->id'";
    $conn->query($sql);

    $sql = "INSERT INTO email (email,userid) VALUES ('".addslashes($this->email)."','$this->id') ON DUPLICATE KEY UPDATE email='".addslashes($this->email)."', userid='$this->id'";
    $conn->query($sql);

    $sql = "INSERT INTO created (created,userid) VALUES ('$this->created','$this->id') ON DUPLICATE KEY UPDATE created='$this->created', userid='$this->id'";
    $conn->query($sql);

    $sql = "INSERT INTO visibility (visibility,userid) VALUES ('$this->visibility','$this->id') ON DUPLICATE KEY UPDATE visibility='$this->visibility', userid='$this->id'";
    $conn->query($sql);

    if ($this->name)
    {
      $sql = "INSERT INTO name (name,show_name,userid) VALUES ('".addslashes($this->name)."','$this->show_name','$this->id') ON DUPLICATE KEY UPDATE name='".addslashes($this->name)."', show_name='$this->show_name', userid='$this->id'";
    }
    else
    {
      $sql = "DELETE FROM name WHERE userid='$this->id'";
    }
    $conn->query($sql);

    if ($this->website)
    {
      $sql = "INSERT INTO website (website,userid) VALUES ('".addslashes($this->website)."','$this->id') ON DUPLICATE KEY UPDATE website='".addslashes($this->website)."', userid='$this->id'";
    }
    else
    {
      $sql = "DELETE FROM website WHERE userid='$this->id'";
    }
    $conn->query($sql);

    if ($this->birthday)
    {
      $sql = "INSERT INTO birthday (birthday,userid) VALUES ('$this->birthday','$this->id') ON DUPLICATE KEY UPDATE birthday='$this->birthday', userid='$this->id'";
    }
    else
    {
      $sql = "DELETE FROM birthday WHERE userid='$this->id'";
    }
    $conn->query($sql);

    if ($this->gender)
    {
      $sql = "INSERT INTO gender (gender,userid) VALUES ('$this->gender','$this->id') ON DUPLICATE KEY UPDATE gender='$this->gender', userid='$this->id'";
    }
    else
    {
      $sql = "DELETE FROM gender WHERE userid='$this->id'";
    }
    $conn->query($sql);

    if ($this->ethnicity)
    {
      $sql = "INSERT INTO ethnicity (ethnicity,userid) VALUES ('$this->ethnicity','$this->id') ON DUPLICATE KEY UPDATE ethnicity='$this->ethnicity', userid='$this->id'";
    }
    else
    {
      $sql = "DELETE FROM ethnicity WHERE userid='$this->id'";
    }
    $conn->query($sql);

    if ($this->relationship)
    {
      $sql = "INSERT INTO relationship (relationship,userid) VALUES ('$this->relationship','$this->id') ON DUPLICATE KEY UPDATE relationship='$this->relationship', userid='$this->id'";
    }
    else
    {
      $sql = "DELETE FROM relationship WHERE userid='$this->id'";
    }
    $conn->query($sql);

    $sql = "DELETE FROM looking_for WHERE userid='$this->id'";
    $conn->query($sql);
    foreach ($this->looking_for as $opt=>$val)
    {
      if ($val)
      {
        $sql = "INSERT INTO looking_for (looking_for,userid) VALUES ('".Enum::$looking_for[$opt]."','$this->id')";
        $conn->query($sql);
      }
    }

    $sql = "DELETE FROM interested_in WHERE userid='$this->id'";
    $conn->query($sql);
    foreach (User::$interested_in as $interested_in)
    {
      if ($this->interested_in[$interested_in])
      {
        $sql = "INSERT INTO interested_in (interested_in,userid) VALUES ('".$this->interested_in[$interested_in]."','$this->id')";
        $conn->query($sql);
      }
    }

    if ($this->reset_code && $this->reset_expires)
    {
      $resettime = strtotime($this->reset_expires);
      $now = time();
      if ($resettime<$now)
      {
        // delete
        $sql = "DELETE FROM reset WHERE userid='$this->id'";
      }
      else
      {
        $sql = "INSERT INTO reset (reset_code,reset_expires,userid) VALUES ('$this->reset_code','$this->reset_expires','$this->id') ON DUPLICATE KEY UPDATE reset_code='$this->reset_code', reset_expires='$this->reset_expires', userid='$this->id'";
      }
    }
    else
    {
      $sql = "DELETE FROM reset WHERE userid='$this->id'";
    }
    $conn->query($sql);

    if ($this->is_suspended)
    {
      $sql = "INSERT INTO is_suspended (userid) VALUES ('$this->id') ON DUPLICATE KEY UPDATE userid=userid";
    }
    else
    {
      $sql = "DELETE FROM is_suspended WHERE userid='$this->id'";
    }

    if ($this->home_location && $this->home_location['country_code']!='')
    {
      $sql = "INSERT INTO home_location (country,region,city,userid) VALUES ('".$this->home_location['country_code']."','".$this->home_location['region_code']."','".$this->home_location['city_code']."','$this->userid') ON DUPLICATE KEY UPDATE country='".$this->home_location['country_code']."',region='".$this->home_location['region_code']."',city='".$this->home_location['city_code']."'";
    }
    else
    {
      $sql = "DELETE FROM home_location WHERE userid='$this->id'";
    }
    $conn->query($sql);

    if ($this->current_location)
    {
      $returntime = strtotime($this->current_location['from'])+$this->current_location['duration'];
      $now = time();
      if ($now > $returntime)
      {
        $sql = "DELETE FROM current_location WHERE userid='$this->id'";
      }
      else
      {
        $sql = "INSERT INTO current_location (country,region,city,address,place_name,from,duration,userid) VALUES ('".$this->current_location['country_code']."','".$this->current_location['region_code']."','".$this->current_location['city_code']."','".$this->current_location['address']."','".$this->current_location['pace_name']."','".$this->current_location['from']."','".$this->current_location['duration']."','$this->userid') ON DUPLICATE KEY UPDATE country='".$this->current_location['country_code']."',region='".$this->current_location['region_code']."',city='".$this->current_location['city_code']."',address='".$this->current_location['address']."',place_name='".$this->current_location['place_name']."',from='".$this->current_location['from']."',duration='".$this->current_location['duration']."'";
      }
    }
    else
    {
      $sql = "DELETE FROM current_location WHERE userid='$this->id'";
    }
    $conn->query($sql);

    // need a test for if it's been updated....
    $sql = "DELETE FROM description WHERE userid='$this->id'";
    $conn->query($sql);
    $words = str_to_words($this->description);
    foreach ($words as $word)
    {
      $sql = "INSERT INTO description (word,userid) VALUES ('".addslashes($word)."','$this->id')";
      $conn->query($sql);
    }

    $sql = "DELETE FROM keywords WHERE userid='$this->id'";
    $conn->query($sql);
    foreach ($this->keywords as $keyword)
    {
      $sql = "INSERT INTO keywords (keyword,userid) VALUES ('".addslashes($keyword)."','$this->id')";
      $conn->query($sql);
    }

    // done indexing... ( I hope!)
  }

  function rememberMe()
  {
    $remember = str_random_code(100,'mixed_an');
    $conn = new DB('userindex',$this->id);
    $sql = "INSERT INTO remember (remember,userid) VALUES ('$remember','$this->id') ON DUPLICATE KEY UPDATE remember='$remember', userid='$this->id'";
    if ($conn->query($sql)) return $remember;
    else return false;
  }

  function setDefaults()
  {
    $this->refresh_rate = 5;
    $this->featured_status = Enum::$featured_status['normal'];
    $this->visibility = Enum::$visibility['public'];
    $this->show_name = true;
    $this->notification_settings = User::$notification_settings;
    $this->message_settings = User::$message_settings;
  }

  function confirm()
  {
    unset ($this->confirmation_code);
    $this->is_confirmed = true;
    $this->update(false);
    return true;
  }

  function remove()
  {
    // remove from indices
    $conn = new DB('userindex',$this->id);
    $sql = "DELETE FROM birthday WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM created WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM current_location WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM description WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM email WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM ethnicity WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM gender WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM home_location WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM interested_in WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM is_suspended WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM keywords WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM looking_for WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM name WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM relationship WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM remember WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM reset WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM rss WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM username WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM visibility WHERE userid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM website WHERE userid='$this->id'";
    $conn->query($sql);

    // remove all posts

    // delete user record
    $conn = new DB('user',$this->id);
    $sql = "DELETE FROM users WHERE id='$this->id'";
    $conn->query($sql);

    // remove from cache
    cache_delete('User_'.$this->id);
    return true;
  }

/******************************************************************************/
/**************************** STATIC  User methods ****************************/
/******************************************************************************/

  static $indices = array
  (
    'birthday','created','current_location','description','email','ethnicity',
    'gender','home_location','interested_in','is_suspended','keywords',
    'looking_for','name','relationship','reset','username','visibility','website'
  );

  static function exists($type,$value)
  {
    if ($type=='user')
    {
      $value = strtolower($value);
      if (!ValidID($value))
      {
        log_write("ERROR in User::exists: value '$value' is not valid for type '$type'");
        return false;
      }

      $conn = new DB('user',$value);
      $match = $conn->countRows('users',"id='$value'");
      if ($match>1) log_write("ERROR in User::exists: multiple matches found for '$value' in '$type'");
      return ($match==1);
    }
    else if (in_array($type,User::$indices))
    {
      $conn = new DB('userindex',null,0);
      $val = addslashes($value);
      $match = $conn->countRows($type,"$type='$val'");
      if ($match>1) log_write("ERROR in User::exists: multiple matches found for '$value' in '$type'");
      return ($match==1);
    }
    else
    {
      log_write("ERROR in User::exists: Unknown type '$type' requested with value '$value'");
      return false;
    }
  }

/******************************************************************************/
/****************************** SEARCH FUNCTIONS ******************************/
/******************************************************************************/

  function getUsers($list,$page,$display,$filter='',$sort='abc')
  {
    $start = ($page-1) * USERS_PER_PAGE;
    $users = array();
    $conn = new DB('userindex',null,0);
    // get total
    $condition = "userid='$this->id' AND $list=1";
    switch ($display)
    {
      case (Enum::$userlist_display_opt['mute_on']) : $condition .= " AND muted=1"; break;
      case (Enum::$userlist_display_opt['mute_off']) : $condition .= " AND muted=0"; break;
      case (Enum::$userlist_display_opt['phone_on']) : $condition .= " AND sms_on=1"; break;
      case (Enum::$userlist_display_opt['phone_off']) : $condition .= " AND sms_on=0"; break;
    }
    $total = $conn->countRows('people',$condition);
    // get results
    $sql = "SELECT id FROM people WHERE $condition";
    if ($sort=='abc') $sql .= " ORDER BY username";
    if ($filter=='')
    {
      $sql .= " LIMIT $start,".USERS_PER_PAGE;
      $res = $conn->query($sql);
      while ($row = mysql_fetch_array($res))
      {
        $users[] = $row['id'];
      }
      $ret = array ('page'=>$page,'total'=>$total,'users'=>$users,'display'=>$display);
      return $ret;
    }
    else
    {
      $res = $conn->query($sql);
      $total = 0;
      $end = $start + USERS_PER_PAGE;
      while ($row = mysql_fetch_array($res))
      {
        $user = User::get($row['id']);
        if ($user)
        {
          if (preg_match("/^".$filter."/i",$user->username) || ($user->show_name && preg_match("/\b".$filter."/i",$user->name)))
          {
            if ($total >= $start && $total < $end)
            {
              $users[] = $user->id;
            }
            $total++;
          }
        }
      }
      $ret = array ('page'=>$page,'total'=>$total,'users'=>$users,'display'=>$display,'filter'=>$filter);
      return $ret;
    }
  }

  function getFriendsOfFriends($page,$filter='',$sort='abc')
  {
    $start = ($page-1) * USERS_PER_PAGE;
    $friends = array();
    $conn = new DB('userindex',null,0);
    $sql = "SELECT id FROM people WHERE userid='$this->id' AND friend=1";
    $res = $conn->query($sql);
    while ($row = mysql_fetch_array($res))
    {
      $friends[] = $row['id'];
    }
    $flist = implode("','",$friends);

    $users = array();
    $sql = "SELECT COUNT(DISTINCT id) AS ttl FROM people WHERE friend=1 AND userid IN ('$flist') AND id<>'$this->id'";
    $res = $conn->query($sql);
    $t = mysql_fetch_array($res);
    $total = $t['ttl'];

    $sql = "SELECT DISTINCT id FROM people WHERE friend=1 AND userid IN ('$flist') AND id<>'$this->id'";
    if ($sort=='abc') $sql .= " ORDER BY username";
    if ($filter=='')
    {
      $sql .= " LIMIT $start,".USERS_PER_PAGE;
      $res = $conn->query($sql);
      while ($row = mysql_fetch_array($res))
      {
        $users[] = $row['id'];
      }
      $ret = array ('page'=>$page,'total'=>$total,'users'=>$users,'display'=>$display);
      return $ret;
    }
    else
    {
      $res = $conn->query($sql);
      $total = 0;
      $end = $start + USERS_PER_PAGE;
      while ($row = mysql_fetch_array($res))
      {
        $user = User::get($row['id']);
        if ($user)
        {
          if (preg_match("/^".$filter."/i",$user->username) || ($user->show_name && preg_match("/\b".$filter."/i",$user->name)))
          {
            if ($total >= $start && $total < $end)
            {
              $users[] = $user->id;
            }
            $total++;
          }
        }
      }
      $ret = array ('page'=>$page,'total'=>$total,'users'=>$users,'display'=>$display,'filter'=>$filter);
      return $ret;
    }
  }

  function getGroups($list,$page,$display,$filter='',$sort='abc')
  {
    $match = array
    (
      'publicgroups'  => "private=0",
      'privategroups' => "private=1",
      'admingroups'   => "admin=1"
    );

    $start = ($page-1) * USERS_PER_PAGE;
    $groups = array();
    $conn = new DB('userindex',null,0);
    // get total
    $condition = "userid='$this->id' AND member=1 AND ".$match[$list];
    switch ($display)
    {
      case (Enum::$userlist_display_opt['mute_on']) : $condition .= " AND muted=1"; break;
      case (Enum::$userlist_display_opt['mute_off']) : $condition .= " AND muted=0"; break;
      case (Enum::$userlist_display_opt['phone_on']) : $condition .= " AND sms_on=1"; break;
      case (Enum::$userlist_display_opt['phone_off']) : $condition .= " AND sms_on=0"; break;
    }
    $total = $conn->countRows('groups',$condition);

    $sql = "SELECT groupid FROM groups WHERE $condition";
    if ($sort=='abc') $sql .= " ORDER BY groupname";
    if ($filter=='')
    {
      $sql .= " LIMIT $start,".USERS_PER_PAGE;
      $res = $conn->query($sql);
      while ($row = mysql_fetch_array($res))
      {
        $groups[] = $row['groupid'];
      }
      $ret = array ('page'=>$page,'total'=>$total,'groups'=>$groups,'display'=>$display);
      return $ret;
    }
    else
    {
      $res = $conn->query($sql);
      $total = 0;
      $end = $start + USERS_PER_PAGE;
      while ($row = mysql_fetch_array($res))
      {
        $group = Group::get($row['groupid']);
        if ($group)
        {
          if (preg_match("/^".$filter."/i",$group->groupname) || ($group->show_name && preg_match("/\b".$filter."/i",$group->name)))
          {
            if ($total >= $start && $total < $end)
            {
              $groups[] = $group->id;
            }
            $total++;
          }
        }
      }
      $ret = array ('page'=>$page,'total'=>$total,'groups'=>$groups,'display'=>$display,'filter'=>$filter);
      return $ret;
    }
  }

  function getFriendsGroups($page,$filter='',$sort='abc')
  {
    $start = ($page-1) * USERS_PER_PAGE;
    $friends = array();
    $conn = new DB('userindex',null,0);
    $sql = "SELECT id FROM people WHERE userid='$this->id' AND friend=1";
    $res = $conn->query($sql);
    while ($row = mysql_fetch_array($res))
    {
      $friends[] = $row['id'];
    }
    $flist = implode("','",$friends);

    $sql = "SELECT COUNT(DISTINCT groupid) AS ttl FROM groups WHERE private=0 AND userid IN ('$flist')";
    $res = $conn->query($sql);
    $t = mysql_fetch_array($res);
    $total = $t['ttl'];

    $groups = array();
    $sql = "SELECT DISTINCT groupid FROM groups WHERE private=0 AND userid IN ('$flist')";
    if ($sort=='abc') $sql .= " ORDER BY groupname";
    if ($filter=='')
    {
      $sql .= " LIMIT $start,".USERS_PER_PAGE;
      $res = $conn->query($sql);
      while ($row = mysql_fetch_array($res))
      {
        $groups[] = $row['groupid'];
      }
      $ret = array ('page'=>$page,'total'=>$total,'groups'=>$groups,'display'=>$display);
      return $ret;
    }
    else
    {
      $res = $conn->query($sql);
      $total = 0;
      $end = $start + USERS_PER_PAGE;
      while ($row = mysql_fetch_array($res))
      {
        $group = Group::get($row['groupid']);
        if ($group)
        {
          if (preg_match("/^".$filter."/i",$group->groupname) || ($group->show_name && preg_match("/\b".$filter."/i",$group->name)))
          {
            if ($total >= $start && $total < $end)
            {
              $groups[] = $group->id;
            }
            $total++;
          }
        }
      }
      $ret = array ('page'=>$page,'total'=>$total,'groups'=>$groups,'display'=>$display,'filter'=>$filter);
      return $ret;
    }
  }

  static function fullSearch($keywords,$page=1)
  {
    $matches = array();
    $unmatches = array();
    $dblist = DB::$shardlist['userindex'];

    foreach ($dblist as $db)
    {
      $conn = new DB('userindex',null,$db);
      // get matches by username
      foreach ($keywords as $word)
      {
        $sql = "SELECT userid FROM username WHERE username='$word'";
        $res = $conn->query($sql);
        while ($r = mysql_fetch_array($res))
        {
          $unmatches[] = $r['userid'];
        }
      }
    }
    $unmatches = array_unique($unmatches);
    sort($unmatches);
    $matches = $unmatches;

    $nmatches = array();
    foreach ($dblist as $db)
    {
      $conn = new DB('userindex',null,$db);
      // get matches by firstname or lastname
      foreach ($keywords as $word)
      {
        $sql = "SELECT userid FROM name WHERE show_name=1 AND name RLIKE '[[:<:]]".$word."[[:>:]]'";
        if (count($matches)>0)
        {
          $exclude = implode("','",$matches);
          $sql .= " AND userid NOT IN ('$exclude')";
        }
        $res = $conn->query($sql);
        while ($r = mysql_fetch_array($res))
        {
          $nmatches[] = $r['userid'];
        }
      }
    }
    $nmatches = array_unique($nmatches);
    sort($nmatches);
    $matches = array_merge((array)$matches,(array)$nmatches);

    // get private users to use in exclude list
    $priv = array();
    foreach ($dblist as $db)
    {
      $conn = new DB('userindex',null,$db);
      $sql = "SELECT userid FROM visibility WHERE visibility=2";
      $pvt = $conn->query($sql);
      while ($p = mysql_fetch_array($pvt))
      {
        $priv[] = $p['userid'];
      }
    }

    $exc = array_merge((array)$priv,(array)$matches);
    // get keyword matches first
    $tagmatches = array();
    foreach ($dblist as $db)
    {
      $conn = new DB('userindex',null,$db);
      foreach ($keywords as $word)
      {
        $sql = "SELECT userid FROM keywords WHERE keyword='$word'";
        if (count($exc)>0)
        {
          $exclude = implode("','",$exc);
          $sql .= " AND userid NOT IN('$exclude')";
        }
        $res = $conn->query($sql);
        while ($r = mysql_fetch_array($res))
        {
          $tagmatches[] = $r['userid'];
        }
      }
    }
    $tagmatches = array_unique($tagmatches);
    sort($tagmatches);
    $matches = array_merge((array)$matches,(array)$tagmatches);
    $exc = array_merge((array)$priv,(array)$matches);

    // get users from descrpitions
    $dmatches = array();
    foreach ($dblist as $db)
    {
      $conn = new DB('userindex',null,$db);
      foreach ($keywords as $word)
      {
        $sql = "SELECT userid FROM description WHERE word='$word'";
        if (count($exc)>0)
        {
          $exclude = implode("','",$exc);
          $sql .= " AND userid NOT IN ('$exclude')";
        }
        $res = $conn->query($sql);
        while ($r = mysql_fetch_array($res))
        {
          $dmatches[] = $r['userid'];
        }
      }
    }
    $dmatches = array_unique($dmatches);
    sort($dmatches);
    $matches = array_merge((array)$matches,(array)$dmatches);
    // $matches now contains ALL matching users
    $response = array();
    $response['total'] = count($matches);
    $response['pages'] = ceil($response['total']/USERS_PER_PAGE);
    $response['page'] = $page;
    $b = ($page-1)*USERS_PER_PAGE;
    $response['users'] = array_slice($matches,$b,USERS_PER_PAGE);
    return $response;
  }

  static function getFeatured($page)
  {

  }



  static function taglines_users()
  {
    echo "<h1>taglines_users</h1>";
    return false;
  }

  static function featured_getall()
  {
    echo "<h1>featured_getall</h1>";
    return false;
  }



/******************************************************************************/
/*************************** STATIC  USER VARIABLES ***************************/
/******************************************************************************/

  static $notification_settings = array
  (
    'new_follower'        => array( 'dashboard'=>true, 'email'=>false, 'sms'=>false ),
    'follow_request'      => array( 'dashboard'=>true, 'email'=>false, 'sms'=>false ),
    'follow_accepted'     => array( 'dashboard'=>true, 'email'=>false, 'sms'=>false ),
    'follow_declined'     => array( 'dashboard'=>true, 'email'=>false, 'sms'=>false ),
    'group_invitation'    => array( 'dashboard'=>true, 'email'=>false, 'sms'=>false ),
    'membership_accepted' => array( 'dashboard'=>true, 'email'=>false, 'sms'=>false ),
    'membership_declined' => array( 'dashboard'=>true, 'email'=>false, 'sms'=>false )
  );

  static $message_settings = array
  (
    'public_message'      => array( 'email'=>true, 'sms'=>false ),
    'private_message'     => array( 'email'=>true, 'sms'=>false ),
    'public_reply'        => array( 'email'=>false, 'sms'=>false ),
    'private_reply'       => array( 'email'=>false, 'sms'=>false )
  );

  static $interested_in = array
  (
    'male','female'
  );


}