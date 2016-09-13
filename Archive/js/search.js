var Search = {};

Search.Options = {
                    "all":"Public Timeline",
                    "text":"Texts",
                    "photo":"Photos",
                    "video":"Videos",
                    "link":"Links",
                    "review":"Reviews",
                    "question":"Questions",
                    "rss":"RSS",
                    "location":"Locations",
                    "group":"Groups",
                    "member":"Members"
                 };

Search.Init = function(val)
{
  Search.Val = "";
  Search.Type = "";
  Search.SelectOpen = false;
  Search.Initial = true;
  Search.CurrentPage = 1;
  if (val!="") Search.Search();
}

Search.Search = function(obj)
{
  if (obj) obj.blur();
  Search.Type = Forms.GetValue('search_type');
  var text = Forms.GetValue('search_value');
  if (text.trim()=='')
  {
    alert("Please enter a word or phrase to search for.");
    return false;
  }
  else
  {
    Search.Val = text.trim();
    Search.GetResults();
  }
  DOM.SetHTML('update_count','0');
  DOM.Hide('update_counter');
  return false;
}

Search.GetResults = function()
{
  var messages = new Ajax(Search.GotResults);
  var url = "search/get_results/"+Search.Type;
  var params = "isajax=1";
  params += "&searchval="+Search.Val;//encodeURIComponent?
  params += "&page="+Search.CurrentPage;
  if (Search.Initial) params += "&initial_load=1";
  DOM.Show('user_loading');
  messages.sendPostRequest(url,params);
}

Search.GotResults = function(response)
{
  DOM.Hide('user_loading');
  DOM.SetHTML('search_results',response);
  DOM.SetHTML('search_phrase',Search.Val);
  DOM.SetHTML('search_where',Search.Options[Search.Type]);
  DOM.Show('search_container');
  if (Search.Type=='member' || Search.Type=='group') Users.Init('search');
  else Messages.Init('search');
}

Search.Paginate = function(listpage)
{
  Search.CurrentPage = listpage;
  Search.GetResults();
}

Search.SelectType = function()
{
  if (Search.SelectOpen)
  {
    // close
    DOM.Hide('search_selectlist');
    Search.SelectOpen = false;
  }
  else
  {
    // open
    DOM.Show('search_selectlist');
    Search.SelectOpen = true;
  }
  return false;
}

Search.SelectTypeOpt = function(opt_type,opt_val)
{
  DOM.SetValue('search_type',opt_type);
  DOM.SetHTML('search_select',opt_val);
  return false;
}