<?

// Top Tabs Response controller

/*********************************** PAGES  ***********************************/

// Message pages

function all()
{
  Render('tabs','messages');
}

function text()
{
  Render('tabs','messages');
}

function photo()
{
  Render('tabs','messages');
}

function video()
{
  Render('tabs','messages');
}

function links()
{
  Render('tabs','messages');
}

function review()
{
  Render('tabs','messages');
}

function question()
{
  Render('tabs','messages');
}

function location()
{
  Render('tabs','messages');
}

function rss()
{
  Render('tabs','messages');
}

// non-message tabs

function groups()
{
  global $PARAMS,$SHOWLIST;
  if ($PARAMS) $SHOWLIST = true;
  else $SHOWLIST = false;
  Render('tabs','categories');
}

function categories()
{
  global $PARAMS,$SHOWLIST;
  if ($PARAMS) $SHOWLIST = true;
  else $SHOWLIST = false;
  Render('tabs','categories');
}

/************************************ AJAX ************************************/

function get_messages()
{
  global $PARAMS, $LIST;
  // get working variables
  $t = $_POST['type'] =='links' ? 'link' : $_POST['type'];
  $type = Enum::$message_type[$t];
  $category = intval($_POST['category']);
  $page = ( $_POST['message_page'] > 0 ) ? $_POST['message_page'] : 1;
  $viewtime = ( $_POST['viewtime'] > 0 ) ? $_POST['viewtime'] : microtime(true);

  //$starttime = floor($viewtime) - DAY_IN_SEC;
  $starttime = floor($viewtime) - (DAY_IN_SEC * 365);

  $LIST = Post::getPublicTimeline($PARAMS,$type,$category,$page,$viewtime,$starttime);
  if ($_POST['initial_load']==1)
  {
    Render("partials","messagelist");
  }
  else echo "ok";
}

function get_groups()
{
// TODO: rework for GROUP support
  global $PARAMS, $GROUP_LIST, $LIST_PAGE, $FILTER, $DISPLAY_TYPE;
  if ($_POST['page']) $LIST_PAGE = $_POST['page'];
  else $LIST_PAGE = 1;
  $FILTER = strtolower($_POST['filter']);
  $isfiltered = ($FILTER != "");
  $displayopt = $_POST['display'];
  $category = $_POST['category'];

  $grouplist = array();

  switch($PARAMS)
  {
    case 'featured' : $grouplist = Group::getFeatured(); break;
    case 'newestg'  : $grouplist = Group::getAll('age'); break;
    case 'popular'  : $grouplist = Group::getAll('pop'); break;
  }

  $GroupList = array();
  foreach ($grouplist as $group)
  {
    if (is_array($group))
    {
      if ($group['group_id']) $group = Group::get($group['group_id']);
      else if ($group['id']) $group = Group::get($group['id']);
    }
    else $group = Group::get($group);
    if ($group->category==$category)
    {
      $DISPLAY_TYPE = 'short';
      switch ($displayopt)
      {
        case 'long_list':
          $DISPLAY_TYPE = 'long';
          $GroupList[] = $group->id;
          break;
        case 'phone_on':
          if ($Group->notifyBySMS($group->id)) $GroupList[] = $group->id;
          break;
        case 'phone_off':
          if (!$Group->notifyBySMS($group->id)) $GroupList[] = $group->id;
          break;
        case 'mute_on':
          if ($Group->isMuted($group->id)) $GroupList[] = $group->id;
          break;
        case 'mute_off':
          if (!$Group->isMuted($group->id)) $GroupList[] = $group->id;
          break;
        default: $GroupList[] = $uroup->id;
      }
    }
  }

  $GROUP_LIST = $GroupList;
  Render('partials','grouplist');
}

?>
