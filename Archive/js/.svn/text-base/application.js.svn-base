var HEIGHT_ADJ_WITH_SEARCH = 223;
var HEIGHT_ADJ = 102;
var TOPICLENGTH = 40;
var GROUPLENGTH = 140;
var TAGLENGTH = 140;
var DESCRIPTIONLENGTH = 140;
var REPLY_LENGTH = 140;
var SHARE_LENGTH = 140;
var VIDEO_PREVIEW_WIDTH = 400;
var VIDEO_PREVIEW_HEIGHT =400;
var NEW_MESSAGE_HIGHLIGHT_TIME = 7000; // in milliseconds
var MAX_INLINE_REPLIES = 3;
var UPDATE_INTERVAL = 5000; // in milliseconds
var NOTIFY_INTERVAL = 10000; // in milliseconds
var NOTIFY = null;
var LOTS = 100;
var PREFERENCE_TYPES = { 'text':1,'photo':2,'video':3,'link':4,'review':5,'question':6,'location':7,'rss':8,'share':101,'reply':102 };
var MEMBER_PREFERENCE_TYPES = { 'text':1,'photo':2,'video':3,'link':4,'review':5,'question':6,'location':7,'admin':999 };

/******************************************************************************/

function ImageHighlight(obj,on)
{
  if (on)
  {
    obj.oldclass = obj.className;
    obj.className = obj.oldclass + " imagehighlight";
  }
  else
  {
    obj.className = obj.oldclass;
  }
}

function Init(controller,page)
{
  var loading = document.getElementById('user_loading');
  loading.style.top = ((DOM.BrowserHeight()-36)/2)+"px";
  loading.style.left = ((DOM.BrowserWidth()-206)/2)+"px";

  var err = document.getElementById('error_div');
  var pwr = document.getElementById('password_reset');
  if (err)
  {
    var h = DOM.GetHeight('error_div');
    var ht = DOM.BrowserHeight() - h - 145;
    err.style.marginBottom = ht+"px";
  }
  else if (pwr)
  {
    var h = DOM.GetHeight('password_reset');
    var ht = DOM.BrowserHeight() - h - 165;
    pwr.style.marginBottom = ht+"px";
  }
  else
  {
    switch (controller)
    {
      case "tabs"     : if (page=='groups' || page=='categories')
                        {
                          var div = document.getElementById('category_list');
                          var h=143;
                          if (!div)
                          {
                            var div = document.getElementById('tab_container');
                            h=185;
                          }
                        }
                        else
                        {
                          var div = document.getElementById('tab_container');
                          var h=143;
                        }
                        break;
      case "messages" : var div = document.getElementById('content_div'); var h=175; break;
      case "members"  : if (page=='index') { var div = document.getElementById('featured'); var h=143; }
                        else { var div = document.getElementById('content_div'); var h=195; }
                        break;
      case "search"   : var div = document.getElementById('middle_content'); var h=145; break;
      case "pages"    : var div = document.getElementById('page_content'); var h=163; break;
      case "user"     : if (page=='login' || page=='forgot_login')
                        { var div = document.getElementById('login_div'); var h=B.login_ht_adj; }
                        else { var div = document.getElementById('page_sizer'); var h=145; }
                        break;
      case "signup"   : if (page=='setup') { var div = document.getElementById('setup'); var h=143; }
                        else if (page=='confirm') { var div = document.getElementById('signup'); var h=133; }
                        else { var div = document.getElementById('signup'); var h=123; }
                        break;
      default         : var div = document.getElementById('content_div'); var h=195; break;
    }

    if (div)
    {
      var ht = DOM.BrowserHeight() - h;
      div.style.minHeight = ht+'px';
    }
  }
}

function HeaderSearch()
{
  var url = "search?t="+DOM.GetValue('header_search_type')+"&q="+DOM.GetValue('header_search_text');
  location.href = url;
}

/******************************************************************************/

var Photo = {};

Photo.ExpandedImage = {};

Photo.Expand = function(image,ht,wd)
{
  var maxht = DOM.BrowserHeight()-66;
  var maxwd = DOM.BrowserWidth()-66;
  var wrapper = document.getElementById('wrapper');
  var scrolladj = wrapper.scrollTop;
  if (ht>maxht)
  {
    var ratio = maxht/ht;
    if (wd*ratio > maxwd) ratio = maxwd/wd;
    Photo.ExpandedImage.FinalHeight = Math.floor(ht*ratio);
    Photo.ExpandedImage.FinalWidth = Math.floor(wd*ratio);
  }
  else
  {
    Photo.ExpandedImage.FinalHeight = ht;
    Photo.ExpandedImage.FinalWidth = wd;
  }
  Photo.ExpandedImage.StartHeight = 0;
  Photo.ExpandedImage.StartWidth = 0;
  Photo.ExpandedImage.CurrentHeight = 0;
  Photo.ExpandedImage.CurrentWidth = 0;
  Photo.ExpandedImage.FinalCenterX = (Math.floor(DOM.BrowserWidth()/2) + DOM.ScrollX());
  Photo.ExpandedImage.FinalCenterY = (Math.floor(DOM.BrowserHeight()/2) + DOM.ScrollY());
  Photo.ExpandedImage.StartCenterX = Photo.ExpandedImage.CurrentCenterX = DOM.PosX(image) + (Math.floor(image.width/2));
  Photo.ExpandedImage.StartCenterY = Photo.ExpandedImage.CurrentCenterY = DOM.PosY(image) + Math.floor(image.height/2) - scrolladj;
  Photo.ExpandedImage.DeltaHeight = Photo.ExpandedImage.FinalHeight / 10;
  Photo.ExpandedImage.DeltaWidth = Photo.ExpandedImage.FinalWidth / 10;
  Photo.ExpandedImage.DeltaX = (Photo.ExpandedImage.FinalCenterX-Photo.ExpandedImage.StartCenterX) / 10;
  Photo.ExpandedImage.DeltaY = (Photo.ExpandedImage.FinalCenterY-Photo.ExpandedImage.StartCenterY) / 10;

  Photo.ExpandedImage.Container = document.createElement('div');
  Photo.ExpandedImage.Container.id = "photocontainer";
  Photo.ExpandedImage.Container.style.top = (Photo.ExpandedImage.StartCenterY-20) + 'px';
  Photo.ExpandedImage.Container.style.left = (Photo.ExpandedImage.StartCenterX-20) + 'px';

  var block = document.createElement('div');
  //alert(document.body.clientHeight+'\n'+document.body.innerHeight+'\n'+document.body.scrollHeight);
  block.style.height = document.body.clientHeight + 'px';
  //block.style.height = DOM.BrowserHeight() + 'px';
  block.style.width = (DOM.BrowserWidth()-17) + 'px';
  block.id = 'photoblocker';
  document.body.appendChild(block);

  var htm = "<img class='close' src='images/close.gif' title='close' alt='X' onclick='Photo.Collapse()'>";
  htm += "<img src='"+image.src+"' id='expandedphoto' onclick='Photo.Collapse()' height=0 width=0>";
  Photo.ExpandedImage.Container.innerHTML = htm;
  document.body.appendChild(Photo.ExpandedImage.Container);
  Photo.ExpandedImage.ImageElement = document.getElementById('expandedphoto');

  setTimeout(Photo.ExpandImage,10);

}

Photo.ExpandImage = function()
{
  Photo.ExpandedImage.CurrentHeight += Photo.ExpandedImage.DeltaHeight;
  Photo.ExpandedImage.CurrentWidth += Photo.ExpandedImage.DeltaWidth;

  Photo.ExpandedImage.ImageElement.height = Photo.ExpandedImage.CurrentHeight;
  Photo.ExpandedImage.ImageElement.width = Photo.ExpandedImage.CurrentWidth;

  Photo.ExpandedImage.CurrentCenterY += Photo.ExpandedImage.DeltaY;
  Photo.ExpandedImage.CurrentCenterX += Photo.ExpandedImage.DeltaX;

  if (Photo.ExpandedImage.CurrentHeight >= Photo.ExpandedImage.FinalHeight)
  {
    Photo.ExpandedImage.CurrentHeight = Photo.ExpandedImage.FinalHeight;
    Photo.ExpandedImage.CurrentWidth = Photo.ExpandedImage.FinalWidth;
    var done = true;
  }

  Photo.ExpandedImage.Container.style.top = (Photo.ExpandedImage.CurrentCenterY - Math.floor(Photo.ExpandedImage.CurrentHeight/2) - 20) + 'px';
  Photo.ExpandedImage.Container.style.left = (Photo.ExpandedImage.CurrentCenterX - Math.floor(Photo.ExpandedImage.CurrentWidth/2) - 20) + 'px';

  if (!done) setTimeout(Photo.ExpandImage,10);
}

Photo.Collapse = function()
{
  setTimeout(Photo.CollapseImage,10);
}

Photo.CollapseImage = function()
{
  Photo.ExpandedImage.CurrentHeight -= Photo.ExpandedImage.DeltaHeight;
  Photo.ExpandedImage.CurrentWidth -= Photo.ExpandedImage.DeltaWidth;

  Photo.ExpandedImage.ImageElement.height = Photo.ExpandedImage.CurrentHeight;
  Photo.ExpandedImage.ImageElement.width = Photo.ExpandedImage.CurrentWidth;

  Photo.ExpandedImage.CurrentCenterY -= Photo.ExpandedImage.DeltaY;
  Photo.ExpandedImage.CurrentCenterX -= Photo.ExpandedImage.DeltaX;

  if (Photo.ExpandedImage.CurrentHeight <= 0)
  {
    Photo.ExpandedImage.CurrentHeight = 0;
    Photo.ExpandedImage.CurrentWidth = 0;
    var done = true;
  }

  Photo.ExpandedImage.Container.style.top = (Photo.ExpandedImage.CurrentCenterY - Math.floor(Photo.ExpandedImage.CurrentHeight/2) - 20) + 'px';
  Photo.ExpandedImage.Container.style.left = (Photo.ExpandedImage.CurrentCenterX - Math.floor(Photo.ExpandedImage.CurrentWidth/2) - 20) + 'px';

  if (!done) setTimeout(Photo.CollapseImage,10);
  else Photo.CollapseDone();
}

Photo.CollapseDone = function()
{
  var block = document.getElementById('photoblocker');
  document.body.removeChild(block);
  document.body.removeChild(Photo.ExpandedImage.Container);
  Photo.ExpandedImage = {};
}

/******************************************************************************/
/*
var Notify = {};

Notify.GetUpdate = function()
{
  var update = new Ajax(Notify.GotUpdate);
  var url = "ajax/notify";
  update.sendRequest(url);
}

Notify.GotUpdate = function(response)
{
  if (response=='badid')
  {
    alert("Error: Invalid User ID");
  }
  else
  {
    if (response != 'null')
    {
      var update = response.jsonParse();
      if (update.length>0)
      {
        var html = "";
        for (var x in update) html += update[x];
        DOM.SetHTML('popup_text',html);
        Notify.OpenUpdate();
      }
    }
    NOTIFY = setTimeout(Notify.GetUpdate,NOTIFY_INTERVAL);
  }
}

Notify.OpenUpdate = function()
{
  function expand()
  {
    Notify.UpdateHeight += 3;
    DOM.SetHeight('popup_notifier',Notify.UpdateHeight);
    if (Notify.UpdateHeight < 90) setTimeout(expand,0);
    else
    {
      Notify.UpdateIsOpen = true;
      setTimeout(Notify.CloseUpdate,4000);
    }
  }

  if (Notify.UpdateIsOpen)
  {
    Notify.CloseUpdate(expand);
  }
  else
  {
    Notify.UpdateHeight = 0;
    setTimeout(expand,0);
  }
}

Notify.CloseUpdate = function(donext)
{
  function shrink()
  {
    Notify.UpdateHeight -= 3;
    DOM.SetHeight('popup_notifier',Notify.UpdateHeight);
    if (Notify.UpdateHeight > 0) setTimeout(shrink,0);
    else
    {
      Notify.UpdateIsOpen = false;
      if (typeof(donext)=='function') donext();
    }
  }

  if (Notify.UpdateIsOpen)
  {
    Notify.UpdateHeight = 90;
    setTimeout(shrink,0);
  }
}
*/
//-----------------------------------------------------------------------

var SearchHeader = {};

SearchHeader.Dropdown = function()
{
  if (document.getElementById('header_search_menu').style.display=='none'){
    DOM.Show('header_search_menu');
    DOM.Hide('search_down_link');
    DOM.Show('search_up_link');
  }
  else
  {
    DOM.Hide('header_search_menu');
    DOM.Hide('search_up_link');
    DOM.Show('search_down_link');
  }
  return false;
}

/******************************************************************************/

var Lib = {};

Lib.InitLocation = function(Obj,countries,states,cities,prefix)
{
  if (!isset(prefix)) var prefix='';
  var country = document.getElementById(prefix+'country');
  if (country)
  {
    if (!isset(countries)) var countries = [];
    var Country = new AutoFill(countries,true);
    Country.Init(prefix.ucFirst()+"Country",prefix+"country");
    Country.suggestOnBlank = false;
    Country.allowBlank = true;
    Obj[prefix.ucFirst()+'Country'] = Country;
  }

  if (country.value.trim()!="")
  {
    Lib.ChangeCountry(Obj,country,prefix,true);
  }
  else
  {
    var state = document.getElementById(prefix+'state');
    if (state)
    {
      if (!isset(states)) var states = [];
      var State = new AutoFill(states,true);
      State.Init(prefix.ucFirst()+"State",prefix+"state");
      State.suggestOnBlank = false;
      State.allowBlank = true;
      Obj[prefix.ucFirst()+'State'] = State;
    }

    var city = document.getElementById(prefix+'city');
    if (city)
    {
      if (!isset(cities)) var cities = [];
      var City = new AutoFill(cities,true);
      City.Init(prefix.ucFirst()+"City",prefix+"city");
      City.suggestOnBlank = false;
      City.allowBlank = true;
      Obj[prefix.ucFirst()+'City'] = City;
    }
  }
}

Lib.ChangeCountry = function(Obj,obj,prefix,init)
{
  // loads states
  function ret(response)
  {
    DOM.Hide('user_loading');
    var states = response.jsonParse();
    if (states)
    {
      var State = new AutoFill(states,true);
      State.Init(prefix.ucFirst()+"State",prefix+"state");
      State.suggestOnBlank = false;
      State.allowBlank = true;
      Obj[prefix.ucFirst()+'State'] = State;
      if (init)
      {
        var state = document.getElementById('state');
        if (state && state.value.trim != '') Lib.ChangeState(Obj,state,prefix,true);
      }
    }
  }

  if (!isset(prefix)) var prefix='';
  if (!isset(init))
  {
    Forms.SetValue(prefix+'state',"");
    Forms.SetValue(prefix+'city',"");
  }
  if (obj.value.trim()=="")
  {
    ret('{}');
    Lib.ChangeState(Obj,null,prefix);
  }
  else
  {
    var list = new Ajax(ret);
    var url = "ajax/get_states";
    var params = "isajax=1";
    params += "&country="+obj.value;
    if (!isset(init)) DOM.Show('user_loading');
    list.sendPostRequest(url,params);
  }
}

Lib.ChangeState = function(Obj,obj,prefix,init)
{
  // loads cities
  function ret(response)
  {
    DOM.Hide('user_loading');
    var cities = response.jsonParse();
    if (cities)
    {
      var City = new AutoFill(cities,true);
      City.Init(prefix.ucFirst()+"City",prefix+"city");
      City.suggestOnBlank = false;
      City.allowBlank = true;
      Obj[prefix.ucFirst()+'City'] = City;
    }
  }

  if (!isset(prefix)) var prefix='';
  if (!isset(init)) Forms.SetValue(prefix+'city',"");
  if (!obj || obj.value.trim()=="") ret('{}');
  else
  {
    var list = new Ajax(ret);
    var url = "ajax/get_cities";
    var params = "isajax=1";
    params += "&country="+Forms.GetValue(prefix+'country');
    params += "&state="+obj.value.trim();
    if (!isset(init)) DOM.Show('user_loading');
    list.sendPostRequest(url,params);
  }
}
