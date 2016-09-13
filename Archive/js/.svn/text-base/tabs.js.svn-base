var Tabs = {};

Tabs.TabOptions = [ "newest", "discussed", "shared", "popular", "featured", "newestg" ];

Tabs.Init = function(tabtype,category)
{
  Tabs.Type = tabtype;
  if (tabtype=='groups') var tab = 'newestg';
  else if (tabtype=='featured') var tab = 'featured';
  else var tab = 'newest';
  Tabs.Category = (category ? category : 0);
  Tabs.Tab = "";
  Tabs.SelectTab(null,tab);
}

Tabs.SelectTab = function(obj,tab)
{
  if (obj) obj.blur();
  if (Tabs.Tab==tab) return false;
  Tabs.Tab = tab;
  for (var t=0;t<Tabs.TabOptions.length;t++)
  {
    var tb = Tabs.TabOptions[t];
    if (tab==tb) DOM.SetClass('tab_'+tb,'active');
    else DOM.SetClass('tab_'+tb,'');
  }
  if (Tabs.Type=='map')
  {
    Map.Init();
  }
  else if (Tabs.Type=='groups')
  {
    Tabs.GetGroupList();
  }
  else
  {
    Tabs.GetMessages();
  }
  return false;
}

Tabs.GetMessages = function()
{
  var messages = new Ajax(Tabs.GotMessages);
  var url = "tabs/get_messages/"+Tabs.Tab;
  var params = "isajax=1";
  params += "&type="+Tabs.Type;
  params += "&category="+Tabs.Category;
  params += "&initial_load=1";
  DOM.Show('user_loading');
  messages.sendPostRequest(url,params);
}

Tabs.GotMessages = function(response)
{
  DOM.Hide('user_loading');
  DOM.SetHTML('tab_messages',response);
  
  Messages.Init('tabs');
}

Tabs.CategoryList = function()
{
  clearTimeout(Messages.Refresh);
  DOM.Hide('category_tabs');
  DOM.Hide('group_tabs');
  DOM.Show('category_list');
  return false;
}

Tabs.GetGroups = function(id)
{
  Tabs.Category = id;
  Tabs.Tab = "";
  Tabs.SelectTab(null,'featured');
}

Tabs.GetGroupList = function()
{
  var groups = new Ajax(Tabs.GotGroups);
  var url = "tabs/get_groups/"+Tabs.Tab;
  var params = "isajax=1";
  //params += "&type="+Tabs.Type;
  params += "&category="+Tabs.Category;
  DOM.Show('user_loading');
  groups.sendPostRequest(url,params);
}

Tabs.GotGroups = function(response)
{
  DOM.Hide('user_loading');
  DOM.SetHTML('tab_groups',response);
  
  Users.Init('tabs');
}

Tabs.PauseUpdates = function()
{
  Messages.PauseUpdates();
}

Tabs.ResumeUpdates = function()
{
  Messages.ResumeUpdates();
}