<? global $User, $USER_LIST, $RANDOM_LIST, $KEYWORDS, $KEYARRAY, $AVATAR_URL; ?>
<div id="found_members">

	<h2>These members like the same things you do<a href="#" onclick="return Signup.NextStep()">skip</a></h2>
	
	<ul>
	  <? if (count($USER_LIST)>0) { ?>
  		<li>
  			<p>
  				These members like or mention: <?= $KEYWORDS ?>
  			</p>
  		</li>
  		<li>
  			<div class="select_all">
  				<input type="checkbox" id="select_all_match" onclick="Signup.SelectAll(this,'match')" checked>
  				<label id="label_select_all_match">Unselect All</label>
  			</div>
  			
  			<ul class="results" id="match_results">
  			  <? foreach ($USER_LIST as $id) { ?>
  			    <?
  			      $user = User::get($id);
  			      if ($user->photo == "") $avatar = $AVATAR_URL.'default.jpg';
              else $avatar = $AVATAR_URL.$user->photo;
              
              $description = $user->description;
              $username = $user->username;
              if ($user->show_name)
              {
                $realname = trim($user->first_name . ' ' . $user->last_name);
                if ($realname != "") $realname = '('.$realname.')';
              }
              else $realname = "";
              
              foreach ($KEYARRAY as $val)
              {
                if (strtolower($user->username)==$val) $username="<span class='highlight'>$user->username</span>";
                $realname = preg_replace("/\b$val\b/i", '<span class="highlight">$0</span>', $realname);
                $description = preg_replace("/\b$val\b/i", '<span class="highlight">$0</span>', $description);
              }
              
              
              $tags = "";
              foreach ($user->tags as $tag)
              {
                if (in_array(Options::$tags[$tag],$KEYARRAY))
                {
                  $tags .= "<span class='highlight'>" . Options::$tags[$tag] . '</span> ';
                }
                else $tags .= Options::$tags[$tag] . ' ';
              }
  			    ?>
    				<li>
    					<div class="check_img">
    						<input type="checkbox" id="u_<?= $id ?>" value="<?= $id ?>" checked>
    						<img src="<?= $avatar ?>" height="<?= AVATAR_SIZE ?>" width="<?= AVATAR_SIZE ?>">
    					</div>
    					<div class="user_info">
    						<h4><?= $username ?> <?= $realname ?></h4>
    						<p><span>About:</span> <?= $description ?></p>
    						<p><span>Keywords:</span> <?= $tags ?></p>
    					</div>
    					<div class="clear"></div>
    				</li>
    		  <? } ?>
  			</ul>
  		</li>
  	<? } else { ?>
  	  <li>Sorry, we were not able to find any matches for: <?= $KEYWORDS ?></li>
  	<? } ?>
  	<? if (count($RANDOM_LIST)>0) { ?>
  	  <li>
  			<p>
  				Here are some other members for you to consider:
  			</p>
  		</li>
  		<li>
  			<div class="select_all">
  				<input type="checkbox" id="select_all_rand" onclick="Signup.SelectAll(this,'rand')" checked>
  				<label id="label_select_all_rand">Unselect All</label>
  			</div>
  			
  			<ul class="results" id="rand_results">
  			  <? foreach ($RANDOM_LIST as $id) { ?>
  			    <?
  			      $user = User::get($id);
  			      if ($user->photo == "") $avatar = $AVATAR_URL.'default.jpg';
              else $avatar = $AVATAR_URL.$user->photo;
              if ($user->show_name)
              {
                $realname = trim($user->first_name . " " . $user->last_name);
                if ($realname != "") $realname = '('.$realname.')';
              }
              else $realname = "";
  			    ?>
    				<li>
    					<div class="check_img">
    						<input type="checkbox" id="u_<?= $id ?>" value="<?= $id ?>" checked>
    						<img src="<?= $avatar ?>" height="<?= AVATAR_SIZE ?>" width="<?= AVATAR_SIZE ?>">
    					</div>
    					<div class="user_info">
    						<h4><?= $user->username ?> <?= $realname ?></h4>
    						<p><span>About:</span> <?= $user->description ?></p>
    					</div>
    					<div class="clear"></div>
    				</li>
    		  <? } ?>
  			</ul>
  		</li>
  	<? } ?>
		
		<li class="buttons">
			<div class="right_buttons">
				<button class="short_button" onclick="return Signup.Follow()">Add and continue</button>
			</div>
			<a href="#" onclick="location.reload();return false">back</a>	
		</li>
	</ul>
</div>