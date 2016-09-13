<?

class Group
{
  protected static $_groups = array();

  protected function __clone() {}

  public static function get($id)
  {
    // if group object defined, return it
    if (array_key_exists($id,self::$_groups)) { return self::$_groups['$id']; }
    if ($id!='0')
    {
      // does group exist?
      $exists = self::exists('group',$id);
      if (!$exists) { return false; }
    }
    //create and return Group object
    return new self($id);
  }

  public static function getByName($groupname)
  {
    // check name index for groupid
    $groupname = addslashes(trim($groupname));
    $groupid = DB::getUnique('groupid','groupname',"groupname='$groupname'");
    if ($groupid) return self::get($groupid);
    else return false;
  }

  public static function create()
  {
    // get new ID
    $id = DB::getID('group');
    // create new group object
    $group = new self($id,true);
    $group->created = microtime(true);
    $group->setDefaults();
    // write new group to group table
    $group->save();
    // return group object
    return $group;
  }

  protected function __construct($id,$new=false)
  {
    // register self with class
    self::$_groups[$id]==$this;
    if ($new || $id=='0')
    {
      $this->id = $id;
      return;
    }

    // check cache first
    $group = cache_get('Group_'.$id);

    if ($group)
    {
      foreach ($group as $key=>$value)
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
    // do not load null group - this should never happen
    if ($this->id=='0')
    {
      log_write("ERROR in Group::loadFromDB: Can't load NULL group");
      return false;
    }

    $conn = new DB('group',$this->id);
    $group = $conn->get('grp','groups',"id='$this->id'");
    if (!$group)
    {
      log_write("ERROR in Group::loadFromDB: unable to retreive group from database (using id='$this->id')");
      return false;
    }
    else
    {
      $usr = unserialize(stripslashes($group));
      foreach ($usr as $key=>$val) $this->$key=$val;
      cache_set('Group_'.$this->id,$this);
      return;
    }
  }

/******************************************************************************/
/*********************** BOOLEAN STATUS TEST  FUNCTIONS ***********************/
/******************************************************************************/

  function membershipRequestedBy($userid)
  {
    $conn = new DB('groupindex',null,0);
    $res = $conn->countRows('members',"groupid='$this->id' AND userid='$userid' AND membership_request IS NOT NULL");
    return $res>0;
  }

  function visibleTo($userid)
  {
    if ($this->visibility==Enum::$visibility['public'] || $this->hasMember($userid))
    {
      // TODO: need to check blocked status
      return true;
    }
    else return false;
  }

/******************************************************************************/
/*********************** MEMBERS &  MEMBERSHIP HANDLING ***********************/
/******************************************************************************/

  function getMemberList($list,$page,$display,$filter='',$sort='abc')
  {
    $start = ($page-1) * USERS_PER_PAGE;
    $users = array();
    $conn = new DB('groupindex',null,0);
    switch($list)
    {
      case 'owner_owner'    : $view = 'owner=1'; $type = 'transfer_owner'; break;
      case 'owner_invite'   : $view = 'admin=1 AND owner=0 AND owner_invite IS NULL'; $type = 'transfer_owner'; break;
      case 'owner_pending'  : $view = 'admin=1 AND owner_invite IS NOT NULL'; $type = 'transfer_owner'; break;
      case 'admin_admins'   : $view = 'admin=1'; $type = 'manage_admins'; break;
      case 'admin_invite'   : $view = 'member=1 AND admin=0 AND admin_invite IS NULL'; $type = 'manage_admins'; break;
      case 'admin_invites'  : $view = 'admin_invite IS NOT NULL'; $type = 'manage_admins'; break;
      case 'manage_members' : $view = 'member=1'; $type = 'manage_members'; break;
      default               : $view = 'member=1';
    }
    // get total
    $condition = "groupid='$this->id' AND $view";
    $total = $conn->countRows('members',$condition);
    // get results
    $sql = "SELECT userid FROM members WHERE $condition";
    if ($sort=='abc') $sql .= " ORDER BY username";
    if ($filter=='')
    {
      $sql .= " LIMIT $start,".USERS_PER_PAGE;
      $res = $conn->query($sql);
      while ($row = mysql_fetch_array($res))
      {
        $users[] = $row['userid'];
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
        $user = User::get($row['userid']);
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

  // MEMBERS

  function getMembers($refresh=false)
  {
    $cacheid = "members_$this->id";
    if (!$refresh) $members = cache_get($cacheid);
    if (!$members)
    {
      $conn = new DB('groupindex',null,0);
      $sql = "SELECT userid FROM members WHERE groupid='$this->id' AND member=1";
      $res = $conn->query($sql);
      $members = array();
      while ($r = mysql_fetch_assoc($res))
      {
        $members[] = $r['userid'];
      }
      cache_set($cacheid,$members);
    }
    return $members;
  }

  function hasMember($userid)
  {
    $members = $this->getMembers();
    return in_array($userid,$members);
  }

  // ADMINS

  function getAdmins($refresh=false)
  {
    $cacheid = "admins_$this->id";
    if (!$refresh) $admins = cache_get($cacheid);
    if (!$admins)
    {
      $conn = new DB('groupindex',null,0);
      $sql = "SELECT userid FROM members WHERE groupid='$this->id' AND admin=1 AND owner=0";
      $res = $conn->query($sql);
      $admins = array();
      while ($r = mysql_fetch_assoc($res))
      {
        $admins[] = $r['userid'];
      }
      cache_set($cacheid,$admins);
    }
    return $admins;
  }

  function hasAdmin($userid)
  {
    $admins = $this->getAdmins();
    return in_array($userid,$admins);
  }

  function hasInvitedAdmin($userid)
  {
    $conn = new DB('groupindex',null,0);
    $res = $conn->countRows('members',"groupid='$this->id' AND userid='$userid' AND admin_invite IS NOT NULL");
    return $res>0;
  }

  function inviteAdmin($userid)
  {
    // need to test for invite
    if (!$this->hasMember($userid)) return false;
    if ($this->hasInvitedAdmin($userid)) return true;
    if ($this->hasAdmin($userid)) return false;
    Notify::AdminInvite($userid,$this);
    $conn = new DB('userindex',null,0);
    $sql = "UPDATE groups SET admin_invite=1 WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $conn = new DB('groupindex',null,0);
    $sql = "UPDATE members SET admin_invite=1 WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $this->getAdmins(true);
    return true;
  }

  function cancelAdmin($userid)
  {
    // need to test for invite
    if (!$this->hasMember($userid)) return false;
    if (!$this->hasInvitedAdmin($userid)) return true;
    if ($this->hasAdmin($userid)) return false;
    $conn = new DB('userindex',null,0);
    $sql = "UPDATE groups SET admin_invite=NULL WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $conn = new DB('groupindex',null,0);
    $sql = "UPDATE members SET admin_invite=NULL WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $this->getAdmins(true);
    return true;
  }

  function makeAdmin($userid,$override=false)
  {
    // need to test for invite
    /*if (!$override)
    {
      if (!$this->hasMember($userid)) return false;
      if (!$this->hasInvitedAdmin($userid)) return false;
    }*/
    if ($this->hasAdmin($userid)) return true;
    $conn = new DB('userindex',null,0);
    $sql = "UPDATE groups SET admin=1,admin_invite=NULL WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $conn = new DB('groupindex',null,0);
    $sql = "UPDATE members SET admin=1,admin_invite=NULL WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $this->getAdmins(true);
    return true;
  }

  function removeAdmin($userid)
  {
    // need to test for invite
    if (!$this->hasAdmin($userid)) return true;
    $conn = new DB('userindex',null,0);
    $sql = "UPDATE groups SET admin=0 WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $conn = new DB('groupindex',null,0);
    $sql = "UPDATE members SET admin=0 WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $this->getAdmins(true);
    return true;
  }

  // OWNER

  function inviteOwner($userid)
  {
    // need to test for invite
    if (!$this->hasMember($userid)) return false;
    if ($this->hasInvitedOwner($userid)) return true;
    if (!$this->hasAdmin($userid)) return false;
    Notify::OwnerInvite($userid,$this);
    $conn = new DB('userindex',null,0);
    $sql = "UPDATE groups SET owner_invite=1 WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $conn = new DB('groupindex',null,0);
    $sql = "UPDATE members SET owner_invite=1 WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $this->getAdmins(true);
    return true;
  }

  function cancelOwner($userid)
  {
    // need to test for invite
    if (!$this->hasAdmin($userid)) return false;
    if (!$this->hasInvitedOwner($userid)) return true;
    $conn = new DB('userindex',null,0);
    $sql = "UPDATE groups SET owner_invite=NULL WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $conn = new DB('groupindex',null,0);
    $sql = "UPDATE members SET owner_invite=NULL WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $this->getAdmins(true);
    return true;
  }

  function hasInvitedOwner($userid)
  {
    $conn = new DB('groupindex',null,0);
    $res = $conn->countRows('members',"groupid='$this->id' AND userid='$userid' AND owner_invite IS NOT NULL");
    return $res>0;
  }

  function makeOwner($userid,$first=false)
  {
    // need to test for invite
    if (!$this->hasAdmin($userid)) return false;
    if ($first)
    {
      $this->original_owner = $userid;
    }
    else
    {
      Notify::OwnerAccepted($userid,$this);
      $conn = new DB('userindex',null,0);
      $sql = "UPDATE groups SET owner=0 WHERE groupid='$this->id' AND userid='$this->owner'";
      $conn->query($sql);
      $conn = new DB('groupindex',null,0);
      $sql = "UPDATE members SET owner=0 WHERE groupid='$this->id' AND userid='$this->owner'";
      $conn->query($sql);
      $this->previous_owner = $this->owner;
    }
    $conn = new DB('userindex',null,0);
    $sql = "UPDATE groups SET owner=1,owner_invite=NULL WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $conn = new DB('groupindex',null,0);
    $sql = "UPDATE members SET owner=1,owner_invite=NULL WHERE groupid='$this->id' AND userid='$userid'";
    $conn->query($sql);
    $this->owner = $userid;
    $this->update();
    return true;
  }


/******************************************************************************/
/****************************** GROUP MANAGEMENT ******************************/
/******************************************************************************/

/******************************************************************************/
/****************************** PHOTOS &  ALBUMS ******************************/
/******************************************************************************/

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
    if ($full) return "<a href='groups/view/$this->id'>$this->groupname</a>";
    else return "groups/view/$this->id";
  }

  function getCreatedDate()
  {
    return date('Y-m-d',$this->created);
  }

  function getLocation()
  {
    $str = "";
    if ($this->location)
    {
      if ($this->location['city'])
      {
        if ($str != '') $str .= ', ';
        $str .= trim($this->location['city']);
      }
      if ($this->location['region'])
      {
        if ($str != '') $str .= ', ';
        $str .= trim($this->location['region']);
      }
      if ($this->location['country'])
      {
        if ($str != '') $str .= ', ';
        $str .= trim($this->location['country']);
      }
    }
    return $str;
  }

/******************************************************************************/
/******************************* DATA  HANDLING *******************************/
/******************************************************************************/

  function save()
  {
    $conn = new DB('group',$this->id);
    $ser = addslashes(serialize($this));
    $sql = "INSERT INTO groups (id,grp) VALUES ('$this->id','$ser')";
    $res = $conn->query($sql);
    if (!$res)
    {
      return false;
    }
    else
    {
      cache_set('Group_'.$this->id,$this);
      $this->index();
      return true;
    }
  }

  function update($reindex=true)
  {
    $conn = new DB('group',$this->id);
    $ser = addslashes(serialize($this));
    $sql = "UPDATE groups SET grp='$ser' WHERE id='$this->id'";
    $res = $conn->query($sql);
    if (!$res)
    {
      return false;
    }
    else
    {
      cache_set('Group_'.$this->id,$this);
      // update indices
      if ($reindex) $this->index();
      return true;
    }
  }

  function index()
  {
    $conn = new DB('groupindex',$this->id);

    // TODO: break into smaller functions
    // TODO: test values to determine if they NEED updating
    // use $reindex - param that lists what needs to be reindexed.

    $sql = "INSERT INTO groupname (groupname,groupid) VALUES ('".addslashes($this->groupname)."','$this->id') ON DUPLICATE KEY UPDATE groupname='".addslashes($this->groupname)."', groupid='$this->id'";
    $conn->query($sql);

    $sql = "INSERT INTO created (created,groupid) VALUES ('$this->created','$this->id') ON DUPLICATE KEY UPDATE created='$this->created', groupid='$this->id'";
    $conn->query($sql);

    $sql = "INSERT INTO visibility (visibility,groupid) VALUES ('$this->visibility','$this->id') ON DUPLICATE KEY UPDATE visibility='$this->visibility', groupid='$this->id'";
    $conn->query($sql);

    if ($this->name)
    {
      $sql = "INSERT INTO name (name,show_name,groupid) VALUES ('".addslashes($this->name)."','$this->show_name','$this->id') ON DUPLICATE KEY UPDATE name='".addslashes($this->name)."', show_name='$this->show_name', groupid='$this->id'";
    }
    else
    {
      $sql = "DELETE FROM name WHERE groupid='$this->id'";
    }
    $conn->query($sql);

    if ($this->website)
    {
      $sql = "INSERT INTO website (website,groupid) VALUES ('".addslashes($this->website)."','$this->id') ON DUPLICATE KEY UPDATE website='".addslashes($this->website)."', groupid='$this->id'";
    }
    else
    {
      $sql = "DELETE FROM website WHERE groupid='$this->id'";
    }
    $conn->query($sql);

    if ($this->is_suspended)
    {
      $sql = "INSERT INTO is_suspended (groupid) VALUES ('$this->id') ON DUPLICATE KEY UPDATE groupid=groupid";
    }
    else
    {
      $sql = "DELETE FROM is_suspended WHERE groupid='$this->id'";
    }

    if ($this->location && $this->location['country_code']!='')
    {
      $sql = "INSERT INTO location (country,region,city,groupid) VALUES ('".$this->location['country_code']."','".$this->location['region_code']."','".$this->location['city_code']."','$this->groupid') ON DUPLICATE KEY UPDATE country='".$this->location['country_code']."',region='".$this->location['region_code']."',city='".$this->location['city_code']."'";
    }
    else
    {
      $sql = "DELETE FROM location WHERE groupid='$this->id'";
    }
    $conn->query($sql);

    // need a test for if it's been updated....
    $sql = "DELETE FROM description WHERE groupid='$this->id'";
    $conn->query($sql);
    $words = str_to_words($this->description);
    foreach ($words as $word)
    {
      $sql = "INSERT INTO description (word,groupid) VALUES ('".addslashes($word)."','$this->id')";
      $conn->query($sql);
    }

    $sql = "DELETE FROM keywords WHERE groupid='$this->id'";
    $conn->query($sql);
    foreach ($this->keywords as $keyword)
    {
      $sql = "INSERT INTO keywords (keyword,groupid) VALUES ('".addslashes($keyword)."','$this->id')";
      $conn->query($sql);
    }

    // done indexing... ( I hope!)
  }

  function setDefaults()
  {
    $this->visibility = Enum::$visibility['public'];
    $this->show_name = true;
  }

  function remove()
  {
    // TODO: really, there's got to be a more efficient way!
    // remove from indices
    $conn = new DB('groupindex',$this->id);
    $sql = "DELETE FROM admins WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM category WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM created WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM groupname WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM is_suspended WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM keywords WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM location WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM members WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM name WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM owner WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM visibility WHERE groupid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM website WHERE groupid='$this->id'";
    $conn->query($sql);

    // remove all posts

    // delete group record
    $conn = new DB('group',$this->id);
    $sql = "DELETE FROM groups WHERE id='$this->id'";
    $conn->query($sql);

    // remove from cache
    cache_delete('Group_'.$this->id);
    return true;
  }

/******************************************************************************/
/**************************** STATIC Group methods ****************************/
/******************************************************************************/

  static $indices = array
  (
    'admins','created','description','groupname','is_suspended','keywords',
    'location','members','name','owner','visibility','website'
  );

  static function exists($type,$value)
  {
    if ($type=='group')
    {
      $value = strtolower($value);
      if (!ValidID($value))
      {
        log_write("ERROR in Group::exists: value '$value' is not valid for type '$type'");
        return false;
      }

      $conn = new DB('group',$value);
      $match = $conn->countRows('groups',"id='$value'");
      if ($match>1) log_write("ERROR in Group::exists: multiple matches found for '$value' in '$type'");
      return ($match==1);
    }
    else if (in_array($type,Group::$indices))
    {
      $conn = new DB('groupindex',null,$db);
      $val = addslashes($value);
      $match = $conn->countRows($type,"$type='$val'");
      if ($match>1) log_write("ERROR in Group::exists: multiple matches found for '$value' in '$type'");
      return ($match==1);
    }
    else
    {
      log_write("ERROR in Group::exists: Unknown type '$type' requested with value '$value'");
      return false;
    }
  }

/******************************************************************************/
/************************** STATIC  SEARCH FUNCTIONS **************************/
/******************************************************************************/

  static function fullSearch($keywords,$page=1)
  {
    $matches = array();
    $unmatches = array();
    $dblist = DB::$shardlist['groupindex'];

    foreach ($dblist as $db)
    {
      $conn = new DB('groupindex',null,$db);
      // get matches by groupname
      foreach ($keywords as $word)
      {
        $sql = "SELECT groupid FROM groupname WHERE groupname='$word'";
        $res = $conn->query($sql);
        while ($r = mysql_fetch_array($res))
        {
          $unmatches[] = $r['groupid'];
        }
      }
    }
    $unmatches = array_unique($unmatches);
    sort($unmatches);
    $matches = $unmatches;

    $nmatches = array();
    foreach ($dblist as $db)
    {
      $conn = new DB('groupindex',null,$db);
      // get matches by firstname or lastname
      foreach ($keywords as $word)
      {
        $sql = "SELECT groupid FROM name WHERE show_name=1 AND name RLIKE '[[:<:]]".$word."[[:>:]]'";
        if (count($matches)>0)
        {
          $exclude = implode("','",$matches);
          $sql .= " AND groupid NOT IN ('$exclude')";
        }
        $res = $conn->query($sql);
        while ($r = mysql_fetch_array($res))
        {
          $nmatches[] = $r['groupid'];
        }
      }
    }
    $nmatches = array_unique($nmatches);
    sort($nmatches);
    $matches = array_merge((array)$matches,(array)$nmatches);

    // get keyword matches first
    $tagmatches = array();
    foreach ($dblist as $db)
    {
      $conn = new DB('groupindex',null,$db);
      foreach ($keywords as $word)
      {
        $sql = "SELECT groupid FROM keywords WHERE keyword='$word'";
        if (count($exc)>0)
        {
          $exclude = implode("','",$exc);
          $sql .= " AND groupid NOT IN('$exclude')";
        }
        $res = $conn->query($sql);
        while ($r = mysql_fetch_array($res))
        {
          $tagmatches[] = $r['groupid'];
        }
      }
    }
    $tagmatches = array_unique($tagmatches);
    sort($tagmatches);
    $matches = array_merge((array)$matches,(array)$tagmatches);
    $exc = array_merge((array)$priv,(array)$matches);

    // get groups from descrpitions
    $dmatches = array();
    foreach ($dblist as $db)
    {
      $conn = new DB('groupindex',null,$db);
      foreach ($keywords as $word)
      {
        $sql = "SELECT groupid FROM description WHERE word='$word'";
        if (count($exc)>0)
        {
          $exclude = implode("','",$exc);
          $sql .= " AND groupid NOT IN ('$exclude')";
        }
        $res = $conn->query($sql);
        while ($r = mysql_fetch_array($res))
        {
          $dmatches[] = $r['groupid'];
        }
      }
    }
    $dmatches = array_unique($dmatches);
    sort($dmatches);
    $matches = array_merge((array)$matches,(array)$dmatches);
    // $matches now contains ALL matching groups
    $response = array();
    $response['total'] = count($matches);
    $response['pages'] = ceil($response['total']/USERS_PER_PAGE);
    $response['page'] = $page;
    $b = ($page-1)*USERS_PER_PAGE;
    $response['groups'] = array_slice($matches,$b,USERS_PER_PAGE);
    return $response;
  }
}