<? global $User, $AVATAR_URL, $LOGGEDIN, $Profile; ?>
<link href="css/user.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/user.js"></script>

<!-- other stylesheets & javascript that might be needed on this page -->
<link href="css/messagelist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/messagelist.js"></script>
<link href="css/messageform.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/messageform.js"></script>
<link href="css/userlist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/userlist.js"></script>

<link href="css/user_invite.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/user_invite.js"></script>
<link href="css/user_alerts.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/user_alerts.js"></script>
<link href="css/user_settings.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/user_settings.js"></script>
<link href="css/user_creategroup.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/user_creategroup.js"></script>

<link href="css/forms.css" rel="stylesheet" type="text/css">
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

  var sms_providers = [];
  <?
  foreach(Options::$carriers as $key=>$provider)
  {
    echo "sms_providers['$key'] = \"".$provider['name']."\";\n";
  }
  ?>

  var FOLLOWED = [];
  <?
    $flist = array();
    foreach($User->friends as $friend)
    {
      $Friend = User::get($friend);
      $flist[] = $Friend->username;
    }
    $following = array();
    foreach($User->following as $sub)
    {
      $following[] = $sub['followed_id'];
    }
    $contacts = array_unique(array_merge($flist,$following));
    $allcontacts = array();
    foreach($contacts as $friend)
    {
      $Friend = User::get($friend);
      $allcontacts[] = $Friend->username;
    }
    //sort($allcontacts,SORT_STRING);
    natcasesort($allcontacts);
    $allcontacts = array_values($allcontacts);
    for ($c=0;$c<count($allcontacts);$c++)
    {
      echo "FOLLOWED[$c] = '".$allcontacts[$c]."';\n";
    }
  ?>

  var GROUPS = [];
  <?
    $groups = array();
    foreach($User->public_groups as $group)
    {
      $Group = User::get($group['group_id']);
      $groups[] = $Group->username;
    }
    foreach($User->private_groups as $group)
    {
      $Group = User::get($group['group_id']);
      $groups[] = $Group->username;
    }
    natcasesort($groups);
    $groups = array_values($groups);
    for ($g=0;$g<count($groups);$g++)
    {
      echo "GROUPS[$g] = '".$groups[$g]."';\n";
    }
  ?>
</script>


  <table>
    <tr>
      <td id="left_col">
        <div class="menu_box">
          <ul class="left_nav">
            <li class="dash">
              <div>Dashboard</div>
              <hr>
            </li>
            <? $now = microtime(true); ?>
            <li>
              <div id="user_timeline" onclick="User.Navigate(this)">Timeline</div>
                <!--<div id="timeline_count" class="new_count" style="display:none">
                  <span id="timeline_x"></span>
                </div>
              <input id="last_timeline" type="hidden" value="<?= $now ?>">-->
            </li>

            <li>
              <div id="user_received" onclick="User.Navigate(this)">
                Messages to me
                <div id="received_count" class="new_count" style="display:none">
                  <span id="received_x"></span>
                </div>
              </div>
              <input id="last_received" type="hidden" value="<?= $now ?>">
            </li>

            <li>
              <div id="user_rreceived" onclick="User.Navigate(this)">
                Replies to me
                <div id="rreceived_count" class="new_count" style="display:none">
                  <span id="rreceived_x"></span>
                </div>
              </div>
              <input id="last_rreceived" type="hidden" value="<?= $now ?>">
            </li>

            <li><div id="user_sent" onclick="User.Navigate(this)">Messages sent</div></li>

            <li><div id="user_rsent" onclick="User.Navigate(this)">Replies sent</div></li>

            <li>
              <div id="user_thread" onclick="User.Navigate(this)">
                Threads I'm in
                <div id="thread_count" class="new_count" style="display:none">
                  <span id="thread_x"></span>
                </div>
              </div>
              <input id="last_thread" type="hidden" value="<?= $now ?>">
            </li>

            <li>
              <div id="user_notifications" onclick="User.Navigate(this)">
                Notifications
                <div id="notifications_count" class="new_count" style="display:none">
                  <span id="notifications_x"></span>
                </div>
              </div>
              <input id="last_notifications" type="hidden" value="<?= $now ?>">
            </li>

            <li class="divider"><hr></li>

            <li><div id="user_people" onclick="User.Navigate(this)">People</div>
              <ul id="nav_user_people" style="display:none">
                <li><div id="user_people_friends" onclick="User.Navigate(this)">Friends (<span id="user_friend_count"><?= count($User->friends) ?></span>)</div></li>
                <li><div id="user_people_following" onclick="User.Navigate(this)">Following (<span id="user_following_count"><?= count($User->following) ?></span>)</div></li>
                <li><div id="user_people_followers" onclick="User.Navigate(this)">Followers (<span id="user_follower_count"><?= count($User->followers) ?></span>)</div></li>
                <li><div id="user_people_featured" onclick="User.Navigate(this)">Featured Members</div></li>
                <li><div id="user_people_fof" onclick="User.Navigate(this)">Friends of Friends</div></li>
              </ul>
            </li>

            <li><div id="user_groups" onclick="User.Navigate(this)">My Groups</div>
              <ul id="nav_user_groups" style="display:none">
                <li><div id="user_groups_publicgroups" onclick="User.Navigate(this)">My Public Groups (<span id="user_public_count"><?= count($User->public_groups) ?></span>)</div></li>
                <li><div id="user_groups_privategroups" onclick="User.Navigate(this)">My Private Groups (<span id="user_private_count"><?= count($User->private_groups) ?></span>)</div></li>
                <li><div id="user_groups_admingroups" onclick="User.Navigate(this)">Groups I Own or Administer</div></li>
                <!--<li><div id="user_groups_featuredgroups" onclick="User.Navigate(this)">Featured Groups</div></li>-->
                <li><div id="user_groups_friendgroups" onclick="User.Navigate(this)">Friend's Groups</div></li>
                <li><div id="user_groups_create" onclick="User.Navigate(this)">Create a Group</div></li>
              </ul>
            </li>

            <li><div id="user_invite" onclick="User.Navigate(this)">Invite</div>
              <ul id="nav_user_invite" style="display:none">
                <li><div id="user_invite_emailcontacts" onclick="User.Navigate(this)">Invite Email Contacts</div></li>
                <li><div id="user_invite_socialnetwork" onclick="User.Navigate(this)">Invite from Social Networks</div></li>
                <li><div id="user_invite_email" onclick="User.Navigate(this)">Email an Invitation</div></li>
                <? /*<li><div id="user_invite_text" onclick="User.Navigate(this)">Text an Invitation</div></li>*/ ?>
                <li><div id="user_invite_share" onclick="User.Navigate(this)">Share an Invitation Link</div></li>
              </ul>
            </li>

            <li><div id="user_alerts" onclick="User.Navigate(this)">Alerts</div></li>

            <li><div id="user_photo" onclick="User.Navigate(this)">My Photos</div>
              <ul id="nav_user_photo" style="display:none">
                <li><div id="user_photo_profilephoto" onclick="User.Navigate(this)">Profile Photo</div></li>
                <li><div id="user_photo_albums" onclick="User.Navigate(this)">Photo Albums</div></li>
              </ul>
            </li>

            <li><div id="user_profile" onclick="User.Navigate(this)">Edit Profile</div>
              <ul id="nav_user_profile" style="display:none">
                <li><div id="user_profile_profileinfo" onclick="User.Navigate(this)">Profile Information</div></li>
                <li><div id="user_profile_username" onclick="User.Navigate(this)">Change Username</div></li>
                <li><div id="user_profile_password" onclick="User.Navigate(this)">Change Password</div></li>
                <li><div id="user_profile_emailaddress" onclick="User.Navigate(this)">Account Email Address</div></li>
              </ul>
            </li>

            <li><div id="user_settings" onclick="User.Navigate(this)">Edit Settings</div>
              <ul id="nav_user_settings" style="display:none">
                <li><div id="user_settings_mobile" onclick="User.Navigate(this)">Mobile Phone</div></li>
                <li><div id="user_settings_notifications" onclick="User.Navigate(this)">General Notification Settings</div></li>
                <li><div id="user_settings_message" onclick="User.Navigate(this)">General Message Settings</div></li>
                <li><div id="user_settings_refreshrate" onclick="User.Navigate(this)">Refresh Mode</div></li>
                <li><div id="user_settings_cancel" onclick="User.Navigate(this)">Cancel Account</div></li>
              </ul>
            </li>
          </ul>
        </div>

        <div class="menu_box">
          <ul class="left_nav">
            <li class="syndication">
              <div id="user_syndication">Syndication</div>
              <hr>
            </li>
            <li>
              <div id="user_twitter" onclick="User.Navigate(this)">
                <img src="logos/favicon/twitter.png">
                <span>Twitter<span>
              </div>
            </li>

            <!--<li><div id="user_facebook" onclick="User.Navigate(this)">Facebook</div></li>-->

            <li>
              <div id="user_rss" onclick="User.Navigate(this)">
                <img src="logos/favicon/rss.png">
                <span>RSS</span>
              </div>
            </li>
          </ul>
        </div>
      </td>

      <td id="center_col"><div id="page_sizer">
        <div id="content_div" class="content_with_rcol" onmouseover="User.PauseUpdates()" onmouseout="User.ResumeUpdates()">
          <? Render("partials","messageform"); ?>

          <div class="timeline_header">
            <div id="content_header"></div>
            <? Render('partials','updatecounter'); ?>
            <? Render('partials','pausecounter'); ?>
            <div class="filter_button">
              <button id="show_timeline_filters" class="short_toggle" onclick="return Messages.ShowAllFilters();">Filters</button>
              <button id="hide_timeline_filters" class="short_toggle_sel" onclick="return Messages.HideAllFilters();" style="display:none">Filters</button>
           </div>
          </div>

          <div id="timeline_filters" style="display:none">
            <? Render("partials","messagefilters"); ?>

            <? Render("partials","messagefilter"); ?>

            <? Render("partials","userfilter"); ?>
          </div>
          <div id="user_content"></div>
        </div>
      </div></td>

      <td id="right_col">
        <!--<img src="filler/google_ads.gif">-->
      </td>
    </tr>
  </table>


<script type="text/javascript">
  User.Init();
</script>
