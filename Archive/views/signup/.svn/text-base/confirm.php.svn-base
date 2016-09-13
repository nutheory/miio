<? global $LOGGEDIN, $LOGINHOST, $User, $SESSION, $PARAMS; ?>

<link href="css/signup.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/signup.js"></script>

<div id="signup">
  <div class="form">
	  <? if ($LOGGEDIN) { ?>
      <div class="section">
        <? if ($PARAMS=='signup') { ?>
          <h1>Welcome to Miio!</h1>
          <p>
            An email has been sent to <i><?= $User->email ?></i> with a
            confirmation code. Enter this code below to confirm your Miio account.
        	</p>
    		  <p>
            If you do not see the confirmation email in your inbox in a few minutes,
            try checking your spam or junk folder. If you still haven't received it,
            try <a href="#" onclick="return Confirm.ResendCode();">resending the code</a>.
    		  </p>
        <? } else { ?>
          <? if ($PARAMS=="ml") { ?>
            <div class="error_box">
              <p>You have to confirm your account!</p>
            </div>
          <? } ?>
          <h1>Confirm your Miio account</h1>
    		  <p>
            Please enter your confirmation code below to confirm your Miio account.
            If you have not received it or have lost it, you can
            <a href="#" onclick="return Confirm.ResendCode();">have it resent</a>.
    		  </p>
    		<? } ?>
      </div>
      
      <div class="section">
        <label for="confirmation_code">Confirmation Code:</label>
        <input type="text" class="text" name="confirmation_code" id="confirmation_code" tabindex=1>
        <div class="resend">
          <a style="font-size:12px;" href="#" onclick="return Confirm.ResendCode();">Resend Confirmation Code</a>
        </div>
      </div>
      
      <div class="section_button">
  			<button name="submit" class="norm_button" tabindex=2 onclick="return Confirm.SubmitForm(true);">Confirm my account</button>
  		</div>
    <? } else { ?>
      <!-- if not logged in -->
	    <h1>Login and Confirm your Miio account</h1>
	    <table>
        <tr>
          <td colspan=2 style="padding-bottom:20px">
            Please enter your username, password, and confirmation code below to
            login and confirm your Miio account.<br><br>
            If you have not received your confirmation code or if you have lost it, you can
            <a href="forms/resend_confirmation">request it to be resent</a>.
          </td>
        </tr>
        <tr>
          <th>Username:</th>
          <td><input type="text" name="username" id="username" tabindex=1></td>
        </tr>
        <tr>
          <th>Password:</th>
          <td><input type="password" name="password" id="password" tabindex=2></td>
        </tr>
        <tr>
          <th>Confirmation Code:</th>
          <td><input type="text" name="confirmation_code" id="confirmation_code" tabindex=3></td>
        </tr>
        <tr>
          <td colspan=2 class="submit" style="text-align:center"><input type="submit" name="submit" value="Confirm my Account!" tabindex=4 onclick="return Confirm.SubmitForm(false);"></td>
        </tr>
      </table>
    <? } ?>
  </div>
</div>
