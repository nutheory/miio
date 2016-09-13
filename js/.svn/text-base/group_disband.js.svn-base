Group.Disband = {};

Group.Disband.Init = function()
{
  DOM.Hide('right_col');
  DOM.SetClass('content_div','content_no_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('user_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
}

Group.Disband.FormSubmit = function()
{
  var disband = document.getElementById('disband_group_text');
  if (disband)
  {
    if (disband.value.toLowerCase()=="disband")
    {
      if (confirm("Are you sure you want to disband and permanently delete this group?"))
      {
        var group = new Ajax(Group.Disband.Updated);
        var url = "groups/delete_group/"+Group.ID;
        var params = "isajax=1";
        group.sendPostRequest(url,params);
      }
    }
    else alert("Enter 'disband' to disband this group");
  }
  else alert('Unable to read disband confirmation');
  return false;
}

Group.Disband.Updated = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    // redirect to main page
    alert(Group.UserName + " has been disbanded.");
    location.href=HTTP_BASE;
  }
  else
  {
    alert("Error:\n\n"+response);
  }
}