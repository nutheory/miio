User.Alerts = {};

User.Alerts.Init = function()
{
  DOM.Hide('message_form');
  DOM.Hide('messageform_divider');
  DOM.Hide('message_filters');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('user_filter_container');
  DOM.Hide('people_friends');
  DOM.Hide('people_subscriptions');
  DOM.Hide('people_subscribers');
  DOM.Hide('groups_publicgroups');
  DOM.Hide('groups_privategroups');
}

User.Alerts.CheckContentType = function(check,contenttype,id)
{
  if (id) var t = "_"+id;
  else var t = "";
  var div = document.getElementById('alert_form_contenttype'+t);
  var list = div.getElementsByTagName('input');
  if (contenttype=='all')
  {
    for (var l=0;l<list.length;l++) list[l].checked = check.checked;
  }
  else if (check.checked)
  {
    var allchecked = true;
    for (var l=0;l<list.length;l++)
    {
      if (list[l].id!='alert_all'+t && !list[l].checked) allchecked = false;
    }
    if (allchecked) Forms.Check('alert_all'+t);
  }
  else Forms.Uncheck('alert_all'+t);
}

User.Alerts.Save = function()
{
  var keyword = document.getElementById('alert_keyword');
  if (!keyword)
  {
    alert ("Error: Unable to read keyword");
    return false;
  }
  if (keyword.value.trim()=="")
  {
    alert ("Please enter a keyword");
    return false;
  }
  var alerts = new Ajax(User.Alerts.Saved);
  var url = "user/save_alert";
  var params = "isajax=1";
  params += "&keyword="+keyword.value;
  params += "&dashboard="+Forms.Ischecked('alert_dashboard');
  params += "&email="+Forms.Ischecked('alert_email');
  params += "&sms="+Forms.Ischecked('alert_sms');

  params += "&text="+Forms.Ischecked('alert_text');
  params += "&photo="+Forms.Ischecked('alert_photo');
  params += "&video="+Forms.Ischecked('alert_video');
  params += "&link="+Forms.Ischecked('alert_link');
  params += "&review="+Forms.Ischecked('alert_review');
  params += "&question="+Forms.Ischecked('alert_question');
  params += "&location="+Forms.Ischecked('alert_location');
  params += "&share="+Forms.Ischecked('alert_share');
  params += "&rss="+Forms.Ischecked('alert_rss');

  DOM.Show('user_loading');
  User.Alerts.Ajax.sendPostRequest(url,params);
}

User.Alerts.Saved = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    User.GetPage();
  }
  else
  {
    alert("Error:\n\n"+response);
  }
}

User.Alerts.Pause = function(id,text)
{
  var alerts = new Ajax(User.Alerts.Saved);
  var url = "user/pause_alert";
  var params = "isajax=1";
  params += "&id="+id;
  DOM.Show('user_loading');
  User.Alerts.Ajax.sendPostRequest(url,params);
  return false;
}

User.Alerts.Resume = function(id,text)
{
  var alerts = new Ajax(User.Alerts.Saved);
  var url = "user/resume_alert";
  var params = "isajax=1";
  params += "&id="+id;
  DOM.Show('user_loading');
  User.Alerts.Ajax.sendPostRequest(url,params);
  return false;
}

User.Alerts.Delete = function(id,text)
{
  if (confirm("Really delete alert '"+text+"'?"))
  {
    var alerts = new Ajax(User.Alerts.Saved);
    var url = "user/delete_alert";
    var params = "isajax=1";
    params += "&id="+id;
    DOM.Show('user_loading');
    User.Alerts.Ajax.sendPostRequest(url,params);
  }
  return false;
}

User.Alerts.Edit = function(id,showedit)
{
  if (showedit)
  {
    DOM.Show('edit_'+id);
    DOM.Hide('show_'+id);
  }
  else
  {
    DOM.Show('show_'+id);
    DOM.Hide('edit_'+id);
  }
  return false;
}

User.Alerts.Update = function(id)
{
  var alerts = new Ajax(User.Alerts.Saved);
  var url = "user/update_alert";
  var params = "isajax=1";
  params += "&id="+id;

  params += "&dashboard="+Forms.Ischecked('dashboard_'+id);
  params += "&email="+Forms.Ischecked('email_'+id);
  params += "&sms="+Forms.Ischecked('sms_'+id);

  params += "&text="+Forms.Ischecked('alert_text_'+id);
  params += "&review="+Forms.Ischecked('alert_review_'+id);
  params += "&question="+Forms.Ischecked('alert_question_'+id);
  params += "&link="+Forms.Ischecked('alert_link_'+id);
  params += "&photo="+Forms.Ischecked('alert_photo_'+id);
  params += "&video="+Forms.Ischecked('alert_video_'+id);
  params += "&location="+Forms.Ischecked('alert_location_'+id);
  params += "&share="+Forms.Ischecked('alert_share_'+id);
  params += "&rss="+Forms.Ischecked('alert_rss_'+id);

  DOM.Show('user_loading');
  User.Alerts.Ajax.sendPostRequest(url,params);
}

User.Alerts.ConfirmSMS = function()
{
  User.Navigate('user_settings_mobile');
  return false;
}

User.Alerts.Ajax = new Ajax(User.Alerts.Saved);