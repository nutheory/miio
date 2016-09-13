<?
class logToFile
{
	private $filename;
	public function  __construct($fname) 
	{
        $this->filename = $fname;
    }

   //File will be rewritten if already exists
   public function write($newdata) 
   {
		$f=fopen($this->filename,"w");
		fwrite($f,$newdata."\n");
		fclose($f);  
   }

   public function append($newdata) 
   {
		$f=fopen($this->filename,"a");
		fwrite($f,$newdata."\n");
		fclose($f);  
   }
   
   public function append_array($newdata) 
   {
		$f=fopen($this->filename,"a");
		foreach ($newdata as $item) 
		{
			fwrite($f,$item->Key."(".$item->Count.")\n");
		}
		fclose($f);  
   }
}
?>
