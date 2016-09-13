<?
include_once('views/partials/profile.php');

function rendermessage($Post,$filter)
{
  $html = "<div id='message_$Post->id' class='message' onmouseover='Messages.ShowBar(\"$Post->id\")' onmouseout='Messages.HideBar(\"$Post->id\")'>";
  $html .= "<div class='message_header'></div>";

  $html .= message_body($Post,$filter);

  $html .= "<div class='message_footer'></div>";

  $html .= "</div>";

  return $html;
}

function message_body($Post,$filter)
{
  global $User, $Profile;
  $Sender = User::get($Post->sent_by);
  if ($Sender)
  {
    $IsReply = $Post->type==Enum::$message_type['reply'];
    if ($Post->sharing==Enum::$sharing['public_group'] || $Post->sharing==Enum::$sharing['private_group'] || $Post->sharing==Enum::$sharing['admin_group'])
    {
      if ($IsReply)
      {
        $parent = Post::get($post->original_id);
        if ($parent) $Group = Group::get($parent->sent_to[0]);
      }
      else $Group = Group::get($Post->sent_to[0]);
    }
    $html = "<div class='message_body'>";
    $html .= $Sender->getAvatar();
    $html .= message_saidto($Post,$Sender,$User,$Profile,$Group,$IsReply,$filter);
    $html .= message_preview($Post);
    $html .= message_text($Post,$IsReply);
    $html .= message_tabs($Post,$User,$Profile,$Group,$IsReply);
    if (!$IsReply)
    {
      $html .= message_about($Post, $Sender, $User, $Group);
      $html .= message_metadata($Post);
      $html .= message_share($Post, $User);
      $html .= message_reply($Post, $User);
    }
    $html .= "<div class='clear'></div>";
    $html .= "</div>";
  }
  return $html;
}

function message_saidto($Post,$Sender,$User,$Profile,$Group,$IsReply,$filter)
{
  if ($Sender->id===$User->id)
  {
    $fromname = "You";
    $fromposs = "your";
  }
  else
  {
    $fromname = $Sender->username;
    $fromposs = "$Sender->username's";
  }
  if ($filter!='' && preg_match("/^".$filter."/i",$Sender->username))
  {
    $fromname = "<span class='highlight'>$fromname</span>";
    $fromposs = "<span class='highlight'>$fromposs</span>";
  }
  $sentto = $Post->sentTo();

  $html = "<div class='said_to'>";
  if ($Group)
  {
    $groupname = preg_replace("/^".$filter."/i","<span class='highlight'>$0</span>",$Group->groupname);
    if ($IsReply) $html .= "<a href='".$Sender->getProfileLink()."'>$fromname</a> replied in group <a href='".$Group->getProfileLink()."'>$groupname</a>:";
    else $html .= "<a href='".$Sender->getProfileLink()."'>$fromname</a> said in group <a href='".$Group->getProfileLink()."'>$groupname</a>:";
  }
  else
  {
    if ($IsReply)
    {
      $html .= "<a href='".$Sender->getProfileLink()."'>$fromname</a> replied to ";
      if ($sentto===$User->id)
      {
        if ($filter!='' && preg_match("/^".$filter."/i",$User->username)) $html .= "<span class='highlight'>your</span> ";
        else $html .= "your ";
      }
      else
      {
        $Recipient = User::get($sentto);
        if ($Recipient)
        {
          $username = preg_replace("/^".$filter."/i","<span class='highlight'>$0</span>",$Recipient->username);
          $html .= "<a href='".$Recipient->getProfileLink()."'>$username</a>'s ";
        }
      }
      $html .= Options::$message_type[$Post->original_type];
    }
    else
    {
      $html .= "<a href='".$Sender->getProfileLink()."'>$fromname</a> said to ";

      if ($Post->sharing==Enum::$sharing['friends'])
      {
        $html .= "$fromposs friends";
      }
      else if ($sentto=='everyone')
      {
        $html .= "everyone";
      }
      else if ($sentto===$User->id)
      {
        if ($filter!='' && preg_match("/^".$filter."/i",$User->username)) $html .= "<a href='".$User->getProfileLink()."'><span class='highlight'>you</span></a>";
        else $html .= "<a href='".$User->getProfileLink()."'>you</a>";
      }
      else if (!is_array($sentto))
      {
        $Recipient = User::get($sentto);
        if ($Recipient)
        {
          $username = preg_replace("/^".$filter."/i","<span class='highlight'>$0</span>",$Recipient->username);
          $html .= "<a href='".$Recipient->getProfileLink()."'>$username</a>";
        }
      }
      else
      {
        if (in_array($User->id,$Post->sent_to))
        {
          if ($filter!='' && preg_match("/^".$filter."/i",$User->username)) $html .= "<a href='".$Sender->getProfileLink()."'><span class='highlight'>you</span></a> and ";
          else $html .= "<a href='".$Sender->getProfileLink()."'>you</a> and ";
          $html .= "<a href='#' onclick='return Messages.ShowRecipients(\"$Post->id\");' id='showrecipients_$Post->id'>Others</a>";
          $html .= "<span class='recipientname' id='showingrecipients_$Post->id' style='display:none'>Others</span>";
        }
        else if (in_array($Profile->id,$Post->sent_to))
        {
          $username = preg_replace("/^".$filter."/i","<span class='highlight'>$0</span>",$Profile->username);
          $html .= "<a href='".$Profile->getProfileLink()."'>$username</a> and ";
          $html .= "<a href='#' onclick='return Messages.ShowRecipients(\"$Post->id\");' id='showrecipients_$Post->id'>Others</a>";
          $html .= "<span class='recipientname' id='showingrecipients_$Post->id' style='display:none'>Others</span>";
        }
        else
        {
          $html .= "<a href='#' onclick='return Messages.ShowRecipients(\"$Post->id\");' id='showrecipients_$Post->id'>Multiple People</a>";
          $html .= "<span class='recipientname' id='showingrecipients_$Post->id' style='display:none'>Multiple People</span>";
        }
        $comma = false;
        $multiple = true;
        foreach ($Post->sent_to as $rec)
        {
          if ($rec!=$Profile->id)
          {
            $recipient = User::get($rec);
            if ($recipient)
            {
              $username = preg_replace("/^".$filter."/i","<span class='highlight'>$0</span>",$recipient->username);
              if($comma) $to_list .= ", ";
              $to_list .= "<a href='".$recipient->getProfileLink()."'>$username</a>";
              $comma = true;
            }
          }
        }
      }
    }

    if ($Post->sharing==Enum::$sharing['public']) $html .= " publicly:";
    else $html .= " privately:";
  }

  if ($multiple)
  {
    // multiple recipient list:
    $html .= "<div class='recipient_list' id='recipients_$Post->id' ";
    if (!$shownames) $html .= "style='display:none'";
    $html .= ">";
    $html .= "<a href='#' onclick='return Messages.HideRecipients(\"$Post->id\");' class='close'><img src='images/close2.gif' alt='X'></a>";
    $html .= $to_list;
    $html .= "</div>";
  }

  $html .= "</div>";

  return $html;
}

function message_preview($Post)
{
  $html = "";
  if ($Post->link)
  {
    switch($Post->link['type'])
    {
      case Enum::$link_type['url']:
        $html .= "<div class='preview'>";
        $html .= "<a href='".$Post->link['uri']."' target='_blank'><img src='".THUMB.$Post->link['uri']."' height=70></a>";
        $html .= "</div>";
        break;
      case Enum::$link_type['image']:
        $resize = Image::sizeImage($Post->link['height'],$Post->link['width'],MESSAGELIST_PHOTO_MAX_HEIGHT,MESSAGELIST_PHOTO_MAX_WIDTH);
        $html .= "<div class='preview'><a href='messages/view/$Post->id'><img src='".$Post->link['uri']."' height=".$resize['ht']." width=".$resize['wd']."></a></div>";
        break;
      case Enum::$link_type['embed']:
        $woffset = strpos($Post->link['uri'],'width')+7;
        $wd = substr($Post->link['uri'],$woffset,strpos($Post->link['uri'],"'",$woffset)-$woffset);
        $w = $wd;
        $hoffset = strpos($Post->link['uri'],'height')+8;
        $ht = substr($Post->link['uri'],$hoffset,strpos($Post->link['uri'],"'",$hoffset)-$hoffset);
        $h = $ht;
        $adj = 1;
        if ($wd > MESSAGELIST_VIDEO_MAX_WIDTH)
        {
          $adj = MESSAGELIST_VIDEO_MAX_WIDTH / $wd;
        }
        if ($ht * $adj > MESSAGELIST_VIDEO_MAX_HEIGHT)
        {
          $adj = MESSAGELIST_VIDEO_MAX_HEIGHT / $ht;
        }
        $ht = floor($ht*$adj);
        $wd = floor($wd*$adj);
        $linktext = str_replace("width='$w'","width='$wd'",$Post->link['uri']);
        $linktext = str_replace("height='$h'","height='$ht'",$linktext);
        $html .= "<div class='preview' id='message_embed_$Post->id'><a href='messages/view/$Post->id'>$linktext</a></div>";
        $html .= "<div id='message_embed_placeholder_$Post->id' style='height:".$ht."px;display:none'></div>";
        break;
    }
  }
  return $html;
}

function message_text($Post,$IsReply)
{
  $html = "<div class='messagetext'>";
  if ($Post->type!=Enum::$message_type['reply'])
  {
    if ($Post->type==Enum::$message_type['notification'])
    {
      $html .= "<a href='".$Post->oppLink()."'>".Options::$notification_type[$Post->notification_type]."</a>: ";
    }
    else
    {
      $html .= "<a href='".$Post->oppLink()."'>".Options::$message_type[$Post->type]."</a>: ";
    }
  }
  $html .= "<span class='messagetext' id='messagetext_$Post->id'>".$Post->messageText()."</span>";
  $html .= "</div>";
  $html .= "<div class='clear'></div>";

  return $html;
}

function message_tabs($Post,$User,$Profile,$Group,$IsReply)
{
  $html = "<div id='message_footer_$Post->id' class='links'>";
  $html .= "<span class='sent' id='sent_$Post->id'>";
  $sent = $Post->sent();
  $html .= "<a href='".$Post->oppLink()."'>Sent <span>".$sent['when']."</span> ".$sent['how'];
  $html .= "</a>";
  $html .= "</span>";

  if ($IsReply)
  {
    $html .= "<a class='view_thread' href='".$Post->oppLink()."'>View in thread</a>";
  }
  else
  {
    $html .= message_tablinks($Post,$User,$Profile,$Group);
  }

  $html .= "</div>";
  return $html;

}

function message_tablinks($Post,$User,$Profile,$Group)
{
  $html = "<ul id='message_bar_$Post->id'>";

  // MEMBER INFO
  if ($Post->type<Enum::$message_type['share'])
  {
    $html .= "<li><a href='#' id='profile_info_link_$Post->id' onclick='return Messages.ShowMemberInfo(this,\"$Post->id\");'>";
    $html .= "<img src='images/messagetypes_transparent/contactinfo.png'></a>";
    $html .= "<a href='#' id='profile_info_close_link_$Post->id' style='display:none' onclick='return Messages.HideMemberInfo(this,\"$Post->id\");'>";
    $html .= "<img src='images/messagetypes_transparent/contactinfo.png'></a>";
    $html .= "<label>About</label></li>";
  }

  // MESSAGE INFO
  if ($Post->type<Enum::$message_type['share'])
  {
    $html .= "<li><a href='#' id='message_info_link_$Post->id' onclick='return Messages.ShowMessageInfo(this,\"$Post->id\");'>";
    $html .= "<img src='images/messagetypes_transparent/objectinfo.png'></a>";
    $html .= "<a href='#' id='message_info_close_link_$Post->id' style='display:none' onclick='return Messages.HideMessageInfo(this,\"$Post->id\");'>";
    $html .= "<img src='images/messagetypes_transparent/objectinfo.png'></a>";
    $html .= "<label>Metadata</label></li>";
  }

  // SHARE
  if ($Post->sent_by != $User->id && $Post->type<Enum::$message_type['share'])
  {
    $html .= "<li class='share'><a href='#' id='sharelink_$Post->id' onclick='return Messages.OpenShares(this,\"$Post->id\");'>";
    if (count($Post->shares)>0)
    {
      $html .= "<span class='count' id='shares_count_$Post->id'>".count($Post->shares)."</span>";
    }
    else
    {
      $html .= "<span class='count' id='shares_count_$Post->id' style='display:none'></span>";
    }
    $html .= "<img src='images/messagetypes_transparent/share.png'></a>";
    $html .= "<input id='totalshares_$Post->id' type='hidden' value='".count($Post->shares)."'>";
    $html .= "<label>Share</label></li>";
  }

  // REPLY
  if ($Post->type<Enum::$message_type['share'])
  {
    $html .= "<li class='reply'><a href='#' id='replylink_$Post->id' onclick='return Messages.OpenReplies(this,\"$Post->id\");'>";
    if (count($Post->replies)>100)
    {
      $html .= "<span class='count' id='replies_count_$Post->id'>lots</span>";
    }
    else if (count($Post->replies)>0)
    {
      $html .= "<span class='count' id='replies_count_$Post->id'>".count($Post->replies)."</span>";
    }
    else
    {
      $html .= "<span class='count' id='replies_count_$Post->id' style='display:none'></span>";
    }
    $html .= "<img src='images/messagetypes_transparent/reply.png'></a>";
    $html .= "<input id='totalreplies_$Post->id' type='hidden' value='".count($Post->replies)."'>";
    $html .= "<label>Reply</label></li>";
  }

  // DELETE
  if
  (
    LOGGEDIN &&
    (
      $Post->sent_by===$User->id ||
      $User==$Profile ||
      ( $Group && $User->isAdminOf($Group->id) )
    )
  )
  {
    $html .= "<li><a href='#' onclick='return Messages.Delete(\"$Post->id\",".count($Post->replies).");'><img src='images/messagetypes_transparent/delete.png'></a>";
    $html .= "<label>Delete</label></li>";
  }

  $html .= "</ul>";
  return $html;

}

function message_about($Post, $Sender, $User)
{
  $html = "<input type='hidden' id='profile_info_open_$Post->id' value=0>";
  $html .= "<div id='profile_info_$Post->id' style='display:none'>";
  $html .= ListUserProfile($User,$Sender,$Post->id,true);
  $html .= "</div>";
  return $html;
}

function message_metadata($Post)
{
  $html = "<input type='hidden' id='message_info_open_$Post->id' value=0>";
  $html .= "<div id='message_info_$Post->id' class='message_details' style='display:none'>";

  $html .= "<a href='#' class='close' onclick='return Messages.HideMessageInfo(this,\"$Post->id\")'><img src='images/grey_close.png' alt='close' title='Close'></a>";

  $html .= "<h2>Message Metadata</h2>";

  $html .= "<ul>";
  $html .= "<li><label>Message Type</label><div>" . Options::$message_type[$Post->type] . "</div></li>";
  if ($Post->type==Enum::$message_type['link'])
  {
    $html .= "<li><label>URL</label> <div><a href='" . $Post->link['uri'] . "' target='_blank'>" . $Post->link['uri'] . "</a></div></li>";
  }
  $html .= "<input type='hidden' id='wrapper_$Post->id' value='$Post->id'>";
  $html .= "<li><label>Message Distribution</label><div>" . Options::$sharing[$Post->sharing] . "</div></li>";
  $html .= "<li><label>Keywords</label><div>".implode(' ',$Post->keywords)."</div></li>";
  $html .= "<li><label>Category</label><div>" . Options::$category[$Post->category] . "</div></li>";
  $html .= "<li><label>Shares</label><div><span id='info_shares_$Post->id'>" . count($Post->shares) . "</span></div></li>";
  $html .= "<li><label>Replies</label><div><span id='info_replies_$Post->id'>" . count($Post->replies) . "</span></div></li>";
  $html .= "<li><label>Location Name</label><div>" . $Post->location['place_name'] . "</div></li>";
  $html .= "<li><label>Address</label><div>" . $Post->location['address'] . "</div></li>";
  $html .= "<li><label>Country</label><div>" . $Post->location['country'] . "</div></li>";
  $html .= "<li><label>State/Province/Region</label><div>" . $Post->location['region'] . "</div></li>";
  $html .= "<li><label>City</label><div>" . $Post->location['city'] . "</div></li>";
  $loc = trim($Post->location['address'].' '.$Post->location['city'].' '.$Post->location['region'].' '.$Post->location['country']);
  $html .= "</ul>";
  if ($loc != "")
  {
    $html .= "<a href='http://maps.google.com/maps?q=$loc&z=14' target='_blank'>";
    $html .= "<img class='map' src='http://maps.google.com/maps/api/staticmap?markers=".urlencode($loc)."&zoom=14&size=460x200&maptype=roadmap&sensor=false&key=".GMAP_KEY."'>";
    $html .= "</a>";
  }
  $html .= "</div>";
  return $html;
}

function message_share($Post, $User)
{
  $messageType = Options::$message_type[$Post->type];
  $html = "<input type='hidden' id='sharelistopen_$Post->id' value=0>";
  $html .= "<input type='hidden' id='last_share_count_$Post->id' value=".count($Post->shares).">";
  $html .= "<input type='hidden' id='viewingmore_shares_$Post->id' value=0>";
  $html .= "<input type='hidden' id='all_shares_$Post->id' value='".implode(',',$Post->shares)."'>";

  $html .= "<div class='shares' id='sharelist_$Post->id' style='display:none'>";

  $html .= "<a href='#' class='close' name='hide_shares_$Post->id' id='hide_shares_$Post->id' onclick='return Messages.CloseShares(\"$Post->id\")'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  $html .= "<h2>Share ";
  if (LOGGEDIN)
  {
    if ($Post->sent_by==$User->id)
    {
      if (count($Post->shares) > 0)
      {
        $html .= "<span class='title-desc' id='message_shared_$Post->id'>These members shared your $messageType</span>";
        $html .= "<span class='title-desc' id='message_not_shared_$Post->id' style='display:none'>No one has shared your $messageType yet</span>";
      }
      else
      {
        $html .= "<span class='title-desc' id='message_shared_$Post->id' style='display:none'>These members shared your $messageType</span>";
        $html .= "<span class='title-desc' id='message_not_shared_$Post->id'>No one has shared your $messageType yet</span>";
      }
    }
    else
    {
      if ($Post->wasSharedBy($User->id))
      {
        $html .= "<span class='title-desc' id='message_not_shared_$Post->id' style='display:none'>Share this $messageType with your Friends and Followers</span>";
        $html .= "<span class='title-desc' id='message_shared_$Post->id'>These members shared this $messageType</span>";
      }
      else
      {
        $html .= "<span class='title-desc' id='message_not_shared_$Post->id'>Share this $messageType with your Friends and Followers</span>";
        $html .= "<span class='title-desc' id='message_shared_$Post->id' style='display:none'>These members shared this $messageType</span>";
      }
    }
  }
  $html .= "</h2>";
  $html .= message_sharelist($Post);
  $html .= message_shareform($Post, $User);
  $html .= "<div class='clear'></div>";
  $html .= "</div>";
  return $html;
}

function message_sharelist($Post)
{
  $html = "";
  $total_shares = count($Post->shares);
  if ($total_shares > MAX_INLINE_REPLIES)
  {
    $html .= "<div class='more_shares' id='more_shares_$Post->id'>";
    $html .= "<a href='messages/view/$Post->id?share=open'>";
    $html .= "View all <span id='share_getmore_$Post->id'>$total_shares</span> shares";
    $html .= "</a></div>";
  }
  else
  {
    $html .= "<div class='more_shares' id='more_shares_$Post->id' style='display:none'>";
    $html .= "<a href='messages/view/$Post->id?share=open'>";
    $html .= "View all <span id='share_getmore_$Post->id'>$total_shares</span> shares";
    $html .= "</a></div>";
  }

  if ($total_shares==0)
  {
    $html .= "<div class='share_list' id='share_list_$Post->id' style='display:none'>";
  }
  else
  {
    $html .= "<div class='share_list' id='share_list_$Post->id'>";
  }

  $num_shares = ($total_shares>MAX_INLINE_SHARES) ? MAX_INLINE_SHARES : $total_shares;
  $s = $total_shares - $num_shares;
  $f = $total_shares;
  for ($r=$s;$r<$f;$r++)
  {
    $Share = Post::get($Post->shares[$r]);
    $Sharer = User::get($Share->sent_by);
    $html .= "<div class='sharecontainer' id='messagesharecontainer_$Share->id'>";
    $html .= $Sharer->getAvatar();
    $html .= "<div>";

    // message content
    $html .= "<p><span><a href='members/profile/$Sharer->id'>".$Sharer->username."</a> <a href='messages/view/".$Share->link['uri']."?share=$Share->id'>shared</a>";

    if ($Share->text=="") $html .= " without ";
    else $html .= " with ";
    $html .= "<a href='messages/view/".$Share->link['uri']."?share=$Share->id'>comment</a>:";
    $html .= "</span></p>";

    if ($Share->text!="")
    {
      $html .= "<p class='subtext'>".$Share->messageText()."</p>";
    }

    $html .= "</div><div class='sent'><span>";
    $sent = $Share->sent();
    $html .= $sent['when'] . " " . $sent['how'];
    $html .= "</span>";
    $html .= "<a href='#' class='delete' onclick='return Messages.Delete(\"$Share->id\",-1,false,\"share\");'><img src='images/delete.png' title='Delete' alt='delete'></a>";
    $html .= "</div></div>";
  }
  $html .= "</div>";
  return $html;
}

function message_shareform($Post, $User)
{
  $messageType = Options::$message_type[$Post->type];
  $html = "";
  if ($Post->sent_by==$User->id)
  {
    $html .= "<div class='share_footer_notes'>Please note you can't share or comment on your own messages.</div>";
  }
  else if ($Post->wasSharedBy($User->id))
  {
    $html .= "<div class='share_footer_notes'>Please note you can only share or comment on a $messageType once.</div>";
  }
  else
  {
    if (!LOGGEDIN)
    {
      $html .= "<div class='shareform' id='share_form_$Post->id' style='border-top:1px solid #999'>";
      $html .= "<div class='ls_listreplyform'>";
      $html .= "<a href='user/login'>Login</a> or <a href='signup'>Sign up</a> to share this $messageType";
      $html .= "</div></div>";
    }
    else if (!CONFIRMED)
    {
      $html .= "<div class='shareform' id='share_form_$Post->id' style='border-top:1px solid #999'>";
      $html .= "<div class='ls_listreplyform'>";
      $html .= "<a href='signup/confirm'>Confirm your account</a> to share this $messageType";
      $html .= "</div></div>";
    }
    else
    {
      $html .= "<div class='share_footer_notes' id='share_footer_$Post->id' style='display:none'>Please note you can only share or comment on a $messageType once.</div>";
      if (count($Post->shares)==0) $html .= "<div class='shareform' id='share_form_$Post->id'>";
      else $html .= "<div class='shareform' id='share_form_$Post->id' style='border-top:1px solid #999'>";

      $html .= "<div class='top'>";
      // content
      $html .= "<div class='share_count' id='share_count_$Post->id'>140</div>";
      $html .= "</div>";
      $html .= "<div class='shareformcontainer'>";
      $html .= $User->getAvatar();
      $html .= "<div class='share_callout'></div>";
      $html .= "<div class='share_text_type'><textarea class='subdued' id='share_text_$Post->id' onkeyup='return Messages.ShareCount(event,this,\"\",\"$Post->id\");' onfocus='Messages.ShareFocus(this)' onblur='Messages.ShareBlur(this, \"".$messageType."\")'>Do you want to say something about this $messageType before you share it?</textarea></div>";
      $html .= "<input type='hidden' id='share_original_text_$Post->id' value='Do you want to say something about this $messageType before you share it?'>";
      $html .= "<div class='buttons'>";
      $html .= "<button class='short_button right' id='send_share_$Post->id' onclick='Messages.SendShare(\"$Post->id\",\"\")'>Share</button>";
      $html .= "</div>";
      $html .= "</div>";
      $html .= "</div>";
    }
  }
  return $html;
}

function message_reply($Post, $User)
{
  $html = "<input type='hidden' id='replyopen_$Post->id' value=0>";
  $html .= "<input type='hidden' id='last_count_$Post->id' value=".count($Post->replies).">";
  $html .= "<input type='hidden' id='viewingmore_$Post->id' value=0>";
  $html .= "<input type='hidden' id='all_replies_$Post->id' value='".implode(',',$Post->replies)."'>";

  $html .= "<div class='replies' id='replies_$Post->id' style='display:none;'>";
  $html .= "<a href='#' class='close' id='hide_replies_$Post->id' onclick='return Messages.CloseReplies(\"$Post->id\")'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  $html .= "<h2>Reply</h2>";
  $html .= message_replylist($Post);
  $html .= message_replyform($Post, $User);

  $html .= "<div class='clear'></div>";
  $html .= "</div>";
  return $html;
}

function message_replylist($Post)
{
  $html = "";
  $total_replies = count($Post->replies);
  $num_replies = ($total_replies>MAX_INLINE_REPLIES) ? MAX_INLINE_REPLIES : $total_replies;
  if ($total_replies > MAX_INLINE_REPLIES)
  {
    $html .= "<div class='more_replies' id='more_replies_$Post->id'>";
    $html .= "<a href='messages/view/$Post->id'>";
    $html .= "View all <span id='reply_getmore_$Post->id'>$total_replies</span> replies";
    $html .= "</a></div>";
  }
  else
  {
    $html .= "<div class='more_replies' id='more_replies_$Post->id' style='display:none'>";
    $html .= "<a href='messages/view/$Post->id'>";
    $html .= "View all <span id='reply_getmore_$Post->id'>$total_replies</span> replies";
    $html .= "</a></div>";
  }

  $html .= "<input type='hidden' id='newreplies_$Post->id' value=0>";
  $html .= "<div class='more_replies' id='new_replies_$Post->id' style='display:none;'>";
  $html .= "<a href='messages/view/$Post->id' id='newreplies1_$Post->id' style='display:none'>1 new reply</a>";
  $html .= "<a href='messages/view/$Post->id' id='newrepliesx_$Post->id' style='display:none'><span id='newreplycount_$Post->id'></span> new replies</a>";
  $html .= "</div>";

  if (count($Post->replies)==0)
  {
    $html .= "<div class='reply_list' id='reply_list_$Post->id' style='display:none;'>";
  }
  else
  {
    $html .= "<div class='reply_list' id='reply_list_$Post->id'>";
  }

  $s = $total_replies - $num_replies;
  $f = $total_replies;
  for ($r=$s;$r<$f;$r++)
  {
    $Reply = Post::get($Post->replies[$r]);
    $Rsender = User::get($Reply->sent_by);

    $html .= "<div class='replycontainer' id='messagereplycontainer_$Reply->id'>";
    $html .= $Rsender->getAvatar();
    $html .= "<div>";

    // message content
    $html .= "<p><a href='".$Rsender->getProfileLink()."'>".$Rsender->username."</a> replied:</p>";

    $html .= "<p class='subtext'>".$Reply->messageText()."</p>";
    $html .= "</div><div class='sent'><span>";
    $sent = $Reply->sent();
    $html .= $sent['when'] . " " . $sent['how'];
    $html .= "</span>";

    $html .= "<a href='#' class='delete' onclick='return Messages.Delete(\"$Reply->id\",-1);'><img src='images/delete.png' title='Delete' alt='delete'></a>";
    $html .= "</div></div>";
  }
  $html .= "</div>";
  return $html;
}

function message_replyform($Post, $User)
{
  $html = "<div class='replyform'>";
  $messageType = Options::$message_type[$Post->type];
  if (!LOGGEDIN)
  {
    $html .= "<div class='ls_listreplyform'>";
    $html .= "<a href='user/login'>Login</a> or <a href='signup'>Sign up</a> to reply to this $messageType";
    $html .= "</div>";
  }
  else if (!CONFIRMED)
  {
    $html .= "<div class='ls_listreplyform'>";
    $html .= "<a href='signup/confirm'>Confirm your account</a> to reply to this $messageType";
    $html .= "</div>";
  }
  else
  {
    $html .= $User->getAvatar();

    // content
    $html .= "<div class='replyformcontainer'>";
    $html .= "<div class='top'>";
    $html .= "<div class='label'>Reply to this $messageType</div>";
    $html .= "<div class='reply_count' id='reply_count_$Post->id'>140</div>";
    $html .= "</div>";
    $html .= "<div class='reply_callout'></div>";
    $html .= "<div class='reply_text_type'><textarea class='subdued' id='reply_text_$Post->id' onkeyup='return Messages.Count(event,this,\"reply_count_\",\"$Post->id\",\"$Post->id\");' onfocus='Messages.ReplyFocus(this)' onblur='Messages.ReplyBlur(this)'>Send a reply</textarea></div>";
    $html .= "<div class='buttons'>";
    $html .= "<button class='short_button right' id='send_reply_$Post->id' onclick='Messages.SendReply(\"$Post->id\",\"$Post->id\")'>Send Reply</button>";
    $html .= "</div>";
    $html .= "</div>";
  }
  $html .= "</div>";
  return $html;
}

?>