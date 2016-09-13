<? global $User, $Group; ?>

<? if (!$User->isOwner($Group->id)) { ?>
  <div class="not_admin">
    This page is reserved for the Owner of the <?= $Group->groupname ?> group only.
  </div>
<? } else { ?>
  <div id="disband_group">
    <p>
      <strong>Please note:</strong> Disbanding this group will permanently and completely remove
          the group from our system, along with the group messages!
    </p>
    <p class="center">
      To permanently disband this group, type &quot;disband&quot; in the box below and
          click &quot;Disband Group&quot;
    </p>
    <div class="form_section">
      <div>
        <input type="text" name="disband_group_text" id="disband_group_text">
      </div>
    </div>
    <div class="commit">
      <button class="norm_button" name="submit" id="submit" onclick="Group.Disband.FormSubmit()">Disband Group</button>
    </div>
  </div>
<? } ?>