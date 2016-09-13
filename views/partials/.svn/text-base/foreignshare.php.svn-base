<? global $Profile, $User, $MESSAGE; ?>
<?
if ($MESSAGE) {
  $message_type = Options::$message_type[$MESSAGE->type];
  $fLink = LOC."messages/view/".$MESSAGE->id;
  $fText = "Check out this ".$message_type;
} else {
  if ($Profile->is_group) {
    $fLink = LOC."groups/view/".$Profile->id;
    $fText = "View Miio Group ".$Profile->username.".";
  } else {
    $fLink = LOC."members/profile/".$Profile->id;
    $fText = "View Miio Profile for ".$Profile->username.".";
  }
}
?>
<div class="foreign_share">

  <ul>
    <li>
      <script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>
      <a href="http://www.facebook.com/share.php?u=<?= $fLink ?>" onclick="return fbs_click()" target="_blank"><img src="logos/favicon/facebook.png" alt="Share on Facebook"></a>
    </li>
    <li>
      <? if($User->twitter_token) { ?>
        <a href="#" onclick="Message.SendTweet()"><img src="logos/favicon/twitter.png" alt=" Tweet this!"></a>
      <? } else { ?>
        <a href="http://twitter.com/home?status=<?= $fText." - ".$fLink ?>" target="_blank"><img src="logos/favicon/twitter.png" alt=" Tweet this!"></a>
      <? } ?>
    </li>
    <!--
    <li>
      <script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=c50d61ae-134e-4e57-8385-6849faf2c3c3&amp;type=website&amp;post_services=friendfeed%2Cmyspace%2Cdigg%2Csms%2Cwindows_live%2Cdelicious%2Cstumbleupon%2Creddit%2Cgoogle_bmarks%2Cybuzz%2Cblinklist%2Ctechnorati%2Cbebo%2Cblogger%2Cyahoo_bmarks%2Csphinn%2Cmixx%2Cpropeller%2Cfark%2Cslashdot%2Cwordpress%2Clinkedin%2Cmeneame%2Ctypepad%2Cnewsvine%2Cxanga%2Clivejournal%2Cfunp%2Cbus_exchange%2Cn4g%2Cyigg%2Ccurrent%2Ccare2%2Ctwackle%2Cdealsplus%2Cblogmarks%2Cfresqui%2Cdiigo%2Cmister_wong%2Csimpy%2Ckirtsy%2Cfaves%2Ctwine%2Caim%2Coknotizie"></script>
    </li
    -->
    <!-- <li>
      <a href="http://twitter.com/home?status=<?= $fText." ".$fLink ?>" target="_blank"><img src="logos/favicon/techmeme.png" alt="Share on Techmeme"></a>
    </li> -->
    <li>
      <a href="http://www.stumbleupon.com/submit?url=<?= $fLink ?>" target="_blank"><img src="logos/favicon/stumbleupon.png" alt="Share with StumbleUpon"></a>
    </li>
    <li>
      <a href="http://reddit.com/submit?url=<?= $fLink ?>&amp;title=<?= $fText?>" target="_blank"><img src="logos/favicon/reddit.png" alt="Share on Reddit"></a>
    </li>
    <li>
      <a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=<?= $fLink ?>&amp;title=<?= $fText ?>" target="_blank"><img src="logos/favicon/googlebookmark.png" alt="Share on Google Bookmarks"></a>
    </li>
    <li>
      <a href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?= $fLink ?>" target="_blank"><img src="logos/favicon/myspace.png" alt="Share on Myspace"></a>
    </li>
    <li>
      <a href="http://buzz.yahoo.com/buzz?targetUrl=<?= $fLink ?>" target="_blank"><img src="logos/favicon/yahoobuzz.png" alt="Share on Yahoo! Buzz"></a>
    </li>
    <li>
      <a href="http://www.tumblr.com/" target="_blank"><img src="logos/favicon/tumblr.png" alt="Share on Tumblr"></a>
    </li>
    <li>
      <a href="http://digg.com/submit?phase=2&amp;url=<?= $fLink ?>&amp;title=<?= $fText ?>" target="_blank"><img src="logos/favicon/digg.png" alt="Share on Digg"></a>
    </li>
    <li>
    <a href="http://www.friendfeed.com/share?title=<?= $fText ?>&amp;link=<?= $fLink ?>" target="_blank"><img src="logos/favicon/friendfeed.png" alt="Share on Friendfeed" /></a>
    </li>
    <li>
    <a href="http://www.bebo.com/c/share?Url=<?= $fLink ?>&amp;Title=<?= $fText ?>" target="_blank"><img src="logos/favicon/bebo.jpg" alt="Share on Bebo" /></a>
    </li>
    <li>
    <a href="http://posterous.com/share?linkto=<?= $fLink ?>&amp;title=Miio&amp;selection=<?= $fText ?>" target="_blank"><img src="logos/favicon/posterous.png" alt="Share on Posterous" /></a>
    </li>
    <li>
    <a href="http://delicious.com/post?url=<?= $fLink ?>&amp;title=<?= $fText ?>" target="_blank"><img src="logos/favicon/delicious.png" alt="Share on Delicious" /></a>
    </li>
    <li class="reset">
    <a href="http://technorati.com/faves?add=<?= $fLink ?>" target="_blank"><img src="logos/favicon/technorati.png" alt="Share on Technorati" /></a>
    </li>
  </ul>
</div>