var Profile = {};

Profile.Page = "";
Profile.CurrentPage = 1;
Profile.CurrentFilter = "";
Profile.FilterIsText = false;
Profile.IsRefresh = false;
Profile.DisplayOpt = "short_list";

//User.Swap = 1;

Profile.MenuOpts = {};
// top level

Profile.MenuOpts.timeline = 'Timeline';
Profile.MenuOpts.albums = "%NAME%'s Photo Albums";
Profile.MenuOpts.description = "About %NAME%";
Profile.MenuOpts.friends = "%NAME%'s Friends";
Profile.MenuOpts.following = "%NAME% is Following";
Profile.MenuOpts.followers = "%NAME%'s Followers";
Profile.MenuOpts.groups = "%NAME%'s Groups";
Profile.MenuOpts.report = "Report %NAME%";
Profile.MenuOpts.block = "Block %NAME%";
Profile.MenuOpts.manage = "Manage Follow Settings";
Profile.MenuOpts.unsubscribe = "No longer following";

//Profile.MenuOpts.all = "All of %NAME%'s messages";
Profile.MenuOpts.sent = 'Messages sent by %NAME%';
Profile.MenuOpts.received = 'Messages received by %NAME%';
Profile.MenuOpts.rsent = 'Replies sent by %NAME%';
Profile.MenuOpts.rreceived = 'Replies received by %NAME%';
Profile.MenuOpts.ssent = 'Shares sent by %NAME%';
Profile.MenuOpts.sreceived = "Shares received by %NAME%";
Profile.MenuOpts.who = "Who shared %NAME%'s messages";

Profile.Empty = {};
Profile.Empty.sent = "A list of the public messages %NAME% sends will be displayed here.";
Profile.Empty.received = "A list of the public messages %NAME% receives will be displayed here.";
Profile.Empty.rsent = "A list of public replies %NAME% sends will be displayed here.";
Profile.Empty.rreceived = "A list of public replies %NAME% receives will be displayed here.";
Profile.Empty.ssent = "A list of the public messages %NAME% shares will be displayed here.";
Profile.Empty.sreceived = "A list of public messages that are shared with %NAME% will be displayed here.";


Profile.Init = function(id,username,isme,isprivate)
{
  Profile.IsMe = isme;
  Profile.ID = id;
  Profile.IsPrivate = isprivate;
  Profile.UserName = username;
  if (location.hash != "")
  {
    Profile.CloseCurrent();
    var str = location.hash.substring(1).split('/');
    Profile.Current = str[0];
    if (str[1]) Profile.Sub = str[1];
    else if (Profile.Current=='timeline')
    {
      if (str[1] && str[1]!='') Profile.Sub = str[1];
      else Profile.Sub = "sent";
    }
    else Profile.Sub = "";

    if (str[2]) Profile.SubSub = str[2]; else Profile.SubSub = "";
  }
  else if (Profile.IsMe)
  {
    Profile.Current = "description";
    Profile.Sub = "";
    Profile.SubSub = "";
  }
  else
  {
    Profile.Current = "timeline";
    Profile.Sub = "sent";
    Profile.SubSub = "";
  }
  var loc = 'http://'+location.hostname+location.pathname+"#"+Profile.Current;
  if (Profile.Sub)
  {
    loc += "/"+Profile.Sub;
    if (Profile.SubSub) loc += "/"+Profile.SubSub;
  }
  location = loc;
  Profile.CurrentLocation = location+"";
  Profile.OpenCurrent();
  setTimeout(Profile.WentBack,100);
  Profile.Updater = setTimeout(Profile.Update.GetUpdate,UPDATE_INTERVAL);
  Profile.DisplayOpt = "short_list";
  if (!Profile.IsPrivate || Profile.Current=='report') Profile.GetPage();
}

Profile.WentBack = function()
{
  if (Profile.CurrentLocation != location)
  {
    var str = location.hash.substring(1).split('/');
    var nav = 'profile_'+str[0];
    if (str[1]) nav += '_'+str[1];
    if (str[2]) nav += '_'+str[2];
    Profile.Navigate(nav);
  }
  setTimeout(Profile.WentBack,100);
}

Profile.CloseCurrent = function()
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
  DOM.SetClass('profile_'+Profile.Current,"");
  DOM.SetClass('profile_'+Profile.Current+'_'+Profile.Sub,"");
  DOM.Hide('nav_profile_'+Profile.Current+'_'+Profile.Sub);
  DOM.Hide('nav_profile_'+Profile.Current);
  DOM.Hide('manage_link_off');
  DOM.Show('manage_link');
  DOM.Hide('message_filter_container');
  DOM.Hide('message_filters');
  DOM.Hide('user_filter_container');
  if (Profile.IsReported)
  {
    DOM.Show('reported_text');
    //DOM.Hide('report_link_off');
    DOM.Hide('report_link');
  }
  else
  {
    DOM.Hide('reported_text');
    //DOM.Hide('report_link_off');
    DOM.Show('report_link');
  }
}

Profile.OpenCurrent = function()
{
  if (Profile.Sub) DOM.SetClass('profile_'+Profile.Current+'_'+Profile.Sub,"active");
  else DOM.SetClass('profile_'+Profile.Current,"active");
  DOM.Show('nav_profile_'+Profile.Current);
}

Profile.Navigate = function(which)
{
  //if (which.className && which.className=='active') return false;
  if (typeof(which)=="string")
  {
    //if (which=='profile_description' && Profile.Current=='description') return false;
    //if (which=='profile_timeline_sent' && Profile.Current=='timeline' && Profile.Sub=='sent') return false;
    var currents = which.split('_');
  }
  else var currents = which.id.split('_');

  // close current selection
  Profile.CloseCurrent();
  Profile.Current = currents[1];
  if (currents[2]) Profile.Sub = currents[2]; else Profile.Sub = "";
  if (currents[3]) Profile.SubSub = currents[3]; else Profile.SubSub = "";
  if (Profile.Current=='timeline')
  {
    if (Profile.Sub=='') Profile.Sub = 'sent';
  }
  // save to browser history
  var loc = 'http://'+location.hostname+location.pathname+"#"+Profile.Current;
  if (Profile.Sub)
  {
    loc += "/"+Profile.Sub;
    if (Profile.SubSub) loc += "/"+Profile.SubSub;
  }
  location = loc;
  Profile.CurrentLocation = location+"";
  if (Profile.IsPrivate && currents[1]!='report')
  {
    DOM.Show('right_col');
    DOM.SetClass('content_div','content_with_rcol');
    DOM.SetHTML('profile_content','');
    DOM.Show('private_content');
    return false;
  }

  clearTimeout(Messages.Refresh);
  Profile.OpenCurrent();

  DOM.Hide('clear_message_filter');
  DOM.SetValue('message_filter','');
  DOM.Hide('clear_user_filter');
  DOM.Show('filter_user_filter');
  DOM.SetValue('user_filter','Filter');
  DOM.SetClass('user_filter','');
  Users.FilterIsOpen = false;

  Profile.CurrentPage = 1;
  Profile.CurrentFilter = "";
  Profile.FilterIsText = false;

  // initiate AJAX call
  Profile.GetPage();
  return false;
}

Profile.GetPage = function()
{
  var loc = location.hash.substring(1).split('/');
  if (!loc[0])
  {
    location = HTTP_BASE+"members/profile/"+Profile.ID;
  }
  else
  {
    var url = "members/"+loc[0]+"/"+Profile.ID;
    if (loc[1]) url += "?type="+loc[1];
    else if (Profile.Sub != '') url += "?type=" + Profile.Sub;
    var params = "isajax=1";
    params += "&page="+Profile.CurrentPage;
    params += "&filter="+Profile.CurrentFilter;
    params += "&display="+Profile.DisplayOpt;
    params += "&initial_load=1";
    if (!Profile.IsRefresh) DOM.Show('user_loading');
    Profile.Ajax.sendPostRequest(url,params);
  }
}

Profile.GetResults = function(response)
{
  DOM.Hide('user_loading');
  var content = document.getElementById('profile_content');
  if (content) content.innerHTML = response;

  if (Profile.IsPrivate && Profile.Current=='report') DOM.Hide('private_content');
  if (Profile.IsMe && Profile.Current=='description') DOM.SetClass('profile_photo','active');
  else if (Profile.Current=='timeline' && Profile.Sub=='sent') DOM.SetClass('profile_photo','active');
  else DOM.SetClass('profile_photo','');

  if (Profile.Sub) DOM.SetHTML('content_header',Profile.MenuOpts[Profile.Sub].replace(/%NAME%/,Profile.UserName));
  else DOM.SetHTML('content_header',Profile.MenuOpts[Profile.Current].replace(/%NAME%/,Profile.UserName));
  if (Profile.Current=='timeline')
  {
    //var nmt=document.getElementById('no_message_text');
    DOM.SetHTML('no_message_text',Profile.Empty[Profile.Sub].replace(/%NAME%/,Profile.UserName));
  }
  switch (Profile.Current)
  {
    case "timeline"       : Profile.Timeline.Init(); break;
    case "albums"         : Profile.Albums.Init(); break;
    case "description"    : Profile.Description.Init(); break;
    case "friends"        : Profile.Friends.Init(); break;
    case "following"      : Profile.Following.Init(); break;
    case "followers"      : Profile.Followers.Init(); break;
    case "groups"         : Profile.Groups.Init(); break;
    case "report"         : Profile.ReportMember.Init(); break;
    case "manage"         : Profile.ManageSubscription.Init(); break;
  }
  if (Profile.Current=='manage') DOM.SetClass('follow_preferences','highlightlink');
  else DOM.SetClass('follow_preferences','');
  if (Profile.IsRefresh) Profile.IsRefresh = false;
  else scrollTo(0,0);
}

Profile.Paginate = function(listpage,filtervalue,istext)
{
  Profile.CurrentPage = listpage;
  if (filtervalue!=undefined) Profile.CurrentFilter = filtervalue;
  if (istext!=undefined) Profile.FilterIsText = istext;
  Profile.GetPage();
}

Profile.Block = function(id,username)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=="ok")
    {
      DOM.Hide('block_member');
      DOM.Show('unblock_member');
      DOM.Hide('follow_link');
      DOM.Show('unfollow_link');
      DOM.Hide('follow_preferences');
      DOM.Hide('status_following');
      DOM.Show('status_not_following');
      Profile.CloseCurrent();
      alert("You have blocked "+username);
      location = HTTP_BASE;
    }
    else alert("Error:\n"+response);
  }

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

Profile.UnBlock = function(id,username)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=="ok")
    {
      alert("You have unblocked "+username);
      location.reload();
    }
    if (response=="ok")
    {
      DOM.Show('block_member');
      DOM.Hide('unblock_member');
    }
    else alert("Error:\n"+response);
  }

  DOM.Show('user_loading');
  var profile = new Ajax(ret);
  var params = "isajax=1";
  var url = "members/unblock/"+id;
  profile.sendPostRequest(url,params);
  return false;
}

Profile.Blocked = function(username)
{
  alert(username + " has blocked you");
  return false;
}

Profile.QueueFeatured = function(id)
{
  var queue = new Ajax(Profile.QueueResponse);
  var params = "isajax=1";
  var url = "admin/queue_add/"+id;
  queue.sendPostRequest(url,params);
  return false;
}

Profile.QueueResponse = function(response)
{
  if(response == 'ok')
  {
    DOM.Hide('queue_featured');
    DOM.Show('queued_featured');
  }
  else
  {
    alert("Error: " + response)
  }
}

Profile.Suspend = function(id,username)
{
  function ret(response)
  {
    if (response == 'ok')
    {
      alert(username + " has been suspended");
      DOM.Hide('suspend_user');
      DOM.Show('suspended_user');
    }
    else
    {
      alert("Error: " + response);
    }
  }

  if (confirm("Really suspend "+username+"?"))
  {
    var susp = new Ajax(ret);
    var params = "isajax=1";
    params += "&id="+id;
    var url = "admin/suspend/"+id;
    susp.sendPostRequest(url,params);
  }
  return false;
}

Profile.Report = function(obj,id)
{
  obj.blur();
  Profile.Navigate('profile_report');
  return false;
}

Profile.Manage = function(obj,id)
{
  obj.blur();
  Profile.Navigate('profile_manage');
  return false;
}

Profile.Subscribe = function(id)
{
  function ret(response)
  {
    if (response=='ok')
    {
      DOM.Hide('follow_link');
      DOM.Show('follow_preferences');
      DOM.Show('unfollow_link');
      DOM.Show('status_following');
      DOM.Hide('status_not_following');
      Profile.Navigate('profile_manage');
    }
    else
    {
      DOM.Hide('user_loading');
      alert("Error:\n"+response);
    }
  }

  DOM.Show('user_loading');
  var profile = new Ajax(ret);
  var params = "isajax=1";
  var url = "members/subscribe/"+id;
  profile.sendPostRequest(url,params);

  return false;
}

Profile.RequestSubscription = function(id)
{
  var request = new Ajax(Profile.SubscriptionRequested);
  var params = "isajax=1";
  var url = "members/request_subscription/"+id;
  request.sendPostRequest(url,params);
  return false;
}

Profile.SubscriptionRequested = function(response)
{
  if (response=='ok')
  {
    DOM.Hide('status_not_following');
    DOM.Hide('follow_link');
    DOM.Hide('private_content');
    DOM.Show('sub_requested');
    DOM.Show('status_pending');
  }
  else alert("Error:\n\n"+response);
}

Profile.CancelRequest = function(id)
{
  var request = new Ajax(Profile.RequestCanceled);
  var params = "isajax=1";
  var url = "members/cancel_subscription_request/"+id;
  request.sendPostRequest(url,params);
  return false;
}

Profile.RequestCanceled = function(response)
{
  if (response=='ok')
  {
    DOM.Hide('unfollow_link');
    DOM.Show('follow_link');
    DOM.Hide('cancel_follow_link');
  }
  else alert("Error:\n\n"+response);
}

Profile.PageReturn = function(response)
{
  alert("Unexpected call to Profile.PageReturn in profile.js");
  return false;
}

Profile.Unsubscribe = function(id,username)
{
  var str = "Are you sure you want to stop following "+username+"?";
  if (confirm(str))
  {
    var subscription = new Ajax(Profile.UnsubscribeReturn);
    var url = "members/cancel_subscription/"+Profile.ID;
    var params = "isajax=1";
    DOM.Show('user_loading');
    subscription.sendPostRequest(url,params);
  }
  return false;
}

Profile.UnsubscribeReturn = function(response)
{
  DOM.Hide('user_loading');
  if (response=="ok")
  {
    DOM.Hide('unfollow_link');
    DOM.Show('follow_link');
    DOM.Hide('follow_preferences');
    DOM.Hide('status_following');
    DOM.Show('status_not_following');
    Profile.Navigate('profile_manage_unsubscribe');
  }
  else
  {
    alert("Error:\n"+response);
  }
}

Profile.PauseUpdates = function()
{
  if (Profile.Current=='timeline') Messages.PauseUpdates();
}

Profile.ResumeUpdates = function()
{
  if (Profile.Current=='timeline') Messages.ResumeUpdates();
}

Profile.Ajax = new Ajax(Profile.GetResults);

/********************************** UPDATER  **********************************/

Profile.Update = {};

Profile.Update.GetUpdate = function()
{
  var update = new Ajax(Profile.Update.GotUpdate);
  var url = "ajax/profile_update/"+Profile.ID;
  update.sendRequest(url);
}

Profile.Update.GotUpdate = function(response)
{
  if (response=='badid')
  {
    alert("Error: Invalid User ID");
  }
  else
  {
    var update = response.jsonParse();
    DOM.SetHTML('profile_album_count',update.albums);
    DOM.SetHTML('profile_friend_count',update.friends);
    DOM.SetHTML('profile_follower_count',update.followers);
    DOM.SetHTML('profile_group_count',update.groups);
    DOM.SetHTML('profile_following_count',update.following);
    Profile.Updater = setTimeout(Profile.Update.GetUpdate,UPDATE_INTERVAL);
  }
}
