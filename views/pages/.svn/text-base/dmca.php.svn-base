<? global $FORM_ERR, $SUB, $FORM_VALUES; ?>
<link href="css/pages.css" rel="stylesheet" type="text/css">
<div id="page_content">

  <div id="dmca">
    <div class="section">
    	<h1>DMCA Notice of Copyright Infringement</h1>
    </div>
    <? if ($SUB) { ?>
      <p class="received">Your notice has been received and logged, and we will look into it.</p>
    <? } else { ?>
      <form action="pages/dmca" method="POST">
      	<div class="section">
      		<div>
      		  <? if ($FORM_ERR['name']) { ?>
      		    <div class="error">Your Name is required</div>
      		  <? } ?>
      			<label for="name">Name</label>
      			<input type="text" id="name" name="name" value="<?= $FORM_VALUES['name'] ?>">
      		</div>
      		<div>
      		  <? if ($FORM_ERR['address']) { ?>
      		    <div class="error">Your Address is required</div>
      		  <? } ?>
      			<label for="address">Mailing Address</label>
      			<textarea class="small" id="address" name="address" cols="30" rows="4"><?= $FORM_VALUES['address'] ?></textarea>
      		</div>
      		<div>
      		  <? if ($FORM_ERR['phone']) { ?>
      		    <div class="error">Your Telephone number is required</div>
      		  <? } ?>
      			<label for="phone">Telephone</label>
      			<input type="text" id="phone" name="phone" value="<?= $FORM_VALUES['phone'] ?>">
      		</div>
      		<div>
      		  <? if ($FORM_ERR['no_email']) { ?>
      		    <div class="error">Your Email Address is required</div>
      		  <? } ?>
      		  <? if ($FORM_ERR['email_invalid']) { ?>
      		    <div class="error">A valid Email Address is required</div>
      		  <? } ?>
      			<label for="name">Email</label>
      			<input type="text" id="email" name="email" value="<?= $FORM_VALUES['email'] ?>">
      			<div class="note">
      				(Note that we routinely provide this email address to the user that posted the content you are reporting)
      			</div>
      		</div>
      	</div>
      	<div class="section">
      		<label for="identify_copyrighted" class="top">Identify the copyrighted work that you claim has been infringed.</label>
    		  <? if ($FORM_ERR['id_copy']) { ?>
    		    <div class="fullerror">You must identify the copyrighted work</div>
    		  <? } ?>
      		<textarea id="identify_copyrighted" name="identify_copyrighted" cols="60" rows="6"><?= $FORM_VALUES['identify_copyrighted'] ?></textarea>
      	</div>
      	<div class="section">
      		<label for="identify_infringing" class="top">Identify the content on our site that you claim infringes your copyright.</label>
    		  <? if ($FORM_ERR['id_infringe']) { ?>
    		    <div class="fullerror">You must identify the work you claim infringes your copyright</div>
    		  <? } ?>
      		<textarea id="identify_infringing" name="identify_infringing" cols="60" rows="6"><?= $FORM_VALUES['identify_infringing'] ?></textarea>
      	</div>
      	<div class="section">
      		<label for="locate_infringing" class="top">Where does the infringing content appear on our site? In almost all instances the best way to help us locate the content you are reporting
      			is to provide us with the URL.</label>
    		  <? if ($FORM_ERR['locate']) { ?>
    		    <div class="fullerror">You must locate the work you claim infringes your copyright</div>
    		  <? } ?>
      		<textarea id="locate_infringing" name="locate_infringing" cols="60" rows="6"><?= $FORM_VALUES['locate_infringing'] ?></textarea>
      	</div>
      	<div class="section">
      		<label for="how_does" class="top">How does the content infringe your rights?**</label>
      		<textarea id="how_does" name="how_does" cols="60" rows="6"><?= $FORM_VALUES['how_does'] ?></textarea>
      	</div>
      	<div class="last">
      		<p>
      			By submitting this notice, you declare that you have a good faith belief that use of the copyrighted content described above, in the manner you have complained of, is not authorized 
      			by the copyright owner, its agent, or the law. You also declare that the information in this notice is accurate. And finally, you declare under penalty of perjury, that you are the owner 
      			or authorized to act on behalf of the owner of an exclusive copyright that is allegedly infringed.
      		</p>
    		  <? if ($FORM_ERR['agree']) { ?>
    		    <div class="error">You must Agree to the terms and provide your Electronic Signature</div>
    		  <? } ?>
      		<ul>
      			<li>
      				<input type="radio" name="agree" id="agree" value="yes">
      				<label>I Agree</label>
      			</li>
      			<li>
      				<input type="radio" name="agree" id="disagree" value="no" checked>
      				<label>I Disagree</label>
      			</li>
      			<li>
      				<label for="signature" class="top">Electronic Signature</label>
      				<input type="text" id="signature" name="signature">
      			</li>
      			<li>
      				<button class="norm_button" name="submit" id="submit">Submit</button>
      			</li>
      		</ul>
      		<p>
      			**This question is optional and is not required by the DMCA. However, providing this information may preempt any potential need for follow up questions should your notice be unclear.
      		</p>	
      	</div>
      </form>
    <? } ?>
  </div>
</div>