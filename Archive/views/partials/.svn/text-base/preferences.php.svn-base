<?
function ListUserPreferences($User,$user)
{
  global $LOC;
  if ($user->id=='a') $ismiio = true;
  $nosms = ($User->notification_sms == "");

  $Preferences = $User->getFollowSettings($user->id);
  $muted = $Preferences['muted'];

  $html = "<div class='list_preferences'>";
  $html .= "<a href='#' class='close' onclick='return Users.ClosePreferences(\"$user->id\")'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  $html .= "<h2>Manage Follow Settings</h2>";

  if (!$ismiio)
  {
    $html .= "<input type='hidden' id='is_group_$user->id' value=0>";
    $html .= "<label class='following'>Following</label>";
    $html .= "<a href='#' onclick='return Users.UnFollow(this,\"$user->id\",\"$user->username\");'>Stop Following</a>";

    $html .= "<div id='mute_container_$user->id' class='mute_container";
    if ($muted) $html .= " muted";
    $html .= "'><table>";
    $html .= "<tr><th class='label'></th><th class='opt'>On</th><th class='opt'>Off</th><th class='info'></th></tr>";
    $html .= "<tr><td class='label'>Mute:";
    if ($muted)
    {
      $html .= "<img src='images/mute.png' alt='mute' id='mute_icon_$user->id'>";
      $html .= "<img src='images/mute_off.png' alt='mute' id='mute_off_icon_$user->id' style='display:none'>";
    }
    else
    {
      $html .= "<img src='images/mute.png' alt='mute' id='mute_icon_$user->id' style='display:none'>";
      $html .= "<img src='images/mute_off.png' alt='mute' id='mute_off_icon_$user->id'>";
    }
    $html .= "</td><td class='opt'>";
    $html .= "<input type='radio' name='mute_$user->id' id='mute_on_$user->id' onchange='Users.ChangeMute(\"$user->id\",true);'";
    if ($muted) $html .= " checked";
    $html .= "></td><td class='opt'>";
    $html .= "<input type='radio' name='mute_$user->id' id='mute_off_$user->id' onchange='Users.ChangeMute(\"$user->id\",false);'";
    if (!$muted) $html .= " checked";
    $html .= "></td><td class='info'>";
    if ($user->is_group) $html .= "Mute $user->username but remain a member";
    else $html .= "Mute $user->username but continue following";
    $html .= "</td></tr></table></div>";
  }
  else
  {
    $html .= "<hr>";
  }

  $html .= "<div class='message_options'>";
  $html .= "<div class='message_options_text'><p>";
  $html .= "Choose the types content you would like to receive from <a href='members/profile/$user->id'>$user->username</a> and the delivery method";
  $html .= "</p></div>";
  $html .= "<table>";

  $html .= "<tr><th class='content_type'>Content</th>";
  if (!$ismiio) $html .= "<th>Dashboard</th>";
  $html .= "<th>SMS";
  if ($nosms) $html .= " <span>*</span>";
  $html .= "</th><th>Email</th></tr>";

  $miioall = true;
  $smsall = true;
  $emailall = true;

  foreach (Options::$follow_settings as $type=>$label)
  {
    if ($type!=Enum::$follow_settings['reply'])
    {
      if (!$Preferences['dashboard'][$type]) $miioall = false;
    }
    if (!$Preferences['sms'][$type]) $smsall = false;
    if (!$Preferences['email'][$type]) $emailall = false;
  }

  $html .= "<tr class='select_all'><td>&nbsp;</td>";
  if (!$ismiio)
  {
    $html .= "<td class='col'>";
    if ($muted) $html .= "<span id='dashboardsa_$user->id' style='display:none'>";
    else $html .= "<span id='dashboardsa_$user->id'>";
    if ($miioall)
    {
      $html .= "<a id='dashboard_sa_$user->id' href='#' onclick='return Users.SelectAll(\"dashboard\",\"$user->id\",true);' style='display:none'>Select All</a><a id='dashboard_dsa_$user->id' href='#' onclick='return Users.SelectAll(\"dashboard\",\"$user->id\",false);'>Unselect All</a>";
    }
    else
    {
      $html .= "<a id='dashboard_sa_$user->id' href='#' onclick='return Users.SelectAll(\"dashboard\",\"$user->id\",true);'>Select All</a><a id='dashboard_dsa_$user->id' href='#' onclick='return Users.SelectAll(\"dashboard\",\"$user->id\",false);' style='display:none'>Unselect All</a>";
    }
    $html .= "</span></td>";
  }
  if ($nosms) $html .= "<td class='col'></td>";
  else
  {
    $html .= "<td class='col'>";
    if ($muted) $html .= "<span id='smssa_$user->id' style='display:none'>";
    else $html .= "<span id='smssa_$user->id'>";
    if ($smsall)
    {
      $html .= "<a id='sms_sa_$user->id' href='#' onclick='return Users.SelectAll(\"sms\",\"$user->id\",true);' style='display:none'>Select All</a><a id='sms_dsa_$user->id' href='#' onclick='return Users.SelectAll(\"sms\",\"$user->id\",false);'>Unselect All</a>";
    }
    else
    {
      $html .= "<a id='sms_sa_$user->id' href='#' onclick='return Users.SelectAll(\"sms\",\"$user->id\",true);'>Select All</a><a id='sms_dsa_$user->id' href='#' onclick='return Users.SelectAll(\"sms\",\"$user->id\",false);' style='display:none'>Unselect All</a>";
    }
    $html .= "</span></td>";
  }

  $html .= "<td class='col'>";
  if ($muted) $html .= "<span id='emailsa_$user->id' style='display:none'>";
  else $html .= "<span id='emailsa_$user->id'>";
  if ($emailall)
  {
    $html .= "<a id='email_sa_$user->id' href='#' onclick='return Users.SelectAll(\"email\",\"$user->id\",true);' style='display:none'>Select All</a><a id='email_dsa_$user->id' href='#' onclick='return Users.SelectAll(\"email\",\"$user->id\",false);'>Unselect All</a>";
  }
  else
  {
    $html .= "<a id='email_sa_$user->id' href='#' onclick='return Users.SelectAll(\"email\",\"$user->id\",true);'>Select All</a><a id='email_dsa_$user->id' href='#' onclick='return Users.SelectAll(\"email\",\"$user->id\",false);' style='display:none'>Unselect All</a>";
  }
  $html .= "</span></td>";

  $html .= "</tr>";

  foreach (Options::$follow_settings as $type=>$label)
  {
    $html .= "<tr class='check_row'><td class='content_type'>$label</td>";
    if (!$ismiio)
    {
      if ($type==Enum::$follow_settings['reply'])
      {
        $html .= "<td></td>";
      }
      else
      {
        $html .= "<td><input type='checkbox' name='dashboard_".$type."_".$user->id."' id='dashboard_".$type."_".$user->id."'";
        if ($Preferences['dashboard'][$type]) $html .= ' checked';
        if ($muted) $html .= ' disabled';
        $html .= "></td>";
      }
    }
    $html .= "<td><input type='checkbox' name='sms_".$type."_".$user->id."' id='sms_".$type."_".$user->id."'";
    if ($Preferences['sms'][$type]) $html .= ' checked';
    if ($nosms || $muted) $html .= " disabled";
    $html .= "></td>";
    $html .= "<td><input type='checkbox' name='email_".$type."_".$user->id."' id='email_".$type."_".$user->id."'";
    if ($Preferences['email'][$type]) $html .= ' checked';
    if ($muted) $html .= ' disabled';
    $html .= "></td>";
    $html .= "</tr>";
  }
  $html .= "</table>";

  $html .= "<input type='hidden' id='sms_ok_$user->id' value=".($nosms?'0':'1').">";

  $html .= "</div>";

  if ($nosms)
  {
    $html .= "<div class='sms_note'><span>*</span> To receive SMS Text messages, you must first enable your mobile phone. You can do that <a href='".$LOC."#settings/mobile'>here</a>.</div>";
  }

  $html .= "<div class='submit'>";

  $html .= "<button class='short_button' name='update_$user->id' onclick='return Users.UpdatePreferences(\"$user->id\");'>Update</button>";
  $html .= "</div>";

  if ($User->notification_sms!="" && !$User->sms_confirmed)
  {
    $html .= "<div class='confirm_sms'>You must confirm your mobile phone in order to receive text updates for this feed. You can do that <a href='".$LOC."#settings/mobile'>here</a>.</div>";
  }

  $html .= "</div>";

  return $html;
}

function ListGroupPreferences($User,$group)
{
  global $LOC;
  $nosms = ($User->notification_sms == "");
  $Preferences = $User->getMemberSettings($group->id);
  $muted = $Preferences['muted'];

  $html = "<div class='list_preferences'>";
  $html .= "<a href='#' class='close' onclick='return Users.ClosePreferences(\"$group->id\")'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  $html .= "<h2>Manage Membership</h2>";

  $html .= "<input type='hidden' id='is_group_$group->id' value=1>";
  if ($User->isOwnerOf($group->id))
  {
    $html .= "<label class='owner'>Group Owner</label>";
  }
  else if ($User->isAdminOf($group->id))
  {
    $html .= "<label class='admin'>Group Administrator</label>";
    $html .= "<a href='#' onclick='return Users.LeaveGroup(this,\"$group->id\",\"$group->username\");'>Leave Group</a>";
  }
  else
  {
    $html .= "<label class='member'>Group Member</label>";
    $html .= "<a href='#' onclick='return Users.LeaveGroup(this,\"$group->id\",\"$group->username\");'>Leave Group</a>";
  }

  $html .= "<div id='mute_container_$group->id' class='mute_container";
  if ($muted) $html .= " muted";
  $html .= "'><table>";
  $html .= "<tr><th class='label'></th><th class='opt'>On</th><th class='opt'>Off</th><th class='info'></th></tr>";
  $html .= "<tr><td class='label'>Mute:";
  if ($muted)
  {
    $html .= "<img src='images/mute.png' alt='mute' id='mute_icon_$group->id'>";
    $html .= "<img src='images/mute_off.png' alt='mute' id='mute_off_icon_$group->id' style='display:none'>";
  }
  else
  {
    $html .= "<img src='images/mute.png' alt='mute' id='mute_icon_$group->id' style='display:none'>";
    $html .= "<img src='images/mute_off.png' alt='mute' id='mute_off_icon_$group->id'>";
  }
  $html .= "</td><td class='opt'>";
  $html .= "<input type='radio' name='mute_$group->id' id='mute_on_$group->id' onchange='Users.ChangeMute(\"$group->id\",true);'";
  if ($muted) $html .= " checked";
  $html .= "></td><td class='opt'>";
  $html .= "<input type='radio' name='mute_$group->id' id='mute_off_$group->id' onchange='Users.ChangeMute(\"$group->id\",false);'";
  if (!$muted) $html .= " checked";
  $html .= "></td><td class='info'>";
  if ($group->is_group) $html .= "Mute $group->username but remain a member";
  else $html .= "Mute $group->username but continue following";
  $html .= "</td></tr></table></div>";

  $html .= "<div class='message_options'>";
  $html .= "<div class='message_options_text'><p>";
  if ($group->is_group) $html .= "Choose the types of content you would like to receive from the <a href='groups/view/$group->id'>$group->username</a> group and the delivery method";
  else $html .= "Choose the types content you would like to receive from <a href='members/profile/$group->id'>$group->username</a> and the delivery method";
  $html .= "</p></div>";
  $html .= "<table>";

  $html .= "<tr><th class='content_type'>Content</th>";
  if (!$ismiio) $html .= "<th>Dashboard</th>";
  $html .= "<th>SMS";
  if ($nosms) $html .= " <span>*</span>";
  $html .= "</th><th>Email</th></tr>";

  $miioall = true;
  $smsall = true;
  $emailall = true;

  foreach (Options::$member_settings as $type=>$label)
  {
    if ($type==Enum::$member_settings['rss']) continue;
    if ($type!=Enum::$member_settings['reply'])
    {
      if (!$Preferences['dashboard'][$type]) $miioall = false;
    }
    if (!$Preferences['sms'][$type]) $smsall = false;
    if (!$Preferences['email'][$type]) $emailall = false;
  }

  $html .= "<tr class='select_all'><td>&nbsp;</td>";
  $html .= "<td class='col'>";
  if ($muted) $html .= "<span id='dashboardsa_$group->id' style='display:none'>";
  else $html .= "<span id='dashboardsa_$group->id'>";
  if ($miioall)
  {
    $html .= "<a id='dashboard_sa_$group->id' href='#' onclick='return Users.SelectAll(\"dashboard\",\"$group->id\",true);' style='display:none'>Select All</a><a id='dashboard_dsa_$group->id' href='#' onclick='return Users.SelectAll(\"dashboard\",\"$group->id\",false);'>Unselect All</a>";
  }
  else
  {
    $html .= "<a id='dashboard_sa_$group->id' href='#' onclick='return Users.SelectAll(\"dashboard\",\"$group->id\",true);'>Select All</a><a id='dashboard_dsa_$group->id' href='#' onclick='return Users.SelectAll(\"dashboard\",\"$group->id\",false);' style='display:none'>Unselect All</a>";
  }
  $html .= "</span></td>";
  if ($nosms) $html .= "<td class='col'></td>";

  $html .= "<td class='col'>";
  if ($muted) $html .= "<span id='emailsa_$group->id' style='display:none'>";
  else $html .= "<span id='emailsa_$group->id'>";
  if ($emailall)
  {
    $html .= "<a id='email_sa_$group->id' href='#' onclick='return Users.SelectAll(\"email\",\"$group->id\",true);' style='display:none'>Select All</a><a id='email_dsa_$group->id' href='#' onclick='return Users.SelectAll(\"email\",\"$group->id\",false);'>Unselect All</a>";
  }
  else
  {
    $html .= "<a id='email_sa_$group->id' href='#' onclick='return Users.SelectAll(\"email\",\"$group->id\",true);'>Select All</a><a id='email_dsa_$group->id' href='#' onclick='return Users.SelectAll(\"email\",\"$group->id\",false);' style='display:none'>Unselect All</a>";
  }
  $html .= "</span></td>";

  $html .= "</tr>";

  foreach (Options::$member_settings as $type=>$label)
  {
    if ($type==Enum::$member_settings['rss']) continue;
    $html .= "<tr class='check_row'><td class='content_type'>$label</td>";
    if ($type==Enum::$member_settings['reply'])
    {
      $html .= "<td></td>";
    }
    else
    {
      $html .= "<td><input type='checkbox' name='dashboard_".$type."_".$group->id."' id='dashboard_".$type."_".$group->id."'";
      if ($Preferences['dashboard'][$type]) $html .= ' checked';
      if ($muted) $html .= ' disabled';
      $html .= "></td>";
    }
    $html .= "<td><input type='checkbox' name='sms_".$type."_".$group->id."' id='sms_".$type."_".$group->id."'";
    if ($Preferences['sms'][$type]) $html .= ' checked';
    if ($nosms || $muted) $html .= " disabled";
    $html .= "></td>";
    $html .= "<td><input type='checkbox' name='email_".$type."_".$group->id."' id='email_".$type."_".$group->id."'";
    if ($Preferences['email'][$type]) $html .= ' checked';
    if ($muted) $html .= ' disabled';
    $html .= "></td>";
    $html .= "</tr>";
  }
  $html .= "</table>";

  $html .= "<input type='hidden' id='sms_ok_$group->id' value=".($nosms?'0':'1').">";

  $html .= "</div>";

  if ($nosms)
  {
    $html .= "<div class='sms_note'><span>*</span> To receive SMS Text messages, you must first enable your mobile phone. You can do that <a href='".$LOC."#settings/mobile'>here</a>.</div>";
  }

  $html .= "<div class='submit'>";

  $html .= "<button class='short_button' name='update_$group->id' onclick='return Users.UpdatePreferences(\"$group->id\");'>Update</button>";
  $html .= "</div>";

  if ($User->notification_sms!="" && !$User->sms_confirmed)
  {
    $html .= "<div class='confirm_sms'>You must confirm your mobile phone in order to receive text updates for this feed. You can do that <a href='".$LOC."#settings/mobile'>here</a>.</div>";
  }

  $html .= "</div>";

  return $html;
}

?>