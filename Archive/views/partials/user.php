<?
include_once('views/partials/profile.php');
include_once('views/partials/preferences.php');

function renderuser($userid,$type,$display_type,$filter='',$return='html')
{
  global $User, $Profile, $Group;

  $user = User::get($userid);
  if (!$user) return false;
  $short = ($display_type!=Enum::$userlist_display_opt['long_list']);

  $isme = ($user->id===$User->id);

  // RENDER HTML

  $html = "<div id='user_$user->id' class='user_container' onmouseover='Users.OnMember(\"$user->id\")' onmouseout='Users.OffMember(\"$user->id\")'>";

  // follow info - top right
  $html .= "<div class='follow'>";
  if ($isme) $html .= "Hey, this is you!";
  else if (LOGGEDIN && CONFIRMED)
  {
    switch ($type)
    {
      case 'manage_members' : $html .= user_follow_manage_members($user); break;
      case 'manage_admins'  : $html .= user_follow_manage_admins($user); break;
      case 'transfer_owner' : $html .= user_follow_transfer_owner($user); break;
      default               : $html .= user_follow_default($user); break;
    }
  }
  $html .= "</div>";

  // avatar
  $html .= $user->getAvatar();

  // user name
  $username = preg_replace("/^".$filter."/i","<span class='highlight'>$0</span>",$user->username);
  $realname = preg_replace("/\b".$filter."/i","<span class='highlight'>$0</span>",$user->name);
  $html .= "<div class='user_name'>";
  $html .= "<a href='".$user->getProfileLink()."'>$username</a>";
  if ($user->show_name && $realname!="") $html .= " ($realname)";
  if ($user->visibility==Enum::$visibility['private'])
  {
    $html .= "<div class='privacy'><img src='images/private.png'> Private</div>";
  }
  $html .= "</div>";

  if (!$isme && (!$type || $type=='member'))
  {
    if ($user->visibleTo($User->id))
    {
      //action bar
      $html .= user_action_bar($user,$short);
      $html .= "<div id='profile_info_$user->id'";
      if ($short) $html .= " style='display:none'";
      $html .= ">";
      $html .= ListUserProfile($User,$user,$user->id);
      $html .= "</div>";
    }
    else
    {
      // private profile info
      $html .= user_private_profile($user);
    }

    // preferences
    if (!$type || $type=='member')
    {
      $html .= "<div id='preferences_$user->id' style='display:none'>";
      $html .= ListUserPreferences($User,$user,$user->id);
      $html .= "</div>";
    }
  }
  else
  {

  $html .= "<div class='clear' style='padding-top:4px'></div>";
  }
  $html .= "</div>";

  if ($return=='array')
  {
    $response = array();
    $response['html'] = $html;
    $response['id'] = $user->id;
  }
  else $response = $html;

  return $response;
}

function user_private_profile($user)
{
  global $User;
  $html .= "<div class='private'>";
  if ($User->followRequested($user->id))
  {
    $html .= "<p id='not_requested_$user->id'>$user->username's profile is private. You have already asked to follow $user->username. Your follow request is pending.</p>";
  }
  else
  {
    if (LOGGEDIN)
    {
      $html .= "<p id='not_requested_$user->id'>$user->username's profile is Private. If you know $user->username, click the &quot;Ask to Follow&quot; link. If $user->username accepts your request you can follow $user->username's messages on Miio.</p>";
      $html .= "<p id='sent_request_$user->id' style='display:none'>Your follow request was sent</p>";
    }
    else
    {
      $html .= "<p id='not_requested_$user->id'>$user->username's profile is Private.</p>";
    }
  }
  $html .= "</div>";
  return $html;
}

function user_action_bar($user,$short)
{
  global $User;

  $html .= "<div class='links' style='clear:both;'><ul id='user_bar_$user->id'";
  if (!$short) $html .= " class='mo'";
  $html .= ">";
  if
  (
    !$isme && ($user->visibility==Enum::$visibility['public'] || $User->isFollowing($user->id))
  )
  {
    if ($short)
    {
      $html .= "<input type='hidden' id='profile_open_$user->id' value=0>";
      $html .= "<li class='closed'><a href='#' id='profile_link_$user->id' onclick='return Users.Profile(this,\"$user->id\")'>";
      $html .= "<img src='images/member_info.png'> ";
      $html .= "</a><label>About</label></li>";
    }
    else
    {
      $html .= "<input type='hidden' id='profile_open_$user->id' value=1>";
      $html .= "<li class='open'><a href='#' id='profile_link_$user->id' onclick='return Users.Profile(this,\"$user->id\")'>";
      $html .= "<img src='images/member_info.png'> ";
      $html .= "</a><label>About</label></li>";
    }
  }

  $html .= "<input type='hidden' id='preference_open_$user->id' value=0>";
  if ($User->isFollowing($user->id))
  {
    $html .= "<li id='preference_link_container_$user->id'>";
    $html .= "<a href='#' id='preference_link_$user->id' class='closed' onclick='return Users.Preferences(this,\"$user->id\")'>";
    $html .= "<img src='images/preferences.png'>";
    $html .= "</a><label>Manage</label></li>";
  }
  else
  {
    $html .= "<li id='preference_link_container_$user->id' style='display:none'>";
    $html .= "<a href='#' id='preference_link_$user->id' class='closed' onclick='return Users.Preferences(this,\"$user->id\")'>";
    $html .= "<img src='images/preferences.png'>";
    $html .= "</a><label>Manage</label></li>";
  }
  $html .= "</ul></div>";

  return $html;
}

function user_follow_manage_members($user)
{
  global $User,$Group;
  $html = "";
  if ($Group->hasAdmin($User->id))
  {
    if ($Group->owner==$user->id)
    {
      $html .= "<span>Group Owner</span>";
    }
    else if ($Group->hasAdmin($user->id))
    {
      $html .= "<span>Group Administrator</span>";
    }
    else if ($Group->hasMember($user->id))
    {
      $html .= "<a href='#' id='remove_member_$user->id' onclick='return Group.ManageMembers.Remove(this,\"$user->id\",\"$user->username\");'>";
      $html .= "<img src='images/plus.png'>";
      $html .= "<span>Remove from group</span></a>";
    }
    else
    {
      $html .= "<span>Not a member</span>";
    }
  }
  return $html;
}

function user_follow_manage_admins($user)
{
  global $User,$Group;
  $html = "";
  if ($Group->owner===$User->id)
  {
    if ($Group->hasAdmin($user->id))
    {
      $html .= "<a href='#' id='remove_admin_$user->id' onclick='return Group.ManageAdmins.Remove(\"$user->id\",\"$user->username\");'>";
      $html .= "<img src='images/plus.png'>";
      $html .= "<span>Remove as Admin</span></a>";
    }
    else if ($Group->hasInvitedAdmin($user->id))
    {
      $html .= "<a href='#' id='cancel_admin_$user->id' onclick='return Group.ManageAdmins.Cancel(\"$user->id\",\"$user->username\");'>";
      $html .= "<img src='images/plus.png'>";
      $html .= "<span>Cancel Admin Invite</span></a>";
    }
    else
    {
      $html .= "<a href='#' id='invite_admin_$user->id' onclick='return Group.ManageAdmins.Invite(\"$user->id\",\"$user->username\");'>";
      $html .= "<img src='images/plus.png'>";
      $html .= "<span>Invite to Admin</span></a>";
    }
  }
  return $html;
}


function user_follow_transfer_owner($user)
{
  global $User,$Group;
  $html = "";
  if ($Group->owner===$User->id)
  {
    if ($Group->hasInvitedOwner($user->id))
    {
      $html .= "<a href='#' id='cancel_owner_$user->id' onclick='return Group.TransferOwnership.Cancel(\"$user->id\",\"$user->username\");'>";
      $html .= "<img src='images/plus.png'>";
      $html .= "<span>Cancel Ownership Transfer</span></a>";
    }
    else
    {
      $html .= "<a href='#' id='invite_owner_$user->id' onclick='return Group.TransferOwnership.Invite(\"$user->id\",\"$user->username\");'>";
      $html .= "<img src='images/plus.png'>";
      $html .= "<span>Invite to Own</span></a>";
    }
  }
  return $html;
}

function user_follow_default($user)
{
  global $User;
  $html = "";
  $style = ($User->isFollowing($user->id)) ? "style='display:none'" : "";
  if ($user->visibility==Enum::$visibility['private'])
  {
    if ($User->followRequested($user->id))
    {
      $html .= "Follow Requested";
    }
    else
    {
      $html .= "<span id='requested_$user->id' style='display:none'>Follow requested</span>";
      $html .= "<a href='#' id='follow_link_$user->id' onclick='return Users.RequestFollow(this,\"$user->id\",\"$user->username\");' $style>";
      $html .= "<img src='images/plus.png'>";
      $html .= "<span>Ask to Follow</span></a>";
    }
  }
  else
  {
    $html .= "<a href='#' id='follow_link_$user->id' onclick='return Users.Follow(this,\"$user->id\",\"$user->username\");' $style>";
    $html .= "<img src='images/plus.png'>";
    $html .= "<span>Follow</span></a>";
  }
  $html .= "</div>";

  // status icons
  $html .= "<div class='status' id='status_$user->id' ";
  $html .= ($User->isFollowing($user->id)) ? "" : "style='display:none'";
  $html .= ">";
  $html .= "<img src='images/check.png' alt='following' title='You are following this member'>";
  if ($User->hasOnMute($user->id))
  {
    $html .= "<img id='muteon_$user->id' src='images/mute.png' alt='muted' title='This member is muted'>";
    $html .= "<img id='muteoff_$user->id' src='images/mute_off.png' alt='not muted' title='This member is not muted' style='display:none'>";
  }
  else
  {
    $html .= "<img id='muteon_$user->id' src='images/mute.png' alt='muted' title='This member is muted' style='display:none'>";
    $html .= "<img id='muteoff_$user->id' src='images/mute_off.png' alt='not muted' title='This member is not muted'>";
  }
  if ($User->hasSMSOn($user->id))
  {
    $html .= "<img id='smson_$user->id' src='images/phone.png' alt='phone enabled' title='Phone updates are enabled'>";
    $html .= "<img id='smsoff_$user->id' src='images/phone_off.png' alt='phone disabled' title='Phone updates are not enabled' style='display:none'>";
  }
  else
  {
    $html .= "<img id='smson_$user->id' src='images/phone.png' alt='phone enabled' title='Phone updates are enabled' style='display:none'>";
    $html .= "<img id='smsoff_$user->id' src='images/phone_off.png' alt='phone disabled' title='Phone updates are not enabled'>";
  }
  return $html;
}