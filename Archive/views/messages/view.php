<? global $User, $Sender, $Group, $Profile, $MESSAGE, $REPLY, $SHARE, $REPLY_PAGE, $PARAMS, $GET; ?>
<?
  $ISME = ($User==$Profile);
  $ISMINE = ($MESSAGE->sent_by==$User->id);
  include_once('views/partials/profile.php');
  if ($Sender->isBlockedBy($User->id)) $BLOCKED = true;
  if ($MESSAGE->source == Enum::$source['twitter']) $tw = true;
  else $tw = false;

  if ($MESSAGE->type == Enum::$message_type['rss']) $rss = true;
  else $rss = false;

  if ($tw)
  {
    $fromname = $MESSAGE->foreign_sender;
    $profilename = $fromname;
    $senderlink = '"http://twitter.com/'.$MESSAGE->foreign_sender.'" target="_blank"';
    $profilelink = $senderlink;
    $tw_miio_user = false;
  }
  else
  {
    $fromname = $Sender->username;
    $profilename = $Profile->username;
    $senderlink = '"members/profile/'.$Sender->id.'"';
    if ($Profile->is_group) $profilelink = '"groups/view/'.$Profile->id.'"';
    else $profilelink = '"members/profile/'.$Profile->id.'"';
    $miio_user = true;
  }
?>

<link href="css/message.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/message.js"></script>

<?

// DETERMINE PRIVACY AND WHETHER USER IS ALLOWED TO SEE THIS MESSAGE

if($tw) $avatar = $MESSAGE->foreign_image;

if (count($MESSAGE->sent_to)==1) $sentto = User::get($MESSAGE->sent_to);

?>

<table>
  <tr>
    <td id="left_col">
      <? if($tw) { ?>
        <div id="twlogo" class="profile_photo"><img src="logos/twitter.jpg"></div>
      <? } else { ?>
        <? if ($Profile->is_group) { ?>
          <img src="images/group_header.png" class="group_header">
        <? } ?>
        <div class="profile_photo">
          <?
            $time = microtime(true);
            if ($Profile->photo == "") $photo = 'profile_photos/default.jpg';
            else $photo = "profile_photos/".$Profile->photo."?x=$time";
            $photosize = Image::resize($photo,PROFILE_PAGE_PHOTO_HEIGHT,PROFILE_PAGE_PHOTO_WIDTH);
          ?>
          <? if ($Profile->is_group) { ?>
            <a href="groups/view/<?= $Profile->id ?>"><img src="<?= $photo ?>" height=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> width=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> id="profile_photo" alt='photo'></a>
          <? } else { ?>
            <a href="members/profile/<?= $Profile->id ?>"><img src="<?= $photo ?>" height=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> width=<?= PROFILE_PAGE_PHOTO_HEIGHT ?> id="profile_photo" alt='photo'></a>
          <? } ?>
        </div>
      <? } ?>

      <? if (!$PRIVATE) { ?>
        <div class="menu_box">
          <? if ($ISME) { ?>
            <h2 class="username"><?= $Profile->username ?></h2>
          <? } else { ?>
            <? if ($Profile->is_group) { ?>
              <h2 class="username"><a href="groups/view/<?= $Profile->id ?>"><?= $Profile->username ?></a></h2>
              <div class="backlink">
                <a href="groups/view/<?= $Profile->id ?>">Back to the <?= $Profile->username ?> group page</a>
              </div>
            <? } else { ?>
              <h2 class="username"><a href="members/profile/<?= $Profile->id ?>"><?= $Profile->username ?></a></h2>
              <div class="backlink">
                <a href="members/profile/<?= $Profile->id ?>">Back to <?= $Profile->username ?>'s profile</a>
              </div>
            <? } ?>
          <? } ?>

          <h2>Share this Message</h2>
          <? Render ('partials','foreignshare'); ?>

          <h2>Message URL</h2>
          <div class="copy">
            <input type="text" value="<?= LOC ?>_<?= $MESSAGE->id ?>">
          </div>
        </div>
      <? } ?>
    </td>

    <td id="center_col">
      <div id="content_div" class="content_with_rcol" onmouseover="Message.PauseUpdates()" onmouseout="Message.ResumeUpdates()">

        <? if ($LOGGEDIN && $CONFIRMED) { ?>
          <div class="top_links">
            <? if ($ISME) { ?>
              Hey, this is you!
            <? } else { ?>
              <? if ($Profile->is_group) { ?>
                <? if (!$User->isAdmin($Profile->id)) { ?>
                  <span class="link">
                    <? if ($User->hasReported($Profile->id)) { ?>
                      <a id="report_link" href="groups/view/<?= $Profile->id ?>#report" style="display:none">Report</a>
                      <span id="reported_text">Reported</span>
                    <? } else { ?>
                      <a id="report_link" href="groups/view/<?= $Profile->id ?>#report">Report</a>
                      <span id="reported_text" style="display:none">Reported</span>
                    <? } ?>
                  </span>
                <? } ?>
              <? } else { ?>
                <span class="link">
                  <? if ($User->isBlocked($Profile->id)) { ?>
                    <a id="block_member" href="#" onclick="return Message.Message.Profile.Block(<?= $Profile->id ?>,'<?= $Profile->username ?>')" style="display:none">Block</a>
                    <span id="unblock_member">
                      Blocked
                      &nbsp;
                      (<a href="#" onclick="return Message.Profile.UnBlock(<?= $Profile->id ?>,'<?= $Profile->username ?>')">Remove block</a>)
                    </span>
                  <? } else { ?>
                    <a id="block_member" href="#" onclick="return Message.Profile.Block(<?= $Profile->id ?>,'<?= $Profile->username ?>')">Block</a>
                    <span id="unblock_member" style="display:none">
                      Blocked
                      &nbsp;
                      (<a href="#" onclick="return Message.Profile.UnBlock(<?= $Profile->id ?>,'<?= $Profile->username ?>')">Remove block</a>)
                    </span>
                  <? } ?>
                </span>

                <span class="link">
                  <? if ($User->hasReported($Profile->id)) { ?>
                    <a id="report_link" href="members/profile/<?= $Profile->id ?>#report" style="display:none">Report</a>
                    <span id="reported_text">Reported</span>
                  <? } else { ?>
                    <a id="report_link" href="members/profile/<?= $Profile->id ?>#report">Report</a>
                    <span id="reported_text" style="display:none">Reported</span>
                  <? } ?>
                </span>
              <? } ?>
            <? } ?>
          </div>
        <? } ?>

        <div class="username">
          <?= $Profile->username ?>
          <?
            if ($Profile->show_name)
            {
              $fullname = trim($Profile->first_name . ' ' . $Profile->last_name);
              if ($fullname != '') echo "<span>($fullname)</span>";
            }
          ?>
          <? if ($Profile->visibility=='private') { ?>
            <img src='images/private.png' alt='Private'>
          <? } ?>
        </div>

        <? if (!LOGGEDIN) { ?>
          <div class="ls_actionlinks">
            <? if ($Group) { ?>
              <a href="user/login">Login</a> or <a href="signup">Sign up</a> to Join the <?= $Group->groupname ?> group
            <? } else { ?>
              <a href="user/login">Login</a> or <a href="signup">Sign up</a> to Follow <?= $Profile->username ?>
            <? } ?>
          </div>
        <? } else if (!CONFIRMED) { ?>
          <div class="ls_actionlinks">
            <? if ($Group) { ?>
              <a href="signup/confirm">Confirm your account</a> to Join the <?= $Group->groupname ?> group
            <? } else { ?>
              <a href="signup/confirm">Confirm your account</a> to Follow <?= $Profile->username ?>
            <? } ?>
          </div>
        <? } else { ?>
          <? if (!$ISME) { ?>
            <div class="actionlinks">
              <? if ($Group) { ?>
                <? if ($User->isOwnerOf($Group->id)) { ?>
                  <span id="status_member" class="status">
                    You are the Owner of this group
                  </span>
                <? } else if ($User->isAdminOf($Group->id)) { ?>
                  <span id="status_member" class="status">
                    You are an Administrator of this group
                  </span>
                <? } else if ($User->isMemberOf($Group->id)) { ?>
                  <span id="status_member" class="status">
                    You are a Member of this group
                  </span>
                <? } else if ($User->membershipRequested($Group->id)) { ?>
                  <span id="status_requested" class="status">Membership request pending</span>
                <? } else { ?>
                  <span id="status_not_member" class="status">
                    You are not a Member of this group
                  </span>
                <? } ?>

                <? if ($User->isMemberOf($Group->id)) { ?>
                  <? if (!$User->isAdminOf($Group->id)) { ?>
                    <a href="#" onclick="return Message.Group.Leave('<?= $Group->id ?>','<?= $Group->groupname ?>')" id="leave_link">Leave Group</a>
                  <? } ?>
                  <a href="#" onclick="return Message.Group.Join('<?= $Group->id ?>')" id="join_link" style="display:none">Join</a>
                  <a href="groups/view/<?= $Profile->id ?>#manage" id="member_preferences">Manage</a>
                <? } else { ?>
                  <? if ($Group->visibility==Enum::$visibility['private']) { ?>
                    <? if (!$User->membershipRequested($Group->id)) { ?>
                      <span id="status_requested" class="status" style="display:none">Membership request pending</span>
                      <a href="#" onclick="return Message.Group.RequestMembership('<?= $Group->id ?>')" id="join_link">Request Membership</a>
                    <? } ?>
                  <? } else { ?>
                    <a href="#" onclick="return Message.Group.Leave('<?= $Group->id ?>','<?= $Group->username ?>')" id="leave_link" style="display:none">Leave Group</a>
                    <a href="#" onclick="return Message.Group.Join('<?= $Group->id ?>')" id="join_link">Join</a>
                    <a href="groups/view/<?= $Group->id ?>#manage" id="member_preferences" style="display:none">Manage</a>
                  <? } ?>
                <? } ?>
              <? } else { ?>
                <? if ($User->isFollowing($Profile->id)) { ?>
                  <span id="status_following" class="status">
                    Following
                  </span>
                  <span id="status_not_following" style="display:none" class="status">
                    Not following
                  </span>
                  <a href="#" onclick="return Message.Profile.Unsubscribe('<?= $Profile->id ?>','<?= $Profile->username ?>')" id="unfollow_link">Stop Following</a>
                  <? if ($Profile->visibility=='private') { ?>
                    <a href="#" onclick="return Message.Profile.RequestSubscription('<?= $Profile->id ?>')" id="follow_link" style="display:none">Ask to Follow</a>
                    <a href="#" onclick="return Message.Profile.CancelRequest('<?= $Profile->id ?>')" id="cancel_follow_link" style="display:none">Cancel follow request</a>
                  <? } else { ?>
                    <a href="#" onclick="return Message.Profile.Subscribe('<?= $Profile->id ?>')" id="follow_link" style="display:none">Follow</a>
                  <? } ?>
                  <a href="members/profile/<?= $Profile->id ?>#manage" id="follow_preferences">Manage</a>
                <? } else { ?>
                  <span id="status_following" class="status" style="display:none">
                    Following
                  </span>
                  <span id="status_not_following" class="status">
                    Not following
                  </span>
                  <? if ($User->isBlocking($Profile->id)) { ?>
                    <span class="blocked">Blocked</span>
                  <? } else { ?>
                    <a href="#" onclick="return Message.Profile.Unsubscribe('<?= $Profile->id ?>','<?= $Profile->username ?>')" id="unfollow_link" style="display:none">Stop Following</a>
                    <? if ($Profile->visibility==Enum::$visibility['private']) { ?>
                      <? if ($User->followRequested($Profile->id)) { ?>
                        <a href="#" onclick="return Message.Profile.RequestSubscription('<?= $Profile->id ?>')" id="follow_link" style="display:none">Ask to Follow</a>
                        <a href="#" onclick="return Message.Profile.CancelRequest('<?= $Profile->id ?>')" id="cancel_follow_link">Cancel follow request</a>
                      <? } else { ?>
                        <a href="#" onclick="return Message.Profile.RequestSubscription('<?= $Profile->id ?>')" id="follow_link">Ask to Follow</a>
                        <a href="#" onclick="return Message.Profile.CancelRequest('<?= $Profile->id ?>')" id="cancel_follow_link" style="display:none">Cancel follow request</a>
                      <? } ?>
                    <? } else { ?>
                      <a href="#" onclick="return Message.Profile.Subscribe('<?= $Profile->id ?>')" id="follow_link">Follow</a>
                    <? } ?>
                    <a href="members/profile/<?= $Profile->id ?>#manage" id="follow_preferences" style="display:none">Manage</a>
                  <? } ?>
                <? } ?>
                &nbsp;
              <? } ?>
            </div>
          <? } else { ?>
            <hr class="separator"></hr>
          <? } ?>
        <? } ?>

        <? if ($PRIVATE) { ?>
          <div id="private_content">
            <? if ($Group && $User->membershipRequested($Group->id)) { ?>
              <p>This message is posted in a private group. You have requested membership in the <?= $Group->groupname ?> group. Your membership request is pending.</p>
            <? } else { ?>
              <p>This message is posted in a private group. Please click the "Request Membership" button to request membership in the <?= $Group->groupname ?> group. If the <?= $Group->groupname ?> group accepts your request you can follow <?= $Group->groupname ?>'s messages on Miio.</p>
              <div>
                <button class="short_button" onclick="return Message.Group.RequestMembership('<?= $Group->id ?>')" id="join_button">Request Membership</button>
              </div>
            <? } ?>
          </div>
          <div id="sub_requested" style="display:none">Your membership request was sent</div>
        <? } else if ($MESSAGE->sharing==Enum::$sharing['private'] && !($User->id==$MESSAGE->sent_by || in_array($User->id,$MESSAGE->sent_to))) { ?>
          <input type="hidden" id="message_is_private" value=1>
          <div class="message" id="message">
            This is a private message. You do not have permission to view it.
          </div>
        <? } else if ($MESSAGE->sharing==Enum::$sharing['friends'] && !($User->id==$MESSAGE->sent_by || $User->isFriend($MESSAGE->sent_by))) { ?>
          <input type="hidden" id="message_is_private" value=1>
          <div class="message" id="message">
            This is a private message. You do not have permission to view it.
          </div>
        <? } else { ?>
          <input type="hidden" id="message_is_private" value=0>
          <div class="counter_container">

            <div id="update_counter" style="display:none">
              <span id="replycounter1" style="display:none">1 new reply.</span>
              <span id="replycounterx" style="display:none"><span id="update_replycount">999</span> new replies.</span>
              <span id="sharecounter1" style="display:none">1 new share.</span>
              <span id="sharecounterx" style="display:none"><span id="update_sharecount">999</span> new shares.</span>
              <a href="messages/view/<?= $MESSAGE->id ?>">Refresh</a> to view.
            </div>

            <div id="pause_counter" style="display:none">
              Paused<span id="pausecolon" style="display:none">:</span>
              <span id="pausereply1" style="display:none">1 new reply.</span>
              <span id="pauseshare1" style="display:none">1 new share.</span>
              <span id="pausereplyx" style="display:none"><span id="pause_replycount">999</span> new replies.</span>
              <span id="pausesharex" style="display:none"><span id="pause_sharecount">999</span> new shares.</span>
            </div>
          </div>

          <div class="message" id="message">
            <div class="message_header"></div>
            <!-- message_body -->
            <div class="message_body">
              <?
                if ($MESSAGE->sentTo()=='everyone')
                {
                  $toname = "<span>everyone</span>";
                }
                else if (count($MESSAGE->sent_to)==1)
                {
                  $Recipient = User::get($MESSAGE->sent_to[0]);
                  if ($Recipient) $toname = "<a href='".$Recipient->getProfileLink()."'>$Recipient->username</a>";
                }
                else
                {
                  $to_list = "";
                  $comma = false;
                  foreach ($MESSAGE->sent_to as $rec)
                  {
                    $Recipient = User::get($rec);
                    if ($Recipient)
                    {
                      if($comma) $to_list .= ", ";
                      $to_list .= "<a href='".$Recipient->getProfileLink()."'>$Recipient->username</a>";
                      $comma = true;
                    }
                  }
                }lse
              ?>

              <?= $Sender->getAvatar() ?>

              <div class='said_to'>
                <a href="<?= $Sender->getProfileLink() ?>"><?= $Sender->username ?></a>
                <?
                  if ($MESSAGE->sharing==Enum::$sharing['group']) echo "said in <a href='".$Group->getProfileLink()."'>$Group->groupname</a>";
                  else {
                    echo "said to";
                  ?>
                  <? if ($to_list) { ?>
                    <a href="#" onclick="return Message.ShowRecipients();" id="showrecipients">Multiple People</a>
                    <span class="recipientname" id="showingrecipients" style="display:none">Multiple People</span>
                    <?
                      if ($MESSAGE->sharing==Enum::$sharing['public']) echo " publicly:";
                      else echo " privately:";
                    ?>

                    <div class="recipient_list" id="recipients" style="display:none">
                      <a href="#" onclick="return Message.HideRecipients();" class="close"><img src="images/close2.gif" alt="X"></a>
                      <?= $to_list ?>
                    </div>
                  <? } else if ($MESSAGE->sharing==Enum::$sharing['friends']) { ?>
                    friends only:
                  <? } else { ?>
                    <?
                      echo $toname;
                      if ($MESSAGE->sharing==Enum::$sharing['public']) echo " publicly:";
                      else echo " privately:";
                    ?>
                  <? } ?>
                <? } ?>
              </div>
              <!-- end said_to -->

              <div class='messagetext'>
                <span class="message_type"><?= Options::$message_type[$MESSAGE->type] ?>:</span>
                <span class="messagetext" id="messagetext"><?= $MESSAGE->messageText() ?></span>
              </div>
              <!-- end messagetext -->

              <? if ($MESSAGE->link['type']==Enum::$link_type['image']) { ?>
                <?
                  $resize = Image::sizeImage($MESSAGE->link['height'],$MESSAGE->link['width'],PHOTO_MAX_DISPLAY_HEIGHT,PHOTO_MAX_DISPLAY_WIDTH);
                ?>
                <div class="preview">
                  <a href="<?= $MESSAGE->link['uri'] ?>" target="_blank">
                    <img src="<?= $MESSAGE->link['uri'] ?>" height=" <?= $resize['ht'] ?>" width="<?= $resize['wd'] ?>">
                  </a>
                </div>
              <? } else if ($MESSAGE->link['type']==Enum::$link_type['url']) { ?>
                <div class="preview link">
                  <a href="<?= $MESSAGE->link['uri'] ?>" target="_blank">
                    <img src="<?= THUMB ?><?= $MESSAGE->link['uri'] ?>" height=240 width=320>
                  </a>
                </div>
              <? } else if ($MESSAGE->link['type']==Enum::$link_type['embed']) { ?>
                <?
                  $woffset = strpos($MESSAGE->link['uri'],'width')+7;
                  $wd = substr($MESSAGE->link['uri'],$woffset,strpos($MESSAGE->link['uri'],"'",$woffset)-$woffset);
                  $w = $wd;
                  $hoffset = strpos($MESSAGE->link['uri'],'height')+8;
                  $ht = substr($MESSAGE->link['uri'],$hoffset,strpos($MESSAGE->link['uri'],"'",$hoffset)-$hoffset);
                  $h = $ht;
                  $adj = 1;
                  if ($wd > VIDEO_MAX_DISPLAY_WIDTH)
                  {
                    $adj = VIDEO_MAX_DISPLAY_WIDTH / $wd;
                  }
                  if ($ht * $adj > VIDEO_MAX_DISPLAY_HEIGHT)
                  {
                    $adj = VIDEO_MAX_DISPLAY_HEIGHT / $ht;
                  }
                  $ht = floor($ht*$adj);
                  $wd = floor($wd*$adj);
                  $linktext = str_replace("width='$w'","width='$wd'",$MESSAGE->link['uri']);
                  $linktext = str_replace("height='$h'","height='$ht'",$linktext);
                ?>
                <div class="preview" id="message_embed">
                  <?= $linktext ?>
                </div>
                <div id="message_embed_placeholder" style="height:<?= $ht ?>px;display:none"></div>
              <? } ?>
              <? if ($MESSAGE->type==Enum::$message_type['location']) { ?>
                <?
                  global $gmap_key;
                  $loc = urlencode($MESSAGE->location['address'].' '.$MESSAGE->location['city'].' '.$MESSAGE->location['region'].' '.$MESSAGE->location['country']);
                ?>
                <a href="http://maps.google.com/maps?q=<?= $loc ?>&z=14" target='_blank'>
                  <img class="map" src="http://maps.google.com/maps/api/staticmap?markers=<?= $loc ?>&zoom=14&size=460x300&maptype=roadmap&sensor=false&key=<?= $gmap_key ?>">
                </a>
              <? } ?>

              <div class="links">
                <span class="sent">
                  <? $sent = $MESSAGE->sent(); ?>
                  Sent <span><?= $sent['when'] ?></span>
                  <?= $sent['how'] ?>
                </span>
                <ul>
                  <li>
                    <a href="#" id="profile_info_link" onclick="return Message.ShowMemberInfo(this);"><img src='images/messagetypes_transparent/contactinfo.png'></a>
                    <a href="#" id="profile_info_close_link" onclick="return Message.HideMemberInfo(this);" style="display:none"><img src='images/messagetypes_transparent/contactinfo.png'></a>
                    <label>About</label>
                  </li>
                  <li>
                    <a href="#" id="message_info_link" onclick="return Message.ShowMessageInfo(this);"><img src='images/messagetypes_transparent/objectinfo.png'></a>
                    <a href="#" id="message_info_close_link" onclick="return Message.HideMessageInfo(this);" style="display:none"><img src='images/messagetypes_transparent/objectinfo.png'></a>
                    <label>Metadata</label>
                  </li>
                  <? if ($MESSAGE->sharing==Enum::$sharing['public'] && (($Group && $Group->visibility==Enum::$visibility['public'] && $User->isMember($Group->id)) || $Profile)) { ?>
                    <li <? if ($SHARE) echo 'class="share_active"'; else echo 'class="share"'; ?>>
                      <a href="#" id="sharelink" onclick="return Message.OpenShares(this);">
                        <? $Shares = $MESSAGE->getShares(); ?>
                        <? if (count($Shares)>0) { ?>
                          <span class="count" id="shares_count"><?= count($Shares) ?></span>
                        <? } else { ?>
                          <span class="count" id="shares_count" style="display:none"></span>
                        <? } ?>
                        <img src='images/messagetypes_transparent/share.png'>
                      </a>
                      <label>Share</label>
                    </li>
                    <input id="totalshares" type="hidden" value="<?= count($Shares) ?>">
                  <? } ?>
                  <li <? if (!$SHARE) echo 'class="reply_active"'; else echo 'class="reply"'; ?>>
                    <a href="#" id="replylink" onclick="return Message.OpenReplies(this);">
                      <? $Replies = $MESSAGE->getReplies(); ?>
                      <? if (count($Replies)>100) { ?>
                        <span class="count" id="replies_count">lots</span>
                      <? } else if (count($Replies)>0) { ?>
                        <span class="count" id="replies_count"><?= count($Replies) ?></span>
                      <? } else { ?>
                        <span class="count" id="replies_count" style="display:none"></span>
                      <? } ?>
                      <img src='images/messagetypes_transparent/reply.png'>
                    </a>
                    <input id="totalreplies" type="hidden" value="'.count($Replies).'">
                    <label>Reply</label>
                  </li>
                  <?
                    if
                    (
                      // users can ALWAYS delete their own content
                      LOGGEDIN &&
                      (
                        $User->id==$MESSAGE->sent_by ||
                        (
                          $Group &&
                          (
                            $User->isOwner($Group->id) ||
                            ( $User->isAdmin($Group->id) && !$Sender->isAdmin($Group->id) )
                          )
                        )
                        ||
                        (
                          $User->id==$Profile->id ||
                          in_array($User->id,$MESSAGE->sent_to)
                        )
                      )
                    )
                    {
                  ?>
                    <li>
                      <a href="#" onclick="return Message.DeleteMessage(this,'<?= $MESSAGE->id ?>');"><img src='images/messagetypes_transparent/delete.png'></a>
                      <label>Delete</label>
                    </li>
                  <? } ?>
                </ul>
              </div>

              <!-- Poster Profile Info -->
              <input type="hidden" id="profile_info_open" value=0>
              <div id="profile_info" style="display:none">
                <?= ListUserProfile($User,$Sender,$MESSAGE->id,false) ?>
              </div>

              <!-- Group Info -->
              <? if ($Group) { ?>
                <input type="hidden" id="group_info_open" value=0>
                <div id="group_info" style="display:none">
                  <?= ListGroupProfile($User,$Group,$MESSAGE->id,false) ?>
                </div>
              <? } ?>

              <!-- Message Metadata -->
              <input type="hidden" id="message_info_open" value=0>
              <div id="message_info" style="display:none">
                <div class="message_details">
                  <a href="#" class="close" onclick="return Message.HideMessageInfo(this)"><img src="images/grey_close.png" alt="close" title="Close"></a>
                  <h2>Message Metadata</h2>
                  <ul>
                    <li><label>Message Type:</label><div><?= Options::$message_type[$MESSAGE->type] ?></div></li>
                    <li><label>Message Distribution:</label><div><?= Options::$distribution[$MESSAGE->sharing] ?></div></li>
                    <li><label>Keywords:</label><div><?= implode(' ',$MESSAGE->keywords) ?></div></li>
                    <li><label>Category:</label><div><?= Options::$category[$MESSAGE->category] ?></div></li>
                    <li><label>Location name:</label><div><?= $MESSAGE->location['place_name'] ?></div></li>
                    <li><label>Address:</label><div><?= $MESSAGE->location['address'] ?></div></li>
                    <li><label>Country:</label><div><?= $MESSAGE->location['country'] ?></div></li>
                    <li><label>State/Province/Region:</label><div><?= $MESSAGE->location['region'] ?></div></li>
                    <li><label>City:</label><div><?= $MESSAGE->location['city'] ?></div></li>
                    <li><label>Shares:</label><div><?= count($Shares) ?></div></li>
                    <li><label>Replies:</label><div><?= count($Replies) ?></div></li>
                  </ul>
                </div>
              </div>

              <!-- Replies -->
              <input type="hidden" id="replyopen" value=<?= ($SHARE?'0':'1') ?>>
              <input type="hidden" id="last_count" value="<?= count($Replies) ?>">
              <div class="replies" id="replies" <? if ($SHARE) echo 'style="display:none"'; ?>>
                <a href="#" class="close" id="hide_replies" onclick="return Message.CloseReplies()"><img src="images/grey_close.png" alt="close" title="Close"></a>
                <h2>Reply</h2>

                <div class="reply_list" id="reply_list">
                  <? for ($r=0;$r<count($Replies);$r++) { ?>
                    <?
                      $reply = Post::get($Replies[$r]);
                      $rsender = User::get($reply->sent_by);
                    ?>
                    <div class="replycontainer <? if ($reply->id==$REPLY) echo 'highlight'; ?>" id="replycontainer_<?= $reply->id?>">
                      <?= $rsender->getAvatar() ?>
                      <div id="messagereply_<?= $reply->id ?>">
                        <p><span>
                          <a href="<?= $rsender->getProfileLink() ?>"><?= $rsender->username ?></a> replied:
                        </span></p>
                        <p class="subtext"><?= $reply->messageText() ?></p>
                      </div>

                      <div class="sent">
                        <span>
                          <?
                            $sent = $reply->sent();
                            echo $sent['when'] . " " . $sent['how'];
                          ?>
                        </span>
                        <?
                          if
                          (
                            $reply->sent_by == $User->id ||
                            (
                              $Group &&
                              $User->isMember($Group->id) &&
                              (
                                $MESSAGE->sent_by == $User->id ||
                                $User->isAdmin($Group->id)
                              )
                            )
                            ||
                            (
                              $MESSAGE->sent_by==$User->id
                            )
                          )
                          {
                        ?>
                          <a href="#" class="delete" onclick="return Message.Delete(this,'<?= $reply->id ?>');"><img src="images/delete.png" title="Delete" alt="delete"></a>
                        <? } ?>
                      </div>
                    </div>
                  <? } ?>
                </div>

                <!-- Reply Form -->
                <div class="replyform">
                  <? if (!LOGGEDIN) { ?>
                    <div class='ls_listreplyform'>
                      <a href='user/login'>Login</a> or <a href='signup'>Sign up</a> to reply to this <?= Options::$message_type[$MESSAGE->type] ?>
                    </div>
                  <? } else if (!CONFIRMED) { ?>
                    <div class='ls_listreplyform'>
                      <a href='signup/confirm'>Confirm your account</a> to reply to this <?= Options::$message_type[$MESSAGE->type] ?>
                    </div>
                  <? } else { ?>
                    <?= $User->getAvatar() ?>

                    <div class='replyformcontainer'>
                      <div class='top'>
                        <div class="reply_count" id="reply_count">140</div>
                      </div>
                      <div class='reply_callout'></div>
                      <div class='reply_text_type'>
                        <textarea class="subdued" name="reply_text" id="reply_text" onkeyup="return Message.Count(event,this,'reply_count');" onfocus="Message.ReplyFocus(this)" onblur="Message.ReplyBlur(this)">Send a reply</textarea>
                      </div>
                      <div class="buttons">
                        <button class="short_button" src="images/buttons/sendreply.png" name="send_reply" id="send_reply" onclick="Message.SendReply()">Send Reply</button>
                      </div>
                    </div>
                  <? } ?>
                </div>
              </div>
              <!-- end Replies -->

              <!-- Shares -->
              <input type="hidden" id="sharelistopen" value=<?= ($SHARE?'1':'0') ?>>
              <input type="hidden" id="last_share_count" value=<?= count($Shares) ?>>
              <div class="shares" id="sharelist" <? if (!$SHARE) echo 'style="display:none"'; ?>>
                <a href="#" class="close" id="hide_shares" onclick="return Message.CloseShares()"><img src="images/grey_close.png" alt="close" title="Close"></a>
                <? if ($ISME && count($Shares)==0) { ?>
                  <h2>
                    Share
                    <span id="message_not_shared">No one has shared your <?= Options::$message_type[$MESSAGE->type] ?> yet</span>
                    <span id="message_shared" style="display:none">These members shared your <?= Options::$message_type[$MESSAGE->type] ?></span>
                  </h2>
                <? } else if ($ISME && (count($Shares) > 0)) { ?>
                  <h2>
                    Share
                    <span id="message_not_shared" style="display:none">No one has shared your <?= Options::$message_type[$MESSAGE->type] ?> yet</span>
                    <span id="message_shared">These members shared your <?= Options::$message_type[$MESSAGE->type] ?></span>
                  </h2>
                <? } else if (!$ISME && (count($Shares) > 0)) { ?>
                  <h2>
                    Share
                    <? if (LOGGEDIN) { ?>
                      <span id="message_not_shared" style="display:none">Share this <?= Options::$message_type[$MESSAGE->type] ?> with your Friends and Followers</span>
                      <span id="message_shared">These members shared this <?= Options::$message_type[$MESSAGE->type] ?></span>
                    <? } ?>
                  </h2>
                <? } else { ?>
                  <h2>
                    Share
                    <? if (LOGGEDIN) { ?>
                      <span id="message_not_shared">Share this <?= Options::$message_type[$MESSAGE->type] ?> with your Friends and Followers</span>
                      <span id="message_shared" style="display:none">These members shared this <?= Options::$message_type[$MESSAGE->type] ?></span>
                    <? } ?>
                  </h2>
                <? } ?>

                <div class="share_list" id="share_list">
                  <? for ($r=0;$r<count($Shares);$r++) { ?>
                    <?
                      $share = Post::get($Shares[$r]);
                      $rsender = User::get($share["userid"]);
                    ?>
                    <div class="sharecontainer <? if ($share->id==$SHARE) echo 'highlight'; ?>" id="messagesharecontainer_<?= $share->id?>">
                      <?= $rsender->getAvatar() ?>
                      <div>
                        <p><span>
                          <a href="<?= $rsender->profile_link() ?>"><?= $rsender->username ?></a>
                          <? if ($share->text=='')  echo "without comment"; else echo "with comment:"; ?>
                        </span></p>

                        <? if ($share->text!='') { ?>
                          <p class='subtext'>
                            <?= $share->messageText() ?>
                          </p>
                        <? } ?>
                      </div>
                      <div class="sent">
                        <? $sent = $share->sent(); ?>
                        <span><?= $sent['when'] ?></span> <?= $sent['how'] ?>
                        <? if ($MESSAGE->sent_by==$User->id || $share->sent_by==$User->id) { ?>
                          <a href="#" class="delete" onclick="return Message.Delete(this,'<?= $share->id ?>',true);"><img src="images/delete.png" title="Delete" alt="delete"></a>
                        <? } ?>
                      </div>
                    </div>
                  <? } ?>
                </div>

                <!-- Share Form -->
                <? if (!($ISMINE || $MESSAGE->wasSharedBy($User->id))) { ?>

                  <div class="shareform" id="shareform">
                    <? if (!LOGGEDIN) { ?>
                      <div class='ls_listreplyform'>
                        <a href='user/login'>Login</a> or <a href='signup'>Sign up</a> to share this <?= Options::$message_type[$MESSAGE->type] ?>
                      </div>
                    <? } else if (!CONFIRMED) { ?>
                      <div class='ls_listreplyform'>
                        <a href='signup/confirm'>Confirm your account</a> to share this <?= Options::$message_type[$MESSAGE->type] ?>
                      </div>
                    <? } else { ?>
                      <?= $User->getAvatar() ?>
                      <div class='shareformcontainer'>
                        <div class='top'>
                          <div class='label'>Share this <?= Options::$message_type[$MESSAGE->type] ?></div>
                          <div class="share_count" id="share_count">140</div>
                        </div>
                        <? $sharemessage = "Do you want to say something about this ".Options::$message_type[$MESSAGE->type]." before you share it?"; ?>
                        <div class='share_callout'></div>
                        <div class='share_text_type'><textarea class="subdued" name="share_text" id="share_text" onkeyup="return Message.Count(event,this,'share_count','share');" onfocus="Message.ShareFocus(this)" onblur="Message.ShareBlur(this)"><?= $sharemessage ?></textarea></div>
                        <div class="buttons">
                          <button class="short_button" src="images/buttons/share.png" name="send_share" id="send_share" onclick="Message.SendShare()">Share</button>
                        </div>
                      </div>
                    <? } ?>
                  </div>
                <? } else if ($ISMINE) { ?>
                  <div class="share_footer_notes">
                    Please note you can't share or comment on your own messages
                  </div>
                <? } else { ?>
                  <div class="share_footer_notes">
                    Please note you can only share or comment on a <?= Options::$message_type[$MESSAGE->type] ?> once
                  </div>
                <? } ?>
              </div>
              <!-- end Shares -->

              <div class="message_footer"></div>
            </div>
            <!-- end message_body -->

          </div>
          <!-- end Message -->
        <? } ?>
      </div>
    </td>

    <td id="right_col">
      <!--<img src="filler/google_ads.gif">-->
    </td>
  </tr>
</table>

<input type="hidden" id="last_check" value="<?= time() ?>">

<input type="hidden" id="scrolled_divs" value="<?= $scrolled_divs ?>">

<script type="text/javascript">
  Message.Init('<?= $MESSAGE->id ?>',<?= ($REPLY)?$REPLY:0 ?>,<?= count($Replies) ?>,<?= ($SHARE)?$SHARE:0 ?>,<?= count($Shares) ?>,"<?= Options::$message_type[$MESSAGE->type] ?>");
</script>