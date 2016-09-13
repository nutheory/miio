<? global $PAGE, $PARAMS, $SHOWLIST; ?>
<link href="css/tabs.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/tabs.js"></script>
<link href="css/messagelist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/messagelist.js"></script>
<link href="css/userlist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/userlist.js"></script>

<? if (!$SHOWLIST) { ?>
  <?
    $split = ceil(count(Options::$category)/5);
    $cnt = 1;
    $col = 0;
    $table = array();
    for ($c=1;$c<count(Options::$category);$c++)
    {
      $table[$cnt][$col] = array('id'=>$c,'name'=>Options::$category[$c],'count'=>Options::countCategory($c));
      $cnt++;
      if ($cnt > $split)
      {
        $col++;
        $cnt=1;
      }
    }
  ?>

  <div class="categories" id="category_list">
    <? if ($PAGE=='groups') { ?>
      <h2>
        <img src="images/groupsTabs.png">
        Groups
        <span class="groupnote">
          <? if (LOGGEDIN) { ?>
            Browse groups, <a href="user#groups/create">create</a> a group, or <a href="search/group">search</a> groups
          <? } else { ?>
            Browse groups or <a href="search/group">search</a> groups
          <? } ?>
        </span>
      </h2>
    <? } else { ?>
      <h2>
        <img src="images/categoriesTabs.png">
        Categories
        <span class="groupnote">Browse messages by category</span>
      </h2>
    <? } ?>

    <table class="categories">
      <?
        foreach ($table as $row)
        {
          echo "<tr>";
          for ($c=0;$c<=$col;$c++)
          {
            if ($row[$c]['id']) { ?>
              <td><a href="tabs/<?= $PAGE ?>/<?= $row[$c]['id'] ?>"><?= $row[$c]['name'] ?><? if ($PAGE=='groups') echo " (" . $row[$c]['count'] . ")"; ?></a></td>
            <? }
          }
          echo "</tr>\n";
        }
      ?>
    </table>
  </div>
<? } else { ?>
  <table><tr><td id="left_col">&nbsp;</td><td id="center_col">
    <div id='tab_container'>
      <? if ($PAGE=='categories') { ?>
        <div id="category_tabs">
          <div class="header">
            <h3>
              <img src="images/categoriesTabs.png">
              Categories
              &raquo;
              <span id="category_name"><?= Options::$category[$PARAMS] ?></span>
            </h3>
            <p><a href='search/all'>Search</a> the public timeline</p>
          </div>

          <div class="tabs">
            <a href="#" id="tab_newest" onclick="return Tabs.SelectTab(this,'newest');">Newest</a>
            <a href="#" id="tab_discussed" onclick="return Tabs.SelectTab(this,'discussed');">Most Discussed</a>
            <a href="#" id="tab_shared" onclick="return Tabs.SelectTab(this,'shared');">Most Shared</a>
            <? Render('partials','pausecounter'); ?>
          </div>

          <div id="tab_messages"></div>
        </div>
      <? } else { ?>
        <div id="group_tabs" <? if (!($SHOWLIST && $PAGE=='groups')) echo 'style="display:none"'; ?>>
          <div class="header">
            <h3>
              <img src="images/groupsTabs.png">
              Groups
              &raquo;
              <span id="category_name"><?= Options::$category[$PARAMS] ?></span>
            </h3>
            <p><a href='search/group'>Search</a> groups</p>
          </div>

          <div class="tabs">
            <a href="#" id="tab_newestg" onclick="return Tabs.SelectTab(this,'newestg');">Newest</a>
            <a href="#" id="tab_popular" onclick="return Tabs.SelectTab(this,'popular');">Most Popular</a>
          </div>

          <div id="tab_groups"></div>
        </div>
      <? } ?>
    </div>
  </td><td id="right_col"><!--<img src="filler/google_ads.gif">--></td></tr></table>
<? } ?>

<script type="text/javascript">
  Tabs.Init('<?= $PAGE ?>','<?= $PARAMS ?>');
</script>