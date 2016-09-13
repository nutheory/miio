var Signup = {};

Signup.Init = function()
{
  Signup.GetPage = new Ajax(Signup.GotPage);
  var step = location.hash.substr(1);
  if (step>1)
  {
    DOM.SetHTML('setup_content','');
    Signup.Step = parseInt(step);
    Signup.LoadStep(step);
  }
  else
  {
    Signup.Step = 1;
  }
  DOM.Show('setup_content');
  Signup.CurrentLocation = location+"";
  setTimeout(Signup.WentBack,100);
}

Signup.GotPage = function(response)
{
  DOM.Hide('user_loading');
  DOM.SetHTML('setup_content',response);
  setTimeout(Signup.InitLocation,100);
}

Signup.WentBack = function()
{
  if (Signup.CurrentLocation != location)
  {
    var step = location.hash.substr(1);
    Signup.Step = parseInt(step);
    Signup.LoadStep(step);
  }
  setTimeout(Signup.WentBack,100);
}

Signup.NextStep = function()
{
  Signup.LoadStep(parseInt(Signup.Step)+1);
  return false;
}

Signup.LastStep = function()
{
  Signup.LoadStep(parseInt(Signup.Step)-1);
  return false;
}

Signup.LoadStep = function(step)
{
  switch (parseInt(step))
  {
    case 2: var url = "signup/find_members"; break;
    case 3: var url = "signup/profile_info"; break;
    case 4: var url = "signup/profile_photo"; break;
    default: location = HTTP_BASE + "signup/setup"; return;
  }
  var loc = HTTP_BASE + "signup/setup#"+step;
  location = loc;
  Signup.CurrentLocation = location+"";
  for (var i=1;i<5;i++) DOM.SetClass("step_"+i,"inactive");
  DOM.SetClass("step_"+step,"active");
  if (step<2) return;
  DOM.SetClass("step_"+(step-1),"last");
  Signup.Step = step;
  var params = "isajax=1";
  DOM.Show('user_loading');
  Signup.GetPage.sendPostRequest(url,params);
}

Signup.FindMembers = function()
{
  var url = "signup/found_members"
  var params = "isajax=1";
  params += "&tags="+DOM.GetValue('member_interests');
  Signup.GetPage.sendPostRequest(url,params);
}

Signup.SelectAll = function(obj,which)
{
  var list = document.getElementById(which+'_results');
  var checks = list.getElementsByTagName('input');
  for (var c=0;c<checks.length;c++) checks[c].checked = obj.checked;
  DOM.SetHTML('label_select_all_'+which,(obj.checked?'Unselect All':'Select All'));
}

Signup.Follow = function()
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok') Signup.NextStep();
    else alert(response);
  }
  
  var matchlist = document.getElementById('match_results');
  var randlist = document.getElementById('rand_results');
  var list = [];
  if (matchlist)
  {
    var checks = matchlist.getElementsByTagName('input');
    for (var c=0;c<checks.length;c++) if (checks[c].checked) list.push(checks[c].value);
  }
  if (randlist)
  {
    var checks = randlist.getElementsByTagName('input');
    for (var c=0;c<checks.length;c++) if (checks[c].checked) list.push(checks[c].value);
  }
  if (list.length>0)
  {
    var follow = new Ajax(ret);
    var url = "signup/follow_members";
    var params = "isajax=1";
    params += "&follow="+list;
    follow.sendPostRequest(url,params);
  }
  else
  {
    if (confirm("You didn't select anyone! Do you want to move on without following anyone?"))
    {
      Signup.NextStep();
    }
  }
  return false;
}

Signup.InitLocation = function()
{
  Lib.InitLocation(Signup,countries);
}

Signup.ChangeCountry = function(obj)
{
  Lib.ChangeCountry(Signup,obj);
}

Signup.ChangeState = function(obj,country)
{
  Lib.ChangeStage(Signup,obj);
}

Signup.Count = function(e,obj,divid)
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

Signup.SaveProfile = function()
{
  try
  {
    var profile = new Ajax(Signup.ProfileSaved);
    var url = "user/update_profile";
    var params = "isajax=1";
    
    params += "&first_name="+document.getElementById('first_name').value.trim();
    params += "&last_name="+document.getElementById('last_name').value.trim();
    params += "&show_name="+((document.getElementById('show_name').checked)?'1':'0');
    params += "&description="+document.getElementById('description').value.trim();
    params += "&day="+document.getElementById('day').value;
    params += "&month="+document.getElementById('month').value;
    params += "&year="+document.getElementById('year').value;
    if (document.getElementById('male').checked) params += "&gender=m";
    else if (document.getElementById('female').checked) params += "&gender=f";
    params += "&ethnicity="+document.getElementById('ethnicity').value;
    params += "&country="+document.getElementById('country').value.trim();
    params += "&state="+document.getElementById('state').value.trim();
    params += "&city="+document.getElementById('city').value.trim();
    params += "&website="+document.getElementById('website').value.trim();
    params += "&lf_activity_partners="+((document.getElementById('lf_activity_partners').checked)?'1':'0');
    params += "&lf_chatting="+((document.getElementById('lf_chatting').checked)?'1':'0');
    params += "&lf_dating="+((document.getElementById('lf_dating').checked)?'1':'0');
    params += "&lf_friends="+((document.getElementById('lf_friends').checked)?'1':'0');
    params += "&lf_sharing="+((document.getElementById('lf_sharing').checked)?'1':'0');
    params += "&lf_whatever="+((document.getElementById('lf_whatever').checked)?'1':'0');
    params += "&lf_male="+((document.getElementById('lf_male').checked)?'1':'0');
    params += "&lf_female="+((document.getElementById('lf_female').checked)?'1':'0');
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

Signup.ProfileSaved = function(response)
{
  if (response=='ok') Signup.NextStep();
  else alert(response);
}

Signup.ProfilePhoto = {};

Signup.ProfilePhoto.URL = "";

Signup.ProfilePhoto.SubmitPreview = function()
{
  DOM.Show('user_loading');
}

Signup.ProfilePhoto.FormSubmit = function()
{
  if (Signup.ProfilePhoto.URL.trim() == "")
  {
    alert("Please select and preview a photo");
  }
  else
  {
    try
    {
      var photo = new Ajax(Signup.ProfilePhoto.Updated);
      var url = "user/update_profile_photo";
      var params = "isajax=1";
      params += "&photo="+Signup.ProfilePhoto.URL.trim();
      photo.sendPostRequest(url,params);
    }
    catch (e) { alert ("Unable to read profile photo form"); }
  }
}

Signup.ProfilePhoto.Updated = function(response)
{
  DOM.Hide('user_loading');
  var resp = response.substr(0,2);
  if (resp=='ok')
  {
    var d = new Date();
    photourl = response.substr(2);
    var photo = document.getElementById('user_photo');
    if (photo)
    {
      photo.src = photourl+"?x="+d.getTime();
      DOM.Show(photo.id);
    }
    location = "user";
  }
  else
  {
    alert("Error:\n\n"+response);
  }
}

Signup.ProfilePhoto.UploadDone = function()
{
  function resize_pic()
  {
    DOM.Hide('profile_photo_form');
    DOM.Show('profile_photo_confirm');
    DOM.Hide('user_loading');
  }
  
  var photo = document.getElementById('profilephoto');
  if (photo)
  {
    var d = new Date();
    photo.style.height = 'auto';
    photo.style.width = 'auto';
    photo.onload = resize_pic;
    photo.src = HTTP_BASE + "file_temp/"+Signup.ProfilePhoto.URL+"?x="+d.getTime();
  }
}

Signup.ProfilePhoto.UploadError = function(error)
{
  DOM.Hide('user_loading');
  if (error=='No File') alert("Please select a file to upload before clicking 'Preview'.");
  else alert(error);
}

Signup.ProfilePhoto.Change = function()
{
  DOM.Show('profile_photo_form');
  DOM.Hide('profile_photo_confirm');
  return false;
}

Signup.ProfilePhoto.Skip = function()
{
  var str = "Are you sure you want to skip uploading your profile photo?";
  if (confirm(str))
  {
    location = "user";
  }
  return false;
}