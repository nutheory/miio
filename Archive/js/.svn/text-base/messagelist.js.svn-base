var Messages = {};

Messages.IsPaused = false;
Messages.ReplyHTML =  "<a class='avatar' href='members/profile/%POSTERID%'>" +
                      "  <img src='%AVATAR%' onmouseover='ImageHighlight(this,true)' onmouseout='ImageHighlight(this,false)' width='50' height='50'>" +
                      "</a>" +
                      "<div>" +
                      "  <p><a href='members/profile/%POSTERID%'>%POSTERNAME%</a></p>" +
                      "  <p class='subtext'>%MESSAGETEXT%</p>" +
                      "</div>" +
                      "<div class='sent'>" +
                      "  <span>%TIMESTR%</span> %DELETE%" +
                      "</div>";

Messages.ShareHTML =  "<a class='avatar' href='members/profile/%POSTERID%'>" +
                      " <img src='%AVATAR%' onmouseover='ImageHighlight(this,true)' onmouseout='ImageHighlight(this,false)' width='50' height='50'>" +
                      "</a>" +
                      "<div>" +
                      "  <p><span><a href='members/profile/%POSTERID%'>%POSTERNAME%</a> " +
                      "  <a href='messages/view/%SHAREID%'?opt=share>shared</a> %WITHCOMMENT%" +
                      "  <a href='messages/view/%SHAREID%'?opt=share>comment</a>:</span></p>" +
                      "  %COMMENT%" +
                      "</div>" +
                      "<div class='sent'>" +
                      "  <span>%TIMESTR% %SOURCE%</span> %DELETE%" +
                      "</div>";


Messages.DeleteHTML = "<a href='#' class='delete' onclick='return Messages.Delete(%ID%,-1,false%TYPE%);'><img src='images/delete.png' title='Delete' alt='delete'></a>";
Messages.UpdateCount = 0;
Messages.ForceUpdate = false;

Messages.FilterTypes = ['text','review','question','link','photo','video','location','rss','alert','group','share'];

Messages.Init = function(page)
{
  clearTimeout(Messages.Refresh);
  Messages.MainPage = page;
  Messages.LastCheck = DOM.GetValue('last_check');
  Messages.LastPost = DOM.GetValue('last_message');
  Messages.LastMessage = Messages.LastPost;
  Messages.Top = DOM.GetValue('top_message');
  //Messages.Bottom = DOM.GetValue('bottom_message');
  Messages.Opt = "";
  Messages.SelectedType = "";

  DOM.Hide('user_filter_container');
  DOM.SetValue('message_filter','Filter');
  DOM.Hide('people_friends');
  DOM.Hide('people_subscriptions');
  DOM.Hide('people_subscribers');
  DOM.Hide('groups_publicgroups');
  DOM.Hide('groups_privategroups');

  switch (Messages.MainPage)
  {
    case 'user':
      if (User.CurrentMain=='timeline')
      {
        DOM.Show('message_filter_container');
        DOM.Hide('message_filters');
        DOM.Show('show_timeline_filters');
        DOM.Hide('hide_timeline_filters');
        DOM.Hide('timeline_filters');
        for (var t=0;t<Messages.FilterTypes.length;t++)
        {
          DOM.Show('select_'+Messages.FilterTypes[t]);
          DOM.SetClass('select_'+Messages.FilterTypes[t],'unselected');
        }
        DOM.SetClass('select_','selected');
        if (MessageForm)
        {
          MessageForm.MessageType=User.CurrentSub;
          MessageForm.Init();
        }
      }
      else
      {
        DOM.Show('message_filter_container');
        DOM.Show('message_filters');
        DOM.Show('show_timeline_filters');
        DOM.Hide('hide_timeline_filters');
        DOM.Hide('timeline_filters');
      }

      break;
    case 'groups':
      if (Group.Current=='timeline')
      {
        DOM.Show('message_filter_container');
        DOM.Show('message_filters');
        DOM.Show('show_timeline_filters');
        DOM.Hide('hide_timeline_filters');
        DOM.Hide('timeline_filters');
        DOM.Hide('select_group');
        DOM.Hide('select_alert');
        DOM.Hide('select_share');
        if (MessageForm && Group.IsMember)
        {
          DOM.Show('message_form');
          MessageForm.MessageType=Group.Sub;
          MessageForm.Init();
        }
      }
      break;
    case 'members':
      if (Profile.Current=='timeline')
      {
        DOM.Show('message_filter_container');
        DOM.Show('message_filters');
        DOM.Show('show_timeline_filters');
        DOM.Hide('hide_timeline_filters');
        DOM.Hide('timeline_filters');
        DOM.Show('select_group');
        DOM.Hide('select_alert');
        DOM.Show('select_share');
        if (MessageForm)
        {
          DOM.Show('message_form');
          MessageForm.MessageType=Profile.Sub;
          MessageForm.Init();
        }
      }
      break;
    case 'tabs':
      Messages.Opt = Tabs.Tab;
      if (Tabs.Tab=='discussed' || Tabs.Tab=='shared') Messages.NoRefresh = true;
      else Messages.NoRefresh = false;
      break;
    case 'search':
      break;
  }

  Messages.TotalMessages = DOM.GetValue('total_messages');
  Messages.UpdateIsOpen = false;
  Messages.ScrollToBottom();
  Messages.CurrentFilter = "";
  Messages.Refresh = setTimeout(Messages.CheckRefresh,MESSAGE_REFRESH);
}

Messages.ShowBar = function(id, type)
{
  DOM.SetClass('message_bar_'+id,'mo');
}

Messages.HideBar = function(id)
{
  if
  (
    DOM.GetValue('sharelistopen_'+id)==0 &&
    DOM.GetValue('replyopen_'+id)==0 &&
    DOM.GetValue('group_info_open_'+id)==0 &&
    DOM.GetValue('profile_info_open_'+id)==0 &&
    DOM.GetValue('message_info_open_'+id)==0
  )
  {
    DOM.SetClass('message_bar_'+id,'');
  }
}

Messages.HideAllFilters = function()
{
  DOM.Hide('timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Show('show_timeline_filters');

  var obj = document.getElementById('message_filter');
  if (obj)
  {
   obj.value = "Filter";
   obj.className = "";
  }

  var div = document.getElementById('message_filters');
  var opts = div.getElementsByTagName('a');

  for (var o=0;o<opts.length;o++)
  {
    opts[o].className = 'unselected';
  }
  opts[0].className = 'selected';

  DOM.Hide('clear_message_filter');
  DOM.Show('filter_message_filter');

  Messages.SelectedType = "";
  Messages.CurrentFilter = "";

  // get form
  Messages.Get(Messages.MainPage);

  return false;
}

Messages.ShowAllFilters = function()
{
  if (Messages.MainPage=='user' && User.CurrentMain!='timeline')
  {
    DOM.Hide('message_filters');
  }
  else DOM.Show('message_filters');
  DOM.Show('message_filter_container');
  DOM.Show('timeline_filters');
  DOM.Hide('show_timeline_filters');
  DOM.Show('hide_timeline_filters');
  return false;
}

Messages.OpenShares = function(obj,id)
{
  function getshares()
  {
    //Messages.GetNewShares(id);
  }

  function scrolltobottom(noref)
  {
    var shares = document.getElementById('sharelist_'+id);
    shares.style.height = 'auto';
    var div = document.getElementById('share_list_'+id);
    if (div) div.scrollTop = div.scrollHeight;
    //if (!noref) Messages.Refresh = setTimeout(Messages.CheckRefresh,MESSAGE_REFRESH);
  }

  function openshares(response)
  {
    if (DOM.GetValue('hasnewshares_'+id)>0) DOM.Open('sharelist_'+id,null,getshares);
    else DOM.Open('sharelist_'+id,null,scrolltobottom);
    DOM.SetValue('sharelistopen_'+id,1);
    DOM.SetValue('last_share_count_'+id,DOM.GetValue('totalshares_'+id));
    DOM.SetClass('sharelink_'+id,'shareopen');
    var tab = document.getElementById('sharelink_'+id).parentNode;
    if (tab) tab.className = "share_active";
    DOM.Blur('sharelink_'+id);
  }

  obj.blur();
  if (DOM.GetValue('sharelistopen_'+id)>0)
  {
    Messages.CloseShares(id);
  }
  else if (DOM.GetValue('replyopen_'+id)>0)
  {
    Messages.CloseReplies(id,openshares);
  }
  else if (DOM.GetValue('group_info_open_'+id)>0)
  {
    Messages.HideGroupInfo(obj,id,openshares);
  }
  else if (DOM.GetValue('profile_info_open_'+id)>0)
  {
    Messages.HideMemberInfo(obj,id,openshares);
  }
  else if (DOM.GetValue('message_info_open_'+id)>0)
  {
    Messages.HideMessageInfo(obj,id,openshares);
  }
  else openshares();
  return false;
}

Messages.CloseShares = function(id,donext)
{
  DOM.SetClass('sharelink_'+id,'');
  if (donext) DOM.Close('sharelist_'+id,null,donext);
  else DOM.Close('sharelist_'+id);
  DOM.SetValue('sharelistopen_'+id,0);
  var tab = document.getElementById('sharelink_'+id).parentNode;
  if (tab) tab.className = "share";
  DOM.Blur('sharelink_'+id);
  return false;
}

Messages.ShareCount = function(e,obj,prefix,id)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,SHARE_LENGTH);
    obj.scrollTop = st;
  }

  if (window.event) key = window.event.keyCode;
  else key = e.which;
  if (key==13)
  {
    obj.blur();
    Messages.SendShare(id,prefix);
  }
  else
  {
    if (obj.value.length > SHARE_LENGTH) disallow();
    var counter = document.getElementById(prefix+'share_count_'+id);
    if (counter)
    {
      counter.innerHTML = SHARE_LENGTH-obj.value.length;
    }
  }
}

Messages.ShareFocus = function(obj)
{
  if (obj.className=='subdued')
  {
    obj.value = "";
    obj.className = "";
  }
}

Messages.ShareBlur = function(obj, msgtype)
{
  if (obj.value.trim()=="")
  {
    obj.value = 'Do you want to say something about this ' + msgtype + ' before you share it?';
    obj.className = "subdued";
  }
}

Messages.SendShare = function(id, prefix)
{
  function send()
  {
    DOM.Show('share_footer_'+id);
    DOM.Show('alert_share_footer_'+id);
    DOM.Show('share_share_footer_'+id);
    Messages.SentShare = id;
    var messageNew = msg.value.trim();
    if (messageNew==DOM.GetValue(prefix+'share_original_text_'+id)) messageNew = "";
    var shareThis = new Ajax(Messages.ShareSent);
    var params = "isajax=1";
    params += "&message="+id;
    params += "&text="+encodeURIComponent(messageNew);
    var url = "ajax/send_share";
    DOM.Show('user_loading');
    msg.value = "";
    shareThis.sendPostRequest(url,params);
  }

  var msg = document.getElementById(prefix+'share_text_'+id);
  if (msg)
  {
    clearTimeout(Messages.Refresh);
    switch (prefix)
    {
      case 'alert_':
        DOM.Close('share_form_'+id);
        DOM.Close('share_share_form_'+id);
        DOM.Close('alert_share_form_'+id,null,send);
        break;
      case 'share_':
        DOM.Close('share_form_'+id);
        DOM.Close('alert_share_form_'+id);
        DOM.Close('share_share_form_'+id,null,send);
        break;
      default:
        DOM.Close('alert_share_form_'+id);
        DOM.Close('share_share_form_'+id);
        DOM.Close('share_form_'+id,null,send);
        break;
    }
  }
  else alert("Error: Unable to read share form");
  return false;
}

Messages.ShareSent = function(response)
{
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    if (Messages.MainPage=='tabs' && (Tabs.Tab=='discussed' || Tabs.Tab=='shared'))
    {
      var share = new Ajax(Messages.ShowShare);
      var params = "isajax=1";
      params += "&id="+response.substr(2);
      var url = "ajax/get_share";
      share.sendPostRequest(url,params);
    }
    else
    {
      clearTimeout(Messages.Refresh);
      Messages.CheckRefresh(Messages.SentShare);
      Messages.SentShare = null;
    }
  }
  else if (response=="deleted")
  {
    alert("Sorry, this message has been deleted by the original poster. We are unable to share it.");
    Messages.DeletedType = "message";
    Messages.Deleted('ok'+Messages.SentShare);
    Messages.SentShare= null;
  }
  else
  {
    alert("Error: " + response);
  }
}

Messages.ShowShare = function(response)
{
  var share = response.jsonParse();
  if (share)
  {
    var parentpost = document.getElementById('share_list_'+share.sharelink);
    if (parentpost && !DOM.Exists('messagesharecontainer_'+share.id))
    {
      var htm = Messages.ShareHTML;

      htm = htm.replace(/%ID%/g,share.id);
      htm = htm.replace(/%TYPE%/,',"share"');
      if (share.text=="")
      {
        htm = htm.replace(/%COMMENT%/,"");
        htm = htm.replace(/%WITHCOMMENT%/,"without");
      }
      else
      {
        var c = "<p class='subtext'>"+share.text+"</p>";
        htm = htm.replace(/%COMMENT%/,c);
        htm = htm.replace(/%WITHCOMMENT%/,"with");
      }
      htm = htm.replace(/%POSTERID%/g,share.userid);
      htm = htm.replace(/%POSTERNAME%/g,share.username);
      htm = htm.replace(/%AVATAR%/g,share.avatar);
      htm = htm.replace(/%TIMESTR%/g,share.time_posted);
      htm = htm.replace(/%SOURCE%/g,share.msgsource);
      htm = htm.replace(/%SHAREID%/g,share.sharelink);
      if (share.candelete)
      {
        htm = htm.replace(/%DELETE%/,Messages.DeleteHTML);
      }
      else htm = htm.replace(/%DELETE%/,"");
      var div = document.createElement('div');
      div.className = "sharecontainer highlight";
      div.id = 'messagesharecontainer_'+share.id;
      div.innerHTML = htm;
      parentpost.appendChild(div);
      DOM.Show('share_list_'+share.sharelink);
      Messages.Fader(share.id,false,false,true,'');
      Messages.Slide(share.id,false,true,'');
    }
  }
}

Messages.GetNewShares = function(id)
{
  alert('Unexpected call to Messages.GetNewShares in messagelist.js line 393');
  var shares = new Ajax(Messages.GotNewShares);
  var url = "ajax/get_new_shares/"+id;
  shares.sendRequest(url);
}

Messages.GotNewShares = function(response)
{
  alert('Unexpected call to Messages.GotNewShares in messagelist.js line 400');
  var resp = response.jsonParse();
  if (resp && resp.shares)
  {
    var parentpost = document.getElementById('share_list_'+resp.parentid);
    if (parentpost)
    {
      if (resp.totalshares > 0) parentpost.style.display = '';
      else parentpost.style.display = 'none';
    }

    var cutoff = MAX_INLINE_REPLIES;
    var share_count = (resp.shares.length > cutoff) ? cutoff : resp.shares.length;
    for (var r=0;r<share_count;r++)
    {
      var exists = document.getElementById('messagesharecontainer_'+resp.shares[r].id);
      if (!exists && parentpost)
      {
        var htm = Messages.ShareHTML;
        if (resp.viewerid==resp.shares[r].userid || resp.viewerid==resp.parentposter)
        {
          htm = htm.replace(/%DELETE%/,Messages.DeleteHTML);
        }
        else htm = htm.replace(/%DELETE%/,"");
        htm = htm.replace(/%ID%/g,resp.shares[r].id);
        htm = htm.replace(/%TYPE%/,',"share"');
        if (resp.shares[r].text=="")
        {
          htm = htm.replace(/%COMMENT%/,"");
          htm = htm.replace(/%WITHCOMMENT%/,"without");
        }
        else
        {
          var c = "<p class='subtext'>"+resp.shares[r].text+"</p>";
          htm = htm.replace(/%COMMENT%/,c);
          htm = htm.replace(/%WITHCOMMENT%/,"with");
        }
        htm = htm.replace(/%POSTERID%/g,resp.shares[r].userid);
        htm = htm.replace(/%POSTERNAME%/g,resp.shares[r].username);
        htm = htm.replace(/%AVATAR%/g,resp.shares[r].avatar);
        htm = htm.replace(/%TIMESTR%/g,resp.shares[r].time_posted);
        htm = htm.replace(/%SOURCE%/g,resp.shares[r].msgsource);
        htm = htm.replace(/%SHAREID%/g,resp.shares[r].sharelink);
        var div = document.createElement('div');
        div.className = "sharecontainer";
        div.id = 'messagesharecontainer_'+resp.shares[r].id;
        parentpost.appendChild(div);
        if (!Messages.GettingMore)
        {
          Messages.Fader(resp.shares[r].id,false,false,true,'');
        }
        div.innerHTML = htm;
        Messages.Slide(resp.shares[r].id,false,true,'');
      }
    }
    Messages.GettingMore = false;
    DOM.SetValue('totalshares_'+resp.parentid,resp.totalshares);
    clearTimeout(Messages.Refresh);
    Messages.Refresh = setTimeout(Messages.CheckRefresh,MESSAGE_REFRESH);
  }
  else alert("An unknown error occurred");
}

Messages.Delete = function(id,numreplies,bypass,messagetype)
{
  if (messagetype=='alert') { var str = "Really delete this alert?"; Messages.DeletedType = 'alert'; }
  else if (messagetype=='share') { var str = "Really delete this share?"; Messages.DeletedType = 'share'; }
  else if (numreplies<0) { var str = "Really delete this reply?"; Messages.DeletedType = 'reply'; }
  else if (numreplies >0) { var str = "Really delete this message and all its replies?"; Messages.DeletedType = 'message'; }
  else { var str = "Really delete this message?"; Messages.DeletedType = 'message'; }
  if (bypass || confirm(str))
  {
    var url = HTTP_BASE + "ajax/delete_message/"+id;
    var del = new Ajax(Messages.Deleted);
    DOM.Show('user_loading');
    del.sendRequest(url);
  }
  else Messages.DeletedType = null;
  return false;
}

Messages.Deleted = function(response)
{
  function remove_message()
  {
    if (msg && msg.parentNode) msg.parentNode.removeChild(msg);
    if (isreply)
    {
      if (list.childNodes.length==0) DOM.Close(list.id);
      if (msg1 && msg1.parentNode)
      {
        msg1.parentNode.removeChild(msg1);
        if (list1.childNodes.length==0) DOM.Close(list1.id);
      }
      if (msg2 && msg2.parentNode)
      {
        msg2.parentNode.removeChild(msg2);
        if (list2.childNodes.length==0) DOM.Close(list2.id);
      }
    }
  }

  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    var id = response.substr(2);
    if (Messages.DeletedType == 'message' || Messages.DeletedType == 'alert')
    {
      var msg = document.getElementById('messagecontainer_'+id);
    }
    else if (Messages.DeletedType == 'reply')
    {
      var msg = document.getElementById('messagereplycontainer_'+id);
      var list = msg.parentNode;
      var msg1 = document.getElementById('alert_messagereplycontainer_'+id);
      var msg2 = document.getElementById('share_messagereplycontainer_'+id);
      var isreply = true;
    }
    else if (Messages.DeletedType == 'share')
    {
      var msg = document.getElementById('messagesharecontainer_'+id);
      var list = msg.parentNode;
      var msg1 = document.getElementById('alert_messagesharecontainer_'+id);
      var msg2 = document.getElementById('share_messagesharecontainer_'+id);
      var isshare = true;
    }
    if (msg)
    {
      msg.style.overflow = "hidden";
      DOM.Close(msg.id,null,remove_message);
    }
    if (msg1)
    {
      var list1 = msg1.parentNode;
      msg1.style.overflow = "hidden";
      DOM.Close(msg1.id,null,remove_message);
    }
    if (msg2)
    {
      var list2 = msg2.parentNode;
      msg2.style.overflow = "hidden";
      DOM.Close(msg2.id,null,remove_message);
    }
  }
  else
  {
    alert(response);
  }
}

Messages.GetMore = function(controller,filtervalue)
{
  if (filtervalue!=undefined) Messages.CurrentFilter = filtervalue; else Messages.CurrentFilter = '';

  var params = "isajax=1";
  params += "&viewtime="+Forms.GetValue('viewtime');
  params += "&message_page="+(parseInt(Forms.GetValue('message_page'))+1);
  params += "&response=more";

  /*
  params += "&lastmessage="+Messages.LastMessage;
  params += "&top="+Messages.Top;
  params += "&bottom="+Messages.Bottom;
  params += "&filter="+Messages.CurrentFilter;
  params += "&messagetype="+Messages.SelectedType;
  params += "&response=more";
  params += "&opt="+Messages.Opt;
  */

  switch (controller)
  {
    case 'user':
      var url = controller+"/"+User.CurrentMain;
      if (User.CurrentSub != '') url += "/"+User.CurrentSub;
      User.CurrentFilter = Messages.CurrentFilter;
      if (User.FilterType != "")
      {
        url += "?sub="+User.CurrentSubSub+"&type="+User.FilterType;
      }
      else if (User.CurrentSubSub != "") url += "?type="+User.CurrentSubSub;
      break;
    case 'members':
      var url = controller + "/" + Profile.Current + "/" + Profile.ID + "?type=" + Profile.Sub;
      Profile.CurrentFilter = Messages.CurrentFilter;
      break;
    case 'groups':
      var url = controller + "/" + Group.Current + "/" + Group.ID + "?type=" + Group.Sub;
      Group.CurrentFilter = Messages.CurrentFilter;
      break;
    case 'tabs':
      var url = "tabs/get_messages/" + Tabs.Tab;
      params += "&type="+Tabs.Type;
      break;
    case 'search':
      var url = "search/get_results/"+Search.Type;
      params += "&searchval="+Search.Val;
      break;
    default: alert("Error: Unknown controller '"+controller+"'"); return false;
  }

  DOM.Show('user_loading');
  var getmore = new Ajax(Messages.GotMore);
  getmore.sendPostRequest(url,params);
  return false;
}

Messages.GotMore = function(response)
{
  DOM.Hide('user_loading');
  if (response!='ok')
  {
    var resp = response.jsonParse();
    var messages = resp.messages;
    Forms.SetValue('message_page',resp.message_page);
    //Messages.Bottom = resp.bottom;
    var messagelist = document.getElementById('message_list');
    if (messagelist)
    {
      var wrapper = document.getElementById('wrapper');
      var lastpost = document.getElementById('message_'+Messages.LastMessage);
      // add line
      var divider = document.createElement('hr');
      divider.className = "divider";
      messagelist.appendChild(divider);

      // insert messages
      for (var m=0;m<messages.length;m++)
      {
        var div = document.createElement('div');
        div.id = "messagecontainer_"+messages[m].id;
        div.className = "messagecontainer";
        div.innerHTML = messages[m].html;
        messagelist.appendChild(div);
      }
      Messages.LastMessage = messages[messages.length-1].id;
      if (resp.eol) DOM.Hide('more_link');
    }
  }
  var btn = document.getElementById('more_button');
  if (btn) btn.blur();
}

Messages.Get = function(controller,filtervalue)
{
  clearTimeout(Messages.Refresh);
  if (filtervalue!=undefined) Messages.CurrentFilter = filtervalue;
  switch (controller)
  {
    case 'user':
      var url = controller+"/"+User.CurrentMain;
      if (User.CurrentSub != "") url += "/"+User.CurrentSub;
      User.CurrentFilter = Messages.CurrentFilter;
      if (User.CurrentMain=='messages')
      {
        if (User.CurrentSubSub != "") url += "?type="+User.CurrentSubSub;
      }
      else
      {
        if (User.CurrentSubSub != "") url += "?sub="+User.CurrentSubSub;
        if (User.FilterType != "") url += "&type="+User.FilterType;
      }
      break;
    case 'members':
      var url = controller + "/" + Profile.Current + "/" + Profile.ID + "?type=" + Profile.Sub;
      Profile.CurrentFilter = Messages.CurrentFilter;
      break;
    case 'groups':
      var url = controller + "/" + Group.Current + "/" + Group.ID + "?type=" + Group.Sub;
      Group.CurrentFilter = Messages.CurrentFilter;
      break;
    default: alert("Error: Unknown controller '"+controller+"'"); return false;
  }
  DOM.SetHTML('update_count','0');
  DOM.Hide('update_counter');
  Messages.UpdateIsOpen = false;

  var params = "isajax=1";
  params += "&filter="+Messages.CurrentFilter;
  params += "&messagetype="+Messages.SelectedType;
  params += "&initial_load=1";
  DOM.Show('user_loading');
  Messages.Ajax.sendPostRequest(url,params);
  return false;
}

Messages.Got = function(response)
{
  DOM.Hide('user_loading');
  if (Messages.MainPage=='members') var p = 'profile';
  else var p = Messages.MainPage;
  var div = document.getElementById(p+'_content');
  if (div) div.innerHTML = response;
  var lc = document.getElementById('last_check');
  if (lc) Messages.LastCheck = lc.value;
  var lp = document.getElementById('last_message');
  if (lp)
  {
    Messages.LastPost = lp.value;
    Messages.LastMessage = lp.value;
  }
  Messages.TotalMessages = DOM.GetValue('total_messages');
  Messages.ScrollToBottom();
  clearTimeout(Messages.Refresh);
  Messages.Refresh = setTimeout(Messages.CheckRefresh,MESSAGE_REFRESH);
}

Messages.CheckRefresh = function(id)
{
  if (Messages.NoRefresh) return false;
  var params = "isajax=1";
  params += "&top="+Messages.Top;
  //params += "&bottom="+Messages.Bottom;
  params += "&filter="+Messages.CurrentFilter;
  params += "&messagetype="+Messages.SelectedType;
  params += "&response=status";
  params += "&opt="+Messages.Opt;
  params += "&lastcheck="+Messages.LastCheck;
  params += "&lastpost="+Messages.LastPost;

  switch (Messages.MainPage)
  {
    case 'members':
      var url = "members/"+Profile.Current+"/"+Profile.ID+"?type="+Profile.Sub;
      break;
    case 'groups':
      var url = "groups/"+Group.Current+"/"+Group.ID+"?type="+Group.Sub;
      break;
    case 'tabs':
      var url = "tabs/get_messages/"+Tabs.Tab;
      params += "&type="+Tabs.Type;
      if (Tabs.Type=='categories') { params += "&category="+Tabs.Category; }
      break;
    case 'search':
      var url = "search/get_results/"+Search.Type;
      params += "&searchval="+Search.Val;
      break;
    case 'user':
      var url = "user/"+User.CurrentMain;
      if (User.CurrentSub!="")
      {
        url += "/"+User.CurrentSub;
        if (User.CurrentMain=='filters') url += "?sub="+User.CurrentSubSub+"&type="+User.FilterType;
        else url += "?type="+User.CurrentSubSub;
      }
      break;
  }

  if (Messages.ForceUpdate) params += "&mode=f";
  else params += "&mode="+REFRESH_MODE;
  Messages.ForceUpdate = false;
  if (id) params += "&id="+id;
  Messages.RefreshAjax.sendPostRequest(url,params);
}

Messages.GetUpdates = function(id)
{
  if (!id) id = null;
  clearTimeout(Messages.Refresh);
  Messages.ForceUpdate = true;
  Messages.CheckRefresh(id);
  return false;
}

Messages.DoRefresh = function(response)
{
  var resp = response.jsonParse();
  if (resp)
  {
    var isman = (resp.mode=='m' || (Messages.IsPaused && resp.mode!='f'));
    if (resp.updates)
    {
      if (!Messages.IsPaused) Messages.Top = resp.topmessage;
      DOM.SetValue('top_message',Messages.Top);
      if (!isman) Messages.LastCheck = resp.lastcheck;
      var update = resp.updates;
      var messagelist = document.getElementById('message_list');
      var updatecount = 0;
      for (var u=0;u<update.length;u++)
      {
        if (update[u].isshare) DOM.SetHTML('response_'+update[u].id,"share");
        else if (update[u].isalert) DOM.SetHTML('response_'+update[u].id,"alert");
        if (update[u].new_post)
        {
          var postexists = document.getElementById('message_'+update[u].id);
          if (!postexists)
          {
            if (isman) updatecount++;
            else if (messagelist)
            {
              var firstmessage = messagelist.firstChild;
              var div = document.createElement('div');
              div.id = "messagecontainer_"+update[u].id;
              div.className = "messagecontainer";
              Messages.Fader(update[u].id,false,update[u].isreply);
              div.innerHTML = update[u].html;
              messagelist.insertBefore(div,firstmessage);
              Messages.Slide(update[u].id,false,false,'');
              Messages.LastPost = update[u].id;
            }
          }
        }

        var alertwrapper = DOM.GetValue('alert_wrapper_'+update[u].id);
        var sharewrapper = DOM.GetValue('share_wrapper_'+update[u].id);
        Messages.UpdateCount = updatecount;
        DOM.SetHTML('info_shares_'+update[u].id,update[u].totalshares);
        DOM.SetHTML('alert_info_shares_'+update[u].id,update[u].totalshares);
        DOM.SetHTML('share_info_shares_'+update[u].id,update[u].totalshares);
        if (update[u].totalshares>0)
        {
          DOM.SetHTML('shares_count_'+update[u].id,update[u].totalshares);
          DOM.Show('shares_count_'+update[u].id);
          DOM.SetHTML('alert_shares_count_'+update[u].id,update[u].totalshares);
          DOM.Show('alert_shares_count_'+update[u].id);
          DOM.SetHTML('share_shares_count_'+update[u].id,update[u].totalshares);
          DOM.Show('share_shares_count_'+update[u].id);
        }
        else
        {
          DOM.SetHTML('shares_count_'+update[u].id,'');
          DOM.Hide('shares_count_'+update[u].id);
          DOM.SetHTML('alert_shares_count_'+update[u].id,'');
          DOM.Hide('alert_shares_count_'+update[u].id);
          DOM.SetHTML('share_shares_count_'+update[u].id,'');
          DOM.Hide('share_shares_count_'+update[u].id);
        }
        DOM.SetHTML('info_replies_'+update[u].id,update[u].totalreplies);
        DOM.SetHTML('alert_info_replies_'+update[u].id,update[u].totalreplies);
        DOM.SetHTML('share_info_replies_'+update[u].id,update[u].totalreplies);
        if (update[u].totalreplies>0)
        {
          DOM.SetHTML('replies_count_'+update[u].id,update[u].totalreplies);
          DOM.Show('replies_count_'+update[u].id);
          DOM.SetHTML('alert_replies_count_'+update[u].id,update[u].totalreplies);
          DOM.Show('alert_replies_count_'+update[u].id);
          DOM.SetHTML('share_replies_count_'+update[u].id,update[u].totalreplies);
          DOM.Show('share_replies_count_'+update[u].id);
        }
        else
        {
          DOM.SetHTML('replies_count_'+update[u].id,'');
          DOM.Hide('replies_count_'+update[u].id);
          DOM.SetHTML('alert_replies_count_'+update[u].id,'');
          DOM.Hide('alert_replies_count_'+update[u].id);
          DOM.SetHTML('share_replies_count_'+update[u].id,'');
          DOM.Hide('share_replies_count_'+update[u].id);
        }
        // handle replies
        DOM.SetValue('totalreplies_'+update[u].id,update[u].totalreplies);
        DOM.SetHTML('reply_getmore_'+update[u].id,update[u].totalreplies);
        DOM.SetValue('alert_totalreplies_'+update[u].id,update[u].totalreplies);
        DOM.SetHTML('alert_reply_getmore_'+update[u].id,update[u].totalreplies);
        DOM.SetValue('share_totalreplies_'+update[u].id,update[u].totalreplies);
        DOM.SetHTML('share_reply_getmore_'+update[u].id,update[u].totalreplies);
        if (update[u].newreplies > 0)
        {
          var isclosed = (DOM.GetValue('replyopen_'+update[u].id)==0);
          var ap = DOM.GetValue('alert_wrapper_'+update[u].id);
          var sp = DOM.GetValue('share_wrapper_'+update[u].id);
          if (ap) var alertclosed = (DOM.GetValue('replyopen_'+ap)==0);
          else var alertclosed = true;
          if (sp) var shareclosed = (DOM.GetValue('replyopen_'+sp)==0);
          else var shareclosed = true;

          var parentpost = document.getElementById('reply_list_'+update[u].id);
          var parentalert = document.getElementById('alert_reply_list_'+update[u].id);
          var parentshare = document.getElementById('share_reply_list_'+update[u].id);
          if (parentpost || parentalert || parentshare)
          {
            if (update[u].totalreplies > 0)
            {
              if (parentpost) parentpost.style.display = '';
              if (parentalert) parentalert.style.display = '';
              if (parentshare) parentshare.style.display = '';
            }
            var cutoff = MAX_INLINE_REPLIES;
            var reply_count = (update[u].replies.length > cutoff) ? cutoff : update[u].replies.length;
            var reply_bottom = update[u].replies.length - reply_count;
            var last_insert = 0;
            var alert_last_insert = 0;
            var share_last_insert = 0;
            for (var ri=1;ri<=update[u].replies.length;ri++)
            {
              var r = update[u].replies.length - ri;
              var exists = document.getElementById('messagereplycontainer_'+update[u].replies[r].id);
              var alertexists = document.getElementById('alert_messagereplycontainer_'+update[u].replies[r].id);
              var shareexists = document.getElementById('share_messagereplycontainer_'+update[u].replies[r].id);

              if (r >= reply_bottom)
              {
                var htm = Messages.ReplyHTML;
                if (update[u].replies[r].candelete)
                {
                  htm = htm.replace(/%DELETE%/,Messages.DeleteHTML);
                }
                else htm = htm.replace(/%DELETE%/,"");
                htm = htm.replace(/%ID%/g,update[u].replies[r].id);
                htm = htm.replace(/%TYPE%/,'');
                htm = htm.replace(/%MESSAGETEXT%/,update[u].replies[r].text);
                htm = htm.replace(/%POSTERID%/g,update[u].replies[r].userid);
                htm = htm.replace(/%POSTERNAME%/g,update[u].replies[r].username);
                htm = htm.replace(/%AVATAR%/g,update[u].replies[r].avatar);
                htm = htm.replace(/%TIMESTR%/g,update[u].replies[r].time_posted);

                if (parentpost && !exists)
                {
                  var div = document.createElement('div');
                  div.className = "replycontainer";
                  div.id = 'messagereplycontainer_'+update[u].replies[r].id;
                  div.innerHTML = htm;
                  if (last_insert>update[u].replies[r].id)
                  {
                    var next_node = document.getElementById('messagereplycontainer_'+last_insert);
                    if (next_node) parentpost.insertBefore(div,next_node);
                    else parentpost.appendChild(div);
                  }
                  else parentpost.appendChild(div);
                  last_insert = update[u].replies[r].id;
                  if (!Messages.GettingMore)
                  {
                    div.className = "replycontainer highlight";
                    Messages.Fader(update[u].replies[r].id,true,false,false,'');
                  }
                  if (!isclosed)
                  {
                    Messages.Slide(update[u].replies[r].id,true,false,'');
                  }
                }
                if (parentalert && !alertexists)
                {
                  var div = document.createElement('div');
                  div.className = "replycontainer";
                  div.id = 'alert_messagereplycontainer_'+update[u].replies[r].id;
                  div.innerHTML = htm;
                  if (alert_last_insert>update[u].replies[r].id)
                  {
                    var next_node = document.getElementById('alert_messagereplycontainer_'+alert_last_insert);
                    if (next_node) parentalert.insertBefore(div,next_node);
                    else parentalert.appendChild(div);
                  }
                  else parentalert.appendChild(div);
                  alert_last_insert = update[u].replies[r].id;
                  if (!Messages.GettingMore)
                  {
                    div.className = "replycontainer highlight";
                    Messages.Fader(update[u].replies[r].id,true,false,false,'alert_');
                  }
                  if (!alertclosed)
                  {
                    Messages.Slide(update[u].replies[r].id,true,false,'alert_');
                  }
                }
                if (parentshare && !shareexists)
                {
                  var div = document.createElement('div');
                  div.className = "replycontainer";
                  div.id = 'share_messagereplycontainer_'+update[u].replies[r].id;
                  div.innerHTML = htm;
                  if (share_last_insert>update[u].replies[r].id)
                  {
                    var next_node = document.getElementById('share_messagereplycontainer_'+share_last_insert);
                    if (next_node) parentshare.insertBefore(div,next_node);
                    else parentshare.appendChild(div);
                  }
                  else parentshare.appendChild(div);
                  share_last_insert = update[u].replies[r].id;
                  if (!Messages.GettingMore)
                  {
                    div.className = "replycontainer highlight";
                    Messages.Fader(update[u].replies[r].id,true,false,false,'share_');
                  }
                  if (!shareclosed)
                  {
                    Messages.Slide(update[u].replies[r].id,true,false,'share_');
                  }
                }
              }
              else
              {
                DOM.Close('messagereplycontainer_'+update[u].replies[r].id);
                DOM.Close('alert_messagereplycontainer_'+update[u].replies[r].id);
                DOM.Close('share_messagereplycontainer_'+update[u].replies[r].id);
              }
            }
          }
          var replycount = update[u].totalreplies - DOM.GetValue('last_count_'+update[u].id);

          if (update[u].totalreplies > MAX_INLINE_REPLIES)
          {
            DOM.Show('more_replies_'+update[u].id);
            DOM.Show('alert_more_replies_'+update[u].id);
            DOM.Show('share_more_replies_'+update[u].id);
          }
          else
          {
            DOM.Hide('more_replies_'+update[u].id);
            DOM.Hide('alert_more_replies_'+update[u].id);
            DOM.Hide('share_more_replies_'+update[u].id);
          }
        }
        // check for deleted replies
        if (DOM.Exists('all_replies_'+update[u].id)) var allreplies = DOM.GetValue('all_replies_'+update[u].id);
        else if (DOM.Exists('alert_all_replies_'+update[u].id)) var allreplies = DOM.GetValue('alert_all_replies_'+update[u].id);
        else if (DOM.Exists('share_all_replies_'+update[u].id)) var allreplies = DOM.GetValue('share_all_replies_'+update[u].id);
        else var allreplies = false;
        if (allreplies)
        {
          var existing = allreplies.split(',');
          for (var e=0;e<existing.length;e++)
          {
            var found = false;
            for (var ar=0;ar<update[u].all_replies.length;ar++)
            {
              if (existing[e]==update[u].all_replies[ar])
              {
                found = true;
                break;
              }
            }
            if (!found)
            {
              // delete
              DOM.Close('messagereplycontainer_'+existing[e], null, function() { var obj=document.getElementById('messagereplycontainer_'+existing[e]); if (obj) obj.parentNode.removeChild(obj); } );
              DOM.Close('alert_messagereplycontainer_'+existing[e], null, function() { var obj=document.getElementById('alert_messagereplycontainer_'+existing[e]); if (obj) obj.parentNode.removeChild(obj); } );
              DOM.Close('share_messagereplycontainer_'+existing[e], null, function() { var obj=document.getElementById('share_messagereplycontainer_'+existing[e]); if (obj) obj.parentNode.removeChild(obj); } );
            }
          }
        }
        if (update[u].all_replies)
        {
          DOM.SetValue('all_replies_'+update[u].id,update[u].all_replies.toString());
          DOM.SetValue('alert_all_replies_'+update[u].id,update[u].all_replies.toString());
          DOM.SetValue('share_all_replies_'+update[u].id,update[u].all_replies.toString());
        }
        // handle shares
        DOM.SetValue('totalshares_'+update[u].id,update[u].totalshares);
        DOM.SetHTML('share_getmore_'+update[u].id,update[u].totalshares);
        DOM.SetValue('alert_totalshares_'+update[u].id,update[u].totalshares);
        DOM.SetHTML('alert_share_getmore_'+update[u].id,update[u].totalshares);
        DOM.SetValue('share_totalshares_'+update[u].id,update[u].totalshares);
        DOM.SetHTML('share_share_getmore_'+update[u].id,update[u].totalshares);

        if (update[u].totalshares > 0)
        {
          DOM.Show('message_shared_'+update[u].id);
          DOM.Show('alert_message_shared_'+update[u].id);
          DOM.Show('share_message_shared_'+update[u].id);
          DOM.Hide('message_not_shared_'+update[u].id);
          DOM.Hide('alert_message_not_shared_'+update[u].id);
          DOM.Hide('share_message_not_shared_'+update[u].id);
        }
        else
        {
          DOM.Hide('message_shared_'+update[u].id);
          DOM.Hide('alert_message_shared_'+update[u].id);
          DOM.Hide('share_message_shared_'+update[u].id);
          DOM.Show('message_not_shared_'+update[u].id);
          DOM.Show('alert_message_not_shared_'+update[u].id);
          DOM.Show('share_message_not_shared_'+update[u].id);
        }
        if (update[u].newshares > 0)
        {
          var isclosed = (DOM.GetValue('sharelistopen_'+update[u].id)==0);
          var ap = DOM.GetValue('alert_wrapper_'+update[u].id);
          var sp = DOM.GetValue('share_wrapper_'+update[u].id);
          if (ap) var alertclosed = (DOM.GetValue('sharelistopen_'+ap)==0);
          else var alertclosed = true;
          if (sp) var shareclosed = (DOM.GetValue('sharelistopen_'+sp)==0);
          else var shareclosed = true;

          var parentpost = document.getElementById('share_list_'+update[u].id);
          var parentalert = document.getElementById('alert_share_list_'+update[u].id);
          var parentshare = document.getElementById('share_share_list_'+update[u].id);
          if (parentpost || parentalert || parentshare)
          {
            if (update[u].totalshares > 0)
            {
              if (parentpost) parentpost.style.display = '';
              if (parentalert) parentalert.style.display = '';
              if (parentshare) parentshare.style.display = '';
            }
            var cutoff = MAX_INLINE_REPLIES;
            var share_count = (update[u].shares.length > cutoff) ? cutoff : update[u].shares.length;
            var share_bottom = update[u].shares.length - share_count;
            for (var ri=1;ri<=update[u].shares.length;ri++)
            {
              var r = update[u].shares.length - ri;
              var exists = document.getElementById('messagesharecontainer_'+update[u].shares[r].id);
              var alertexists = document.getElementById('alert_messagesharecontainer_'+update[u].shares[r].id);
              var shareexists = document.getElementById('share_messagesharecontainer_'+update[u].shares[r].id);

              if (r >= share_bottom)
              {
                var htm = Messages.ShareHTML;
                if (update[u].shares[r].candelete)
                {
                  htm = htm.replace(/%DELETE%/,Messages.DeleteHTML);
                }
                else htm = htm.replace(/%DELETE%/,"");
                htm = htm.replace(/%ID%/g,update[u].shares[r].id);
                htm = htm.replace(/%TYPE%/,',"share"');
                htm = htm.replace(/%POSTERID%/g,update[u].shares[r].userid);
                htm = htm.replace(/%POSTERNAME%/g,update[u].shares[r].username);
                htm = htm.replace(/%AVATAR%/g,update[u].shares[r].avatar);
                htm = htm.replace(/%TIMESTR%/g,update[u].shares[r].time_posted);
                htm = htm.replace(/%SOURCE%/g,update[u].shares[r].msgsource);
                htm = htm.replace(/%SHAREID%/g,update[u].shares[r].sharelink);

                if (update[u].shares[r].text=="")
                {
                  htm = htm.replace(/%COMMENT%/,"");
                  htm = htm.replace(/%WITHCOMMENT%/,"without");
                }
                else
                {
                  var c = "<p class='subtext'>"+update[u].shares[r].text+"</p>";
                  htm = htm.replace(/%COMMENT%/,c);
                  htm = htm.replace(/%WITHCOMMENT%/,"with");
                }

                if (parentpost && !exists)
                {
                  var div = document.createElement('div');
                  div.className = "sharecontainer";
                  div.id = 'messagesharecontainer_'+update[u].shares[r].id;
                  div.innerHTML = htm;
                  parentpost.appendChild(div);
                  if (!Messages.GettingMore)
                  {
                    div.className = "sharecontainer highlight";
                    Messages.Fader(update[u].shares[r].id,false,false,true,'');
                  }
                  if (!isclosed)
                  {
                    Messages.Slide(update[u].shares[r].id,false,true,'');
                  }
                }
                if (parentalert && !alertexists)
                {
                  var div = document.createElement('div');
                  div.className = "sharecontainer";
                  div.id = 'alert_messagesharecontainer_'+update[u].shares[r].id;
                  //var html = htm.replace(/%CONTAINER_ID%/g,'alert_messagereply_'+update[u].shares[r].id);
                  div.innerHTML = htm;
                  parentalert.appendChild(div);
                  if (!Messages.GettingMore)
                  {
                    div.className = "sharecontainer highlight";
                    Messages.Fader(update[u].shares[r].id,false,false,true,'alert_');
                  }
                  if (!alertclosed)
                  {
                    Messages.Slide(update[u].shares[r].id,false,true,'alert_');
                  }
                }
                if (parentshare && !shareexists)
                {
                  var div = document.createElement('div');
                  div.className = "sharecontainer";
                  div.id = 'share_messagesharecontainer_'+update[u].shares[r].id;
                  //var html = htm.replace(/%CONTAINER_ID%/g,'share_messagereply_'+update[u].shares[r].id);
                  div.innerHTML = htm;
                  parentshare.appendChild(div);
                  if (!Messages.GettingMore)
                  {
                    div.className = "sharecontainer highlight";
                    Messages.Fader(update[u].shares[r].id,false,false,true,'share_');
                  }
                  if (!shareclosed)
                  {
                    Messages.Slide(update[u].shares[r].id,false,true,'share_');
                  }
                }
              }
              else
              {
                DOM.Close('messagesharecontainer_'+update[u].shares[r].id);
                DOM.Close('alert_messagesharecontainer_'+update[u].shares[r].id);
                DOM.Close('share_messagesharecontainer_'+update[u].shares[r].id);
              }

            }
          }
          var sharecount = update[u].totalshares - DOM.GetValue('last_share_count_'+update[u].id);
          if (update[u].totalshares > MAX_INLINE_REPLIES)
          {
            DOM.Show('more_shares_'+update[u].id);
            DOM.Show('alert_more_shares_'+update[u].id);
            DOM.Show('share_more_shares_'+update[u].id);
          }
          else
          {
            DOM.Hide('more_shares_'+update[u].id);
            DOM.Hide('alert_more_shares_'+update[u].id);
            DOM.Hide('share_more_shares_'+update[u].id);
          }
        }
        // check for deleted shares
        if (DOM.Exists('all_shares_'+update[u].id)) var allshares = DOM.GetValue('all_shares_'+update[u].id);
        else if (DOM.Exists('alert_all_shares_'+update[u].id)) var allshares = DOM.GetValue('alert_all_shares_'+update[u].id);
        else if (DOM.Exists('share_all_shares_'+update[u].id)) var allshares = DOM.GetValue('share_all_shares_'+update[u].id);
        else var allshares = false;
        if (allshares)
        {
          var existing = allshares.split(',');
          for (var e=0;e<existing.length;e++)
          {
            var found = false;
            for (var as=0;as<update[u].all_shares.length;as++)
            {
              if (existing[e]==update[u].all_shares[as])
              {
                found = true;
                break;
              }
            }
            if (!found)
            {
              // delete
              DOM.Close('messagesharecontainer_'+existing[e], null, function() { var obj=document.getElementById('messagesharecontainer_'+existing[e]); if (obj) obj.parentNode.removeChild(obj); } );
              DOM.Close('alert_messagesharecontainer_'+existing[e], null, function() { var obj=document.getElementById('alert_messagesharecontainer_'+existing[e]); if (obj) obj.parentNode.removeChild(obj); } );
              DOM.Close('share_messagesharecontainer_'+existing[e], null, function() { var obj=document.getElementById('share_messagesharecontainer_'+existing[e]); if (obj) obj.parentNode.removeChild(obj); } );
            }
          }
        }
        if (update[u].all_shares)
        {
          DOM.SetValue('all_shares_'+update[u].id,update[u].all_shares.toString());
          DOM.SetValue('alert_all_shares_'+update[u].id,update[u].all_shares.toString());
          DOM.SetValue('share_all_shares_'+update[u].id,update[u].all_shares.toString());
        }
      }

      if (update.length>0) DOM.Hide('no_message_text');
      if (isman && updatecount>0)
      {
        if (Messages.IsPaused)
        {
          if (updatecount==1)
          {
            DOM.Hide('pausecounter0');
            DOM.Show('pausecounter1');
            DOM.Hide('pausecounterx');
          }
          else
          {
            DOM.SetHTML('pause_count',updatecount);
            DOM.Show('pausecounterx');
            DOM.Hide('pausecounter1');
            DOM.Hide('pausecounter0');
          }
        }
        else
        {
          if (updatecount==1)
          {
            DOM.Show('update_counter');
            DOM.Show('updatecounter1');
            DOM.Hide('updatecounterx');
          }
          else
          {
            DOM.Show('update_counter');
            DOM.SetHTML('update_count',updatecount);
            DOM.Show('updatecounterx');
            DOM.Hide('updatecounter1');
          }
        }
      }
      else
      {
        DOM.SetHTML('update_count','0');
        DOM.Hide('update_counter');
        Messages.UpdateIsOpen = false;
      }
    }
  }
  else if (response.substr(0,2) == 'ok')
  {

    var resp = response.split('_');
    Messages.LastCheck = resp[1];
    if (Messages.UpdateIsOpen)
    {
      DOM.SetHTML('update_count',0);
      DOM.Hide('update_Counter',1);
      Messages.UpdateIsOpen = false;
    }
  }

  //else alert('Unknown Error: '+response);
  clearTimeout(Messages.Refresh);
  Messages.Refresh = setTimeout(Messages.CheckRefresh,MESSAGE_REFRESH);
}

Messages.PauseUpdates = function()
{
  if (REFRESH_MODE=='m' || Messages.NoRefresh) return;
  clearTimeout(Messages.MO);
  Messages.IsPaused = true;
  DOM.Show('pause_counter');
  if (Messages.UpdateCount < 1)
  {
    DOM.Hide('pausecounterx');
    DOM.Hide('pausecounter1');
    DOM.Show('pausecounter0');
  }
  else if (Messages.UpdateCount == 1)
  {
    DOM.Hide('pausecounterx');
    DOM.Show('pausecounter1');
    DOM.Hide('pausecounter0');
  }
  else
  {
    DOM.Show('pausecounterx');
    DOM.Hide('pausecounter1');
    DOM.Hide('pausecounter0');
  }
  var div = document.getElementById('content_div');
  if (div) DOM.SetClass('content_div',DOM.GetClass('content_div')+' paused');
  else
  {
    var div = document.getElementById('tab_container');
    if (div) DOM.SetClass('tab_container',DOM.GetClass('tab_container')+' paused');
  }
}

Messages.ResumeUpdates = function()
{
  function resumeupdates()
  {
    clearTimeout(Messages.Refresh);
    Messages.IsPaused = false;
    DOM.Hide('pause_counter');
    switch (Messages.MainPage)
    {
      case 'tabs'     : var divid = 'tab_container'; break;
      case 'search'   : var divid = 'search_results'; break;
      default         : var divid = 'content_div'; break;
    }
    var divclass = DOM.GetClass(divid).split(' ');
    DOM.SetClass(divid,DOM.GetClass(divid)+' paused');
    DOM.SetClass(divid,divclass[0]);
    if (Messages.UpdateCount>0) Messages.CheckRefresh();
    else Messages.Refresh = setTimeout(Messages.CheckRefresh,MESSAGE_REFRESH);
  }

  if (REFRESH_MODE=='m') return;
  Messages.MO = setTimeout(resumeupdates,10);
}

Messages.FilterFocus = function(obj)
{
  if (obj.className!='active')
  {
    obj.value = "";
    obj.className = 'active';
  }
}

Messages.FilterBlur = function(obj)
{
  if (obj.value.trim()=='')
  {
    obj.value = "Filter";
    obj.className = "";
  }
}

Messages.ClearFilter = function()
{
  Messages.Filter('',false);
  var obj = document.getElementById('message_filter');
  if (obj)
  {
    obj.value = "Filter";
    obj.className = "";
  }
  return false;
}

Messages.Filter = function(filtervalue)
{
  clearTimeout(Messages.Refresh);
  Messages.Ajax.http.abort();
  Messages.RefreshAjax.http.abort();

  if (filtervalue.trim()!="")
  {
    DOM.Show('clear_message_filter');
    DOM.Hide('filter_message_filter');
  }
  else
  {
    DOM.Hide('clear_message_filter');
    DOM.Show('filter_message_filter');
  }
  Messages.Get(Messages.MainPage,filtervalue);
  return false;
}

Messages.SelectType = function(obj,opt)
{
  if (obj) obj.blur();
  clearTimeout(Messages.Refresh);
  Messages.Ajax.http.abort();
  Messages.RefreshAjax.http.abort();

  var div = document.getElementById('message_filters');
  var opts = div.getElementsByTagName('a');
  for (var o=0;o<opts.length;o++)
  {
    if ('select_'+opt == opts[o].id) opts[o].className = 'selected';
    else opts[o].className = 'unselected';
  }
  Messages.SelectedType = opt;
  Messages.Get(Messages.MainPage);
  return false;
}

Messages.ShowRecipients = function(id)
{
  DOM.Hide('showrecipients_'+id);
  DOM.Show('showingrecipients_'+id);
  DOM.Show('recipients_'+id);
  return false;
}

Messages.HideRecipients = function(id)
{
  DOM.Show('showrecipients_'+id);
  DOM.Hide('showingrecipients_'+id);
  DOM.Hide('recipients_'+id);
  return false;
}

Messages.GetNewReplies = function(id)
{
  alert('Unexpected call to Messages.GetNewReplies in messagelist.js line 1413');
  var replies = new Ajax(Messages.GotNewReplies);
  var url = "ajax/get_new_replies/"+id;
  replies.sendRequest(url);
}

Messages.GotNewReplies = function(response)
{
  alert('Unexpected call to Messages.GotNewReplies in messagelist.js line 1421');
  var resp = response.jsonParse();
  if (resp.replies)
  {
    DOM.SetHTML('info_replies_'+resp.parentid,resp.totalreplies);
    DOM.SetHTML('alert_info_replies_'+resp.parentid,resp.totalreplies);
    DOM.SetHTML('share_info_replies_'+resp.parentid,resp.totalreplies);
    if (resp.totalreplies>0)
    {
      DOM.SetHTML('replies_count_'+resp.parentid,resp.totalreplies);
      DOM.Show('replies_count_'+resp.parentid);
      DOM.SetHTML('alert_replies_count_'+resp.parentid,resp.totalreplies);
      DOM.Show('alert_replies_count_'+resp.parentid);
      DOM.SetHTML('share_replies_count_'+resp.parentid,resp.totalreplies);
      DOM.Show('share_replies_count_'+resp.parentid);
    }
    // handle replies
    DOM.SetValue('totalreplies_'+resp.parentid,resp.totalreplies);
    DOM.SetHTML('reply_getmore_'+resp.parentid,resp.totalreplies);
    DOM.SetValue('alert_totalreplies_'+resp.parentid,resp.totalreplies);
    DOM.SetHTML('alert_reply_getmore_'+resp.parentid,resp.totalreplies);
    DOM.SetValue('share_totalreplies_'+resp.parentid,resp.totalreplies);
    DOM.SetHTML('share_reply_getmore_'+resp.parentid,resp.totalreplies);

    var parentpost = document.getElementById('reply_list_'+resp.parentid);
    var parentalert = document.getElementById('alert_reply_list_'+resp.parentid);
    var parentshare = document.getElementById('share_reply_list_'+resp.parentid);
    if (parentpost)
    {
      if (resp.totalreplies > 0) parentpost.style.display = '';
      else parentpost.style.display = 'none';
    }
    if (parentalert)
    {
      if (resp.totalreplies > 0) parentalert.style.display = '';
      else parentalert.style.display = 'none';
    }
    if (parentshare)
    {
      if (resp.totalreplies > 0) parentshare.style.display = '';
      else parentshare.style.display = 'none';
    }

    var cutoff = MAX_INLINE_REPLIES;
    var reply_count = (resp.replies.length > cutoff) ? cutoff : resp.replies.length;
    var reply_bottom = resp.replies.length - reply_count;
    for (var ri=1;ri<=resp.replies.length;ri++)
    {
      var r = resp.replies.length - ri;
      var exists = document.getElementById('messagereplycontainer_'+resp.replies[r].id);
      var alertexists = document.getElementById('alert_messagereplycontainer_'+resp.replies[r].id);
      var shareexists = document.getElementById('share_messagereplycontainer_'+resp.replies[r].id);
      if (parentpost || parentalert || parentshare)
      {
        if (r >= reply_bottom)
        {
           var htm = Messages.ReplyHTML;
          if (resp.replies[r].candelete)
          {
            htm = htm.replace(/%DELETE%/,Messages.DeleteHTML);
          }
          else htm = htm.replace(/%DELETE%/,"");
          htm = htm.replace(/%ID%/g,resp.replies[r].id);
          htm = htm.replace(/%TYPE%/,'');
          htm = htm.replace(/%MESSAGETEXT%/,resp.replies[r].text);
          htm = htm.replace(/%POSTERID%/g,resp.replies[r].userid);
          htm = htm.replace(/%POSTERNAME%/g,resp.replies[r].username);
          htm = htm.replace(/%AVATAR%/g,resp.replies[r].avatar);
          htm = htm.replace(/%TIMESTR%/g,resp.replies[r].time_posted);
          if (parentpost && !exists)
          {
            var div = document.createElement('div');
            div.className = "replycontainer";
            div.id = 'messagereplycontainer_'+resp.replies[r].id;
            div.innerHTML = htm;
            parentpost.appendChild(div);

            if (!Messages.GettingMore)
            {
              div.className = "replycontainer highlight";
              Messages.Fader(resp.replies[r].id,true,false,false,'');
            }
            Messages.Slide(resp.replies[r].id,true,false,'');
          }
          if (parentalert && !alertexists)
          {
            var div = document.createElement('div');
            div.className = "replycontainer";
            div.id = 'alert_messagereplycontainer_'+resp.replies[r].id;
            div.innerHTML = htm;
            parentalert.appendChild(div);
            if (!Messages.GettingMore)
            {
              div.className = "replycontainer highlight";
              Messages.Fader(resp.replies[r].id,true,false,false,'alert_');
            }
            Messages.Slide(resp.replies[r].id,true,false,'alert_');
          }
          if (parentshare && !shareexists)
          {
            var div = document.createElement('div');
            div.className = "replycontainer";
            div.id = 'share_messagereplycontainer_'+resp.replies[r].id;
            div.innerHTML = htm;
            parentshare.appendChild(div);
            if (!Messages.GettingMore)
            {
              div.className = "replycontainer highlight";
              Messages.Fader(resp.replies[r].id,true,false,false,'share_');
            }
            Messages.Slide(resp.replies[r].id,true,false,'share_');
          }
        }
        else
        {
          DOM.Close('messagereplycontainer_'+resp.replies[r].id);
          DOM.Close('alert_messagereplycontainer_'+resp.replies[r].id);
          DOM.Close('share_messagereplycontainer_'+resp.replies[r].id);
        }
      }
    }
    Messages.GettingMore = false;
    DOM.SetValue('totalreplies_'+resp.parentid,resp.totalreplies);
    DOM.SetValue('alert_totalreplies_'+resp.parentid,resp.totalreplies);
    DOM.SetValue('share_totalreplies_'+resp.parentid,resp.totalreplies);
    clearTimeout(Messages.Refresh);
    Messages.Refresh = setTimeout(Messages.CheckRefresh,MESSAGE_REFRESH);
  }
  else alert("An unknown error occurred");
}

Messages.OpenReplies = function(obj,id)
{
  function getreplies()
  {
    scrolltobottom(true);
    //Messages.GetNewReplies(id);
  }

  function scrolltobottom(noref)
  {
    var replies = document.getElementById('replies_'+id);
    replies.style.height = 'auto';
    var div = document.getElementById('reply_list_'+id);
    if (div) div.scrollTop = div.scrollHeight;
  }

  function openreplies()
  {
    if (DOM.GetValue('hasnewreplies_'+id)>0) DOM.Open('replies_'+id,null,getreplies);
    else DOM.Open('replies_'+id,null,scrolltobottom);
    DOM.SetValue('replyopen_'+id,1);
    DOM.SetValue('last_count_'+id,DOM.GetValue('totalreplies_'+id));
    DOM.SetClass('replylink_'+id,'replyopen');
    var tab = document.getElementById('replylink_'+id).parentNode;
    if (tab) tab.className = "reply_active";
    DOM.Blur('replylink_'+id);
  }

  obj.blur();
  //clearTimeout(Messages.Refresh);
  if (DOM.GetValue('replyopen_'+id)>0)
  {
    Messages.CloseReplies(id);
  }
  else if (DOM.GetValue('sharelistopen_'+id)>0)
  {
    Messages.CloseShares(id,openreplies);
  }
  else if (DOM.GetValue('group_info_open_'+id)>0)
  {
    Messages.HideGroupInfo(obj,id,openreplies);
  }
  else if (DOM.GetValue('profile_info_open_'+id)>0)
  {
    Messages.HideMemberInfo(obj,id,openreplies);
  }
  else if (DOM.GetValue('message_info_open_'+id)>0)
  {
    Messages.HideMessageInfo(obj,id,openreplies);
  }
  else
  {
    openreplies();
  }
  return false;
}

Messages.CloseReplies = function(id,donext)
{

  var totalreplies = DOM.GetValue('totalreplies_'+id);
  if (totalreplies>0) DOM.Show('viewreplies_'+id);
  DOM.SetValue('last_count_'+id,totalreplies);
  DOM.SetClass('replylink_'+id,'');
  if (donext) DOM.Close('replies_'+id,null,donext);
  else DOM.Close('replies_'+id);
  DOM.SetValue('replyopen_'+id,0);
  var tab = document.getElementById('replylink_'+id).parentNode;
  if (tab) tab.className = "reply";
  DOM.Blur('replylink_'+id);
  return false;
}

Messages.ReplyFocus = function(obj)
{
  if (obj.className=='subdued')
  {
    obj.value = "";
    obj.className = "";
  }
}

Messages.ReplyBlur = function(obj)
{
  if (obj.value.trim()=="")
  {
    obj.value = 'Send a reply';
    obj.className = "subdued";
  }
}

Messages.ScrollToBottom = function()
{
  var scrolled_divs = document.getElementById('scrolled_divs');
  if (scrolled_divs)
  {
    var sd = scrolled_divs.value.trim();
    var list = sd.split(' ');
    for (var d=0;d<list.length;d++)
    {
      var div = document.getElementById('reply_list_'+list[d]);
      if (div) div.scrollTop = div.scrollHeight;
    }
  }
}

Messages.ShowMemberInfo = function(obj,id)
{
  function openprof()
  {
    DOM.Show('profile_info_close_link_'+id);
    DOM.Hide('profile_info_link_'+id);
    DOM.SetValue('profile_info_open_'+id,1);
    var tab = document.getElementById('profile_info_link_'+id).parentNode;
    if (tab) tab.className = "active";
    var gp = document.getElementById('group_info_'+id);
    if (gp)
    {
      DOM.Open('profile_info_'+id,null,opengroup);
    }
    else
    {
      DOM.Open('profile_info_'+id);
    }
  }

  function opengroup()
  {
    Messages.ShowGroupInfo(id);
  }

  obj.blur();

  if (DOM.GetValue('replyopen_'+id)>0)
  {
    Messages.CloseReplies(id,openprof);
  }
  else if (DOM.GetValue('sharelistopen_'+id)>0)
  {
    Messages.CloseShares(id,openprof);
  }
  else if (DOM.GetValue('message_info_open_'+id)>0)
  {
    Messages.HideMessageInfo(obj,id,openprof);
  }
  else if (DOM.GetValue('profile_info_open_'+id)==0)
  {
    openprof();
  }
  else if (DOM.GetValue('group_info_open_'+id)==0)
  {
    Messages.ShowGroupInfo(id);
  }
  return false;
}

Messages.HideMemberInfo = function(obj,id,donext)
{
  function closeprof()
  {
    DOM.SetValue('profile_info_open_'+id,0);
    DOM.Hide('profile_info_close_link_'+id);
    DOM.Show('profile_info_link_'+id);
    var tab = document.getElementById('profile_info_link_'+id).parentNode;
    if (tab) tab.className = "";
    if (donext) DOM.Close('profile_info_'+id,null,donext);
    else DOM.Close('profile_info_'+id);
  }

  obj.blur();
  if (obj.id=="" || DOM.GetValue('group_info_open_'+id)==0) closeprof();
  else
  {
    Messages.HideGroupInfo(obj,id,closeprof);
  }
  return false;
}

Messages.ShowGroupInfo = function(id)
{
  function openprof()
  {
    DOM.SetValue('group_info_open_'+id,1);
    DOM.Open('group_info_'+id);
  }

  if (DOM.GetValue('group_info_open_'+id)==0)
  {
    openprof();
  }
  return false;
}

Messages.HideGroupInfo = function(obj,id,donext)
{
  function closegroup()
  {
    DOM.SetValue('group_info_open_'+id,0);
    DOM.Hide('profile_info_close_link_'+id);
    DOM.Show('profile_info_link_'+id);
    if (donext) DOM.Close('group_info_'+id,null,donext);
    else DOM.Close('group_info_'+id);
  }

  function closeprof()
  {
    Messages.HideMemberInfo(obj,id,dolast);
  }

  obj.blur();
  if (obj.id=="" || DOM.GetValue('profile_info_open_'+id)==0) closegroup();
  else
  {
    var dolast = donext;
    donext = closeprof;
    closegroup();
  }
  return false;
}

Messages.ShowMessageInfo = function(obj,id)
{
  function openinfo()
  {
    DOM.SetValue('message_info_open_'+id,1);
    DOM.Show('message_info_close_link_'+id);
    DOM.Hide('message_info_link_'+id);
    var tab = document.getElementById('message_info_link_'+id).parentNode;
    if (tab) tab.className = "active";
    DOM.Open('message_info_'+id);
  }

  if (DOM.GetValue('sharelistopen_'+id)>0)
  {
    Messages.CloseShares(id,openinfo);
  }
  else if (DOM.GetValue('replyopen_'+id)>0)
  {
    Messages.CloseReplies(id,openinfo);
  }
  else if (DOM.GetValue('group_info_open_'+id)>0)
  {
    Messages.HideGroupInfo(obj,id,openinfo);
  }
  else if (DOM.GetValue('profile_info_open_'+id)>0)
  {
    Messages.HideMemberInfo(obj,id,openinfo);
  }
  else openinfo();
  return false;
}

Messages.HideMessageInfo = function(obj,id,donext)
{
  function closeinfo()
  {
    DOM.SetValue('message_info_open_'+id,0);
    DOM.Hide('message_info_close_link_'+id);
    DOM.Show('message_info_link_'+id);
    var tab = document.getElementById('message_info_link_'+id).parentNode;
    if (tab) tab.className = "";
    if (donext) DOM.Close('message_info_'+id,null,donext);
    else DOM.Close('message_info_'+id);
  }

  obj.blur();
  if (DOM.GetValue('message_info_open_'+id)==1)
  {
    closeinfo();
  }
  return false;
}

Messages.Count = function(e,obj,prefix,dest,id)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,REPLY_LENGTH);
    obj.scrollTop = st;
  }

  if (window.event) key = window.event.keyCode;
  else key = e.which;
  if (key==13)
  {
    obj.blur();
    if (obj.id.substr(0,5)=='share') Messages.SendShare(id,dest);
    else Messages.SendReply(id,dest);
  }
  else
  {
    if (obj.value.length > REPLY_LENGTH) disallow();
    var counter = document.getElementById(prefix+dest);
    if (counter)
    {
      counter.innerHTML = REPLY_LENGTH-obj.value.length;
    }
  }
}

Messages.SendReply = function(id,post)
{
  var msg = document.getElementById('reply_text_'+post);
  if (msg)
  {
    clearTimeout(Messages.Refresh);
    var message = msg.value.trim();
    if (msg.className=='subdued' || message == '') alert("Please enter text for your reply.");
    else
    {
      Messages.SentReply = id;
      var reply = new Ajax(Messages.ReplySent);
      var params = "isajax=1";
      params += "&parent="+id;
      params += "&text="+encodeURIComponent(message);
      var url = "ajax/send_reply";
      DOM.Show('user_loading');
      msg.value = "";
      reply.sendPostRequest(url,params);
    }
  }
  else alert("Error: Unable to read reply form");
  return false;
}

Messages.ReplySent = function(response)
{
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    var msg = document.getElementById('reply_text_'+Messages.SentReply);
    if (msg)
    {
      msg.className = 'subdued';
      msg.value = "Send a reply";
    }
    if (Messages.MainPage=='tabs' && (Tabs.Tab=='discussed' || Tabs.Tab=='shared'))
    {
      var reply = new Ajax(Messages.ShowReply);
      var params = "isajax=1";
      params += "&id="+response.substr(2);
      var url = "ajax/get_reply";
      reply.sendPostRequest(url,params);
    }
    else
    {
      clearTimeout(Messages.Refresh);
      Messages.CheckRefresh(Messages.SentReply);
      Messages.SentReply = null;
    }
  }
  else if (response=="deleted")
  {
    alert("Sorry, this message has been deleted by the original poster. We are unable to post your reply.");
    Messages.DeletedType = "message";
    Messages.Deleted('ok'+Messages.SentReply);
    Messages.SentReply = null;
  }
  else
  {
    alert("Error: " + response);
  }
}

Messages.ShowReply = function(response)
{
  var reply = response.jsonParse();
  if (reply)
  {
    var parentpost = document.getElementById('reply_list_'+reply.parent_id);
    if (parentpost && !DOM.Exists('messagereplycontainer_'+reply.id))
    {
      var htm = Messages.ReplyHTML;
      if (reply.candelete)
      {
        htm = htm.replace(/%DELETE%/,Messages.DeleteHTML);
      }
      else htm = htm.replace(/%DELETE%/,"");
      htm = htm.replace(/%ID%/g,reply.id);
      htm = htm.replace(/%TYPE%/,'');
      htm = htm.replace(/%MESSAGETEXT%/,reply.text);
      htm = htm.replace(/%POSTERID%/g,reply.userid);
      htm = htm.replace(/%POSTERNAME%/g,reply.username);
      htm = htm.replace(/%AVATAR%/g,reply.avatar);
      htm = htm.replace(/%TIMESTR%/g,reply.time_posted);

      var div = document.createElement('div');
      div.className = "replycontainer";
      div.id = 'messagereplycontainer_'+reply.id;
      div.innerHTML = htm;
      div.className = "replycontainer highlight";
      parentpost.appendChild(div);
      DOM.Show('reply_list_'+reply.parent_id);
      Messages.Fader(reply.id,true,false,false,'');
      Messages.Slide(reply.id,true,false,'');
    }
  }
}

Messages.Slide = function(id,isreply,isshare,prefix)
{
  function slideOpen()
  {
    currentHeight += 40;
    if (currentHeight >= ht)
    {
      obj.style.height = ht+'px';
      obj.parentNode.scrollTop = obj.parentNode.scrollHeight;
      obj.parentNode.parentNode.style.height = 'auto';
      clearTimeout(Opener);
    }
    else
    {
      obj.style.height = currentHeight+'px';
      obj.parentNode.scrollTop = obj.parentNode.scrollHeight;
      obj.parentNode.parentNode.style.height = 'auto';

      Opener = setTimeout(slideOpen,0);
    }
  }

  function clearheight()
  {
    if (obj) obj.style.height = 'auto';
    DOM.Hide('message_embed_placeholder_'+id);
    DOM.Show('message_embed_'+id);
  }

  if (isshare)
  {
    var obj = document.getElementById(prefix+'messagesharecontainer_'+id);
    //var obj = document.getElementById('messagesharecontainer_'+id);
    var Opener;
    obj.style.display = "";
    obj.style.overflow = "hidden";
    var ht = obj.scrollHeight;
    var currentHeight = 0;
    obj.style.height = "0";
    Opener = setTimeout(slideOpen,0);
  }
  else if (isreply)
  {
    var obj = document.getElementById(prefix+'messagereplycontainer_'+id);
    var Opener;
    obj.style.display = "";
    obj.style.overflow = "hidden";
    var ht = obj.scrollHeight;
    var currentHeight = 0;
    obj.style.height = "0";
    Opener = setTimeout(slideOpen,0);
  }
  else
  {
    var obj = document.getElementById(prefix+'messagecontainer_'+id);
    //var obj = document.getElementById('messagecontainer_'+id);
    DOM.Show('message_embed_placeholder_'+id);
    DOM.Hide('message_embed_'+id);
    DOM.Open(prefix+'messagecontainer_'+id,null,clearheight);
  }
}

Messages.Fader = function(id,inlinereply,isreply,isshare,prefix)
{
  function fade()
  {
    if (inlinereply)
    {
      var message = document.getElementById(prefix+'messagereplycontainer_'+id);
      var cls = "replycontainer";
    }
    else if (isshare)
    {
      var message = document.getElementById(prefix+'messagesharecontainer_'+id);
      var cls = "sharecontainer";
    }
    else
    {
      var message = document.getElementById('message_'+id);
      var cls = "message";
    }
    if (message)
    {
      switch(fadelevel)
      {
        case 0:
          message.className = cls + " fade1";
          fadelevel = 1;
          fader = setTimeout(fade,fadespeed);
          break;
        case 1:
          message.className = cls + " fade2";
          fadelevel = 2;
          fader = setTimeout(fade,fadespeed);
          break;
        case 2:
          message.className = cls + " fade3";
          fadelevel = 3;
          fader = setTimeout(fade,fadespeed);
          break;
        case 3:
          message.className = cls;
          break;
      }
    }
  }

  var fader = setTimeout(fade,NEW_MESSAGE_HIGHLIGHT_TIME);
  var fadelevel = 0;
  var fadespeed = 100;
}

Messages.AcceptGroupInvitation = function(obj,groupid)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      alert("You are now a member of the '" + response.substr(2) + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/accept_invitation/"+groupid;
  group.sendPostRequest(url,params);
  return false;
}

Messages.DeclineGroupInvitation = function(obj,groupid)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      alert("You have declined an invitation to join the '" + response.substr(2) + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/decline_invitation/"+groupid;
  group.sendPostRequest(url,params);
  return false;
}

Messages.AcceptMemberRequest = function(obj,groupid,userid)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      var names = response.substr(2).split('_');
      alert("You have accepted " + names[1] + "'s request to join the '" + names[0] + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else if (response=='gone')
    {
      alert("Membership request has already been accepted, declined, or canceled.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  params += "&user="+userid;
  var url = "groups/accept_member/"+groupid;
  group.sendPostRequest(url,params);
  return false;
}

Messages.DeclineMemberRequest = function(obj,groupid,userid)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      var names = response.substr(2).split('_');
      alert("You have declined " + names[1] + "'s request to join the '" + names[0] + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  params += "&user="+userid;
  var url = "groups/decline_member/"+groupid;
  group.sendPostRequest(url,params);
  return false;
}

Messages.CancelMemberRequest = function(obj,groupid)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      alert("You have canceled your request to join the '" + response.substr(2) + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/cancel_membership_request/"+groupid;
  group.sendPostRequest(url,params);
  return false;
}

Messages.AcceptFollowRequest = function(obj,followed_id,follower_id)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      var fname = response.substr(2);
      alert("You have accepted " + fname + "'s request to follow your feed.");
      Messages.Delete(msg[1],false,true);
    }
    else if (response=='gone')
    {
      alert("Follow request has already been accepted, declined, or canceled.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "ajax/accept_follower/"+follower_id;
  group.sendPostRequest(url,params);
  return false;
}

Messages.DeclineFollowRequest = function(obj,followed_id,follower_id)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      var fname = response.substr(2);
      alert("You have declined " + fname + "'s request to follow your feed.");
      Messages.Delete(msg[1],false,true);
    }
    else if (response=='gone')
    {
      alert("Follow request has already been accepted, declined, or canceled.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "ajax/decline_follower/"+follower_id;
  group.sendPostRequest(url,params);
  return false;
}

Messages.CancelMemberRequest = function(obj,followed_id)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      alert("You have canceled your request to join the '" + response.substr(2) + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/cancel_membership_request/"+followed_id;
  group.sendPostRequest(url,params);
  return false;
}

Messages.AcceptAdminInvitation = function(obj,groupid)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      alert("You are now an Administrator for the '" + response.substr(2) + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else if (response=='gone')
    {
      alert("Invitation has already been accepted, declined, or canceled.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/accept_admin/"+groupid;
  group.sendPostRequest(url,params);
  return false;
}

Messages.DeclineAdminInvitation = function(obj,groupid)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      alert("You have declined an invitation to be an Administrator for the '" + response.substr(2) + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else if (response=='gone')
    {
      alert("Invitation has already been accepted, declined, or canceled.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/decline_admin/"+groupid;
  group.sendPostRequest(url,params);
  return false;
}

Messages.AcceptOwnerInvitation = function(obj,groupid)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      alert("You are now the Owner of the '" + response.substr(2) + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else if (response=='gone')
    {
      alert("Invitation has already been accepted, declined, or canceled.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/accept_ownership/"+groupid;
  group.sendPostRequest(url,params);
  return false;
}

Messages.DeclineOwnerInvitation = function(obj,groupid)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response.substr(0,2)=='ok')
    {
      alert("You have declined an invitation to become Owner of the '" + response.substr(2) + "' group.");
      Messages.Delete(msg[1],false,true);
    }
    else if (response=='gone')
    {
      alert("Invitation has already been accepted, declined, or canceled.");
      Messages.Delete(msg[1],false,true);
    }
    else alert("Error: "+response);
  }

  var msg = obj.parentNode.id.split('_');
  DOM.Show('user_loading');
  var group = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/decline_ownership/"+groupid;
  group.sendPostRequest(url,params);
  return false;
}

Messages.Ignore = function(postlink)
{
  try
  {
    var postid = postlink.parentNode.id.substr(postlink.parentNode.id.lastIndexOf('_')+1);
    Messages.Delete(postid,0,true);
  }
  catch(err)
  {
    alert("Error:\n"+err.description);
  }
  return false;
}

Messages.Follow = function(postlink,userid,username)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      alert("You are now following " + username);
      try
      {
        var postid = postlink.parentNode.id.substr(postlink.parentNode.id.lastIndexOf('_')+1);
        Messages.Delete(postid,0,true);
      }
      catch(err)
      {
        alert("Error:\n"+err.description);
      }
    }
    else
    {
      alert("Error:\n"+response);
    }
  }

  DOM.Show('user_loading');
  var subscribe = new Ajax(ret);
  var params = "isajax=1";
  var url = "members/subscribe/"+userid;
  subscribe.sendPostRequest(url,params);
  return false;
}

Messages.Subscribe = function(postid,userid,username)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
      alert("You are now following " + username);
      DOM.Hide('subscribe_'+userid+'_'+postid);
    }
    else
    {
      alert("Error:\n"+response);
    }
  }
  alert("Unexpected call to Messages.Subscribe in messagelist.js line 2345");
  DOM.Show('user_loading');
  var subscribe = new Ajax(ret);
  var params = "isajax=1";
  var url = "members/subscribe/"+userid;
  subscribe.sendPostRequest(url,params);
  return false;
}

Messages.ReloadPage = function()
{
  switch (Messages.MainPage)
  {
    case 'user':
      var nav = 'user_'+User.CurrentMain;
      if (User.CurrentSub)
      {
        nav += '_'+User.CurrentSub;
        if (User.CurrentSubSub)
        {
          nav += '_'+User.CurrentSubSub;
          if (User.FilterType) nav += '_'+User.FilterType;
        }
      }
      User.Navigate(nav);
      break;
    case 'groups':
      Group.Navigate('group_'+Group.Current);
      break;
    case 'members':
      var nav = 'profile_'+Profile.Current;
      if (Profile.Sub)
      {
        nav += '_'+Profile.Sub;
      }
      Profile.Navigate(nav);
      break;
    case 'tabs':
      DOM.Hide('update_counter');
      DOM.Hide('updatecounter0');
      DOM.Hide('updatecounter1');
      DOM.Hide('updatecounterx');
      DOM.SetHTML('update_count','');
      Tabs.GetMessages();
      break;
  }
  return false;
}

Messages.Ajax = new Ajax(Messages.Got);
Messages.RefreshAjax = new Ajax(Messages.DoRefresh);