<? global $User, $PAGE, $PARAMS; ?>

<div id="invite_content">
  <? if ($PARAMS=='emailcontacts') { ?>
    <iframe id="email_inviter" src="inviter/index.php?mode=email&type=app"></iframe>
  <? } else if ($PARAMS=='socialnetwork') { ?>
    <iframe id="email_inviter" src="inviter/index.php?mode=network&type=app"></iframe>
  <? } else if ($PARAMS=='share') { ?>
    <? 
	$fLink = "http://www.miio.com";
	$fText = "Has anyone seen this new microblogging site - Miio";
  ?>
	<ul class="socialshares">
	    <li>
	      <script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>
	      <a href="http://www.facebook.com/share.php?u=<?= $fLink ?>" onclick="return fbs_click()" target="_blank" class="facebook">Facebook</a>
	    </li>
	    <li>
	      <!-- <? if($User->twitter_token) { ?>
	        <a href="#" onclick="Message.SendTweet()" class="twitter">Twitter</a>
	      <? } else { ?> -->
	        <a href="http://twitter.com/home?status=<?= $fText." ".$fLink ?>" target="_blank" class="twitter">Twitter</a>
	       <!-- <? } ?> -->
	    </li>
	    <!--
	    <li>
	      <script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=c50d61ae-134e-4e57-8385-6849faf2c3c3&amp;type=website&amp;post_services=friendfeed%2Cmyspace%2Cdigg%2Csms%2Cwindows_live%2Cdelicious%2Cstumbleupon%2Creddit%2Cgoogle_bmarks%2Cybuzz%2Cblinklist%2Ctechnorati%2Cbebo%2Cblogger%2Cyahoo_bmarks%2Csphinn%2Cmixx%2Cpropeller%2Cfark%2Cslashdot%2Cwordpress%2Clinkedin%2Cmeneame%2Ctypepad%2Cnewsvine%2Cxanga%2Clivejournal%2Cfunp%2Cbus_exchange%2Cn4g%2Cyigg%2Ccurrent%2Ccare2%2Ctwackle%2Cdealsplus%2Cblogmarks%2Cfresqui%2Cdiigo%2Cmister_wong%2Csimpy%2Ckirtsy%2Cfaves%2Ctwine%2Caim%2Coknotizie"></script>
	    </li
	    -->
	    <li>
	      <a href="http://www.stumbleupon.com/submit?url=<?= $fLink ?>" target="_blank" class="stumbleupon">StumbleUpon</a>
	    </li>
	    <li>
	      <a href="http://reddit.com/submit?url=<?= $fLink ?>&amp;title=<?= $fText?>" target="_blank" class="reddit">Reddit</a>
	    </li>
	    <li>
	      <a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=<?= $fLink ?>&amp;title=<?= $fText?>" target="_blank" class="googlebookmarks">Google Bookmarks</a>
	    </li>
	    <li>
	      <a href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?= $fLink ?>" target="_blank" class="myspace">Myspace</a>
	    </li>
	    <li>
		
	      <a href="http://buzz.yahoo.com/buzz?targetUrl=<?= $fLink ?>" target="_blank" class="buzz">Buzz</a>
	    </li>
	    <li>
	      <a href="http://www.tumblr.com/" target="_blank" class="tumblr">Tumblr</a>
	    </li>
	    <li>
	      <a href="http://digg.com/submit?phase=2&amp;url=<?= $fLink ?>&amp;title=<?= $fText ?>" class="digg" target="_blank">Digg</a>
	    </li>
		  <li>
			<a href="http://www.friendfeed.com/share?title=<?= $fText ?>&amp;link=<?= $fLink ?>" class="friendfeed" target="_blank">Friendfeed</a>
		  </li>
		  <li>
				<a href="http://www.bebo.com/c/share?Url=<?= $fLink ?>&amp;Title=<?= $fText ?>" class="bebo" target="_blank">Bebo</a>
		  </li>
		  <li>
				<a href="http://posterous.com/share?linkto=<?= $fLink ?>&amp;title=Miio&amp;selection=<?= $fText ?>" class="posterous" target="_blank">Posterous</a>
		  </li>
		  <li>
				<a href="http://delicious.com/post?url=<?= $fLink ?>&amp;title=<?= $fText ?>" class="delicious" target="_blank">Delicious</a>
		  </li>
		  <li class="reset">
				<a href="http://technorati.com/faves?add=<?= $fLink ?>" class="technorati" target="_blank">Technorati</a>
		  </li>
	  </ul>

  <? } else if ($PARAMS=='email') { ?>
  
    <div id="email_form">
		  <div class="form_section_invite">	
		    Enter your friends email address then click &quot;Send Invitations&quot;.
		    <textarea class="full" name="email_list" id="email_list"></textarea>
        <span class="note">Separate multiple email addresses with a comma. Miio will only send them this one message and we won’t store their email address.</span>
		  </div>
		  <div class="button_bar">
			  <button class="short_button" name="email_submit" id="email_submit" onclick="User.Invite.SendEmailInvite()">Send Invitations</button>
		  </div>
	  </div>
	  
    <div id="email_sent" style="display:none">
		  <div class="form_response">
	      <h3>Your invitations have been sent</h3>
		    <div class="link_center"><a class="dash" href="user">Go to Timeline</a></div>
		  </div>
    </div>
    
  <? } /*else if ($PARAMS=='text') { ?>
  
  	<div id="sms_form">
  		<div class="form_section_invite">
	      <p class="ins">Invite a friend via text messaging</p>
  			<div>
  				<label for="country">Country</label>
  				<input type="text" class="long" name="country" id="country" onkeydown="return User.Invite.Country.ProcessTab(event,this)" onkeyup="User.Invite.Country.Suggest(event,this)" onfocus="User.Invite.Country.Suggest(event,this)" onblur="User.Invite.Country.Clear(this);" onchange="User.Invite.ChangeSMSCountry(this);" autocomplete="off" value="<?= $User->sms_country ?>">
          <div id="Country" style="display:none" class="autofill"></div>
  			</div>
  			<div>
  				<? $sms_code = Places::get_sms_code($User->sms_country); ?>
  				<label for="">Mobile number</label>
  				<div id="sms_country_code_text"><? if ($sms_code) echo $sms_code; else echo "0"; ?></div>
  	      <input type="text" name="sms_number" id="sms_number">
  	      <p>Numbers only. (i.e. 1235551234).</p>
		  <p>Miio will only send them this one text message and we won’t save their mobile phone number</p>
  			</div>
  		</div>
  		<div class="button_bar">
  			<button class="short_button" name="sms_submit" id="sms_submit" onclick="User.Invite.SendSMSInvite()">Send Invitation</button>
  		</div>
  	</div>
  	
  	<div id="sms_sent" style="display:none">
  		<div class="form_response">
  	    <h3>Your invitation has been sent</h3>
  		  <div class="link_center"><a class="dash" href="user">Go to Timeline</a></div>
  		</div>
    </div>
  <? } */?>
</div>