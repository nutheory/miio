<? global $User, $Profile; ?>

<div id="profile_description">
<ul>
  <?
    $output = false;
    // I am:
    $iam = "";
    $comma = false;
    if ($Profile->gender)
    {
      $iam .= Options::$gender[$Profile->gender];
      $comma = true;
    }

    if ($Profile->birthday && $Profile->birthday!="0000-00-00")
    {
      if ($comma)
      {
        $iam .= ", ";
      }
      $iam .= $Profile->getAge();
      $comma = true;
    }

    if (in_array(1,$Profile->looking_for))
    {
      if ($comma)
      {
        $iam .= ", ";
        $comma = false;
      }
      $iam .= "looking for ";
      foreach (Options::$looking_for as $looking=>$desc)
      {
        if ($Profile->looking_for[$looking])
        {
          if ($comma)
          {
            $iam .= ", ";
          }
          $iam .= $desc;
          $comma = true;
        }
      }
    }

    if ($iam != "")
    {
      echo "<li><label>I am</label><div>$iam</div></li>";
      $output = true;
    }

    // description
    if ($Profile->description!="")
    {
      echo "<li><label>About</label><div>$Profile->description</div></li>";
      $output = true;
    }

    // location
    $location = $Profile->getLocation();
    if ($location != "")
    {
      echo "<li><label>Location</label><div>$location</div></li>";
      $output = true;
    }

    // website
    if ($Profile->website != "")
    {
      echo "<li><label>Website</label><div><a href='$Profile->website' target='_blank'>$Profile->website</a></div></li>";
      $output = true;
    }

    // relationship
    if ($Profile->relationship)
    {
      echo "<li><label>Relationship</label><div>" . Options::$relationship[$Profile->relationship] . "</div></li>";
      $output = true;
    }

    // interested in
    if (in_array(1,$Profile->interested_in))
    {
      echo "<li><label>Interested in</label><div>";
      if ($Profile->interested_in['male'])
      {
        echo "Men";
      }
      if ($Profile->interested_in['female'])
      {
        if ($Profile->interested_in['male']) echo ", ";
        echo "Women";
      }
      echo "</div></li>";
      $output = true;
    }

    if ($Profile->keywords)
    {
      $keywords = implode(' ',$Profile->keywords);
      echo "<li><label>Keywords</label><div>$keywords</div></li>";
      $output = true;
    }

    // empty
    if (!$output) echo "When $Profile->username completes the profile information form, that information will be displayed here.";
  ?>
</ul>
</div>
