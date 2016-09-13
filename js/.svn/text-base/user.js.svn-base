var User = {};

User.CurrentPage = 1;
User.CurrentFilter = "";
User.DisplayOpt = "short_list";

User.MenuOpts = {};
// top level
User.MenuOpts.timeline = 'Timeline';
User.MenuOpts.people='People';
User.MenuOpts.groups='Groups';
User.MenuOpts.invite='Invite';
User.MenuOpts.alerts='Alerts';
User.MenuOpts.settings='Edit Settings';
User.MenuOpts.profile='Edit Profile';
User.MenuOpts.photo='Photos';

// filters
User.MenuOpts.received = "Messages to me";
User.MenuOpts.sent = "Messages sent";
User.MenuOpts.rreceived = "Replies to me";
User.MenuOpts.rsent = "Replies sent";
User.MenuOpts.thread = "Threads I'm in";
User.MenuOpts.notifications = "General Notifications";

// people
User.MenuOpts.friends='Friends';
User.MenuOpts.followers='Followers';
User.MenuOpts.following='Following';
User.MenuOpts.featured='Featured Members';
User.MenuOpts.fof='Friends of Friends';

// groups
User.MenuOpts.publicgroups='My Public Groups';
User.MenuOpts.privategroups='My Private Groups';
User.MenuOpts.admingroups='Groups I Own or Administer';
User.MenuOpts.featuredgroups='Featured Groups';
User.MenuOpts.friendgroups="Friend's Groups";
User.MenuOpts.create='Create a Group';

// invite
User.MenuOpts.emailcontacts='Invite Email Contacts';
User.MenuOpts.socialnetwork='Invite from Social Networks';
User.MenuOpts.email='Email an invitation';
User.MenuOpts.share='Share an Invitation Link';


// settings
User.MenuOpts.profileinfo='Profile Information';
User.MenuOpts.mobile='Mobile Phone';
User.MenuOpts.message='General Message Settings';
User.MenuOpts.notifications='General Notification Settings';
User.MenuOpts.profilephoto='Profile Photo';
User.MenuOpts.albums='Photo Albums';
User.MenuOpts.twitter='Twitter';
User.MenuOpts.facebook='Facebook';
User.MenuOpts.rss='RSS';
User.MenuOpts.username='Change Username';
User.MenuOpts.password='Change Password';
User.MenuOpts.emailaddress='Account Email Address';
User.MenuOpts.refreshrate='Refresh Mode';
User.MenuOpts.cancel='Cancel Account';

User.Empty = {};
User.Empty.timeline = "A list of all of the messages you have sent and received will be displayed here.";
User.Empty.received = "A list of all the messages sent specifically to you will be displayed here.";
User.Empty.sent = "A list of all the messages you send will be displayed here.";
User.Empty.rreceived = "A list of all the replies to your original message will be displayed here.";
User.Empty.rsent = "A list of all the replies you send will be displayed here.";
User.Empty.thread = "Replies by other members in conversations you participate in, other than those you started, will be displayed here.";
User.Empty.notifications = "Notifications about new followers, invitations, follow requests, etc., will be displayed here.";

User.Empty.people = {};
User.Empty.people.friends = {};
User.Empty.people.friends.unfiltered = "A list of your friends (members you follow who also follow you) will be displayed here.";
User.Empty.people.friends.phone_on = "You are not receiving SMS Text messages from any of your friends";
User.Empty.people.friends.phone_off = "You are receiving SMS Text messages from all of your friends";
User.Empty.people.friends.mute_off = "All of your friends are on Mute";
User.Empty.people.friends.mute_on = "None of your friends are on Mute";

User.Empty.people.following = {};
User.Empty.people.following.unfiltered = "A list of the members you follow will be displayed here.";
User.Empty.people.following.phone_on = "You are not receiving SMS Text messages from any of the members you are following.";
User.Empty.people.following.phone_off = "You are receiving SMS Text messages from all of the members you are following.";
User.Empty.people.following.mute_on = "None of the members you follow are on Mute.";
User.Empty.people.following.mute_off = "All of the members you follow are on Mute.";

User.Empty.people.followers = {};
User.Empty.people.followers.unfiltered = "A list of the members that are following you will be displayed here.";

User.Empty.people.featured = {};
User.Empty.people.featured.unfiltered = "";
User.Empty.people.fof = {};
User.Empty.people.fof.unfiltered = "A list of friends of your friends will be displayed here.";

User.Empty.groups = {};
User.Empty.groups.publicgroups = {};
User.Empty.groups.publicgroups.unfiltered = "A list of the public groups you are a member of will be displayed here.";
User.Empty.groups.publicgroups.phone_on = "You are not receiving SMS Text messages from any of your public groups.";
User.Empty.groups.publicgroups.phone_off = "You are receiving SMS Text messages from all of your public groups.";
User.Empty.groups.publicgroups.mute_on = "None of your public groups are on Mute.";
User.Empty.groups.publicgroups.mute_off = "All of your public groups are on Mute.";

User.Empty.groups.privategroups = {};
User.Empty.groups.privategroups.unfiltered = "A list of the private groups you are a member of will be displayed here.";
User.Empty.groups.privategroups.phone_on = "You are not receiving SMS Text messages from any of your private groups.";
User.Empty.groups.privategroups.phone_off = "You are receiving SMS Text messages from all of your private groups.";
User.Empty.groups.privategroups.mute_on = "None of your private groups are on Mute.";
User.Empty.groups.privategroups.mute_off = "All of your private groups are on Mute.";

User.Empty.groups.admingroups = {};
User.Empty.groups.admingroups.unfiltered = "A list of the groups you own or administer will be displayed here.";
User.Empty.groups.admingroups.phone_on = "You are not receiving SMS Text messages from any of the groups you own or administer.";
User.Empty.groups.admingroups.phone_off = "You are receiving SMS Text messages from all of the groups you own or administer.";
User.Empty.groups.admingroups.mute_on = "None of the groups you own or administer are on Mute.";
User.Empty.groups.admingroups.mute_off = "All of the groups you own or administer are on Mute.";

User.Empty.groups.featuredgroups = {};
User.Empty.groups.featuredgroups.unfiltered = "";

User.Empty.groups.friendgroups = {};
User.Empty.groups.friendgroups.unfiltered = "A list of groups your friends belong to will be displayed here.";

User.MessageLists = { 'timeline':'1', 'received':'1', 'rreceived':'1', 'sent':'1', 'rsent':'1', 'thread':'1', 'notifications':'1' };

User.Init = function()
{
  if (location.hash != "")
  {
    User.CloseCurrent();
    var str = location.hash.substring(1).split('/');
    User.CurrentMain = str[0];
    if (str[1]) User.CurrentSub = str[1]; else User.CurrentSub = "";
  }
  else
  {
    User.CurrentMain = "timeline";
    User.CurrentSub = "";
  }

  var loc = 'http://'+location.hostname+location.pathname+"#"+User.CurrentMain;
  if (User.CurrentSub)
  {
    loc += "/" + User.CurrentSub;
  }
  location = loc;
  User.CurrentLocation = location+"";
  User.OpenCurrent();
  setTimeout(User.WentBack,100);
  User.Updater = setTimeout(User.Update.GetUpdate,UPDATE_INTERVAL);
  User.GetPage();
}

User.WentBack = function()
{
  if (User.CurrentLocation != location)
  {
    var str = location.hash.substring(1).split('/');
    var nav = 'user_'+str[0];
    if (str[1]) nav += '_'+str[1];
    User.Navigate(nav);
  }
  setTimeout(User.WentBack,100);
}

User.CloseCurrent = function()
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
  DOM.SetClass('user_'+User.CurrentMain,"");
  DOM.SetClass('user_'+User.CurrentMain+'_'+User.CurrentSub,"");
  DOM.Hide('nav_user_'+User.CurrentMain+'_'+User.CurrentSub);
  DOM.Hide('nav_user_'+User.CurrentMain);
  DOM.Hide('edit_off');
  DOM.Show('edit_on');
}

User.OpenCurrent = function()
{
  if (User.CurrentMain=='timeline')
  {
    DOM.Show('message_form');
    DOM.Show('messageform_divider');
  }
  else
  {
    DOM.Hide('message_form');
    DOM.Hide('messageform_divider');
  }

  if (User.CurrentMain=='timeline')
  {
    DOM.SetClass('user_'+User.CurrentMain,"active");
  }
  else if (User.CurrentSub!="")
  {
    DOM.SetClass('user_'+User.CurrentMain+'_'+User.CurrentSub,"active");
  }
  else DOM.SetClass('user_'+User.CurrentMain,"active");

  DOM.Show('nav_user_'+User.CurrentMain+'_'+User.CurrentSub);
  DOM.Show('nav_user_'+User.CurrentMain);
  if (User.CurrentMain=='profile')
  {
    DOM.Show('editprofile_selected');
    DOM.Hide('editprofile_unselected');
  }
  else
  {
    DOM.Hide('editprofile_selected');
    DOM.Show('editprofile_unselected');
  }
}

User.Navigate = function(which)
{
  clearTimeout(Messages.Refresh);
  // close current selection
  User.CloseCurrent();

  // open new selection
  if (typeof(which)=="string") var currents = which.split('_');
  else var currents = which.id.split('_');
  User.Last = User.CurrentMain;
  User.CurrentMain = currents[1];
  if (currents[2]) User.CurrentSub = currents[2];
  else
  {
    switch (User.CurrentMain)
    {
      case 'timeline' : User.CurrentSub = ''; break;
      case 'upload'   : User.CurrentSub = 'link'; break;
      case 'people'   : User.CurrentSub = 'friends'; break;
      case 'groups'   : User.CurrentSub = 'publicgroups'; break;
      case 'invite'   : User.CurrentSub = 'emailcontacts'; break;
      case 'settings' : User.CurrentSub = 'mobile'; break;
      case 'profile'  : User.CurrentSub = 'profileinfo'; break;
      case 'photo'    : User.CurrentSub = 'profilephoto'; break;
      case 'twitter'  : User.CurrentSub = ''; break;
      case 'facebook' : User.CurrentSub = ''; break;
      case 'rss'      : User.CurrentSub = ''; break;
      default         : User.CurrentSub = "";
    }
  }

  // save to browser history
  var loc = 'http://'+location.hostname+location.pathname+"#"+User.CurrentMain;
  if (User.CurrentSub)
  {
    loc += "/" + User.CurrentSub;
  }
  location = loc;
  User.CurrentLocation = location+"";
  User.OpenCurrent();

  // clear filter
  DOM.Hide('clear_message_filter');
  DOM.SetValue('message_filter','');
  DOM.Hide('clear_message_filter');
  DOM.Show('filter_message_filter');
  DOM.SetValue('message_filter','Filter');
  DOM.SetClass('message_filter','');
  DOM.Hide('clear_user_filter');
  DOM.Show('filter_user_filter');
  DOM.SetValue('user_filter','Filter');
  DOM.SetClass('user_filter','');

  User.CurrentPage = 1;
  User.CurrentFilter = "";
  User.DisplayOpt = "short_list";

  // initiate AJAX call
  User.GetPage();
}

User.GetPage = function()
{
  var loc = location.hash.substring(1).split('/');
  var url = "user/"+loc[0];
  if (loc[1]) url += "/"+loc[1];

  var params = "isajax=1";
  params += "&page="+User.CurrentPage;
  params += "&filter="+User.CurrentFilter;
  params += "&display="+User.DisplayOpt;
  params += "&initial_load=1";
  if (!User.IsRefresh) DOM.Show('user_loading');
  User.Ajax.sendPostRequest(url,params);
}

User.GetResults = function(response)
{
  DOM.Hide('user_loading');

  switch (User.CurrentMain)
  {
    case 'photo':
    case 'groups':
      if
      (
        (User.CurrentMain == 'photos' && User.CurrentSub!='albums') ||
        (User.CurrentMain == 'groups' && User.CurrentSub!='create')
      )
      {
        DOM.Show('right_col');
        DOM.SetClass('content_div','content_with_rcol');
        break;
      }
    case 'alerts':
    case 'settings':
    case 'profile':
    case 'twitter':
    case 'rss':
    case 'invite':
      DOM.Hide('right_col');
      DOM.SetClass('content_div','content_no_rcol');
      break;
    default:
      DOM.Show('right_col');
      DOM.SetClass('content_div','content_with_rcol');
      break;
  }


  var content = document.getElementById('user_content');
  if (content) content.innerHTML = response;

  var head = document.getElementById('content_header');
  if (head)
  {
    if (User.CurrentMain != 'timeline')
    {
      if (User.CurrentSub!="") var htm = User.MenuOpts[User.CurrentSub];
      else var htm = User.MenuOpts[User.CurrentMain];
    }
    else htm = 'Timeline';
    head.innerHTML = htm;
  }

  DOM.SetHTML(User.CurrentMain+'_x','0');
  DOM.Hide(User.CurrentMain+'_count');
  Forms.SetValue('last_'+User.Last,Forms.GetValue('page_updatetime'));

  switch (User.CurrentMain)
  {
    case 'timeline':
    case 'received':
    case 'rreceived':
    case 'sent':
    case 'rsent':
    case 'thread':
    case 'notifications':
      Messages.Init('user');
      DOM.SetHTML('no_message_text',User.Empty[User.CurrentMain]);
      //if (User.CurrentSub!="") MessageForm.ChangeType(User.CurrentSub,true);
      break;
    case 'people':
      Users.Init('user');
      if (Users.CurrentFilter=='')
      {
        if (User.DisplayOpt=='short_list' || User.DisplayOpt=='long_list') var displayopt = 'unfiltered';
        else var displayopt = User.DisplayOpt;
        DOM.SetHTML('no_users',User.Empty.people[User.CurrentSub][displayopt]);
      }
      else DOM.SetHTML('no_users','No matching results');
      break;
    case 'groups':
      if (User.CurrentSub=='create') User.CreateGroup.Init();
      else
      {
        Users.Init('user');
        if (User.CurrentFilter=='')
        {
          if (User.DisplayOpt=='short_list' || User.DisplayOpt=='long_list') var displayopt = 'unfiltered';
          else var displayopt = User.DisplayOpt;
          DOM.SetHTML('no_users',User.Empty.groups[User.CurrentSub][displayopt]);
        }
        else DOM.SetHTML('no_users','No matching results');
      }
      break;
    case 'invite':
      User.Invite.Init();
      break;
    case 'alerts':
      User.Alerts.Init();
      break;
    case 'photo':
    case 'settings':
    case 'profile':
    case 'twitter':
    case 'rss':
      User.Settings.Init();
      break;
  }

  if (User.IsRefresh) User.IsRefresh = false;
  else scrollTo(0,0);
}

User.ResetLeftNav = function()
{
  DOM.SetClass('user_'+User.CurrentMain+'_'+User.CurrentSub,"");
  var div = document.getElementById('wrapper');
  if (div)
  {
    div.scrollTop = 0;
  }
}

User.Paginate = function(listpage,filtervalue)
{
  User.CurrentPage = listpage;
  if (filtervalue!=undefined) User.CurrentFilter = filtervalue;
  User.GetPage();
}

User.PauseUpdates = function()
{
  if (User.MessageLists[User.CurrentMain]) Messages.PauseUpdates();
}

User.ResumeUpdates = function()
{
  if (User.MessageLists[User.CurrentMain]) Messages.ResumeUpdates();
}

User.Ajax = new Ajax(User.GetResults);

/********************************** UPDATER  **********************************/

User.Update = {};

User.Update.GetUpdate = function()
{
  var update = new Ajax(User.Update.GotUpdate);
  var url = "ajax/user_update";
  var params = "current="+User.CurrentMain;
  params += "&last_received="+Forms.GetValue('last_received');
  params += "&last_rreceived="+Forms.GetValue('last_rreceived');
  params += "&last_thread="+Forms.GetValue('last_thread');
  params += "&last_notifications="+Forms.GetValue('last_notifications');
  update.sendPostRequest(url,params);
}

User.Update.GotUpdate = function(response)
{
  if (response=='badid')
  {
    alert("Error: Invalid User ID");
  }
  else
  {
    var update = response.jsonParse();
    DOM.SetHTML('user_friend_count',update.friends);
    DOM.SetHTML('user_follower_count',update.followers);
    DOM.SetHTML('user_following_count',update.following);
    DOM.SetHTML('user_public_count',update.public);
    DOM.SetHTML('user_private_count',update.private);
    DOM.SetHTML('received_x',(update.received>LOTS?'lots':update.received+' new'));
    DOM.SetHTML('rreceived_x',(update.rreceived>LOTS?'lots':update.rreceived+' new'));
    DOM.SetHTML('thread_x',(update.thread>LOTS?'lots':update.thread+' new'));
    DOM.SetHTML('notifications_x',(update.notifications>LOTS?'lots':update.notifications+' new'));
    (update.received>0) ? DOM.Show('received_count') : DOM.Hide('received_count');
    (update.rreceived>0) ? DOM.Show('rreceived_count') : DOM.Hide('rreceived_count');
    (update.thread>0) ? DOM.Show('thread_count') : DOM.Hide('thread_count');
    (update.notifications>0) ? DOM.Show('notifications_count') : DOM.Hide('notifications_count');
    User.Updater = setTimeout(User.Update.GetUpdate,UPDATE_INTERVAL);
  }
}