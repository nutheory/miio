Profile.Albums = {};

Profile.Albums.Init = function()
{
  DOM.Hide('right_col');
  DOM.SetClass('content_div','content_no_rcol');
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
      var htm = "<a href='user#settings/albums'>Edit albums</a>" + Profile.MenuOpts.albums;
      head.innerHTML = htm;
    }
  }
}

Profile.Albums.ShowPhoto = function(id)
{
  alert("Show Photo not functional yet");
  return false;
}