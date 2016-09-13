<? global $User, $Group, $PARAMS; ?>

<? if (!$User->isOwnerOf($Group->id)) { ?>
  <div class="not_admin">
    This page is reserved for the Owner of the <?= $Group->groupname ?> group only.
  </div>
<? } else { ?>

  <div id="group_profile_form">

    <div class="form_section">
      <div>
        <label for="full_name">Full Group Name</label>
        <input id="full_name" value="<?= $Group->name ?>">
        <div class="option_subdued">
          <input type="checkbox" name="show_name" id="show_name" <? if ($Group->show_name) echo "checked"; ?>>
            <label class="ch">Show full group name on profile.</label>
        </div>
      </div>
      <div>
        <label for="category" class="sel">Category</label>
        <select name="group_category" id="category">
          <? for ($id=1;$id<count(Options::$category);$id++) { ?>
            <option value="<?= $id ?>" <? if ($id==$Group->category) echo "selected"; ?>><?= Options::$category[$id] ?></option>
          <? } ?>
        </select>
      </div>
      <div>
        <label for="website">Website</label>
        <input class="website" type="text" name="website" id="website" value="<?= $Group->website ?>">
        <h6>Example: http://www.miio.com. Up to 255 characters in length.</h6>
      </div>
    </div>

    <div class="form_section">
      <div>
        <label class="ab" for="group_description">About</label>
        <div class="about_count" id="group_count"><?= 140-strlen($Group->description) ?></div>
        <div class="about_text">Tell us about yourself, in 140 characters or less.</div>
        <textarea class="full" name="group_profile_description" id="group_profile_description" onkeyup="return Group.Profile.Count(event,this,'group_count');"><?= $Group->description ?></textarea>
      </div>
    </div>
    <div class="form_section">
      <div>
        <label for="country">Country</label>
        <input type="text" name="country" id="country" onkeydown="return Group.Profile.Country.ProcessTab(event,this)" onkeyup="Group.Profile.Country.Suggest(event,this)" onfocus="Group.Profile.Country.Suggest(event,this)" onblur="Group.Profile.Country.Clear(this);Group.Profile.ChangeCountry(this)" autocomplete="off" value="<?= $Group->location['country'] ?>">
        <div id="Country" style="display:none" class="autofill"></div>
      </div>
      <div>
        <label for="state">State/Province/Region</label>
        <input type="text" name="state" id="state" onkeydown="return Group.Profile.State.ProcessTab(event,this)" onkeyup="Group.Profile.State.Suggest(event,this)" onfocus="Group.Profile.State.Suggest(event,this)" onblur="Group.Profile.State.Clear(this);Group.Profile.ChangeState(this)" autocomplete="off" value="<?= $Group->location['region'] ?>">
        <div id="State" style="display:none" class="autofill"></div>
      </div>
      <div>
        <label for="country">City</label>
        <input type="text" name="city" id="city" onkeydown="return Group.Profile.City.ProcessTab(event,this)" onkeyup="Group.Profile.City.Suggest(event,this)" onfocus="Group.Profile.City.Suggest(event,this)" onblur="Group.Profile.City.Clear(this);" autocomplete="off" value="<?= $Group->location['city'] ?>">
        <div id="City" style="display:none" class="autofill"></div>
      </div>
    </div>
    <div class="form_section">
      <div class="attention privacy">
        <h3>Group Privacy</h3>
        <? if ($Group->visibility==Enum::$visibility['public']) { ?>
          <p>Setting your group to &quot;Private&quot; will still display your group name and photo publicly. Everything else will only be visible to your members.</p>
          <p>Please note: This cannot be undone. Once your group is Private, you cannot make it Public again.</p>
          <input type="checkbox" id="group_private" value="private" onclick="Group.Profile.MakePrivate(this)">
          <label for="group_private">Make this group Private</label>
        <? } else { ?>
          <p>The <?= $Group->groupname ?> group is a Private group.</p>
        <? } ?>
      </div>
    </div>

    <div class="form_section">
      <div class="tags">
        <?
          $keywords = implode(' ',$Group->keywords);
        ?>
        <label class="ab">Keywords</label>
        <div class="tag_count" id="tag_count"><?= 140-strlen($keywords) ?></div>
        <div class="about_text">Add keywords that describe what this group is about so that other members that are interested in the same things can find your group more easily</div>
        <textarea class="full" name="tags" id="tags" onkeyup="return Group.Profile.TagCount(event,this);"><?= $keywords ?></textarea>
        <span>(Up to 140 characters. Use spaces to seperate the keywords.)</span>
      </div>
    </div>

    <div class="commit">
      <button class="norm_button" name="submit" id="submit" onclick="Group.Profile.FormSubmit()">Update Profile</button>
    </div>
  </div>

  <div class="form_response" id="group_profile_updated" style="display:none">
    <h3>The <?= $Group->groupname ?> group's Profile information has been updated</h3>
    <div class="link_center"><a class="dash" href="<?= $Group->getProfileLink() ?>">Go to the Group Timeline</a></div>
  </div>

<? } ?>
