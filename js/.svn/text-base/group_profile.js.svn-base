Group.Profile = {};

Group.Profile.Init = function()
{
  DOM.Hide('right_col');
  DOM.SetClass('content_div','content_no_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('user_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
  
  Lib.InitLocation(Group.Profile,countries,states,cities);
}

Group.Profile.ChangeCountry = function(obj)
{
  Lib.ChangeCountry(Group.Profile,obj);
}

Group.Profile.ChangeState = function(obj,country)
{
  Lib.ChangeState(Group.Profile,obj);
}

Group.Profile.Count = function(e,obj,divid)
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

Group.Profile.TagCount = function(e,obj)
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

Group.Profile.FormSubmit = function()
{
  try
  {
    var profile = new Ajax(Group.Profile.Updated);
    var url = "groups/update_profile/"+Group.ID;
    var params = "isajax=1";
    params += "&fullname="+document.getElementById('full_name').value.trim();
    params += "&showname="+(document.getElementById('show_name').checked?'1':'0');
    params += "&description="+document.getElementById('group_profile_description').value.trim();
    params += "&country="+document.getElementById('country').value.trim();
    params += "&state="+document.getElementById('state').value.trim();
    params += "&city="+document.getElementById('city').value.trim();
    params += "&website="+document.getElementById('website').value.trim();
    params += "&tags="+document.getElementById('tags').value.trim();
    params += "&category="+document.getElementById('category').value;
    if (DOM.Exists('group_private')) params += "&makeprivate="+Forms.Ischecked('group_private');
    DOM.Show('user_loading');
    profile.sendPostRequest(url,params);
  }
  catch (e)
  {
    alert("Unknown error:\n\n"+e);
  }
  return false;
}

Group.Profile.Updated = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    DOM.Hide('group_profile_form');
    DOM.Show('group_profile_updated');
    Group.CloseCurrent();
    scrollTo(0,0);
  }
  else
  {
    alert("Error:\n\n"+response);
  }
}

Group.Profile.Done = function()
{
  Group.Navigate('group_viewmessages_messages');
  return false;
}

Group.Profile.MakePrivate = function(obj)
{
  if (obj.checked)
  {
    var str = "Remember, this is a permanent change. Once you click 'Update Profile', you cannot undo it.";
    alert(str);
  }
  return false;
}
