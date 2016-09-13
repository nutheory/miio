<? global $User, $Profile, $LOGGEDIN; ?>

<div class="profile_albums">
  <? if (count($Profile->albums) > 0) { ?>
    <? foreach($Profile->albums as $album) { ?>
      <? if ($album['sharing']=='public' || $User->isFriend($Profile->id)) { ?>
        <table class="album">
          <tr>
            <th colspan=3>
              <?= $album['title'] ?>
            </th>
          </tr>
          <tr>
            <td colspan=3 class="description">
              <?= $album['description'] ?>
            </td>
          </tr>
          <tr>
            <? $photocount = 0; ?>
            <? foreach($album['photos'] as $photo) { ?>
              <?
                $photocount++;
                $size = Image::resize("albums/".$photo['saved_filename'],ALBUM_PHOTO_HEIGHT,ALBUM_PHOTO_WIDTH);
              ?>
              <td class='photo'>
                <!--<a href="#" onclick="return Profile.Albums.ShowPhoto(<?= $photo['id'] ?>);"><img src="albums/<?= $photo['saved_filename'] ?>" alt="<?= $photo['title'] ?>" height=<?= $size['ht'] ?> width=<?= $size['wd'] ?> onmouseover="ImageHighlight(this,true)" onmouseout="ImageHighlight(this,false)" onclick="Photo.Expand(this,<?= $size['original_ht'] ?>,<?= $size['original_wd'] ?>)"></a>-->
                <img src="albums/<?= $photo['saved_filename'] ?>" alt="<?= $photo['title'] ?>" height=<?= $size['ht'] ?> width=<?= $size['wd'] ?> onmouseover="ImageHighlight(this,true)" onmouseout="ImageHighlight(this,false)" onclick="Photo.Expand(this,<?= $size['original_ht'] ?>,<?= $size['original_wd'] ?>)">
              </td>
              <? if ($photocount==3 || $photocount==6 || $photocount==9) echo "</tr><tr>"; ?>
            <? } ?>
            <? for ($c=0;$c<(3-$photocount%3);$c++) echo "<td></td>"; ?>
          </tr>
        </table>
      <? } ?>
    <? } ?>
  <? } else { ?>
    When <?= $Profile->username ?> creates a photo album, it will be displayed here.
  <? } ?>
</div>