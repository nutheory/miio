<?

function form_logout()
{
  global $User, $LOGGEDIN, $LOC, $SESSION, $Cache, $COOKIE_HOST;
  $User = User::get();
  $LOGGEDIN = false;
  setcookie("MiioSID",'',time()-DAY_IN_SEC,'/',$COOKIE_HOST);
  setcookie('remember',$_COOKIE['remember'],time()-DAY_IN_SEC,'/',$COOKIE_HOST);
  $Cache->delete("Session_".$SESSION['id']);
  $SESSION = array();
  header("Location: $LOC");
}



?>