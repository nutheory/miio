<? global $User, $Group; ?>
<div id="profile_description">
<ul>
  <?
    echo "<li><label>Group name</label><div>$Group->groupname";
    if ($Group->show_name && $Group->name!='') echo " ($Group->name)";
    echo "</div></li>";
    if ($Group->visibilty==Enum::$visibility['private']) echo "<li><label>Private Group</label><div>";
    else echo "<li><label>Public Group</label><div>";
    $membercount = count($Group->number_of['members']);
    if ($membercount==1) echo "$membercount member</div></li>";
    else echo "$membercount members</div></li>";
    echo "<li><label>Category</label><div>" . Options::$category[$Group->category] . "</div></li>";
    echo "<li><label>Formed</label><div>" . date('Y-m-d',$Group->created) . "</div></li>";
    $owner = User::get($Group->owner);
    echo "<li><label>Owner</label><div><a href='".$owner->getProfileLink()."'>$owner->username</a></div></li>";
    $admins = $Group->getAdmins();
    $adminlist = "";
    $comma = false;
    foreach ($admins as $admin)
    {
      if ($comma) $adminlist .= ", ";
      $adm = User::get($admin);
      $adminlist .= "<a href='".$adm->getProfileLink()."'>$adm->username</a>";
      $comma = true;
    }
    if ($adminlist != "") echo "<li><label>Administrators</label><div>$adminlist</div></li>";

    //echo "<br>";
    $output = false;
    // description
    if ($Group->description!="")
    {
      echo "<li><label>About</label><div>$Group->description</div></li>";
      $output = true;
    }

    // website
    if ($Group->website != "")
    {
      echo "<li><label>Website</label><div><a href='http://$Group->website' target='_blank'>$Group->website</a></div></li>";
      $output = true;
    }

    // location
    $location = "";
    $location = $Group->getLocation();
    if ($location != "")
    {
      echo "<li><label>Location</label><div>$location</div></li>";
      $output = true;
    }

    if ($Group->keywords)
    {
      $keywords = implode(' ',$Group->keywords);
      echo "<li><label>Keywords</label><div>$keywords</div></li>";
      $output = true;
    }

    // empty
    if (!$output) echo "When $Group->groupname's Administrators complete the group profile form, that information will be displayed here.";

  ?>
</div>
