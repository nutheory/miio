Group.ManageMembers = {};

Group.ManageMembers.Init = function()
{
  DOM.Show('right_col');
  DOM.SetClass('content_div','content_with_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
  Group.ManageMembers.RemovedMember = "";
  Users.Init('group');
}

Group.ManageMembers.Remove = function(obj,userid,username)
{
  if (confirm('Really remove ' + username + ' from ' + Group.UserName + '?'))
  {
    Group.ManageMembers.RemovedMember = username;
    var remove = new Ajax(Group.ManageMembers.Removed);
    var url = "groups/remove_member/"+Group.ID;
    var params = "isajax=1";
    params += "&userid="+userid;
    DOM.Show('user_loading');
    remove.sendPostRequest(url,params);
  }
  return false;
}

Group.ManageMembers.Removed = function(response)
{
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    alert(Group.ManageMembers.RemovedMember + " has been removed from the group.");
    Group.GetPage();
  }
  else if (response=='isadmin')
  {
    alert('Administrators must first be removed as an Administrator before being removed from the group.');
  }
  else if (response=='isowner')
  {
    alert('The Owner of a group cannot be removed from the group.');
  }
  else alert("Error:\n\n"+response);
}