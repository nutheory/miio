<? global $LIST, $CONTROLLER; ?>
<div class="paging">
<?
  $top_page = ceil($LIST['total']/USERS_PER_PAGE);
  if ($top_page > 1)
  {
    $mid_page = floor(NUM_PAGE_LINKS/2);

    if ($top_page < NUM_PAGE_LINKS+1)
    {
      $start = 1;
      $end = $top_page;
    }
    else
    {
      $start = $LIST['page']-$highest_center;
      if ($start < 1)
      {
        $start = 1;
      }
      else if ($start > $top_page-NUM_PAGE_LINKS+1)
      {
        $start = $top_page-NUM_PAGE_LINKS+1;
      }
      $end = $start + NUM_PAGE_LINKS-1;
    }

    if ($LIST['page'] > 1)
    {
      echo "<a href='#' onclick='return Users.Paginate(\"$CONTROLLER\",1);'>&lt;&lt; first</a> | ";
      $prev = $LIST['page']-1;
      echo "<a href='#' onclick='return Users.Paginate(\"$CONTROLLER\",$prev);'>&lt; prev</a> | ";
    }

    for ($page = $start; $page <= $end; $page++)
    {
      $t = $page*USERS_PER_PAGE;
      if ($page == $LIST['page'])
      {
        echo "$page";
      }
      else
      {
        echo "<a href='#' onclick='return Users.Paginate(\"$CONTROLLER\",$page);'>$page</a>";
      }
      if ($page < $end) echo " | ";
    }

    if ($LIST['page'] < $top_page)
    {
      $next_page = $LIST['page']+1;
      echo " | <a href='#' onclick='return Users.Paginate(\"$CONTROLLER\",$next_page);'>next &gt;</a>";
      echo " | <a href='#' onclick='return Users.Paginate(\"$CONTROLLER\",$top_page);'>last &gt;&gt;</a>";
    }
  }
?>
</div>