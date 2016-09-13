Group.ManageAdmins = {};

Group.ManageAdmins.Init = function()
{
  DOM.Hide('right_col');
  DOM.SetClass('content_div','content_no_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');

  Group.ManageAdmins.RemovedAdmin = "";
  Group.ManageAdmins.InvitedAdmin = "";
  Users.Init('group');
}

Group.ManageAdmins.ChangeSelection = function(opt)
{
  Group.Navigate('group_manageadmins_'+opt);
  return false;
}

Group.ManageAdmins.Invite = function(userid,username)
{
  if (confirm('Do you really want to invite ' + username + ' to be an Administrator for the ' + Group.UserName + ' group?'))
  {
    Group.ManageAdmins.InvitedAdmin = username;
    var remove = new Ajax(Group.ManageAdmins.Invited);
    var url = "groups/invite_admin/"+Group.ID;
    var params = "isajax=1";
    params += "&userid="+userid;
    DOM.Show('user_loading');
    remove.sendPostRequest(url,params);
  }
  return false;
}

Group.ManageAdmins.Cancel = function(userid,username)
{
  if (confirm('Do you really want to cancel the invitation to ' + username + ' to be an Administrator for the ' + Group.UserName + ' group?'))
  {
    Group.ManageAdmins.InvitedAdmin = username;
    var remove = new Ajax(Group.ManageAdmins.Canceled);
    var url = "groups/cancel_admin_invite/"+Group.ID;
    var params = "isajax=1";
    params += "&userid="+userid;
    DOM.Show('user_loading');
    remove.sendPostRequest(url,params);
  }
  return false;
}

Group.ManageAdmins.Remove = function(userid,username)
{
  if (confirm('Do you really want to remove ' + username + ' as an Administrator for the ' + Group.UserName + ' group?'))
  {
    Group.ManageAdmins.RemovedAdmin = username;
    var remove = new Ajax(Group.ManageAdmins.Removed);
    var url = "groups/remove_admin/"+Group.ID;
    var params = "isajax=1";
    params += "&userid="+userid;
    DOM.Show('user_loading');
    remove.sendPostRequest(url,params);
  }
  return false;
}

Group.ManageAdmins.Invited = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    alert(Group.ManageAdmins.InvitedAdmin + " has been invited to be an Administrator of the " + Group.UserName + " group.");
    Group.ManageAdmins.InvitedAdmin = "";
    Group.Navigate('group_manageadmins_invites');
  }
  else if (response=='notmember')
  {
    alert('You cannot invite a non-member to be an Administrator of a group.');
  }
  else alert("Error:\n\n"+response);
}

Group.ManageAdmins.Canceled = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    alert(Group.ManageAdmins.InvitedAdmin + "'s invitation to be an Administrator of the " + Group.UserName + " group has been canceled.");
    Group.ManageAdmins.InvitedAdmin = "";
    Group.GetPage();
  }
  else if (response=='isadmin')
  {
    alert("Unable to cancel. The invitation has already been accepted.");
  }
  else alert("Error:\n\n"+response);
}

Group.ManageAdmins.Removed = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    alert(Group.ManageAdmins.RemovedAdmin + " has been removed as an Administrator of the " + Group.UserName + " group.");
    Group.ManageAdmins.RemovedAdmin = "";
    Group.GetPage();
  }
  else if (response=='isowner')
  {
    alert('The Owner of a group cannot be removed as an Administrator.');
  }
  else alert("Error:\n\n"+response);
}