<?php

class Notify
{
  static function PostMessage($from,$to,$text,$type,$sharing,$source,$system,$link)
  {
    /*$post = array (
                    'sent_to'=>$to,
                    'parent_id'=>0,
                    'text'=>$text,
                    'type'=>$type,
                    'sharing'=>$sharing,
                    'source'=>$source,
                    'system'=>$system,
                    'link'=>$link
                  );
    return Post::save($from,$post);*/
    return true;
  }

  static function PostNotification($to,$text)
  {
    $Post = Post::create();
    $Post->sent_to = array($to);
    $Post->sent_by = 'a';
    $Post->type = Enum::$message_type['notification'];
    $Post->sharing = Enum::$sharing['private'];
    $Post->source = Enum::$source['system'];
    $Post->text = $text;
    $Post->save();
  }

  static function PostAdminNotification($group,$text)
  {
    $Post = Post::create();
    $Post->sent_to = array($group);
    $Post->sent_by = 'a';
    $Post->type = Enum::$message_type['admin'];
    $Post->sharing = Enum::$sharing['admin'];
    $Post->source = Enum::$source['system'];
    $Post->text = $text;
    $Post->save();
  }

/***************************** MISC NOTIFICATIONS *****************************/

  static function WelcomeToMiio($User)
  {
    $to = $User->email;
    $headers = "From: Miio <miio@".SMS_EMAIL_HOST.">\n";
    $subject = 'Welcome to Miio!';

    $msg = "Congratulations and Welcome to Miio!\n\n";
    $msg .= "Please confirm your account by entering the following code on the confirmation page:\n";
    $msg .= "$User->confirmation_code\n(You can copy & paste the code if you want)\n\n";
    $msg .= "Best,\nTeam Miio";
    $msg .= "\n\nDid this email go to your junk/bulk folder? Add miio@miio.com to your address book to ensure that our emails are delivered to your inbox.";
    $msg .= "\n\nYou can also make it easy for your friends to follow you! Just cut and paste this into your email signature: Follow me on Miio. http://miio.com/".$_POST['username'];

    mail($to, $subject, $msg, $headers);
  }

  static function PasswordReset($User,$code)
  {
    global $LOC;
    $message = "You have asked to reset the password for your Miio account '$User->username'. To reset your password, click on the link below, or copy & paste it into your browser. From there, you will be able to change your password.\n\n";
    $message .= "Reset link: ".$LOC."user/password_reset/$code\n\n";
    $message .= "Or you can go to $LOC"."user/password_reset and enter the code: $code\n\n";
    $message .= "NOTE: This link is only valid for 24 hours.";
    $subject = "Miio Password Reset";
    $headers = "From: miio@".SMS_EMAIL_HOST."\n";
    mail($User->email, $subject, $message, $headers);
  }

/********************** FOLLOW  REQUESTS & NOTIFICATIONS **********************/

  static function NewFollower($Follower,$Following)
  {
    global $LOC, $LOCAL;
    $post = "<a href='members/profile/$Follower->id'>$Follower->username</a> is now following you.";
    Notify::PostMessage(1,$Following->id,$post,'newfollower','private','web',1);
  }

/***************************** GROUP  INVITATIONS *****************************/

  static function GroupInvitation($Group,$Inviter,$dist,$list)
  {
    global $LOC, $LOCAL;

    $link = $Group->id;
    $source = 'web';
    $system = true;
    if ($Group->visibility=='public')
    {
      $type = 'publicgroupinvitation';
    }
    else
    {
      $type = 'privategroupinvitation';
    }

    switch($dist)
    {
      case 'all' :
        $text = "I would like to invite everyone to join the <a style='font-weight:bold;color:#666' href='$LOC"."groups/view/$Group->id'>$Group->groupname</a> group.";
        $sharing = 'public';
        $sent_to = 0;
        break;
      case 'friends' :
        $text = "I would like to invite you to join the <a style='font-weight:bold;color:#666' href='$LOC"."groups/view/$Group->id'>$Group->groupname</a> group.";
        $sharing = 'friends';
        $sent_to = 0;
        break;
      case 'list' :
        $text = "I would like to invite you to join the <a style='font-weight:bold;color:#666' href='$LOC"."groups/view/$Group->id'>$Group->groupname</a> group.";
        $sharing = 'private';
        $sent_to = $list;
        break;
      default: return false;
    }
    if ($Group->visibility=='private') $text .= " (A Private group)";
    return Notify::PostMessage($Inviter->id,$sent_to,$text,$type,$sharing,$source,$system,$link);
  }

  static function AdminInvite($memberid,$Group)
  {
    $Member = User::get($memberid);
    if (!$Member) return;
    $text = "You have been invited to become an Administrator for the <a style='font-weight:bold;color:#666' href='".$Group->getProfileLink()."'>$Group->groupname</a> group. <a style='font-weight:bold;color:#666' href='#' onclick='return Messages.AcceptAdminInvitation(this,\"$Group->id\")'>Accept</a> or <a style='font-weight:bold;color:#666' href='#' onclick='return Messages.DeclineAdminInvitation(this,\"$Group->id\")'>Decline</a> this invitation.";
    Notify::PostNotification($Member->id,$text);

    $text = "<a style='font-weight:bold;color:#666' href='".$Member->getProfileLink()."'>$Member->username</a> has been invited to be an Administrator for the <a style='font-weight:bold;color:#666' href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostAdminNotification($Group->id,$text);
  }

  static function OwnerInvite($memberid,$Group)
  {
    $Member = User::get($memberid);
    if (!$Member) return;
    $text = "You have been invited to take over Ownership of the <a style='font-weight:bold;color:#666' href='".$Group->getProfileLink()."'>$Group->groupname</a> group. <a style='font-weight:bold;color:#666' href='#' onclick='return Messages.AcceptOwnerInvitation(this,\"$Group->id\")'>Accept</a> or <a style='font-weight:bold;color:#666' href='#' onclick='return Messages.DeclineOwnerInvitation(this,\"$Group->id\")'>Decline</a> this invitation.";
    Notify::PostNotification($memberid,$text);
  }

/******************************************************************************/
/******************************************************************************/
/******************************************************************************/

  static function SendPopup($User,$message)
  {
    //global $Cache;
    //$User->popup_messages[] = $message;
    //$Cache->replace('User_'.$User->id,$User);
  }

  static function SMSConfirmation($User)
  {
    global $LOCAL;
    if (!$LOCAL)
    {
      if ($User->notification_sms && $User->sms_provider)
      {
        $phone = $User->getSMSEmail();
        $msg = "Miio mobile phone confirmation: To confirm your phone and receive text messages from Miio, reply OK.";
        $headers = "From: sms_confirm@".SMS_EMAIL_HOST."\n";
        $headers .= "Priority: normal";
        $subject = '';
        mail($phone, $subject, $msg, $headers, "-f sms_confirm@".SMS_EMAIL_HOST);
      }
    }
  }

/***************************** GROUP  INVITATIONS *****************************/

  static function NewGroupInvitation($Group,$invited_id,$Inviter)
  {
    // NOT DONE
    global $LOC, $LOCAL;
    $Invited = User::get($invited_id);
    if (!$Invited || $Invited->is_group) return;
    if ($Group->visibility=='public')
    {
      $text = "I've just created a new group called <a href='groups/view/$Group->id'>$Group->groupname</a>, and I would like to invite you to become a member. <span style='%SENDERSTYLE%'><a href='#' onclick='return Messages.AcceptGroupInvitation(this,$Group->id)'>Accept</a> or <a href='#' onclick='return Messages.DeclineGroupInvitation(this,$Group->id)'>Decline</a> this invitation.</span>";
      if ($Invited->email_public_invite)
      {
        $email_invite = true;
        $email_text = "I've just created a new group on Miio called '$Group->groupname', and I would like to invite you to become a member. You can join <a href='".$LOC."groups/view/$Group->id'>here</a>";
      }
      if ($Invited->sms_public_invite)
      {
        $sms_invite = true;
        $sms_text = "I've just created a new group on Miio called '$Group->groupname', and I would like to invite you to become a member. Reply YES to join $Group->groupname, or NO to decline membership.";
      }
      if ($Invited->popup_public_invite)
      {
        $popup_invite = true;
        $popup_text = "<a href='groups/view/$Group->id'>You're invited to join the $Group->groupname group</a>";
      }
    }
    else
    {
      $text = "I've just created a new private group called <a href='groups/view/$Group->id'>$Group->groupname</a>, and I would like to invite you to become a member. <span style='%SENDERSTYLE%'><a href='#' onclick='return Messages.AcceptGroupInvitation(this,$Group->id)'>Accept</a> or <a href='#' onclick='return Messages.DeclineGroupInvitation(this,$Group->id)'>Decline</a> this invitation.</span>";

      if ($Invited->email_private_invite)
      {
        $email_invite = true;
        $email_text = "I've just created a new private group on Miio called '$Group->groupname', and I would like to invite you to become a member. You can accept this invitation <a href='".$LOC."groups/view/$Group->id'>here</a>";
      }
      if ($Invited->sms_private_invite)
      {
        $sms_invite = true;
        $sms_text = "I've just created a new private group on Miio called '$Group->groupname', and I would like to invite you to become a member. Reply YES to join $Group->groupname, or NO to decline membership.";
      }
      if ($Invited->popup_private_invite)
      {
        $popup_invite = true;
        $popup_text = "<a href='groups/view/$Group->id'>You're invited to join the $Group->groupname group</a>";
      }
    }
    Notify::PostMessage($Inviter->id,$Invited->id,$text,'invitation','private','web',1);
    if ($popup_invite) Notify::SendPopup($Invited,$popup_text);
    if ($LOCAL) return;
    if ($email_invite)
    {
      $realname = trim($Inviter->first_name . " " . $Inviter->last_name);
      if ($Inviter->show_name && $realname != "") $name = $realname;
      else $name = $Inviter->username;
      $headers = "From: $name <$Inviter->email>\n";
      $headers .= "Content-type: text/html\n";
      mail($Invited->email, 'Email Notification from Miio', $email_text, $headers);
    }
    if ($sms_invite)
    {
      $phone = $Invited->getSMSEmail();
      $from = "group_invite_$Group->id@".SMS_EMAIL_HOST;
      $headers = "From: $from\n";
      $headers .= "Priority: normal";
      $subject = '';
      mail($phone, $subject, $text, $headers, "-f $from");
    }
  }


  static function AdminDeclined($Member,$Group)
  {
    $post = "<a href='".$Member->getProfileLink()."'>$Member->username</a> has declined the invitation to become an Administrator for the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostAdminNotification($Group->id,$post);

    $post = "You have declined an invitation to be an Administrator for the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostNotification($Member->id,$post);
  }

  static function AdminCanceled($memberid,$Group)
  {
    $Member = User::get($memberid);
    if (!$Member) return;
    $post = "The invitation to <a href='".$Member->getProfileLink()."'>$Member->username</a> to become an Administrator for the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group has been canceled.";
    Notify::PostAdminNotification($Group->id,$post);

    $post = "The invitation to be an Administrator for the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group has been canceled.";
    Notify::PostNotification($Member->id,$post);
  }

  static function AdminAccepted($Member,$Group)
  {
    $post = "<a href='".$Member->getProfileLink()."'>$Member->username</a> has accepted the invitation to become an Administrator for the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostAdminNotification($Group->id,$post);

    $post = "You are now an Administrator for the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostNotification($Member->id,$post);
  }

  static function AdminRemoved($memberid,$Group)
  {
    $Member = User::get($memberid);
    if (!$Member) return;
    $post = "<a href='".$Member->getProfileLink()."'>$Member->username</a> has been removed as an Administrator for the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostAdminNotification($Group->id,$post);

    $post = "You have been removed as an Administrator for the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostNotification($Member->id,$post);
  }



  static function OwnerDeclined($Member,$Group)
  {
    $post = "<a href='".$Member->getProfileLink()."'>$Member->username</a> has declined the invitation to become Owner of the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostNotification($Group->owner,$post);

    $post = "You have declined an invitation to become Owner of the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostNotification($Member->id,$post);
  }

  static function OwnerCanceled($Member,$Group)
  {
    $post = "The invitation to <a href='".$Member->getProfileLink()."'>$Member->username</a> to become Owner of the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group has been canceled.";
    Notify::PostNotification($Group->owner,$post);

    $post = "The invitation to become Owner of the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group has been canceled.";
    Notify::PostNotification($Member->id,$post);
  }

  static function OwnerAccepted($memberid,$Group)
  {
    $Member = User::get($memberid);
    if (!$Member) return;
    $post = "<a href='".$Member->getProfileLink()."'>$Member->username</a> has accepted the invitation to become Owner of the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostNotification($Group->owner,$post);

    $post = "You are now the Owner of the <a href='".$Group->getProfileLink()."'>$Group->groupname</a> group.";
    Notify::PostNotification($Member->id,$post);
  }


/**************************** GROUP ADMIN MESSAGES ****************************/

  static function NewGroupMember($Member,$Group)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$Member->id'>$Member->username</a> has joined the <a href='groups/view/$Group->id'>$Group->groupname</a> group.";
    Notify::PostMessage(1,$Group->id,$post,'memberjoined','admin','web',1);

    $post = "You are now a member of the <a href='groups/view/$Group->id'>$Group->groupname</a> group.";
    Notify::PostMessage(1,$Member->id,$post,'newmember','private','web',1);

  }

  static function InvitationDeclined($Member,$Group)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$Member->id'>$Member->username</a> has declined an invitation to join the <a href='groups/view/$Group->id'>$Group->groupname</a> group.";
    Notify::PostMessage(1,$Group->id,$post,'memberdeclined','admin','web',1);

    $post = "You have declined an invitation to join the <a href='groups/view/$Group->id'>$Group->groupname</a> group.";
    Notify::PostMessage(1,$Member->id,$post,'invitationdeclined','private','web',1);
  }

  static function MemberLeftGroup($Member,$Group)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$Member->id'>$Member->username</a> has left the <a href='groups/view/$Group->id'>$Group->groupname</a> group.";
    Notify::PostMessage(1,$Group->id,$post,'memberleft','admin','web',1);

    $post = "You are no longer a member of the <a href='groups/view/$Group->id'>$Group->groupname</a> group.";
    Notify::PostMessage(1,$Member->id,$post,'notmember','private','web',1);
  }

  static function ChangeOwner($Group,$OldOwner,$NewOwner)
  {
    // NOT DONE
    $post = "The owner of the $Oldname group has been changed from <a href='members/profile/$OldOwner->id'>$OldOwner->username</a> to <a href='members/profile/$NewOwner->id'>$NewOwner->username</a>.";
    Notify::PostMessage(1,$Group->id,$post,'groupannounce','public','web',1);
  }

  static function ChangePrivacy($Group)
  {
    // NOT DONE
    $post = "Privacy settings for the <a href='groups/view/$Group->id'>$Group->groupname</a> has been changed. <a href='groups/view/$Group->id'>$Group->groupname</a> is now a Private group.";
    Notify::PostMessage(1,$Group->id,$post,'groupannounce','public','web',1);
  }

  static function ChangeName($Group,$Oldname)
  {
    // NOT DONE
    $post = "The $Oldname group has changed its name. The new name is <a href='groups/view/$Group->id'>$Group->groupname</a>.";
    Notify::PostMessage(1,$Group->id,$post,'groupannounce','public','web',1);
  }

  static function MembershipRequested($Requester,$Group)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$Requester->id'>$Requester->username</a> has requested membership in the <a href='groups/view/$Group->id'>$Group->groupname</a> group. <a href='#' onclick='return Messages.AcceptMemberRequest(this,$Group->id,$Requester->id)'>Accept</a> or <a href='#' onclick='return Messages.DeclineMemberRequest(this,$Group->id,$Requester->id)'>Decline</a> this request.";
    Notify::PostMessage(1,$Group->id,$post,'memberrequestreceived','admin','web',1);

    $post = "You have requested membership in the <a href='groups/view/$Group->id'>$Group->groupname</a> group.";
    Notify::PostMessage(1,$Requester->id,$post,'memberrequestsent','private','web',1);
  }

  static function MembershipAccepted($Admin,$Requester,$Group)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$Admin->id'>$Admin->username</a> has accepted <a href='members/profile/$Requester->id'>$Requester->username</a>'s request for membership in the <a href='groups/view/$Group->id'>$Group->groupname</a> group.";

    Notify::PostMessage(1,$Group->id,$post,'memberrequestaccepted','admin','web',1);

    $post = "Your request for membership in the <a href='groups/view/$Group->id'>$Group->groupname</a> group has been accepted.";
    Notify::PostMessage(1,$Requester->id,$post,'memberrequestaccepted','private','web',1);

    if ($Requester->popup_group_accepted)
    {
      $popup = "<a href='groups/view/$Group->id'>$Group->groupname</a> has accepted your membership request";
      Notify::SendPopup($Requester,$popup);
    }
  }

  static function MembershipDeclined($Admin,$Requester,$Group)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$Admin->id'>$Admin->username</a> has declined <a href='members/profile/$Requester->id'>$Requester->username</a>'s request for membership in the <a href='groups/view/$Group->id'>$Group->groupname</a> group.";

    Notify::PostMessage(1,$Group->id,$post,'memberrequestdeclined','admin','web',1);

    $post = "Your request for membership in the <a href='groups/view/$Group->id'>$Group->groupname</a> group has been declined.";
    Notify::PostMessage(1,$Requester->id,$post,'memberrequestdeclined','private','web',1);

    if ($Requester->popup_group_rejected)
    {
      $popup = "<a href='groups/view/$Group->id'>$Group->groupname</a> has declined your membership request";
      Notify::SendPopup($Requester,$popup);
    }
  }

  static function GroupDisbanded($Member,$GroupName,$request=false)
  {
    // NOT DONE
    global $LOCAL;
    $post = "The group '$GroupName' has been disbanded.";
    if ($request) $post .= " Your membership request has been canceled.";
    Notify::PostMessage(1,$Member->id,$post,'text','private','web',1);
  }

  static function AnnounceGroup($User,$Group)
  {
    //$post = "<a href='members/profile/$User->id'>$User->username</a> has just created a new group called <a href='groups/view/$GroupID'>$GroupName</a>.";
    $post = "I have just created a new group called <a href='groups/view/$GroupID'>$GroupName</a>.";
    Notify::PostMessage($User->id,0,$post,'text','public','web',1);
  }


/************************ MEMBER SUBSCRIPTION MESSAGES ************************/



  static function SubscriptionRequest($Requester,$Requested)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$Requester->id'>$Requester->username</a> has asked to follow you. <a href='#' onclick='return Messages.AcceptFollowRequest(this,$Requested->id,$Requester->id)'>Accept</a> or <a href='#' onclick='return Messages.DeclineFollowRequest(this,$Requested->id,$Requester->id)'>Decline</a> this request.";
    $email = "$Requester->username has asked to follow you on Miio.";
    $sms = "$Requester->username has asked to follow you on Miio.";
    $popup = "<a href='members/profile/$Requester->id'>$Requester->username has asked to follow you</a>";
    Notify::PostMessage(1,$Requested->id,$post,'followreqsnt','private','web',1);
    if ($Requested->popup_friend_request) Notify::SendPopup($Subscribedto,$popup);
    if ($LOCAL) return;
    if ($Requested->email_friend_request)
    {
      $headers = "From: Miio email notification <email_notify@".SMS_EMAIL_HOST.">\n";
      $headers .= "Content-type: text/html\n";
      mail($Requested->email, 'Email Notification from Miio', $email, $headers);
    }
    if ($Requested->sms_friend_request)
    {
      $phone = $Requested->getSMSEmail();
      $from = "sms_notify@".SMS_EMAIL_HOST;
      $headers = "From: $from\n";
      $headers .= "Priority: normal";
      $subject = '';
      mail($phone, $subject, $sms, $headers, "-f $from");
    }
  }

  static function FollowAccepted($User,$Requester)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$User->id'>$User->username</a> has accepted your request to follow ".hisher($User->gender)." feed.";
    Notify::PostMessage(1,$Requester->id,$post,'followreqacc','private','web',1);

    $post = "You have accepted <a href='members/profile/$Requester->id'>$Requester->username</a>'s request to follow your feed.";
    Notify::PostMessage(1,$User->id,$post,'followreqacc','private','web',1);

    if ($User->popup_friend_accept)
    {
      $popup = "<a href='members/profile/$User->id'>$User->username</a> has accepted your follow request";
      Notify::SendPopup($Requester,$popup);
    }
  }

  static function FollowDeclined($User,$Requester)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$User->id'>$User->username</a> has declined your request to follow ".hisher($User->gender)." feed.";
    Notify::PostMessage(1,$Requester->id,$post,'followreqdec','private','web',1);

    $post = "You have declined <a href='members/profile/$Requester->id'>$Requester->username</a>'s request to follow your feed.";
    Notify::PostMessage(1,$User->id,$post,'followreqdec','private','web',1);

    if ($User->popup_friend_reject)
    {
      $popup = "<a href='members/profile/$User->id'>$User->username</a> has declined your follow request";
      Notify::SendPopup($Requester,$popup);
    }
  }

  static function FollowCanceled($User,$Requested)
  {
    // NOT DONE
    global $LOCAL;
    $post = "<a href='members/profile/$User->id'>$User->username</a> has canceled ".hisher($User->gender)." request to follow your feed.";
    Notify::PostMessage(1,$Requested->id,$post,'followreqcnl','private','web',1);

    $post = "You have canceled your request to follow <a href='members/profile/$Requested->id'>$Requested->username</a>'s feed.";
    Notify::PostMessage(1,$User->id,$post,'followreqcnl','private','web',1);
  }

/******************************************************************************/

  static function SendMiioInvitation($from_name,$from_email,$message,$to_name,$to_email)
  {
    // NOT DONE
    global $User,$LOCAL,$LOGGEDIN;
    $usr = User::getByEmail($to_email);
    if ($usr)
    {
      // TODO: REMOVE DEBUG CODE
      if ($User)
      {
        $Recipient = User::get($usr['id']);
        if ($Recipient == $User)
        {
          $post = "Silly $User->username, inviting yourself to join Miio";
          $email = $post;
          $sms = $post;
          $popup = "You just invited yourself to join Miio.";
        }
        else
        {
          $post = "<a href='members/profile/$User->id'>$User->username</a> is reaching out to invite you to join Miio. <a href='#' onclick='return Messages.Follow(this,$User->id,$User->username)'>Follow $User->username</a> or <a href='#' onclick='return Messages.Ignore(this)'>Ignore this notification</a>.";
          $email = "$User->username just invited you to join Miio, but you're already a member!.";
          $sms = "$User->username is reaching out to invite you to join Miio.";
          $popup = "<a href='members/profile/$User->id'>$User->username is inviting you to join Miio</a>";
        }
      }
      else
      {
        $post = "<a href='members/profile/1'>NEWMIIOUSER</a> is reaching out to invite you to join Miio. <a href='#' onclick='return Messages.Follow(this,1,\"NEWMIIOUSER\")'>Follow NEWMIIOUSER</a> or <a href='#' onclick='return Messages.Ignore(this)'>Ignore this notification</a>.";
          $email = "NEWMIIOUSER just invited you to join Miio, but you're already a member!.";
          $sms = "NEWMIIOUSER is reaching out to invite you to join Miio.";
          $popup = "<a href='members/profile/1'>NEWMIIOUSER is inviting you to join Miio</a>";
      }
      Notify::PostMessage(1,$Recipient->id,$post,'invitation','private','web',1);
      //if ($Recipient->popup_friend_request) Notify::SendPopup($Recipient,$popup);
      if ($LOCAL) return;
      //if ($Recipient->email_friend_request)
      //{
        $headers = "From: Miio email notification <email_notify@".SMS_EMAIL_HOST.">\n";
        $headers .= "Content-type: text/html\n";
        mail($Recipient->email, 'Email Notification from Miio', $email, $headers);
      //}
      /*
      if ($Recipient->sms_friend_request)
      {
        $phone = $Recipient->getSMSEmail();
        $from = "sms_notify@".SMS_EMAIL_HOST;
        $headers = "From: $from\n";
        $headers .= "Priority: normal";
        $subject = '';
        mail($phone, $subject, $sms, $headers, "-f $from");
      }
      */
    }
    else
    {
      $headers = "From: Miio <miio@miio.com>\n";
      $text = "Have you seen this? http://miio.com";
      mail($to_email, "Invitation to join Miio", $text, $headers);
    }
  }

/******************************************************************************/

  static function ReportedMember($Reporter,$Reported,$opts)
  {
    // NOT DONE
    global $LOC, $LOCAL;
    $post = "MEMBER REPORT: <a href=\"".$LOC."members/profile/".$Reported->id."\">$Reported->username</a> is being reported by <a href=\"".$LOC."members/profile/".$Reporter->id."\">$Reporter->username</a> for:\n\n";
    if ($opts['spam']) $post .= "spam\n";
    if ($opts['abuse']) $post .= "rude/abusive\n";
    if ($opts['obscene']) $post .= "obscenity/content\n";
    if ($opts['copyright']) $post .= "copyright violation\n";
    if ($opts['hate']) $post .= "hate\n";
    if ($opts['other']) $post .= "other\n";
    if (trim($opts['comments'])!="") $post .= "\ncomments:\n".$opts['comments'];
    Notify::PostMessage(1,1,$post,'text','private','web',1);
    if ($LOCAL) return;
    $headers = "From: Miio System <email_notify@".SMS_EMAIL_HOST.">\n";
    $headers .= "Content-type: text/html\n";
    mail('23.tony@gmail.com', 'Miio User Report', $post, $headers);
    mail('richardlusk@gmail.com', 'Miio User Report', $post, $headers);
  }

  static function ReportedGroup($Reporter,$Reported,$opts)
  {
    // NOT DONE
    global $LOC, $LOCAL;
    $post = "GROUP REPORT: <a href=\"".$LOC."groups/view/".$Reported->id."\">$Reported->groupname</a> is being reported by <a href=\"".$LOC."members/profile/".$Reporter->id."\">$Reporter->username</a> for:\n\n";
    if ($opts['spam']) $post .= "spam\n";
    if ($opts['abuse']) $post .= "rude/abusive\n";
    if ($opts['obscene']) $post .= "obscenity/content\n";
    if ($opts['copyright']) $post .= "copyright violation\n";
    if ($opts['hate']) $post .= "hate\n";
    if ($opts['other']) $post .= "other\n";
    if (trim($opts['comments'])!="") $post .= "\ncomments:\n".$opts['comments'];
    Notify::PostMessage(1,1,$post,'text','private','web',1);
    if ($LOCAL) return;
    $headers = "From: Miio System <email_notify@".SMS_EMAIL_HOST.">\n";
    $headers .= "Content-type: text/html\n";
    mail('23.tony@gmail.com', 'Miio User Report', $post, $headers);
    mail('richardlusk@gmail.com', 'Miio User Report', $post, $headers);
  }

  static function MessageFailed($Sender,$failed,$receivedcount,$post)
  {
    $failedlist = implode(', ',$failed);
    $text = "MESSAGE FAILED: Your message '";
    $text .= substr($post,0,25);
    if (strlen($post)>25) $text .= "...";
    $text .= "' failed to be delivered to non-existent recipient(s): $failedlist";
    Notify::PostMessage(1,$Sender->id,$text,'text','private','web',1);
  }




}

?>