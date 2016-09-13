
User.Settings = {};

User.Settings.Errors = [];

User.Settings.Init = function()
{
  DOM.Hide('message_form');
  DOM.Hide('messageform_divider');
  DOM.Hide('message_filters');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('user_filter_container');
  DOM.Hide('people_friends');
  DOM.Hide('people_subscriptions');
  DOM.Hide('people_subscribers');
  DOM.Hide('groups_publicgroups');
  DOM.Hide('groups_privategroups');

  Lib.InitLocation(User.Settings,countries,states,cities);

  var provider = document.getElementById('sms_provider');
  if (provider)
  {
    User.Settings.SMS = new AutoFill(sms_providers,true);
    User.Settings.SMS.Init("providerlist","sms_provider");
  }
  if (DOM.Exists('miio_all'))
  {
    if (DOM.GetValue('miio_all')==0) { DOM.Show('miio_select'); DOM.Hide('miio_unselect'); }
    if (DOM.GetValue('email_all')==0) { DOM.Show('email_select'); DOM.Hide('email_unselect'); }
    if (DOM.GetValue('sms_all')==0) { DOM.Show('sms_select'); DOM.Hide('sms_unselect'); }
  }
}

User.Settings.AddError = function(message)
{
  User.Settings.Errors.push(message);
}

User.Settings.ShowErrors = function()
{
  var str = "There are errors in your form. Please check the following and try again:\n\n";
  for (var err in User.Settings.Errors)
  {
    str += "- " + User.Settings.Errors[err] + ".\n";
  }
  alert(str);
  User.Settings.Errors = [];
}

User.Settings.ChangeCountry = function(obj)
{
  Lib.ChangeCountry(User.Settings,obj);
}

User.Settings.ChangeState = function(obj,country)
{
  Lib.ChangeState(User.Settings,obj,country);
}

User.Settings.TagCount = function(e,obj)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,TAGLENGTH);
    obj.scrollTop = st;
  }

  if (window.event) key = window.event.keyCode;
  else key = e.which;

  var counter = document.getElementById('tags_count');
  if (counter)
  {
    if (obj.value.length > TAGLENGTH) disallow();
    else counter.innerHTML = TAGLENGTH-obj.value.length;
  }
}

User.Settings.ResponsePage = function(response)
{
  DOM.Hide('user_loading');
  var content = document.getElementById('user_content');
  if (content) content.innerHTML = response;
  var head = document.getElementById('content_header');
  if (head)
  {
  if (User.CurrentMain != 'timeline')
    {
      if (User.FilterType != "") var htm = User.FilterOpts[User.FilterType];
      else if (User.CurrentSubSub != "") var htm = User.MenuOpts[User.CurrentSubSub];
      else if (User.CurrentSub!="") var htm = User.MenuOpts[User.CurrentSub];
      else var htm = User.MenuOpts[User.CurrentMain];
    }
    else htm = 'Timeline';
    head.innerHTML = htm;
  }
  // reset left nav
  User.ResetLeftNav();
}

/******************************************************************************/

User.Settings.Profile = {};

User.Settings.Profile.Count = function(e,obj,divid)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,DESCRIPTIONLENGTH);
    obj.scrollTop = st;
  }

  if (window.event) key = window.event.keyCode;
  else key = e.which;

  var counter = document.getElementById(divid);
  if (counter)
  {
    if (obj.value.length > DESCRIPTIONLENGTH) disallow();
    else counter.innerHTML = DESCRIPTIONLENGTH-obj.value.length;
  }
}

User.Settings.Profile.FormSubmit = function()
{
  try
  {
    var profile = new Ajax(User.Settings.Profile.Updated);
    var url = "user/update_profile";
    var params = "isajax=1";
    params += "&name="+document.getElementById('full_name').value.trim();
    params += "&show_name="+((document.getElementById('show_name').checked)?'1':'0');
    params += "&description="+document.getElementById('description').value.trim();
    params += "&day="+document.getElementById('day').value;
    params += "&month="+document.getElementById('month').value;
    params += "&year="+document.getElementById('year').value;
    if (document.getElementById('male').checked) params += "&gender=male";
    else if (document.getElementById('female').checked) params += "&gender=female";
    params += "&ethnicity="+document.getElementById('ethnicity').value;
    params += "&country="+document.getElementById('country').value.trim();
    params += "&state="+document.getElementById('state').value.trim();
    params += "&city="+document.getElementById('city').value.trim();
    User.Settings.Profile.NewLocation =
    {
      "country":Forms.GetValue('country'),
      "state":Forms.GetValue('state'),
      "city":Forms.GetValue('city')
    }
    params += "&website="+document.getElementById('website').value.trim();
    params += "&lf_activity_partners="+((document.getElementById('lf_activity_partners').checked)?'1':'0');
    params += "&lf_chatting="+((document.getElementById('lf_chatting').checked)?'1':'0');
    params += "&lf_dating="+((document.getElementById('lf_dating').checked)?'1':'0');
    params += "&lf_friends="+((document.getElementById('lf_friends').checked)?'1':'0');
    params += "&lf_networking="+((document.getElementById('lf_networking').checked)?'1':'0');
    params += "&lf_whatever="+((document.getElementById('lf_whatever').checked)?'1':'0');
    params += "&interested_male="+((document.getElementById('interested_male').checked)?'1':'0');
    params += "&interested_female="+((document.getElementById('interested_female').checked)?'1':'0');
    params += "&relationship="+document.getElementById('relationship').value;
    params += "&visibility="+((document.getElementById('private').checked)?'private':'public');
    params += "&tags="+document.getElementById('settings_tags').value.trim();
    DOM.Show('user_loading');
    profile.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Profile.Updated = function(response)
{
  if (response=='ok')
  {
    Forms.SetValue('message_country_default',User.Settings.Profile.NewLocation.country);
    Forms.SetValue('message_state_default',User.Settings.Profile.NewLocation.state);
    Forms.SetValue('message_city_default',User.Settings.Profile.NewLocation.city);
    Forms.SetValue('location_country_default',User.Settings.Profile.NewLocation.country);
    Forms.SetValue('location_state_default',User.Settings.Profile.NewLocation.state);
    Forms.SetValue('location_city_default',User.Settings.Profile.NewLocation.city);
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/profileinfo";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
  User.Settings.Profile.NewName = null;
}

/******************************************************************************/

User.Settings.Mobile = {};

User.Settings.Mobile.ChangeCountry = function(obj)
{
  function ret(response)
  {
    var div = document.getElementById('sms_country_code_text');
    if (div) div.innerHTML = response;
  }

  var req = new Ajax(ret);
  var url = "ajax/get_sms_code";
  var params = "isajax=1";
  params += "&country="+obj.value;
  req.sendPostRequest(url,params);
}

User.Settings.Mobile.ClearAuth = function()
{
  alert("Error: Invalid call to User.Settings.Mobile.CelarAuth"); return;
  var auth = document.getElementById('sms_accept_charges');
  if (auth) auth.checked = false;
}

User.Settings.Mobile.FormSubmit = function()
{
  User.Settings.Mobile.Validate.MobileNumber();
  User.Settings.Mobile.Validate.Country();
  User.Settings.Mobile.Validate.Provider();
  User.Settings.Mobile.Validate.Auth();
  if (User.Settings.Errors.length == 0)
  {
    var mobile = new Ajax(User.Settings.Mobile.Updated);
    var url = "user/update_mobile";
    var params = "isajax=1";
    params += "&country="+document.getElementById('country').value.trim();
    params += "&sms_provider="+document.getElementById('sms_provider').value.trim();
    params += "&notification_sms="+document.getElementById('notification_sms').value.trim();
    params += "&sms_web_enabled="+((document.getElementById('sms_web_enabled').checked)?'1':'0');
    params += "&sms_accept_charges="+((document.getElementById('sms_accept_charges').checked)?'1':'0');
    DOM.Show('user_loading');
    mobile.sendPostRequest(url,params);
  }
  else
  {
    User.Settings.ShowErrors();
  }
  return false;
}

User.Settings.Mobile.Updated = function(response)
{
  if (response=='ok')
  {
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/mobile";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
}

User.Settings.Mobile.ResendConfirmation = function()
{
  function ret(response)
  {
    if (response=='ok')
    {
      DOM.Show('mobile_resent');
    }
    else alert("Unknown error:\n\n"+response);
  }

  var req = new Ajax(ret);
  var url = "user/resend_sms_confirmation";
  var params = "isajax=1";
  req.sendPostRequest(url,params);
  return false;
}

User.Settings.Mobile.RemoveMobile = function(redirect)
{
  function ret(response)
  {
    if (response=='ok')
    {
      if (redirect)
      {
        User.Settings.Mobile.ViewForm();
      }
      else
      {
        var page = new Ajax(User.Settings.ResponsePage);
        var url = "user/settings_response/mobileremoved";
        var params = "isajax=1";
        page.sendPostRequest(url,params);
      }
    }
    else alert("Unknown error:\n\n"+response);
  }

  var req = new Ajax(ret);
  var url = "user/remove_mobile";
  var params = "isajax=1";
  req.sendPostRequest(url,params);
  return false;
}

User.Settings.Mobile.ViewForm = function()
{
  DOM.Show('user_loading');
  var page = new Ajax(User.GetResults);
  var url = "user/settings/mobile";
  User.CloseCurrent();
  User.CurrentMain = "settings";
  User.CurrentSub = "mobile";
  User.OpenCurrent();
  var params = "isajax=1";
  page.sendPostRequest(url,params);
  return false;
}

User.Settings.Mobile.Validate = {};

User.Settings.Mobile.Validate.MobileNumber = function()
{
  var mobile_number = document.getElementById('notification_sms');
  if (mobile_number)
  {
    if (mobile_number.value.trim().match(/^[0-9]*$/))
    {
      return true;
    }
    else User.Settings.AddError('Please enter a valid mobile telephone number using the numbers only. No spaces, dashes or other punctuation');
  }
  else User.Settings.AddError('Unable to read mobile number');
  return false;
}

User.Settings.Mobile.Validate.Country = function()
{
  var country = document.getElementById('country');
  if (country)
  {
    if (country.value.trim() != "")
    {
      return true;
    }
    else User.Settings.AddError('Please select the country for your mobile service');
  }
  else User.Settings.AddError('Unable to read country');
  return false;
}

User.Settings.Mobile.Validate.Provider = function()
{
  var sms_provider = document.getElementById('sms_provider');
  if (sms_provider)
  {
    if (sms_provider.value.trim() != "")
    {
      return true;
    }
    else User.Settings.AddError('Please select the Provider for your mobile service');
  }
  else User.Settings.AddError('Unable to read Service Provider');
  return false;
}

User.Settings.Mobile.Validate.Auth = function()
{
  var auth = document.getElementById('sms_accept_charges');
  if (auth)
  {
    if (auth.checked)
    {
      return true;
    }
    else User.Settings.AddError('You must authorize Miio to send text messages to your phone');
  }
  else User.Settings.AddError('Unable to read Authorization');
  return false;
}

/******************************************************************************/

User.Settings.Notifications = {};

User.Settings.Notifications.SelectAll = function(which,ischecked)
{
  var div = document.getElementById('settings_content');
  if (div)
  {
    var checks = div.getElementsByTagName('input');
    for (var c=0;c<checks.length;c++)
    {
      if (checks[c].id.substr(0,which.length)==which) checks[c].checked = ischecked;
    }
    if (ischecked)
    {
      DOM.Show(which+'_unselect');
      DOM.Hide(which+'_select');
    }
    else
    {
      DOM.Hide(which+'_unselect');
      DOM.Show(which+'_select');
    }
  }
  return false;
}

User.Settings.Notifications.RestoreDefaults = function()
{
  var tbl = document.getElementById('notification_options');
  var checks = tbl.getElementsByTagName('input');
  for (var c=0;c<checks.length;c++)
  {
    if (checks[c].id.substr(0,9)=='dashboard') checks[c].checked = true;
    else checks[c].checked = false;
  }
  Forms.Check('email_new_follower');
  Forms.Check('email_follow_request');
  DOM.Show('dashboard_unselect');
  DOM.Hide('dashboard_select');
  DOM.Show('email_select');
  DOM.Hide('email_unselect');
  DOM.Show('sms_select');
  DOM.Hide('sms_unselect');
  User.Settings.Notifications.FormSubmit();
  return false;
}

User.Settings.Notifications.FormSubmit = function()
{
  try
  {
    var notifications = new Ajax(User.Settings.Notifications.Updated);
    var url = "user/update_notifications";
    var params = "isajax=1";
    var tbl = document.getElementById('notification_options');
    var checks = tbl.getElementsByTagName('input');
    for (var c=0;c<checks.length;c++)
    {
      params += '&'+checks[c].id+'='+((checks[c].checked)?'1':'0');
    }
    DOM.Show('user_loading');
    notifications.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Notifications.Updated = function(response)
{
  if (response=='ok')
  {
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/notifications";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else
  {
    DOM.Hide('user_loading');
    DOM.SetHTML('content_div',response);
    //alert("Error:\n\n"+response);
  }
}

/******************************************************************************/

User.Settings.Messages = {};

User.Settings.Messages.SelectAll = function(which,ischecked)
{
  var div = document.getElementById('settings_content');
  if (div)
  {
    var checks = div.getElementsByTagName('input');
    for (var c=0;c<checks.length;c++)
    {
      if (checks[c].id.substr(0,which.length)==which) checks[c].checked = ischecked;
    }
    if (ischecked)
    {
      DOM.Show(which+'_unselect');
      DOM.Hide(which+'_select');
    }
    else
    {
      DOM.Hide(which+'_unselect');
      DOM.Show(which+'_select');
    }
  }
  return false;
}

User.Settings.Messages.RestoreDefaults = function()
{
  var tbl = document.getElementById('notification_options');
  var checks = tbl.getElementsByTagName('input');
  for (var c=0;c<checks.length;c++)
  {
    checks[c].checked = false;
  }
  Forms.Check('email_public_message');
  Forms.Check('email_private_message');
  DOM.Show('email_select');
  DOM.Hide('email_unselect');
  DOM.Show('sms_select');
  DOM.Hide('sms_unselect');
  User.Settings.Messages.FormSubmit();
  return false;
}

User.Settings.Messages.FormSubmit = function()
{
  try
  {
    var notifications = new Ajax(User.Settings.Messages.Updated);
    var url = "user/update_messagesettings";
    var params = "isajax=1";
    var tbl = document.getElementById('notification_options');
    var checks = tbl.getElementsByTagName('input');
    for (var c=0;c<checks.length;c++)
    {
      params += '&'+checks[c].id+'='+((checks[c].checked)?'1':'0');
    }
    DOM.Show('user_loading');
    notifications.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Messages.Updated = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/message";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else
  {
    DOM.SetHTML('content_div',response);
  }
}

/******************************************************************************/

User.Settings.ProfilePhoto = {};

User.Settings.ProfilePhoto.URL = "";

User.Settings.ProfilePhoto.SubmitPreview = function()
{
  DOM.Show('user_loading');
}

User.Settings.ProfilePhoto.FormSubmit = function()
{
  if (User.Settings.ProfilePhoto.URL.trim() == "")
  {
    alert("Please select and preview a photo");
  }
  else
  {
    try
    {
      var photo = new Ajax(User.Settings.ProfilePhoto.Updated);
      var url = "user/update_profile_photo";
      var params = "isajax=1";
      params += "&photo="+User.Settings.ProfilePhoto.URL.trim();
      photo.sendPostRequest(url,params);
    }
    catch (e) { alert ("Unable to read profile photo form"); }
  }
}

User.Settings.ProfilePhoto.UploadDone = function()
{
  function resize_pic()
  {
    DOM.Show('profile_photo');
    DOM.Show(photo.id);
    DOM.Hide('photo_upload_form');
    DOM.Hide('profile_photo_form');
    DOM.Hide('edit_photo_text');
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
    photo.style.height = 'auto';
    photo.style.width = 'auto';
    photo.onload = resize_pic;
    photo.src = HTTP_BASE + "file_temp/"+User.Settings.ProfilePhoto.URL;
  }
}

User.Settings.ProfilePhoto.UploadError = function(error)
{
  DOM.Hide('user_loading');
  if (error=='No File') alert("Please select a file to upload before clicking 'Preview'.");
  else alert(error);
}

User.Settings.ProfilePhoto.Change = function()
{
  DOM.Hide('profile_photo');
  DOM.Hide('submit');
  DOM.Show('photo_upload_form');
  return false;
}

User.Settings.ProfilePhoto.Cancel = function(resetphoto)
{
  var photo = document.getElementById('profilephoto');
  if (resetphoto)
  {
    if (photo)
    {
      photo.style.height = 'auto';
      photo.style.width = 'auto';
      photo.onload = resize_pic;
      photo.src = HTTP_BASE+'profile_photos/'+resetphoto;
    }
    DOM.Show('profile_photo_delete');
  }
  DOM.Show('profile_photo');
  DOM.Hide('profile_photo_cancel_upload');
  DOM.Hide('submit');
  DOM.Hide('photo_upload_form');
  return false;
}

User.Settings.ProfilePhoto.Updated = function(response)
{
  function resize_pic()
  {
    DOM.Show(photo.id);
    var w = photo.offsetWidth;
    var h = photo.offsetHeight;
    DOM.Hide(photo.id);
    if (w>80 || h>80)
    {
      var adj = 1;
      if (w > h)
      {
        adj = 80/w;
      }
      else
      {
        adj = 80/h;
      }
      photo.style.height = (Math.floor(h*adj))+'px';
      photo.style.width = (Math.floor(w*adj))+'px';
    }
    DOM.Show(photo.id);
  }

  var resp = response.substr(0,2);
  if (resp=='ok')
  {
    photourl = response.substr(2);
    var photo = document.getElementById('user_photo');
    if (photo)
    {
      photo.src = HTTP_BASE+'avatars/'+photourl;
      DOM.Show(photo.id);
    }

    var photolink = document.getElementById('user_photo_link');
    if (photolink) photolink.href = "members/profile/"+USER_ID;

    var mf_photo = document.getElementById('messageform_photo');
    if (mf_photo) mf_photo.src = HTTP_BASE+'avatars/'+photourl;

    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/profilephoto";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
  User.Settings.ProfilePhoto.URL = "";
}

User.Settings.ProfilePhoto.Delete = function()
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      var photo = document.getElementById('profilephoto');
      if (photo)
      {
        photo.style.height = 'auto';
        photo.style.width = 'auto';
        photo.onload = null;
        photo.src = "";
        DOM.Hide('profile_photo');
        DOM.Hide('profile_photo_cancel_upload');
        DOM.Hide('profile_photo_delete');
        DOM.Show('submit');
        DOM.Show('photo_upload_form');
      }

      var mf_photo = document.getElementById('messageform_photo');
      if (mf_photo) mf_photo.src = HTTP_BASE+"avatars/default.jpg";

      var profilephoto = document.getElementById('user_photo');
      if (profilephoto)
      {
        profilephoto.src = HTTP_BASE+"avatars/default.jpg";
        //DOM.Hide('user_photo');
      }
      var photolink = document.getElementById('user_photo_link');
      photolink.href = "user#settings/profilephoto/";
    }
    else
    {
      alert("Error:\n\n"+response);
    }
  }

  var str = "Are you sure you want to delete your profile photo?";
  if (confirm(str))
  {
    var page = new Ajax(ret);
    var url = "user/delete_profile_photo";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  return false;
}

/******************************************************************************/

User.Settings.Albums = {};

User.Settings.Albums.Edit = function(id)
{
  function changeheader()
  {
    DOM.Show('edit_head_'+id);
    DOM.Hide('view_head_'+id);
    DOM.Open('edit_'+id);
  }

  if (id=='new')
  {
    DOM.Hide('openedit_new');
    DOM.Open('edit_new');
  }
  else
  {
    DOM.Close('album_grid_'+id,null,changeheader);
  }
  return false;
}

User.Settings.Albums.Count = function(e,obj,divid)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,DESCRIPTIONLENGTH);
    obj.scrollTop = st;
  }

  if (window.event) key = window.event.keyCode;
  else key = e.which;

  var counter = document.getElementById(divid);
  if (counter)
  {
    if (obj.value.length > DESCRIPTIONLENGTH) disallow();
    else counter.innerHTML = DESCRIPTIONLENGTH-obj.value.length;
  }
}

User.Settings.Albums.Cancel = function(id,newphotos)
{
  function openedit()
  {
    DOM.Show('openedit_'+id);
  }

  function changeheader()
  {
    DOM.Hide('edit_head_'+id);
    DOM.Show('view_head_'+id);
    DOM.Open('album_grid_'+id);
  }

  function openphotos()
  {
    DOM.Show('viewing_album_'+id);
    DOM.Hide('editing_album_'+id);
    DOM.Open('album_photos_'+id);
  }

  if (newphotos) DOM.Close('new_photos_'+id,null,openphotos);
  else if (id=='new') DOM.Close('edit_new',null,openedit);
  else DOM.Close('edit_'+id,null,changeheader);
  return false;
}

User.Settings.Albums.ToggleAlbum = function(id,show)
{
  function done()
  {
    if (show)
    {
      DOM.Show('hide_album_'+id);
      DOM.Hide('show_album_'+id);
    }
    else
    {
      DOM.Show('show_album_'+id);
      DOM.Hide('hide_album_'+id);
      DOM.SetClass('albumcontainer_'+id,'album');
    }
  }

  if (show)
  {
    DOM.Open('album_grid_'+id,null,done);
  }
  else DOM.Close('album_grid_'+id,null,done);
  return false;
}

User.Settings.Albums.Add = function(id)
{
  function openform()
  {
    DOM.Hide('viewing_album_'+id);
    DOM.Show('editing_album_'+id);
    DOM.Open('new_photos_'+id);
  }

  DOM.Close('album_photos_'+id,null,openform)
  return false;
}

User.Settings.Albums.Enable = function(file,album,photo)
{
  if (file.value != "")
  {
    var nextinput = document.getElementById('image_'+album+'_'+photo);
    if (nextinput) nextinput.disabled = false;
    file.disabled = true;
  }
}

User.Settings.Albums.FormSubmit = function(album)
{
  var albumtitle = document.getElementById('title_'+album);
  if (albumtitle)
  {
    if (albumtitle.value.trim()=="")
    {
      alert("Please enter a title for this album.");
      return false;
    }
    if (album=='new' && document.getElementById('image_'+album+'_1').value == "")
    {
      alert("Please select at least one photo to upload to this album.");
      return false;
    }
    for (var p=1;p<10;p++)
    {
      var file = document.getElementById('image_'+album+'_'+p);
      if (file) file.disabled = false;
    }
    DOM.Show('user_loading');
    return true;
  }
  else
  {
    alert('Unable to read form');
    return false;
  }
}

User.Settings.Albums.UploadDone = function(response)
{
  DOM.Hide('user_loading');
  var resp = response.jsonParse();
  if (resp.errors.length>0)
  {
    var str = "There were errors uploading your pictures:\n\n";
    for (var e=0;e<resp.errors.length;e++)
    {
      str += resp.errors[e] + "\n";
    }
    alert(str);
  }
  var page = new Ajax(User.Settings.ResponsePage);
  var url = "user/settings/albums?id="+resp.id;
  var params = "isajax=1";
  page.sendPostRequest(url,params);
}

User.Settings.Albums.UploadError = function(err)
{
  DOM.Hide('user_loading');
  if (err=='too_many') alert("You have exceeded the number of available albums. You must first delete one if you wish to create a new one.");
  else if (err=='bad_id') alert("The album id is invalid.");
  else alert ("Unknown error:\n\n"+err);
}

User.Settings.Albums.DeletePhoto = function(album,photo,phototitle,pid)
{
  function ret(response)
  {
    if (response=='ok')
    {
      try
      {
        var p = pid.parentNode.id.split('_');
        var idx = p[2];
        var max_i = 0;
        if (idx<10)
        {
          for (var ix=idx;ix<10;ix++)
          {
            var i0 = album+'_'+ix;
            var i1 = album+'_'+(parseInt(ix)+1);
            if (DOM.GetValue('i_'+i1)>0)
            {
              DOM.SetHTML('t_'+i0,DOM.GetHTML('t_'+i1));
              DOM.SetHTML('p_'+i0,DOM.GetHTML('p_'+i1));
              DOM.SetHTML('d_'+i0,DOM.GetHTML('d_'+i1));
              DOM.SetValue('i_'+i0,DOM.GetValue('i_'+i1));
              max_i = ix;
              document.getElementById('pic_'+i0).src = document.getElementById('pic_'+i1).src;
              document.getElementById('image_'+i0).disabled = true;
            }
            else
            {
              DOM.SetHTML('t_'+i0,"&nbsp;");
              DOM.SetHTML('p_'+i0,ix);
              DOM.SetHTML('d_'+i0,"");
              DOM.SetValue('i_'+i0,0);
              DOM.Hide('pic_'+i0);
              DOM.Show('image_'+i0);
              document.getElementById('image_'+i0).disabled = true;
            }
          }
          if (DOM.GetValue('i_'+album+'_'+idx)<1) var imginput = idx;
          else var imginput = parseInt(max_i) + 1;
          document.getElementById('image_'+album+'_'+(imginput)).disabled = false;
          imginput++;
          if (imginput < 10) document.getElementById('image_'+album+'_'+imginput).disabled = true;
          DOM.Show('add_photo_link_'+album);
        }
      }
      catch(e)
      {

      }
    }
    else
    {
      alert('Error: '+response);
    }
  }

  if (confirm("Really delete '"+phototitle+"' from the album?"))
  {
    var del = new Ajax(ret);
    var url = "user/delete_photo";
    var params = "isajax=1";
    params += "&album="+album;
    params += "&photo="+photo;
    del.sendPostRequest(url,params);
  }
  return false;
}

User.Settings.Albums.Update = function(album)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      var page = new Ajax(User.Settings.ResponsePage);
      var url = "user/settings/albums?id="+response.substr(2);
      var params = "isajax=1";
      page.sendPostRequest(url,params);
    }
    else
    {
      alert('Error: '+response);
    }
  }

  var albumtitle = document.getElementById('title_'+album);
  if (albumtitle)
  {
    if (albumtitle.value.trim()=="")
    {
      alert("Please enter a title for this album.");
      return false;
    }
    var update = new Ajax(ret);
    var url = "user/update_album";
    var params = "isajax=1";
    params += "&album_id="+album;
    params += "&title="+albumtitle.value.trim();
    params += "&description="+document.getElementById('description_'+album).value.trim();
    DOM.Show('user_loading');
    update.sendPostRequest(url,params);
  }
  else
  {
    alert('Unable to read form');
  }
  return false;
}

User.Settings.Albums.DeleteAlbum = function(album,albumtitle)
{
  function ret(response)
  {
    if (response=="ok")
    {
      var page = new Ajax(User.Settings.ResponsePage);
      var url = "user/settings_response/albums?action=deletealbum";
      var params = "isajax=1";
      page.sendPostRequest(url,params);
    }
    else
    {
      alert("An unknown error occurred:\n\n"+response);
    }
  }

  if (confirm("Really delete '"+albumtitle+"' and all its photos?"))
  {
    var del = new Ajax(ret);
    var url = "user/delete_album";
    var params = "isajax=1";
    params += "&album="+album;
    del.sendPostRequest(url,params);
  }
  return false;
}

User.Settings.Albums.ReturnToAlbums = function()
{
  DOM.Show('user_loading');
  var page = new Ajax(User.GetResults);
  var url = "user/settings/albums";
  var params = "isajax=1";
  User.CloseCurrent();
  User.CurrentMain = 'settings';
  User.CurrentSub = 'albums';
  User.CurrentSubSub = "";
  User.OpenCurrent();
  page.sendPostRequest(url,params);
  return false;
}

/******************************************************************************/

User.Settings.Feed = {};

User.Settings.Feed.SaveTwitterToken = function(token, secret, id, sn)
{
  try
  {
    var pm = new Ajax(User.Settings.Feed.Kill);
    var url = "user/update_twitter";
    var params = "isajax=1";
    params += "&savetwitter=1";
    params += "&twitter_token="+(token);
    params += "&twitter_secret="+(secret);
    params += "&twitter_sn="+(sn);
    params += "&twitter_id="+(id);
    DOM.Show('user_loading');
    pm.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Feed.DestroyTwitter = function()
{
  try
  {
    var pm = new Ajax(User.Settings.Feed.TwitterUpdated);
    var url = "user/update_twitter";
    var params = "isajax=1";
    params += "&destroy=1";
    DOM.Show('user_loading');
    pm.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Feed.EditTwitter = function()
{
  DOM.Hide('tview');
  DOM.Show('tedit');
  DOM.Hide('tvfooter');
  DOM.Show('tefooter');
}

User.Settings.Feed.FormSubmit = function(opt)
{
  var pm = new Ajax(User.Settings.Feed.TwitterUpdated);
  var url = "user/update_twitter";
  var params = "isajax=1";
  params += "&updatetwitter=1";
  params += "&push="+(document.getElementById('twitter_push').checked ? '1':'0');
  params += "&share="+(document.getElementById('twitter_share').checked ? '1':'0');
  params += "&reply="+(document.getElementById('twitter_reply').checked ? '1':'0');
  params += "&twitter_action="+opt;
  DOM.Show('user_loading');
  pm.sendPostRequest(url,params);
  return false;
}

User.Settings.Feed.TwitterUpdated = function(response)
{
  var page = new Ajax(User.Settings.ResponsePage);
  var params = "isajax=1";
  switch (response)
  {
    case "destroyed"  : var url = "user/settings_response/remove_twitter"; break;
    case "updated"    : var url = "user/settings_response/update_twitter"; break;
    case "added"      : var url = "user/settings_response/added_twitter"; break;
    default           : var err = true;
  }
  if (err)
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
  else
  {
    page.sendPostRequest(url,params);
  }
}


User.Settings.Feed.Kill = function(response)
{
  if (response == "added")
  {
    self.close();
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
}

/*---BEGIN DELETE CHECK--------------------------------------*/
/*-----------------------------------------------------------*/
/*-----------------------------------------------------------*/

User.Settings.Feed.twitterCheck = function()
{
  alert("Error: Bad call to User.Settings.Feed.twitterCheck()");
  return false;
  try
  {
    var pm = new Ajax(User.Settings.Feed.twitterChecked);
    var url = "user/check_twitter";
    var params = "isajax=1";
    params += "&check=1";
    DOM.Show('user_loading');
    pm.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Feed.rssCheck = function()
{
  alert("Error: Bad call to User.Settings.Feed.rssCheck()");
  return false;
  try
  {
    var pm = new Ajax(User.Settings.Feed.rssChecked);
    var url = "user/check_rss";
    var params = "isajax=1";
    params += "&check=1";
    DOM.Show('user_loading');
    pm.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Feed.rssChecked = function(response)
{
  alert("Error: Bad call to User.Settings.Feed.rssChecked()");
  return false;
  if (response == "ok")
  {
  DOM.Hide('user_loading');
    alert("Checked");
  }
  else
  {
    DOM.Hide('user_loading');

  }
}

User.Settings.Feed.twitterChecked = function(response)
{
  alert("Error: Bad call to User.Settings.Feed.twitterChecked()");
  return false;
  if (response == "ok")
  {
  DOM.Hide('user_loading');
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error: " + response);
  }
}

/*---END DELETE CHECK----------------------------------------*/
/*-----------------------------------------------------------*/
/*-----------------------------------------------------------*/

User.Settings.Feed.rssTest = function()
{
  try
  {
    var pm = new Ajax(User.Settings.Feed.rssValidate);
    var url = "user/check_rss";
    var params = "isajax=1";
    params += "&rss_url="+document.getElementById('rss_url').value;
    DOM.Show('user_loading');
    pm.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Feed.rssValidate = function(response)
{
  if (response == "ok")
  {
  DOM.Hide('user_loading');
    alert("Feed validated successfully!");
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Feed is not valid!");
  }
}

User.Settings.Feed.rssSave = function(feed)
{
  try
  {
    var pm = new Ajax(User.Settings.Feed.rssUpdated);
    var url = "user/save_"+feed;
    var params = "isajax=1";
    params += "&"+feed+"_url="+document.getElementById(feed+'_url').value;
    DOM.Show('user_loading');
    pm.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Feed.EditFeed = function()
{
  DOM.Hide('feedlist');
  DOM.Show('editfeed');
  DOM.Hide('footer');
  DOM.Show('editfooter');
}

User.Settings.Feed.DestroyFeed = function(feed_url)
{
  try
  {
    var pm = new Ajax(User.Settings.Feed.rssUpdated);
    var url = "user/destroy_feed";
    var params = "isajax=1";
    params += "&rss_url="+feed_url;
    DOM.Show('user_loading');
    pm.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Feed.rssUpdate = function(feed)
{
  try
  {
    var pm = new Ajax(User.Settings.Feed.rssUpdated);
    var url = "user/save_"+feed;
    var params = "isajax=1";
    params += "&"+feed+"_url="+document.getElementById(feed+'_url').value;
    DOM.Show('user_loading');
    pm.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}



User.Settings.Feed.rssSave = function(feed)
{
  try
  {
    var pm = new Ajax(User.Settings.Feed.rssUpdated);
    var url = "user/save_"+feed;
    var params = "isajax=1";
    params += "&"+feed+"_url="+document.getElementById(feed+'_url').value;
    DOM.Show('user_loading');
    pm.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

User.Settings.Feed.rssUpdated = function(response)
{
  if (response == "destroyed")
  {
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/remove_feed";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else if (response == "updated")
  {
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/update_feed";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else if (response == "added")
  {
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/added_feed";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
}

/******************************************************************************/

User.Settings.Username = {};

User.Settings.Username.CheckName = function()
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response)
    {
      if (response=='ok')
      {
        DOM.Hide('name_invalid');
        DOM.Show('name_valid');
      }
      else
      {
        DOM.Hide('name_valid');
        DOM.Show('name_invalid');
      }
    }
  }

  var username = document.getElementById('settings_username');
  if (username)
  {
    if (username.value.trim().match(/^[_a-zA-Z0-9-]{3,20}$/))
    {0
      var un = new Ajax(ret);
      var url = "ajax/check_name";
      var params = "isajax=1";
      params += "&name="+username.value.trim();
      DOM.Show('user_loading');
      un.sendPostRequest(url,params);
    }
    else
    {
      alert('Please enter a valid user name: 3-20 characters, use only letters, numbers, underscore, or hyphen. No spaces. No period');
    }
  }
  else alert('Error: Unable to read username');
  return false;
}

User.Settings.Username.FormSubmit = function()
{
  var username = document.getElementById('settings_username');
  if (username)
  {
    if (username.value.trim().match(/^[_a-zA-Z0-9-\.]{3,20}$/))
    {
      User.Settings.Username.Newname = username.value.trim();
      var un = new Ajax(User.Settings.Username.Updated);
      var url = "user/update_username";
      var params = "isajax=1";
      params += "&username="+username.value.trim();
      DOM.Show('user_loading');
      un.sendPostRequest(url,params);
    }
    else
    {
      alert('Please enter a valid user name: 3-20 characters, use only letters, numbers, underscore, or hyphen. No spaces. No period.');
    }
  }
  else alert('Error: Unable to read username');
  return false;
}

User.Settings.Username.Updated = function(response)
{
  if (response=='ok')
  {
    var greeting = document.getElementById('header_username');
    if (greeting) greeting.innerHTML = User.Settings.Username.Newname;
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/username";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else if (response=='taken')
  {
    DOM.Hide('user_loading');
    alert('Sorry, that username is assigned to another Miio member.');
  }
  else if (response=='invalid')
  {
    DOM.Hide('user_loading');
    alert('Please enter a valid user name: 3-20 characters, use only letters, numbers, underscore, or hyphen. No spaces. No period.');
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
  User.Settings.Username.Newname = null;
}

/******************************************************************************/

User.Settings.Passwd = {};

User.Settings.Passwd.ResetPW = function()
{
  function ret(response)
  {
    if (response=='ok')
    {
      DOM.Show('password_reset');
      DOM.Hide('reset_password');
    }
    else
    {
      alert("Error:\n\n"+response);
    }
  }

  var msg = "Miio will send an email to your registered email address with a link you can use to reset your password. This link must be used within 24 hours.";
  if (confirm(msg))
  {
    var page = new Ajax(ret);
    var url = "user/reset_password";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  return false;
}

User.Settings.Passwd.FormSubmit = function()
{
  var oldpw = document.getElementById('settings_currentpw');
  var password = document.getElementById('settings_newpw');
  var password_confirm = document.getElementById('settings_confirmpw');

  if (oldpw && password && password_confirm)
  {
    if (oldpw.value.trim() == "") alert("Please enter your current password.");
    else if (password.value.trim() != password_confirm.value.trim()) alert("Passwords do not match.");
    else if (!password.value.trim().match(/^[_!%a-zA-Z0-9-\.\$\*]{5,20}$/)) alert('Please enter a valid password: 5-20 characters using A-Z,a-z,0-9,!,$,%,*');
    else
    {
      var pw = new Ajax(User.Settings.Passwd.Updated);
      var url = "user/update_password";
      var params = "isajax=1";
      params += "&oldpw="+oldpw.value.trim();
      params += "&password="+password.value.trim();
      params += "&password_confirm="+password_confirm.value.trim();
      DOM.Show('user_loading');
      pw.sendPostRequest(url,params);
    }
  }
  else alert('Error: Unable to read password');
  return false;
}

User.Settings.Passwd.Updated = function(response)
{
  if (response=='ok')
  {
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/password";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else if (response=='wrongpw')
  {
    DOM.Hide('user_loading');
    alert('Current password is incorrect.');
  }
  else if (response=='nomatch')
  {
    DOM.Hide('user_loading');
    alert('Passwords do not match.');
  }
  else if (response=='invalid')
  {
    DOM.Hide('user_loading');
    alert('Please enter a valid password: 5-20 characters using A-Z,a-z,0-9,!,$,%,*');
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
}

/******************************************************************************/

User.Settings.Email = {};

User.Settings.Email.FormSubmit = function()
{
  var emailaddress = document.getElementById('settings_email');
  if (emailaddress)
  {
    if (emailaddress.value.trim().match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/))
    {
      var email = new Ajax(User.Settings.Email.Updated);
      var url = "user/update_email";
      var params = "isajax=1";
      params += "&email="+emailaddress.value.trim();
      DOM.Show('user_loading');
      email.sendPostRequest(url,params);
    }
    else
    {
      alert('Please enter a valid email address');
    }
  }
  else alert('Error: Unable to read email address');
  return false;
}

User.Settings.Email.Updated = function(response)
{
  if (response=='ok')
  {
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/emailaddress";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else if (response=='taken')
  {
    DOM.Hide('user_loading');
    alert('Sorry, that address is assigned to another Miio member.');
  }
  else if (response=='invalid')
  {
    DOM.Hide('user_loading');
    alert('Please enter a valid email address');
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
}

/******************************************************************************/

User.Settings.Refresh = {};

User.Settings.Refresh.UpdateForm = function(obj)
{
  var refresh = document.getElementById('settings_refresh');
  var units = document.getElementById('settings_refresh_unit');
  var manual = document.getElementById('settings_refresh_mode_manual');
  var auto = document.getElementById('settings_refresh_mode_auto');
  if (obj==manual)
  {
    refresh.disabled = true;
    units.disabled = true;
  }
  else if (obj==auto)
  {
    refresh.disabled = false;
    units.disabled = false;
  }
  else if (obj==refresh)
  {
    if (!refresh.value.isNumeric() || refresh.value==0 )
    {
      alert('Please enter a number greater than 0');
      refresh.focus();
    }
  }
}

User.Settings.Refresh.FormSubmit = function()
{
  var refresh = document.getElementById('settings_refresh');
  var units = document.getElementById('settings_refresh_unit');
  var manual = document.getElementById('settings_refresh_mode_manual');
  if (!refresh.value.isNumeric() || refresh.value==0 )
  {
    alert('Please enter a number greater than 0 or select Manual refresh');
    refresh.focus();
    return false;
  }
  else
  {
    var ref = new Ajax(User.Settings.Refresh.Updated);
    var url = "user/update_refresh";
    var params = "isajax=1";
    if (manual.checked) params += "&rate=0";
    else if (units.value=='min') params += "&rate="+(refresh.value*60);
    else params += "&rate="+refresh.value;
    DOM.Show('user_loading');
    ref.sendPostRequest(url,params);
  }
  return false;
}

User.Settings.Refresh.Updated = function(response)
{
  if (response.substr(0,2)=='ok')
  {
    var rate = response.substr(2);
    if (rate>0)
    {
      REFRESH_MODE = 'a';
      MESSAGE_REFRESH = rate * 1000;
    }
    else
    {
      REFRESH_MODE = 'm';
      MESSAGE_REFRESH = 5000;
    }
    var page = new Ajax(User.Settings.ResponsePage);
    var url = "user/settings_response/refreshrate";
    var params = "isajax=1";
    page.sendPostRequest(url,params);
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
}

/******************************************************************************/

User.Settings.CancelAccount = {};

User.Settings.CancelAccount.FormSubmit = function()
{
  var cancel = document.getElementById('cancel_account_text');
  if (cancel)
  {
    if (cancel.value.toLowerCase()=="cancel")
    {
      if (confirm("Are you sure you want to cancel and permanently delete your Miio account?"))
      {
        var page = new Ajax(User.Settings.CancelAccount.Updated);
        var url = "user/cancel_account";
        var params = "isajax=1";
        page.sendPostRequest(url,params);
      }
    }
    else alert("Enter 'cancel' to cancel your account");
  }
  else alert('Unable to read cancel confirmation');
  return false;
}

User.Settings.CancelAccount.Updated = function(response)
{
  if (response=='ok')
  {
    alert("Your acount has been canceled.");
    location = "forms/logout";
  }
  else
  {
    DOM.Hide('user_loading');
    alert("Error:\n\n"+response);
  }
}

/******************************************************************************/

