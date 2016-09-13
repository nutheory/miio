<? global $User, $USER_LIST, $AVATAR_URL; ?>
<div id="contactuserlist">
  <div>
    Some of your email contacts are already using Miio. Please click the &quot;Follow Selected&quot;
    button below to follow their feeds on Miio.
  </div>
  <div class="tablehead">
    <input type="checkbox" id="member_selectall" onclick="Invite.SelectAllMemberContacts(this.checked)" checked>
    Select All
  </div>
  <div class="tablecontainer">
    <table>
      <? foreach ($USER_LIST as $u) { ?>
        <?
          if (is_array($u)) $user = User::get($u['group_id']);
          else $user = User::get($u);
          // leave in for testing:
          //$user->visibility = 'private';
          if ($user->photo == "") $avatar = $AVATAR_URL.'default.jpg';
          else $avatar = $AVATAR_URL.$user->photo;
          $avatar .= "?x=" . microtime(true);
          $username = $user->username;
          if ($user->show_name) $realname = ' (' . $user->first_name . ' ' . $user->last_name . ')';
        ?>
        <tr>
          <td>
            <table class="user">
              <tr>
                <?
                  if ($user->visibility=='public') echo '<td class="action" rowspan=3>';
                  else echo '<td class="action" rowspan=2>';
                ?>
                  <input type="checkbox" name="subscribe_<?= $user->id ?>" value="<?= $user->id ?>" checked>
                </td>
                <td class="avatar"><a href="members/profile/<?= $user->id ?>"><img src="<?= $avatar ?>"></a></td>
                <td class="name"><a href="members/profile/<?= $user->id ?>"><?= $username ?></a><?= $realname ?></td>
              </tr>
              <? if ($user->visibility=='public') { ?>
                <tr>
                  <td class="userinfo" colspan=2>
                    Public Profile<?
                      $str = "";
                      if ($user->relationship && $user->relationship != "na") $str .= Options::$relationship[$user->relationship];
                      if ($user->gender)
                      {
                        if ($str!="") $str .= ", ";
                        $str .= Options::$gender[$user->gender];
                      }
                      if ($user->age)
                      {
                        if ($str!="") $str .= ", ";
                        $str .= "$user->age";
                      }
                      if ($location!="")
                      {
                        if ($str!="") $str .= ", ";
                        $str .= "from $location";
                      }
                      if ($str != "") echo ": $str";
                    ?>
                  </td>
                </tr>
                <tr>
                  <td class="description" colspan=2>
                    <div>
                      <label>Description:</label>
                      <?
                        if ($user->description!="") echo $user->description;
                        else echo '<span class="none">No description entered</span>';
                      ?>
                    </div>
                    <div><label>Following:</label> <?= count($user->following) ?></div>
                    <div><label>Followers:</label> <?= count($user->followers) ?></div>
                    <? if ($tags) { ?>
                      <div><label>Tags:</label> <?= $tags ?></div>
                    <? } ?>
                  </td>
                </tr>
              <? } else { ?>
                <tr>
                  <td class="userinfo" colspan=2>Private Profile</td>
                </tr>
              <? } ?>
            </table>
          </td>
        </tr>
        <!--
        <tr>
          <td class="avatar"><img src="<?= $avatar ?>"></td>
          <td class="username"><?= $username ?></td>
          <td class="action">
            <? if ($user->visibility=='public') { ?>
              <? if (!$User->isSubscribed($user->id)) { ?>
                <a href="#" onclick="return Invite.Subscribe(<?= $user->id ?>);">Follow</a>
              <? } else if (!$User->isFriend($user->id)) { ?>
                <a href="#" onclick="return Invite.SendFriendRequest(<?= $user->id ?>);">Send Friend Request</a>
              <? } ?>
            <? } else { ?>
              Private Profile
            <? } ?>
          </td>
        </tr>
        -->
      <? } ?>
    </table>
  </div>
  <div class="tablefoot">
    <button name="subscribe" id="subscribe" onclick="Invite.Subscribe(this)">Follow Selected</button>
  </div>
</div>