<?

class Show
{
  function Messages($controller,$list)
  {
    global $LIST;
    $page = ( $_POST['message_page'] > 0 ) ? $_POST['message_page'] : 1;
    $viewtime = ( $_POST['viewtime'] > 0 ) ? $_POST['viewtime'] : microtime(true);
    if ($_POST['messagetype']=='group') $messagetype = 'group';
    else $messagetype = ( $_POST['messagetype'] ) ? Enum::$message_type[$_POST['messagetype']] : '';
    $filter = $_POST['filter'];
    if ($controller=='group')
    {
      global $Group;
      $LIST = Post::forGroup($Group->id,$list,$page,$viewtime,$filter);
    }
    else
    {
      global $User, $Profile;
      if ($controller=='user')
      {
        if (!LOGGEDIN)
        {
          Render('user','login','ml');
          return;
        }
        else if (!CONFIRMED)
        {
          Render("signup","confirm","ml");
          return;
        }
        $Profile=$User;
      }
      $view = $controller.'_'.$list;
      $LIST = Post::forUser($Profile->id,$view,$messagetype,$page,$viewtime,$filter);
    }

    if ($_POST['initial_load']==1)
    {
      Render('partials','messagelist');
    }
    else
    {
      include_once('views/partials/message.php');
      $updates = array();
      foreach ($LIST['list'] as $id)
      {
        $update = array();
        $update['id'] = $id;
        $message = Post::get($id);
        $update['html'] = rendermessage($message,$filter);
        $updates[] = $update;
      }
      $response = array('messages'=>$updates,'message_page'=>$LIST['page'],'total'=>$LIST['total'],'viewtime'=>$LIST['viewtime']);
      echo json_encode($response);
    }
  }

  function Users($controller,$list)
  {
    global $LIST;
    $page = ( $_POST['page'] > 0 ) ? $_POST['page'] : 1;
    $display = Enum::$userlist_display_opt[$_POST['display']];
    $filter = $_POST['filter'];
    if ($controller=='group')
    {
      global $Group;
      $LIST = $Group->getMemberList($list,$page,$display,$filter);
    }
    else
    {
      global $User, $Profile;
      if ($controller=='user')
      {
        if (!LOGGEDIN)
        {
          Render('user','login','ml');
          return;
        }
        else if (!CONFIRMED)
        {
          Render("signup","confirm","ml");
          return;
        }
        $Profile=$User;
      }

      $listopts = array ('friends'=>'friend','following'=>'following','followers'=>'follower');

      switch ($list)
      {
        case 'friends':
        case 'following':
        case 'followers':
          $LIST = $Profile->getUsers($listopts[$list],$page,$display,$filter);
          break;
        case 'fof':
          $LIST = $User->getFriendsOfFriends($page,$filter);
          break;
        case 'featured':
          $LIST = User::getFeatured($page,$filter);
          break;
      }
    }

    Render('partials','userlist');
  }

  function Groups($controller,$list)
  {
    global $User, $Profile, $LIST;
    $page = ( $_POST['page'] > 0 ) ? $_POST['page'] : 1;
    $display = Enum::$userlist_display_opt[$_POST['display']];
    $filter = $_POST['filter'];
    if ($controller=='user')
    {
      if (!LOGGEDIN)
      {
        Render('user','login','ml');
        return;
      }
      else if (!CONFIRMED)
      {
        Render("signup","confirm","ml");
        return;
      }
      $Profile=$User;
    }

    switch ($list)
    {
      case 'publicgroups':
      case 'privategroups':
      case 'admingroups':
        $LIST = $Profile->getGroups($list,$page,$display,$filter);
        break;
      case 'friendgroups':
        $LIST = $User->getFriendsGroups($page,$filter);
        break;
    }

    Render('partials','grouplist');
  }
}

?>