Profile.ManageSubscription = {};

Profile.ManageSubscription.Init = function()
{
  DOM.Hide('right_col');
  DOM.SetClass('content_div','content_no_rcol');
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');
  DOM.Hide('user_filter_container');

  Profile.ManageSubscription.IsMuted = Forms.Ischecked('preference_mute_on');
  if (!Profile.ManageSubscription.IsMuted)
  {
    if (DOM.GetValue('dashboard_check')==1) { DOM.Hide('dashboard_sa'); DOM.Show('dashboard_dsa'); }
    if (DOM.GetValue('sms_check')==1) { DOM.Hide('sms_sa'); DOM.Show('sms_dsa'); }
    if (DOM.GetValue('email_check')==1) { DOM.Hide('email_sa'); DOM.Show('email_dsa'); }
  }
  Profile.ManageSubscription.SMSok = DOM.GetValue('sms_ok');
}

Profile.ManageSubscription.FormSubmit = function()
{
  var subscription = new Ajax(Profile.ManageSubscription.FormReturn);
  var url = "members/update_subscription/"+Profile.ID;
  var params = "isajax=1";
  params += "&mute="+Forms.Ischecked('preference_mute_on');
  var dashboard_reply = 0;
  for (var t in PREFERENCE_TYPES)
  {
    if (PREFERENCE_TYPES[t]!='reply')
    {
      if (Forms.Ischecked('dashboard_'+PREFERENCE_TYPES[t]))
      {
        dashboard_reply = 1;
        params += "&dashboard["+PREFERENCE_TYPES[t]+"]=1";
      }
      else params += "&dashboard["+PREFERENCE_TYPES[t]+"]=0";
    }
    params += "&sms["+PREFERENCE_TYPES[t]+"]="+Forms.Ischecked('sms_'+PREFERENCE_TYPES[t]);
    params += "&email["+PREFERENCE_TYPES[t]+"]="+Forms.Ischecked('email_'+PREFERENCE_TYPES[t]);
  }
  params += "&dashboard[102]="+dashboard_reply;
  DOM.Show('user_loading');
  subscription.sendPostRequest(url,params);
  return false;
}

Profile.ManageSubscription.FormReturn = function(response)
{
  DOM.Hide('user_loading');
  if (response=="ok")
  {
    alert("Your follow settings have been updated.");
    Profile.Navigate('profile_timeline');
  }
  else
  {
    alert("Error:\n"+response);
  }
}

Profile.ManageSubscription.Close = function()
{
  Profile.Navigate('profile_timeline');
  return false;
}

Profile.ManageSubscription.SelectAll = function(opt,sel)
{
  if (sel)
  {
    for (var t in PREFERENCE_TYPES)
    {
      Forms.Check(opt+'_'+PREFERENCE_TYPES[t]);
    }
    DOM.Hide(opt+'_sa');
    DOM.Show(opt+'_dsa');
    DOM.SetValue(opt+'_check',1);
  }
  else
  {
    for (var t in PREFERENCE_TYPES)
    {
      Forms.Uncheck(opt+'_'+PREFERENCE_TYPES[t]);
    }
    DOM.Hide(opt+'_dsa');
    DOM.Show(opt+'_sa');
    DOM.SetValue(opt+'_check',0);
  }
  return false;
}

Profile.ManageSubscription.ChangeMute = function(muted)
{
  if (muted)
  {
    DOM.Show('mute_icon');
    DOM.Hide('mute_off_icon');
    for (var t in PREFERENCE_TYPES)
    {
      Forms.Disable('dashboard_'+PREFERENCE_TYPES[t]);
      Forms.Disable('sms_'+PREFERENCE_TYPES[t]);
      Forms.Disable('email_'+PREFERENCE_TYPES[t]);
    }
    DOM.Hide('dashboard_dsa');
    DOM.Hide('sms_dsa');
    DOM.Hide('email_dsa');
    DOM.Hide('dashboard_sa');
    DOM.Hide('sms_sa');
    DOM.Hide('email_sa');
    DOM.SetClass('subscription_preference_options','message_options muted');
  }
  else
  {
    DOM.Hide('mute_icon');
    DOM.Show('mute_off_icon');
    for (var t in PREFERENCE_TYPES)
    {
      Forms.Enable('dashboard_'+PREFERENCE_TYPES[t]);
      if (Profile.ManageSubscription.SMSok==1) Forms.Enable('sms_'+PREFERENCE_TYPES[t]);
      Forms.Enable('email_'+PREFERENCE_TYPES[t]);
    }
    if (DOM.GetValue('dashboard_check')==1) { DOM.Hide('dashboard_sa'); DOM.Show('dashboard_dsa'); }
    else { DOM.Show('dashboard_sa'); DOM.Hide('dashboard_dsa'); }
    if (DOM.GetValue('sms_check')==1) { DOM.Hide('sms_sa'); DOM.Show('sms_dsa'); }
    else { DOM.Show('sms_sa'); DOM.Hide('sms_dsa'); }
    if (DOM.GetValue('email_check')==1) { DOM.Hide('email_sa'); DOM.Show('email_dsa'); }
    else { DOM.Show('email_sa'); DOM.Hide('email_dsa'); }
    DOM.SetClass('subscription_preference_options','message_options');
  }
}