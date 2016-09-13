<? global $PHOTOCOLLAGE, $CONTROLLER; ?>

<? if (count($PHOTOCOLLAGE)>0) { ?>
  <table class="photo_collage">
    <tr>
      <?
        for ($p=0;$p<25;$p++)
        {
          if ($newrow)
          {
            echo "<tr>";
            $newrow = false;
          }
          if ($PHOTOCOLLAGE[$p])
          {
            $usr = User::get($PHOTOCOLLAGE[$p]);
            echo "<td><a href='".$usr->getProfileLink()."'><img src='".$usr->getAvatar(false)."' alt='avatar' title='$usr->username' onmouseover='ImageHighlight(this,true);' onmouseout='ImageHighlight(this,false);'></a></td>";
          }
          else echo "<td></td>";
          if (($p+1)/5==floor(($p+1)/5))
          {
            echo "</tr>";
            $newrow = true;
            if (!$PHOTOCOLLAGE[$p]) break;
          }
        }
      ?>
    </tr>
    <? if (count($PHOTOCOLLAGE)>25) { ?>
      <tr class="collage_link"><td colspan=5>
        <? if ($CONTROLLER=='members') { ?>
          <a href="#" onclick="return Profile.Navigate('profile_subscriptions');">View more...</a>
        <? } ?>
      </td></tr>
    <? } ?>
  </table>

<? } ?>