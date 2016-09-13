<?
// search controller

function all()
{
  Render("search","index");
}

function text()
{
  Render("search","index");
}

function photo()
{
  Render("search","index");
}

function video()
{
  Render("search","index");
}

function links()
{
  Render("search","index");
}

function review()
{
  Render("search","index");
}

function question()
{
  Render("search","index");
}

function group()
{
  Render("search","index");
}

function member()
{
  Render("search","index");
}

function rss()
{
  Render("search","index");
}

function location()
{
  Render("search","index");
}

function get_results()
{
  // declare globals
  global $PARAMS;
  global $User;
  if ($PARAMS=='member')
  {
    global $LIST;
    $LIST = array();
    global $USER_LIST, $KEYWORDS, $KEYARRAY, $LIST_PAGE, $DISPLAY_TYPE, $ISSEARCH;

    if ($_POST['page'] && is_numeric($_POST['page'])) $page = $_POST['page'];
    else $page = 1;
    $LIST['display_type'] = 'short';
    $LIST['search'] = true;
    $LIST['searchstring'] = strtolower(trim(str_replace(',',' ',$_POST['searchval'])));
    $LIST['keywords'] = explode(' ',addslashes($LIST['searchstring']));
    $search_results = User::fullSearch($LIST['keywords'],$page);
    $LIST = array_merge($LIST,$search_results);
    //print_r($LIST);echo "<hr>";
    Render('partials','userlist');
  }
  else if ($PARAMS=='group')
  {
    global $GROUP_LIST, $KEYWORDS, $KEYARRAY, $LIST_PAGE, $DISPLAY_TYPE, $ISSEARCH;

    if ($_POST['page']) $LIST_PAGE = $_POST['page'];
    else $LIST_PAGE = 1;
    $DISPLAY_TYPE = 'short';
    $ISSEARCH=true;

    $KEYWORDS = strtolower(trim(str_replace(',',' ',$_POST['searchval'])));
    $KEYARRAY = explode(' ',$KEYWORDS);
    $GROUP_LIST = Group::getByKeyword($KEYARRAY);
    Render('partials','grouplist');
  }
  else
  {
    global $MESSAGES, $MESSAGE_FILTER;
    // get working variables
    $lastcheck = $_POST['lastcheck'];
    $lastpost = $_POST['lastpost'];
    $now = time();
    $MESSAGE_FILTER = strtolower(trim(str_replace(',',' ',$_POST['searchval'])));
    $isfiltered = false;
    $type = $PARAMS == 'links' ? 'link' : $PARAMS;
    $category = $_POST['category'];
    $Messages = array();

    $PublicPosts = Post::quicksearch($_POST['searchval']);

    foreach ($PublicPosts as $id)
    {
      $post = Post::get($id);
      if
      (
        $post['parent_id']==0 &&
        $post['sharing']=='public' &&
        $post['type']!='share' &&
        ($type=='all' || $post['type']==$type)
      )
      {
        $Messages[] = $post['id'];
      }
    }

    if ($_POST['response']=='status')
    {
      $updates = GetMessageUpdateStatus($Messages,$lastcheck,$lastpost);
      if (count($updates)>0)
      {
        $response = array();
        $response['lastcheck'] = $now;
        $response['updates'] = array_reverse($updates);
        $response['totalmessages'] = count($Messages);
        if ($_POST['mode']) $response['mode'] = $_POST['mode'];
        if ($_POST['init']) $response['init'] = $_POST['init'];
        echo json_encode($response);
      }
      else echo "ok_".$now;
    }
    else if ($_POST['initial_load']==1)
    {
      $MESSAGES = $Messages;
      Render("partials","messagelist");
    }
    else if ($_POST['lastmessage'])
    {
      $updates = GetNextGroupOfMessages($Messages,$_POST['lastmessage']);
      if (count($updates)>0)
      {
        echo json_encode($updates);
      }
      else echo "ok";
    }
    else echo "ok_".$now;
  }
}

?>