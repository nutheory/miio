<? global $PARAMS, $LOGIN_ERR; ?>
<link href="css/forms.css" rel="stylesheet" type="text/css">
<div id="login_div">
  <? if ($PARAMS=="ml") { ?>
    <div class="error_box">
      <p>You have to login to do that!</p>
    </div>
  <? } ?>
  <div class="login_form">
    <h1>Login</h1>
    <? if ($LOGIN_ERR) { ?>
      <div class="loginerr">
        The username and password you entered do not match any of our records
      </div>
    <? } ?>
    <form name="login" id="login" action="user/login" method="POST">
      <ul>
        <li>
          <label for="login_username">Username:</label>
          <input class="text" type="text" name="login_username" id="login_username" class="text" maxlength=20 tabindex=1>
        </li>
        <li>
          <label for="login_password">Password:</label>
          <input class="text" type="password" name="login_password" id="login_password" class="text" tabindex=2 autocomplete=off>
        </li>
        <li class="remember">
          <label for="login_remember" class="remember_label">Remember me</label>
          <input type="checkbox" name="login_remember" id="login_remember" tabindex=3>

        </li>
        <li class="submit">
          <button type="submit" class="norm_button" name="login_submit" id="login_submit" tabindex=4>Login </button>
        </li>
        <li class="forgot">
          <a href="user/forgot_login">Forgot username or password?</a>
        </li>
      </ul>
    </form>
  </div>
  <div class="loginsignup">
    Don't have an account? <a href="signup/index">Sign up now</a>.<br>It only takes a few seconds.
  </div>
</div>

