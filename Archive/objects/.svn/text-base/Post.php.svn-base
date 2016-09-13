<?

class Post
{
  protected static $_posts = array();

  protected function __clone() {}

  public static function get($id)
  {
    // if post object defined, return it
    if (array_key_exists($id,self::$_posts)) { return self::$_posts['$id']; }
    if ($id!='0')
    {
      // does post exist?
      $exists = self::exists('post',$id);
      if (!$exists) { return false; }
    }
    //create and return Post object
    return new self($id);
  }

  public static function create()
  {
    // get new ID
    $id = DB::getID('post');
    // create new post object
    $post = new self($id,true);
    $post->created = microtime(true);
    $post->updated = $post->created;
    // return post object
    return $post;
  }

  protected function __construct($id,$new=false)
  {
    // register self with class
    self::$_posts[$id]==$this;
    if ($new || $id=='0')
    {
      $this->id = $id;
      return;
    }

    // check cache first
    $post = cache_get('Post_'.$id);

    if ($post)
    {
      foreach ($post as $key=>$value)
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
    // do not load null post - this should never happen
    if ($this->id=='0')
    {
      log_write("ERROR in Post::loadFromDB: Can't load NULL post");
      return false;
    }

    $conn = new DB('post',$this->id);
    $post = $conn->get('post','posts',"id='$this->id'");
    if (!$post)
    {
      log_write("ERROR in Post::loadFromDB: unable to retreive post from database (using id='$this->id')");
      return false;
    }
    else
    {
      $usr = unserialize(stripslashes($post));
      foreach ($usr as $key=>$val) $this->$key=$val;
      cache_set('Post_'.$this->id,$this);
      return;
    }
  }

/******************************************************************************/
/******************************* MISC FUNCTIONS *******************************/
/******************************************************************************/

  function sentTo()
  {
    if (count($this->sent_to)==0) return "everyone";
    else if (count($this->sent_to)==1) return $this->sent_to[0];
    else return $this->sent_to;
  }

  function isSentTo($id)
  {
    return in_array($id,$this->sent_to);
  }

  function wasSharedBy($id)
  {
    // look at shares to determine if user $id has shared this post
    return false;
  }

  function getReplies($refresh=false)
  {
    $cacheid = "replies_$this->id";
    if (!$refresh) $replies = cache_get($cacheid);
    if (!$replies)
    {
      $conn = new DB('postindex',null,0);
      $sql = "SELECT replyid FROM replies WHERE postid='$this->id'";
      $res = $conn->query($sql);
      $replies = array();
      while ($r = mysql_fetch_assoc($res))
      {
        $replies[] = $r['replyid'];
      }
      cache_set($cacheid,$replies);
    }
    return $replies;
  }

  function getShares($refresh=false)
  {
    $cacheid = "shares_$this->id";
    if (!$refresh) $shares = cache_get($cacheid);
    if (!$shares)
    {
      $conn = new DB('postindex',null,0);
      $sql = "SELECT shareid FROM shares WHERE postid='$this->id'";
      $res = $conn->query($sql);
      $shares = array();
      while ($r = mysql_fetch_assoc($res))
      {
        $shares[] = $r['shareid'];
      }
      cache_set($cacheid,$shares);
    }
    return $shares;
  }

/******************************************************************************/
/******************************* HTML FUNCTIONS *******************************/
/******************************************************************************/

  function messageText()
  {
    if ($this->type==Enum::$message_type['notification'] || $this->type==Enum::$message_type['alert'])
    {
      return $this->text;
    }
    else
    {
      $messagetext = htmlspecialchars($this->text,ENT_COMPAT,null,false);
      $messagetext = preg_replace("/(www\.|http:\/\/|https:\/\/)([^\s]+)/", '<a href="$1$2" target="_blank">$1$2</a>', $messagetext);
      $messagetext = str_replace("href=\"www.","href=\"http://www.",$messagetext);
      return $messagetext;
    }
  }

  function oppLink()
  {
    return "messages/view/$this->id";
  }

  function sent()
  {
    $time = time() - floor($this->created);
    if ($time < MINUTE_IN_SEC)
    {
      if ($time==1) $when = '1 second ago';
      else $when = $time . ' seconds ago';
    }
    else if ($time < HOUR_IN_SEC)
    {
      $m = floor($time/MINUTE_IN_SEC);
      if ($m==1) $when = '1 minute ago';
      else $when =  $m . ' minutes ago';
    }
    else if ($time < DAY_IN_SEC)
    {
      $h = floor($time/HOUR_IN_SEC);
      if ($h==1) $when = '1 hour ago';
      else $when =  $h . ' hours ago';
    }
    else
    {
      $d = floor($time/DAY_IN_SEC);
      if ($d==1) $when = '1 day ago';
      else $when =  $d . ' days ago';
    }

    if ($this->source == Enum::$source['text']) $how .= "by text";
    else $how .= "from the web";

    $ret = array( 'when'=>$when, 'how'=>$how );
    return $ret;
  }


/******************************************************************************/
/******************************* DATA  HANDLING *******************************/
/******************************************************************************/

  function save()
  {
    $conn = new DB('post',$this->id);
    if ($this->type<Enum::$message_type['share'])
    {
      $this->number_of = array('replies'=>0,'shares'=>0);
    }
    $ser = addslashes(serialize($this));
    $sql = "INSERT INTO posts (id,post) VALUES ('$this->id','$ser')";
    $res = $conn->query($sql);
    if (!$res)
    {
      return false;
    }
    else
    {
      cache_set('Post_'.$this->id,$this);
      if ($this->type==Enum::$message_type['reply']) $this->saveReply();
      else if ($this->type==Enum::$message_type['share']) $this->saveShare();
      else if ($this->type<Enum::$message_type['share'] && ($this->sharing==Enum::$sharing['public'] || $this->sharing==Enum::$sharing['public_group'])) $this->index(true);
      Message::queue($this->id);
      return true;
    }
  }

  function saveReply()
  {
    $conn = new DB('postindex',$this->id);
    $sql = "INSERT INTO replies (replyid,postid,created) VALUES ('$this->id','$this->original_id',$this->created)";
    $conn->query($sql);

    $parent = self::get($this->original_id);
    if ($parent)
    {
      $parent->number_of['replies'] += 1;
      $parent->updated = $this->created;
      $sql = "UPDATE public_timeline SET num_replies=".$parent->number_of['replies'].", updated=$this->created WHERE postid='$parent->id'";
      $conn->query($sql);
      $parent->saveIndex($conn,'updated');
      cache_set('Post_'.$parent->id,$parent);
    }
  }

  function saveShare()
  {
    $conn = new DB('postindex',$this->id);
    $sql = "INSERT INTO shares (shareid,postid,created) VALUES ('$this->id','$this->original_id',$this->created)";
    $conn->query($sql);

    $shared = self::get($this->original_id);
    if ($shared)
    {
      $shared->number_of['shares'] += 1;
      $shared->updated = $this->created;
      $sql = "UPDATE public_timeline SET num_shares=".$shared->number_of['shares'].", updated=$this->created WHERE postid='$shared->id'";
      $conn->query($sql);
      $shared->saveIndex($conn,'updated');
      cache_set('Post_'.$shared->id,$shared);
    }
  }

  function update()
  {
    $conn = new DB('post',$this->id);
    $ser = addslashes(serialize($this));
    $sql = "UPDATE posts SET post='$ser' WHERE id='$this->id'";
    $res = $conn->query($sql);
    if (!$res)
    {
      return false;
    }
    else
    {
      cache_set('Post_'.$this->id,$this);
      // update indices
      if ($this->type<Enum::$message_type['share'] && ($this->sharing==Enum::$sharing['public'] || $this->sharing==Enum::$sharing['public_group'])) $this->index(true);
      return true;
    }
  }

  private function saveIndex($conn,$index,$table=null)
  {
    if (!$table) $table = $index;
    $sql = "INSERT INTO $table ($index,postid,created) VALUES ('".$this->$index."','$this->id','$this->created') ON DUPLICATE KEY UPDATE $table='".$this->$index."'";
    $conn->query($sql);
  }

  function index($new=false)
  {
    $conn = new DB('postindex',$this->id);

    if ($this->updated) { $this->saveIndex($conn,'updated'); $updated=$this->updated; }
    else $updated = $this->created;
    if ($new)
    {
      $category = ($this->category) ? $this->category : 0;
      $sql = "INSERT INTO public_timeline (postid,category,type,num_shares,num_replies,created,updated) VALUES ('$this->id',$category,$this->type,0,0,'$this->created','$updated')";
      $conn->query($sql);

      $sql = "INSERT INTO created (created,postid) VALUES ('$this->created','$this->id')";
      $conn->query($sql);

      if ($this->category) $this->saveIndex($conn,'category');
      if ($this->sent_by) $this->saveIndex($conn,'sent_by');
      if ($this->source) $this->saveIndex($conn,'source');
      if ($this->type) $this->saveIndex($conn,'type');

      if (count($this->sent_to>0))
      {
        foreach ($this->sent_to as $sent_to)
        {
          $sql = "INSERT INTO sent_to (sent_to,postid,created) VALUES ('$sent_to','$this->id','$this->created')";
          $conn->query($sql);
        }
      }

      if ($this->location && $this->location['country_code']!='')
      {
        $sql = "INSERT INTO location (country,region,city,postid,created) VALUES ('".$this->location['country_code']."','".$this->location['region_code']."','".$this->location['city_code']."','$this->postid','$this->created')";
        $conn->query($sql);
      }

      // text & keywords
      $words = str_to_words($this->text);
      foreach ($words as $word)
      {
        $sql = "INSERT INTO text (word,postid,created) VALUES ('".addslashes($word)."','$this->id','$this->created')";
        $conn->query($sql);
      }

      foreach ($this->keywords as $keyword)
      {
        $sql = "INSERT INTO keywords (keyword,postid,created) VALUES ('".addslashes($keyword)."','$this->id','$this->created')";
        $conn->query($sql);
      }
    }
  }

  function saveToTimeline($id,$group=false)
  {
    if ($group)
    {
      $timeline = 0;
      $requests = 0;
      $admin = 0;

      if ($this->type==Enum::$message_type['request'])
      {
        $requests = 1;
      }
      else if ($this->sharing==Enum::$sharing['admin'] || $this->type==Enum::$message_type['admin'])
      {
        $admin = 1;
      }
      else if ($this->type!=Enum::$message_type['reply'])
      {
        $timeline = 1;
      }

      $conn = new DB('groupindex',$id);
      $sql = "INSERT INTO posts (
                postid,
                groupid,
                created,
                timeline,
                requests,
                admin,
                type
              ) VALUES (
                '$this->id',
                '$id',
                $this->created,
                $timeline,
                $requests,
                $admin,
                $this->type
              )";
      $conn->query($sql);
      return true;
    }
    else
    {
      $user_timeline = 0;
      $user_received = 0;
      $user_sent = 0;
      $user_rreceived = 0;
      $user_rsent = 0;
      $user_thread = 0;
      $user_notifications = 0;
      $member_sent = 0;
      $member_rsent = 0;
      $member_ssent = 0;
      $member_received = 0;
      $member_rreceived = 0;
      $member_sreceived = 0;
      // reply sent by me: user_rsent, member_rsent
      // reply to my op: user_rreceived, member_rreceived
      // reply not to my op: user_thread

      // notification: user_timeline, user_notifications
      // alert: user_timeline, user_notifications

      // share: user_timeline
      // share sent to me: member_sreceived
      // share I sent: member_ssent

      // OP sent by me: user_timeline, user_sent, member_sent
      // OP addressed to me: user_timeline, user_received, member_received
      // OP not addressed to me: user_timeline

      if ($this->type==Enum::$message_type['reply'])
      {
        if ($id==$this->sent_by)                { $user_rsent = 1; $member_rsent = 1; }
        else if (in_array($id,$this->sent_to))  { $user_rreceived = 1; $member_rreceived = 1; }
        else                                    { $user_thread = 1; }
      }
      else if ($this->type==Enum::$message_type['share'])
      {
        $user_timeline = 1;
        if ($id==$this->sent_by)                { $member_ssent = 1; }
      }
      else if ($this->type==Enum::$message_type['notification'] || $this->type==Enum::$message_type['alert'])
      {
        $user_timeline = 1; $user_notifications = 1;
      }
      else
      {
        if ($id==$this->sent_by)                { $user_timeline = 1; $user_sent = 1; $member_sent = 1; }
        else if (in_array($id,$this->sent_to))  { $user_timeline = 1; $user_received = 1; $member_received = 1; }
        else                                    { $user_timeline = 1; }
      }
      $isgroup = ($this->sharing==Enum::$sharing['public_group'] || $this->sharing==Enum::$sharing['private_group']) ? 1 : 0;
      $conn = new DB('userindex',$id);
      $sql = "INSERT INTO posts (
                postid,
                userid,
                created,
                type,
                is_group,
                user_timeline,
                user_received,
                user_sent,
                user_rreceived,
                user_rsent,
                user_thread,
                user_notifications,
                member_sent,
                member_rsent,
                member_ssent,
                member_received,
                member_rreceived,
                member_sreceived
              ) VALUES (
                '$this->id',
                '$id',
                $this->created,
                $this->type,
                $isgroup,
                $user_timeline,
                $user_received,
                $user_sent,
                $user_rreceived,
                $user_rsent,
                $user_thread,
                $user_notifications,
                $member_sent,
                $member_rsent,
                $member_ssent,
                $member_received,
                $member_rreceived,
                $member_sreceived
              )";
      $conn->query($sql);
      return true;
    }
  }

  function remove()
  {
    // TODO: REMOVE REPLIES, ALERTS, SHARES FIRST
    // TODO: really, there's got to be a more efficient way!
    // remove from indices
    $conn = new DB('postindex',$this->id);
    $sql = "DELETE FROM category WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM created WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM keywords WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM location WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM original_post WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM replies WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM sent_by WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM sent_to WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM shares WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM source WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM text WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM type WHERE postid='$this->id'";
    $conn->query($sql);
    $sql = "DELETE FROM updated WHERE postid='$this->id'";
    $conn->query($sql);

    // remove from user
    $conn = new DB('userindex',null,0);
    $sql = "SELECT userid FROM posts WHERE postid='$this->id'";
    $res = $conn->query($sql);
    $sql = "DELETE FROM posts WHERE postid='$this->id'";
    $conn->query($sql);
    while ($r = mysql_fetch_assoc($res))
    {
      cache_delete('User_'.$r['userid']);
    }

    // delete post record
    $conn = new DB('post',$this->id);
    $sql = "DELETE FROM posts WHERE id='$this->id'";
    $conn->query($sql);

    // remove from cache
    cache_delete('Post_'.$this->id);
    return true;
  }

/******************************************************************************/
/**************************** STATIC Post methods ****************************/
/******************************************************************************/

  static $indices = array
  (
    'category','created','keywords','location','original_post','replies',
    'sent_by','sent_to','shares','source','text','type','updated'
  );

  static function exists($type,$value)
  {
    if ($type=='post')
    {
      $value = strtolower($value);
      if (!ValidID($value))
      {
        log_write("ERROR in Post::exists: value '$value' is not valid for type '$type'");
        return false;
      }

      $conn = new DB('post',$value);
      $match = $conn->countRows('posts',"id='$value'");
      if ($match>1) log_write("ERROR in Post::exists: multiple matches found for '$value' in '$type'");
      return ($match==1);
    }
    else if (in_array($type,Post::$indices))
    {
      $match = 0;
      foreach (DB::$shardlist['postindex'] as $db)
      {
        $conn = new DB('postindex',null,$db);
        $val = addslashes($value);
        $match += $conn->countRows($type,"$type='$val'");
      }
      if ($match>1) log_write("ERROR in Post::exists: multiple matches found for '$value' in '$type'");
      return ($match==1);
    }
    else
    {
      log_write("ERROR in Post::exists: Unknown type '$type' requested with value '$value'");
      return false;
    }
  }

/******************************************************************************/
/************************** STATIC  SEARCH FUNCTIONS **************************/
/******************************************************************************/

  static function forUser($userid,$view,$messagetype,$page,$viewtime,$filter='')
  {
    $start = (($page-1)*MESSAGES_PER_PAGE);
    $conn = new DB('userindex',null,0);
    $total = $conn->countRows('posts',"userid='$userid' AND $view=1 AND created<$viewtime");
    $messages = array();
    if ($messagetype)
    {
      if ($messagetype=='group')
      {
        $sql = "SELECT postid FROM posts WHERE userid='$userid' AND $view=1 AND created<$viewtime AND is_group=1 ORDER BY created DESC";
      }
      else
      {
        $sql = "SELECT postid FROM posts WHERE userid='$userid' AND $view=1 AND created<$viewtime AND type=$messagetype ORDER BY created DESC";
      }
    }
    else
    {
      $sql = "SELECT postid FROM posts WHERE userid='$userid' AND $view=1 AND created<$viewtime ORDER BY created DESC";
    }
    if ($filter=='')
    {
      $sql .= " LIMIT $start,".MESSAGES_PER_PAGE;
      $res = $conn->query($sql);
      while ($row = mysql_fetch_assoc($res))
      {
        $messages[] = $row['postid'];
      }
      return array('list'=>$messages,'total'=>$total,'viewtime'=>$viewtime,'page'=>$page);
    }
    else
    {
      $res = $conn->query($sql);
      $total = 0;
      $end = $start + MESSAGES_PER_PAGE;
      while ($row = mysql_fetch_array($res))
      {
        $post = Post::get($row['postid']);
        if ($post)
        {
          // match sender
          $user = User::get($post->sent_by);
          //if (preg_match("/^".$filter."/i",$user->username) || ($user->show_name && preg_match("/\b".$filter."/i",$user->name)))
          if (preg_match("/^".$filter."/i",$user->username))
          {
            if ($total >= $start && $total < $end)
            {
              $messages[] = $post->id;
            }
            $total++;
          }
          else if ($post->sharing==Enum::$sharing['public_group'] || $post->sharing==Enum::$sharing['private_group'] || $post->sharing==Enum::$sharing['admin_group'])
          {
            $group = Group::get($post->sent_to[0]);
            if (preg_match("/^".$filter."/i",$group->groupname))
            {
              if ($total >= $start && $total < $end)
              {
                $messages[] = $post->id;
              }
              $total++;
            }
          }
          else if (count($post->sent_to)>0)
          {
            foreach ($post->sent_to as $u)
            {
              $user = User::get($u);
              //if (preg_match("/^".$filter."/i",$user->username) || ($user->show_name && preg_match("/\b".$filter."/i",$user->name)))
              if (preg_match("/^".$filter."/i",$user->username))
              {
                if ($total >= $start && $total < $end)
                {
                  $messages[] = $post->id;
                }
                $total++;
                break;
              }
            }
          }
        }
      }
      return array('list'=>$messages,'total'=>$total,'viewtime'=>$viewtime,'page'=>$page,'filter'=>$filter);
    }
  }

  static function forGroup($groupid,$view,$page,$viewtime)
  {
    $start = (($page-1)*MESSAGES_PER_PAGE);
    $conn = new DB('groupindex',null,0);
    $total = $conn->countRows('posts',"groupid='$groupid' AND $view=1 AND created<$viewtime");
    $messages = array();
    $sql = "SELECT postid FROM posts WHERE groupid='$groupid' AND $view=1 AND created<$viewtime ORDER BY created DESC LIMIT $start,".MESSAGES_PER_PAGE;
    $res = $conn->query($sql);
    while ($row = mysql_fetch_assoc($res))
    {
      $messages[] = $row['postid'];
    }
    return array('list'=>$messages,'total'=>$total,'viewtime'=>$viewtime,'page'=>$page);
  }

  static function getPublicTimeline($view,$type,$category,$page,$viewtime,$starttime)
  {
    $start = (($page-1)*MESSAGES_PER_PAGE);
    $where = "created>$starttime AND created<$viewtime";
    if ($category>0) $where .= " AND category=$category";
    if ($type>0) $where .= " AND type=$type";
    switch ($view)
    {
      case 'discussed'  : $order = "num_shares DESC, created DESC"; break;
      case 'shared'     : $order = "num_replies DESC, created DESC"; break;
      default           : $order = "created DESC"; break;
    }

    $conn = new DB('postindex',null,0);
    $total = $conn->countRows('public_timeline',$where);
    $messages = array();
    $sql = "SELECT postid FROM public_timeline WHERE $where ORDER BY $order LIMIT $start,".MESSAGES_PER_PAGE;
    $res = $conn->query($sql);
    while ($row = mysql_fetch_assoc($res))
    {
      $messages[] = $row['postid'];
    }
    return array('list'=>$messages,'total'=>$total,'viewtime'=>$viewtime,'page'=>$page);
  }

}