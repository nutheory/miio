<? global $User, $Group, $PARAMS; ?>

<? if (!$User->isAdminOf($Group->id)) { ?>
  <div class="not_admin">
    This page is reserved for Administrators of the <?= $Group->groupname ?> group only.
  </div>
<? } else { ?>
  <div id="photo_upload_form" <? if ($Group->photo!="") echo 'style="display:none"'; ?>>
    <p class="header">
      This is the photo displayed on the <?= $Group->groupname ?> group's <a href="<?= $Group->getProfileLink() ?>">profile</a> page.
    </p>

    <form enctype="multipart/form-data" name="photo_form" id="photo_form" action="ajax/upload_photo" method="POST" target="upload_target" onsubmit="Group.EditPhoto.SubmitPreview()">
      <div class="form_section">
        <label>Browse for a photo</label>
        <div class="directions">
          <p>Photos larger than 600x600 pixels will be automatically resized.</p>
          <div class="browse_button">
            <input type="hidden" name="isajax" value="1">
              <input type="hidden" name="js_url" value="Group.EditPhoto.URL">
              <input type="hidden" name="js_return" value="Group.EditPhoto.UploadDone">
              <input type="hidden" name="js_error" value="Group.EditPhoto.UploadError">
              <input type="hidden" name="profile_photo" value="1">
              <label class="up" for="photo_file_source">Upload photo:</label>
              <div class="highlight"><input type="file" name="photo_file_source" id="photo_file_source"></div>
              <p class="mid">Please be patient as photos may take a while to load.</p>
           </div>
        </div>
      </div>
      <div class="commit">
        <input type="submit" class="norm_button" name="preview" id="preview" value="Preview">
      </div>
    </form>
  </div>

  <div id="profile_photo" <? if ($Group->photo=="") echo 'style="display:none"'; ?>>
    <p class="header" id="second_header">
      This is the photo displayed on the <?= $Group->groupname ?> group's <a href="<?= $Group->getProfileLink() ?>">profile</a> page.
    </p>
    <div class="form_section">
      <h3 id="add_photo_head" style="display:none">Approve your group's profile photo</h3>
      <div class="directions">
        <p id="add_photo_text" style="display:none">If you are satisfied with your group's profile photo, please click the &quot;Update Group Photo&quot; button below. If you want to change it, please click the &quot;Cancel&quot; button below your photo.</p>
        <p id="edit_photo_text">If you want to either change or delete it, please click the buttons below your photo.</p>
        <div class="view_preview">
          <?
            $dim = Image::resize('profile_photos/'.$Group->photo,PROFILE_PHOTO_UPLOAD_SIZE,PROFILE_PHOTO_UPLOAD_SIZE);
          ?>
          <img src="<?= $Group->getProfilePhoto() ?>" alt="No Group Photo" id="profilephoto" height="<?= $dim['ht'] ?>" width="<?= $dim['wd'] ?>">
          <div class="buttons" id="profile_photo_delete" <? if ($Group->photo=="") echo 'style="display:none"'; ?>>
            <button class="short_button" onclick="return Group.EditPhoto.Change();">Change</button>
            <button class="short_button" onclick="return Group.EditPhoto.Delete();">Delete</button>
          </div>
          <div class="buttons" id="profile_photo_change" style="display:none">
            <button class="short_button" onclick="return Group.EditPhoto.Change();">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <div class="notice">
      Composing, creating, linking, or uploading unauthorized copyrighted material or inappropriate material
          of any kind is against our <a href="pages/terms">Terms of Use</a>
          and <a href="pages/copyright">Copyright Policy</a> and may result in your membership being canceled.
    </div>
    <div class="commit">
      <button class="short_button" name="submit" id="submit" onclick="Group.EditPhoto.FormSubmit()" style="display:none">Update Group Photo</button>
    </div>

  </div>

  <div class="form_response_short" id="form_response" style="display:none">
    <h3>The <?= $Group->groupname ?> group's Profile photo has been updated</h3>
    <div class="link_center"><a class="dash" href="<?= $Group->getProfileLink() ?>">Go to the Group Timeline</a></div>
  </div>
<? } ?>