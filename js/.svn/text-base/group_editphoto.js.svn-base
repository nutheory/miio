Group.EditPhoto = {};

Group.EditPhoto.URL = "";

Group.EditPhoto.Init = function()
{
  DOM.Show('right_col');
  DOM.SetClass('content_div','content_with_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('user_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
}

Group.EditPhoto.SubmitPreview = function()
{
  DOM.Show('user_loading');
}

Group.EditPhoto.FormSubmit = function()
{
  if (Group.EditPhoto.URL.trim() == "")
  {
    alert("Please select and preview a photo");
  }
  else
  {
    try
    {
      var photo = new Ajax(Group.EditPhoto.Updated);
      var url = "groups/update_profile_photo/"+Group.ID;
      var params = "isajax=1";
      params += "&photo="+Group.EditPhoto.URL.trim();
      photo.sendPostRequest(url,params);
    }
    catch (e) { alert ("Unable to read profile photo form"); }
  }
}

Group.EditPhoto.UploadDone = function()
{
  function resize_pic()
  { 
    DOM.Show('profile_photo');
    DOM.Show(photo.id);
    DOM.Hide('photo_upload_form');
    DOM.Hide('profile_photo_form');
	  DOM.Hide('edit_photo_text');
	  //DOM.Hide('second_header');
    DOM.Show('submit');
	  DOM.Show('add_photo_head');
	  DOM.Show('add_photo_text');
    DOM.Show('profile_photo_cancel_upload');
    DOM.Show('profile_photo_note');
    DOM.Hide('profile_photo_delete');
    DOM.Show('profile_photo_change');
    DOM.Hide('user_loading');
  }
  
  var photo = document.getElementById('profilephoto');
  if (photo)
  {
    var d = new Date();
    photo.style.height = 'auto';
    photo.style.width = 'auto';
    photo.onload = resize_pic;
    photo.src = HTTP_BASE + "file_temp/"+Group.EditPhoto.URL+"?x="+d.getTime();
  }
}

Group.EditPhoto.UploadError = function(error)
{
  DOM.Hide('user_loading');
  if (error=='No File') alert("Please select a file to upload before clicking 'Preview'.");
  else alert(error);
}

Group.EditPhoto.Change = function()
{
  DOM.Hide('profile_photo');
  DOM.Hide('profile_photo_note');
  DOM.Hide('submit');
  DOM.Show('photo_upload_form');
  return false;
}

Group.EditPhoto.Cancel = function(resetphoto)
{
  function resize_pic()
  {
    DOM.Show('profile_photo');
    DOM.Show(photo.id);
    var w = photo.offsetWidth;
    var h = photo.offsetHeight;
    DOM.Hide(photo.id)
    if (w>300 || h>300)
    {
      var adj = 1;
      if (w > h)
      {
        adj = 300/w;
      }
      else
      {
        adj = 300/h;
      }
      photo.style.height = (Math.floor(h*adj))+'px';
      photo.style.width = (Math.floor(w*adj))+'px';
    }
    DOM.Show(photo.id);
  }
  
  var photo = document.getElementById('profilephoto');
  if (resetphoto)
  {
    if (photo)
    {
      var d = new Date();
      photo.style.height = 'auto';
      photo.style.width = 'auto';
      photo.onload = resize_pic;
      photo.src = 'profile_photos/'+resetphoto+"?x="+d.getTime();
    }
    DOM.Show('profile_photo_delete');
  }
  DOM.Show('profile_photo');
  DOM.Hide('profile_photo_cancel_upload');
  DOM.Hide('submit');
  DOM.Hide('photo_upload_form');
  return false;
}

Group.EditPhoto.Updated = function(response)
{
  DOM.Hide('user_loading');
  var resp = response.substr(0,2);
  if (resp=='ok')
  {
    var d = new Date();
    photourl = response.substr(2);
    var photo = document.getElementById('group_photo');
    if (photo)
    { 
      photo.src = photourl+"?x="+d.getTime();
    }
    
    // load "new page"
    DOM.Hide('photo_upload_form');
    DOM.Hide('profile_photo');
    DOM.Hide('add_photo_head');
    DOM.Hide('add_photo_text');
    DOM.Show('edit_photo_text');
    DOM.Show('profile_photo_delete');
    DOM.Hide('profile_photo_change');
    DOM.Hide('submit');
    DOM.Show('form_response');
    Group.CloseCurrent();
  }
  else
  {
    alert("Error:\n\n"+response);
  }
  Group.EditPhoto.URL = "";
}

Group.EditPhoto.Delete = function()
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      var photo = document.getElementById('profilephoto');
      if (photo)
      {
        var d = new Date();
        photo.style.height = 'auto';
        photo.style.width = 'auto';
        photo.onload = null;
        photo.src = "?x="+d.getTime();
        DOM.Hide('profile_photo');
        DOM.Hide('profile_photo_cancel_upload');
        DOM.Hide('profile_photo_delete');
        DOM.Show('submit');
        DOM.Show('photo_upload_form');
      }
      var profilephoto = document.getElementById('group_photo');
      if (profilephoto)
      {
        var d = new Date();
        profilephoto.onload = null;
        profilephoto.src = "profile_photos/default.jpg?x="+d.getTime();
      }
      DOM.SetHTML('upload_photo_link','Upload a group photo');
    }
    else
    {
      alert("Error:\n\n"+response);
    }
  }
  
  var str = "Are you sure you want to delete "+Group.UserName+"'s profile photo?";
  if (confirm(str))
  {
    var page = new Ajax(ret);
    var url = "groups/delete_profile_photo/"+Group.ID;
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  return false;
}

Group.EditPhoto.Done = function()
{
  Group.Navigate('group_viewmessages_messages');
  return false;
}