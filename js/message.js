var Message = {};

Message.IsPaused = false;
Message.ForceUpdate = false;

Message.Init = function(id,reply,totalreplies,share,totalshares,msgtype)
{
  Message.ID = id;
  Message.ReplyID = reply;
  Message.ShareID = share;
  Message.IsPrivate = DOM.GetValue('message_is_private')==1 ? true : false;
  Message.LastCheck = 0;
  Message.Type = msgtype;
  Message.TotalReplies = totalreplies;
  Message.TotalShares = totalshares;
  if (Message.ShareID>0)
  {
    DOM.SetValue('replyopen',0);
    DOM.SetValue('sharelistopen',1);
  }
  else
  {
    DOM.SetValue('replyopen',1);
    DOM.SetValue('sharelistopen',0);
  }
  DOM.SetValue('group_info_open',0);
  DOM.SetValue('profile_info_open',0);
  DOM.SetValue('message_info_open',0);
  if (Message.ReplyID)
  {
    var div = document.getElementById('replycontainer_'+Message.ReplyID);
    if (div) Message.Fader(div,'replycontainer');
  }
  else
  if (Message.ShareID)
  {
    var div = document.getElementById('messagesharecontainer_'+Message.ShareID);
    if (div) Message.Fader(div,'sharecontainer');
  }
  if (!Message.IsPrivate) Message.Refresh = setTimeout(Message.CheckRefresh,MESSAGE_REFRESH);
}

Message.ShowRecipients = function()
{
  DOM.Hide('showrecipients');
  DOM.Show('showingrecipients');
  DOM.Show('recipients');
  return false;
}

Message.HideRecipients = function()
{
  DOM.Show('showrecipients');
  DOM.Hide('showingrecipients');
  DOM.Hide('recipients');
  return false;
}

Message.ShareCount = function(e,obj,dest)
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
    Message.SendShare();
  }
  else
  {
    if (obj.value.length > SHARE_LENGTH) disallow();
    var counter = document.getElementById(dest);
    if (counter)
    {
      counter.innerHTML = SHARE_LENGTH-obj.value.length;
    }
  }
}

Message.SendShare = function()
{
  var msg = document.getElementById('share_text');
  if (msg)
  {
    clearTimeout(Message.Refresh);
    var message = msg.value.trim();
    if (message=='Do you want to say something about this ' + Message.Type + ' before you share it?') message = "";
    var share = new Ajax(Message.ShareSent);
    var params = "isajax=1";
    params += "&message="+Message.ID;
    params += "&text="+encodeURIComponent(message);
    var url = "ajax/send_share";
    DOM.Show('user_loading');
    msg.value = "";
    share.sendPostRequest(url,params);
  }
  else alert("Error: Unable to read share form");
  return false;
}

Message.ShareSent = function(response)
{
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    DOM.Close('shareform',null,Message.GetUpdates);
    
  }
  else if (response=="deleted")
  {
    alert("Sorry, this message has been deleted by the original poster. We are unable to share it.");
    location.reload();
  }
  else
  {
    alert("Error: " + response);
  }
}

Message.OpenShares = function(obj)
{ 
  function getshares()
  {
    Message.GetNewShares();
  }
  
  function scrolltobottom(noref)
  {
    var div = document.getElementById('sharelist');
    if (div)
    {
      div.style.height = 'auto';
      div.scrollTop = div.scrollHeight;
    }
    //if (!noref) Message.Refresh = setTimeout(Message.CheckRefresh,MESSAGE_REFRESH);
  }
  
  function openshares(response)
  {
    DOM.Show('closesharelink');
    DOM.Hide('viewsharelink');
    DOM.Show('shareoff');
    DOM.Hide('shareon');
    if (DOM.GetValue('hasnewshares')>0) DOM.Open('sharelist',null,getshares);
    else DOM.Open('sharelist',null,scrolltobottom);
    DOM.SetValue('sharelistopen',1);
    if (REFRESH_MODE != 'm') DOM.SetValue('last_share_count',DOM.GetValue('totalshares'));
    DOM.SetClass('sharelink','shareopen');
    DOM.Blur('sharelink');
    var tab = document.getElementById('sharelink').parentNode;
    if (tab) tab.className = "share_active";
  }
  
  obj.blur();
  if (DOM.GetValue('sharelistopen')>0)
  {
    Message.CloseShares();
  }
  else if (DOM.GetValue('replyopen')>0)
  {
    Message.CloseReplies(openshares);
  }
  else if (DOM.GetValue('group_info_open')>0)
  {
    Message.HideGroupInfo(obj,openshares);
  }
  else if (DOM.GetValue('profile_info_open')>0)
  {
    Message.HideMemberInfo(obj,openshares);
  }
  else if (DOM.GetValue('message_info_open')>0)
  {
    Message.HideMessageInfo(obj,openshares);
  }
  else openshares();
  return false;
}

Message.CloseShares = function(donext)
{
  DOM.SetClass('sharelink','');
  DOM.Hide('closesharelink');
  DOM.Show('viewsharelink');
  DOM.Hide('shareoff');
  DOM.Show('shareon');
  var d = document.getElementById('content_div');
  var ht = parseInt(d.style.minHeight);
  if (DOM.GetHeight('content_div')>ht+50) d.style.minHeight = (DOM.GetHeight('content_div')-50)+'px';
  DOM.Close('sharelist',null,donext);
  DOM.SetValue('sharelistopen',0);
  DOM.Blur('sharelink');
  var tab = document.getElementById('sharelink').parentNode;
  if (tab) tab.className = "share";
  return false;
}

Message.ShareFocus = function(obj)
{
  if (obj.className=='subdued')
  {
    obj.value = "";
    obj.className = "";
  }
}

Message.ShareBlur = function(obj)
{
  if (obj.value.trim()=="")
  {
    obj.value = 'Do you want to say something about this ' + Message.Type + ' before you share it?';
    obj.className = "subdued";
  }
}

Message.OpenReplies = function(obj)
{
  function getreplies()
  {
    scrolltobottom(true);
    Message.GetNewReplies();
  }
  
  function scrolltobottom(noref)
  {
    var div = document.getElementById('replies');
    if (div)
    {
      div.style.height = 'auto';
      div.scrollTop = div.scrollHeight;
    }
    //if (!noref) Message.Refresh = setTimeout(Message.CheckRefresh,MESSAGE_REFRESH);
  }
  
  function openreplies()
  {
    DOM.Show('hidereplies');
    DOM.Hide('viewreplies');
    DOM.Show('replyoff');
    DOM.Hide('replyon');
    if (DOM.GetValue('hasnewreplies')>0) DOM.Open('replies',null,getreplies);
    else DOM.Open('replies',null,scrolltobottom);
    DOM.SetValue('replyopen',1);
    if (REFRESH_MODE != 'm') DOM.SetValue('last_count',DOM.GetValue('totalreplies'));
    DOM.SetClass('replylink','replyopen');
    DOM.Blur('replylink');
    var tab = document.getElementById('replylink').parentNode;
    if (tab) tab.className = "reply_active";
  }
  
  obj.blur();
  clearTimeout(Message.Refresh);
  if (DOM.GetValue('replyopen')>0)
  {
    Message.CloseReplies();
  }
  else if (DOM.GetValue('sharelistopen')>0)
  {
    Message.CloseShares(openreplies);
  }
  else if (DOM.GetValue('group_info_open')>0)
  {
    Message.HideGroupInfo(obj,openreplies);
  }
  else if (DOM.GetValue('profile_info_open')>0)
  {
    Message.HideMemberInfo(obj,openreplies);
  }
  else if (DOM.GetValue('message_info_open')>0)
  {
    Message.HideMessageInfo(obj,openreplies);
  }
  else
  {
    openreplies();
  }
  return false;
}

Message.CloseReplies = function(donext)
{
  DOM.Hide('hidereplies');
  var totalreplies = DOM.GetValue('totalreplies');
  if (totalreplies>0) DOM.Show('viewreplies');
  if (REFRESH_MODE != 'm') DOM.SetValue('last_count',totalreplies);
  DOM.SetClass('replylink','');
  DOM.Hide('replyoff');
  DOM.Show('replyon');
  var d = document.getElementById('content_div');
  var ht = parseInt(d.style.minHeight);
  if (DOM.GetHeight('content_div')>ht+50) d.style.minHeight = (DOM.GetHeight('content_div')-50)+'px';
  DOM.Close('replies',null,donext);
  DOM.SetValue('replyopen',0);
  DOM.Blur('replylink');
  var tab = document.getElementById('replylink').parentNode;
  if (tab) tab.className = "reply";
  return false;
}

Message.ReplyFocus = function(obj)
{
  if (obj.className=='subdued')
  {
    obj.value = "";
    obj.className = "";
  }
}

Message.ReplyBlur = function(obj)
{
  if (obj.value.trim()=="")
  {
    obj.value = 'Send a reply';
    obj.className = "subdued";
  }
}

Message.ShowMemberInfo = function(obj)
{
  function openprof()
  {
    DOM.Show('profile_info_close_link');
    DOM.Hide('profile_info_link');
    DOM.SetValue('profile_info_open',1);
    var tab = document.getElementById('profile_info_link').parentNode;
    if (tab) tab.className = "active";
    var gp = document.getElementById('group_info');
    if (gp)
    {
      DOM.Open('profile_info',null,opengroup);
    }
    else
    {
      DOM.Open('profile_info');
    }
  }
  
  function opengroup()
  {
    Message.ShowGroupInfo();
  }
  
  obj.blur();
  
  if (DOM.GetValue('replyopen')>0)
  {
    Message.CloseReplies(openprof);
  }
  else if (DOM.GetValue('sharelistopen')>0)
  {
    Message.CloseShares(openprof);
  }
  else if (DOM.GetValue('message_info_open')>0)
  {
    Message.HideMessageInfo(obj,openprof);
  }
  else if (DOM.GetValue('profile_info_open')==0)
  { 
    openprof();
  }
  else if (DOM.GetValue('group_info_open')==0)
  {
    Message.ShowGroupInfo();
  }
  return false;
}

Message.HideMemberInfo = function(obj,donext)
{
  function closeprof()
  {
    DOM.SetValue('profile_info_open',0);
    DOM.Hide('profile_info_close_link');
    DOM.Show('profile_info_link');
    var d = document.getElementById('content_div');
    var ht = parseInt(d.style.minHeight);
    if (DOM.GetHeight('content_div')>ht+50) d.style.minHeight = (DOM.GetHeight('content_div')-50)+'px';
    var tab = document.getElementById('profile_info_link').parentNode;
    if (tab) tab.className = "";
    if (donext) DOM.Close('profile_info',null,donext);
    else DOM.Close('profile_info');
  }
  
  obj.blur();
  if (obj.id=="" || DOM.GetValue('group_info_open')==0) closeprof();
  else
  {
    Message.HideGroupInfo(obj,closeprof);
  }
  return false;
}

Message.ShowGroupInfo = function()
{
  function openprof()
  {
    DOM.SetValue('group_info_open',1);
    DOM.Open('group_info');
  }
  
  if (DOM.GetValue('group_info_open')==0)
  {
    openprof();
  }
  return false;
}

Message.HideGroupInfo = function(obj,donext)
{
  function closegroup()
  { 
    DOM.SetValue('group_info_open',0);
    DOM.Hide('profile_info_close_link');
    DOM.Show('profile_info_link');
    var d = document.getElementById('content_div');
    var ht = parseInt(d.style.minHeight);
    if (DOM.GetHeight('content_div')>ht+50) d.style.minHeight = (DOM.GetHeight('content_div')-50)+'px';
    if (donext) DOM.Close('group_info',null,donext);
    else DOM.Close('group_info');
  }
  
  function closeprof()
  {
    Message.HideMemberInfo(obj,dolast);
  }
  
  obj.blur();
  if (obj.id=="" || DOM.GetValue('profile_info_open')==0) closegroup();
  else
  {
    var dolast = donext;
    donext = closeprof;
    closegroup();
  }
  return false;
}

Message.ShowMessageInfo = function(obj)
{
  function openinfo()
  {
    DOM.SetValue('message_info_open',1);
    DOM.Show('message_info_close_link');
    DOM.Hide('message_info_link');
    var tab = document.getElementById('message_info_link').parentNode;
    if (tab) tab.className = "active";
    DOM.Open('message_info');
  }
  
  if (DOM.GetValue('sharelistopen')>0)
  {
    Message.CloseShares(openinfo);
  }
  else if (DOM.GetValue('replyopen')>0)
  {
    Message.CloseReplies(openinfo);
  }
  else if (DOM.GetValue('group_info_open')>0)
  {
    Message.HideGroupInfo(obj,openinfo);
  }
  else if (DOM.GetValue('profile_info_open')>0)
  {
    Message.HideMemberInfo(obj,openinfo);
  }
  else openinfo();
  return false;
}

Message.HideMessageInfo = function(obj,donext)
{
  function closeinfo()
  { 
    DOM.SetValue('message_info_open',0);
    DOM.Hide('message_info_close_link');
    DOM.Show('message_info_link');
    var d = document.getElementById('content_div');
    var ht = parseInt(d.style.minHeight);
    if (DOM.GetHeight('content_div')>ht+50) d.style.minHeight = (DOM.GetHeight('content_div')-50)+'px';
    var tab = document.getElementById('message_info_link').parentNode;
    if (tab) tab.className = "";
    if (donext) DOM.Close('message_info',null,donext);
    else DOM.Close('message_info');
  }
  
  obj.blur();
  if (DOM.GetValue('message_info_open')==1)
  {
    closeinfo();
  }
  return false;
}

Message.DeleteMessage = function(id)
{
  if (confirm("Really delete this message and all its replies?"))
  {
    var url = HTTP_BASE + "ajax/delete_message/"+id;
    var del = new Ajax(Message.Deleted);
    DOM.Show('user_loading');
    del.sendRequest(url);
  }
  return false;
}

Message.Delete = function(obj,id,isshare)
{
  obj.blur();
  var confirmstr = "Really delete this " + (isshare ? 'share?' : 'reply?');
  if (confirm(confirmstr))
  {
    var url = HTTP_BASE + "ajax/delete_message/"+id;
    var del = new Ajax(Message.Deleted);
    DOM.Show('user_loading');
    del.sendRequest(url);
  }
  return false;
}

Message.Deleted = function(response)
{
  function remove_message()
  {
    msg.parentNode.removeChild(msg);
  }
  
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    var id = response.substr(2);
    var msg = document.getElementById('replycontainer_'+id);
    if (msg)
    {
      msg.style.overflow = "hidden";
      DOM.Close(msg.id,null,remove_message);
    }
    else
    {
      var msg = document.getElementById('messagesharecontainer_'+id);
      if (msg)
      {
        msg.style.overflow = "hidden";
        DOM.Close(msg.id,null,remove_message);
      }
      else
      {
        alert("Your message has been deleted.");
        location.href=HTTP_BASE;
      }
    }
  }
  else
  {
    alert(response);
  }
}

Message.GetAllReplies = function(init)
{
  var message = new Ajax(Message.DoRefresh);
  var url = "messages/checkreplies/"+Message.ID;
  var params = "isajax=1";
  params += "&lastcheck="+Message.LastCheck;
  if (init) params += "&init=1";
  message.sendPostRequest(url,params);
}

Message.GetUpdates = function()
{
  Message.ForceUpdate = true;
  Message.CheckRefresh();
  return false;
}

Message.CheckRefresh = function()
{
  clearTimeout(Message.Refresh);
  var message = new Ajax(Message.DoRefresh);
  var url = "messages/checkreplies/"+Message.ID;
  var params = "isajax=1";
  params += "&lastcheck="+Message.LastCheck;
  if (Message.ForceUpdate) params += "&mode=f";
  else params += "&mode="+REFRESH_MODE;
  Message.ForceUpdate = false;
  message.sendPostRequest(url,params);
}

Message.DoRefresh = function(response)
{
  if (response.substr(0,2) != 'ok')
  {
    var resp = response.jsonParse();
    var reply = resp.replies;
    var share = resp.shares;
    var isman = (resp.mode=='m');
    var ispaused = (Message.IsPaused && resp.mode!='f');
    Message.LastCheck = resp.lastcheck;
    var replylist = document.getElementById('reply_list');
    var replycount = 0;
    Message.TotalReplies = reply.length;
    if (Message.TotalReplies > 0)
    {
      DOM.SetHTML('replies_count',Message.TotalReplies);
      DOM.Show('replies_count');
    }
    else
    {
      DOM.SetHTML('replies_count','');
      DOM.Hide('replies_count');
    }
    var sharelist = document.getElementById('share_list');
    var sharecount = 0;
    Message.TotalShares = share.length;
    if (Message.TotalShares > 0)
    {
      DOM.SetHTML('shares_count',share.length);
      DOM.Show('shares_count');
      DOM.Show('message_shared');
      DOM.Hide('message_not_shared');
    }
    else
    {
      DOM.SetHTML('shares_count','');
      DOM.Hide('shares_count');
      DOM.Hide('message_shared');
      DOM.Show('message_not_shared');
    }
    
    if (reply)
    {
      for (var r=0;r<reply.length;r++)
      {
        var replyexists = document.getElementById('replycontainer_'+reply[r].id);
        if (!replyexists)
        {
          if (ispaused || isman) replycount++;
          else
          {
            if (replylist)
            {
              var div = document.createElement('div');
              div.id = "replycontainer_"+reply[r].id;
              if (resp.init)
              {
                div.className = "replycontainer";
                div.innerHTML = reply[r].html;
                replylist.appendChild(div);
              }
              else
              {
                div.className = "replycontainer highlight";
                div.innerHTML = reply[r].html;
                Message.Fader(div,'replycontainer');
                replylist.appendChild(div);
                Message.Slide('replycontainer_'+reply[r].id,'reply');
              }
            }
          }
        }
      }
    }
    if (share)
    {
      for (var r=0;r<share.length;r++)
      {
        var shareexists = document.getElementById('messagesharecontainer_'+share[r].id);
        if (!shareexists)
        {
          if (ispaused || isman) sharecount++;
          else
          {
            if (sharelist)
            {
              var div = document.createElement('div');
              div.id = "messagesharecontainer_"+share[r].id;
              if (resp.init)
              {
                div.className = "sharecontainer";
                div.innerHTML = share[r].html;
                sharelist.appendChild(div);
              }
              else
              {
                div.className = "sharecontainer highlight";
                div.innerHTML = share[r].html;
                Message.Fader(div,'sharecontainer');
                sharelist.appendChild(div);
                Message.Slide('messagesharecontainer_'+share[r].id,'share');
              }
            }
          }
        }
      }
    }
    if (ispaused)
    {
      if (replycount>0 || sharecount>0)
      {
        DOM.Show('pausecolon')
        if (replycount>1)
        {
          DOM.SetHTML('pause_replycount',replycount);
          DOM.Hide('pausereply1');
          DOM.Show('pausereplyx');
        }
        else if (replycount==1)
        {
          DOM.Show('pausereply1');
          DOM.Hide('pausereplyx');
        }
        else
        {
          DOM.Hide('pausereply1');
          DOM.Hide('pausereplyx');
        }
        if (sharecount>1)
        {
          DOM.SetHTML('pause_sharecount',sharecount);
          DOM.Hide('pauseshare1');
          DOM.Show('pausesharex');
        }
        else if (sharecount==1)
        {
          DOM.Show('pauseshare1');
          DOM.Hide('pausesharex');
        }
        else
        {
          DOM.Hide('pauseshare1');
          DOM.Hide('pausesharex');
        }
      }
      else
      {
        DOM.Hide('pausecolon');
        DOM.Hide('pausereply1');
        DOM.Hide('pausereplyx');
        DOM.Hide('pauseshare1');
        DOM.Hide('pausesharex');
      }
    }
    else if (isman)
    {
      if (replycount>0 || sharecount>0)
      {
        DOM.Show('update_counter')
        if (replycount>1)
        {
          DOM.SetHTML('update_replycount',replycount);
          DOM.Hide('replycounter1');
          DOM.Show('replycounterx');
        }
        else if (replycount==1)
        {
          DOM.Show('replycounter1');
          DOM.Hide('replycounterx');
        }
        else
        {
          DOM.Hide('replycounter1');
          DOM.Hide('replycounterx');
        }
        if (sharecount>1)
        {
          DOM.SetHTML('share_count',sharecount);
          DOM.Hide('sharecounter1');
          DOM.Show('sharecounterx');
        }
        else if (sharecount==1)
        {
          DOM.Show('sharecounter1');
          DOM.Hide('sharecounterx');
        }
        else
        {
          DOM.Hide('sharecounter1');
          DOM.Hide('sharecounterx');
        }
      }
      else
      {
        DOM.Hide('update_counter');
        DOM.Hide('replycounter1');
        DOM.Hide('replycounterx');
        DOM.Hide('sharecounter1');
        DOM.Hide('sharecounterx');
      }
    }
    
    if (resp.init)
    {
      alert('unexpected value at message.js line 673');
      if (REFRESH_MODE!='m')
      {
        DOM.Show('reply_counter');
      }
      if (Message.ReplyID>0)
      {
        var Reply = document.getElementById('reply_'+Message.ReplyID);
        var div = document.getElementById('wrapper');
        if (div) div.scrollTop = (DOM.PosY(Reply)-640);
        Reply.className = "replycontainer highlight";
        Message.Fader(Message.ReplyID,'replycontainer');
      }
      else if (Message.ShareID>0)
      {
        var Share = document.getElementById('share_'+Message.ShareID);
        var div = document.getElementById('wrapper');
        if (div) div.scrollTop = (DOM.PosY(Share)-640);
        Share.className = "sharecontainer highlight";
        Message.Fader(Message.ShareID,'sharecontainer');
      }
    }
  }
  else
  {
    var resp = response.split('_');
    Message.LastCheck = resp[1];
  }
  Message.Refresh = setTimeout(Message.CheckRefresh,MESSAGE_REFRESH);
}

Message.PauseUpdates = function()
{
  if (REFRESH_MODE=='m' || Message.IsPrivate) return;
  clearTimeout(Message.MO);
  Message.IsPaused = true;
  DOM.Hide('pausecolon');
  DOM.Hide('pausereplyx');
  DOM.Hide('pausereply1');
  DOM.Hide('pausesharex');
  DOM.Hide('pauseshare1');
  DOM.Show('pause_counter');
  //DOM.SetClass('message','message paused');
  DOM.SetClass('content_div','content_with_rcol paused');
}

Message.ResumeUpdates = function()
{
  function resumeupdates()
  {
    clearTimeout(Message.Refresh);
    Message.IsPaused = false;
    DOM.Hide('pause_counter');
    //DOM.SetClass('message','message');
    DOM.SetClass('content_div','content_with_rcol');
    Message.CheckRefresh();
  }
  
  if (REFRESH_MODE=='m' || Message.IsPrivate) return;
  Message.MO = setTimeout(resumeupdates,10);
}

Message.ScrollToBottom = function()
{
  var div = document.getElementById('wrapper');
  if (div) div.scrollTop = div.scrollHeight;
}


Message.SendReply = function()
{
  var msg = document.getElementById('reply_text');
  if (msg)
  {
    var message = msg.value.trim();
    if (msg.className=='subdued' || message == '') alert("Please enter text for your reply.");
    else
    {
      msg.value = "";
      DOM.SetHTML('reply_count','140');
      var reply = new Ajax(Message.ReplySent);
      var params = "isajax=1";
      params += "&parent="+Message.ID;
      params += "&text="+encodeURIComponent(message);
      var url = "ajax/send_reply";
      DOM.Show('user_loading');
      reply.sendPostRequest(url,params);
    }
  }
  else alert("Error: Unable to read reply form");
  return false;
}

Message.ReplySent = function(response)
{
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    Message.GetUpdates();
  }
  else if (response=="deleted")
  {
    alert("Sorry, this message has been deleted by the original poster. We are unable to post your reply.");
    location.href = HTTP_BASE;
  }
  else
  {
    alert("Error: "+response);
  }
}

Message.Count = function(e,obj,dest,share)
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
    if (share) Message.SendShare();
    else Message.SendReply();
  }
  if (obj.value.length > REPLY_LENGTH) disallow();
  var counter = document.getElementById(dest);
  if (counter)
  {
    counter.innerHTML = REPLY_LENGTH-obj.value.length;
  }
}

Message.Slide = function(id)
{
  function slideOpen()
  {
    currentHeight += 40;
    if (currentHeight >= ht)
    {
      obj.style.height = 'auto';
      //Message.ScrollToBottom();
      clearTimeout(Opener);
    }
    else
    {
      obj.style.height = currentHeight+'px';
      //Message.ScrollToBottom();
      Opener = setTimeout(slideOpen,0);
    }
  }
  
  var obj = document.getElementById(id);
  var Opener;
  obj.style.display = "";
  obj.style.overflow = "hidden";
  var ht = obj.scrollHeight;
  var currentHeight = 0;
  obj.style.height = "0";
  Opener = setTimeout(slideOpen,0);
}

Message.Fader = function(div,baseclass)
{
  function fade()
  {
    switch(fadelevel)
    {
      case 0:
        div.className = baseclass + " fade1";
        fadelevel = 1;
        fader = setTimeout(fade,fadespeed);
        break;
      case 1:
        div.className = baseclass + " fade2";
        fadelevel = 2;
        fader = setTimeout(fade,fadespeed);
        break;
      case 2:
        div.className = baseclass + " fade3";
        fadelevel = 3;
        fader = setTimeout(fade,fadespeed);
        break;
      case 3:
        div.className = baseclass;
        break;
    }
  }
  
  var fader = setTimeout(fade,NEW_MESSAGE_HIGHLIGHT_TIME);
  var fadelevel = 0;
  var fadespeed = 100;
}

/******************************************************************************/

Message.Profile = {};


Message.Profile.Block = function(id,username)
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

Message.Profile.UnBlock = function(id,username)
{
  function ret(response)
  {
    DOM.Hide('user_loading');
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

Message.Profile.Subscribe = function(id)
{
  function ret(response)
  {
    if (response=='ok')
    {
      location = "members/profile/"+id+"#manage";
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

Message.Profile.RequestSubscription = function(id)
{
  var request = new Ajax(Message.Profile.SubscriptionRequested);
  var params = "isajax=1";
  var url = "members/request_subscription/"+id;
  request.sendPostRequest(url,params);
  return false;
}

Message.Profile.SubscriptionRequested = function(response)
{
  if (response=='ok')
  {
    DOM.Hide('unfollow_link');
    DOM.Hide('follow_link');
    DOM.Show('cancel_follow_link');
  }
  else alert("Error:\n\n"+response);
}

Message.Profile.CancelRequest = function(id)
{
  var request = new Ajax(Message.Profile.RequestCanceled);
  var params = "isajax=1";
  var url = "members/cancel_subscription_request/"+id;
  request.sendPostRequest(url,params);
  return false;
}

Message.Profile.RequestCanceled = function(response)
{
  if (response=='ok')
  {
    DOM.Hide('unfollow_link');
    DOM.Show('follow_link');
    DOM.Hide('cancel_follow_link');
  }
  else alert("Error:\n\n"+response);
}

Message.Profile.Unsubscribe = function(id,username)
{
  var str = "Are you sure you want to stop following "+username+"?";
  if (confirm(str))
  {
    Message.Profile.UnsubscribeID = id;
    var subscription = new Ajax(Message.Profile.UnsubscribeReturn);
    var url = "members/cancel_subscription/"+id;
    var params = "isajax=1";
    DOM.Show('user_loading');
    subscription.sendPostRequest(url,params);
  }
  return false;
}

Message.Profile.UnsubscribeReturn = function(response)
{
  DOM.Hide('user_loading');
  if (response=="ok")
  {
    location = "members/profile/"+Message.Profile.UnsubscribeID+"#manage/unsubscribe";
  }
  else
  {
    alert("Error:\n"+response);
  }
}

/******************************************************************************/

Message.Group = {};

Message.Group.Join = function(id)
{
  function ret(response)
  {
    if (response=='ok')
    { 
      location = "groups/view/"+id+"#manage";
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
  var url = "groups/join_group/"+id;
  profile.sendPostRequest(url,params);
  return false;
}

Message.Group.RequestMembership = function(id)
{
  function ret(response)
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
  
  var request = new Ajax(ret);
  var params = "isajax=1";
  var url = "groups/request_membership/"+id;
  request.sendPostRequest(url,params);
  return false;
}

Message.Group.Leave = function(id,username)
{
  var str = "Are you sure you want to leave the "+username+" group?";
  if (Message.Group.IsAdmin) str += "\n\nIf you leave, you will also lose your rights as a Group Administrator.";
  if (confirm(str))
  {
    var membership = new Ajax(Message.Group.LeaveReturn);
    Message.Group.LeaveID = id;
    var url = "groups/leave_group/"+id;
    var params = "isajax=1";
    DOM.Show('user_loading');
    membership.sendPostRequest(url,params);
  }
  return false;
}

Message.Group.LeaveReturn = function(response)
{
  DOM.Hide('user_loading');
  if (response=="ok")
  {
    location = "groups/view/"+Message.Group.LeaveID+"#manage/leave";
  }
  else
  {
    alert("Error:\n"+response);
  }
}