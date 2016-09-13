var Groups = {};

Groups.Photos = [];
Groups.CurrentView = 'newest';
Groups.Opts = ['newest','popular','list','browse'];

Groups.Init = function()
{alert('unexpected call to Groups.Init in groups.js');
  Groups.Resize();
  window.onresize = Groups.Resize;
  Groups.GetPhotos();
  Groups.current = 1;
  Groups.text = document.getElementById('text');
}

Groups.Resize = function()
{
  var ht = DOM.BrowserHeight();
  var divht = ht-(HEIGHT_ADJ)
  DOM.SetHeight('groups',divht);
  DOM.SetHeight('grouptable',divht-190);
}

Groups.Go = function(where)
{
  if (where=='+') Groups.Next();
  else if (where=='-') Groups.Prev();
  return false;
}

Groups.Next = function()
{
  Groups.current++;
  Groups.GetPhotos();
}

Groups.Prev = function()
{
  Groups.current--;
  Groups.GetPhotos();
}

Groups.Change = function(opt)
{
  for (var o=0;o<Groups.Opts.length;o++)
  {
    if (Groups.Opts[o]==opt)
    {
      DOM.Show('view_'+Groups.Opts[o]+'_sel');
      DOM.Hide('view_'+Groups.Opts[o]);
    }
    else
    {
      DOM.Hide('view_'+Groups.Opts[o]+'_sel');
      DOM.Show('view_'+Groups.Opts[o]);
    }
  }
  Groups.current = 1;
  Groups.CurrentView = opt;
  Groups.GetPhotos();
  return false;
}

Groups.GetPhotos = function()
{
  var photos = new Ajax(Groups.GetResponse);
  var url = HTTP_BASE+"groups/get_groups/"+Groups.CurrentView+"?isajax=1&page="+Groups.current;
  photos.sendRequest(url);
}

Groups.GetResponse = function(response)
{
  DOM.Show('top_text');
  DOM.Hide('list_head');
  switch (Groups.CurrentView)
  {
    case 'newest'  :
    case 'popular' :
      var resp = response.jsonParse();
      Groups.Photos = [];
      var cnt = 1;
      for (var x in resp.photos)
      {
        Groups.Photos[cnt] = x;
        var img = document.getElementById('i_'+cnt);
        if (img) img.src = resp.base + resp.photos[x];
        cnt++;
      }
      DOM.Show('group_photos');
      DOM.Hide('category_list');
      DOM.Hide('group_list');
      break;
    case 'list'    :
      var div = document.getElementById('group_list');
      if (div) div.innerHTML = response;
      DOM.Hide('group_photos');
      DOM.Hide('category_list');
      DOM.Show('group_list');
      Users.Init('groups');
      break;
    case 'browse'  :
      var div = document.getElementById('category_list');
      if (div) div.innerHTML = response;
      DOM.Hide('group_photos');
      DOM.Show('category_list');
      DOM.Hide('group_list');
      break;
  }
}

Groups.GetCategory = function(cat,groupname)
{
  var list = new Ajax(Groups.ShowCategories);
  Groups.Category = cat;
  var url = HTTP_BASE+"groups/category/"+Groups.Category+"?isajax=1&page="+Groups.current;
  var div = document.getElementById('category_name');
  if (div) div.innerHTML = groupname;
  list.sendRequest(url);
  return false;
}

Groups.ShowCategories = function(response)
{
  var div = document.getElementById('group_list');
  if (div) div.innerHTML = response;
  DOM.Hide('top_text');
  DOM.Show('list_head');
  DOM.Hide('group_photos');
  DOM.Hide('category_list');
  DOM.Show('group_list');
  Users.Init('groups');
}

Groups.Paginate = function(listpage,filtervalue,istext)
{
  Groups.CurrentPage = listpage;
  if (filtervalue!=undefined) Groups.CurrentFilter = filtervalue;
  if (istext!=undefined) Groups.FilterIsText = istext;
  Groups.GetPage();
}

Groups.GetPage = function()
{
  if (Groups.CurrentView == 'list')
  {
    var url = HTTP_BASE+"groups/get_groups/"+Groups.CurrentView+"?isajax=1&page="+Groups.CurrentPage;
  }
  else
  {
    var url = HTTP_BASE+"groups/category/"+Groups.Category+"?isajax=1&page="+Groups.CurrentPage;
  }
  Groups.Ajax.sendRequest(url);
}

Groups.GetPageResults = function(response)
{
  var div = document.getElementById('group_list');
  if (div) div.innerHTML = response;
}

Groups.Ajax = new Ajax(Groups.GetPageResults);