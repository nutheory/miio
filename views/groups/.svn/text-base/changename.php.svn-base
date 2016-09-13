<? global $User, $Group, $PARAMS; ?>

<? if (!$User->isAdminOf($Group->id)) { ?>
  <div class="not_admin">
    This page is reserved for Administrators of the <?= $Group->groupname ?> group only.
  </div>
<? } else { ?>
  <div id="group_changename_form">
    <div class="form_section">
      <div>
        <label for="group_name">Group Name</label>
        <input type="text" name="group_name" id="group_name" value="<?= $Group->groupname ?>" maxlength=20>
        <button class="check_button" onclick="return Group.ChangeName.CheckName();">Check Availability</button>
        <div id="name_valid" style="display:none">Group Name Available</div>
        <div id="name_invalid" style="display:none">Group Name Unavailable</div>
        <h6>(3-20 Characters. No spaces, or periods)</h6>
      </div>
    </div>
    <div class="commit">
      <button class="norm_button" name="submit" id="submit" onclick="Group.ChangeName.FormSubmit()">Change Group Name</button>
    </div>
  </div>

  <div class="form_response" id="form_response" style="display:none">
    <h3>The group name has been updated</h3>
  <div class="link_center"><a class="dash" href="<?= $Group->getProfileLink() ?>">Go to the Group Timeline</a></div>

<? } ?>