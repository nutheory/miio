var Map = {};
Map.Point = null;
Map.Marker = null;
Map.Map = null;
Map.MapDiv = null;
Map.Posts = [];
Map.PollingTime = 5000;
Map.TopUpdateTime = 5000;
Map.LastUpdateTime = new Date();
Map.Timeout = null;
Map.LastTimestamp = "";
Map.Template = 
    '  <a href="user/profile/%ID%"><img src="%IMAGESOURCE%" height=50 width=50></a>'
  + '  <div class="text">'
  + '    <span class="head"><a href="user/profile/%ID%">%NAME%</a></span><br>'
  + '    <span class="para"><a href="post/view/%POSTID%">%TEXT%</a></span>'
  + '  </div>'
  + '  <div class="clear"></div>';


Map.Init = function()
{
  Map.MapDiv = document.getElementById('google_map');
  if (!Map.MapDiv) return false;
  
  if (GBrowserIsCompatible())
  {
    Map.Map = new google.maps.Map2(Map.MapDiv);
    //Map.Map.setCenter(new google.maps.LatLng(33.27313, -117.22065), 21);
    Map.Map.setCenter(new google.maps.LatLng(33.27313, -117.22065), 3);
    
    Map.Map.addControl(new GLargeMapControl());
  }
  else
  {
    Map.MapDiv.innerHTML = "<i style='color:red'>Unable to load map</i>";
    return false;
  }
  //Map.GetUpdate();
}

Map.CreateMarker = function(point,html)
{
  var marker = new google.maps.Marker(point);
  return marker;
}

Map.GetUpdate = function()
{
  // build request URL
  var uri = HTTP_BASE+'ajax/postupdate';
  uri += '?timestamp='+Map.LastTimestamp;
  // make Ajax request
  Map.Ajax.sendRequest(uri);
}

Map.Update = function(response)
{
  // TODO: Map causes error when response is received after a new page load is initiated (such as logging in)
  var lasttime = "";
  if (response != 'NONE')
  {
    // parse update data
    var post = eval('(' + response + ')');
    Map.LastTimestamp = post.timestamp;
    
    var now = new Date();
    if (now - Map.LastUpdateTime > Map.TopUpdateTime)
    {
      Map.LastUpdateTime = now;
      // update current post
      Map.UpdatePost(post.array[post.array.length-1]);
      
    }
  }
  
  // set next polling cycle
  Map.Timeout = setTimeout(Map.GetUpdate,Map.PollingTime);
}

Map.UpdatePost = function(post)
{
  if (post)
  {
    // remove previous marker
    Map.Map.closeInfoWindow();
    var s = Map.Template;
    s = s.replace(/%POSTID%/,post.id);
    s = s.replace(/%IMAGESOURCE%/,post.image);
    s = s.replace(/%TEXT%/,post.text);
    s = s.replace(/%NAME%/,post.screen_name);
    s = s.replace(/%ID%/g,post.userid);
    var div = document.createElement('div');
    div.className = 'post';
    div.innerHTML = s;
    Map.Point = new google.maps.LatLng(post.lat,post.long);
    Map.Map.openInfoWindowHtml(Map.Point,div);
    // keep, in case we want to add panning
    //Map.Map.panTo(Map.Point);
  }
}


Map.Pause = function()
{
  clearTimeout(Map.Timeout);
}

Map.Resume = function()
{
  if (Map.MapDiv) Map.GetUpdate();
  else
  {
    if (Map.Init()) Map.GetUpdate();
    else alert("Error loading map");
  }
}

Map.Ajax = new Ajax(Map.Update);