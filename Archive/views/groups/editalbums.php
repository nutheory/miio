<? global $User, $Group, $PARAMS, $GET; ?>

<? if (!$User->isAdmin($Group->id)) { ?>
  <div class="not_admin">
    This page is reserved for Administrators of the <?= $Group->groupname ?> group only.
  </div>
<? } else { ?>
<div id="group_album_form">
  <? if (count($Group->albums) < 5) { ?>
    <div class="form_section" id="openedit_new">
      <a href="#" onclick="return Group.EditAlbums.Edit('new')">Create New Album</a>
      <? if (count($Group->albums) > 0) { ?>
        <a class="see_albums" href="groups/view/<?= $Group->id ?>#albums">See the <?= $Group->groupname ?> group's albums the way others see them</a>
      <? } ?>
    </div>

    <div class="edit" id="edit_new" style="display:none">
      <form enctype="multipart/form-data" name="album_form_new" id="album_form_new" action="groups/upload_album/<?= $Group->id ?>" method="POST" target="upload_target" onsubmit="return Group.EditAlbums.FormSubmit('new')">
        <div class="form_section" style="padding-top:0">
          <h3 class="album_head">
            Create New Album
            <a class="cancel" href="#" onclick="return Group.EditAlbums.Cancel('new')">Cancel</a>
          </h3>
          <div style="margin-bottom:0">
            <label for="title_new">Title</label>
            <input type="text" name="title" id="title_new">
          </div>
          <div class="counter">
            <div id="count_new">140</div>
            <label for="description_new">Description</label>
            <textarea name="description" id="description_new" onkeyup="return Group.EditAlbums.Count(event,this,'count_new');"></textarea>
          </div>
        </div>
        <div class="form_section">
          <input type="hidden" name="isajax" value="1">
          <input type="hidden" name="js_return" value="Group.EditAlbums.UploadDone">
          <input type="hidden" name="js_error" value="Group.EditAlbums.UploadError">
          <input type="hidden" name="album_id" value="new">
          <ul>
            <? for ($p=1;$p<10;$p++) { ?>
              <li>
                <div class="number"><?= $p ?></div>
                <div class="item">
                  <input type="file" name="<?= $p ?>" id="image_new_<?= $p ?>" <? if ($p>1) echo "disabled"; ?> onchange="Group.EditAlbums.Enable(this,'new',<?= $p+1 ?>)">
                </div>
              </li>
            <? } ?>
          </ul>
        </div>

        <div class="notice">
          Composing, creating, linking, or uploading unauthorized copyrighted material or inappropriate material
          of any kind is against our <a href="pages/terms">Terms of Use</a>
          and <a href="pages/copyright">Copyright Policy</a> and may result in your membership being canceled.
        </div>
        <div class="commit">
          <input type="submit" class="norm_button" name="submit_new" id="submit_new" value="Save Album" style="float: none">
        </div>
      </form>
    </div>
  <? } ?>

  <? if (count($Group->albums)>0) { ?>
    <? $albums = 0; ?>
    <? foreach ($Group->albums as $album) { ?>
      <? $albums++; ?>
      <? $showthis = ($GET['id']==$album['id']) ? true : false; ?>
      <div class="album <? if ($showthis) echo 'album_highlight'; ?>" id="albumcontainer_<?= $album['id'] ?>">
        <div id="view_head_<?= $album['id'] ?>">
          <div class="showlink">
            <a href="#" id="hide_album_<?= $album['id'] ?>" onclick="return Group.EditAlbums.ToggleAlbum(<?= $album['id'] ?>,false);" <? if (!$showthis) echo "style='display:none'"; ?>>Hide album</a>
            <a href="#" id="show_album_<?= $album['id'] ?>" onclick="return Group.EditAlbums.ToggleAlbum(<?= $album['id'] ?>,true);" <? if ($showthis) echo "style='display:none'"; ?>>Show album</a>
          </div>
          <h3><?= $album['title'] ?></h3>
        </div>
        <div id="edit_head_<?= $album['id'] ?>" style="display:none">
          <a href="#" class="cancel" onclick="return Group.EditAlbums.Cancel(<?= $album['id'] ?>)" style="float:right;font-size:12px;margin-right:2px;margin-top:3px;">Cancel Edit</a>
          <h3>Editing <?= $album['title'] ?></h3>
        </div>

        <div class="edit" id="edit_<?= $album['id'] ?>" style="display:none">
          <div class="form_section">
            <div style="margin-bottom:0">
              <label for="title_<?= $album['id'] ?>">Title</label>
              <input type="text" id="title_<?= $album['id'] ?>" value="<?= $album['title'] ?>">
            </div>
            <div class="counter">
              <div id="count_<?= $album['id'] ?>">140</div>
              <label for="description_<?= $album['id'] ?>">Description</label>
              <textarea id="description_<?= $album['id'] ?>" onkeyup="return Group.EditAlbums.Count(event,this,'count_<?= $album['id'] ?>');"><?= $album['description'] ?></textarea>
            </div>
          </div>

          <div class="commit">
            <button class="norm_button" id="submit_<?= $album['id'] ?>" onclick="Group.EditAlbums.Update(<?= $album['id'] ?>)">Update Album</button>
          </div>
        </div>

        <div id="album_grid_<?= $album['id'] ?>" <? if (!$showthis) echo "style='display:none'"; ?>>
          <p><?= $album['description'] ?></p>

          <div class="action_bar" id="viewing_album_<?= $album['id'] ?>">
            <a href="#" id="add_photo_link_<?= $album['id'] ?>" onclick="return Group.EditAlbums.Add(<?= $album['id'] ?>)" <? if (count($album['photos'])>8) echo "style='display:none'"; ?>>Add Photos</a>
            <a href="#" onclick="return Group.EditAlbums.Edit(<?= $album['id'] ?>)">Edit Album Info</a>
            <a class="delete" href="#" onclick="return Group.EditAlbums.DeleteAlbum(<?= $album['id'] ?>,'<?= $album['title'] ?>');">Delete Entire Album</a>
          </div>
          <div class="action_bar" id="editing_album_<?= $album['id'] ?>" style="display:none;">
            &nbsp;
            <a class="cancel" href="#" onclick="return Group.EditAlbums.Cancel('<?= $album['id'] ?>',true)">Cancel</a>
          </div>
          <?
            $photos = array();
            foreach ($album['photos'] as $photo) $photos[] = $photo;

          ?>
          <div id="album_photos_<?= $album['id'] ?>">
            <table class="album_photos">
              <? for ($p=0;$p<9;$p++) { ?>
                <?
                  $col = $p%3;
                  $ix = $p+1;
                ?>
                <? if ($col==0) echo "<tr>"; ?>
                  <td class="col<?= $col ?>">
                    <? if ($photos[$p]) { ?>

                      <div class="title" id="t_<?= $album['id'] ?>_<?= $ix ?>"><?= $photos[$p]['title'] ?></div>
                      <div class="photo" id="p_<?= $album['id'] ?>_<?= $ix ?>">
                        <? $size = Image::resize('albums/'.$photos[$p]['saved_filename'],ALBUM_PHOTO_HEIGHT,ALBUM_PHOTO_WIDTH); ?>
                        <img src="albums/<?= $photos[$p]['saved_filename'] ?>" height=<?= $size['ht'] ?> width=<?= $size['wd'] ?> title="<?= $photos[$p]['title'] ?>">
                      </div>
                      <div id="d_<?= $album['id'] ?>_<?= $ix ?>">
                        <a href="#" class="delete" id="delete_<?= $album['id'] ?>_<?= $photos[$p]['id'] ?>" onclick="return Group.EditAlbums.DeletePhoto(<?= $album['id'] ?>,<?= $photos[$p]['id'] ?>,'<?= $photos[$p]['title'] ?>',this)">Delete</a>
                      </div>
                      <input type="hidden" id="i_<?= $album['id'] ?>_<?= $ix ?>" value="1">
                    <? } else { ?>
                      <div class="title" id="t_<?= $album['id'] ?>_<?= $ix ?>">&nbsp;</div>
                      <div class="photo" id="p_<?= $album['id'] ?>_<?= $ix ?>"><?= $ix ?></div>
                      <div id="d_<?= $album['id'] ?>_<?= $ix ?>"></div>
                      <input type="hidden" id="i_<?= $album['id'] ?>_<?= $ix ?>" value="0">
                    <? } ?>
                  </td>
                <? if ($col==2) echo "</tr>"; ?>
              <? } ?>
            </table>
          </div>

          <div class='edit' id="new_photos_<?= $album['id'] ?>" style="display:none">
            <form enctype="multipart/form-data" name="album_form_<?= $album['id'] ?>" id="album_form_<?= $album['id'] ?>" action="groups/upload_album/<?= $Group->id ?>" method="POST" target="upload_target" onsubmit="return Group.EditAlbums.FormSubmit('<?= $album['id'] ?>')">
              <div class='form_section'>
                <input type="hidden" name="isajax" value="1">
                <input type="hidden" name="js_return" value="Group.EditAlbums.UploadDone">
                <input type="hidden" name="js_error" value="Group.EditAlbums.UploadError">
                <input type="hidden" name="album_id" value="<?= $album['id'] ?>">
                <ul class="album_inputs">
                  <? for ($p=1;$p<10;$p++) { ?>
                    <li>
                      <div class="number"><?= $p ?></div>
                      <div class="item">
                        <? if ($photos[$p-1]) { ?>
                          <? $size = Image::resize('albums/'.$photos[$p-1]['saved_filename'],40,40); ?>
                          <img id="pic_<?= $album['id'] ?>_<?= $p ?>" src="albums/<?= $photos[$p-1]['saved_filename'] ?>" height=<?= $size['ht'] ?> width=<?= $size['wd'] ?> title="<?= $photos[$p-1]['title'] ?>">
                        <? } ?>
                        <input type="file" name="<?= $p ?>" id="image_<?= $album['id'] ?>_<?= $p ?>" onchange="Group.EditAlbums.Enable(this,'<?= $album['id'] ?>',<?= (int)$p+1 ?>)" <? if ($photos[$p-1]) echo "style='display:none'"; if($p-1!=count($photos)) echo " disabled"; ?> >
                      </div>
                    </li>
                  <? } ?>
                </ul>
              </div>
              <div class="commit">
                <input type="submit" class="norm_button" name="submit_<?= $album['id'] ?>" id="submit_<?= $album['id'] ?>" value="Update Album" style="float: none">
              </div>
            </form>
          </div>

        </div>
      </div>
    <? } ?>
  <? } ?>
</div>

<div class="form_response" id="form_response" style="display:none">
  <h3>Your Album has been updated</h3>
  <div class="link_center"><a class="dash" href="groups/view/<?= $Group->id ?>">Go to the Group Timeline</a>
  |
  <a href="#" onclick="return Group.EditAlbums.ReturnToAlbums()">Return to Albums</a></div>
</div>
<? } ?>






























<?
/*

  <table class="group_editalbums" id="group_editalbums_form">
    <? if (count($Group->albums)>0) { ?>
      <? $albums = 0; ?>
      <? foreach ($Group->albums as $album) { ?>
        <? $albums++; ?>
        <tr>
          <td class="album">
            <div class="title"><?= $album['title'] ?></div>
            <div class="description"><?= $album['description'] ?></div>
            <div class="sharing"><label>Sharing:</label> <?= $album['sharing'] ?></div>
            <div class="preview">
              <? foreach ($album['photos'] as $photo) { ?>
                <img src="albums/<?= $photo['saved_filename'] ?>" title="<?= $photo['title'] ?>" height=50>
              <? } ?>
            </div>
            <div class="openedit" id="openedit_<?= $album['id'] ?>">
              <a href="#" onclick="return Group.EditAlbums.Edit(<?= $album['id'] ?>)">Edit album</a>
              |
              <a class="delete" href="#" onclick="return Group.EditAlbums.DeleteAlbum(<?= $album['id'] ?>,'<?= $album['title'] ?>');">Delete Album</a>
            </div>
            <div class="edit" id="edit_<?= $album['id'] ?>" style="display:none">
              <div class="cancel"><a href="#" onclick="return Group.EditAlbums.Cancel(<?= $album['id'] ?>)">Cancel Edit</a></div>
              <table class="albuminfo">
                <tr>
                  <td class="albumlabel">Title:</td>
                  <td class="albuminput">
                    <input type="text" name="title" id="title_<?= $album['id'] ?>" value="<?= $album['title'] ?>">
                  </td>
                </tr>
                <tr>
                  <td class="albumlabel">Description:</td>
                  <td class="albuminput">
                    <textarea name="description" id="description_<?= $album['id'] ?>"><?= $album['description'] ?></textarea>
                  </td>
                </tr>
              </table>
              <? $photos = $album['photos']; ?>
              <form enctype="multipart/form-data" name="album_form_<?= $album['id'] ?>" id="album_form_<?= $album['id'] ?>" action="groups/upload_album/<?= $Group->id ?>" method="POST" target="upload_target">
                <input type="hidden" name="isajax" value="1">
                <input type="hidden" name="js_url" value="Group.EditAlbums.URL">
                <input type="hidden" name="js_return" value="Group.EditAlbums.UploadDone">
                <input type="hidden" name="js_error" value="Group.EditAlbums.UploadError">
                <input type="hidden" name="album_id" value="<?= $album['id'] ?>">
                <input type="hidden" name="deletedphotos_<?= $album['id'] ?>" id="deletedphotos_<?= $album['id'] ?>" value="">
                <table class="photos">
                  <? $enable = true; ?>
                  <? for ($p=1;$p<10;$p++) { ?>
                    <? $photo = array_shift($photos); ?>
                    <tr>
                      <td class="imagenumber"><?= $p ?></td>
                      <td class="photo">
                        <? if ($photo) { ?>
                          <img id="photo_<?= $album['id'] ?>_<?= $photo['id'] ?>" src="albums/<?= $photo['saved_filename'] ?>" title="<?= $photo['title'] ?>" height=50></td>
                        <? } ?>
                      </td>
                      <td class="imagefileinput"><input type="file" name="<?= $p ?>" id="image_<?= $album['id'] ?>_<?= $p ?>" <? if (!$enable) echo "disabled"; ?> onchange="Group.EditAlbums.Enable(this.value,'<?= $album['id'] ?>',<?= $p+1 ?>)"></td>
                      <td class="delete">
                        <? if ($photo) { ?>
                          <a href="#" class="delete" id="delete_<?= $album['id'] ?>_<?= $photo['id'] ?>" onclick="return Group.EditAlbums.DeletePhoto(<?= $album['id'] ?>,<?= $photo['id'] ?>)">Delete</a>
                          <span class="delete" id="deleted_<?= $album['id'] ?>_<?= $photo['id'] ?>" style="display:none">Deleted</span>
                        <? } ?>
                      </td>
                    </tr>
                    <? if (!$photo) $enable = false; ?>
                  <? } ?>
                </table>
              </form>
              <table>
                <tr>
                  <td class="submit">
                    <input type="submit" name="submit_<?= $album['id'] ?>" id="submit_<?= $album['id'] ?>" value="Update Album" onclick="Group.EditAlbums.FormSubmit(<?= $album['id'] ?>)">
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      <? } ?>
    <? } ?>

    <? if ($albums < 5) { ?>
      <tr>
        <td class="album">
          <div class="openedit" id="openedit_new"><a href="#" onclick="return Group.EditAlbums.Edit('new')">Create New Album</a></div>
          <div class="edit" id="edit_new" style="display:none">
            <div class="cancel"><a href="#" onclick="return Group.EditAlbums.Cancel('new')">Cancel</a></div>
            <form enctype="multipart/form-data" name="album_form_new" id="album_form_new" action="groups/upload_album/<?= $Group->id ?>" method="POST" target="upload_target">
              <table class="albuminfo">
                <tr>
                  <td class="albumlabel">Title:</td>
                  <td class="albuminput">
                    <input type="text" name="title" id="title_new">
                  </td>
                </tr>
                <tr>
                  <td class="albumlabel">Description:</td>
                  <td class="albuminput">
                    <textarea name="description" id="description_new"></textarea>
                  </td>
                </tr>
              </table>
              <input type="hidden" name="isajax" value="1">
              <input type="hidden" name="js_return" value="Group.EditAlbums.UploadDone">
              <input type="hidden" name="js_error" value="Group.EditAlbums.UploadError">
              <input type="hidden" name="album_id" value="new">
              <table class="photos">
                <? for ($p=1;$p<10;$p++) { ?>
                  <tr>
                    <td class="imagenumber"><?= $p ?></td>
                    <td class="photo"></td>
                    <td class="imagefileinput"><input type="file" name="<?= $p ?>" id="image_new_<?= $p ?>" <? if ($p>1) echo "disabled"; ?> onchange="Group.EditAlbums.Enable(this.value,'new',<?= $p+1 ?>)"></td>
                    <td class="delete"></td>
                  </tr>
                <? } ?>
              </table>
            </form>
            <table>
              <tr>
                <td class="submit">
                  <input type="submit" name="submit_new" id="submit_new" value="Save Album" onclick="Group.EditAlbums.FormSubmit('new')">
                </td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    <? } ?>
    <tr>
      <td class="notice">
        Composing, creating, linking, or uploading unauthorized copyrighted material or inappropriate material
        of any kind is against our <a href="pages/terms">Terms of Use</a>
        and <a href="pages/copyright">Copyright Policy</a> and may result in your membership being canceled.
      </td>
    </tr>

  </table>

  <div id="group_albums_updated" style="display:none">
    <?= $Group->groupname ?>'s albums have been updated.<br><br>
    <a href="#" onclick="return Group.EditAlbums.ReturnToAlbums();">Back to albums</a>
  </div>
*/
?>