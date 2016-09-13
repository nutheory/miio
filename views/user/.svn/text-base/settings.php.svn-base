<? global $User, $PARAMS, $GET; ?>
<? $Page = $GET['type']; ?>
<div id="settings_content">
<? /******************************* PROFILE  *******************************/ ?>
<? if ($PARAMS=='profileinfo') { ?>
  <input type="hidden" id="profile_username" value="<?= $User->username ?>">
  <div id="settings_profile">
    <p class="header">
      You can change this form at any time.
    </p>
    <div class="form_section">
      <div>
        <label for="full_name">Your Name</label>
        <input type="text" id="full_name" value="<?= $User->name ?>" maxlength=100>
      </div>
      <div class="option_subdued">
        <input type="checkbox" id="show_name" <? if ($User->show_name) echo "checked"; ?>>
          <label class="ch">Show name on profile.</label>
      </div>
    </div>
    <div class="form_section">
      <div>
        <label class="ab" for="description">About</label>
        <div class="about_count" id="description_count"><?= 140-strlen($User->description) ?></div>
        <div class="about_text">Tell us about yourself, in 140 characters or less.</div>
        <textarea class="full" id="description"  onkeyup="return User.Settings.Profile.Count(event,this,'description_count');"><?= $User->description ?></textarea>
      </div>
    </div>
    <div class="form_section">
      <div>
        <label for="birthday" class="sel">Birthday</label>
        <? $birthday = explode('-',$User->birthday); ?>
        <select id="day">
          <option value="">Day</option>
          <? for ($d=1;$d<32;$d++) { ?>
            <? if ($d == $birthday[2]) { ?>
              <option value="<?= $d ?>" selected><?= $d ?></option>
            <? } else { ?>
              <option value="<?= $d ?>"><?= $d ?></option>
            <? } ?>
          <? } ?>
        </select>
        <select id="month">
          <option value="">Month</option>
          <? foreach (Options::$months as $m=>$mo) { ?>
            <? if ($m == $birthday[1]) { ?>
              <option value="<?= $m ?>" selected><?= $mo ?></option>
            <? } else { ?>
              <option value="<?= $m ?>"><?= $mo ?></option>
            <? } ?>
          <? } ?>
        </select>
        <select id="year">
          <option value="">Year</option>
          <? $thisyear = date('Y',time()); ?>
          <? for ($y=$thisyear-14;$y>$thisyear-101;$y--) { ?>
            <? if ($y == $birthday[0]) { ?>
              <option value="<?= $y ?>" selected><?= $y ?></option>
            <? } else { ?>
              <option value="<?= $y ?>"><?= $y ?></option>
            <? } ?>
          <? } ?>
        </select>
      </div>
      <div>
        <label for="gender">Gender</label>
        <? if ($User->gender == Enum::$gender['male']) { ?>
          <input class="hor" type="radio" name="gender" id="male" value="<?= Enum::$gender['male'] ?>" checked><label class="hor" for="male">Male</label>
          <input class="hor" type="radio" name="gender" id="female" value="<?= Enum::$gender['female'] ?>"><label class="hor" for="female">Female</label>
        <? } else if ($User->gender == Enum::$gender['female']) { ?>
          <input class="hor" type="radio" name="gender" id="male" value="<?= Enum::$gender['male'] ?>"><label class="hor" for="male">Male</label>
          <input  class="hor" type="radio" name="gender" id="female" value="<?= Enum::$gender['female'] ?>" checked><label class="hor" for="female">Female</label>
        <? } else { ?>
          <input class="hor" type="radio" name="gender" id="male" value="<?= Enum::$gender['male'] ?>"><label class="hor" for="male">Male</label>
          <input class="hor" type="radio" name="gender" id="female" value="<?= Enum::$gender['female'] ?>"><label class="hor" for="female">Female</label>
        <? } ?>
      </div>
      <div>
        <label for="ethnicity" class="sel">Ethnicity</label>
        <select id="ethnicity">
          <option value="">-</option>
          <? foreach (Options::$ethnicity as $opt=>$val) { ?>
            <option value="<?= Enum::$ethnicity[$opt] ?>" <? if($User->ethnicity==Enum::$ethnicity[$opt]) echo 'selected'; ?>><?= $val ?></option>
          <? } ?>
        </select>
      </div>
    </div>
    <div class="form_section">
      <div>
        <label for="country">Country</label>
        <input type="text" id="country" onkeydown="return User.Settings.Country.ProcessTab(event,this)" onkeyup="User.Settings.Country.Suggest(event,this)" onfocus="User.Settings.Country.Suggest(event,this)" onblur="User.Settings.Country.Clear(this);User.Settings.ChangeCountry(this);"autocomplete="off" value="<?= $User->country_name ?>">
        <div id="Country" style="display:none" class="autofill"></div>
      </div>
      <div>
        <label for="state">State/Province/Region</label>
        <input type="text" id="state" onkeydown="return User.Settings.State.ProcessTab(event,this)" onkeyup="User.Settings.State.Suggest(event,this)" onfocus="User.Settings.State.Suggest(event,this)" onblur="User.Settings.State.Clear(this);User.Settings.ChangeState(this)" autocomplete="off" value="<?= $User->state_name ?>">
        <div id="State" style="display:none" class="autofill"></div>
      </div>
      <div>
        <label for="city">City</label>
        <input type="text" id="city" onkeydown="return User.Settings.City.ProcessTab(event,this)" onkeyup="User.Settings.City.Suggest(event,this)" onfocus="User.Settings.City.Suggest(event,this)" onblur="User.Settings.City.Clear(this);" autocomplete="off" value="<?= $User->city_name ?>">
        <div id="City" style="display:none" class="autofill"></div>
      </div>
    </div>
    <div class="form_section">
      <div>
        <label for="website">Website</label>
        <input type="text" id="website" class="website" value="<?= $User->website ?>">
        <h6>Example: http://www.miio.com. Up to 255 characters in length.</h6>
      </div>
      <div>
        <label for="">Looking for</label>
        <ul class="lf">
          <? foreach (Enum::$looking_for as $opt=>$val) { ?>
            <li>
              <input type="checkbox" id="lf_<?= $opt ?>" <? if($User->looking_for[$val]) echo 'checked'; ?>>
              <label class="ch" for="lf_<?= opt ?>"><?= Options::$looking_for[$val] ?></label>
            </li>
          <? } ?>
        </ul>
      </div>
      <div>
        <label class="sel" for="relationship">Relationship</label>
        <select id="relationship">
          <option value="">-</option>

          <? foreach (Options::$relationship as $opt=>$val) { ?>
            <option value="<?= Enum::$relationship[$opt] ?>" <? if($User->relationship==Enum::$relationship[$opt]) echo 'selected'; ?>><?= $val ?></option>
          <? } ?>
        </select>
      </div>
      <div>
        <label>Interested in</label>
        <input class="hor_last" type="checkbox" id="interested_male" <? if($User->interested_in['male']) echo 'checked'; ?>>
        <label class="hor_last" for="interested_male">Men</label>
        <input class="hor_last" type="checkbox" id="interested_female" <? if($User->interested_in['female']) echo 'checked'; ?>>
        <label class="hor_last" for="interested_female">Women</label>
      </div>
    </div>
    <div class="form_section">
      <div class="attention">
        <h3>Profile Privacy</h3>
        <p>Setting your profile to &quot;Private&quot; will still display your username and profile photo publicly. Everything else will only be visible to your friends</p>
        <? if ($User->visibility==Enum::$visibility['public']) { ?>
          <input class="hor_last" type="radio" name="visibility" id="public" value="public" checked><label class="hor_last" for="public">Public</label>
          <input class="hor_last" type="radio" name="visibility" id="private" value="private"><label class="rw" for="private">Private (Friends Only)</label>
        <? } else { ?>
          <input class="hor_last" type="radio" name="visibility" id="public" value="public"><label class="hor_last" for="public">Public</label>
          <input class="hor_last" type="radio" name="visibility" id="private" value="private" checked><label class="rw" for="private">Private (Friends Only)</label>
        <? } ?>
      </div>
    </div>
    <div class="form_section">
      <div class="tags">
        <?
          $tags = trim(implode(' ',$User->keywords));
        ?>
        <label class="ab">Keywords</label>
        <div class="tag_count" id="settings_tags_count"><?= 140-strlen($tags) ?></div>
        <div class="about_text">Add keywords about topics that interest you so that other members that are interested in the same things can find you more easily</div>
        <textarea class="full" name="settings_tags" id="settings_tags" onkeyup="return User.Settings.Profile.Count(event,this,'settings_tags_count');"><?= $tags ?></textarea>
        <span>(Up to 140 characters. Use spaces to seperate the keywords.)</span>
      </div>
    </div>
    <div class="commit">
      <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Profile.FormSubmit()">Update Profile</button>
    </div>
  </div>

<? /******************************** MOBILE ********************************/ ?>
<? } else if ($PARAMS=='mobile') { ?>
  <?
    // TODO: Mobile Settings not fully completed and working
    $sms_entered = (isset($User->sms) && $User->sms['number'] != "");
  ?>

  <div id="mobile">
    <? if (!$sms_entered) { ?>
      <p>
        You can send and read Miio messages on your mobile phone as soon as you confirm your phone on Miio.
      </p>
      <p>
        Enter your mobile phone details below and click "Save Mobile Phone"
      </p>
      <p class="note">
        Miio will not share your phone number with anyone. Miio does not charge for text messaging but your
        mobile phone service provider does. Please check your text messaging plan for specific details.
      </p>
      <div class="form_section">
        <div>
          <label for="country">Country</label>
          <input type="text" class="long" id="country" onkeydown="return User.Settings.Country.ProcessTab(event,this)" onkeyup="User.Settings.Country.Suggest(event,this)" onfocus="User.Settings.Country.Suggest(event,this)" onblur="User.Settings.Country.Clear(this);" onchange="User.Settings.Mobile.ChangeCountry(this);" autocomplete="off" value="<?= $User->home_location['country'] ?>">
          <div id="Country" style="display:none" class="autofill"></div>
        </div>
        <div>
          <? $sms_code = Places::get_sms_code($User->home_location['country']); ?>
          <label for="country">Mobile Number</label>
          <div id="sms_country_code_text"><? if ($sms_code) echo $sms_code; else echo "0"; ?></div>
          <input type="text" id="notification_sms">
          <h6>Numbers only. (i.e. 1235551234)</h6>
          <input type="hidden" id="sms_country_code" value="<? if ($sms_code) echo $sms_code; else echo "0"; ?>">
        </div>
        <div>
          <label for="country">Service Provider</label>
          <input type="text" class="long" id="sms_provider" onkeydown="return User.Settings.SMS.ProcessTab(event,this)" onkeyup="User.Settings.SMS.Suggest(event,this)" onfocus="User.Settings.SMS.Suggest(event,this)" onblur="User.Settings.SMS.Clear(this);" autocomplete="off">
          <div id="providerlist" style="display:none" class="autofill"></div>
          <div class="option_subdued">
            <input type="checkbox" id="sms_web_enabled">
            <label for="sms_web_enabled" class="ch">Web capable phone</label>
          </div>
        </div>
      </div>
      <div class="form_section">
        <h3>Authorization</h3>
        <div class="authorize highlight">
          <input type="checkbox" name="sms_accept_charges" id="sms_accept_charges">
          <label for="sms_accept_charges">Please send text messages to my phone. I understand that I may incur charges from my service provider.</label>
        </div>
      </div>
      <div class="commit">
        <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Mobile.FormSubmit()">Save Mobile Phone</button>
      </div>
      <div class="telecoms">
        <img src="images/carrier_1.jpg" alt="">
        <img src="images/carrier_2.jpg" alt="">
        <img src="images/carrier_3.jpg" alt="">
        <img src="images/carrier_4.jpg" alt="">
      </div>

    <? } else { ?>
      <? if ($User->sms['is_confirmed']) { ?>
        <div class="form_response">
          <p class="mobile_comp">
            Your mobile phone has been confirmed to send and receive Miio messages.
          </p>
          <p class="mobile_comp">
            Text new Miio messages to:
            <strong>
            <?
              if ($User->sms['country']=='US') echo "201 238 0827";
              else if ($User->sms['country']=='CA') echo "705 717 1484";
              else if ($User->sms['country']=='UK') echo "778 148 9670";
              else echo "+44 778 148 9670";
            ?>
            </strong>.
            <a href="help.miio.com">Learn more</a> about sending and receiving Miio messages on your mobile phone.
          </p>

          <ul class="view_section">
            <li>
              <label>Country</label>
              <div><?= Places::get_country_name($User->sms['country']) ?></div>
            </li>
            <li>
              <label>Mobile Number</label>
              <div>
                <?
                  $sms_code = Places::get_sms_code($User->sms['country']);
                  if ($sms_code) echo $sms_code; else echo "0";
                ?>
              </div>
            </li>
            <li>
              <label>Service Provider</label>
              <div><?= Options::$carriers[$User->sms['provider']]['name'] ?></div>
            </li>
            <li>
              <p>Phone is <? if (!$User->sms['web_enabled']) echo "NOT"; ?> Web Capable</p>
            </li>
          </ul>

          <div class="commit">
            <button class="short_button" onclick="return User.Settings.Mobile.RemoveMobile(true);">Change Phone</button>
            <button class="short_button" onclick="return User.Settings.Mobile.RemoveMobile(false);">Remove Phone</button>
          </div>
        </div>
      <? } else { ?>
        <div class="form_response">
          <p>Your mobile phone number has been saved but not yet confirmed. You must confirm your phone before
          you can send and read Miio messages.</p>
          <p>Please click on the &quot;Resend confirmation message&quot; link below and Miio will send a text
          message to your mobile phone. Reply &quot;OK&quot;to confirm your phone on Miio.</p>
          <div class="commit">
            <button class="short_button" onclick="return User.Settings.Mobile.ResendConfirmation();">Resend Confirmation Message</button>
            <button class="short_button" onclick="return User.Settings.Mobile.RemoveMobile(false);">Remove Phone</button>
          </div>
          <div class="link_center">
            <a class="dash" href="user">Go to Timeline</a> |
            <a href="pages/contact/bug_report?page=settings_mobile">Report problems with this form</a>
          </div>
        </div>
        <p id="mobile_resent" style="display:none">
          A new confirmation message has been sent to your mobile number. Reply 'OK' to confirm your phone.
        </p>
      <? } ?>
    <? } ?>
  </div>

<? /*************************** MESSAGE SETTINGS ***************************/ ?>
<? } else if ($PARAMS=='message') { ?>
  <div id="notifications">
    <? $nosms = (!isset($User->sms) || $User->sms['number'] == ""); ?>
    <p>Check the boxes below to customize how you receive email or SMS notifications for members you are not following.</p>
    <p class="notice">These are your general settings for messages and notifications throughout Miio. You can override these settings individually for Members you follow or Groups you join from the &quot;Manage&quot; link on their profile page.</p>

    <table id="notification_options">
      <tr>
        <th class="restore"><button class="short_button" onclick="User.Settings.Messages.RestoreDefaults()">Restore Defaults</button></th>
        <th class="optcol">Email</th>
        <th class="optcol">SMS</th>
      </tr>

      <tr class="selectrow">
        <td class="optlabel"></td>
        <td class="optcol">
          <a href="#" id="email_select" style="display:none" onclick="return User.Settings.Messages.SelectAll('email',true);">Select All</a>
          <a href="#" id="email_unselect" onclick="return User.Settings.Messages.SelectAll('email',false);">Unselect All</a>
        </td>
        <td class="optcol">
          <? if (!$nosms) { ?>
            <a href="#" id="sms_select" style="display:none" onclick="return User.Settings.Messages.SelectAll('sms',true);">Select All</a>
            <a href="#" id="sms_unselect" onclick="return User.Settings.Messages.SelectAll('sms',false);">Unselect All</a>
          <? } ?>
        </td>
      </tr>
      <?
        $email_all = true;
        $sms_all = ($nosms ? false : true);
      ?>

      <? foreach (Options::$message_preferences as $key=>$text) { ?>
        <tr class="check_row">
          <td class="optlabel"><?= $text ?></td>
          <td class="optcol"><input type="checkbox" id="<?= 'email_'.$key ?>" <? if ($User->message_settings[$key]['email']) echo 'checked'; else $email_all=false; ?>></td>
          <td class="optcol"><input type="checkbox" id="<?= 'sms_'.$key ?>" <? if ($nosms) echo 'disabled'; else if ($User->message_settings[$key]['sms']) echo 'checked'; else $sms_all=false; ?>></td>
        </tr>
      <? } ?>

    </table>
    <input type="hidden" id="email_all" value="<? $email_all ? '1' : '0'; ?>">
    <input type="hidden" id="sms_all" value="<? $sms_all ? '1' : '0'; ?>">
    <div class="notice">You can manage your email, SMS and content selection settings for individual friends, followed feeds, and groups within the &quot;Manage&quot; link on their profile page.</div>
    <div class="commit">
      <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Messages.FormSubmit()">Save General Message Settings</button>
    </div>
    <? if ($nosms) { ?>
      <div class="notice">
        You cannot receive messages or notifications by SMS until you have entered and confirmed your mobile phone number. You can do that <a href="user#settings/mobile">here</a>.
      </div>
    <? } ?>
  </div>

<? /**************************** NOTIFICATIONS  ****************************/ ?>
<? } else if ($PARAMS=='notifications') { ?>
  <div id="notifications">
    <? $nosms = (!isset($User->sms) || $User->sms['number'] == ""); ?>
    <p>Check the boxes below to customize how you receive notifications about new followers, requests, and group invitations.</p>
    <table id="notification_options">
      <tr>
        <th class="restore"><button class="short_button" onclick="User.Settings.Notifications.RestoreDefaults()">Restore Defaults</button></th>
        <th class="optcol">Dashboard</th>
        <th class="optcol">Email</th>
        <th class="optcol">SMS</th>
      </tr>

      <tr class="selectrow">
        <td class="optlabel"></td>

        <td class="optcol">
          <a href="#" id="dashboard_select" style="display:none" onclick="return User.Settings.Notifications.SelectAll('dashboard',true);">Select All</a>
          <a href="#" id="dashboard_unselect" onclick="return User.Settings.Notifications.SelectAll('dashboard',false);">Unselect All</a>
        </td>

        <td class="optcol">
          <a href="#" id="email_select" style="display:none" onclick="return User.Settings.Notifications.SelectAll('email',true);">Select All</a>
          <a href="#" id="email_unselect" onclick="return User.Settings.Notifications.SelectAll('email',false);">Unselect All</a>
        </td>
        <td class="optcol">
          <? if (!$nosms) { ?>
            <a href="#" id="sms_select" style="display:none" onclick="return User.Settings.Notifications.SelectAll('sms',true);">Select All</a>
            <a href="#" id="sms_unselect" onclick="return User.Settings.Notifications.SelectAll('sms',false);">Unselect All</a>
          <? } ?>
        </td>
      </tr>
      <?
        $dashboard_all = true;
        $email_all = true;
        $sms_all = ($nosms ? false : true);
      ?>

      <? foreach (Options::$notification_types as $key=>$text) { ?>
        <? if ($key=='follow_request' && $User->visibility==Enum::$visibility['public']) continue; ?>
        <tr class="check_row">
          <td class="optlabel"><?= $text ?></td>
          <td class="optcol"><input type="checkbox" id="dashboard_<?= $key ?>" <? if ($User->notification_settings[$key]['dashboard']) echo 'checked'; else $miio_all=false; ?>></td>
          <td class="optcol"><input type="checkbox" id="email_<?= $key ?>" <? if ($User->notification_settings[$key]['email']) echo 'checked'; else $email_all=false; ?>></td>
          <td class="optcol"><input type="checkbox" id="sms_<?= $key ?>" <? if ($nosms) echo 'disabled'; else if ($User->notification_settings[$key]['sms']) echo 'checked'; else $sms_all=false; ?>></td>
        </tr>
      <? } ?>
    </table>
    <input type="hidden" id="dashboard_all" value="<? $dashboard_all ? '1' : '0'; ?>">
    <input type="hidden" id="email_all" value="<? $email_all ? '1' : '0'; ?>">
    <input type="hidden" id="sms_all" value="<? $sms_all ? '1' : '0'; ?>">
    <div class="notice">
      You can manage your email, SMS and content selection settings for individual friends, followed feeds, and groups within the &quot;Manage&quot; link on their profile page.
    </div>
    <div class="commit">
      <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Notifications.FormSubmit()">Save Notification Settings</button>
    </div>
    <? if ($nosms) { ?>
      <div class="notice">
        You cannot receive messages or notifications by SMS until you have entered and confirmed your mobile phone number. You can do that <a href="user#settings/mobile">here</a>.
      </div>
    <? } ?>
  </div>

<? /**************************** PROFILE  PHOTO ****************************/ ?>
  <? } else if ($PARAMS=='profilephoto') { ?>
  <div id="photo_upload_form" <? if ($User->photo!="") echo 'style="display:none"'; ?>>
    <p class="header">
      This is the photo displayed on your <a href="members/profile/<?= $User->id ?>">profile</a> page.
    </p>

    <form enctype="multipart/form-data" name="photo_form" id="photo_form" action="ajax/upload_photo" method="POST" target="upload_target" onsubmit="User.Settings.ProfilePhoto.SubmitPreview()">
      <div class="form_section">
        <label>Browse for a photo</label>
        <div class="directions">
          <p>Photos larger than 600x600 pixels will be automatically resized.</p>
          <div class="browse_button">
            <input type="hidden" name="isajax" value="1">
              <input type="hidden" name="js_url" value="User.Settings.ProfilePhoto.URL">
              <input type="hidden" name="js_return" value="User.Settings.ProfilePhoto.UploadDone">
              <input type="hidden" name="js_error" value="User.Settings.ProfilePhoto.UploadError">
              <input type="hidden" name="profile_photo" value="1">
              <label class="up" for="photo_file_source">Upload photo:</label>
              <div class="highlight"><input type="file" name="photo_file_source" id="photo_file_source"></div>
              <p class="mid">Please be patient as photos may take a while to load.</p>
           </div>
        </div>
      </div>
      <div class="commit">
        <input type="submit" class="norm_button" name="preview" id="preview" value="Preview">
      </div>
    </form>
  </div>

  <div id="profile_photo" <? if ($User->photo=="") echo 'style="display:none"'; ?>>
    <p class="header" id="second_header">
      This is the photo displayed on your <a href="members/profile/<?= $User->id ?>">profile</a> page.
    </p>
    <div class="form_section">
      <h3 id="add_photo_head" style="display:none">Approve your profile photo</h3>
      <div class="directions">
        <p id="add_photo_text" style="display:none">If you are satisfied with your profile photo, please click the &quot;Update Profile Photo&quot; button below. If you want to change it, please click the &quot;Cancel&quot; button below your photo.</p>
        <p id="edit_photo_text">If you want to either change or delete it, please click the buttons below your photo.</p>
        <div class="view_preview">
          <?
            $dim = Image::resize('profile_photos/'.$User->photo,300,300);
          ?>
          <img src="profile_photos/<?= $User->photo ?>" alt="No Profile Photo" id="profilephoto" height="<?= $dim['ht'] ?>" width="<?= $dim['wd'] ?>">
          <div class="buttons" id="profile_photo_delete" <? if ($User->photo=="") echo 'style="display:none"'; ?>>
            <button class="short_button" onclick="return User.Settings.ProfilePhoto.Change();">Change</button>
            <button class="short_button" onclick="return User.Settings.ProfilePhoto.Delete();">Delete</button>
          </div>
          <div class="buttons" id="profile_photo_change" style="display:none">
            <button class="short_button" onclick="return User.Settings.ProfilePhoto.Change();">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <div class="notice">
      Composing, creating, linking, or uploading unauthorized copyrighted material or inappropriate material
      of any kind is against our <a href="pages/terms">Terms of Use</a>
      and <a href="pages/copyright">Copyright Policy</a> and may result in your membership being canceled.
    </div>
    <div class="commit">
      <button class="short_button" name="submit" id="submit" onclick="User.Settings.ProfilePhoto.FormSubmit()" style="display:none">Update Profile Photo</button>
    </div>

  </div>
<? /******************************** ALBUMS ********************************/ ?>
<? } else if ($PARAMS=='albums') { ?>

  <? if (count($User->albums) < 5) { ?>
    <div class="form_section" id="openedit_new">
      <a href="#" onclick="return User.Settings.Albums.Edit('new')">Create New Album</a>
      <? if (count($User->albums) > 0) { ?>
        <a class="see_albums" href="members/profile/<?= $User->id ?>#albums">See your albums the way others see them</a>
      <? } ?>
    </div>

    <div class="edit" id="edit_new" style="display:none">
      <form enctype="multipart/form-data" name="album_form_new" id="album_form_new" action="user/upload_album" method="POST" target="upload_target" onsubmit="return User.Settings.Albums.FormSubmit('new')">
        <div class="form_section">
          <h3 class="album_head">
            Add New Album
            <a class="cancel" href="#" onclick="return User.Settings.Albums.Cancel('new')">Cancel</a>
          </h3>
          <div style="margin-bottom:0">
            <label for="title_new">Title</label>
            <input type="text" name="title" id="title_new">
          </div>
          <div>
            <div id="count_new" style="text-align: right;margin:0">140</div>
            <label for="description_new" style="padding-top:0">Description</label>
            <textarea name="description" id="description_new" style="width:450px" onkeyup="return User.Settings.Albums.Count(event,this,'count_new');"></textarea>
          </div>
        </div>
        <div class="form_section">
          <input type="hidden" name="isajax" value="1">
          <input type="hidden" name="js_return" value="User.Settings.Albums.UploadDone">
          <input type="hidden" name="js_error" value="User.Settings.Albums.UploadError">
          <input type="hidden" name="album_id" value="new">
          <ul>
            <? for ($p=1;$p<10;$p++) { ?>
              <li>
                <div class="number"><?= $p ?></div>
                <div class="item">
                  <input type="file" name="<?= $p ?>" id="image_new_<?= $p ?>" <? if ($p>1) echo "disabled"; ?> onchange="User.Settings.Albums.Enable(this,'new',<?= $p+1 ?>)">
                </div>
              </li>
            <? } ?>
          </ul>
        </div>

        <div class="notice">
          Composing, creating, linking, or uploading unauthorized copyrighted material or inappropriate material
          of any kind is against our <a href="pages/terms">Terms of Use</a>
          and <a href="pages/copyright">Copyright Policy</a> and may result in your membership being canceled.
        </div>
        <div class="commit">
          <input type="submit" class="norm_button" name="submit_new" id="submit_new" value="Save Album" style="float: none">
        </div>
      </form>
    </div>
  <? } ?>

  <? if (count($User->albums)>0) { ?>
    <? $albums = 0; ?>
    <? foreach ($User->albums as $album) { ?>
      <? $albums++; ?>
      <? $showthis = ($GET['id']==$album['id']) ? true : false; ?>
      <div class="album <? if ($showthis) echo 'album_highlight'; ?>" id="albumcontainer_<?= $album['id'] ?>">
        <div id="view_head_<?= $album['id'] ?>">
          <div class="showlink">
            <a href="#" id="hide_album_<?= $album['id'] ?>" onclick="return User.Settings.Albums.ToggleAlbum(<?= $album['id'] ?>,false);" <? if (!$showthis) echo "style='display:none'"; ?>>Hide album</a>
            <a href="#" id="show_album_<?= $album['id'] ?>" onclick="return User.Settings.Albums.ToggleAlbum(<?= $album['id'] ?>,true);" <? if ($showthis) echo "style='display:none'"; ?>>Show album</a>
          </div>
          <h3><?= $album['title'] ?></h3>
        </div>
        <div id="edit_head_<?= $album['id'] ?>" style="display:none">
          <a href="#" class="cancel" onclick="return User.Settings.Albums.Cancel(<?= $album['id'] ?>)" style="float:right;font-size:12px;margin-right:2px;margin-top:3px;">Cancel Edit</a>
          <h3>Editing <?= $album['title'] ?></h3>
        </div>

        <div class="edit" id="edit_<?= $album['id'] ?>" style="display:none">
          <div class="form_section">
            <div style="margin-bottom:0">
              <label for="title_<?= $album['id'] ?>">Title</label>
              <input type="text" id="title_<?= $album['id'] ?>" value="<?= $album['title'] ?>">
            </div>
            <div>
              <div id="count_<?= $album['id'] ?>" style="text-align: right;margin:0">140</div>
              <label for="description_<?= $album['id'] ?>" style="padding-top:0">Description</label>
              <textarea name="description" id="description_<?= $album['id'] ?>" style="width:450px" onkeyup="return User.Settings.Albums.Count(event,this,'count_new');"><?= $album['description'] ?></textarea>
            </div>
          </div>

          <div class="commit">
            <button class="norm_button" id="submit_<?= $album['id'] ?>" onclick="User.Settings.Albums.Update(<?= $album['id'] ?>)">Update Album</button>
          </div>
        </div>

        <div id="album_grid_<?= $album['id'] ?>" <? if (!$showthis) echo "style='display:none'"; ?>>
          <p><?= $album['description'] ?></p>
          <!--<h3 style="margin-top:15px;color:red">Richard: The photo titles each indicate how many characters they contain</h3>-->
          <div class="action_bar" id="viewing_album_<?= $album['id'] ?>">
            <a href="#" id="add_photo_link_<?= $album['id'] ?>" onclick="return User.Settings.Albums.Add(<?= $album['id'] ?>)" <? if (count($album['photos'])>8) echo "style='display:none'"; ?>>Add Photos</a>
            <a href="#" onclick="return User.Settings.Albums.Edit(<?= $album['id'] ?>)">Edit Album Info</a>
            <a class="delete" href="#" onclick="return User.Settings.Albums.DeleteAlbum(<?= $album['id'] ?>,'<?= $album['title'] ?>');">Delete Entire Album</a>
          </div>
          <div class="action_bar" id="editing_album_<?= $album['id'] ?>" style="display:none;">
            &nbsp;
            <a class="cancel" href="#" onclick="return User.Settings.Albums.Cancel('<?= $album['id'] ?>',true)">Cancel</a>
          </div>
          <?
            $photos = array();
            foreach ($album['photos'] as $photo) $photos[] = $photo;

          ?>
          <div id="album_photos_<?= $album['id'] ?>">
            <table class="album_photos">
              <? for ($p=0;$p<9;$p++) { ?>
                <?
                  $col = $p%3;
                  $ix = $p+1;
                ?>
                <? if ($col==0) echo "<tr>"; ?>
                  <td class="col<?= $col ?>">
                    <? if ($photos[$p]) { ?>
                      <div class="title" id="t_<?= $album['id'] ?>_<?= $ix ?>"><?= $photos[$p]['title'] ?></div>
                      <div class="photo" id="p_<?= $album['id'] ?>_<?= $ix ?>">
                        <? $size = Image::resize('albums/'.$photos[$p]['saved_filename'],ALBUM_PHOTO_HEIGHT,ALBUM_PHOTO_WIDTH); ?>
                        <img src="albums/<?= $photos[$p]['saved_filename'] ?>" height=<?= $size['ht'] ?> width=<?= $size['wd'] ?> title="<?= $photos[$p]['title'] ?>">
                      </div>
                      <div id="d_<?= $album['id'] ?>_<?= $ix ?>">
                        <a href="#" class="delete" id="delete_<?= $album['id'] ?>_<?= $photos[$p]['id'] ?>" onclick="return User.Settings.Albums.DeletePhoto(<?= $album['id'] ?>,<?= $photos[$p]['id'] ?>,'<?= $photos[$p]['title'] ?>',this)">Delete</a>
                      </div>
                      <input type="hidden" id="i_<?= $album['id'] ?>_<?= $ix ?>" value="1">
                    <? } else { ?>
                      <div class="title" id="t_<?= $album['id'] ?>_<?= $ix ?>">&nbsp;</div>
                      <div class="photo" id="p_<?= $album['id'] ?>_<?= $ix ?>"><?= $ix ?></div>
                      <div id="d_<?= $album['id'] ?>_<?= $ix ?>"></div>
                      <input type="hidden" id="i_<?= $album['id'] ?>_<?= $ix ?>" value="0">
                    <? } ?>
                  </td>
                <? if ($col==2) echo "</tr>"; ?>
              <? } ?>
            </table>
          </div>

          <div class='edit' id="new_photos_<?= $album['id'] ?>" style="display:none">
            <form enctype="multipart/form-data" name="album_form_<?= $album['id'] ?>" id="album_form_<?= $album['id'] ?>" action="user/upload_album" method="POST" target="upload_target" onsubmit="return User.Settings.Albums.FormSubmit('<?= $album['id'] ?>')">
              <div class='form_section'>
                <input type="hidden" name="isajax" value="1">
                <input type="hidden" name="js_return" value="User.Settings.Albums.UploadDone">
                <input type="hidden" name="js_error" value="User.Settings.Albums.UploadError">
                <input type="hidden" name="album_id" value="<?= $album['id'] ?>">
                <ul class="album_inputs">
                  <? for ($p=1;$p<10;$p++) { ?>
                    <li>
                      <div class="number"><?= $p ?></div>
                      <div class="item">
                        <? if ($photos[$p-1]) { ?>
                          <? $size = Image::resize('albums/'.$photos[$p-1]['saved_filename'],40,40); ?>
                          <img id="pic_<?= $album['id'] ?>_<?= $p ?>" src="albums/<?= $photos[$p-1]['saved_filename'] ?>" height=<?= $size['ht'] ?> width=<?= $size['wd'] ?> title="<?= $photos[$p-1]['title'] ?>">
                        <? } ?>
                        <input type="file" name="<?= $p ?>" id="image_<?= $album['id'] ?>_<?= $p ?>" onchange="User.Settings.Albums.Enable(this,'<?= $album['id'] ?>',<?= (int)$p+1 ?>)" <? if ($photos[$p-1]) echo "style='display:none'"; if($p-1!=count($photos)) echo " disabled"; ?> >
                      </div>
                    </li>
                  <? } ?>
                </ul>
              </div>
              <div class="commit">
                <input type="submit" class="norm_button" name="submit_<?= $album['id'] ?>" id="submit_<?= $album['id'] ?>" value="Update Album" style="float: none">
              </div>
            </form>
          </div>

        </div>
      </div>
    <? } ?>
  <? } ?>

<? /******************************* TWITTER  *******************************/ ?>
<? } else if ($PARAMS=='twitter') { ?>
  <div id="twitter">
    <?
      if( $User->twitter['token'] != '' )
      {
        $to = new TwitterHelper(TWITTER_KEY, TWITTER_SECRET, $User->twitter['token'], $User->twitter['secret']);
        $uInfo = $to->OAuthRequest('http://twitter.com/statuses/user_timeline.json?count=1', array(), 'GET');
        $tInfo = json_decode($uInfo, true);
      }
      else
      {
        $to = new TwitterHelper(TWITTER_KEY, TWITTER_SECRET);
        $tok = $to->getRequestToken();
        $token = $tok['oauth_token'];
        Session::Set('oauth_request_token',$token);
        Session::Set('oauth_request_secret',$tok['oauth_token_secret']);
        $request_link = $to->getAuthorizeURL($token);
      }
    ?>
    <div id="header">
      <img src="logos/twitter.jpg" alt="">
    </div>

    <div id="content">
      <? if($User->twitter['token'] != '' ) { ?>
        <div class="form_section">
          <ul id="tview">
            <? if($tInfo[0]['user']['screen_name'] != '') { ?>
              <li class="title">
                <div id="signedin">Signed in as:</div>
                <a href="http://www.twitter.com/<?= $tInfo[0]['user']['screen_name'] ?>" target="_blank">
                  <img src="<?= $tInfo[0]['user']['profile_image_url'] ?>" class="avatar">
                </a>
                <div class="name">
                  <a href="http://www.twitter.com/<?= $tInfo[0]['user']['screen_name'] ?>" target="_blank">@<?= $tInfo[0][user][screen_name] ?></a>
                </div>
              </li>
            <? } else { ?>
              <li id="error">You are signed in, however Twitter did not respond to our request. If this problem persists, try re-authenticating by deleting your credentials and adding Twitter again.</li>
            <? } ?>
            <li class="opt">
              <label class="twitter_what">Publish my Miio messages to Twitter</label>
              <div class="<?= ($User->twitter['push']) ? 'twitter_result_y' : 'twitter_result_n'; ?>">
                <?= ($User->twitter['push']) ? 'Yes' : 'No'; ?>
              </div>
            </li>
            <li class="opt">
              <label class="twitter_what">Publish my Miio replies to Twitter</label>
              <div class="<?= ($User->twitter['reply']) ? 'twitter_result_y' : 'twitter_result_n'; ?>">
                <?= ($User->twitter['reply']) ? 'Yes' : 'No'; ?>
              </div>
            </li>
            <li class="opt">
              <label class="twitter_what">Publish my Miio shares to Twitter</label>
              <div class="<?= ($User->twitter['share']) ? 'twitter_result_y' : 'twitter_result_n'; ?>">
                <?= ($User->twitter['share']) ? 'Yes' : 'No'; ?>
              </div>
            </li>
          </ul>
        </div>
        <ul id="tedit" style="display:none">
          <? if($tInfo[0]['user']['screen_name'] != '') { ?>
            <li class="title">
              <div id="signedin">Signed in as:</div>
              <a href="http://www.twitter.com/<?= $tInfo[0]['user']['screen_name'] ?>" target="_blank">
                <img src="<?= $tInfo[0]['user']['profile_image_url'] ?>" class="avatar">
              </a>
              <div class="name">
                <a href="http://www.twitter.com/<?= $tInfo[0]['user']['screen_name'] ?>" target="_blank">@<?= $tInfo[0][user][screen_name] ?></a>
              </div>
            </li>
          <? } else { ?>
            <li id="error">You are signed in, however Twitter did not respond to our request. If this problem persists, try re-authenticating by deleting your credentials and adding Twitter again.</li>
          <? } ?>
          <li id="editp">
            <p>Please make the following selections and then press the "Update Twitter Settings" button below.</p>
          </li>
          <li class="opt">
            <div class="label">Publish my Miio messages to Twitter</div>
            <div class="radios">
              <input type="radio" name="twitter_push" id="twitter_push" value="1" <? if ($User->twitter['push']) echo 'checked'; ?>> yes
              <input type="radio" name="twitter_push" value="0" <? if (!$User->twitter['push']) echo 'checked'; ?>> no
            </div>
          </li>
          <li class="opt">
            <div class="label">Publish my Miio replies to Twitter</div>
            <div class="radios">
              <input type="radio" name="twitter_reply" id="twitter_reply" value="1" <? if ($User->twitter['reply']) echo 'checked'; ?>> yes
              <input type="radio" name="twitter_reply" value="0" <? if (!$User->twitter['reply']) echo 'checked'; ?>> no
            </div>
          </li>
          <li class="opt">
            <div class="label">Publish my Miio shares to Twitter</div>
            <div class="radios">
              <input type="radio" name="twitter_share" id="twitter_share" value="1" <? if ($User->twitter['share']) echo 'checked'; ?>> yes
              <input type="radio" name="twitter_share" value="0" <? if (!$User->twitter['share']) echo 'checked'; ?>> no
            </div>
          </li>
        </ul>
        <? } else { ?>
        <p class="lo">Publish your Miio messages, replies, and shares to Twitter.</p>
        <div class="signin">
          <a href="<?= $request_link ?>" target="_blank"><img src="images/twitter_signin.png" class="exbutton" alt=""></a>
        </div>
      <? } ?>
    </div>
    <? if ($User->twitter_token != '') { ?>
      <div id="tvfooter">
        <button class="short_button" onclick="User.Settings.Feed.EditTwitter()">Change</button>
        <button class="short_button" onclick="User.Settings.Feed.DestroyTwitter()">Delete</button>
      </div>
      <div id="tefooter" style="display:none">
        <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Feed.FormSubmit('update')">Update Twitter Settings</button>
      </div>
    <? } ?>
    <div id="tsfooter" style="display:none">
      <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Feed.FormSubmit('add')">Save Twitter Settings</button>
    </div>
  </div>

<? /********************************* RSS  *********************************/ ?>
<? } else if ($PARAMS=='rss') { ?>
  <div id="rss">
    <div id="header">
      <img src="logos/rss.gif" alt="">
    </div>
    <? if(isset($User->rss) && $User->rss['url']) { ?>
      <div id="content">
        <p>We are now publishing the following RSS feed to your Miio account automatically</p>
        <div id="feedlist">
          <div class="title"><?= $User->rss['name'] ?></div>
          <div class="url">
            <? if ($User->rss['favicon'] != '') { ?>
              <div>
                <a href="<?= $User->rss['url'] ?>" target='_blank'>
                  <img src="<?= $User->rss['favicon'] ?>">
                </a>
              </div>
            <? } ?>
            <div class="link">
              <a href="<?= $User->rss['url'] ?>" target="_blank"><?= $User->rss['url'] ?></a>
            </div>
          </div>
        </div>
      </div>
      <div id="footer">
        <span onclick="User.Settings.Feed.DestroyFeed('<?= $feed['url'] ?>')">Delete</span>
      </div>
    <? } else { ?>
      <div id="content">
        <p>Publish your RSS feed to Miio automatically</p>
        <p class="small">Miio will check your blog or website periodically and post new items to Miio.</p>
        <p class="small">Enter your RSS feed below and then press the "Save RSS" button</p>
        <div class="form_section">
          <label class="up">RSS feed URL<span class="test" onclick="User.Settings.Feed.rssTest()">Test</span></label>
          <input type="text" class="full" id="rss_url">
        </div>
      </div>
      <div id="abfooter">
        <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Feed.rssSave('rss')">Save RSS</button>
      </div>
    <? } ?>
  </div>
<? /******************************* USERNAME *******************************/ ?>
<? } else if ($PARAMS=='username') { ?>
  <div id="username">
    <div class="form_section">
      <div>
        <label for="settings_username">Username</label>
        <input type="text" name="settings_username" id="settings_username" value="<?= $User->username ?>" maxlength=20>
        <button class="check_button" onclick="return User.Settings.Username.CheckName();">Check Availability</button>
        <div id="name_valid" style="display:none">Username Available</div>
        <div id="name_invalid" style="display:none">Username Unavailable</div>
        <h6>3-20 Characters. No spaces, or periods.</h6>
      </div>
    </div>
    <div class="commit">
      <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Username.FormSubmit()">Change Username</button>
    </div>
  </div>
<? /******************************* PASSWORD *******************************/ ?>
<? } else if ($PARAMS=='password') { ?>
    <div id="password">
    <div class="form_section">
      <label>Current Password</label>
      <input type="password" class="text" name="settings_currentpw" id="settings_currentpw" tabindex=1>
      <button class="check_button" onclick="return User.Settings.Passwd.ResetPW()" id="reset_password">Reset forgotten password</button>
      <div id="password_reset" style="display:none">
        An email has been sent to your registered account email address with a link. Click on that link
        to be directed to a page where you can reset your password.<br><br>
        NOTE: This link is valid for only 24 hours.
      </div>
    </div>
    <div class="form_section">
      <div>
        <label>New Password</label>
        <input type="password" class="text" name="settings_newpw" id="settings_newpw" tabindex=2>
        <h6>(5 characters or longer)</h6>
      </div>
      <div>
        <label for="password_confirm">Confirm New Password</label>
        <input type="password" class="text" name="settings_confirmpw" id="settings_confirmpw" tabindex=3>
      </div>
    </div>
    <div class="commit">
      <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Passwd.FormSubmit()">Change Password</button>
    </div>
  </div>

<? /**************************** EMAIL  ADDRESS ****************************/ ?>
<? } else if ($PARAMS=='emailaddress') { ?>
  <div id="email_address_change">
    <div class="form_section">
      <label for="settings_email">Email Address</label>
      <input type="text" name="settings_email" id="settings_email" value="<?= $User->email ?>">
    </div>
    <div class="commit">
      <button class="norm_button" name="submit" id="submit" onclick="User.Settings.Email.FormSubmit()">Change Email Address</button>
    </div>
  </div>

<? /******************************* REFRESH MODE *******************************/ ?>
<? } else if ($PARAMS=='refreshrate') { ?>
  <?
    if ($User->refresh_rate>0 && $User->refresh_rate/60 == floor($User->refresh_rate/60))
    {
      $units='min';
      $num=$User->refresh_rate/60;
    }
    else
    {
      $units='sec';
      $num=$User->refresh_rate;
    }
  ?>
  <div id="refresh_mode">
    <p>The refresh mode lets you choose the speed that new messages and content arrive in Miio.</p>
    <p>You can choose to refresh manually.</p>
    <p>Or you can choose to have messages and content arrive in real time similar to IM.</p>
    <div class="form_section">
      <div>
        <label>Select a refresh mode</label>
        <div class="refresh_right">
          <div class="option">
            <input type="radio" name="settings_refresh_mode" id="settings_refresh_mode_manual" <? if ($User->refresh_rate==0) echo "checked"; ?> onchange="User.Settings.Refresh.UpdateForm(this);">
            <label for="settings_refresh_mode_manual">Manual refresh</label>
          </div>
          <div class="option">
            <input type="radio" name="settings_refresh_mode" id="settings_refresh_mode_auto" <? if ($User->refresh_rate>0) echo "checked"; ?> onchange="User.Settings.Refresh.UpdateForm(this);">
            <label for="settings_refresh_mode_auto">Auto refresh</label>
            <label for="settings_refresh" class="freq">How often should we refresh the page?</label>
            <input type="text" id="settings_refresh" value="<?= ($num==0)?'5':$num ?>" onchange="User.Settings.Refresh.UpdateForm(this);" <? if ($num==0) echo "disabled"; ?>>
            <select id="settings_refresh_unit" <? if ($num==0) echo "disabled"; ?>>
              <option value='sec' <? if ($units!='min') echo "selected"; ?>>seconds</option>
              <option value='min' <? if ($units=='min') echo "selected"; ?>>minutes</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="commit">
      <button class="norm_button" id="submit" onclick="User.Settings.Refresh.FormSubmit()">Update Refresh Mode</button>
    </div>
  </div>

<? /******************************** CANCEL ********************************/ ?>
<? } else if ($PARAMS=='cancel') { ?>
  <div id="cancel_account">
    <p><strong>Please note:</strong> Canceling your account will permanently and completely remove
    your account from our system, along with all your account settings and messages!</p>
    <p class="center">To permanently cancel your account, type &quot;cancel&quot; in the box below and
    click &quot;Cancel My Account&quot;</p>
    <div class="form_section">
      <div>
        <input type="text" id="cancel_account_text">
      </div>
    </div>
    <div class="commit">
      <button class="norm_button" id="submit" onclick="User.Settings.CancelAccount.FormSubmit()">Cancel My Account</button>
    </div>
  </div>

<? /************************************************************************/ ?>
<? } ?>
</div>