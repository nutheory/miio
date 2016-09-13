<? global $PARAMS, $LOGIN_ERR, $LOGIN_RESET; ?>
<link href="css/forms.css" rel="stylesheet" type="text/css">
<div id="login_div">
  <div class="login_form">
    <h1>Forgot Login</h1>
    <? if ($LOGIN_RESET) { ?>
      <div>
        An email has been sent to your registered account email address with
        your Miio username and a link. Click on that link to be directed to a
        page where you can reset your password.<br><br>
        NOTE: This link is valid for only 24 hours.
      </div>
    <? } else { ?>
      <? if ($LOGIN_ERR) { ?>
        <div class="loginerr">
          Please enter your Miio username or the email address for your Miio account
        </div>
      <? } ?>
      <div>Enter either your username or account email address below and we will send you a link you can use to reset your password</div>
      <form name="login" id="login" action="user/forgot_login" method="POST">
        <ul>
          <li>
            <label for="login_username">Username:</label>
            <input class="text" type="text" name="login_username" id="login_username" class="text" maxlength=20 tabindex=1>
          </li>
          <li>
            <label for="login_email">Email:</label>
            <input class="text" type="text" name="login_email" id="login_email" class="text" tabindex=2>
          </li>
          <li class="submit">
            <button class="short_button" name="login_submit" id="login_submit" tabindex=4>Send Reset Code</button>
          </li>
        </ul>
      </form>
    <? } ?>
  </div>
</div>
