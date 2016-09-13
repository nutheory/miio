<? global $MESSAGES, $User, $Profile, $MESSAGE_FILTER, $CONTROLLER, $PAGE, $GET, $LOGGEDIN; ?>
<?
  include 'views/partials/message.php';
  $isfiltered = ($MESSAGE_FILTER != "");
  $filterlength = strlen($MESSAGE_FILTER);
  $messages = $MESSAGES;

  $messagetype = Options::$messagetype;
  $scrolled_divs = "";
?>

<link href="css/trends.css" rel="stylesheet" type="text/css">
<link href="css/message.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/topic.js"></script>
<script type="text/javascript" src="js/messagelist.js"></script>
<script type="text/javascript" src="js/user.js"></script>
<script type="text/javascript" src="js/_lib.js"></script>

<div>
  <? Render("partials","search"); ?>
  <div id="trending">
      <p>Trending Topics:</p>
      <p>
          <?
            $t = new Topics();
            //run this if no index being built:
            //createindex();
            //run this url to initialize: http://localhost/miio/queue/trendprocess.php
            //$t->init();
            //run this periodically if no process is running, and there is incoming traffic
            //$t->updateCurrentTrends();
            $t1 = $t->getLatestTrends(12);

            $i = 0;
            foreach ($t1 as $trend3)
            {
              $i++;
              echo "<a href='#' onclick='return Topic.GetPosts(\"".$trend3->Key."\");' id='topic_".$trend3->Key."'>".$trend3->Key."</a> ";
              if($i != 12) echo ", ";
              if($i == 6) echo "</p><p>";
            }
          ?>
        </p>
    </div>  
  <br><br>

  <div id="Posts">
    <table id="message">
      <tr>
        <td id="right_col">
            <!--Posts-->
            <div id="trend_posts">
            </div>
        </td>
      </tr>
    </table>
  </div>
  
</div>

<script type="text/javascript">
  Topic.Init();
</script>

