<?
  $split = ceil(count(Options::$category)/5);
  $cnt = 1;
  $col = 0;
  $table = array();
  for ($id=1;$id<count(Options::$category);$id++)
  {
    $table[$cnt][$col] = array('id'=>$id,'name'=>Options::$category[$id],'count'=>Options::countCategory($c));
    $cnt++;
    if ($cnt > $split)
    {
      $col++;
      $cnt=1;
    }
  }
?>
<table class="group_categories">
  <?
    foreach ($table as $row)
    {
      echo "<tr>";
      for ($c=0;$c<=$col;$c++)
      {
        if ($row[$c]['id']) echo "<td><a href='#' onclick='return Groups.GetCategory(".$row[$c]['id'].",\"".$row[$c]['name']."\");'>".$row[$c]['name']." (".$row[$c]['count'].")</a></td>";
      }
      echo "</tr>\n";
    }
  ?>
</table>