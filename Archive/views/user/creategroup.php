<? global $User, $AVATAR_URL; ?>
<link href="css/forms.css" rel="stylesheet" type="text/css">

<div id="group_form">

  <div class="form_section">
    <div>
      <label for="group_name">Group Name</label>
      <input name="group_name" id="group_name" maxlength="20">
      <button class="check_button" onclick="return User.CreateGroup.CheckName();">Check Availability</button>
      <div id="name_valid" style="display:none">Group Name Available</div>
      <div id="name_invalid" style="display:none">Group Name Unavailable</div>
      <h6>(3-20 Characters. No spaces, or periods.)</h6>
    </div>
    <div>
      <label for="name">Full Group Name</label>
      <input type="text" id="name">
      <div class="option_subdued">
        <input type="checkbox" name="show_name" id="show_name" checked>
          <label class="ch">Show full group name on profile.</label>
      </div>
    </div>
    <div>
      <label for="group_category" class="sel">Select a category:</label>
      <select name="group_category" id="group_category">
        <option value="0">-- Select a Category --</option>
        <? foreach (Options::$category as $id=>$cat) { ?>
          <? if ($id>0) { ?><option value="<?= $id ?>"><?= $cat ?></option><? } ?>
        <? } ?>
      </select>
    </div>
    <div>
      <label for="group_website">Website</label>
      <input class="website" type="text" name="group_website" id="group_website">
      <h6>Example: http://www.miio.com. Up to 255 characters in length.</h6>
    </div>
  </div>
  <div class="form_section">
    <div>
      <label class="ab" for="group_description">About</label>
      <div class="about_count" id="group_count">140</div>
      <div class="about_text">Tell us about your group, in 140 characters or less.</div>
      <textarea class="full" name="group_description" id="group_description" onkeyup="return User.CreateGroup.Count(event,this,'group_count');"></textarea>
    </div>
  </div>
  <div class="form_section">
    <div>
      <label for="country">Country</label>
      <input type="text" name="country" id="country" onkeydown="return User.CreateGroup.Country.ProcessTab(event,this)" onkeyup="User.CreateGroup.Country.Suggest(event,this)" onfocus="User.CreateGroup.Country.Suggest(event,this)" onblur="User.CreateGroup.Country.Clear(this);User.CreateGroup.ChangeCountry(this)" autocomplete="off">
      <div id="Country" style="display:none" class="autofill"></div>
    </div>
    <div>
      <label for="state">State/Province/Region:</label>
      <input type="text" name="state" id="state" onkeydown="return User.CreateGroup.State.ProcessTab(event,this)" onkeyup="User.CreateGroup.State.Suggest(event,this)" onfocus="User.CreateGroup.State.Suggest(event,this)" onblur="User.CreateGroup.State.Clear(this);User.CreateGroup.ChangeState(this)" autocomplete="off">
      <div id="State" style="display:none" class="autofill"></div>
    </div>
    <div>
      <label for="city">City</label>
      <input type="text" name="city" id="city" onkeydown="return User.CreateGroup.City.ProcessTab(event,this)" onkeyup="User.CreateGroup.City.Suggest(event,this)" onfocus="User.CreateGroup.City.Suggest(event,this)" onblur="User.CreateGroup.City.Clear(this);" autocomplete="off">
      <div id="City" style="display:none" class="autofill"></div>
    </div>
  </div>

  <div class="form_section">
    <h3>Group Privacy</h3>
    <div class="privacy" title="Everyone can join this public group. Everyone can see the group content and group messages. Public groups can become private later on but private groups always remain private.">
      <input type="radio" name="group_visibility" id="group_public" value="public" checked onclick="User.CreateGroup.ChangePrivacy('public')">
      <label for="group_public">Public</label>
    </div>
    <div class="privacy" title="Group owner or administrators must invite or approve requests for new members to join this private group. Everyone can see the group photo and description, but only group members can see other members, content, and group messages. This group will be visible to you on your Dashboard but not to your visitors on your public profile page. Public groups can become private later on but private groups always remain private">
      <input type="radio" name="group_visibility" id="group_private" value="private" onclick="User.CreateGroup.ChangePrivacy('private')">
      <label for="group_private">Private</label>
    </div>
  </div>

  <div class="form_section">
    <div class="photo_form">
      <h3 class="space">Group Photo</h3>
      <p>This is the photo that will be displayed on your group's profile page</p>
      <form enctype="multipart/form-data" name="photo_form" id="photo_form" action="ajax/upload_photo" method="POST" target="upload_target">
        <div class="directions">
          <h3 class="browse">Browse for a photo</h3>
          <p>Photos larger than 600x600 pixels will be automatically resized.</p>
          <div class="browse_button">
            <input type="hidden" name="js_return" id="js_return" value="User.CreateGroup.PhotoReturn">
            <input type="hidden" name="js_error" id="js_error" value="User.CreateGroup.PhotoError">
            <input type="hidden" name="js_url" id="js_url" value="User.CreateGroup.PhotoURL">
            <label class="up" for="photo_file_source">Upload photo:</label>
            <div class="highlight"><input type="file" name="photo_file_source" id="photo_file_source"></div>
            <p>Please be patient as photos may take a while to load.</p>
          </div>
          <div class="commit">
            <input type="submit" class="norm_button" name="photo_submit" id="photo_submit" value="Preview" onclick="User.CreateGroup.PhotoPreview();">
          </div>
        </div>
      </form>
    </div>

    <div id="group_photo_container" style="display:none">
      <h3 class="browse" style="margin-top:0;text-align:left;font-weight:normal">Your group photo:</h3>
      <div class="view_preview">
        <img id="group_photo" src="">
      </div>
      <button class="short_button" onclick="return User.CreateGroup.CancelPhoto();">Cancel</button>
    </div>
  </div>

  <div class="form_section">
    <div class="tags">
      <label class="ab">Keywords</label>
      <div class="tag_count" id="tag_count">140</div>
      <div class="about_text">Add keywords that describe what this group is about so that other members that are interested in the same things can find your group more easily</div>
      <textarea class="full" name="group_tags" id="group_tags" onkeyup="return User.CreateGroup.TagCount(event,this);"></textarea>
      <span>(Up to 140 characters. Use spaces to seperate the keywords.)</span>
    </div>
  </div>

  <div class="form_section" id="announce_group">
    <h3 class="space">Announce</h3>
    <div class="authorize">
      <input type="checkbox" name="group_announce" id="group_announce" value="1" checked>
        <label for="group_announce"><strong>Publicly announce</strong> to everyone the creation of this group.</label>
    </div>
  </div>
  <div class="form_section">
    <h3 class="space">Invite</h3>
    <h4>Choose one:</h4>
    <ul>
      <li class="authorize">
        <input type="radio" name="group_invite" id="group_invite_all" value="1" checked onclick="User.CreateGroup.FriendList(this)">
        <label for="group_invite_all">Invite <strong>your friends and followers</strong> to join</label>
      </li>
      <li class="authorize">
        <input type="radio" name="group_invite" id="group_invite_friends" value="1" onclick="User.CreateGroup.FriendList(this)">
        <label for="group_invite_friends">Invite <strong>only your friends</strong> to join</label>
      </li>
      <li class="authorize">
        <input type="radio" name="group_invite" id="group_invite_list" value="1" onclick="User.CreateGroup.FriendList(this)">
        <label for="group_invite_list">Invite <strong>specific friends</strong> to join</label>
        <div id="invite_friend_list" class="friend_list" style="display:none">
          <ul>
            <? foreach ($User->friends as $id) { ?>
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
                  <input type="checkbox" id="u_<?= $id ?>" value="<?= $id ?>">
                  <img src="<?= $avatar ?>" height="<?= AVATAR_SIZE ?>" width="<?= AVATAR_SIZE ?>">
                </div>
                <div class="friend_info">
                  <?= $user->username ?> <?= $realname ?>
                </div>
              </li>
            <? } ?>
          </ul>
        </div>
      </li>
      <li class="authorize">
        <input type="radio" name="group_invite" id="group_invite_none" value="1" onclick="User.CreateGroup.FriendList(this)">
        <label for="group_invite_none">Don't invite anyone</label>
      </li>
    </ul>
  </div>

  <div class="notice">
    Composing, creating, linking, or uploading unauthorized copyrighted material or inappropriate material
      of any kind is against our <a href="pages/terms">Terms of Use</a>
      and <a href="pages/copyright">Copyright Policy</a> and may result in your membership being canceled.
  </div>
  <div class="commit">
    <button class="norm_button" name="group_submit" id="group_submit" onclick="User.CreateGroup.FormSubmit()">Create Group</button>
  </div>
</div>

<div id="group_done" style="display:none">
  Your group has been created. See it <a href="#" onclick="return User.CreateGroup.GoToGroup()";>here</a>
</div>
