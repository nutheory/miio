<? global $User; ?>
<?
if ($User->photo == "") $avatar = AVATAR_URL.'default.jpg';
else $avatar = AVATAR_URL.$User->photo;
?>

<div id="message_form" class="message_form" style="display:none" onclick="MessageForm.PreserveForm(event)">

  <div class="speaker">
    <a href="members/profile/<?= $User->id ?>"><img id="messageform_photo" src="<?= $avatar ?>" alt="" class="avatar"></a>
    <!-- Begin Message Text -->
    <div id="message_text_container">
      <div class="callout"></div>
      <div id="text_type"><textarea class="subdued" name="message_text" id="message_text" onkeyup="return MessageForm.Count(event,this,'message_count');" onpaste="return MessageForm.Count(event,this,'message_count');" <?/*onmouseup="return MessageForm.Count(event,this,'message_count');" */?> onfocus="MessageForm.TextFocus(this);" onblur="MessageForm.TextBlur(this);" class="subdued">What's on your mind right now?</textarea></div>
    </div>
    <!-- End Message Text -->

    <div class="clear"></div>
  </div>

  <div id="new_message_wrapper" style="display:none">
    <div class="all_type_line">
      <div id="message_count">140</div>
        <ul class="links" id="message_type_selector">
          <li><a href="#" id="mt_message" onclick="return MessageForm.ChangeType('message');" class="selected"><img src="images/messageform/message_mid.png" alt="Message" title="Message"><h4 id="mtsp_message" style="display:inline">Text</h4></a></li>
          <li><a href="#" id="mt_review" onclick="return MessageForm.ChangeType('review');"><img src="images/messageform/review_mid.png" alt="Review" title="Review"><h4 id="mtsp_review" style="display:none">Review</h4></a></li>
          <li><a href="#" id="mt_question" onclick="return MessageForm.ChangeType('question');"><img src="images/messageform/question_mid.png" alt="Question" title="Question"><h4 id="mtsp_question" style="display:none">Question</h4></a></li>
          <li><a href="#" id="mt_link" onclick="return MessageForm.ChangeType('link');"><img src="images/messageform/link_mid.png" alt="Link" title="Link"><h4 id="mtsp_link" style="display:none">Link</h4></a></li>
          <li><a href="#" id="mt_photo" onclick="return MessageForm.ChangeType('photo');"><img src="images/messageform/photo_mid.png" alt="Photo" title="Photo"><h4 id="mtsp_photo" style="display:none">Photo</h4></a></li>
          <li><a href="#" id="mt_video" onclick="return MessageForm.ChangeType('video');"><img src="images/messageform/video_mid.png" alt="Video" title="Video"><h4 id="mtsp_video" style="display:none">Video</h4></a></li>
          <li><a href="#" id="mt_location" onclick="return MessageForm.ChangeType('location');"><img src="images/messageform/location_mid.png" alt="Location Update" title="Location Update"><h4 id="mtsp_location" style="display:none">Location</h4></a></li>
        </ul>
    </div>

    <!-- Message Type Mini Forms Start -->

    <!-- Begin Location Form -->

    <div id="message_forms" class="attachments">
      <div class="container">
        <a href="#" class="close" onclick="MessageForm.HideForm(); return false;"><img src="images/grey_close.png"></a>
        <? Render("partials","distribution"); ?>

        <!-- BEGIN FORMS -->

        <div id="form_message" style="display:none;"></div>

        <div id="form_location" style="display:none;" class="mt_section">
          <a href="#" class="clear_location" onclick="return MessageForm.ClearLocationInfo(this,'location')">Clear location</a>
          <h4 >Enter where you are and how long you'll be there:</h4>

          <div class="location">
            <label>Location Name:</label>
            <input type="text" name="location_location" id="location_location">
          </div>

          <div class="location">
            <label>Address:</label>
            <input type="text" name="location_address" id="location_address">
          </div>

          <div class="location">
            <label>Country:</label>
            <input type="text" name="location_country" id="location_country" onkeydown="return MessageForm.Location_Country.ProcessTab(event,this)" onkeyup="MessageForm.Location_Country.Suggest(event,this)" onfocus="MessageForm.Location_Country.Suggest(event,this)" onblur="MessageForm.Location_Country.Clear(this);MessageForm.ChangeLocationCountry(this)" autocomplete="off">
            <input type="hidden" id="location_country_default" value="<?= $User->country_name ?>">
            <div id="Location_Country" style="display:none" class="autofill"></div>
          </div>

          <div class="location">
            <label>State/Province/Region:</label>
            <input type="text" name="location_state" id="location_state" onkeydown="return MessageForm.Location_State.ProcessTab(event,this)" onkeyup="MessageForm.Location_State.Suggest(event,this)" onfocus="MessageForm.Location_State.Suggest(event,this)" onblur="MessageForm.Location_State.Clear(this);MessageForm.ChangeLocationState(this)" autocomplete="off">
            <input type="hidden" id="location_state_default" value="<?= $User->state_name ?>">
            <div id="Location_State" style="display:none" class="autofill"></div>
          </div>

          <div class="location">
            <label>City:</label>
            <input type="text" name="location_city" id="location_city" onkeydown="return MessageForm.Location_City.ProcessTab(event,this)" onkeyup="MessageForm.Location_City.Suggest(event,this)" onfocus="MessageForm.Location_City.Suggest(event,this)" onblur="MessageForm.Location_City.Clear(this);" autocomplete="off">
            <input type="hidden" id="location_city_default" value="<?= $User->city_name ?>">
            <div id="Location_City" style="display:none" class="autofill"></div>
          </div>

          <div class="location">
            <label>How long?</label>
            <input type="text" name="location_time" id="location_time">
            <select name="location_unit" id="location_unit">
              <option value='min'>minutes</option>
              <option value='hr'>hours</option>
              <option value='day'>days</option>
              <option value='wk'>weeks</option>
              <option value='mon'>months</option>
            </select>
          </div>
        </div>

        <div id="form_review" style="display:none"></div>

        <div id="form_question" style="display:none"></div>

        <div id="form_link" style="display:none" class="mt_section">
          <h4>Add Link</h4>
          <p>Enter your photo link, URL, or EMBED text below, then click &quot;Preview&quot;</p>
          <textarea name="link_url" id="link_url"></textarea>
          <div class="prev_button">
            <button name="link_submit" class="short_button" id="link_submit" onclick="MessageForm.Preview('link')">Preview</button>
          </div>
        </div>

        <div id="form_photo" style="display:none" class="mt_section">
          <h4>Add Photo</h4>
          <input type="radio" class="radio" name="photo_option" id="photo_option_url" value="url" checked onchange="MessageForm.ChangePhotoSource(this)">
          <label for="photo_option_url"> Enter your photo, photo link, URL or EMBED text below and click &quot;Preview&quot;</label>
          <textarea name="photo_url" id="photo_url" onchange="MessageForm.ChangePhotoSource(this)" class="photo"></textarea>
          <h4 class="center">OR</h4>
          <form enctype="multipart/form-data" name="photo_form" id="photo_form" action="ajax/upload_photo" method="POST" target="upload_target">
            <input type="hidden" name="is_attachment" value="1">
            <input type="hidden" name="js_url" value="MessageForm.LinkURL">
            <input type="hidden" name="js_return" value="MessageForm.PhotoReturn">
            <input type="hidden" name="js_error" value="MessageForm.PhotoError">
            <input type="radio" class="radio" name="photo_option" id="photo_option_file" value="file" onchange="MessageForm.ChangePhotoSource(this)">
            <label for="message_option_file">Upload photo:</label>
            <div class="file_input_container">
              <input class="file" type="file" name="photo_file" id="photo_file" onclick="MessageForm.ChangePhotoSource(this)" onchange="MessageForm.ChangePhotoSource(this)">
              <div id="file_button"><button name="button_file" id="button_file" >Select a File</button></div>
              <div id="file_name"></div>
            </div>
            <!--
            <div class="file_input_container">
              <input class="highlight" type="file" name="photo_file" id="photo_file" onclick="MessageForm.ChangePhotoSource(this)" onchange="MessageForm.ChangePhotoSource(this)">
            </div>
            -->
          </form>
          <div class="prev_button">
            <button name="photo_submit" class="short_button" id="photo_submit" onclick="MessageForm.Preview('photo')">Preview</button>
          </div>
        </div>

        <div id="form_video" style="display:none" class="mt_section">
          <h4>Add Video</h4>
          <p>Enter your video link, URL, or EMBED text below, then click &quot;Preview&quot;</p>
          <textarea name="video_url" id="video_url"></textarea>
          <div class="prev_button">
            <button name="video_submit" class="short_button" id="video_submit" onclick="MessageForm.Preview('video')">Preview</button>
          </div>
        </div>

        <!-- END OF FORMS -->

        <!-- Preview Start -->
        <div id="attachment_preview" class="attachment_preview" style="display:none">
          <img id="attachment_preview_image" src="" style="display:none">
          <div id="attachment_preview_div" style="display:none"></div>
          <a href="#" id="attachment_preview_change" onclick="return MessageForm.ChangePreview()" style="display:none">Change</a>
          <br>
        </div>
        <!-- Preview End -->

      <!-- Metadata Start -->
  <div class="mt_section">
    <div id="extendable_advanced">
      <a href="#" id="open_advanced" onclick="return MessageForm.OpenAdvanced();">Add Metadata</a>
      <a href="#" id="close_advanced" onclick="return MessageForm.CloseAdvanced();" style="display:none">Add Metadata<img src="images/grey_close.png" class="close"></a>
      <div id="advanced_options" class="options" style="display:none">
          <p>Fill this out if you want more members to find your <span id="advanced_type">text</span>.</p>
          <div class="tags">
            <div id="tags_count">140</div>
            <label class="ex">Keywords:</label>
            <textarea name="message_tags" id="message_tags" onkeyup="return MessageForm.TagCount(event,this);"></textarea>
            <div class="note">(Up to 140 characters. Use spaces to separate the keywords.)</div>
          </div>

          <div class="category">
            <label  class="ex rise">Post this <span id="category_type">message</span> in:</label>
            <select name="message_category" id="message_category">
              <option value="0">-- Select a Category --</option>
              <? for ($id=1;$id<count(Options::$category);$id++) { ?>
                <option value="<?= $id ?>"><?= Options::$category[$id] ?></option>
              <? } ?>
            </select>
          </div>

          <div class="location" id="opt_location" style="margin-top:15px">
            <a href="#" class="clear_location" onclick="return MessageForm.ClearLocationInfo(this,'message')">Clear location</a>

            <label class="ex">Location Name:</label>
            <input type="text" name="message_location" id="message_location">
          </div>

          <div class="location" id="opt_address">
            <label class="ex">Address:</label>
            <input type="text" name="message_address" id="message_address">
          </div>

          <div class="location" id="opt_country">
            <label class="ex">Country:</label>
            <input type="text" name="message_country" id="message_country" onkeydown="return MessageForm.Message_Country.ProcessTab(event,this)" onkeyup="MessageForm.Message_Country.Suggest(event,this)" onfocus="MessageForm.Message_Country.Suggest(event,this)" onblur="MessageForm.Message_Country.Clear(this);MessageForm.ChangeCountry(this)" autocomplete="off">
            <input type="hidden" id="message_country_default" value="<?= $User->country_name ?>">
            <div id="Message_Country" style="display:none" class="autofill"></div>
          </div>

          <div class="location" id="opt_state">
            <label class="ex">State/Province/Region:</label>
            <input type="text" name="message_state" id="message_state" onkeydown="return MessageForm.Message_State.ProcessTab(event,this)" onkeyup="MessageForm.Message_State.Suggest(event,this)" onfocus="MessageForm.Message_State.Suggest(event,this)" onblur="MessageForm.Message_State.Clear(this);MessageForm.ChangeState(this)" autocomplete="off">
            <input type="hidden" id="message_state_default" value="<?= $User->state_name ?>">
            <div id="Message_State" style="display:none" class="autofill"></div>
          </div>

          <div class="location" id="opt_city">
            <label class="ex">City:</label>
            <input type="text" name="message_city" id="message_city" onkeydown="return MessageForm.Message_City.ProcessTab(event,this)" onkeyup="MessageForm.Message_City.Suggest(event,this)" onfocus="MessageForm.Message_City.Suggest(event,this)" onblur="MessageForm.Message_City.Clear(this);" autocomplete="off">
            <input type="hidden" id="message_city_default" value="<?= $User->city_name ?>">
            <div id="Message_City" style="display:none" class="autofill"></div>
          </div>
        </div>
      </div>

    <!-- Location Image Start -->
  <div id="extendable_extra" style="display:none">
    <a href="#" id="open_extra" onclick="return MessageForm.OpenExtra();">Add Link or Photo</a>
    <a href="#" id="close_extra" onclick="return MessageForm.CloseExtra();" style="display:none">Add Link or Photo <img src="images/grey_close.png"></a>
      <div id="extra_form" class="options" style="display:none">
          <input type="radio" class="radio" name="photo_extra_option" id="photo_extra_option_url" value="url" checked onchange="MessageForm.ChangePhotoSource(this,true)">
          <label for="photo_extra_option_url"> Enter your photo, photo link, URL or EMBED text below and click &quot;Preview&quot;</label>
          <textarea name="photo_extra_url" id="photo_extra_url" onchange="MessageForm.ChangePhotoSource(this,true)" class="photo"></textarea>
          <h4>OR</h4>
          <form enctype="multipart/form-data" name="photo_extra_form" id="photo_extra_form" action="ajax/upload_photo" method="POST" target="upload_target">
            <input type="hidden" name="is_extra" value="1">
            <input type="hidden" name="js_url" value="MessageForm.LinkURL">
            <input type="hidden" name="js_return" value="MessageForm.PhotoReturn">
            <input type="hidden" name="js_error" value="MessageForm.PhotoError">
            <input type="radio" class="radio" name="photo_extra_option" id="photo_extra_option_file" value="file" onchange="MessageForm.ChangePhotoSource(this,true)">
            <label for="photo_extra_option_file">Upload photo:</label>
            <div class="file_input_container">
              <input class="file" type="file" name="photo_extra_file" id="photo_extra_file" onclick="MessageForm.ChangePhotoSource(this,true)" onchange="MessageForm.ChangePhotoSource(this,true)">
              <div id="extra_file_button"><button name="button_extra_file" id="button_extra_file" >Select a File</button></div>
              <div id="extra_file_name"></div>
            </div>
            <!--
            <div class="file_input_container">
              <input class="highlight" type="file" name="photo_extra_file" id="photo_extra_file" onclick="MessageForm.ChangePhotoSource(this,true)" onchange="MessageForm.ChangePhotoSource(this,true)">
            </div>
            -->
          </form>
          <div class="prev_button">
            <button name="photo_extra_submit" class="short_button" id="photo_extra_submit" onclick="MessageForm.Preview()">Preview</button>
          </div>
        </div>
  </div>
  </div>

      <div id="attachment_extra_preview" class="attachment_preview" style="display:none">
        <img id="attachment_extra_preview_image" src="" style="display:none">
        <div id="attachment_extra_preview_div" style="display:none"></div>
        <a href="#" id="attachment_extra_preview_change" onclick="return MessageForm.ChangePreview(true)" style="display:none">Change</a>
        <div class="clear" style="height:0;border:none;"></div>
      </div>
      <div class="footer">
        <button name="message_submit" id="message_submit" class="short_button" onclick="MessageForm.FormSubmit()">Send</button>
      </div>
      </div>
    </div>


  </div> <!-- Close Message Wrapper -->

</div> <!-- Close Main Form -->
