Group.TransferOwnership = {};

Group.TransferOwnership.Init = function()
{
  DOM.Hide('right_col');
  DOM.SetClass('content_div','content_no_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
  
  Group.TransferOwnership.AdminName = "";
  Users.Init('group');
}

Group.TransferOwnership.ChangeSelection = function(opt)
{
  Group.Navigate('group_transferownership_'+opt);
  return false;
}

Group.TransferOwnership.Invite = function(userid,username)
{
  if (confirm('Really invite ' + username + ' to take over ownership of the ' + Group.UserName + ' group?'))
  {
    Group.TransferOwnership.AdminName = username;
    var remove = new Ajax(Group.TransferOwnership.Invited);
    var url = "groups/invite_owner/"+Group.ID;
    var params = "isajax=1";
    params += "&userid="+userid;
    DOM.Show('user_loading');
    remove.sendPostRequest(url,params);
  }
  return false;
}

Group.TransferOwnership.Cancel = function(userid,username)
{
  if (confirm('Really cancel invitation to ' + username + ' to take over ownership of the ' + Group.UserName + ' group?'))
  {
    Group.TransferOwnership.AdminName = username;
    var remove = new Ajax(Group.TransferOwnership.Canceled);
    var url = "groups/cancel_owner_invite/"+Group.ID;
    var params = "isajax=1";
    params += "&userid="+userid;
    DOM.Show('user_loading');
    remove.sendPostRequest(url,params);
  }
  return false;
}

Group.TransferOwnership.Invited = function(response)
{
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    DOM.SetHTML('invited_owner_name',Group.TransferOwnership.AdminName);
    alert(response.substr(2) + " has been invited to take over ownership of the " + Group.UserName + " group.");
    Group.Navigate('group_transferownership_pending');
  }
  else if (response=='notadmin')
  {
    alert('You cannot invite a member who is not an Administrator to take over ownership of a group.');
  }
  else if (response=='notmember')
  {
    alert('You cannot invite a non-member to take over ownership of a group.');
  }
  else alert("Error:\n\n"+response);
}

Group.TransferOwnership.Canceled = function(response)
{
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    alert(response.substr(2) + "'s invitation to to own the " + Group.UserName + " group has been canceled.");
    Group.Navigate('group_transferownership_invite');
  }
  else if (response=='lastowner')
  {
    alert("Unable to cancel ownership transfer. The ownership transfer has already been accepted");
    location.href=HTTP_BASE+"groups/view/"+Group.ID;
  }
  else alert("Error:\n\n"+response);
}
