<? global $Profile, $User, $GET; ?>
<?
$Preferences = $User->getFollowSettings($Profile->id);
$nosms = ($User->notification_sms == "");
$muted = $Preferences['muted'];
if ($Profile->id=='a') $ismiio = true;
?>

<? if ($GET['type']=='unsubscribe') { ?>
  <div class="response_text">You are no longer following <?= $Profile->username ?>. Return to <a href="user">dashboard</a>.</div>
<? } else { ?>
  <div id="manage_subscription">
    <? if (!$ismiio) { ?>
      <div id="mute_container" class="mute_container">
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
                <input type="radio" name="mute" id="preference_mute_on" onchange="Profile.ManageSubscription.ChangeMute(true);" checked>
              </td>
              <td class="opt">
                <input type="radio" name="mute" id="preference_mute_off" onchange="Profile.ManageSubscription.ChangeMute(false);">
              </td>
            <? } else { ?>
              <td class="opt">
                <input type="radio" name="mute" id="preference_mute_on" onchange="Profile.ManageSubscription.ChangeMute(true);">
              </td>
              <td class="opt">
                <input type="radio" name="mute" id="preference_mute_off" onchange="Profile.ManageSubscription.ChangeMute(false);" checked>
              </td>
            <? } ?>
            <td class="info">
              Mute <?= $Profile->username ?> but continue following
            </td>
          </tr>
        </table>
      </div>
    <? } ?>

    <div id="subscription_preference_options" class="message_options <? if ($muted) echo 'muted'; ?>">
      <div class="message_options_text">
        Choose the types of content you would like to receive from <a href="members/profile/<?= $Profile->id ?>"><?= $Profile->username ?></a> and the delivery method
      </div>

      <table>
        <tr>
          <th class="label">Content</th>
          <? if (!$ismiio) { ?><th>Dashboard</th><? } ?>
          <th>SMS <? if ($nosms) { ?><span>*</span><? } ?></th>
          <th>Email</th>
        </tr>

        <tr class="select_all">
          <td></td>
          <? if (!$ismiio) { ?>
            <td class="opt">
              <a id="dashboard_sa" href="#" onclick="return Profile.ManageSubscription.SelectAll('dashboard',true);" <? if ($muted) echo 'style="display:none"'; ?>>Select All</a>
              <a id="dashboard_dsa" href="#" onclick="return Profile.ManageSubscription.SelectAll('dashboard',false);" style="display:none">Unselect All</a>
            </td>
          <? } ?>

          <? if ($nosms) { ?>
            <td class="opt"></td>
          <? } else { ?>
            <td class="opt">
              <a id="sms_sa" href="#" onclick="return Profile.ManageSubscription.SelectAll('sms',true);" <? if ($muted) echo 'style="display:none"'; ?>>Select All</a>
              <a id="sms_dsa" href="#" onclick="return Profile.ManageSubscription.SelectAll('sms',false);" style="display:none">Unselect All</a>
            </td>
          <? } ?>
          <td class="opt">
            <a id="email_sa" href="#" onclick="return Profile.ManageSubscription.SelectAll('email',true);" <? if ($muted) echo 'style="display:none"'; ?>>Select All</a>
            <a id="email_dsa" href="#" onclick="return Profile.ManageSubscription.SelectAll('email',false);" style="display:none">Unselect All</a>
          </td>
        </tr>
        <?
          $dashboardcheck = true;
          $smscheck = true;
          $emailcheck = true;
        ?>
        <? foreach (Options::$follow_settings as $type=>$label) { ?>
          <tr class="content_item">
            <td class="label"><?= $label ?></td>
            <? if (!$ismiio) { ?>
              <? if ($type==Enum::$follow_settings['reply']) { ?>
                <td class="opt"></td>
              <? } else { ?>
                <td class="opt">
                  <input type="checkbox" name="dashboard_<?= $type ?>" id="dashboard_<?= $type ?>" <? if ($Preferences['dashboard'][$type]) echo 'checked'; else $dashboardcheck = false; ?> <? if ($muted) echo 'disabled'; ?>>
                </td>
              <? } ?>
            <? } ?>
            <td class="opt">
              <? if ($nosms) { ?>
                <input type="checkbox" name="sms_<?= $type ?>" id="sms_<?= $type ?>" disabled>
              <? } else { ?>
                <input type="checkbox" name="sms_<?= $type ?>" id="sms_<?= $type ?>" <? if ($Preferences['sms'][$type]) echo 'checked'; else $smscheck = false; ?> <? if ($muted) echo 'disabled'; ?>>
              <? } ?>
            </td>
            <td class="opt">
              <input type="checkbox" name="email_<?= $type ?>" id="email_<?= $type ?>" <? if ($Preferences['email'][$type]) echo 'checked'; else $emailcheck = false; ?> <? if ($muted) echo 'disabled'; ?>>
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
      <button class="short_button" name="cancel" onclick="return Profile.ManageSubscription.Close();">Cancel</button>
      <button class="short_button" name="update" onclick="return Profile.ManageSubscription.FormSubmit();">Update</button>
    </div>

    <? if ($User->notification_sms!='' && !$User->sms_confirmed) { ?>
      <div class="sms_note">You must confirm your mobile phone in order to receive text updates for this feed. You can do that <a href="<?= LOC ?>#settings/mobile">here</a>.</div>
    <? } ?>

  </div>

<? } ?>
