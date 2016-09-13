Profile.Description = {};

Profile.Description.Init = function()
{
  DOM.Show('right_col');
  DOM.SetClass('content_div','content_with_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
  DOM.Hide('user_filter_container');
  if (Profile.IsMe)
  {
    var head = document.getElementById('profile_header');
    if (head)
    {
      var htm = Profile.MenuOpts.description + "<a href='user#settings/profile'>Edit</a>";
      head.innerHTML = htm;
    }
  }
}

Profile.Description.DoSearch = function(searchtype,searchval)
{
  alert("Search functionality not yet enabled:\n\n"+searchtype+"->"+searchval);
  return false;
}
