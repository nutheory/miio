<? global $User, $Profile, $ISME, $PRIVATE; ?>
<?
if ($User==$Profile) $ISME = true;
else
{
  if ($Profile->visibility==Enum::$visibility['private'] && !$User->isFollowing($Profile->id)) $PRIVATE = true;
  if ($User->isBlockedBy($Profile->id) || $Profile->isBlockedBy($User->id)) $BLOCKED = true;
}
$MIIO = $Profile->id=='a';

$fLink = LOC."members/profile/".$Profile->id;
$fText = "View Miio Profile for <?= $Profile->username; ?>";
?>
<link href="css/profile.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/profile.js"></script>

<link href="css/messagelist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/messagelist.js"></script>
<link href="css/userlist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/userlist.js"></script>
<link href="css/messageform.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/messageform.js"></script>
<link href="css/profile_albums.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/profile_albums.js"></script>
<link href="css/profile_description.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/profile_description.js"></script>
<link href="css/profile_manage_subscription.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/profile_manage_subscription.js"></script>
<link href="css/profile_report_member.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/profile_report_member.js"></script>

<script type="text/javascript" src="js/profile_friends.js"></script>
<script type="text/javascript" src="js/profile_subscriptions.js"></script>
<script type="text/javascript" src="js/profile_subscribers.js"></script>
<script type="text/javascript" src="js/profile_groups.js"></script>
<script type="text/javascript" src="js/profile_timeline.js"></script>

<?
$COUNTRIES = Places::get_countries();
?>
<script type="text/javascript">
  var countries = [];
  <?
  for($i=0;$i<count($COUNTRIES);$i++)
  {
    echo "countries[$i] = \"".$COUNTRIES[$i]['name']."\";\n";
  }
  ?>
  var states = [];
  var cities = [];
</script>

<table>
  <tr>
    <td id="left_col">
      <div class="profile_photo">
        <? if ($ISME) { ?>
          <img src="<?= $Profile->getProfilePhoto() ?>" height=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> width=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> id="profile_photo" alt='photo' onclick="Profile.Navigate('profile_description');">
        <? } else { ?>
          <img src="<?= $Profile->getProfilePhoto() ?>" height=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> width=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> id="profile_photo" alt='photo' onclick="return Profile.Navigate('profile_timeline_sent');">
        <? } ?>
      </div>

      <? if (!$PRIVATE && !$BLOCKED) { ?>
        <div class="menu_box">
          <ul class="left_nav">
            <li class='home'>
              <div>
                <h2 id="profile_username"><?= $Profile->username ?></h2>
              </div>
            </li>
            <? if (!$ISME) { ?>
              <li><div id="profile_timeline" onclick="Profile.Navigate(this)">Timeline</div>
                <ul id="nav_profile_timeline" style="display:none">
                  <li><div id="profile_timeline_sent" onclick="Profile.Navigate(this)">Messages Sent</div></li>
                  <li><div id="profile_timeline_rsent" onclick="Profile.Navigate(this)">Replies Sent</div></li>
                  <li><div id="profile_timeline_ssent" onclick="Profile.Navigate(this)">Shares Sent</div></li>
                  <li><div id="profile_timeline_received" onclick="Profile.Navigate(this)">Messages Received</div></li>
                  <li><div id="profile_timeline_rreceived" onclick="Profile.Navigate(this)">Replies Received</div></li>
                  <li><div id="profile_timeline_sreceived" onclick="Profile.Navigate(this)">Shares Received</div></li>
                </ul>
              </li>
            <? } ?>
            <li><div id="profile_description" onclick="Profile.Navigate(this)">About</div></li>
            <li><div id="profile_albums" onclick="Profile.Navigate(this)">Photo albums (<span id="profile_album_count"><?= count($Profile->albums) ?></span>)</div></li>
            <li><div id="profile_friends" onclick="Profile.Navigate(this)">Friends (<span id="profile_friend_count"><?= count($Profile->friends) ?></span>)</div></li>
            <li><div id="profile_followers" onclick="Profile.Navigate(this)">Followers (<span id="profile_follower_count"><?= count($Profile->followers) ?></span>)</div></li>
            <li><div id="profile_following" onclick="Profile.Navigate(this)">Following (<span id="profile_following_count"><?= count($Profile->following) ?></span>)</div></li>
            <li><div id="profile_groups" onclick="Profile.Navigate(this)">Groups (<span id="profile_group_count"><?= count($Profile->public_groups) ?></span>)</div></li>
          </ul>

          <h2>Following</h2>
          <?
            global $PHOTOCOLLAGE;
            $PHOTOCOLLAGE = $Profile->getFollowing(false,true);
            Render('partials','photocollage');
          ?>

          <? if ($Profile->visibility==Enum::$visibility['public']) { ?>
            <h2>Share this Profile</h2>
            <? Render ('partials','foreignshare'); ?>
          <? } ?>

          <h2>Profile URL</h2>
          <div class="copy">
            <input type="text" value="<?= LOC ?><?= $Profile->username; ?>">
          </div>

        </div>
      <? } ?>
    </td>

    <td id="center_col">
      <div id="content_div" class="content_with_rcol" onmouseover="Profile.PauseUpdates()" onmouseout="Profile.ResumeUpdates()">
        <? if (!$MIIO) { ?>
          <div class="top_links">
            <? if ($ISME) { ?>
              Hey, this is you!
            <? } else if (LOGGEDIN && CONFIRMED) { ?>
              <span class="link">
                <? if ($Profile->isBlockedBy($User->id)) { ?>
                  <a id="block_member" href="#" onclick="return Profile.Block('<?= $Profile->id ?>','<?= $Profile->username ?>')" style="display:none">Block</a>
                  <span id="unblock_member">
                    Blocked
                    &nbsp;
                    (<a href="#" onclick="return Profile.UnBlock('<?= $Profile->id ?>','<?= $Profile->username ?>')">Remove block</a>)
                  </span>
                <? } else { ?>
                  <a id="block_member" href="#" onclick="return Profile.Block('<?= $Profile->id ?>','<?= $Profile->username ?>')">Block</a>
                  <span id="unblock_member" style="display:none">
                    Blocked
                    &nbsp;
                    (<a href="#" onclick="return Profile.UnBlock('<?= $Profile->id ?>','<?= $Profile->username ?>')">Remove block</a>)
                  </span>
                <? } ?>
              </span>

              <span class="link">
                <? if ($User->hasReportedUser($Profile->id)) { ?>
                  <a id="report_link" href="#" onclick="return Profile.Report(this,'<?= $Profile->id ?>')" style="display:none">Report</a>
                  <span id="reported_text">Reported</span>
                <? } else { ?>
                  <a id="report_link" href="#" onclick="return Profile.Report(this,'<?= $Profile->id ?>')">Report</a>
                  <span id="reported_text" style="display:none">Reported</span>
                <? } ?>
              </span>
            <? } ?>
          </div>
        <? } ?>

        <div class="username">
          <?= $Profile->username ?>
          <?
            if ($Profile->show_name)
            {
              if ($Profile->name!= '') echo "<span>($Profile->name)</span>";
            }
          ?>
          <? if ($Profile->visibility==Enum::$visibility['private']) { ?><img src='images/private.png' alt='Private'><? } ?>
        </div>
        <? if ($User->is_super) { ?>
          <div class="atq_rfq">
            <? if ($Profile->featured_status == Enum::$featured_status['normal']){?>
              <a href="#" onclick="return Profile.QueueFeatured('<?= $Profile->id ?>')" id="queue_featured">Add to Queue</a>
              <span id="queued_featured" style="display:none">Added to Queue</span>
            <? } else if ($Profile->featured_status==Enum::$featured_status['queued']) { ?>
              <span id="queued_featured">Added to Queue</span>
            <? } else { ?>
              <span id="queued_featured">Featured</span>
            <? } ?>
            &nbsp;&nbsp;&nbsp;
            <a href="#" onclick="return Profile.Suspend('<?= $Profile->id ?>','<?= $Profile->username ?>')" id="suspend_user">Suspend User</a>
            <span id="suspended_user" style="display:none">User Suspended</span>
          </div>
        <? } ?>
        <? if (!$ISME) { ?>
          <? if (!LOGGEDIN) { ?>
            <div class="ls_actionlinks">
              Miio is the new place to meet, talk and share.
              <a href="user/login">Login</a> or <a href="signup">Sign up</a>
              to connect with <?= $Profile->username ?> - It's free and anyone can join.
            </div>
          <? } else if (!CONFIRMED) { ?>
            <div class="ls_actionlinks">
              <a href="signup/confirm">Confirm your account</a> to Follow <?= $Profile->username ?>
            </div>
          <? } else { ?>
            <div class="actionlinks">
              <? if ($User->isFollowing($Profile->id)) { ?>
                <span id="status_following" class="status">
                  Following
                </span>
                <span id="status_pending" class="status" style="display:none">
                  Follow request pending
                </span>
                <span id="status_not_following" style="display:none" class="status">
                  Not following
                </span>
                <? if (!$MIIO) { ?>
                  <a href="#" onclick="return Profile.Unsubscribe('<?= $Profile->id ?>','<?= $Profile->username ?>')" id="unfollow_link">Stop Following</a>
                  <? if ($Profile->visibility==Enum::$visibility['private']) { ?>
                    <a href="#" onclick="return Profile.RequestSubscription('<?= $Profile->id ?>')" id="follow_link" style="display:none">Ask to Follow</a>
                  <? } else { ?>
                    <a href="#" onclick="return Profile.Subscribe('<?= $Profile->id ?>')" id="follow_link" style="display:none">Follow</a>
                  <? } ?>
                <? } ?>
                <a href="#" onclick="return Profile.Manage(this,'<?= $Profile->id ?>')" id="follow_preferences">Manage</a>
              <? } else { ?>
                <span id="status_following" class="status" style="display:none">
                  Following
                </span>
                <? if ($User->followRequested($Profile->id)) { ?>
                  <span id="status_pending" class="status">
                    Follow request pending
                  </span>
                  <span id="status_not_following" class="status" style="display:none">
                    Not following
                  </span>
                <? } else { ?>
                  <span id="status_pending" class="status" style="display:none">
                    Follow request pending
                  </span>
                  <span id="status_not_following" class="status">
                    Not following
                  </span>
                <? } ?>
                <? if ($User->isBlockedBy($Profile->id)) { ?>
                  <span class="blocked">Blocked</span>
                <? } else { ?>
                  <a href="#" onclick="return Profile.Unsubscribe('<?= $Profile->id ?>','<?= $Profile->username ?>')" id="unfollow_link" style="display:none">Stop Following</a>
                  <? if ($Profile->visibility==Enum::$visibility['private']) { ?>
                    <? if ($User->followRequested($Profile->id)) { ?>
                      <a href="#" onclick="return Profile.RequestSubscription('<?= $Profile->id ?>')" id="follow_link" style="display:none">Ask to Follow</a>
                    <? } else { ?>
                      <a href="#" onclick="return Profile.RequestSubscription('<?= $Profile->id ?>')" id="follow_link">Ask to Follow</a>
                    <? } ?>
                  <? } else { ?>
                    <a href="#" onclick="return Profile.Subscribe('<?= $Profile->id ?>')" id="follow_link">Follow</a>
                  <? } ?>
                  <a href="#" onclick="return Profile.Manage(this,'<?= $Profile->id ?>')" id="follow_preferences" style="display:none">Manage</a>
                <? } ?>
              <? } ?>
            </div>
          <? } ?>
        <? } ?>

        <? if (!$PRIVATE && !$BLOCKED) { ?>
          <? if (!$ISME && LOGGEDIN) Render("partials", "messageform"); ?>

          <div class="timeline_header">
            <div id="content_header"></div>
            <? Render('partials','pausecounter'); ?>
            <div class="filter_button">
              <button id="show_timeline_filters" class="short_toggle" onclick="return Messages.ShowAllFilters();">Filters</button>
              <button id="hide_timeline_filters" class="short_toggle_sel" onclick="return Messages.HideAllFilters();" style="display:none">Filters</button>
            </div>

            <div class="clear"></div>
          </div>

          <div id="timeline_filters" style="display:none">
            <? Render("partials","messagefilters"); ?>

            <? Render("partials","messagefilter"); ?>

            <? Render("partials","userfilter"); ?>
          </div>
          <div class="profile_update_counter">
            <? Render('partials','updatecounter'); ?>
          </div>
        <? } else if ($PRIVATE) { ?>
          <? if (LOGGEDIN) { ?>
            <div id="profile_content"></div>
            <div id="private_content">
              <? if ($User->followRequested($Profile->id)) { ?>
                <p><?= $Profile->username ?>'s profile is private. You have already asked to follow <?= $Profile->username ?>. Your follow request is pending.</p>
              <? } else { ?>
                <p><?= $Profile->username ?>'s profile is private. If you know <?= $Profile->username ?>, click the "Ask to Follow" button. If <?= $Profile->username ?> accepts your request you can follow <?= $Profile->username ?>'s messages on Miio.</p>
                <div class="follow_buttons">
                  <button class="short_button" onclick="return Profile.RequestSubscription('<?= $Profile->id ?>')" id="follow_button">Ask to Follow</button>
                </div>
              <? } ?>
            </div>
            <div id="sub_requested" style="display:none">Your follow request was sent</div>
          <? } else { ?>
            <div id="private_content">
              <p><?= $Profile->username ?>'s profile is private.</p>
            </div>
          <? } ?>
        <? } ?>

        <? if ($User->isBlockedBy($Profile->id)) { ?>
          <div id="profile_content">
            <div class="blocked">Sorry, <?= $Profile->username ?> has blocked you.</div>
          </div>
        <? } else if ($Profile->isBlockedBy($User->id)) { ?>
          <div id="profile_content">
            <div class="blocked">You have blocked <?= $Profile->username ?>.</div>
          </div>
        <? } else { ?>
          <div id="profile_content"></div>
        <? } ?>

      </div>
    </td>

    <td id="right_col">
      <!--<img src="filler/google_ads.gif">-->
    </td>
  </tr>
</table>


<script type="text/javascript">
  Profile.IsReported = <?= ($User->hasReportedUser($Profile->id))? 'true' : 'false'; ?>;
<? if (!$BLOCKED) { ?>
  Profile.Init('<?= $Profile->id ?>','<?= $Profile->username ?>',<?= ($ISME) ? 1 : 0 ?>,<?= ($PRIVATE) ? 1 : 0 ?>);
<? } ?>
</script>