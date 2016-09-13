<?
/*#####*/
require_once BASE."controllers/$CONTROLLER.php";
if ($PAGE == "") $PAGE = "index";
if ($CONTROLLER=="forms") $PAGE = 'form_'.$PAGE;
if (is_callable($PAGE)) $PAGE();
else if (file_exists(BASE."views/".$CONTROLLER."/$PAGE.php"))
{
  include BASE."views/$CONTROLLER/$PAGE.php";
}
else
{
  echo "<h1>ERROR</h1><h3>Page '$PAGE' for Controller '$CONTROLLER' is not defined</h3>";
  echo "<h1 style='color:red'>FIND A BETTER WAY TO HANDLE ERRORS</h1>";
  echo "<a href='".LOC."'>back to main page</a><br>&nbsp;";
}


?>