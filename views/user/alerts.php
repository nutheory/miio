<? global $User; ?>
<div id="alert_content">
  <table id="alert">
    <tr>
      <td>

        <div class="alert_desc">
          <p>Miio Alerts are notifications of the latest relevant results based on your choice of keyword or phrase.</p>
          <p>Please enter your keyword or phrase. Then choose the types of content you would like to receive and the Alert delivery method.</p>
          <p>For example if you enter &quot;Lakers&quot; as your keyword and then select &quot;Photos&quot; and &quot;Videos&quot;. Every time a Lakers photo or video is uploaded you will get a real time alert</p>
        </div>
        <div class="alert_form_header">Create Alert</div>
        <table class="alert_form">
          <tr>
            <td class="alert_form_label">
              <label class="head">Keyword / Phrase:</label>
              <span class="note">(30 char max)</span>
              <input type="text" name="alert_keyword" id="alert_keyword" maxlength=30>
            </td>
            <td class="alert_form_contenttype">
              <label class="head">Select Content Type:</label>
              <table id="alert_form_contenttype">
                <tr>
                  <td class="all">
                    <input type="checkbox" id="alert_all" checked onclick="User.Alerts.CheckContentType(this,'all');">
                    <label for="alert_all">All</label>
                  </td>
                  <td>
                    <input type="checkbox" id="alert_text" checked onclick="User.Alerts.CheckContentType(this,'text');">
                    <label for="alert_messages">Text</label>
                  </td>
                  <td>
                    <input type="checkbox" id="alert_photo" checked onclick="User.Alerts.CheckContentType(this,'photo');">
                    <label for="alert_photo">Photo</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="checkbox" id="alert_video" checked onclick="User.Alerts.CheckContentType(this,'video');">
                    <label for="alert_video">Video</label>
                  </td>
                  <td>
                    <input type="checkbox" id="alert_link" checked onclick="User.Alerts.CheckContentType(this,'link');">
                    <label for="alert_links">Link</label>
                  </td>
                  <td>
                    <input type="checkbox" id="alert_review" checked onclick="User.Alerts.CheckContentType(this,'review');">
                    <label for="alert_review">Review</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="checkbox" id="alert_question" checked onclick="User.Alerts.CheckContentType(this,'question');">
                    <label for="alert_question">Question</label>
                  </td>
                  <td>
                    <input type="checkbox" id="alert_share" checked onclick="User.Alerts.CheckContentType(this,'share');">
                    <label for="alert_share">Share</label>
                  </td>
                  <td>
                    <input type="checkbox" id="alert_rss" checked onclick="User.Alerts.CheckContentType(this,'rss');">
                    <label for="alert_rss">RSS Feed</label>
                  </td>
                </tr>
                <tr>
                  <td colspan=3>
                    <input type="checkbox" id="alert_location" checked onclick="User.Alerts.CheckContentType(this,'location');">
                    <label for="alert_location">Location Update</label>
                  </td>
                </tr>
              </table>
            </td>
            <td class="alert_form_notify">
              <label class="head">Notify by:</label>
              <table>
                <tr>
                  <td>
                    <input type="checkbox" id="alert_dashboard" checked>
                    <label for="alert_dashboard">Dashboard</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="checkbox" id="alert_email">
                    <label for="alert_email">Email</label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <? if ($User->sms_confirmed) { ?>
                      <input type="checkbox" id="alert_sms">
                      <label for="alert_sms">SMS</label>
                    <? } else { ?>
                      <input type="checkbox" id="alert_sms" disabled>
                      <label for="alert_sms" class="disabed">SMS</label>
                    <? } ?>
                  </td>
                </tr>
              </table>
            </td>
            <td class="alert_form_submit">
              <input type="submit" name="submit" id="alert_submit" value="Save Alert" onclick="User.Alerts.Save()">
            </td>
          </tr>
        </table>
        <span class="note">Note: You will not receive alerts for messages that <b>you</b> post containing these keywords or phrases.</span>
        <? if (!$User->sms['is_confirmed']) { ?>
          <div class="sms_note">You cannot receive alerts by SMS until you have entered and confirmed your mobile phone number. You can do that <a href="#" onclick="return User.Alerts.ConfirmSMS()">here</a></div>
        <? } ?>
        <div class="alert_list_header">Alerts</div>
        <? $Alerts = $User->getAlerts(); ?>
        <? if (count($Alerts) > 0) { ?>
          <table class="alerts">
            <tr>
              <th>Keyword / Phrase</th>
              <th>Content types</th>
              <th>Notify by</th>
              <th>&nbsp;</th>
            </tr>
            <? foreach ($Alerts as $alert) { ?>
              <tr id="show_<?= $alert['alert_id'] ?>" class="alert_item <? if ($alert['paused']) echo 'paused';?>">
                <td class="alert_text">
                  <a href="search?q=<?= $alert['keyword'] ?>"><?= $alert['keyword'] ?></a>
                  <? if ($alert['paused']) echo "&nbsp;&nbsp;(paused)"; ?>
                </td>
                <td class="alert_contenttype">
                  <?
                    $contenttype = "";
                    $all = true;
                    $comma = false;
                    foreach (Options::$alert_type as $key=>$val)
                    {
                      if ($alert[$key])
                      {
                        if ($comma) $contenttype .= ", ";
                        $contenttype .= $val;
                        $comma = true;
                      }
                      else $all = false;
                    }
                    if ($all) $contenttype = "All content types";
                    echo $contenttype;
                  ?>
                </td>
                <td class="alert_method">
                  <?
                    $alertstr = "";
                    if ($alert['dashboard']) $alertstr .= "Dashboard";
                    if ($alert['email']) { if ($alertstr!="") $alertstr.=", "; $alertstr .= "Email"; }
                    if ($alert['sms']) { if ($alertstr!="") $alertstr.=", "; $alertstr .= "SMS"; }
                    echo $alertstr;
                  ?>
                </td>
                <td class="alert_options">
                  <? if ($alert['paused']) { ?>
                    <a href="#" onclick="return User.Alerts.Resume('<?= $alert['alert_id'] ?>');">Resume</a>
                  <? } else { ?>
                    <a href="#" onclick="return User.Alerts.Pause('<?= $alert['alert_id'] ?>');">Pause</a>
                  <? } ?>
                  |
                  <a href="#" onclick="return User.Alerts.Edit('<?= $alert['alert_id'] ?>',true);">Edit</a>
                  |
                  <a href="#" onclick="return User.Alerts.Delete('<?= $alert['alert_id'] ?>','<?= $alert['keyword'] ?>');">Delete</a>
                </td>
              </tr>
              <tr id="edit_<?= $alert['alert_id'] ?>" class="alert_editform <? if ($alert['paused']) echo 'paused';?>" style="display:none;">
                <td class="alert_keyword">
                  <span><?= $alert['keyword'] ?></span>
                </td>
                <td class="alert_content_type">
                  <table id="alert_form_contenttype_<?= $alert['alert_id'] ?>">
                    <tr>
                      <td class="all">
                        <input type="checkbox" id="alert_all_<?= $alert['alert_id'] ?>" <? if ($all) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'all','<?= $alert['alert_id'] ?>');">
                        <label for="alert_all">All</label>
                      </td>
                      <td>
                        <input type="checkbox" id="alert_text_<?= $alert['alert_id'] ?>" <? if ($alert['text']) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'text','<?= $alert['alert_id'] ?>');">
                        <label for="alert_messages">Texts</label>
                      </td>
                      <td>
                        <input type="checkbox" id="alert_photo_<?= $alert['alert_id'] ?>" <? if ($alert['photo']) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'photo','<?= $alert['alert_id'] ?>');">
                        <label for="alert_photo">Photos</label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input type="checkbox" id="alert_video_<?= $alert['alert_id'] ?>" <? if ($alert['video']) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'video','<?= $alert['alert_id'] ?>');">
                        <label for="alert_video">Videos</label>
                      </td>
                      <td>
                        <input type="checkbox" id="alert_link_<?= $alert['alert_id'] ?>" <? if ($alert['link']) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'link','<?= $alert['alert_id'] ?>');">
                        <label for="alert_links">Links</label>
                      </td>
                      <td>
                        <input type="checkbox" id="alert_review_<?= $alert['alert_id'] ?>" <? if ($alert['review']) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'review','<?= $alert['alert_id'] ?>');">
                        <label for="alert_review">Reviews</label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input type="checkbox" id="alert_question_<?= $alert['alert_id'] ?>" <? if ($alert['question']) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'question','<?= $alert['alert_id'] ?>');">
                        <label for="alert_question">Questions</label>
                      </td>
                      <td>
                        <input type="checkbox" id="alert_share_<?= $alert['alert_id'] ?>" <? if ($alert['share']) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'share','<?= $alert['alert_id'] ?>');">
                        <label for="alert_share">Shares</label>
                      </td>
                      <td>
                        <input type="checkbox" id="alert_rss_<?= $alert['alert_id'] ?>" <? if ($alert['rss']) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'rss','<?= $alert['alert_id'] ?>');">
                        <label for="alert_rss">RSS Feeds</label>
                      </td>
                    </tr>
                    <tr>
                      <td colspan=3>
                        <input type="checkbox" id="alert_location_<?= $alert['alert_id'] ?>" <? if ($alert['location']) echo "checked"; ?> onclick="User.Alerts.CheckContentType(this,'location','<?= $alert['alert_id'] ?>');">
                        <label for="alert_location">Location Updates</label>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="alert_notifyby">
                  <table>
                    <tr>
                      <td>
                        <input type="checkbox" name="dashboard_<?= $alert['alert_id'] ?>" id="dashboard_<?= $alert['alert_id'] ?>" value="miio" <? if ($alert['dashboard']) echo "checked"; ?>>
                        <label for="dashboard_<?= $alert['alert_id'] ?>">Dashboard</label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input type="checkbox" name="email_<?= $alert['alert_id'] ?>" id="email_<?= $alert['alert_id'] ?>" value="email" <? if ($alert['email']) echo "checked"; ?>>
                        <label for="email_<?= $alert['alert_id'] ?>">Email</label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input type="checkbox" name="sms_<?= $alert['alert_id'] ?>" id="sms_<?= $alert['alert_id'] ?>" value="sms" <? if ($alert['sms']) echo "checked"; ?>>
                        <label for="sms_<?= $alert['alert_id'] ?>">SMS</label>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="alert_submitedit">
                  <a href="#" onclick="return User.Alerts.Edit('<?= $alert['alert_id'] ?>',false);">Cancel</a>
                  &nbsp;
                  <input type="submit" name="submit_<?= $alert['alert_id'] ?>" id="submit_<?= $alert['alert_id'] ?>" onclick="User.Alerts.Update('<?= $alert['alert_id'] ?>');" value="Update">
                </td>
              </tr>
            <? } ?>
          </table>
        <? } else { ?>
          You have not set up any alerts yet.
        <? } ?>
      </td>
    </tr>
  </table>
</div>
