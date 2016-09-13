<?php

class Session
{
  static function Get($var)
  {
    global $SESSION;
    return $SESSION[$var];
  }

  static function Set($var,$val)
  {
    global $SESSION;
    $SESSION[$var] = $val;
    cache_set("Session_".$SESSION['id'],$SESSION);
  }

  static function Clear($var)
  {
    global $SESSION;
    unset($SESSION[$var]);
    cache_set("Session_".$SESSION['id'],$SESSION);
  }

  static function GetNewSessionID()
  {
    // need to come up with something better
    $id = str_random_code(10,'mixed_an').(string)time();
    return $id;
  }

  static function Kill()
  {
    global $SESSION;
    // expire current session & remove from cache & memory
    setcookie("MiioSID",'',time()-3600,'/',COOKIE_HOST);
    cache_delete("Session_".$SESSION['id']);
    unset ($SESSION);
  }

  static function StartNew()
  {
    global $SESSION;
    $sessionID = self::GetNewSessionID();
    $SESSION = array("id"=>$sessionID);
    cache_set("Session_".$sessionID,$SESSION);
    setcookie("MiioSID",$sessionID,0,'/',COOKIE_HOST);
    //setcookie("MiioSID",$sessionID,0,'/',COOKIE_HOST,true);
  }

  static function Start()
  {
    global $SESSION;
    if ($_COOKIE["MiioSID"])
    {
      // session cookie is set
      $gotsession = true;
      // check cache ??? maybe we should use database?
      $sessionID = $_COOKIE["MiioSID"];
      $SESSION = cache_get("Session_".$sessionID);
      if (!$SESSION)
      {
        // wasn't in cache: clear old session, expire old cookie
        setcookie("MiioSID",'',time()-3600,'/',COOKIE_HOST);
        $gotsession = false;
      }
    }
    if (!$gotsession)
    {
      // get new session
      self::StartNew();
    }
  }

  static function Restart()
  {
    self::Kill();
    self::StartNew();
  }

}

?>