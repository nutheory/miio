var Users = {};

Users.CurrentFilter = "";
Users.FilterIsOpen = false;
Users.ViewOpts = ['short_list','long_list','phone_on','phone_off','mute_on','mute_off'];
Users.DisplayOpt = "";

Users.Init = function(mainpage)
{
  DOM.Hide('message_form');
  DOM.Hide('message_filters');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Show('timeline_filters');
  DOM.Show('user_filter_container');

  Users.Controller = mainpage;
  Users.RemoveAfterFollow = false;
  if (mainpage=='members')
  {
    Users.MainPage = Profile;
    Users.HidePhoneMute();
  }
  else if (mainpage=='group')
  {
    Users.MainPage = Group;
    Users.HidePhoneMute();
    if (Group.Sub=='owner') DOM.Hide('user_filter_container');
  }
  else if (mainpage=='tabs')
  {
    Users.MainPage = Tabs;
    Users.HidePhoneMute();
  }
  else if (mainpage=='user')
  {
    Users.MainPage = User;
    Users.Sub = User.CurrentSub;
    switch (User.CurrentSub)
    {
      case 'friends':
      case 'following':
      case 'publicgroups':
      case 'privategroups':
      case 'admingroups':
        DOM.Show('phone_on');
        DOM.Show('phone_off');
        DOM.Show('mute_on');
        DOM.Show('mute_off');
        Users.RemoveAfterFollow = true;
        break;
      case 'followers':
        Users.RemoveAfterFollow = true;
      case 'featuredgroups':
      case 'friendgroups':
      case 'featured':
      case 'fof':
        Users.HidePhoneMute();
        break;
      default:
        alert("Error: unknown value for User.CurrentSub: '"+User.CurrentSub+"'");
    }
  }
  else if (mainpage=='search')
  {
    Users.MainPage = Search;
  }
  else alert("Error: unable to initialize User List. Page='"+mainpage+"'");
}

Users.HidePhoneMute = function()
{
  DOM.Hide('phone_on');
  DOM.Hide('phone_off');
  DOM.Hide('mute_on');
  DOM.Hide('mute_off');
  DOM.Hide('phone_on_sel');
  DOM.Hide('phone_off_sel');
  DOM.Hide('mute_on_sel');
  DOM.Hide('mute_off_sel');
}

Users.Paginate = function(controller,listpage,filtervalue)
{
  switch (controller)
  {
    case 'user'    : User.Paginate(listpage,filtervalue); break;
    case 'members' : Profile.Paginate(listpage,filtervalue); break;
    case 'tabs'    : Tabs.Paginate(listpage,filtervalue); break;
    case 'group'   : Group.Paginate(listpage,filtervalue); break;
    case 'search'  : Search.Paginate(listpage); break;
    default: alert("Error: Unknown controller '"+controller+"'");
  }
  return false;
}

Users.FilterFocus = function(filter)
{
  if (filter.className!='active')
  {
    filter.className = 'active';
    filter.value = "";
  }
}

Users.FilterBlur = function(filter)
{
  if (filter.value=="")
  {
    filter.value = "Filter";
    filter.className = "";
  }
}

Users.ClearFilter = function()
{
  DOM.SetClass('user_filter','');
  DOM.SetValue('user_filter','Filter');
  Users.Filter('');
}

Users.Display = function(opt)
{
  Users.DisplayOpt = opt;
  switch (Users.Controller)
  {
    case 'user':
      User.DisplayOpt = opt;
      User.GetPage();
      break;
    case 'group':
      Group.DisplayOpt = opt;
      Group.GetPage();
      break;
    case 'members':
      Profile.DisplayOpt = opt;
      Profile.GetPage();
      break;
  }

  var div = document.getElementById('user_filter_container');
  var btn = div.getElementsByTagName('span');
  for (var b=0;b<btn.length;b++)
  {
    if (btn[b].id==opt) btn[b].className = 'active';
    else btn[b].className = '';
  }
}

Users.Filter = function(filtervalue)
{
  if (filtervalue.trim()!="")
  {
    DOM.Show('clear_user_filter');
    DOM.Hide('filter_user_filter');
  }
  else
  {
    DOM.Show('filter_user_filter');
    DOM.Hide('clear_user_filter');
  }

  if (Users.MainPage.Ajax.http.readyState != 0) Users.MainPage.Ajax.http.abort();
  switch (Users.Controller)
  {
    case 'user':
      User.CurrentFilter = filtervalue;
      User.GetPage();
      break;
    case 'group':
      Group.CurrentFilter = filtervalue;
      Group.GetPage();
      break;
    case 'members':
      Profile.CurrentFilter = filtervalue;
      Profile.GetPage();
      break;
  }
  return false;
}



Users.Block = function(id,username)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=="ok")
    {
      alert("You have blocked "+username);
      DOM.Hide('user_'+id);
    }
    else alert("Error:\n"+response);
  }

  alert("Unexpected call to Users.Block in userlist.js line 280");
  var str = "Are you sure you want to block "+username+"?";
  if (confirm(str))
  {
    DOM.Show('user_loading');
    var profile = new Ajax(ret);
    var params = "isajax=1";
    var url = "members/block/"+id;
    profile.sendPostRequest(url,params);
  }
  return false;
}

Users.OnMember = function(id)
{
  DOM.SetClass('user_bar_'+id,'mo');
}

Users.OffMember = function(id)
{
  if (DOM.GetValue('preference_open_'+id)==0 && DOM.GetValue('profile_open_'+id)==0)
  {
    DOM.SetClass('user_bar_'+id,'');
  }
}

Users.ClosePreferences = function(id,donext)
{
  var prefs = document.getElementById('preference_link_'+id);
  Users.Preferences(prefs,id,donext);
  return false;
}

Users.Preferences = function(obj,id,donext)
{
  obj.blur();
  function openprefs()
  {
    obj.parentNode.className = 'open';
    //DOM.SetValue('preference_open_'+id,1);
    DOM.Open('preferences_'+id);
  }

  if (DOM.GetValue('preference_open_'+id)>0)
  {
    obj.parentNode.className = 'closed';
    DOM.SetValue('preference_open_'+id,0);
    if (donext) DOM.Close('preferences_'+id,null,donext);
    else DOM.Close('preferences_'+id);
  }
  else
  {
    var profile = document.getElementById('profile_link_'+id);
    DOM.SetValue('preference_open_'+id,1);
    if (DOM.GetValue('profile_open_'+id)>0) Users.Profile(profile,id,openprefs);
    else openprefs();
  }
  return false;
}

Users.UpdatePreferences = function(id)
{
  Users.PreferenceUpdateIsGroup = (DOM.GetValue('is_group_'+id)==1);
  Users.PreferenceUpdateID = id;
  var preference = new Ajax(Users.PreferencesUpdated);
  var params = "isajax=1";
  Users.PreferenceUpdateMute = Forms.Ischecked('mute_on_'+id);
  params += "&mute="+Users.PreferenceUpdateMute;
  Users.PreferenceUpdateSMS = 0;
  var dashboard_reply = 0;
  for (var t in PREFERENCE_TYPES)
  {
    if (PREFERENCE_TYPES[t]!='reply')
    {
      if (Forms.Ischecked('dashboard_'+PREFERENCE_TYPES[t]+'_'+id))
      {
        dashboard_reply = 1;
        params += "&dashboard["+PREFERENCE_TYPES[t]+"]=1";
      }
      else params += "&dashboard["+PREFERENCE_TYPES[t]+"]=0";
    }

    var sms = Forms.Ischecked('sms_'+PREFERENCE_TYPES[t]+'_'+id);
    if (sms) Users.PreferenceUpdateSMS = 1;
    params += "&sms["+PREFERENCE_TYPES[t]+"]="+sms;
    params += "&email["+PREFERENCE_TYPES[t]+"]="+Forms.Ischecked('email_'+PREFERENCE_TYPES[t]+'_'+id);
  }
  params += "&dashboard[102]="+dashboard_reply;
  if (Users.PreferenceUpdateIsGroup)
  {
    var url = "groups/update_membership/"+id;
    params += "&dashboard_admin=1";
    var sms = Forms.Ischecked('sms_admin_'+id);
    if (sms) Users.PreferenceUpdateSMS = 1;
    params += "&sms_admin="+sms;
    params += "&email_admin="+Forms.Ischecked('email_admin_'+id);
  }
  else var url = "members/update_subscription/"+id;
  DOM.Show('user_loading');
  preference.sendPostRequest(url,params);
  return false;
}

Users.PreferencesUpdated = function(response)
{
  function removeuser()
  {
    var obj = document.getElementById('user_'+Users.PreferenceUpdateID);
    if (obj) obj.parentNode.removeChild(obj);
    clearvars();
  }

  function clearvars()
  {
    Users.PreferenceUpdateMute = null;
    Users.PreferenceUpdateSMS = null;
    Users.PreferenceUpdateIsGroup = null;
    Users.PreferenceUpdateID = null;
  }

  function prefsclosed()
  {
    var clear = false;
    if (Users.PreferenceUpdateMute==1)
    {
      if (Users.DisplayOpt=='mute_off') clear = true;
      else
      {
        DOM.Show('muteon_'+Users.PreferenceUpdateID);
        DOM.Hide('muteoff_'+Users.PreferenceUpdateID);
      }
    }
    else
    {
      if (Users.DisplayOpt=='mute_on') clear = true;
      else
      {
        DOM.Show('muteoff_'+Users.PreferenceUpdateID);
        DOM.Hide('muteon_'+Users.PreferenceUpdateID);
      }
    }
    if (Users.PreferenceUpdateSMS)
    {
      if (Users.DisplayOpt=='phone_off') clear = true;
      else
      {
        DOM.Show('smson_'+Users.PreferenceUpdateID);
        DOM.Hide('smsoff_'+Users.PreferenceUpdateID);
      }
    }
    else
    {
      if (Users.DisplayOpt=='phone_on') clear = true;
      else
      {
        DOM.Show('smsoff_'+Users.PreferenceUpdateID);
        DOM.Hide('smson_'+Users.PreferenceUpdateID);
      }
    }
    if (Users.PreferenceUpdateIsGroup) alert("Your membership preferences have been updated");
    else alert("Your follow preferences have been updated");
    if (clear) DOM.Close('user_'+Users.PreferenceUpdateID,null,removeuser);
    else clearvars();
  }

  DOM.Hide('user_loading');
  if (response=="ok")
  {
    Users.ClosePreferences(Users.PreferenceUpdateID,prefsclosed);
  }
  else
  {
    alert("Error:\n"+response);
  }
}

Users.CloseProfile = function(id)
{
  var profile = document.getElementById('profile_link_'+id);
  Users.Profile(profile,id);
  return false;
}

Users.Profile = function(obj,id,donext)
{
  obj.blur();

  function openprof()
  {
    obj.parentNode.className = 'open';
    //DOM.SetValue('profile_open_'+id,1);
    DOM.Open('profile_info_'+id);
  }

  if (DOM.GetValue('profile_open_'+id)>0)
  {
    obj.parentNode.className = 'close';
    DOM.SetValue('profile_open_'+id,0);
    if (donext) DOM.Close('profile_info_'+id,null,donext);
    else DOM.Close('profile_info_'+id);
  }
  else
  {
    var pref = document.getElementById('preference_link_'+id);
    DOM.SetValue('profile_open_'+id,1);
    if (DOM.GetValue('preference_open_'+id)>0) Users.Preferences(pref,id,openprof);
    else openprof();
  }
  return false;
}

Users.ChangeMute = function(id,muted)
{
  if (muted)
  {
    DOM.Show('mute_icon_'+id);
    DOM.Hide('mute_off_icon_'+id);
    for (var t in PREFERENCE_TYPES)
    {
      Forms.Disable('dashboard_'+PREFERENCE_TYPES[t]+'_'+id);
      Forms.Disable('sms_'+PREFERENCE_TYPES[t]+'_'+id);
      Forms.Disable('email_'+PREFERENCE_TYPES[t]+'_'+id);
    }
    DOM.Hide('dashboardsa_'+id);
    DOM.Hide('smssa_'+id);
    DOM.Hide('emailsa_'+id);
    DOM.SetClass('mute_container_'+id,'mute_container muted');
  }
  else
  {
    SMSok = DOM.GetValue('sms_ok_'+id);
    DOM.Hide('mute_icon_'+id);
    DOM.Show('mute_off_icon_'+id);
    for (var t in PREFERENCE_TYPES)
    {
      Forms.Enable('dashboard_'+PREFERENCE_TYPES[t]+'_'+id);
      if (SMSok==1) Forms.Enable('sms_'+PREFERENCE_TYPES[t]+'_'+id);
      Forms.Enable('email_'+PREFERENCE_TYPES[t]+'_'+id);
    }
    DOM.Show('dashboardsa_'+id);
    DOM.Show('smssa_'+id);
    DOM.Show('emailsa_'+id);
    DOM.SetClass('mute_container_'+id,'mute_container');
  }
}

Users.SelectAll = function(opt,id,sel)
{
  if (sel)
  {
    for (var t in PREFERENCE_TYPES)
    {
      Forms.Check(opt+'_'+PREFERENCE_TYPES[t]+'_'+id);
    }
    DOM.Hide(opt+'_sa_'+id);
    DOM.Show(opt+'_dsa_'+id);
  }
  else
  {
    for (var t in PREFERENCE_TYPES)
    {
      Forms.Uncheck(opt+'_'+PREFERENCE_TYPES[t]+'_'+id);
    }
    DOM.Hide(opt+'_dsa_'+id);
    DOM.Show(opt+'_sa_'+id);
  }
  return false;
}

/******************************************************************************/
// ACTION RESPONSE

Users.Follow = function(obj,userid,username)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      alert("You are now following " + username);
      if (Users.RemoveAfterFollow)
      {
        DOM.Close('user_'+userid);
        var div = document.getElementById('user_'+userid);
        div.parentNode.removeChild(div);
      }
      else
      {
        Users.SelectAll('dashboard',userid,true);
        DOM.Show('status_'+userid);
        DOM.Show('preference_link_container_'+userid);
        DOM.Hide('follow_link_'+userid);
      }
    }
    else
    {
      alert("Error:\n"+response);
    }
  }

  obj.blur();
  DOM.Show('user_loading');
  var subscribe = new Ajax(ret);
  var params = "isajax=1";
  var url = "members/subscribe/"+userid;
  subscribe.sendPostRequest(url,params);
  return false;
}

Users.RequestFollow = function(obj,userid,username)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      alert("You have asked to follow " + username);
      DOM.Hide('not_requested_'+userid);
      DOM.Show('sent_request_'+userid);
      DOM.Show('requested_'+userid);
      DOM.Hide('follow_link_'+userid);
    }
    else
    {
      alert("Error:\n"+response);
    }
  }

  obj.blur();
  DOM.Show('user_loading');
  var subscribe = new Ajax(ret);
  var params = "isajax=1";
  var url = "members/request_subscription/"+userid;
  subscribe.sendPostRequest(url,params);
  return false;
}

Users.UnFollow = function(obj,userid,username)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      alert("You are no longer following " + username);
      if (Users.RemoveAfterFollow)
      {
        DOM.Close('user_'+userid);
        var div = document.getElementById('user_'+userid);
        div.parentNode.removeChild(div);
      }
      else
      {
        DOM.Hide('status_'+userid);
        DOM.Hide('preference_link_container_'+userid);
        DOM.Show('follow_link_'+userid);
        DOM.SetClass('preference_link_'+userid,'closed');
      }
    }
    else
    {
      alert("Error:\n"+response);
    }
  }

  function unfollow()
  {
    DOM.Show('user_loading');
    var subscribe = new Ajax(ret);
    var params = "isajax=1";
    var url = "members/cancel_subscription/"+userid;
    subscribe.sendPostRequest(url,params);
  }

  obj.blur();
  if (DOM.GetValue('preference_open_'+userid)>0) Users.ClosePreferences(userid,unfollow);
  else unfollow();
  return false;
}

Users.JoinGroup = function(obj,id,groupname)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      alert("You are now a member of the " + groupname + " group");
      if (Users.RemoveAfterFollow)
      {
        DOM.Close('user_'+id);
        var div = document.getElementById('user_'+id);
        div.parentNode.removeChild(div);
      }
      else
      {
        DOM.Show('status_'+id);
        DOM.Show('preference_link_container_'+id);
        DOM.Hide('join_link_'+id);
      }
    }
    else
    {
      alert("Error:\n"+response);
    }
  }

  obj.blur();
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/join_group/"+id;
  group.sendPostRequest(url,params);
  return false;
}

Users.RequestMembership = function(obj,id,groupname)
{
  function ret(response)
  {
    DOM.Hide('user_loading');

    if (response=='ok')
    {
      alert("You have requested membership in the " + groupname + " group");
      DOM.Hide('not_requested_'+id);
      DOM.Show('sent_request_'+id);
      DOM.Show('requested_'+id);
      DOM.Hide('join_link_'+id);
    }
    else
    {
      alert("Error:\n"+response);
    }
  }

  obj.blur();
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/request_membership/"+id;
  group.sendPostRequest(url,params);
  return false;
}

Users.LeaveGroup = function(obj,id,groupname)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      alert("You are no longer a member of the " + groupname + " group");
      if (Users.RemoveAfterFollow)
      {
        DOM.Close('user_'+id);
        var div = document.getElementById('user_'+id);
        div.parentNode.removeChild(div);
      }
      else
      {
        DOM.Hide('status_'+id);
        DOM.Hide('preference_link_container_'+id);
        DOM.Show('join_link_'+id);
      }
    }
    else
    {
      alert("Error:\n"+response);
    }
  }

  function unfollow()
  {
    DOM.Show('user_loading');
    var leave = new Ajax(ret);
    var params = "isajax=1";
    var url = "groups/leave_group/"+id;
    leave.sendPostRequest(url,params);
  }

  obj.blur();
  if (DOM.GetValue('preference_open_'+id)>0) Users.ClosePreferences(id,unfollow);
  else unfollow();
  return false;
}




