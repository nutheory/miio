<? global $User, $Group, $LOGGEDIN, $AVATAR_URL; ?>

<div id="group_form">
  <div class="form_section">
    <h3 class="space">Choose one:</h3>
    <ul>
      <? if ($Group->visibility=='public') { ?>
        <li class="authorize">
          <input type="radio" name="group_invite" id="group_invite_all" value="1" checked onclick="Group.InviteMembers.FriendList(this)">
          <label for="group_invite">Invite <strong>your friends and followers</strong> to join</label>
        </li>
        <li class="authorize">
          <input type="radio" name="group_invite" id="group_invite_friends" value="1" onclick="Group.InviteMembers.FriendList(this)">
          <label for="group_invite">Invite <strong>only your friends</strong> to join</label>
        </li>
    <? } else { ?>
      <li class="authorize">
          <input type="radio" name="group_invite" id="group_invite_friends" value="1" checked onclick="Group.InviteMembers.FriendList(this)">
          <label for="group_invite">Invite <strong>only your friends</strong> to join</label>
        </li>
    <? } ?>
      <li class="authorize">
        <input type="radio" name="group_invite" id="group_invite_list" value="1" onclick="Group.InviteMembers.FriendList(this)">
        <label for="group_invite">Invite <strong>specific friends</strong> to join</label>
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
    </ul>
  </div>
  <div class="commit">
    <button class="norm_button" style="margin-right: 80px;" name="invite_cancel" id="invite_cancel" onclick="Group.InviteMembers.Cancel()">Cancel</button>
    <button class="norm_button" name="invite_submit" id="invite_submit" onclick="Group.InviteMembers.FormSubmit()">Send Invitations</button>
  </div>
</div>

<div class="form_response_short" id="form_response" style="display:none">
  <h3>Your invitations have been sent</h3>
  <div class="link_center"><a class="dash" href="groups/view/<?= $Group->id ?>">Go to the Group Timeline</a></div>
</div>
