<?php
/*SCALABILITY UPDATED OK*/
class Validate
{
  function email($address)
  {
    if (preg_match("/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/", $address)) return true;
    else return false;
  }

  function password($pw)
  {
    if (strlen($pw)>5) return true;
    else return false;
    //if (preg_match("/^[%!$*A-Za-z0-9-]+$/",$pw) && strlen($pw)>4 && strlen($pw)<21) return true;
    //else return false;
  }

  function username($name)
  {
    if (preg_match("/^[_@A-Za-z0-9-]+$/",$name) && strlen($name)>2 && strlen($name)<21) return true;
    else return false;
  }

  function url($url)
  {
    // still needs some work - allows illegal characters
    if (strlen($url)<256 && preg_match('/^(s?https?:\/\/)?[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})[\/\?#]?([A-Za-z0-9-_\/\?#\$\.!\*\'\(\),%=])*$/',$url)) return true;
    else return false;
  }

  function protocol($url)
  {
    if (preg_match('/^(s?https?:\/\/)/',$url)) return $url;
    else return 'http://'.$url;
  }

}

?>