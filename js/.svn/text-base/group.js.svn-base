var Group = {};

Group.Page = "";

Group.CurrentPage = 1;
Group.CurrentFilter = "";
Group.FilterIsText = false;
Group.IsRefresh = false;
Group.DisplayOpt = "short_list";

Group.MenuOpts = {};
// top level
Group.MenuOpts.timeline = 'Timeline';
Group.MenuOpts.albums = "Photo albums";
Group.MenuOpts.description = "About";
Group.MenuOpts.members = "Members";
Group.MenuOpts.profile = "Group profile";
Group.MenuOpts.editphoto = "Group photo";
Group.MenuOpts.editalbums = "Albums";
Group.MenuOpts.invitemembers = "Invite others to join";
Group.MenuOpts.requests = "Membership requests";
Group.MenuOpts.managemembers = "Manage members";
Group.MenuOpts.manageadmins = "Manage Administrators";
Group.MenuOpts.transferownership = "Transfer ownership";
Group.MenuOpts.disband = "Disband group";
Group.MenuOpts.changename = "Change group name";
Group.MenuOpts.manage = "Manage Membership";
Group.MenuOpts.leave = "No longer a member";
Group.MenuOpts.report = "Report this group";

// timeline options

Group.Empty = {};
// top level
Group.Empty.timeline = "When %NAME%'s members post messages to the group, they will be displayed here.";
Group.Empty.albums = "";
Group.Empty.description = "";
Group.Empty.members = "";
Group.Empty.profile = "";
Group.Empty.editphoto = "";
Group.Empty.editalbums = "";
Group.Empty.invitemembers = "";
Group.Empty.requests = "There are currently no requests for membership in the %NAME% group.";
Group.Empty.managemembers = "";
Group.Empty.admininvite = "All of the %NAME% group's members are already Administrators.";
Group.Empty.admininvites = "There are no pending Administrator invitations.";
Group.Empty.adminadmins = "";
Group.Empty.ownerinvite = "There are no Administrators in the %NAME% group who can be invited to become the Owner.";
Group.Empty.ownerpending = "There are no pending Ownership transfers.";
Group.Empty.ownerowner = "";
Group.Empty.disband = "";
Group.Empty.changename = "";
Group.Empty.manage = "";
Group.Empty.report = "";
Group.Empty.leave = "";

Group.Init = function(id,username)
{
  Group.IsMember = (DOM.GetValue('ismember')==1) ? true : false;
  Group.IsAdmin = (DOM.GetValue('isadmin')==1) ? true : false;
  Group.IsPrivate = (DOM.GetValue('isprivate')==1) ? true : false;
  Group.ID = id;
  Group.UserName = username;
  if (location.hash != "")
  {
    Group.CloseCurrent();
    var str = location.hash.substring(1).split('/');
    Group.Current = str[0];
    if (str[1]) Group.Sub = str[1];
    //else if (Group.Current=='timeline') Group.Sub = 'all';
    else if (Group.Current=='manageadmins') Group.Sub = 'admins';
    else if (Group.Current=='transferownership') Group.Sub = 'owner';
    else Group.Sub = "";
    if (str[2]) Group.SubSub = str[2]; else Group.SubSub = "";
  }
  else
  {
    Group.Current = "timeline";
    Group.Sub = "";
    Group.SubSub = "";
  }
  var loc = 'http://'+location.hostname+location.pathname+"#"+Group.Current;
  if (Group.Sub)
  {
    loc += "/"+Group.Sub;
    if (Group.SubSub) loc += "/"+Group.SubSub;
  }
  location = loc;
  Group.CurrentLocation = location+"";
  Group.OpenCurrent();
  Group.ShowPageElements();
  setTimeout(Group.WentBack,100);
  setTimeout(Group.Update.GetUpdate,UPDATE_INTERVAL);
  Group.DisplayOpt = "short_list";
  if (!Group.IsPrivate || Group.Current=='report') Group.GetPage();
}

Group.WentBack = function()
{
  if (Group.CurrentLocation != location)
  {
    var str = location.hash.substring(1).split('/');
    var nav = 'group_'+str[0];
    if (str[1]) nav += '_'+str[1];
    if (str[2]) nav += '_'+str[2];
    Group.Navigate(nav);
  }
  setTimeout(Group.WentBack,100);
}

Group.CloseCurrent = function()
{
  DOM.SetHTML('update_count','0');
  DOM.Hide('update_counter');
  DOM.Show('updatecounter1');
  DOM.Hide('updatecounterx');
  DOM.SetHTML('pause_count','0');
  DOM.Hide('pause_counter');
  DOM.Show('pausecounter1');
  DOM.Hide('pausecounterx');
  if (Messages) Messages.UpdateIsOpen = false;

  DOM.SetClass('group_'+Group.Current,"");
  DOM.Hide('nav_group_'+Group.Current);
  DOM.Hide('manage_link_off');
  DOM.Show('manage_link');
  DOM.Hide('invite_link_off');
  DOM.Show('invite_link');
  DOM.Hide('message_form');

  DOM.Hide('group_manageadmins_header');
  DOM.Hide('group_manageadmins_header_admins');
  DOM.Hide('group_manageadmins_header_invites');
  DOM.Hide('group_manageadmins_header_invite');
  DOM.Show('group_manageadmins_header_admins_link');
  DOM.Show('group_manageadmins_header_invites_link');
  DOM.Show('group_manageadmins_header_invite_link');

  DOM.Hide('group_transferownership_header');
  DOM.Hide('group_transferownership_header_owner');
  DOM.Hide('group_transferownership_header_pending');
  DOM.Hide('group_transferownership_header_invite');
  DOM.Show('group_transferownership_header_owner_link');
  DOM.Show('group_transferownership_header_pending_link');
  DOM.Show('group_transferownership_header_invite_link');

  if (!Group.IsReported)
  {
    DOM.Hide('report_link_off');
    DOM.Show('report_link');
  }
}

Group.OpenCurrent = function()
{
  DOM.SetClass('group_'+Group.Current,"active");
  DOM.Show('nav_group_'+Group.Current);


  DOM.Show('group_'+Group.Current+'_header');
  DOM.Show('group_'+Group.Current+'_header_'+Group.Sub);
  DOM.Hide('group_'+Group.Current+'_header_'+Group.Sub+'_link');
}

Group.ShowPageElements = function()
{
  if (Group.IsMember)
  {
    DOM.Show('is_member');
    DOM.Hide('not_member_links');
    DOM.Hide('not_member');
  }
  else
  {
    DOM.Hide('is_member');
    DOM.Show('not_member_links');
    DOM.Hide('message_form');
  }
}

Group.Navigate = function(which)
{
  //if (which.className && which.className=='active') return false;
  if (typeof(which)=="string") var currents = which.split('_');
  else var currents = which.id.split('_');
  //if (currents[1]==Group.Current && currents[2]==Group.Sub) return false;

  // close current selection
  Group.CloseCurrent();
  Group.Current = currents[1];
  if (currents[2] && currents[2]!='') Group.Sub = currents[2];
  //else if (Group.Current=='timeline') Group.Sub = "all";
  else if (Group.Current=='manageadmins') Group.Sub = "admins";
  else if (Group.Current=='transferownership') Group.Sub = "owner";
  else Group.Sub = "";
  if (currents[3] && currents[3]!='') Group.SubSub = currents[3];
  else Group.SubSub = '';

  // save to browser history
  var loc = 'http://'+location.hostname+location.pathname+"#"+Group.Current;
  if (Group.Sub)
  {
    loc += "/"+Group.Sub;
    if (Group.SubSub) loc += "/"+Group.SubSub;
  }
  location = loc;
  Group.CurrentLocation = location+"";
  if (Group.IsPrivate && currents[1]!='report')
  {
    DOM.Show('right_col');
    DOM.SetClass('content_div','content_with_rcol');
    DOM.SetHTML('groups_content','');
    DOM.Show('private_content');
    return false;
  }
  clearTimeout(Messages.Refresh);

  Group.OpenCurrent();

  DOM.SetValue('user_filter','Filter');
  DOM.SetClass('user_filter','');
  DOM.Hide('clear_user_filter');
  DOM.Show('filter_user_filter');
  Users.FilterIsOpen = false;

  Group.CurrentPage = 1;
  Group.CurrentFilter = "";
  Group.FilterIsText = false;

  // initiate AJAX call
  Group.GetPage();
  return false;
}

Group.GetPage = function()
{
  var loc = location.hash.substring(1).split('/');
  if (!loc[0])
  {
    location = HTTP_BASE+"groups/view/"+Group.ID;
  }
  else
  {
    var url = "groups/"+loc[0]+"/"+Group.ID;
    if (loc[1]) url += "?type="+loc[1];
    else if (Group.Sub != '') url += "?type=" + Group.Sub;

    var params = "isajax=1";
    params += "&page="+Group.CurrentPage;
    params += "&filter="+Group.CurrentFilter;
    params += "&display="+Group.DisplayOpt;
    params += "&initial_load=1";
    if (!Group.IsRefresh) DOM.Show('user_loading');
    Group.Ajax.sendPostRequest(url,params);
  }
}

Group.GetResults = function(response)
{
  DOM.Hide('user_loading');
  var content = document.getElementById('groups_content');
  if (content) content.innerHTML = response;
  if (Group.IsPrivate && Group.Current=='report') DOM.Hide('private_content');
  if (Group.Current=='transferownership')
  {
    DOM.SetHTML('content_header',Group.MenuOpts[Group.Current].replace(/%NAME%/,Group.UserName));
    if (Group.CurrentFilter=="") DOM.SetHTML('no_users',Group.Empty['owner'+Group.Sub].replace(/%NAME%/,Group.UserName));
  }
  else if (Group.Current=='manageadmins')
  {
    DOM.SetHTML('content_header',Group.MenuOpts[Group.Current].replace(/%NAME%/,Group.UserName));
    if (Group.CurrentFilter=="") DOM.SetHTML('no_users',Group.Empty['admin'+Group.Sub].replace(/%NAME%/,Group.UserName));
  }
  else if (Group.Sub!="")
  {
    DOM.SetHTML('content_header',Group.MenuOpts[Group.Sub].replace(/%NAME%/,Group.UserName));
    DOM.SetHTML('no_message_text',Group.Empty[Group.Sub].replace(/%NAME%/,Group.UserName));
  }
  else
  {
    DOM.SetHTML('content_header',Group.MenuOpts[Group.Current].replace(/%NAME%/,Group.UserName));
    DOM.SetHTML('no_message_text',Group.Empty[Group.Current].replace(/%NAME%/,Group.UserName));
  }

  switch (Group.Current)
  {
    case 'timeline'           : Group.Timeline.Init(); break;
    case 'albums'             : Group.Albums.Init(); break;
    case 'description'        : Group.Description.Init(); break;
    case 'members'            : Group.Members.Init(); break;
    case 'changename'         : Group.ChangeName.Init(); break;
    case 'profile'            : Group.Profile.Init(); break;
    case 'editphoto'          : Group.EditPhoto.Init(); break;
    case 'editalbums'         : Group.EditAlbums.Init(); break;
    case 'invitemembers'      : Group.InviteMembers.Init(); break;
    case 'requests'           : Group.Requests.Init(); break;
    case 'managemembers'      : Group.ManageMembers.Init(); break;
    case 'manageadmins'       : Group.ManageAdmins.Init(); break;
    case 'transferownership'  : Group.TransferOwnership.Init(); break;
    case 'disband'            : Group.Disband.Init(); break;
    case 'changename'         : Group.ChangeName.Init(); break;
    case 'report'             : Group.ReportGroup.Init(); break;
    case 'manage'             : Group.ManageMembership.Init(); break;
  }
  if (Group.Current=='manage') DOM.SetClass('member_preferences','highlightlink');
  else DOM.SetClass('member_preferences','');
  if (Group.IsRefresh) Group.IsRefresh = false;
  else scrollTo(0,0);
}

Group.Paginate = function(listpage,filtervalue,istext)
{
  Group.CurrentPage = listpage;
  if (filtervalue!=undefined) Group.CurrentFilter = filtervalue;
  if (istext!=undefined) Group.FilterIsText = istext;
  Group.GetPage();
}

Group.Report = function(obj,id)
{
  obj.blur()
  Group.Navigate('group_report');
  return false;
}

Group.Suspend = function(id,username)
{
  function ret(response)
  {
    if (response == 'ok')
    {
      alert("The "+ username + " group has been suspended");
      DOM.Hide('suspend_user');
      DOM.Show('suspended_user');
    }
    else
    {
      alert("Error: " + response);
    }
  }

  if (confirm("Really suspend the "+username+" group?"))
  {
    var susp = new Ajax(ret);
    var params = "isajax=1";
    params += "&id="+id;
    var url = "admin/suspend/"+id;
    susp.sendPostRequest(url,params);
  }
  return false;
}

Group.Manage = function(obj,id,newmember)
{
  obj.blur();
  Group.Navigate('group_manage');
  return false;
}

Group.Invite = function(id)
{
  Group.Navigate('group_invitemembers');
  return false;
}

Group.Join = function(id)
{
  function ret(response)
  {
    if (response=='ok')
    {
      Group.IsMember = true;
      DOM.SetValue('ismember',1);
      DOM.Show('status_member');
      DOM.Hide('status_not_member');
      DOM.Hide('join_link');
      DOM.Show('member_preferences');
      DOM.Show('leave_link');
      location = 'groups/view/'+id+'#manage';
      location.reload();
    }
    else
    {
      DOM.Hide('user_loading');
      alert("Error:\n"+response);
    }
  }

  Group.CloseCurrent();
  DOM.Show('user_loading');
  var profile = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/join_group/"+id;
  profile.sendPostRequest(url,params);
  return false;
}

Group.RequestMembership = function(id)
{
  var request = new Ajax(Group.MembershipRequested);
  var params = "isajax=1";
  var url = "groups/request_membership/"+id;
  request.sendPostRequest(url,params);
  return false;
}

Group.MembershipRequested = function(response)
{
  if (response=='ok')
  {
    DOM.Show('status_requested');
    DOM.Show('membership_requested');
    DOM.Show('sub_requested');
    DOM.Hide('status_not_member');
    DOM.Hide('membership_not_requested');
    DOM.Hide('join_link');
    DOM.Hide('join_button');
    DOM.Hide('private_content');
  }
  else alert("Error:\n\n"+response);
}

Group.Leave = function(id,username)
{
  var str = "Are you sure you want to leave the "+username+" group?";
  if (Group.IsAdmin) str += "\n\nIf you leave, you will also lose your rights as a Group Administrator.";
  if (confirm(str))
  {
    var membership = new Ajax(Group.LeaveReturn);
    var url = "groups/leave_group/"+Group.ID;
    var params = "isajax=1";
    DOM.Show('user_loading');
    membership.sendPostRequest(url,params);
  }
  return false;
}

Group.LeaveReturn = function(response)
{
  DOM.Hide('user_loading');
  if (response=="ok")
  {
    Group.IsMember = false;
    DOM.SetValue('ismember',0);
    DOM.Hide('status_member');
    DOM.Hide('status_admin');
    DOM.Show('status_not_member');
    DOM.Show('join_link');
    DOM.Hide('member_preferences');
    DOM.Hide('leave_link');
    location = 'groups/view/'+Group.ID+'#manage/leave';
    location.reload();
  }
  else
  {
    alert("Error:\n"+response);
  }
}

Group.PauseUpdates = function()
{
  if (Group.Current=='timeline') Messages.PauseUpdates();
}

Group.ResumeUpdates = function()
{
  if (Group.Current=='timeline') Messages.ResumeUpdates();
}

Group.Ajax = new Ajax(Group.GetResults);

/********************************** UPDATER  **********************************/

Group.Update = {};

Group.Update.GetUpdate = function()
{
  var update = new Ajax(Group.Update.GotUpdate);
  var url = "ajax/group_update/"+Group.ID;
  update.sendRequest(url);
}

Group.Update.GotUpdate = function(response)
{
  if (response=='badid')
  {
    alert("Error: Invalid User ID");
  }
  else
  {
    var update = response.jsonParse();
    DOM.SetHTML('group_album_count',update.albums);
    DOM.SetHTML('group_member_count',update.members);
    DOM.SetHTML('group_request_count',update.requests);
    Group.Updater = setTimeout(Group.Update.GetUpdate,UPDATE_INTERVAL);
  }
}