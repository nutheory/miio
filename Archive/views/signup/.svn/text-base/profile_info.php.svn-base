<? global $User; ?>
<div id="profile_info">
	<h2>Enter profile information<a href="#" onclick="return Signup.NextStep()">skip</a></h2>
	<ul>
		<li>
			<p class="header">
				You can change this form at any time.
			</p>
		</li>
		<li class="form_section">
			<div>
				<label for="first_name">First Name</label>
				<input type="text" id="first_name" value="<?= $User->first_name ?>" maxlength=20>
			</div>
			<div>
				<label for="last_name">Last Name</label>
				<input type="text" id="last_name" value="<?= $User->last_name ?>" maxlength=20>
			</div>
			<div class="option_subdued">
				<input type="checkbox" id="show_name" checked>
		        <label class="ch">Show name on profile.</label>
			</div>
		</li>
		<li class="form_section">
			<div>
			  <label class="ab" for="description">About</label>
				<div class="ab_count" id="description_count"><?= 140-strlen($User->description) ?></div>
				<div class="ab_text">Tell us about yourself, in 140 characters or less.</div>
				<textarea  class="full" id="description" onkeyup="return Signup.Count(event,this,'description_count');"><?= $User->description ?></textarea>
			</div>
		</li>
		<li class="form_section">
			<div>
				<label class="sel">Birthday</label>
        <select id="day">
          <option value="">Day</option>
          <? for ($d=1;$d<32;$d++) { ?>
            <option value="<?= $d ?>"><?= $d ?></option>
          <? } ?>
        </select>
        <select id="month">
          <option value="">Month</option>
          <? foreach (Options::$months as $m=>$mo) { ?>
              <option value="<?= $m ?>"><?= $mo ?></option>
          <? } ?>
        </select>
        <select id="year">
          <option value="">Year</option>
          <? $thisyear = date('Y',time()); ?>
          <? for ($y=$thisyear-14;$y>$thisyear-101;$y--) { ?>
              <option value="<?= $y ?>"><?= $y ?></option>
          <? } ?>
        </select>
			</div>
			<div>
				<label>Gender</label>
        <input class="hor" type="radio" name="gender" id="male" value="m"><label class="hor" for="male">Male</label>
        <input class="hor" type="radio" name="gender" id="female" value="f"><label class="hor" for="female">Female</label>
			</div>
			<div>
				<label class="sel">Ethnicity</label>
				<select id="ethnicity">
          <option value="">-</option>
          <? foreach (Options::$ethnicity as $opt=>$val) { ?>
            <option value="<?= $opt ?>"><?= $val ?></option>
          <? } ?>
        </select>
			</div>
		</li>
		<li class="form_section">
			<div>
				<label>Country</label>
				<input type="text" id="country" onkeydown="return Signup.Country.ProcessTab(event,this)" onkeyup="Signup.Country.Suggest(event,this)" onfocus="Signup.Country.Suggest(event,this)" onblur="Signup.Country.Clear(this);Signup.ChangeCountry(this)" autocomplete="off">
				<div id="Country" style="display:none" class="autofill"></div>
			</div>
			<div>
				<label for="state">State/Province/Region</label>
				<input type="text" id="state" onkeydown="return Signup.State.ProcessTab(event,this)" onkeyup="Signup.State.Suggest(event,this)" onfocus="Signup.State.Suggest(event,this)" onblur="Signup.State.Clear(this);Signup.ChangeState(this)" autocomplete="off">
	          	<div id="State" style="display:none" class="autofill"></div>
			</div>
			<div>
				<label for="city">City</label>
				<input type="text" id="city" onkeydown="return Signup.City.ProcessTab(event,this)" onkeyup="Signup.City.Suggest(event,this)" onfocus="Signup.City.Suggest(event,this)" onblur="Signup.City.Clear(this);" autocomplete="off">
	      <div id="City" style="display:none" class="autofill"></div>
			</div>
		</li>
		<li class="form_section">
			<div>
				<label for="website">Website</label>
				<input type="text" id="website" value="<?= $User->website ?>">
				<div class="option_subdued_text">Example: www.miio.com</div>
			</div>
			<div>
				<label>Looking for</label>
				<ul>
				  <? foreach (Options::$looking as $opt=>$val) { ?>
  					<li>
  						<input type="checkbox" id="lf_<?= $opt ?>">
  		        		<label class="ch" for="lf_<?= $opt ?>"><?= $val ?></label>
  					</li>
  			  <? } ?>
				</ul>
			</div>
			<div>
				<label class="sel" for="relationship">Relationship</label>
				<select id="relationship">
		      <option value="">-</option>
		      <? foreach (Options::$relationship as $opt=>$val) { ?>
		        <option value="<?= $opt ?>"><?= $val ?></option>
		      <? } ?>
		    </select>
			</div>
			<div>
				<label>Interested in</label>
				<input class="hor_last" type="checkbox" id="lf_male">
	      <label class="hor_last" for="lf_male">Men</label>
	      <input class="hor_last" type="checkbox" id="lf_female">
	      <label class="hor_last" for="lf_female">Women</label>
			</div>
		</li>
		<li class="form_section">
			<div class="attention">
				<h3>Profile Privacy</h3>
				<p>Setting your profile to &quot;Private&quot; will still display your username and profile photo publicly. Everything else will only be visible to your friends</p>
	      <input class="hor_last" type="radio" name="visibility" id="public" value="public" checked>
	      <label class="hor_last" for="public">Public</label>
	      <input class="hor_last" type="radio" name="visibility" id="private" value="private">
	      <label class="rw" for="private">Private (Friends Only)</label>
			</div>
		</li>
		<li>
			<div class="tags">
			  <div class="tag_count" id="settings_tags_count">140</div>
			  <div class="tagtext">Add keywords about topics that interest you so that other members that are interested in the same things can find you more easily.</div>
		    
		    <textarea class="full" id="settings_tags" onkeyup="return Signup.Count(event,this,'settings_tags_count');"></textarea>
		    <span>(Up to 140 characters. Use spaces to separate the keywords.)</span>
			</div>
		</li>
		<li class="buttons">
			<div class="right_buttons">
				<button class="short_button" onclick="Signup.SaveProfile()">Save profile and continue</button>
			</div>
			<a href="#" onclick="return Signup.LastStep()">back</a>	
		</li>
	</ul>
</div>