<? global $FORM_ERR, $FORM_VALUES, $SUB, $LOGGEDIN, $User; ?>
<?
$opts = array
(
  "general"=>"General Site Comment",
  "help"=>"Help Question?",
  "complaints"=>"Complaints",
  "suggestions"=>"Suggestions",
  "bugs"=>"Bug Report",
  "jobs"=>"Employment",
  "advertising"=>"Advertising",
  "bizdev"=>"Business Development",
  "copyright"=>"Copyright Infringement",
  "privacy"=>"Privacy"
);
?>
<link href="css/pages.css" rel="stylesheet" type="text/css">

<div id="page_content">
  <div class="contact_left">
  	<img src="images/logoME.jpg">
  	<? if (!$LOGGEDIN) { ?>
  	  <p>
  		  <a href="signup">Sign up</a> Meet new people. Find and share content and conversations happening now.
  	  </p>
  	<? } ?>
  </div>
  
  <div class="contact_form">
    <h1>Contact Us</h1>
    <? if ($SUB) { ?>
      <p>Thank you for your message!</p>
    <? } else { ?>
      <p>Please contact Miio using the form below:</p>
      
      <form name="contact" action="pages/contact" method="POST">
        <ul>
          <? if ($FORM_ERR=='email_invalid') { ?>
            <li class="error">Please enter a valid email address</li>
          <? } else if ($FORM_ERR=='no_email') { ?>
            <li class="error">Please enter your email address</li>
          <? } ?>
          <li>
            <label>Email Address:</label>
            <input type="text" class="field" name="email" id="email" value="<? if ($FORM_ERR) echo $FORM_VALUES['email']; else if ($LOGGEDIN) echo $User->email; ?>" tabindex=1>
          </li>
          <li>
            <label>Your Name:</label>
            <input type="text" class="field" name="name" id="name" value="<? if ($FORM_ERR) echo $FORM_VALUES['name']; else if ($LOGGEDIN) echo trim($User->first_name.' '.$User->last_name); ?>" tabindex=2>
          </li>
          <li>
            <label>Your Miio Username:</label>
            <input type="text" class="field" name="username" id="username" value="<? if ($FORM_ERR) echo $FORM_VALUES['username']; else if ($LOGGEDIN) echo $User->username; ?>" tabindex=3>
          </li>
          <li>
            <label class="label">Message Type:</label>
            <select name="category" id="category" tabindex=4>
              <option value="">--Please Select--</option>
              <? foreach ($opts as $opt=>$txt) { ?>
                <option value="<?= $opt ?>" <? if ($opt==$FORM_VALUES['category']) echo 'selected'; ?>>
                  <?= $txt ?>
                </option>
              <? } ?>
            </select>
          </li>
          <li>
            <label>Subject:</label>
            <input type="text" class="field" name="subject" id="subject" value="<?= $FORM_VALUES['subject']; ?>"tabindex=5>
          </li>
          <li>
            <label>Message:</label>
            <textarea class="text" name="message" id="message" tabindex=6><?= $FORM_VALUES['message']; ?></textarea>
          </li>
          <li class="submit">
            <button name="submit" id="submit" class="norm_button" tabindex=7>Send Message</button>
          </li>
        </ul>
      </form>
    <? } ?>
  </div>
</div>