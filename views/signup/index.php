
<link href="css/signup.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/signup.js"></script>

<div id="signup">
  <?
     $to = new TwitterHelper(TWITTER_KEY, TWITTER_SECRET);
     $tok = $to->getRequestToken();
     $token = $tok['oauth_token'];
     Session::Set('oauth_request_token',$token);
     Session::Set('oauth_request_secret',$tok['oauth_token_secret']);
     $request_link = $to->getAuthorizeURL($token);

  ?>
  <div class="tagline">
    Miio is the new and easy way to Microblog
  </div>

  <div id="got_account" style="display:none">
    <input type="hidden" value="" id="twitter_token" name="twitter_token">
    <input type="hidden" value="" id="twitter_secret" name="twitter_secret">
    <input type="hidden" value="" id="twitter_id" name="twitter_id">
    <input type="hidden" value="" id="twitter_sn" name="twitter_sn">
    <input type="hidden" value="" id="bio_name" name="bio_name">
    <input type="hidden" value="" id="bio_desc" name="bio_desc">
    <input type="hidden" value="" id="bio_image" name="bio_image">
    <input type="hidden" value="" id="bio_url" name="bio_url">
    <div id="twitterInfo"></div>
    <p id='twit_yes' style='display:none'>This Twitter username is available as a Miio username.</p>
    <p id='twit_not' style='display:none'>This Twitter username is not available as a Miio username.</p>
    <div id="twitterOptions"></div>
  </div>

  <div id="add_account">
    <span>Do you Have a Twitter Account?</span>
    <a href="<?= $request_link ?>" target="twitReceiver" onclick="this.blur()"><img src="images/twitter_signin.png" class="exbutton" alt=""></a>
    <p><strong>Note:</strong> We won't send anything back to your account without your explicit permission.</p>
  </div>

  <div class="form">
    <div class="section">

      <div>
        <label for="username">Username</label>
        <input type="text" class="text" name="username" id="username" tabindex=1>
        <button href="#" class="check_name short_button" onclick="return Signup.CheckName();">Check Availability</button>
        <div id="name_valid" style="display:none">Username Available</div>
        <div id="name_invalid" style="display:none">Username Unavailable</div>
        <h6>3-20 Characters. No spaces or periods.</h6>
      </div>

      <div>
        <label for="email">Email</label>
        <input type="text" class="text" name="email" id="email" tabindex=2>
      </div>

      <div>
        <label for="confirm_email">Confirm Email</label>
        <input type="text" class="text" name="confirm_email" id="confirm_email" tabindex=3>
      </div>

    </div>

    <div class="section">
      <div>
        <label>Password</label>
        <input type="password" class="text" name="password" id="password" tabindex=4>
        <h6>5 characters or longer.</h6>
      </div>
      <div>
        <label for="password_confirm">Confirm Password</label>
        <input type="password" class="text" name="password_confirm" id="password_confirm" tabindex=5>
      </div>
    </div>

    <div class="section">
      <div class="captcha">
        <img id="captcha_image" src="<?= LOC ?>securimage/securimage_show.php" alt="CAPTCHA image">
        <label for="captcha">Type the text above</label>
        <input type="text" class="text" name="captcha" id="captcha" tabindex=6>
      </div>
    </div>
    <div class="section_button">
      <p class="tos">By clicking &quot;Sign me up&quot; you agree to the <a href="pages/terms" class="tos" target="_blank">Terms of Use</a>.</p>
      <button class="signup_button" onclick="return Signup.SubmitForm();" tabindex=7>Sign Me Up</button>
      or View <a href="pages/features" class="">Features</a> or <a href="">Video Demo</a>
    </div>

  </div>
</div>
