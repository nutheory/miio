<? global $CONTROLLER, $PAGE, $GET; ?>
<?
  $SearchType = isset($GET['t']) ? $GET['t'] : ""; 
  $SearchVal = isset($GET['q']) ? $GET['q'] : "";
  $st = ($PAGE=="index") ? "all" : $PAGE;
?>
<link href="css/search.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/search.js"></script>
<link href="css/messagelist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/messagelist.js"></script>
<link href="css/userlist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/userlist.js"></script>

<table><tr><td id="left_col">&nbsp;</td><td id="center_col">
  <div id="middle_content">
    <div class="quick_search">
      <div class="head"><img src="images/logo_med.png"></div>
      <div class="search_input">
        <div class="search_select" onclick="Search.SelectType()">
          <span id="search_select">
            <?
              if (($PAGE=="" || $PAGE=="index") && $SearchType=="")
              {
                echo "Public Timeline";
                $searchtype = 'all';
              }
              else
              {
                foreach (Options::$searchtypes as $type=>$value)
                {
                  if ($PAGE==$type || $SearchType==$type) { echo "$value"; $searchtype = $type; }
                  else if ($type=='link' && ($PAGE=='links' || $SearchType=='link')) { echo "$value"; $searchtype = $type; }
                }
              }
            ?>
          </span>
          <input id="search_type" type="hidden" value="<?= $searchtype ?>">
          <div id="search_selectlist" style="display:none">
            <? foreach (Options::$searchtypes as $type=>$value) { ?>
              <? if ($type=='group') echo "<hr>"; ?>
              <a href="#" class="<?= $type ?>" onclick="return Search.SelectTypeOpt('<?= $type ?>','<?= $value ?>');"><?= $value ?></a>
            <? } ?>
          </div>
        </div>
        <input type="text" name="search_value" id="search_value" value="<?= $SearchVal ?>" onkeypress="Forms.Enter(event,this,Search.Search);">
        <button name="search" class="short_button search" id="search" value="search" onclick="Search.Search(this)">Search</button>
      </div>
    </div>
    
    <div id="search_container" style="display:none">  
      <? Render('partials','pausecounter'); ?>
      <div class="header">
        <h3><img src='images/messagetypes_transparent/search36.png'>Search</h3>
        <p><span id="search_where"><?= Options::$searchtypes[$st] ?></span> for <span id="search_phrase"></span></p>
      </div>
      
      <div id="update_counter" style="display:none">
        <span id="updatecounter0" style="display:none"></span>
        <span id="updatecounter1" style="display:none">1 new message. <a href="#" onclick="return Search.Search()">Refresh</a> to view.</span>
        <span id="updatecounterx" style="display:none"><span id="update_count">999</span> new messages. <a href="#" onclick="return Search.Search()">Refresh</a> to view.</span>
      </div>
      
      <div id="search_results"></div>
    </div>
  </div>
</td><td id="right_col"><!--<img src="filler/google_ads.gif">--></td></tr></table>

<script type="text/javascript">
  Search.Init('<?= $SearchVal ?>');
</script>