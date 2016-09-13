<?
include_once('views/partials/profile.php');
include_once('views/partials/preferences.php');

function rendergroup($groupid,$type,$display_type,$filter='',$return='html')
{
  global $User, $Profile, $Group;

  $group = Group::get($groupid);
  if (!$group) return false;
  $short = ($display_type!='long');

  // RENDER HTML

  $html = "<div id='user_$group->id' class='user_container' onmouseover='Users.OnMember(\"$group->id\")' onmouseout='Users.OffMember(\"$group->id\")'>";

  // follow info - top right
  $html .= "<div class='follow'>";
  if (LOGGEDIN && CONFIRMED)
  {
      $html .= user_member_options($group);
  }
  $html .= "</div>";

  // avatar
  $html .= $group->getAvatar();

  // user name
  $groupname = preg_replace("/^".$filter."/i","<span class='highlight'>$0</span>",$group->groupname);
  $fullname = preg_replace("/\b".$filter."/i","<span class='highlight'>$0</span>",$group->name);
  $html .= "<div class='user_name'>";
  $html .= "<a href='".$group->getProfileLink()."'>$groupname</a>";
  if ($group->show_name && $fullname!="") $html .= " ($fullname)";
  if ($group->visibility==Enum::$visibility['private'])
  {
    $html .= "<div class='privacy'><img src='images/private.png'> Private</div>";
  }
  $html .= "</div>";

  if ($group->visibleTo($User->id))
  {
    //action bar
    $html .= group_action_bar($group,$short);
    $html .= "<div id='profile_info_$group->id'";
    if ($short) $html .= " style='display:none'";
    $html .= ">";
    $html .= ListGroupProfile($User,$group);
    $html .= "</div>";
  }
  else
  {
    // private profile info
    $html .= group_private_profile($group);
  }

  // preferences
  if (!$type)
  {
    $html .= "<div id='preferences_$group->id' style='display:none'>";
    $html .= ListGroupPreferences($User,$group);
    $html .= "</div>";
  }

  $html .= "</div>";

  if ($return=='array')
  {
    $response = array();
    $response['html'] = $html;
    $response['id'] = $group->id;
  }
  else $response = $html;

  return $response;
}

function group_private_profile($group)
{
  global $User;
  $html .= "<div class='private'>";
  if ($User->membershipRequested($group->id))
  {
    $html .= "<p id='not_requested_$group->id'>$group->groupname's profile is private. You have already asked to follow $group->groupname. Your follow request is pending.</p>";
  }
  else
  {
    if (LOGGEDIN)
    {
      $html .= "<p id='not_requested_$group->id'>$group->groupname's profile is Private. If you know $group->groupname, click the &quot;Ask to Follow&quot; link. If $group->groupname accepts your request you can follow $group->groupname's messages on Miio.</p>";
      $html .= "<p id='sent_request_$group->id' style='display:none'>Your follow request was sent</p>";
    }
    else
    {
      $html .= "<p id='not_requested_$group->id'>$group->groupname's profile is Private.</p>";
    }
  }
  $html .= "</div>";
  return $html;
}

function group_action_bar($group,$short)
{
  global $User;

  $html .= "<div class='links' style='clear:both;'><ul id='user_bar_$group->id'";
  if (!$short) $html .= " class='mo'";
  $html .= ">";

  if ($short)
  {
    $html .= "<input type='hidden' id='profile_open_$group->id' value=0>";
    $html .= "<li class='closed'><a href='#' id='profile_link_$group->id' onclick='return Users.Profile(this,\"$group->id\")'>";
    $html .= "<img src='images/member_info.png'> ";
    $html .= "</a><label>About</label></li>";
  }
  else
  {
    $html .= "<input type='hidden' id='profile_open_$group->id' value=1>";
    $html .= "<li class='open'><a href='#' id='profile_link_$group->id' onclick='return Users.Profile(this,\"$group->id\")'>";
    $html .= "<img src='images/member_info.png'> ";
    $html .= "</a><label>About</label></li>";
  }

  $html .= "<input type='hidden' id='preference_open_$group->id' value=0>";
  if ($User->isMemberOf($group->id))
  {
    $html .= "<li id='preference_link_container_$group->id'>";
    $html .= "<a href='#' id='preference_link_$group->id' class='closed' onclick='return Users.Preferences(this,\"$group->id\")'>";
    $html .= "<img src='images/preferences.png'>";
    $html .= "</a><label>Manage</label></li>";
  }
  else
  {
    $html .= "<li id='preference_link_container_$group->id' style='display:none'>";
    $html .= "<a href='#' id='preference_link_$group->id' class='closed' onclick='return Users.Preferences(this,\"$group->id\")'>";
    $html .= "<img src='images/preferences.png'>";
    $html .= "</a><label>Manage</label></li>";
  }
  $html .= "</ul></div>";

  return $html;
}

function user_member_options($group)
{
  global $User;
  $html = "";
  $style = ($User->isMemberOf($group->id)) ? "style='display:none'" : "";
  if ($group->visibility==Enum::$visibility['private'])
  {
    if ($User->membershipRequested($group->id))
    {
      $html .= "Membership Requested";
    }
    else
    {
      $html .= "<span id='requested_$group->id' style='display:none'>Membership requested</span>";
      $html .= "<a href='#' id='join_link_$group->id' onclick='return Users.RequestMembership(this,\"$group->id\",\"$group->groupname\");' $style>";
      $html .= "<img src='images/plus.png'>";
      $html .= "<span>Ask to Join</span></a>";
    }
  }
  else
  {
    $html .= "<a href='#' id='join_link_$group->id' onclick='return Users.Join(this,\"$group->id\",\"$group->groupname\");' $style>";
    $html .= "<img src='images/plus.png'>";
    $html .= "<span>Join</span></a>";
  }
  $html .= "</div>";

  // status icons
  $html .= "<div class='status' id='status_$group->id' ";
  $html .= ($User->isMemberOf($group->id)) ? "" : "style='display:none'";
  $html .= ">";
  if ($User->isOwnerOf($group->id)) $html .= "<img src='images/owner.png' alt='owner' title='You are the Owner of this group'>";
  else if ($User->isAdminOf($group->id)) $html .= "<img src='images/admin.png' alt='administrator' title='You are an Administrator of this group'>";
  else $html .= "<img src='images/check.png' alt='member' title='You are a member of this group'>";
  if ($User->hasOnMute($group->id,true))
  {
    $html .= "<img id='muteon_$group->id' src='images/mute.png' alt='muted' title='This group is muted'>";
    $html .= "<img id='muteoff_$group->id' src='images/mute_off.png' alt='not muted' title='This group is not muted' style='display:none'>";
  }
  else
  {
    $html .= "<img id='muteon_$group->id' src='images/mute.png' alt='muted' title='This group is muted' style='display:none'>";
    $html .= "<img id='muteoff_$group->id' src='images/mute_off.png' alt='not muted' title='This group is not muted'>";
  }
  if ($User->hasSMSOn($group->id,true))
  {
    $html .= "<img id='smson_$group->id' src='images/phone.png' alt='phone enabled' title='Phone updates are enabled'>";
    $html .= "<img id='smsoff_$group->id' src='images/phone_off.png' alt='phone disabled' title='Phone updates are not enabled' style='display:none'>";
  }
  else
  {
    $html .= "<img id='smson_$group->id' src='images/phone.png' alt='phone enabled' title='Phone updates are enabled' style='display:none'>";
    $html .= "<img id='smsoff_$group->id' src='images/phone_off.png' alt='phone disabled' title='Phone updates are not enabled'>";
  }
  return $html;
}