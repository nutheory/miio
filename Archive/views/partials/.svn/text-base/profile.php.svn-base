<?

function ListUserProfile($User,$user,$messagelist,$message=false)
{
  $html = "<div class='list_profile'>";
  if ($messagelist)
  {
    $html .= "<a href='#' class='close' onclick='return Messages.HideMemberInfo(this,\"$user->id\")'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  }
  else if ($message)
  {
    $html .= "<a href='#' class='close' onclick='return Message.HideMemberInfo(this)'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  }
  else
  {
    $html .= "<a href='#' class='close' onclick='return Users.CloseProfile(\"$user->id\")'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  }

  $html .= "<h2>About Member</h2><ul>";
  $html .= "<li><label>Member Name</label><div><a href='".$user->getProfileLink()."'>$user->username</a></div></li>";
  if ($user->visibility==Enum::$visibility['public']) $html .= "<li><label>Profile Visibility</label><div>Public</div></li>";
  else $html .= "<li><label>Profile Visibility</label><div>Private</div></li>";

  if ($user->visibility==Enum::$visibility['public'] || $User->isFollowing($user->id))
  {
    $location = "";
    if (isset($user->home_location))
    {
      if ($user->home_location['city']) $location .= $user->home_location['city'];
      if ($user->home_location['region'])
      {
        if ($location != "") $location .= ', ';
        $location .= $user->home_location['region'];
      }
      if ($user->home_location['country'])
      {
        if ($location != "") $location .= ', ';
        $location .= $user->home_location['country'];
      }
    }

    $html .= "<li><label>Description</label><div>";
    $comma = false;
    if ($user->relationship && $user->relationship != Enum::$relationship['na'])
    {
      $html .= Options::$relationship[$user->relationship];
      $comma = true;
    }
    if ($user->gender)
    {
      if ($comma) $html .= ', ';
      $html .= Options::$gender[$user->gender];
      $comma = true;
    }
    if ($user->age)
    {
      if ($comma) $html .= ', ';
      $html .= "$user->age";
      $comma = true;
    }
    if ($location!='')
    {
      if ($comma) $html .= ', ';
      $html .= "from $location";
    }
    $html .= "</div></li>";

    if ($user->show_name)
    {
      $realname = trim("$user->first_name $user->last_name");
      $html .= "<li><label>Real name</label><div>$realname</div></li>";
    }

    $html .= "<li><label>About</label><div>$user->description</div></li>";

    $html .= "<li><label>Looking for</label><div>";
    $comma = false;
    foreach (Options::$looking_for as $looking=>$desc)
    {
      if ($user->looking_for[$looking])
      {
        if ($comma) $html .= ', ';
        $html .= $desc;
        $comma = true;
      }
    }
    $html .= "</div></li>";

    $html .= "<li><label>Interested in</label><div>";
    if ($user->looking['male'] && $user->looking['female']) $html .= "Both men and women";
    else if ($user->looking['male']) $html .= "Men";
    else if ($user->looking['female']) $html .= "Women";
    $html .= "</div></li>";

    if (count($user->albums) > 0) $albums = "<a href='members/profile/".$user->id."#albums'>".count($user->albums)."</a>";
    else $albums = "0";

    $html .= "<li><label>Following</label><div>" . count($user->subscriptions) ."</div></li>";
    $html .= "<li><label>Followers</label><div>" . count($user->subscribers) ."</div></li>";
    $html .= "<li><label>Groups</label><div>" . count($user->public_groups) ."</div></li>";
    $html .= "<li><label>Photo Albums</label><div>".$albums."</div></li>";

    if ($user->tags)
    {
      foreach ($user->tags as $tag)
      {
        $tags .= Options::$tags[$tag] . ' ';
      }
      $tags = trim($tags);
    }
    $html .= "<li><label>Keywords</label><div>$tags</div></li>";
    $html .= "<li><label>Website</label><div class='extra_hidden'><a href='http://$user->website' target='_blank'>$user->website</a></div></li>";
  }
  $html .= "</ul>";

  if ($location != "")
  {
    $html .= "<a href='http://maps.google.com/maps?q=$location&z=14' target='_blank'>";
    $html .= "<img class='map' src='http://maps.google.com/maps/api/staticmap?markers=".urlencode($location)."&zoom=14&size=460x200&maptype=roadmap&sensor=false&key=$gmap_key'>";
    $html .= "</a>";
  }

  $html .= "</div>";

  return $html;
}

function ListGroupProfile($User,$group,$messagelist,$message=false)
{
  $html = "<div class='list_profile'>";
  if ($messagelist)
  {
    $html .= "<a href='#' class='close' onclick='return Messages.HideGroupInfo(this,\"$group->id\")'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  }
  else if ($message)
  {
    $html .= "<a href='#' class='close' onclick='return Message.HideGroupInfo(this)'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  }
  else
  {
    $html .= "<a href='#' class='close' onclick='return Users.CloseProfile(\"$group->id\")'><img src='images/grey_close.png' alt='close' title='Close'></a>";
  }

  $html .= "<h2>About Group</h2><ul>";
  $html .= "<li><label>Group Name</label><div><a href='groups/view/$group->id'>$group->username</a>";
  if ($group->show_name && $group->first_name!='') $html .= " ($group->first_name)";
  $html .= "</div></li>";
  if ($group->visibility==Enum::$visibility['public']) $html .= "<li><label>Group Visibility</label><div>Public</div></li>";
  else $html .= "<li><label>Group Visibility</label><div>Private</div></li>";
  if ($group->visibleTo($User->id))
  {
    $html .= "<li><label>Members</label><div>" . count($group->group_members) . "</div></li>";

    $html .= "<li><label>Category</label><div>" . Options::$category[$group->category] . "</div></li>";

    $html .= "<li><label>Formed</label><div>" . date('j F, Y',strtotime($group->created)) . "</div></li>";

    $owner = User::get($group->owner);
    $html .= "<li><label>Owner</label><div><a href='members/profile/$owner->id'>" . $owner->username . "</a></div></li>";

    $admins = $group->getAdmins();
    $html .= "<li><label>Administrators</label><div>";
    $comma = false;
    foreach ($admins as $admin)
    {
      if ($admin['userid']!=$owner->id)
      {
        if ($comma) $html .= ", ";
        $Admin = User::get($admin['userid']);
        $html .= "<a href='members/profile/$Admin->id'>".$Admin->username."</a>";
        $comma = true;
      }
    }
    $html .= "</div></li>";

    $html .= "<li><label>About</label><div>$group->description</div></li>";

    if (count($group->albums) > 0) $albums = "<a href='groups/view/".$group->id."#albums'>".count($group->albums)."</a>";
    else $albums = "0";
    $html .= "<li><label>Photo Albums</label><div>".$albums."</div></li>";
    $html .= "<li><label class='long'>Website</label><div><a href='$group->website' target='_blank'>$group->website</a></div></li>";

    $location = "";
    if ($group->city_name) $location .= $group->city_name;
    if ($group->state_name)
    {
      if ($location != "") $location .= ', ';
      $location .= $group->state_name;
    }
    if ($group->country_name)
    {
      if ($location != "") $location .= ', ';
      $location .= $group->country_name;
    }
    $html .= "<li><label>Location</label><div>$location</div></li>";

    if ($group->tags)
    {
      foreach ($group->tags as $tag)
      {
        $tags .= Options::$tags[$tag] . ' ';
      }
      $tags = trim($tags);
    }
    $html .= "<li><label>Keywords</label><div>$tags</div></li>";
  }
  $html .= "</ul>";

  if ($location != "")
  {
    $html .= "<a href='http://maps.google.com/maps?q=$location&z=14' target='_blank'>";
    $html .= "<img class='map' src='http://maps.google.com/maps/api/staticmap?markers=".urlencode($location)."&zoom=14&size=460x200&maptype=roadmap&sensor=false&key=$gmap_key'>";
    $html .= "</a>";
  }
  $html .= "</div>";
  return $html;
}

?>