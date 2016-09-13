<?

if (isset($_GET['controller'])) $CONTROLLER = $_GET['controller'];
if (isset($_GET['page'])) $PAGE = $_GET['page'];
if (isset($_GET['params'])) $PARAMS = $_GET['params'];
$SHOW_HTML = true;
// parse additional parameters

$p = explode('?',$_SERVER['REQUEST_URI']);
$GET = array();
if (count($p) > 1)
{
  $gg = explode("&",$p[1]);
  for ($g=0;$g<count($gg);$g++)
  {
    $gs = explode("=",$gg[$g]);
    if (count($gs) > 1) $GET[$gs[0]] = $gs[1];
  }
}

if ((!isset($CONTROLLER)) || ($CONTROLLER == ""))
{
  if (LOGGEDIN)
  {
    $CONTROLLER = "user";
    $PAGE = "";
  }
  else
  {
    $CONTROLLER = "members";
  }
}

if (!file_exists(BASE."controllers/$CONTROLLER.php"))
{
  if (substr($CONTROLLER,0,1)=='_')
  {
    // message page
    $p = Post::getByCode(substr($CONTROLLER,1));
    $CONTROLLER = 'messages';
    $PAGE = 'view';
    $PARAMS = $p;
  }
  else
  {
    // is it a user?
    $usr = User::getByName($CONTROLLER);
    if ($usr)
    {
      if ($usr->is_group)
      {
        $CONTROLLER = 'groups';
        $PAGE = 'view';
      }
      else
      {
        $CONTROLLER = 'members';
        $PAGE = 'profile';
      }
      $PARAMS = $u['id'];
    }
    else
    {
      $PARAMS = $CONTROLLER;
      $CONTROLLER = 'error';
      $PAGE = 'notfound';

    }
  }
}
else if (($CONTROLLER == "ajax") || ($CONTROLLER == "forms") || $GET['isajax'] || $_POST['isajax'])
{
  // don't display HTML
  $SHOW_HTML = false;
  require_once BASE."includes/controller.php";
}

// parse pages if necessary

?>