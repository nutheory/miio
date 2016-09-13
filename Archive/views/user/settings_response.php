<? global $PARAMS, $User; ?>
<link href="css/forms.css" rel="stylesheet" type="text/css">
<div id="settings_content">
<? $returnText = "Go to your Dashboard Timeline"; ?>
<? if ($PARAMS=='profileinfo') { ?>
  <div class="form_response">
    <h3>Your Profile information has been updated</h3>
    <p>You can see your profile as others see it by clicking <a href="members/profile/<?= $User->id ?>#description">here</a></p>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='mobile') { ?>
  <div class="form_response">
    <p>We just sent a text message to your mobile phone. Reply &quot'OK&quot;
    to confirm your phone and begin sending and reading Miio messages.</p>
    <div class="commit">
      <button class="short_button" onclick="return User.Settings.Mobile.ResendConfirmation();">Resend Confirmation Message</button>
      <button class="short_button" onclick="return User.Settings.Mobile.RemoveMobile(false);">Remove Phone</a>
    </div>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a> | <a href="pages/contact/bug_report?page=settings_mobile">Report problems with this form</a></div>
  </div>
  <div id="mobile_resent" style="display:none" class="form_response">
    <p>A new confirmation message has been sent to your mobile number. Reply 'OK' to confirm your phone.</p>
  </div>
<? } else if ($PARAMS=='mobileremoved') { ?>
  <div class="form_response">
    <h3>Your Mobile Phone information has been removed</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a> | <a href="#" onclick="return User.Settings.Mobile.ViewForm();">Enter new mobile phone</a></div>
  </div>
<? } else if ($PARAMS=='message') { ?>
  <div class="form_response">
    <h3>Your General Message Settings have been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='notifications') { ?>
  <div class="form_response">
    <h3>Your Notification Settings have been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='profilephoto') { ?>
  <div class="form_response_short">
    <h3>Your Profile photo has been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='albums') { ?>
  <div class="form_response">
    <h3>Your Album has been updated</h3>
    <p>You can see your albums as others see them by clicking <a href="members/profile/<?= $User->id ?>#albums/">here</a></p>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a> | <a href="#" onclick="return User.Settings.Albums.ReturnToAlbums()">Return to Albums</a></div>
  </div>
<? } else if ($PARAMS=='avatar') { ?>
  <div class="form_response">
    <h3>Your Avatar image has been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='username') { ?>
  <div class="form_response">
    <h3>Your Username has been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='password') { ?>
  <div class="form_response">
    <h3>Your Password has been changed</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='emailaddress') { ?>
  <div class="form_response">
    <h3>Your account Email address has been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='refreshrate') { ?>
  <div class="form_response">
    <h3>Your page refresh rate has been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='remove_feed') { ?>
  <div class="form_response">
    <h3>Your RSS information has been deleted</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='update_feed') { ?>
  <div class="form_response">
    <h3>Your RSS information has been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='added_feed') { ?>
  <div class="form_response">
    <h3>Congratulations. We will now publish your RSS feed to Miio automatically</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='remove_twitter') { ?>
  <div class="form_response">
    <h3>Your Twitter information has been deleted</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='update_twitter') { ?>
  <div class="form_response">
    <h3>Your Twitter information has been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='added_twitter') { ?>
  <div class="form_response">
    <h3>Your Twitter information has been saved</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='remove_facebook') { ?>
  <div class="form_response">
    <h3>Your Facebook information has been deleted</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='update_facebook') { ?>
  <div class="form_response">
    <h3>Your Facebook information has been updated</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else if ($PARAMS=='added_facebook') { ?>
  <div class="form_response">
    <h3>Your Facebook information has been added</h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } else { ?>
  <div class="form_response">
    <h3>Settings updated: <?= $PARAMS ?></h3>
    <div class="link_center"><a class="dash" href="user"><?= $returnText ?></a></div>
  </div>
<? } ?>
</div>
