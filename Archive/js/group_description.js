Group.Description = {};

Group.Description.Init = function()
{
  DOM.Show('right_col');
  DOM.SetClass('content_div','content_with_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('user_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
}


Group.Description.DoSearch = function(searchtype,searchval)
{
  alert("Search functionality not yet enabled:\n\n"+searchtype+"->"+searchval);
  return false;
}