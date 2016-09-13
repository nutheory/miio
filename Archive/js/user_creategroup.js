User.CreateGroup = {};

User.CreateGroup.Errors = [];

User.CreateGroup.PhotoURL = "";

User.CreateGroup.Init = function()
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
  Lib.InitLocation(User.CreateGroup,countries,states,cities);
  var head = document.getElementById('content_header');
  if (head)
  {
    var htm = "Create a Group";
    head.innerHTML = htm;
  }
  User.CreateGroup.ListOpen = false;
}

User.CreateGroup.AddError = function(message)
{
  User.CreateGroup.Errors.push(message);
}

User.CreateGroup.ShowErrors = function()
{
  var str = "There are errors in your form. Please check the following and try again:\n\n";
  for (var err in User.CreateGroup.Errors)
  {
    str += "- " + User.CreateGroup.Errors[err] + ".\n";
  }
  alert(str);
  User.CreateGroup.Errors = [];
}

User.CreateGroup.ChangeCountry = function(obj)
{
  Lib.ChangeCountry(User.CreateGroup,obj);
}

User.CreateGroup.ChangeState = function(obj,country)
{
  Lib.ChangeState(User.CreateGroup,obj);
}

User.CreateGroup.TagCount = function(e,obj)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,TAGLENGTH);
    obj.scrollTop = st;
  }

  if (window.event) key = window.event.keyCode;
  else key = e.which;

  var counter = document.getElementById('tag_count');
  if (counter)
  {
    if (obj.value.length > TAGLENGTH) disallow();
    else counter.innerHTML = TAGLENGTH-obj.value.length;
  }
}

/******************************************************************************/

User.CreateGroup.CheckName = function()
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      DOM.Show('name_valid');
      DOM.Hide('name_invalid');
    }
    else
    {
      DOM.Hide('name_valid');
      DOM.Show('name_invalid');
    }
  }

  User.CreateGroup.Validate.Name();
  if (User.CreateGroup.Errors.length>0)
  {
    alert(User.CreateGroup.Errors[0]);
    User.CreateGroup.Errors = [];
  }
  else
  {
    var params = "isajax=1";
    params += "&name="+document.getElementById('group_name').value.trim();
    var group = new Ajax(ret);
    DOM.Show('user_loading');
    group.sendPostRequest('ajax/check_name',params);
  }
  return false;
}

User.CreateGroup.Count = function(e,obj,divid)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,GROUPLENGTH);
    obj.scrollTop = st;
  }

  if (window.event) key = window.event.keyCode;
  else key = e.which;

  var counter = document.getElementById(divid);
  if (counter)
  {
    if (key==13)
    {
      obj.value = obj.value.replace(/\n/g,'');
      var submitbtn = document.getElementById('group_submit');
      if (submitbtn) submitbtn.click();
    }
    else if (obj.value.length > GROUPLENGTH) disallow();
    else counter.innerHTML = GROUPLENGTH-obj.value.length;
  }
}

User.CreateGroup.ChangePrivacy = function(privacy)
{
  var head = document.getElementById('content_header');
  if (head)
  {
    var htm = "Groups &raquo; Create a Group &raquo; ";
    htm += ((privacy=='private') ? "Private Group" : "Public Group");
    head.innerHTML = htm;
  }
  if (privacy=='private')
  {
    DOM.Hide('announce_group');
    alert("Please note: PRIVATE groups will always remain private - they cannot be changed to PUBLIC groups");
  }
  else DOM.Show('announce_group');
}

User.CreateGroup.FormSubmit = function()
{
  User.CreateGroup.Validate.Name();
  User.CreateGroup.Validate.Description();
  User.CreateGroup.Validate.Category();
  User.CreateGroup.Validate.Photo();
  if (User.CreateGroup.Errors.length == 0)
  {
    var url = "user/save_group";
    var params = "isajax=1";
    params += "&groupname="+document.getElementById('group_name').value.trim();
    params += "&name=" + document.getElementById('name').value.trim();
    params += "&showname=" + (document.getElementById('show_name').checked ? '1' : '0');
    params += "&description="+document.getElementById('group_description').value.trim();
    params += "&category="+document.getElementById('group_category').value;
    params += "&website="+document.getElementById('group_website').value.trim();
    params += "&country="+document.getElementById('country').value.trim();
    params += "&state="+document.getElementById('state').value.trim();
    params += "&city="+document.getElementById('city').value.trim();
    params += "&photo="+User.CreateGroup.PhotoURL;
    if (Forms.Ischecked('group_private'))
    {
      params += "&visibility=private";
      params += "&announce=0";
    }
    else
    {
      params += "&visibility=public";
      params += "&announce="+Forms.Ischecked('group_announce');
    }
    params += "&tags="+document.getElementById('group_tags').value.trim();
    if (Forms.Ischecked('group_invite_none')) params += "&invite=none";
    else if (Forms.Ischecked('group_invite_friends')) params += "&invite=friends";
    else if (Forms.Ischecked('group_invite_list'))
    {
      var div = document.getElementById('invite_friend_list');
      var list = div.getElementsByTagName('input');
      var inv = [];
      for (var c=0;c<list.length;c++)
      {
        if (list[c].checked) inv.push(list[c].value);
      }
      if (inv.length>0)
      {
        params += "&invite=list&invitelist="+inv.toString();
      }
      else params += "&invite=none";
    }
    else params += "&invite=all";

    var group = new Ajax(User.CreateGroup.Create);
    DOM.Show('user_loading');
    group.sendPostRequest(url,params);
  }
  else
  {
    User.CreateGroup.ShowErrors();
  }
  return false;
}

User.CreateGroup.PhotoPreview = function()
{
  DOM.Show('user_loading');
}

User.CreateGroup.CancelPhoto = function()
{
  User.CreateGroup.PhotoURL = "";
  var pic = document.getElementById('group_photo');
  DOM.Hide(pic.id);
  pic.style.width = 'auto';
  pic.style.height = 'auto';
  pic.src = "";
  DOM.Hide('group_photo_container');
  User.CreateGroup.PhotoCanceled = true;
  return false;
}

User.CreateGroup.PhotoReturn = function()
{
  function resize_pic()
  {
    DOM.Show('group_photo_container');
    DOM.Show(pic.id);
    var w = pic.offsetWidth;
    var h = pic.offsetHeight;
    DOM.Hide(pic.id);
    if (w>270 || h>270)
    {
      var adj = 1;
      if (w > h)
      {
        adj = 270/w;
      }
      else
      {
        adj = 270/h;
      }
      pic.style.height = (Math.floor(h*adj))+'px';
      pic.style.width = (Math.floor(w*adj))+'px';
    }
    DOM.Show(pic.id);
    DOM.Hide('group_photo_form');
    DOM.Hide('user_loading');
  }

  DOM.Hide('user_loading');
  var d = new Date();
  var pic = document.getElementById('group_photo');
  DOM.Hide(pic.id);
  pic.style.width = 'auto';
  pic.style.height = 'auto';
  pic.onload = resize_pic;
  pic.src = HTTP_BASE + "file_temp/"+User.CreateGroup.PhotoURL+"?x="+d.getTime();
  User.CreateGroup.PhotoCanceled = false;
}

User.CreateGroup.PhotoError = function(err)
{
  DOM.Hide('user_loading');
  alert(err);
}

User.CreateGroup.Create = function(response)
{
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    var r = response.split('_');
    User.CreateGroup.GroupID = r[1];
    DOM.Hide('group_form');
    DOM.Show('group_done');
    User.ResetLeftNav();
  }
  else
  {
    User.CreateGroup.Errors = response.jsonParse();
    if (User.CreateGroup.Errors.length > 0) User.CreateGroup.ShowErrors();
    else alert("Unknown error:\n\n"+response);
  }
}

User.CreateGroup.FriendList = function(obj)
{
  if (obj.id=='group_invite_list')
  {
    if (!User.CreateGroup.ListOpen) DOM.Open('invite_friend_list');
    User.CreateGroup.ListOpen = true;
  }
  else
  {
    if (User.CreateGroup.ListOpen) DOM.Close('invite_friend_list');
    User.CreateGroup.ListOpen = false;
  }
}

User.CreateGroup.GoToGroup = function()
{
  location = "groups/view/"+User.CreateGroup.GroupID;
  return false;
}

/******************************************************************************/

User.CreateGroup.Validate = {};

User.CreateGroup.Validate.Name = function()
{
  var group_name = document.getElementById('group_name');
  if (group_name)
  {
    if (group_name.value.trim().match(/^[_a-zA-Z0-9-]{3,20}$/))
    {
      return true;
    }
    else User.CreateGroup.AddError('Please enter a valid user name: 3-20 characters, use only letters, numbers, underscore, or hyphen. No spaces. No period.');
  }
  else User.CreateGroup.AddError('Unable to read group name');
  return false;
}

User.CreateGroup.Validate.Description = function()
{
  var text = document.getElementById('group_description');
  if (text)
  {
    if (text.value.trim()!="")
    {
      return true;
    }
    else User.CreateGroup.AddError('Please tell us \"About\" your group');
  }
  else User.CreateGroup.AddError('Unable to read description');
  return false;
}

User.CreateGroup.Validate.Category = function()
{
  var category = document.getElementById('group_category');
  if (category)
  {
    if (category.value>0)
    {
      return true;
    }
    else User.CreateGroup.AddError('Please select a category');
  }
  else User.CreateGroup.AddError('Unable to read category');
  return false;
}

User.CreateGroup.Validate.Photo = function()
{
  var photo = document.getElementById('photo_file_source');
  if (photo)
  {
    if (photo.value=="")
    {
      return true;
    }
    else
    {
      if (User.CreateGroup.PhotoURL!="" || User.CreateGroup.PhotoCanceled)
      {
        return true;
      }
      else
      {
        User.CreateGroup.AddError('Please select a group profile photo and click "Preview"');
      }
    }
  }
  else User.CreateGroup.AddError('Unable to read photo form');
  return false;
}

/******************************************************************************/

