Group.InviteMembers = {};

Group.InviteMembers.Init = function()
{
  DOM.Hide('right_col');
  DOM.SetClass('content_div','content_no_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
  //DOM.Show('invite_link_off');
  //DOM.Hide('invite_link');
  //DOM.Hide('group_viewmessages_header');
  Group.InviteMembers.ListOpen = false;
}

Group.InviteMembers.FriendList = function(obj)
{
  if (obj.id=='group_invite_list')
  {
    if (!Group.InviteMembers.ListOpen) DOM.Open('invite_friend_list');
    Group.InviteMembers.ListOpen = true;
  }
  else
  {
    if (Group.InviteMembers.ListOpen) DOM.Close('invite_friend_list');
    Group.InviteMembers.ListOpen = false;
  }
}

Group.InviteMembers.Cancel = function()
{
  Group.Navigate('group_timeline');
  return false;
}

Group.InviteMembers.FormSubmit = function()
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      DOM.Hide('group_form');
      DOM.Show('form_response');
    }
    else alert("ERROR: "+response);
  }
  
  var url = "groups/send_invitation/"+Group.ID;
  var params = "isajax=1";
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
  var group = new Ajax(ret);
  DOM.Show('user_loading');
  group.sendPostRequest(url,params);
  return false;
}