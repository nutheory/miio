var MessageForm = {};

MessageForm.Errors = [];

MessageForm.Types = {};
MessageForm.Types["text"] = { "max_length":140, "header_text":"", "button_text":"Post Message", "text":"Text" };
MessageForm.Types["question"] = { "max_length":140, "header_text":"What is your question?", "button_text":"Ask Question", "text":"Question" };
MessageForm.Types["review"] = { "max_length":140, "header_text":"What would you like to review and what would you like to say?", "button_text":"Post Review", "text":"Review" };
MessageForm.Types["link"] = { "max_length":140, "header_text":"What would you like to say about this link?", "button_text":"Submit Link", "text":"Link" };
MessageForm.Types["photo"] = { "max_length":140, "header_text":"What would you like to say about this photo?", "button_text":"Submit Photo", "text":"Photo" };
MessageForm.Types["video"] = { "max_length":140, "header_text":"What would you like to say about this video?", "button_text":"Submit Video", "text":"Video" };
MessageForm.Types["location"] = { "max_length":140, "header_text":"Where are you?", "button_text":"Submit Location Update", "text":"Location Update" };


MessageForm.Init = function()
{
  document.body.onclick = MessageForm.HideForm;
  DOM.Hide("new_message_wrapper");
  MessageForm.IsExtra = false;
  MessageForm.IsChanging = false;
  MessageForm.FileName = "";
  DOM.Hide('new_message_wrapper');
  if (typeof(Group)!='undefined')
  {
    MessageForm.IsGroup = true;
    MessageForm.Types.text.header_text = "What would you like to say to the " + Group.UserName + " group?";
  }
  else if (typeof(Profile)!='undefined')
  {
    MessageForm.IsProfile = true;
    MessageForm.Types.text.header_text = "What would you like to say to " + Profile.UserName + "?";
  }
  else if (typeof(User)!='undefined')
  {
    MessageForm.IsDashboard = true;
    MessageForm.Types.text.header_text = "What's on your mind right now?";
  }
  var group = document.getElementById('group_distribution_list');
  if (group && group.type!='hidden')
  {
    MessageForm.Group = new AutoFill(GROUPS,true);
    MessageForm.Group.Init("send_to_group","group_distribution_list");
    MessageForm.Group.emptyList = "<div class='highlight'>When you join groups, we will display them here.</div>";
  }
  if (!MessageForm.IsProfile)
  {
    var direct = document.getElementById('direct_distribution_list');
    if (direct)
    {
      MessageForm.Direct = new MultiFill(FOLLOWED);
      MessageForm.Direct.Init("send_to_direct","direct_distribution_list");
    }
    var private = document.getElementById('private_distribution_list');
    if (private)
    {
      MessageForm.Private = new MultiFill(FOLLOWED);
      MessageForm.Private.Init("send_to_private","private_distribution_list");
    }
  }

  Lib.InitLocation(MessageForm,countries,states,cities,'message_');
  Lib.InitLocation(MessageForm,countries,states,cities,'location_');

  MessageForm.LinkURL = "";
  MessageForm.ClearForm();
}

MessageForm.TagCount = function(e,obj)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,TAGLENGTH);
    obj.scrollTop = st;
  }

  if (window.event) key = window.event.keyCode;
  else key = e.which;

  var counter = document.getElementById('tags_count');
  if (counter)
  {
    if (obj.value.length > TAGLENGTH) disallow();
    else counter.innerHTML = TAGLENGTH-obj.value.length;
  }
}

MessageForm.ChangeCountry = function(obj)
{
  Lib.ChangeCountry(MessageForm,obj,'message_');
}

MessageForm.ChangeState = function(obj)
{
  Lib.ChangeState(MessageForm,obj,'message_');
}

MessageForm.ChangeLocationCountry = function(obj)
{
  Lib.ChangeCountry(MessageForm,obj,'location_');
}

MessageForm.ChangeLocationState = function(obj)
{
  Lib.ChangeState(MessageForm,obj,'location_');
}

MessageForm.PostMessage = function(opt)
{
  if (MessageForm.IsChanging) return false;
  if (typeof(User)!='undefined')
  {
    DOM.SetHTML('message_type',MessageForm.Types[opt].text);
    User.Navigate('user_messages_all_'+opt);
  }
  else if (typeof(Profile)!='undefined')
  {
    DOM.SetHTML('message_type',MessageForm.Types[opt].text);
    Profile.Navigate('profile_viewmessages_all_'+opt);
  }
  else if (typeof(Group)!='undefined')
  {
    DOM.SetHTML('message_type',MessageForm.Types[opt].text);
    Group.Navigate('group_viewmessages_messages_'+opt);
  }
  return false;
}

MessageForm.ChangeType = function(opt,justshow)
{
  function closedivs()
  {
    if (justshow)
    {
      for (var n=0;n<toclose.length;n++)
      {
        DOM.Hide(toclose[n]);
      }
      hide();
    }
    else if (toclose.length > 0)
    {
      var nextdiv = toclose.shift();
      var div = document.getElementById(nextdiv);
      if (div && div.style.display == '') DOM.Close(nextdiv,null,closedivs);
      else closedivs();
    }
    else hide();
  }

  function hide()
  {
    for (var n=0;n<tohide.length;n++)
    {
      DOM.Hide(tohide[n]);
    }
    show();
  }

  function show()
  {
    if (Forms.GetValue('message_text')==MessageForm.Types[oldtype].header_text)
    {
      DOM.SetClass('message_text','subdued');
      Forms.SetValue('message_text',MessageForm.Types[MessageForm.MessageType].header_text);
      DOM.SetHTML('message_count',MessageForm.Types[MessageForm.MessageType].max_length);
    }
    for (var n=0;n<toshow.length;n++)
    {
      DOM.Show(toshow[n]);
    }
    MessageForm.IsChanging = false;
    opendivs();
  }

  function opendivs()
  {
    if (justshow)
    {
      for (var n=0;n<toopen.length;n++)
      {
        DOM.Show(toopen[n]);
      }
      DOM.Show('new_message_wrapper');
    }
    else if (toopen.length > 0)
    {
      var nextdiv = toopen.shift();
      var div = document.getElementById(nextdiv);
      if (div && div.style.display != '') DOM.Open(nextdiv,null,opendivs,true);
      else opendivs();
    }
  }

  if (MessageForm.IsChanging || opt==MessageForm.MessageType) return false;
  MessageForm.IsChanging = true;

  var div = document.getElementById('message_type_selector');
  var opts = div.getElementsByTagName('a');
  var optspan = div.getElementsByTagName('h4');
  for (var o=0;o<opts.length;o++) opts[o].className = "";
  DOM.SetClass('mt_'+opt,'selected');
  for (var s=0;s<optspan.length;s++) optspan[s].style.display = "none";
  DOM.Show('mtsp_'+opt);
  var obj = document.getElementById('mt_'+opt);
  if (obj) obj.blur();
  var oldtype = MessageForm.MessageType;
  MessageForm.MessageType = opt;

  // change form settings
  MessageForm.LinkURL = "";
  MessageForm.PhotoIsLink = false;
  MessageForm.LinkType = "";
  MessageForm.FileName = "";
  DOM.SetHTML('extra_file_name',"");
  DOM.SetHTML('file_name',"");
  DOM.SetHTML('category_type',MessageForm.MessageType);
  DOM.SetHTML('tags_type',MessageForm.MessageType);
  DOM.SetHTML('message_type',MessageForm.Types[MessageForm.MessageType].text);
  DOM.SetHTML('advanced_type',MessageForm.Types[MessageForm.MessageType].text.toLowerCase());

  // clear form
  if (MessageForm.IsGroup)
  {
    DOM.SetValue('message_distribution','group');
    DOM.SetValue('group_distribution_list',Group.UserName);
    MessageForm.Distribution = 'group';
  }
  else if (MessageForm.IsProfile)
  {
    DOM.SetValue('message_distribution','direct');
    DOM.SetValue('direct_distribution_list',Profile.UserName);
    DOM.SetValue('private_distribution_list',Profile.UserName);
    MessageForm.Distribution = 'direct';
  }
  else
  {
    DOM.SetValue('message_distribution','public');
    DOM.SetValue('direct_distribution_list','');
    DOM.SetValue('private_distribution_list','');
    DOM.SetValue('group_distribution_list','');
    MessageForm.Distribution = 'public';
  }
  DOM.SetValue('link_url','');
  DOM.SetValue('photo_url','');
  DOM.SetValue('video_url','');
  DOM.SetValue('photo_extra_url','');
  var preview = document.getElementById('attachment_preview_image');
  if (preview) preview.src = "";
  DOM.SetHTML('attachment_preview_div','');
  DOM.Hide('attachment_preview_div');
  DOM.Hide('attachment_preview_image');
  Forms.Check('photo_option_url');
  Forms.Uncheck('photo_option_file');
  var preview2 = document.getElementById('attachment_extra_preview_image');
  if (preview2) preview.src = "";
  DOM.SetHTML('attachment_extra_preview_div','');
  DOM.Hide('attachment_extra_preview_div');
  DOM.Hide('attachment_extra_preview_image');
  Forms.Check('photo_extra_option_url');
  Forms.Uncheck('photo_extra_option_file');
  DOM.SetHTML('tags_count',TAGLENGTH);
  DOM.SetValue('message_tags','');
  DOM.SetValue('message_category','0');
  DOM.Hide('close_advanced'); //Added by D
  DOM.Show('open_advanced'); //Added by D
  DOM.Hide('close_extra');
  DOM.Show('open_extra');
  MessageForm.Extra = false;
  MessageForm.Advanced = false;
  DOM.SetValue('message_location','');
  DOM.SetValue('message_address','');
  DOM.SetValue('message_country',Forms.GetValue('message_country_default'));
  DOM.SetValue('message_state',Forms.GetValue('message_state_default'));
  DOM.SetValue('message_city',Forms.GetValue('message_city_default'));
  DOM.SetValue('location_location','');
  DOM.SetValue('location_address','');
  DOM.SetValue('location_country',Forms.GetValue('location_country_default'));
  DOM.SetValue('location_state',Forms.GetValue('location_state_default'));
  DOM.SetValue('location_city',Forms.GetValue('location_city_default'));
  DOM.SetValue('location_time','');
  DOM.SetValue('location_unit','min');

  switch(oldtype)
  {
    case 'text':
      var toclose = [ 'advanced_options', 'message_forms' ];
      var tohide = [];
      break;
    case 'review':
      var toclose = [ 'advanced_options', 'attachment_extra_preview', 'extra_form', 'message_forms' ];
      var tohide = [ 'extendable_extra', 'form_review' ];
      break;
    case 'question':
      var toclose = [ 'advanced_options', 'attachment_extra_preview', 'extra_form', 'message_forms' ];
      var tohide = [ 'extendable_extra', 'form_question' ];
      break;
    case 'link':
    case 'photo':
    case 'video':
      var toclose = [ 'advanced_options', 'attachment_preview', 'message_forms' ];
      var tohide = [ 'form_'+oldtype ];
      break;
    case 'location':
      var toclose = [ 'advanced_options', 'attachment_extra_preview', 'extra_form', 'message_forms' ];
      var tohide = [ 'extendable_extra', 'form_location' ];
      break;
    default: var toclose = []; var tohide = [];
  }

  var alwayshide = [ 'distribution_public', 'distribution_friends', 'distribution_direct', 'distribution_private', 'distribution_group', 'title_'+oldtype ];
  tohide = tohide.concat(alwayshide);

  switch(opt)
  {
    case 'text':
      var toopen = [ 'message_forms' ];
      var toshow = [ /*'message_text_container',*/ 'extendable_advanced', 'opt_location', 'opt_address', 'opt_country', 'opt_state', 'opt_city', 'message_count' ];
      break;
    case 'review':
    case 'question':
      var toopen = [ 'message_forms' ];
      var toshow = [ /*'message_text_container',*/ 'extendable_advanced', 'extendable_extra', 'opt_location', 'opt_address', 'opt_country', 'opt_state', 'opt_city', 'message_count' ];
      break;
    case 'link':
    case 'photo':
    case 'video':
      var toopen = [ 'message_forms' ];
      var toshow = [ /*'message_text_container',*/ 'extendable_advanced', 'opt_location', 'opt_address', 'opt_country', 'opt_state', 'opt_city', 'message_count' ];
      break;
    case 'location':
      var toopen = [ 'message_forms' ];
      var toshow = [ 'extendable_advanced', 'extendable_extra' ];

      var h = [ /*'message_text_container',*/ 'opt_location', 'opt_address', 'opt_country', 'opt_state', 'opt_city', 'message_count' ];
      tohide = tohide.concat(h);
      break;
  }

  var alwaysshow = [ 'title_'+opt, 'form_'+opt ]
  toshow= toshow.concat(alwaysshow);

  closedivs();

  return false;
}

MessageForm.ChangeDistribution = function(opt)
{
  function opendiv()
  {
    DOM.Open('distribution_'+MessageForm.Distribution);
  }

  var olddist = MessageForm.Distribution;
  MessageForm.Distribution = opt;
  if (opt=='friends' || opt=='private') DOM.Hide('extendable_advanced');
  else DOM.Show('extendable_advanced');
  DOM.Close('distribution_'+olddist,null,opendiv);
}

MessageForm.ChangeDirectDistribution = function(private)
{
  DOM.SetValue('message_distribution',(private?'private':'direct'));
  if (private) DOM.Hide('extendable_advanced');
  else DOM.Show('extendable_advanced');
}

MessageForm.ShowForm = function()
{
  var obj = document.getElementById('message_form');
  if (!obj.isopen)
  {
    obj.isopen = true;
    DOM.Open("new_message_wrapper",null,null,true);

    if (Messages)
    {
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
      Messages.HideAllFilters();
      if (MessageForm.IsProfile)
      {
        Profile.CloseCurrent();
        Profile.Sub = "received";
        Profile.OpenCurrent();
      }

      // get form
      Messages.Get(Messages.MainPage);
    }
  }
}

MessageForm.HideForm = function()
{
  var obj = document.getElementById('message_form');
  if (obj && obj.isopen)
  {
    obj.isopen = false;
    DOM.Close("new_message_wrapper",null,MessageForm.ClearForm);
  }
}

MessageForm.PreserveForm = function(e)
{
  if (!e) var e = window.event;
  e.cancelBubble = true;
  if (e.stopPropagation) e.stopPropagation();
}

MessageForm.TextFocus = function(obj)
{
  if (obj.className=='subdued')
  {
    obj.value = "";
    obj.className = "";
  }
  MessageForm.ShowForm();
}

MessageForm.TextBlur = function(obj)
{
  if (obj.value.trim()=="")
  {
    obj.value = MessageForm.Types[MessageForm.MessageType].header_text;
    obj.className = "subdued";
  }
}

MessageForm.Count = function(e,obj)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,MessageForm.Types[MessageForm.MessageType].max_length);
    obj.scrollTop = st;
  }

  function delayedtest()
  {
    if (obj.value.length > MessageForm.Types[MessageForm.MessageType].max_length) disallow();
    var counter = document.getElementById('message_count');
    if (counter)
    {
      counter.innerHTML = MessageForm.Types[MessageForm.MessageType].max_length-obj.value.length;
    }
  }

  if (e && e.type=='paste')
  {
    setTimeout(delayedtest,100);
  }
  else
  {
    if (window.event) var key = window.event.keyCode;
    else var key = e.which;
    if (key==13)
    {
      obj.blur();
      MessageForm.FormSubmit();
    }
    else
    {
      if (obj.value.length > MessageForm.Types[MessageForm.MessageType].max_length) disallow();
      var counter = document.getElementById('message_count');
      if (counter)
      {
        counter.innerHTML = MessageForm.Types[MessageForm.MessageType].max_length-obj.value.length;
      }
    }
  }
}

MessageForm.ChangePhotoSource = function(which,extra)
{
  if (extra)
  {
    if (which.id=='photo_extra_option_file' || which.id=='photo_extra_file')
    {
      document.getElementById('photo_extra_option_url').checked = false;
      document.getElementById('photo_extra_option_file').checked = true;
      if (which.id=='photo_extra_file') MessageForm.FileName = which.value;
      else MessageForm.FileName = "";
    }
    else
    {
      document.getElementById('photo_extra_option_url').checked = true;
      document.getElementById('photo_extra_option_file').checked = false;
      MessageForm.FileName = "";
    }
    DOM.SetHTML('extra_file_name',MessageForm.FileName);
  }
  else
  {
    if (which.id=='photo_option_file' || which.id=='photo_file')
    {
      document.getElementById('photo_option_url').checked = false;
      document.getElementById('photo_option_file').checked = true;
      if (which.id=='photo_file') MessageForm.FileName = which.value;
      else MessageForm.FileName = "";
    }
    else
    {
      document.getElementById('photo_option_url').checked = true;
      document.getElementById('photo_option_file').checked = false;
      MessageForm.FileName = "";
    }
    DOM.SetHTML('file_name',MessageForm.FileName);
  }
}

MessageForm.OpenAdvanced = function()
{
  DOM.Hide('open_advanced');
  DOM.Show('close_advanced');
  DOM.Open('advanced_options');
  MessageForm.Advanced = true;
  return false;
}

MessageForm.CloseAdvanced = function()
{
  function show()
  {
    DOM.Hide('close_advanced');
    DOM.Show('open_advanced');
  }

  MessageForm.Advanced = false;
  DOM.Close('advanced_options',null,show);
  return false;
}

MessageForm.OpenExtra = function()
{
  DOM.Hide('open_extra');
  DOM.Show('close_extra');
  DOM.Open('extra_form');
  MessageForm.Extra = true;
  return false;
}

MessageForm.CloseExtra = function()
{
  function show()
  {
    DOM.Hide('close_extra');
    DOM.Show('open_extra');
    MessageForm.FileName = "";
    DOM.SetHTML('extra_file_name',MessageForm.FileName);
    Forms.Check('photo_extra_option_url');
    Forms.Uncheck('photo_extra_option_file');
  }

  MessageForm.Extra = false;
  DOM.Close('extra_form',null,show);
  return false;
}

MessageForm.AddError = function(message)
{
  MessageForm.Errors.push(message);
}

MessageForm.ShowErrors = function()
{
  var str = "There are errors in your form. Please check the following and try again:\n\n";
  for (var err in MessageForm.Errors)
  {
    str += "- " + MessageForm.Errors[err] + ".\n";
  }
  alert(str);
  MessageForm.Errors = [];
}

/******************************************************************************/

MessageForm.Preview = function(extra)
{
  var thislink = "";
  var ok = false;
  var url = "";
  MessageForm.IsExtra = false;
  switch (MessageForm.MessageType)
  {
    case 'photo':
      if (document.getElementById('photo_option_file').checked)
      {
        MessageForm.UploadPhoto();
        var skip = true;
      }
      else
      {
        MessageForm.PhotoIsLink = true;
        url = document.getElementById('photo_url').value.trim();
      }
      break;
    case 'review':
    case 'question':
    case 'location':
      MessageForm.IsExtra = true;
      if (document.getElementById('photo_extra_option_file').checked)
      {
        MessageForm.UploadPhoto();
        var skip = true;
      }
      else
      {
        MessageForm.PhotoIsLink = true;
        url = document.getElementById('photo_extra_url').value.trim();
      }
      break;
    case 'link':      url = document.getElementById('link_url').value.trim(); break;
    case 'video':     url = document.getElementById('video_url').value.trim(); break;
  }
  if (!skip) MessageForm.GetURL(url);
  return false;
}

MessageForm.UploadPhoto = function()
{
  if (MessageForm.FileName=="") return;
  DOM.Show('user_loading');
  // upload file
  MessageForm.PhotoIsLink = false;
  if (MessageForm.IsExtra) var photoform = document.getElementById('photo_extra_form');
  else var photoform = document.getElementById('photo_form');
  photoform.submit();
}

MessageForm.ResizePhoto = function(pic)
{
  if (MessageForm.IsExtra) var x = "extra_"; else var x = "";
  DOM.Show(pic.id);
  DOM.Show('attachment_'+x+'preview');
  var w = pic.offsetWidth;
  var h = pic.offsetHeight;
  DOM.Hide('attachment_'+x+'preview');
  if (w>300 || h>300)
  {
    var adj = 1;
    if (w > h)
    {
      adj = 300/w;
    }
    else
    {
      adj = 300/h;
    }
    pic.style.height = (Math.floor(h*adj))+'px';
    pic.style.width = (Math.floor(w*adj))+'px';
  }
}

MessageForm.PhotoReturn = function(isattachment)
{
  function resize_pic()
  {
    MessageForm.ResizePhoto(pic);
    MessageForm.ShowPreview(true);
  }

  if (MessageForm.IsExtra) var x = "extra_"; else var x = "";
  DOM.Hide('user_loading');
  var pic = document.getElementById('attachment_'+x+'preview_image');
  if (pic)
  {
    var d = new Date();
    DOM.Hide(pic.id);
    pic.style.height = "auto";
    pic.style.width = "auto";
    pic.src = HTTP_BASE+"file_temp/"+MessageForm.LinkURL+"?x="+d.getTime();
    pic.onload = resize_pic;
    MessageForm.LinkType = "image";
  }
}

MessageForm.PhotoError = function(error)
{
  DOM.Hide('user_loading');
  if (error=="No File") alert("Please select a photo to upload");
  else alert("Error: "+ error);
}

MessageForm.GetURL = function(linkurl)
{
  if (linkurl != "")
  {
    var httpvalue = 'http://'+linkurl;
    if (httpvalue.isURL()) linkurl = httpvalue;
    if (linkurl.isURL())
    {
      MessageForm.LinkURL = linkurl;
      var ok = true;
    }
    else if (linkurl.indexOf('<embed') >= 0 )
    {
      // check code for proper embed and resize embedded video
      var s = linkurl.substr(linkurl.indexOf('<embed'));
      s = s.substr(0,s.indexOf('>'));
      s = s.replace(/\n/g,' ');
      var urlparams = s.split(' ');
      var params = {}; var embed="";
      for (var p=0;p<urlparams.length;p++)
      {
        urlparams[p] = urlparams[p].trim();
        var parm = urlparams[p].substr(0,urlparams[p].indexOf('=')).toLowerCase();
        switch (parm)
        {
          case 'src':
          case 'type':
          case 'width':
          case 'height':
          case 'flashvars':
          case 'style':
            params[parm] = urlparams[p].substring(urlparams[p].indexOf('=')+2,urlparams[p].length-1);
            break;
        }
      }
      if (!params['width'] && !params['height'] && params['style'])
      {
        var styles = params['style'].split(';');
        for (var i=0;i<styles.length;i++)
        {
          var thisstyle = styles[i].split(':');
          if (thisstyle[0].trim().toLowerCase()=='height') params['height'] = thisstyle[1].replace(/px/,'').trim();
          else if (thisstyle[0].trim().toLowerCase()=='width') params['width'] = thisstyle[1].replace(/px/,'').trim();
        }
      }
      if (params['width'] && (!params['height'] || !params['height'].isNumeric())) params['height'] = params['width'];
      else if ((!params['width'] || !params['width'].isNumeric()) && params['height']) params['width'] = params['height'];
      else if ((!params['width'] || !params['width'].isNumeric()) && (!params['height'] || !params['height'].isNumeric()))
      {
        params['width'] = VIDEO_PREVIEW_WIDTH;
        params['height'] = VIDEO_PREVIEW_HEIGHT;
      }
      var adj = 1;
      if (params['width']>VIDEO_PREVIEW_WIDTH)
      {
        adj = VIDEO_PREVIEW_WIDTH/params['width'];
      }
      if (params['height']*adj>VIDEO_PREVIEW_HEIGHT)
      {
        adj = VIDEO_PREVIEW_HEIGHT/params['height'];
      }

      var embed = "<embed";
      embed += " src='"+params['src']+"'";
      embed += " type='"+params['type']+"'";
      embed += " width='"+Math.floor(adj*params['width'])+"'";
      embed += " height='"+Math.floor(adj*params['height'])+"'";
      if (params['flashvars']) embed += " flashvars='"+params['flashvars']+"'";
      embed += "></embed>";
      MessageForm.LinkURL = embed;
      var ok = true;
    }
  }

  if (ok)
  {
    DOM.Show('user_loading');
    var p = new Ajax(MessageForm.GotURL);
    var url = "ajax/get_filetype";
    var params = "isajax=1";
    params += "&url="+encodeURIComponent(MessageForm.LinkURL);
    p.sendPostRequest(url,params);
  }
  else
  {
    alert ("Please enter a valid photo URL, link, or EMBED text");
  }
}

MessageForm.GotURL = function(response)
{
  function resize_pic()
  {
    DOM.Hide('user_loading');
    MessageForm.ResizePhoto(pic);
    MessageForm.ShowPreview(true);
  }

  if (MessageForm.IsExtra) var x = "extra_"; else var x = "";
  var dest = document.getElementById('attachment_'+x+'preview_div');
  var pic = document.getElementById('attachment_'+x+'preview_image');
  if (dest && pic)
  {
    switch(response)
    {
      case '':
      case 'text':
        pic.src = THUMB+MessageForm.LinkURL;
        pic.onload = resize_pic;
        MessageForm.LinkType = 'url';
        break;
      case 'image':
        var d = new Date();
        DOM.Hide(pic.id);
        pic.src = MessageForm.LinkURL+"?x="+d.getTime();
        pic.onload = resize_pic;
        MessageForm.LinkType = 'image';
        break;
      case 'embed':
        dest.innerHTML = MessageForm.LinkURL;
        MessageForm.LinkType = 'embed';
        MessageForm.ShowPreview();
        break;
      case 'application':
      case 'audio':
      case 'example':
      case 'message':
      case 'model':
      case 'multipart':
      case 'video':
        DOM.Hide('user_loading');
        alert ("Sorry, you have entered a link to an unsupported file type.");
        MessageForm.LinkURL = "";
        MessageForm.PhotoIsLink = false;
        MessageForm.LinkType = "";
        break;
      case 'notfound':
        DOM.Hide('user_loading');
        alert("Error: The link that you entered was not found");
        break;
      default:
        DOM.Hide('user_loading');
        alert ("Please enter a valid photo URL, link, or EMBED text");
    }
  }
  else DOM.Hide('user_loading');
}

MessageForm.ShowPreview = function(image)
{
  function openpreview()
  {
    if (!image) DOM.Show('attachment_'+x+'preview_div');
    DOM.Open('attachment_'+x+'preview',null,showdiv);
    DOM.Hide('attachment_'+x+'preview_div');
  }

  function showdiv()
  {
    DOM.Hide('user_loading');
    if (!image) DOM.Show('attachment_'+x+'preview_div');
    DOM.Show('attachment_'+x+'preview_change');
  }

  if (MessageForm.IsExtra) var x = "extra_"; else var x = "";
  if (image)
  {
    DOM.Hide('attachment_'+x+'preview_div');
    DOM.Show('attachment_'+x+'preview_image');
  }
  else
  {
    DOM.Hide('attachment_'+x+'preview_image');
  }
  if (MessageForm.IsExtra) DOM.Close('extra_form',null,openpreview);
  else DOM.Close('form_'+MessageForm.MessageType,null,openpreview);
}

MessageForm.ChangePreview = function(extra)
{

  function openform()
  {
    DOM.Hide('attachment_'+x+'preview_image');
    if (extra) DOM.Open('extra_form');
    else DOM.Open('form_'+MessageForm.MessageType);
  }

  if (extra) var x = "extra_"; else var x = "";
  DOM.Hide('attachment_'+x+'preview_div');
  DOM.Hide('attachment_'+x+'preview_change');
  DOM.SetValue(MessageForm.MessageType+'_url','');
  DOM.SetValue('photo_extra_url','');
  DOM.Close('attachment_'+x+'preview',null,openform);
  return false;
}

/******************************************************************************/

MessageForm.FormSubmit = function()
{
  // validate
  if (MessageForm.MessageType != 'location') MessageForm.Validate.FormText();
  var form_distro = Forms.GetValue('message_distribution');
  var distro_list = document.getElementById(form_distro+'_distribution_list');
  if (distro_list) var send_to = distro_list.value.trim().replace(/ /g,",");
  else var send_to = '0';
  MessageForm.Validate.Distribution(form_distro,send_to);
  switch (MessageForm.MessageType)
  {
    case 'review':
    case 'question':
      if (MessageForm.Extra && document.getElementById('attachment_extra_preview').style.display=='none') MessageForm.Validate.Attachment();
      break;
    case 'link':
    case 'video':
      MessageForm.Validate.LinkURL();
      break;
    case 'photo':
      MessageForm.Validate.Photo();
      break;
  }

  if (MessageForm.Errors.length>0)
  {
    MessageForm.ShowErrors();
  }
  else
  {
    if (form_distro=='private') var sharing = 'private';
    else if (form_distro=='friends') var sharing = 'friends';
    else var sharing = 'public';
    MessageForm.MessageSharing = sharing;

    var params = "isajax=1";
    params += "&type="+MessageForm.MessageType;
    var text = document.getElementById('message_text').value.trim();
    params += "&text="+encodeURIComponent(text);
    params += "&sendto="+send_to;
    params += "&sharing="+sharing;

    var tags = Forms.GetValue('message_tags');
    var category = Forms.GetValue('message_category');
    if (MessageForm.MessageType=='location')
    {
      var loc = Forms.GetValue('location_location');
      var addr = Forms.GetValue('location_address');
      var country = Forms.GetValue('location_country');
      var state = Forms.GetValue('location_state');
      var city = Forms.GetValue('location_city');
      var howlong = Forms.GetValue('location_time');
      var unit = Forms.GetValue('location_unit');
    }
    else
    {
      var loc = Forms.GetValue('message_location');
      var addr = Forms.GetValue('message_address');
      var country = Forms.GetValue('message_country');
      var state = Forms.GetValue('message_state');
      var city = Forms.GetValue('message_city');
    }
    if (document.getElementById('advanced_options').style.display == "")
    {
      if (tags) params += "&tags="+tags;
      if (category) params += "&category="+category;
      if (loc) params += "&location="+loc;
      if (addr) params += "&address="+addr;
      if (country) params += "&country="+country;
      if (state) params += "&state="+state;
      if (city) params += "&city="+city;
    }

    switch (MessageForm.MessageType)
    {
      case 'question':
      case 'review':
        if (MessageForm.LinkURL!="")
        {
          params += "&link="+encodeURIComponent(MessageForm.LinkURL);
          params += "&linktype="+MessageForm.LinkType;
          params += "&islink=" + ((MessageForm.PhotoIsLink) ? "1" : "0");
        }
        break;
      case 'link':
      case 'video':
        params += "&link="+encodeURIComponent(MessageForm.LinkURL);
        params += "&linktype="+MessageForm.LinkType;
        break;
      case 'photo':
        params += "&link="+encodeURIComponent(MessageForm.LinkURL);
        params += "&linktype="+MessageForm.LinkType;
        params += "&islink=" + ((MessageForm.PhotoIsLink) ? "1" : "0");
        break;
      case 'location':
        if (loc) params += "&location="+loc;
        if (addr) params += "&address="+addr;
        if (country) params += "&country="+country;
        if (state) params += "&state="+state;
        if (city) params += "&city="+city;
        if (howlong) params += "&howlong="+howlong+"&units="+unit;
        if (MessageForm.LinkURL!="")
        {
          params += "&link="+encodeURIComponent(MessageForm.LinkURL);
          params += "&linktype="+MessageForm.LinkType;
          params += "&islink=" + ((MessageForm.PhotoIsLink) ? "1" : "0");
        }
        break;
    }

    message = new Ajax(MessageForm.SubmitReturn);
    var url = "ajax/save_message";
    DOM.Show('user_loading');
    message.sendPostRequest(url,params);
  }

  return false;
}

MessageForm.SubmitReturn = function(response)
{
  DOM.Hide('user_loading');
  if (response.substr(0,2)=='ok')
  {
    DOM.Hide('new_message_wrapper');
    var obj = document.getElementById('message_form');
    if (obj) obj.isopen = false;
    MessageForm.ClearForm();
    var div = document.getElementById('wrapper');
    if (div) div.scrollTop = 0;
    if (Messages)
    {
      if (MessageForm.MessageSharing) Messages.GetUpdates(response.substr(2));
      else Messages.GetUpdates();
    }
  }
  else if (response=='toomany')
  {
    alert('Your message could not be sent because you have exceeded the maximum number of recipients. Please reduce the recipient list to less than 100 names.');
  }
  else
  {
    alert("Error:\n\n"+response);
  }
}

MessageForm.ClearForm = function()
{
  var div = document.getElementById('message_type_selector');
  for (var mt in MessageForm.Types)
  {
    DOM.SetClass('mt_'+mt,"");
    DOM.Hide('title_'+mt);
    DOM.Hide('mtsp_'+mt);
  }
  DOM.SetClass('mt_message','selected');
  DOM.Show('title_message');
  DOM.Show('mtsp_message');
  MessageForm.MessageType = "text";
  MessageForm.LinkURL = "";
  MessageForm.PhotoIsLink = false;
  MessageForm.LinkType = "";

  DOM.SetClass('message_text','subdued');
  DOM.SetValue('message_text',MessageForm.Types[MessageForm.MessageType].header_text);
  DOM.SetHTML('message_count',MessageForm.Types[MessageForm.MessageType].max_length);
  DOM.Show('message_text_container');
  DOM.SetHTML('message_type',MessageForm.Types[MessageForm.MessageType].text);
  if (MessageForm.IsGroup)
  {
    DOM.SetValue('message_distribution','group');
    DOM.SetValue('group_distribution_list',Group.UserName);
    MessageForm.Distribution = 'group';
  }
  else if (MessageForm.IsProfile)
  {
    DOM.SetValue('message_distribution','direct');
    DOM.SetValue('direct_distribution_list',Profile.UserName);
    DOM.SetValue('private_distribution_list',Profile.UserName);
    Forms.Uncheck('messageform_private');
    MessageForm.Distribution = 'direct';
  }
  else
  {
    DOM.SetValue('message_distribution','public');
    DOM.SetValue('direct_distribution_list','');
    DOM.SetValue('private_distribution_list','');
    DOM.SetValue('group_distribution_list','');
    MessageForm.Distribution = 'public';
  }
  DOM.SetValue('link_url','');
  DOM.SetValue('photo_url','');
  DOM.SetValue('video_url','');
  DOM.SetValue('photo_extra_url','');
  DOM.Hide('attachment_preview_image');
  DOM.Hide('attachment_preview_div');
  DOM.Hide('attachment_preview');
  var preview = document.getElementById('attachment_preview_image');
  if (preview) preview.src = "";
  DOM.SetHTML('attachment_preview_div','');
  DOM.Hide('attachment_extra_preview_image');
  DOM.Hide('attachment_extra_preview_div');
  DOM.Hide('attachment_extra_preview');
  var preview2 = document.getElementById('attachment_extra_preview_image');
  if (preview2) preview.src = "";
  DOM.SetHTML('attachment_extra_preview_div','');
  DOM.SetHTML('tags_count',TAGLENGTH);
  DOM.SetValue('message_tags','');
  DOM.SetValue('message_category','0');
  DOM.SetValue('message_location','');
  DOM.SetValue('message_address','');
  DOM.SetValue('message_country',Forms.GetValue('message_country_default'));
  DOM.SetValue('message_state',Forms.GetValue('message_state_default'));
  DOM.SetValue('message_city',Forms.GetValue('message_city_default'));
  DOM.SetValue('location_location','');
  DOM.SetValue('location_address','');
  DOM.SetValue('location_country',Forms.GetValue('location_country_default'));
  DOM.SetValue('location_state',Forms.GetValue('location_state_default'));
  DOM.SetValue('location_city',Forms.GetValue('location_city_default'));
  DOM.SetValue('location_time','');
  DOM.SetValue('location_unit','min');
  DOM.SetHTML('advanced_type',MessageForm.Types[MessageForm.MessageType].text.toLowerCase());
  DOM.Show('open_advanced');
  DOM.Hide('close_advanced');
  DOM.Hide('advanced_options');
  MessageForm.Extra = false;
  MessageForm.Advanced = false;
  DOM.Show('open_extra');
  DOM.Hide('close_extra');
  DOM.Hide('extra_form');
  DOM.Hide('distribution_public');
  DOM.Hide('distribution_friends');
  DOM.Hide('distribution_direct');
  DOM.Hide('distribution_private');
  DOM.Hide('distribution_group');
  //DOM.Hide('form_poll');
  DOM.Hide('form_link');
  DOM.Hide('form_photo');
  DOM.Hide('form_video');
  DOM.Hide('form_location');
  //DOM.Hide('poll_answers');
  DOM.Show('opt_location');
  DOM.Show('opt_address');
  DOM.Show('opt_country');
  DOM.Show('opt_state');
  DOM.Show('opt_city');
  Forms.Check('photo_extra_option_url');
  Forms.Uncheck('photo_extra_option_file');
  Forms.Check('photo_option_url');
  Forms.Uncheck('photo_option_file');
}

MessageForm.ClearLocationInfo = function(obj,opt)
{
  obj.blur();
  DOM.SetValue(opt+'_location','');
  DOM.SetValue(opt+'_address','');
  DOM.SetValue(opt+'_country','');
  DOM.SetValue(opt+'_state','');
  DOM.SetValue(opt+'_city','');
  return false;
}

/******************************************************************************/

MessageForm.Validate = {};

MessageForm.Validate.Errors = {};
MessageForm.Validate.Errors.FormText = {};
MessageForm.Validate.Errors.FormText.text = "Please enter a message";
MessageForm.Validate.Errors.FormText.review = "Please write your review";
MessageForm.Validate.Errors.FormText.question = "Please write your question";
MessageForm.Validate.Errors.FormText.link = "Please write what you would like to say about this link";
MessageForm.Validate.Errors.FormText.photo = "Please write what you would like to say about this photo";
MessageForm.Validate.Errors.FormText.video = "Please write what you would like to say about this video";

MessageForm.Validate.FormText = function()
{
  var formtext = document.getElementById('message_text');
  if (formtext)
  {
    if (formtext.className=='subdued' || formtext.value.trim()=='')
    {
      MessageForm.AddError(MessageForm.Validate.Errors.FormText[MessageForm.MessageType]);
    }
  }
  else MessageForm.AddError("Unable to read form");
}

MessageForm.Validate.LinkURL = function()
{
  if (MessageForm.LinkURL == "")
  {
    if (MessageForm.MessageType=='link')
    {
      MessageForm.AddError("Please enter a link or EMBED code and click 'Preview' to upload and preview your link");
    }
    else
    {
      MessageForm.AddError("Please enter a " + MessageForm.MessageType + " link or EMBED code and click 'Preview' to upload and preview your " + MessageForm.MessageType);
    }
  }
  else if (document.getElementById('attachment_preview').style.display != "")
  {
    MessageForm.AddError("Please click 'Preview' to upload and preview your link");
  }
}

MessageForm.Validate.Photo = function()
{
  if (MessageForm.LinkURL == "")
  {
    MessageForm.AddError("Please enter a photo link or select a file, and click 'Preview' to upload and preview your photo");
  }
  else if (document.getElementById('attachment_preview').style.display != "")
  {
    MessageForm.AddError("Please click 'Preview' to upload and preview your photo");
  }
}

MessageForm.Validate.Attachment = function()
{
  if (MessageForm.LinkURL == "")
  {
    MessageForm.AddError("Enter your photo, photo link, URL or EMBED text and click 'Preview' to upload and preview your photo");
  }
  else if (document.getElementById('attachment_preview').style.display != "")
  {
    MessageForm.AddError("Please click 'Preview' to upload and preview your photo");
  }
}

MessageForm.Validate.Distribution = function(form_distro,send_to)
{
  if (send_to==0)
  {
    if (form_distro == "direct")
    {
      MessageForm.AddError("Please enter a recipient for your message");
    }
    else if (form_distro == "private")
    {
      MessageForm.AddError("Please enter a recipient for your private message");
    }
    else if (form_distro == "group")
    {
      MessageForm.AddError("Please enter a group to send this message to");
    }
  }
}
