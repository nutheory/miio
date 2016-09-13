<? global $User, $PARAMS, $RESET_STATUS; ?>
<? if ($RESET_STATUS=='err') global $err; ?>
<link href="css/forms.css" rel="stylesheet" type="text/css">
<link href="css/user_password_reset.css" rel="stylesheet" type="text/css">

<table><tr><td id="left_col">&nbsp;</td><td id="center_col">
  <div id="password_reset">
  
    <div id="content_header">
      Reset your Miio Password
    </div>
    
    <? if ($RESET_STATUS=='bln') { ?>
      <form name="reset_password" id="reset_password" action="<?= $LOC ?>user/password_reset" method="POST">
        <div class="form_section">
          <div>
            <? if ($err['validate']) { ?>
              <div class="error">Please enter a valid password at least 5 characters long</div>
            <? } ?>
            <label>Enter Reset Code</label>
            <input type="text" class="text" name="reset_code" id="reset_code" tabindex=1>
          </div>
        </div>
        
        <div class="commit">
          <button class="norm_button" name="submit_code" id="submit_code">Check Reset Code</button>
        </div>
      </form>
      
    <? } else if ($RESET_STATUS=='acc' || $RESET_STATUS=='err') { ?>
      
      <? if ($err['unknown']) { ?>
        <div class="error">An unknown error occurred while processing your request</div>
      <? } ?>
    
      <form name="reset_password" id="reset_password" action="<?= $LOC ?>user/password_reset" method="POST">
        <div class="form_section">
          <div>
            <? if ($err['validate']) { ?>
              <div class="error">Please enter a valid password at least 5 characters long</div>
            <? } ?>
            <label>Enter New Password</label>
            <input type="password" class="text" name="settings_newpw" id="settings_newpw" tabindex=1>
            <h6>(5 characters or longer)</h6>
          </div>
          <div>
            <? if ($err['match']) { ?>
              <div class="error">Please confirm your new password</div>
            <? } ?>
            <label for="password_confirm">Confirm New Password</label>
            <input type="password" class="text" name="settings_confirmpw" id="settings_confirmpw" tabindex=2>
          </div>
          
        </div>
        
        <div class="commit">
          <button class="norm_button" name="submit" id="submit">Change Password</button>
        </div>
      </form>
    
    <? } else if ($RESET_STATUS=='exp') { ?>
    
      The reset code you are using has expired. You can request a new one <a href="signup/forgot_login">here</a>.
      
    <? } else if ($RESET_STATUS=='sub') { ?>
    
      Your password has been reset. <a href="user/login">Login</a> to your account.
    
    <? } else { ?>
      The reset code '<?= $PARAMS ?>' you are attempting to use does not exist. Please re-check the code and try again.
    <? } ?>

    
  </div>
</td><td id="right_col"><!--<img src="filler/google_ads.gif">--></td></tr></table>
