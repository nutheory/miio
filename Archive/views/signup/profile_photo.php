<div id="profile_photo_form">
	<h2>Add a profile photo<a href="user">skip</a></h2>
	<p class="header">
		This is the photo displayed on your profile page.
	</p>

	<form enctype="multipart/form-data" name="photo_form" id="photo_form" action="ajax/upload_photo" method="POST" target="upload_target" onsubmit="Signup.ProfilePhoto.SubmitPreview()">
 		<div class="form_section">
 			<label>Browse for a photo</label>
 			<div class="directions">
 				<p>Photos larger than 600x600 pixels will be automatically resized.</p>
 				<div class="browse_button">
     			<input type="hidden" name="isajax" value="1">
          <input type="hidden" name="js_url" value="Signup.ProfilePhoto.URL">
          <input type="hidden" name="js_return" value="Signup.ProfilePhoto.UploadDone">
          <input type="hidden" name="js_error" value="Signup.ProfilePhoto.UploadError">
          <input type="hidden" name="profile_photo" value="1">
          <label class="up" for="photo_file_source">Upload photo:</label>
          <div class="highlight"><input type="file" name="photo_file_source" id="photo_file_source"></div>
          <p class="mid">Please be patient as photos may take a while to load.</p>
        </div>
 			</div>
 		</div>
		<div class="buttons">
			<div class="right_buttons">
       	<input type="submit" class="short_button" name="preview" id="preview" value="Preview">
 			</div>
			<a href="#" onclick="return Signup.LastStep()">back</a>
		</div>
	</form>
</div>

<div id="profile_photo_confirm" style="display:none">
	<h2>Confirm your profile photo<a href="user">skip</a></h2>
	<div class="form_section">
		<div class="directions">
      <p>If you are satisfied with your profile photo, please click the &quot;Add Profile Photo&quot; button below.</p>
			<p>If you want to either change it or skip without uploading, please click the buttons below your photo.</p>
			<div class="view_preview">
				<img src="" alt="No Profile Photo" id="profilephoto">
				<div id="profile_photo_delete">
					<button class="short_button" onclick="return Signup.ProfilePhoto.Change();">Cancel</button>
	        <button class="short_button" onclick="return Signup.ProfilePhoto.Skip();">Skip</button>
	      </div>
			</div>
		</div>
	</div>
	
	<div class="notice">
		Composing, creating, linking, or uploading unauthorized copyrighted material or inappropriate material
				of any kind is against our <a href="pages/terms" target="_blank">Terms of Use</a>
				and <a href="pages/copyright" target="_blank">Copyright Policy</a> and may result in your membership being canceled.
	</div>
	
	<div class="buttons">
		<div class="right_buttons">
			<button class="short_button" name="submit" id="submit" onclick="Signup.ProfilePhoto.FormSubmit()">Add Photo and finish</button>
		</div>
		<a href="#" onclick="return Signup.LastStep()">back</a>
	</div>
</div>