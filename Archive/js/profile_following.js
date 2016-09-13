Profile.Following = {};

Profile.Following.Init = function()
{
  DOM.Show('right_col');
  DOM.SetClass('content_div','content_with_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
  if (Profile.CurrentFilter=='') DOM.SetHTML('no_users','When ' + Profile.UserName + ' follows other members, a list of those members will be displayed here.');
  else DOM.SetHTML('no_users',"No matching results");
  Users.Init('members');
}
