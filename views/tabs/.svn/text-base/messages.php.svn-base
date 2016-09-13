<? global $CONTROLLER, $PAGE; ?>
<?
$tabopts = array (
                    "all"=>array("name"=>"Public Timeline","id"=>"all","img"=>"timeline","text"=>"<a href='user#timeline/message'>Send</a> a message or <a href='search/all'>Search</a> the public timeline","large_image"=>"<img src='images/messagetypes_transparent/timeline36.png'>"),
                    "text"=>array("name"=>"Texts","id"=>"text","img"=>"message","text"=>"<a href='user#timeline/message'>Send</a> a text or <a href='search/text'>Search</a> public texts","large_image"=>"<img src='images/messagetypes_transparent/texts36.png'>"),
                    "photo"=>array("name"=>"Photos","id"=>"photo","img"=>"photo","text"=>"<a href='user#timeline/photo'>Send</a> a photo or <a href='search/photo'>Search</a> public photos","large_image"=>"<img src='images/messagetypes_transparent/photos36.png'>"),
                    "video"=>array("name"=>"Videos","id"=>"video","img"=>"video","text"=>"<a href='user#timeline/video'>Send</a> a video or <a href='search/video'>Search</a> public videos","large_image"=>"<img src='images/messagetypes_transparent/videos36.png'>"),
                    "links"=>array("name"=>"Links","id"=>"links","img"=>"link","text"=>"<a href='user#timeline/link'>Send</a> a link or <a href='search/links'>Search</a> public links","large_image"=>"<img src='images/messagetypes_transparent/links36.png'>"),
                    "review"=>array("name"=>"Reviews","id"=>"review","img"=>"review","text"=>"<a href='user#timeline/review'>Create</a> and send a review or <a href='search/review'>Search</a> public reviews","large_image"=>"<img src='images/messagetypes_transparent/reviews36.png'>"),
                    "question"=>array("name"=>"Questions","id"=>"question","img"=>"question","text"=>"<a href='user#timeline/question'>Ask</a> a question or <a href='search/question'>Search</a> public questions","large_image"=>"<img src='images/messagetypes_transparent/questions36.png'>"),
                    "location"=>array("name"=>"Location Update","id"=>"location","img"=>"location","text"=>"<a href='user#timeline/location'>Send</a> a location update or <a href='search/location'>Search</a> location updates","large_image"=>"<img src='images/messagetypes_transparent/locations36.png'>"),
                    "rss"=>array("name"=>"RSS","id"=>"rss","img"=>"rss","text"=>"<a href='user#rss'>Publish</a> your RSS feed to Miio or <a href='search/rss'>Search</a> RSS feeds","large_image"=>"<img src='images/messagetypes_transparent/rss36.png'>")
                 );
?>

<link href="css/tabs.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/tabs.js"></script>
<link href="css/messagelist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/messagelist.js"></script>

<table><tr><td id="left_col">&nbsp;</td><td id="center_col">
  <div id="middle_content">
    <div class="message_filters">
      <? foreach ($tabopts as $tab=>$opts) { ?>
        <? if ($tab==$PAGE) { ?>
          <span>
            <img src="images/messagelist/<?= $opts['img'] ?>_filter_sel.png" title="<?= $opts['name'] ?>">
          </span>
        <? } else { ?>
          <a href="tabs/<?= $tab ?>">
            <img src="images/messagelist/<?= $opts['img'] ?>_filter.png" title="<?= $opts['name'] ?>">
          </a>
        <? } ?>
      <? } ?>
    </div>

    <div id="tab_container" onmouseover="Tabs.PauseUpdates()" onmouseout="Tabs.ResumeUpdates()">

      <div class="header">
        <h3><?= $tabopts[$PAGE]['large_image'] ?><?= $tabopts[$PAGE]['name'] ?></h3>
        <p><?= $tabopts[$PAGE]['text'] ?></p>
      </div>


      <div class="tabs">
        <? Render('partials','pausecounter'); ?>
        <a href="#" id="tab_newest" onclick="return Tabs.SelectTab(this,'newest');">Newest</a>
        <a href="#" id="tab_discussed" onclick="return Tabs.SelectTab(this,'discussed');">Most Discussed</a>
        <a href="#" id="tab_shared" onclick="return Tabs.SelectTab(this,'shared');">Most Shared</a>
      </div>

      <? Render('partials','updatecounter'); ?>
      <div id="tab_messages"></div>
    </div>
  </div>
</td><td id="right_col"><!--<img src="filler/google_ads.gif">--></td></tr></table>



<script type="text/javascript">
  Tabs.Init('<?= $PAGE ?>');
</script>