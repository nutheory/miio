<? global $Group, $User, $GET; ?>
<?
$Membership = $User->getMemberSettings($Group->id);
$nosms = ($User->notification_sms == "");
$muted = $Membership['muted'];
?>

<?if ($GET['type']=='leave') { ?>
  <div class="response_text">You are no longer a member of <?= $Group->groupname ?>.<br><br>Return to <a href="user">dashboard</a>.</div>
<? } else { ?>

  <div id="manage_membership">
    <div id="mute_container" class="mute_container ">
      <table>
        <tr>
          <th class="label"></th>
          <th class="opt">On</th>
          <th class="opt">Off</th>
          <th class="info"></th>
        </tr>

        <tr>
          <td class="label">
            Mute:
            <? if ($muted) { ?>
              <img src="images/mute.png" alt="mute" id="mute_icon">
              <img src="images/mute_off.png" alt="mute" id="mute_off_icon" style="display:none">
            <? } else { ?>
              <img src="images/mute.png" alt="mute" id="mute_icon" style="display:none">
              <img src="images/mute_off.png" alt="mute" id="mute_off_icon">
            <? } ?>
          </td>
          <? if ($muted) { ?>
            <td class="opt">
              <input type="radio" name="mute" id="preference_mute_on" onchange="Group.ManageMembership.ChangeMute(true);" checked>
            </td>
            <td class="opt">
              <input type="radio" name="mute" id="preference_mute_off" onchange="Group.ManageMembership.ChangeMute(false);">
            </td>
          <? } else { ?>
            <td class="opt">
              <input type="radio" name="mute" id="preference_mute_on" onchange="Group.ManageMembership.ChangeMute(true);">
            </td>
            <td class="opt">
              <input type="radio" name="mute" id="preference_mute_off" onchange="Group.ManageMembership.ChangeMute(false);" checked>
            </td>
          <? } ?>
          <td class="info">
            Mute <?= $Group->groupname ?> but remain a member
          </td>
        </tr>
      </table>
    </div>

    <div id="membership_preference_options" class="message_options <? if ($muted) echo 'muted'; ?>">
      <div class="message_options_text">
        Choose the types of content you would like to receive from the <a href="<?= $Group->getProfileLink() ?>"><?= $Group->groupname ?></a> group and the delivery method
      </div>

      <table>
        <tr>
          <th class="label">Content</th>
          <th>Dashboard</th>
          <th>SMS <? if ($nosms) { ?><span>*</span><? } ?></th>
          <th>Email</th>
        </tr>

        <tr class="select_all">
          <td></td>
          <td class="opt">
            <a id="dashboard_sa" href="#" onclick="return Group.ManageMembership.SelectAll('dashboard',true);" <? if ($muted) echo 'style="display:none"'; ?>>Select All</a>
            <a id="dashboard_dsa" href="#" onclick="return Group.ManageMembership.SelectAll('dashboard',false);" style="display:none">Unselect All</a>
          </td>
          <? if ($nosms) { ?>
            <td class="opt"></td>
          <? } else { ?>
            <td class="opt">
              <a id="sms_sa" href="#" onclick="return Group.ManageMembership.SelectAll('sms',true);" <? if ($muted) echo 'style="display:none"'; ?>>Select All</a>
              <a id="sms_dsa" href="#" onclick="return Group.ManageMembership.SelectAll('sms',false);" style="display:none">Unselect All</a>
            </td>
          <? } ?>
          <td class="opt">
            <a id="email_sa" href="#" onclick="return Group.ManageMembership.SelectAll('email',true);" <? if ($muted) echo 'style="display:none"'; ?>>Select All</a>
            <a id="email_dsa" href="#" onclick="return Group.ManageMembership.SelectAll('email',false);" style="display:none">Unselect All</a>
          </td>
        </tr>
        <?
          if (!$muted)
          {
            $dashboardcheck = true;
            $smscheck = true;
            $emailcheck = true;
          }
        ?>
        <? foreach (Options::$member_settings as $type=>$label) { ?>
          <? if ($type==Enum::$member_settings['admin'] && !$User->isAdminOf($Group->id)) continue; ?>
          <tr class="content_item">
            <td class="label"><?= $label ?></td>
            <? if ($type==Enum::$member_settings['admin']) { ?>
              <td class="opt"></td>
            <? } else { ?>
              <td class="opt">
                <input type="checkbox" name="dashboard_<?= $type ?>" id="dashboard_<?= $type ?>" <? if ($Membership['dashboard'][$type]) echo 'checked'; else $dashboardcheck = false; ?> <? if ($muted) echo 'disabled'; ?>>
              </td>
            <? } ?>
            <td class="opt">
              <? if ($nosms) { ?>
                <input type="checkbox" name="sms_<?= $type ?>" id="sms_<?= $type ?>" disabled>
              <? } else { ?>
                <input type="checkbox" name="sms_<?= $type ?>" id="sms_<?= $type ?>" <? if ($Membership['sms'][$type]) echo 'checked'; else $smscheck = false; ?> <? if ($muted) echo 'disabled'; ?>>
              <? } ?>
            </td>
            <td class="opt">
              <input type="checkbox" name="email_<?= $type ?>" id="email_<?= $type ?>" <? if ($Membership['email'][$type]) echo 'checked'; else $emailcheck = false; ?> <? if ($muted) echo 'disabled'; ?>>
            </td>
          </tr>
        <? } ?>
      </table>
      <input type="hidden" id="dashboard_check" value=<?= $dashboardcheck?'1':'0' ?>>
      <input type="hidden" id="sms_check" value=<?= $smscheck?'1':'0' ?>>
      <input type="hidden" id="email_check" value=<?= $emailcheck?'1':'0' ?>>
      <input type="hidden" id="sms_ok" value=<?= $nosms?'0':'1' ?>>
    </div>

    <? if ($nosms) { ?>
      <div class="sms_note"><span>*</span> To receive SMS Text messages, you must first enable your mobile phone. You can do that <a href="<?= LOC ?>#settings/mobile">here</a>.</div>
    <? } ?>

    <div class="submit">
      <button class="short_button" name="cancel" onclick="return Group.ManageMembership.Close();">Cancel</button>
      <button class="short_button" name="update" onclick="return Group.ManageMembership.FormSubmit();">Update</button>
    </div>

    <? if ($User->notification_sms!='' && !$User->sms_confirmed) { ?>
      <div class="sms_note">You must confirm your mobile phone in order to receive text updates for this feed. You can do that <a href="<?= LOC ?>#settings/mobile">here</a>.</div>
    <? } ?>

  </div>

<? } ?>
