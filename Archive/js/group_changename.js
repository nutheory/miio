Group.ChangeName = {};

Group.ChangeName.Init = function()
{
  DOM.Hide('right_col');
  DOM.SetClass('content_div','content_no_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('user_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
}

Group.ChangeName.FormSubmit = function()
{
  var username = document.getElementById('group_name');
  if (username)
  {
    var un = username.value.trim();
    if (un.match(/^[_a-zA-Z0-9-]{3,20}$/))
    {
      if (un!=Group.UserName)
      {
        var profile = new Ajax(Group.ChangeName.Updated);
        var url = "groups/update_name/"+Group.ID;
        var params = "isajax=1";
        Group.ChangeName.NewName = un;
        params += "&name="+Group.ChangeName.NewName;
        DOM.Show('user_loading');
        profile.sendPostRequest(url,params);
      }
      else alert("You didn't change the name!");
    }
    else
    {
      alert('Please enter a valid user name: 3-20 characters, use only letters, numbers, underscore, or hyphen. No periods or spaces.');
    }
  }
  return false;
}

Group.ChangeName.Updated = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    Group.UserName = Group.ChangeName.NewName;
    Group.ChangeName.NewName = "";
    DOM.SetHTML('group_username',Group.UserName);
    DOM.SetHTML('group_head_username',Group.UserName);
    DOM.Hide('group_changename_form');
    DOM.Show('form_response');
    scrollTo(0,0);
  }
  else
  {
    alert("Error:\n\n"+response);
  }
}

Group.ChangeName.Done = function()
{
  Group.Navigate('group_timeline_messages');
  return false;
}

Group.ChangeName.CheckName = function()
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
  
  var username = document.getElementById('group_name');
  if (username)
  {
    var uname = username.value.trim();
    if (uname.match(/^[_a-zA-Z0-9-]{3,20}$/))
    {
      if (uname!=Group.UserName)
      {
        var un = new Ajax(ret);
        var url = "ajax/check_name";
        var params = "isajax=1";
        params += "&name="+uname;
        DOM.Show('user_loading');
        un.sendPostRequest(url,params);
      }
      else alert("That is the current group name.");
    }
    else
    {
      alert('Please enter a valid user name: 3-20 characters, use only letters, numbers, underscore, or hyphen. No periods or spaces.');
    }
  }
  else alert('Error: Unable to read username');
  return false;
}