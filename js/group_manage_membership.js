Group.ManageMembership = {};

Group.ManageMembership.Init = function()
{
  if (Group.Sub=='leave')
  {
    DOM.Show('right_col');
    DOM.SetClass('content_div','content_with_rcol');
  }
  else
  {
    DOM.Hide('right_col');
    DOM.SetClass('content_div','content_no_rcol');
  }
  DOM.Hide('message_form');
  DOM.Hide('message_filter_container');
  DOM.Hide('user_filter_container');
  DOM.Hide('show_timeline_filters');
  DOM.Hide('hide_timeline_filters');
  DOM.Hide('message_filters');

  Group.ManageMembership.IsMuted = Forms.Ischecked('preference_mute_on');
  if (!Group.ManageMembership.IsMuted)
  {
    if (DOM.GetValue('dashboard_check')==1) { DOM.Hide('dashboard_sa'); DOM.Show('dashboard_dsa'); }
    if (DOM.GetValue('sms_check')==1) { DOM.Hide('sms_sa'); DOM.Show('sms_dsa'); }
    if (DOM.GetValue('email_check')==1) { DOM.Hide('email_sa'); DOM.Show('email_dsa'); }
  }
  Group.ManageMembership.SMSok = DOM.GetValue('sms_ok');
}

Group.ManageMembership.FormSubmit = function()
{
  var membership = new Ajax(Group.ManageMembership.FormReturn);
  var url = "groups/update_membership/"+Group.ID;
  var params = "isajax=1";
  params += "&mute="+Forms.Ischecked('preference_mute_on');
  var dashboard_reply = 0;
  for (var t in MEMBER_PREFERENCE_TYPES)
  {
    if (MEMBER_PREFERENCE_TYPES[t]!='reply')
    {
      if (Forms.Ischecked('dashboard_'+MEMBER_PREFERENCE_TYPES[t]))
      {
        dashboard_reply = 1;
        params += "&dashboard["+MEMBER_PREFERENCE_TYPES[t]+"]=1";
      }
      else params += "&dashboard["+MEMBER_PREFERENCE_TYPES[t]+"]=0";
    }
    params += "&sms["+MEMBER_PREFERENCE_TYPES[t]+"]="+Forms.Ischecked('sms_'+MEMBER_PREFERENCE_TYPES[t]);
    params += "&email["+MEMBER_PREFERENCE_TYPES[t]+"]="+Forms.Ischecked('email_'+MEMBER_PREFERENCE_TYPES[t]);
  }
  params += "&dashboard_reply="+dashboard_reply;
  params += "&dashboard_admin=1";
  params += "&sms_admin="+Forms.Ischecked('sms_999');
  params += "&email_admin="+Forms.Ischecked('email_999');

  DOM.Show('user_loading');
  membership.sendPostRequest(url,params);
  return false;
}

Group.ManageMembership.FormReturn = function(response)
{
  DOM.Hide('user_loading');
  if (response=="ok")
  {
    alert("Your membership settings have been updated");
    Group.Navigate('group_timeline');
  }
  else
  {
    alert("Error:\n"+response);
  }
}

Group.ManageMembership.Close = function()
{
  Group.Navigate('group_timeline');
  return false;
}

Group.ManageMembership.SelectAll = function(opt,sel)
{
  if (sel)
  {
    for (var t in MEMBER_PREFERENCE_TYPES)
    {
      Forms.Check(opt+'_'+MEMBER_PREFERENCE_TYPES[t]);
    }
    DOM.Hide(opt+'_sa');
    DOM.Show(opt+'_dsa');
    DOM.SetValue(opt+'_check',1);
  }
  else
  {
    for (var t in MEMBER_PREFERENCE_TYPES)
    {
      Forms.Uncheck(opt+'_'+MEMBER_PREFERENCE_TYPES[t]);
    }
    DOM.Hide(opt+'_dsa');
    DOM.Show(opt+'_sa');
    DOM.SetValue(opt+'_check',0);
  }
  return false;
}

Group.ManageMembership.ChangeMute = function(muted)
{
  if (muted)
  {
    DOM.Show('mute_icon');
    DOM.Hide('mute_off_icon');
    for (var t in MEMBER_PREFERENCE_TYPES)
    {
      Forms.Disable('dashboard_'+MEMBER_PREFERENCE_TYPES[t]);
      Forms.Disable('sms_'+MEMBER_PREFERENCE_TYPES[t]);
      Forms.Disable('email_'+MEMBER_PREFERENCE_TYPES[t]);
    }
    DOM.Hide('dashboard_dsa');
    DOM.Hide('sms_dsa');
    DOM.Hide('email_dsa');
    DOM.Hide('dashboard_sa');
    DOM.Hide('sms_sa');
    DOM.Hide('email_sa');
    DOM.SetClass('membership_preference_options','message_options muted');
  }
  else
  {
    DOM.Hide('mute_icon');
    DOM.Show('mute_off_icon');
    for (var t in MEMBER_PREFERENCE_TYPES)
    {
      Forms.Enable('dashboard_'+MEMBER_PREFERENCE_TYPES[t]);
      if (Group.ManageMembership.SMSok==1) Forms.Enable('sms_'+MEMBER_PREFERENCE_TYPES[t]);
      Forms.Enable('email_'+MEMBER_PREFERENCE_TYPES[t]);
    }
    if (DOM.GetValue('dashboard_check')==1) { DOM.Hide('dashboard_sa'); DOM.Show('dashboard_dsa'); }
    else { DOM.Show('dashboard_sa'); DOM.Hide('dashboard_dsa'); }
    if (DOM.GetValue('sms_check')==1) { DOM.Hide('sms_sa'); DOM.Show('sms_dsa'); }
    else { DOM.Show('sms_sa'); DOM.Hide('sms_dsa'); }
    if (DOM.GetValue('email_check')==1) { DOM.Hide('email_sa'); DOM.Show('email_dsa'); }
    else { DOM.Show('email_sa'); DOM.Hide('email_dsa'); }
    DOM.SetClass('membership_preference_options','message_options');
  }
}