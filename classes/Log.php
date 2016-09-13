<?php
class Log
{
  private $logto, $logfile;

  public $isupdate = false;

  public function __construct($logtype='')
  {
    if ($_SERVER['REQUEST_URI']=='/miio/user/timeline' || $_SERVER['REQUEST_URI']=='/miio/ajax/user_update')
    {
      $this->isupdate = true;
      return false;
    }
    $today = date('Y-m-d');
    $this->logfile = "/logs/$logtype"."_$today.log";
    if (!file_exists($this->logfile))
    {
      touch($this->logfile);
      chmod($this->logfile,0666);
    }
    $this->logto = fopen($this->logfile,"a");

    // write start of request:
    $logstr = "--- START REQUEST\n".PADLOG."--- FROM: ".$_SERVER['REMOTE_ADDR']." REQUESTING: ".$_SERVER['REQUEST_URI'].":".$_SERVER['REMOTE_PORT'];
    $logstr .= "\n".PADLOG."--- USING: ".$_SERVER['HTTP_USER_AGENT']."\n".PADLOG."--- REFERED FROM: ".$_SERVER['HTTP_REFERER']."\n".PADLOG."--- ORIGINAL REQUEST: ".$_SERVER['QUERY_STRING'];
    $this->write($logstr);
  }

  public function write($message)
  {
    if ($this->isupdate) return;
    $now = date('H:i:s');
    $logstring = "$now:  $message \n";
    flock($this->logto,LOCK_EX);
    fwrite($this->logto,$logstring);
    flock($this->logto,LOCK_UN);
  }

  public function __destruct()
  {
    fclose($this->logto);
  }
}

?>