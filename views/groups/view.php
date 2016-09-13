<? global $User, $Group, $ISME, $PRIVATE, $IS_MEMBER, $IS_ADMIN; ?>
<?

if ($User->isMemberOf($Group->id))
{
  $IS_MEMBER = true;
  if ($User->isAdminOf($Group->id))
  {
    $IS_ADMIN = true;
    if ($User->isOwnerOf($Group->id)) $IS_OWNER = true;
  }
}
else if ($Group->visibility==Enum::$visibility['private']) $PRIVATE = true;

$fLink = LOC."groups/view/".$Group->id;
$fText = "View Miio Group titled <?= $Group->groupname; ?>";
?>
<link href="css/group.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group.js"></script>

<link href="css/messagelist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/messagelist.js"></script>
<link href="css/userlist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/userlist.js"></script>
<link href="css/messageform.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/messageform.js"></script>
<link href="css/group_timeline.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_timeline.js"></script>
<link href="css/group_albums.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_albums.js"></script>
<link href="css/group_description.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_description.js"></script>
<link href="css/group_manage_membership.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_manage_membership.js"></script>
<link href="css/group_invite_members.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_invite_members.js"></script>
<link href="css/group_report_group.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_report_group.js"></script>

<link href="css/group_profile.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_profile.js"></script>
<link href="css/group_editphoto.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_editphoto.js"></script>
<link href="css/group_editalbums.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_editalbums.js"></script>
<link href="css/group_requests.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_requests.js"></script>
<link href="css/group_managemembers.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_managemembers.js"></script>
<link href="css/group_changename.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/group_changename.js"></script>

<script type="text/javascript" src="js/group_members.js"></script>
<script type="text/javascript" src="js/group_manageadmins.js"></script>
<script type="text/javascript" src="js/group_transferownership.js"></script>
<script type="text/javascript" src="js/group_disband.js"></script>

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
</script>

  <table>
    <tr>
      <td id="left_col">
        <img src="images/group_header.png" class="group_header">
        <div class="profile_photo">
          <a href="#" onclick="Group.Navigate('group_timeline');return false;"><img src="<?= $Group->getProfilePhoto() ?>" height=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> width=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> id="group_photo" alt='photo'></a>
        </div>

        <? if (!$PRIVATE) { ?>
          <div class="menu_box">
            <ul class="left_nav">
              <li class='home'>
                <div>
                  <h2 id="group_username"><?= $Group->groupname ?></h2>
                </div>
              </li>
              <li><div id="group_timeline" onclick="Group.Navigate(this)">Timeline</div></li>
              <li><div id="group_description" onclick="Group.Navigate(this)">About</div></li>
              <li><div id="group_albums" onclick="Group.Navigate(this)">Photo albums (<span id="group_album_count"><?= count($Group->albums) ?></span>)</div></li>
              <li><div id="group_members" onclick="Group.Navigate(this)">Members (<span id="group_member_count"><?= count($Group->group_members) ?></span>)</div></li>
            </ul>

            <h2>Members</h2>
            <?
              global $PHOTOCOLLAGE;
              $PHOTOCOLLAGE = $Group->getMembers();
              Render('partials','photocollage');
            ?>

            <? if ($Group->visibility==Enum::$visibility['public']) { ?>
              <h2>Share this Group</h2>
              <? Render ('partials','foreignshare'); ?>
            <? } ?>

            <h2>Group URL</h2>
            <div class="copy">
              <input type="text" value="<?= LOC ?><?= $Group->groupname; ?>">
            </div>

          </div>

          <? if ($IS_ADMIN) { ?>
            <div class="menu_box">
              <? if ($IS_ADMIN) { ?>
                <ul class="left_nav">
                  <li class="admin">
                    <div id="group_admin_header">Group Admin</div>
                    <hr>
                  </li>
                  <? if ($IS_OWNER) { ?>
                    <li><div id="group_profile" onclick="Group.Navigate(this)">Group profile</div></li>
                    <li><div id="group_changename" onclick="Group.Navigate(this)">Change group name</div></li>
                  <? } ?>
                  <li><div id="group_editphoto" onclick="Group.Navigate(this)">Group photo</div></li>
                  <li><div id="group_editalbums" onclick="Group.Navigate(this)">Albums</div></li>
                  <li><div id="group_invitemembers" onclick="Group.Navigate(this)">Invite</div></li>
                  <li id="group_requests_container" <? if ($Group->visibility!=Enum::$visibility['private']) echo 'style="display:none"'; ?>><div id="group_requests" onclick="Group.Navigate(this)">Membership Req's (<span id="group_request_count"><?= count($Group->requested_memberships) ?></span>)</div></li>
                  <li><div id="group_managemembers" onclick="Group.Navigate(this)">Remove members</div></li>
                  <? if ($IS_OWNER) { ?>
                    <li><div id="group_manageadmins" onclick="Group.Navigate(this)">Manage administrators</div></li>
                    <li><div id="group_transferownership" onclick="Group.Navigate(this)">Transfer ownership</div></li>
                    <li><div id="group_disband" onclick="Group.Navigate(this)">Disband group</div></li>
                  <? } ?>
                </ul>
              <? } ?>
            </div>
          <? } ?>
        <? } ?>
      </td>

      <td id="center_col">
        <div id="content_div" class="content_with_rcol" onmouseover="Group.PauseUpdates()" onmouseout="Group.ResumeUpdates()">
          <? if (LOGGEDIN && CONFIRMED) { ?>
            <div class="top_links">
              <? if ($ISME) { ?>
                Hey, this is you!
              <? } else if (!$IS_ADMIN) { ?>
                <span class="link">
                  <? if ($User->hasReportedGroup($Group->id)) { ?>
                    <a id="report_link" href="#" onclick="return Group.Report(this,'<?= $Group->id ?>')" style="display:none">Report</a>
                    <span id="reported_text">Reported</span>
                  <? } else { ?>
                    <a id="report_link" href="#" onclick="return Group.Report(this,'<?= $Group->id ?>')">Report</a>
                    <span id="reported_text" style="display:none">Reported</span>
                  <? } ?>
                </span>
              <? } ?>
            </div>
          <? } ?>

          <div class="username">
            <span class="fullsize" id="group_head_username">
              <?= $Group->groupname ?>
            </span>
              <? if ($Group->show_name && $Group->name) { ?>
                <span>(<?= $Group->name ?>)</span>
              <? } ?>

            <? if ($Group->visibility==Enum::$visibility['private']) { ?>
              <img src='images/private.png' alt='Private'>
            <? } ?>
          </div>
          <? if ($User->is_super) { ?>
          <div class="atq_rfq">
            <a href="#" onclick="return Group.Suspend('<?= $Group->id ?>','<?= $Group->groupname ?>')" id="suspend_user">Suspend Group</a>
            <span id="suspended_user" style="display:none">Group Suspended</span>
          </div>
        <? } ?>
          <? if (!LOGGEDIN) { ?>
            <div class="ls_actionlinks">
              Miio is the new place to meet, talk and share.
              <a href="user/login">Login</a> or <a href="signup">Sign up</a>
              to connect with the <?= $Group->groupname ?> group - It's free and anyone can join.
            </div>
          <? } else if (!CONFIRMED) { ?>
            <div class="ls_actionlinks">
              <a href="signup/confirm">Confirm your account</a> to Join the <?= $Group->groupname ?> group
            </div>
          <? } else { ?>
            <div class="actionlinks">
              <? if ($IS_OWNER) { ?>
                <span id="status_owner" class="status">
                  You are the Owner of this group
                </span>
                <a href="#" onclick="return Group.Manage(this,'<?= $Group->id ?>')" id="member_preferences">Manage</a>
              <? } else if ($IS_ADMIN) { ?>
                <span id="status_admin" class="status">
                  You are an Administrator of this group
                </span>
                <span id="status_member" style="display:none" class="status">
                  You are a Member of this group
                </span>
                <span id="status_not_member" style="display:none" class="status">
                  You are not a Member of this group
                </span>
                <a href="#" onclick="return Group.Leave('<?= $Group->id ?>','<?= $Group->groupname ?>')" id="leave_link">Leave Group</a>
                <? if ($Group->visibility==Enum::$visibility['private']) { ?>
                  <a href="#" onclick="return Group.RequestMembership('<?= $Group->id ?>')" id="join_link" style="display:none">Request Membership</a>
                <? } else { ?>
                  <a href="#" onclick="return Group.Join('<?= $Group->id ?>')" id="join_link" style="display:none">Join</a>
                <? } ?>
                <a href="#" onclick="return Group.Manage(this,'<?= $Group->id ?>')" id="member_preferences">Manage</a>
              <? } else if ($IS_MEMBER) { ?>
                <span id="status_member" class="status">
                  You are a Member of this group
                </span>
                <span id="status_not_member" style="display:none" class="status">
                  You are not a Member of this group
                </span>
                <? if ($Group->visibility==Enum::$visibility['private']) { ?>
                  <span id="status_requested" style="display:none" class="status">
                    You have <span>requested membership</span> in the <span><?= $Group->groupname ?></span> group
                  </span>
                <? } ?>
                <a href="#" onclick="return Group.Leave('<?= $Group->id ?>','<?= $Group->groupname ?>')" id="leave_link">Leave Group</a>
                <? if ($Group->visibility==Enum::$visibility['private']) { ?>
                  <a href="#" onclick="return Group.RequestMembership('<?= $Group->id ?>')" id="join_link" style="display:none">Request Membership</a>
                <? } else { ?>
                  <a href="#" onclick="return Group.Join('<?= $Group->id ?>')" id="join_link" style="display:none">Join</a>
                <? } ?>
                <a href="#" onclick="return Group.Manage(this,'<?= $Group->id ?>')" id="member_preferences">Manage</a>
              <? } else if ($User->membershipRequested($Group->id)) { ?>
                <span id="status_member" class="status" style="display:none">
                  You are a Member of this group
                </span>
                <span id="status_not_member" style="display:none" class="status">
                  You are not a Member of this group
                </span>
                <? if ($Group->visibility==Enum::$visibility['private']) { ?>
                  <span id="status_requested" class="status">
                    Membership request pending
                  </span>
                <? } ?>
                <a href="#" onclick="return Group.Leave('<?= $Group->id ?>','<?= $Group->groupname ?>')" id="leave_link" style="display:none">Leave Group</a>
                <? if ($Group->visibility==Enum::$visibility['private']) { ?>
                  <a href="#" onclick="return Group.RequestMembership('<?= $Group->id ?>')" id="join_link" style="display:none">Request Membership</a>
                <? } else { ?>
                  <a href="#" onclick="return Group.Join('<?= $Group->id ?>')" id="join_link" style="display:none">Join</a>
                <? } ?>
                <a href="#" onclick="return Group.Manage(this,'<?= $Group->id ?>')" id="member_preferences" style="display:none">Manage</a>
              <? } else { ?>
                <span id="status_member" class="status" style="display:none">
                  You are a Member of this group
                </span>
                <span id="status_not_member" class="status">
                  You are not a Member of this group
                </span>
                <? if ($Group->visibility==Enum::$visibility['private']) { ?>
                  <span id="status_requested" class="status" style="display:none">
                    Membership request pending
                  </span>
                <? } ?>
                <a href="#" onclick="return Group.Leave('<?= $Group->id ?>','<?= $Group->groupname ?>')" id="leave_link" style="display:none">Leave Group</a>
                <? if ($Group->visibility==Enum::$visibility['private']) { ?>
                  <a href="#" onclick="return Group.RequestMembership('<?= $Group->id ?>')" id="join_link">Request Membership</a>
                <? } else { ?>
                  <a href="#" onclick="return Group.Join('<?= $Group->id ?>')" id="join_link">Join</a>
                <? } ?>
                <a href="#" onclick="return Group.Manage(this,'<?= $Group->id ?>')" id="member_preferences" style="display:none">Manage</a>
              <? } ?>
              &nbsp;
            </div>
          <? } ?>

          <? if (!$PRIVATE) { ?>
            <? if ($IS_MEMBER) Render("partials", "messageform"); ?>

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
            <div class="group_update_counter">
              <? Render('partials','updatecounter'); ?>
            </div>
            <? if ($IS_OWNER) { ?>
              <div id="group_manageadmins_header" style="display:none">
                <a href="#" onclick="return Group.ManageAdmins.ChangeSelection('admins');" style="display:none" id="group_manageadmins_header_admins_link">Current Administrators</a>
                <span id="group_manageadmins_header_admins">Current Administrators</span>
                |
                <a href="#" onclick="return Group.ManageAdmins.ChangeSelection('invite');" id="group_manageadmins_header_invite_link">Invite Admins</a>
                <span id="group_manageadmins_header_invite" style="display:none">Invite Admins</span>
                |
                <a href="#" onclick="return Group.ManageAdmins.ChangeSelection('invites');" id="group_manageadmins_header_invites_link">Admin Invitations Pending</a>
                <span id="group_manageadmins_header_invites" style="display:none">Admin Invitations Pending</span>
              </div>
              <div id="group_transferownership_header" style="display:none">
                <a href="#" onclick="return Group.TransferOwnership.ChangeSelection('owner');" style="display:none" id="group_transferownership_header_owner_link">Current Owner</a>
                <span id="group_transferownership_header_owner">Current Owner</span>
                |
                <a href="#" onclick="return Group.TransferOwnership.ChangeSelection('invite');" id="group_transferownership_header_invite_link">Invite Admin to Own</a>
                <span id="group_transferownership_header_invite" style="display:none">Invite Admin to Own</span>
                |
                <a href="#" onclick="return Group.TransferOwnership.ChangeSelection('pending');" id="group_transferownership_header_pending_link">Ownership Transfer Pending</a>
                <span id="group_transferownership_header_pending" style="display:none">Ownership Transfer Pending</span>
              </div>
            <? } ?>

            <div id="groups_content"></div>

      <? } else { ?>

      <div id="groups_content">

        <div id="profile_description">
          <ul>
            <?
              echo "<li><label>Group name</label><div>$Group->groupname</div></li>";
              if ($Group->visibilty==Enum::$visibility['private']) echo "<li><label>Private Group</label><div>";
              else echo "<li><label>Private Group</label><div>";
              $membercount = $Group->number_of[Enum::$number_of['members']];
              if ($membercount==1) echo "$membercount member</div></li>";
              else echo "$membercount members</div></li>";
              echo "<li><label>Category</label><div>" . Options::$category[$Group->category] . "</div></li>";
              echo "<li><label>Formed</label><div>" . $Group->getCreatedDate() . "</div></li>";
              $output = false;
              // description
              if ($Group->description!="")
              {
                echo "<li><label>About</label><div>$Group->description</div></li>";
                $output = true;
              }

              // location
              $location = $Group->getLocation();
              if ($location != "")
              {
                echo "<li><label>Location</label><div>$location</div></li>";
                $output = true;
              }

              if ($Group->keywords)
              {
                $keywords = implode(' ',$Group->keywords);
                echo "<li><label>Keywords</label><div>$keywords</div></li>";
                $output = true;
              }
            ?>
          </div>

        </div>
        <div id="private_content">
          <? if ($User->membershipRequested($Group->id)) { ?>
            <p>This is a private group. You have requested membership in the <?= $Group->groupname ?> group. Your membership request is pending.</p>
          <? } else { ?>
            <? if (LOGGEDIN) { ?>
              <p>This is a private group. Please click the "Request Membership" button to request membership in the <?= $Group->groupname ?> group. If the <?= $Group->groupname ?> group accepts your request you can follow <?= $Group->groupname ?>'s messages on Miio.</p>
              <div>
                <button class="short_button" onclick="return Group.RequestMembership('<?= $Group->id ?>')" id="join_button">Request Membership</button>
              </div>
            <? } else { ?>
              <p>This is a private group.</p>
            <? } ?>
          <? } ?>
        </div>
        <div id="sub_requested" style="display:none">Your membership request was sent</div>
        <? } ?>
      </td>
      <td id="right_col">
        <!--<img src="filler/google_ads.gif">-->
      </td>
    </tr>
  </table>

<input type="hidden" id="ismember" value="<?= (($IS_MEMBER) ? 1 : 0) ?>">
<input type="hidden" id="isadmin" value="<?= (($IS_ADMIN) ? 1 : 0) ?>">
<input type="hidden" id="isprivate" value="<?= (($PRIVATE) ? 1 : 0) ?>">
<script type="text/javascript">
  Group.IsReported = <?= ($User->hasReportedGroup($Group->id))? 'true' : 'false'; ?>;
  Group.Init('<?= $Group->id ?>','<?= $Group->groupname ?>');
</script>