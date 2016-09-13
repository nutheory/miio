<?php
// database config & routines

class DB
{
  function __construct($obj,$value,$shard=null)
  {
    switch($obj)
    {
      case 'user': global $USER_DB; $DB = $USER_DB; break;
      case 'userindex': global $USER_INDEX; $DB = $USER_INDEX; break;
      case 'group': global $GROUP_DB; $DB = $GROUP_DB; break;
      case 'groupindex': global $GROUP_INDEX; $DB = $GROUP_INDEX; break;
      case 'post': global $POST_DB; $DB = $POST_DB; break;
      case 'postindex': global $POST_INDEX; $DB = $POST_INDEX; break;
      default:
        log_write("ERROR in DB::__construct: Unknown type '$obj' submitted with value: '$value'");
        $this->dblink = null;
        return false;
        //die;
    }

    if ($value=='') $value='a';
    if (is_null($shard)) $shard = self::$shard[$obj][substr($value,-1)];
    $link = mysql_connect($DB[$shard]['host'],MIIO_DB_USER,MIIO_DB_PASSWORD);
    if (!$link)
    {
      log_write("ERROR in DB::__construct: Can't connect to shard:'$shard' host:'".$DB[$shard]['host']."' for type:'$obj'\n".PADLOG.mysql_error()."\n");
      // TODO: need better error handling
      die;
    }
    $db = mysql_select_db($DB[$shard]['database'],$link);
    if (!$db)
    {
      log_write("ERROR in DB::__construct: Can't select database:'".$DB[$shard]['database']."' shard:'$shard' host:'".$DB[$shard]['host']."' for type:'$obj'\n".PADLOG.mysql_error()."\n".print_r(debug_backtrace(),true));
      // TODO: need better error handling
      die;
    }
    $this->dblink = $link;
  }

/******************************** SHARD HASHES ********************************/

  static private $shard = array
  (
    'user' => array
    (
      'a'=>'0', 'b'=>'1', 'c'=>'0', 'd'=>'1',
      'e'=>'0', 'f'=>'1', 'g'=>'0', 'h'=>'1',
      'i'=>'0', 'j'=>'1', 'k'=>'0', 'l'=>'1',
      'm'=>'0', 'n'=>'1', 'o'=>'0', 'p'=>'1'
    ),

    'userindex' => array
    (
      'a'=>'0', 'b'=>'1', 'c'=>'0', 'd'=>'1',
      'e'=>'0', 'f'=>'1', 'g'=>'0', 'h'=>'1',
      'i'=>'0', 'j'=>'1', 'k'=>'0', 'l'=>'1',
      'm'=>'0', 'n'=>'1', 'o'=>'0', 'p'=>'1'
    ),

    'group' => array
    (
      'a'=>'0', 'b'=>'1', 'c'=>'0', 'd'=>'1',
      'e'=>'0', 'f'=>'1', 'g'=>'0', 'h'=>'1',
      'i'=>'0', 'j'=>'1', 'k'=>'0', 'l'=>'1',
      'm'=>'0', 'n'=>'1', 'o'=>'0', 'p'=>'1'
    ),

    'groupindex' => array
    (
      'a'=>'0', 'b'=>'1', 'c'=>'0', 'd'=>'1',
      'e'=>'0', 'f'=>'1', 'g'=>'0', 'h'=>'1',
      'i'=>'0', 'j'=>'1', 'k'=>'0', 'l'=>'1',
      'm'=>'0', 'n'=>'1', 'o'=>'0', 'p'=>'1'
    ),

    'post' => array
    (
      'a'=>'0', 'b'=>'1', 'c'=>'2', 'd'=>'3',
      'e'=>'0', 'f'=>'1', 'g'=>'2', 'h'=>'3',
      'i'=>'0', 'j'=>'1', 'k'=>'2', 'l'=>'3',
      'm'=>'0', 'n'=>'1', 'o'=>'2', 'p'=>'3'
    ),

    'postindex' => array
    (
      'a'=>'0', 'b'=>'1', 'c'=>'2', 'd'=>'3',
      'e'=>'0', 'f'=>'1', 'g'=>'2', 'h'=>'3',
      'i'=>'0', 'j'=>'1', 'k'=>'2', 'l'=>'3',
      'm'=>'0', 'n'=>'1', 'o'=>'2', 'p'=>'3'
    ),

    'id' => 0

  );

  static public $shardlist = array
  (
    'user' => array ('0','1'),
    'group' => array ('0','1')
  );


/****************************** DATA  WHITELISTS ******************************/

  static private $idtypes = array
  (
    // defines objects that can use ID types
    'user'=>'userid',
    'group'=>'groupid',
    'post'=>'postid',
    'alert'=>'alertid'
  );

  static private function incrementid($id)
  {
    $vals = 'abcdefghijklmnop';
    if (strlen($id)<1)
    {
      return 'a';
    }
    $ch = substr($id,-1);
    $str = substr($id,0,-1);
    $pos = strpos($vals,$ch)+1;
    if ($pos>=strlen($vals))
    {
      $newch = 'a';
      $str = self::incrementid($str);
    }
    else
    {
      $newch = substr($vals,$pos,1);
    }
    $newid = $str.$newch;
    return $newid;
  }

/******************************* MISC FUNCTIONS *******************************/

  function getID($type)
  {
    if (array_key_exists($type,self::$idtypes))
    {
      $table = self::$idtypes[$type];
      $sql1 = "LOCK TABLES $table WRITE";
      $sql2 = "SELECT $table FROM $table WHERE id=0";
      $sql3a = "UPDATE $table SET $table='";
      $sql3b = "' WHERE id=0";
      $sql4 = "UNLOCK TABLES";

      global $ID_DB;
      $shard = self::$shard['id'];
      $link = mysql_connect($ID_DB[$shard]['host'],MIIO_DB_USER,MIIO_DB_PASSWORD);
      if (!$link)
      {
        log_write("ERROR in DB::getID: Can't connect to host:'".$ID_DB[$shard]['host']."' for type:'id'\n".PADLOG.mysql_error()."\n");
        // TODO: need better error handling
        die;
      }
      $db = mysql_select_db($ID_DB[$shard]['database'],$link);
      if (!$db)
      {
        log_write("ERROR in DB::getID: Can't select database:'".$ID_DB[$shard]['database']."' host:'".$ID_DB[$shard]['host']."' for type:'id'\n".PADLOG.mysql_error()."\n");
        // TODO: need better error handling
        die;
      }

      // lock tables
      $res1 = mysql_query($sql1,$link);
      if (!$res1) log_write("ERROR in DB::getID\n".PADLOG."$sql1\n".PADLOG.mysql_error()."\n");
      // get old ID
      $res2 = mysql_query($sql2,$link);
      if (!$res2) log_write("ERROR in DB::getID\n".PADLOG."$sql2\n".PADLOG.mysql_error()."\n");
      $row = mysql_fetch_assoc($res2);
      $id = $row[$table];
      // calculate new ID
      $newid = self::incrementid($id);
      // save new ID
      $res3 = mysql_query($sql3a.$newid.$sql3b,$link);
      if (!$res3) log_write("ERROR in DB::getID\n".PADLOG.$sql3a.$newid.$sql3b."\n".PADLOG.mysql_error()."\n");
      // unlock tables
      $res4 = mysql_query($sql4,$link);
      if (!$res4) log_write("ERROR in DB::getID\n".PADLOG."$sql4\n".PADLOG.mysql_error()."\n");
      // return new ID
      return $newid;
    }
    else log_write("ERROR in DB::getID: Unknown ID type '$type'");
    return false;
  }

/******************************** IO FUNCTIONS ********************************/

  function query($sql)
  {
    $res = mysql_query($sql,$this->dblink);
    if (!$res) log_write("ERROR in DB::query\n".PADLOG."$sql\n".PADLOG.mysql_error()."\n");
    return $res;
  }

  function countRows($table,$condition)
  {
    $sql = "SELECT COUNT(*) FROM $table WHERE $condition";
    $res = mysql_query($sql,$this->dblink);
    if (!$res) log_write("ERROR in DB::countRows\n".PADLOG."$sql\n".PADLOG.mysql_error()."\n");
    $row = mysql_fetch_assoc($res);
    return $row['COUNT(*)'];
  }

  function get($field,$table,$condition)
  {
    $sql = "SELECT $field FROM $table WHERE $condition";
    $res = mysql_query($sql,$this->dblink);
    if (!$res) log_write("ERROR in DB::get\n".PADLOG."$sql\n".PADLOG.mysql_error()."\n");
    if (mysql_num_rows($res)!=1) return false;
    $row = mysql_fetch_assoc($res);
    return $row[$field];
  }

  static function getUnique($field,$table,$condition)
  {
    $match = array();
    $conn = new DB('userindex',null,'0');
    if (!$conn) log_write("ERROR in DB::getUnique\n".PADLOG.mysql_error()."\n");
    $sql = "SELECT $field FROM $table WHERE $condition";
    $res = $conn->query($sql);
    if (!$res) log_write("ERROR in DB::getUnique\n".PADLOG."$sql\n".PADLOG.mysql_error()."\n");
    while ($row = mysql_fetch_assoc($res)) $match[] = $row[$field];
    if (count($match)!=1) return false;
    else return $match[0];
  }

}

?>