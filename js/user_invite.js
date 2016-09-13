User.Invite = {};

User.Invite.Errors = [];

User.Invite.Init = function()
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
  var country = document.getElementById('country');
  if (country)
  {
    User.Invite.Country = new AutoFill(countries,true);
    User.Invite.Country.Init("Country","country");
  }
}

User.Invite.AddError = function(message)
{
  User.Invite.Errors.push(message);
}

User.Invite.ShowErrors = function()
{
  var str = "There are errors in your form. Please check the following and try again:\n\n";
  for (var err in User.Invite.Errors)
  {
    str += "- " + User.Invite.Errors[err] + ".\n";
  }
  alert(str);
  User.Invite.Errors = [];
}

/******************************************************************************/

User.Invite.SendEmailInvite = function()
{
  var emails = document.getElementById('email_list');
  if (emails)
  {
    if (emails.value.trim()=='')
    {
      alert('Please enter an email address');
    }
    else
    {
      var email = new Ajax(User.Invite.EmailInviteSent);
      var url = HTTP_BASE+"user/send_email_invitations";
      var params = "isajax=1";
      params += "&emails="+emails.value;
      DOM.Show('user_loading');
      email.sendPostRequest(url,params);
    }
  }
  return false;
}

User.Invite.EmailInviteSent = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    DOM.Hide('email_form');
    DOM.Show('email_sent');
    User.ResetLeftNav();
  }
  else
  {
    alert("Error:\n\n"+response);
  }
}

User.Invite.EmailMore = function()
{
  DOM.Show('email_form');
  DOM.Hide('email_sent');
  return false;
}

/******************************************************************************/

User.Invite.ChangeSMSCountry = function(obj)
{
  function ret(response)
  {
    var div = document.getElementById('sms_country_code_text');
    if (div) div.innerHTML = response;
  }
  
  var req = new Ajax(ret);
  var url = "ajax/get_sms_code";
  var params = "isajax=1";
  params += "&country="+obj.value;
  req.sendPostRequest(url,params);
}


User.Invite.SendSMSInvite = function()
{
  var sms = document.getElementById('sms_number');
  if (sms)
  {
    if (sms.value.trim()=='')
    {
      alert('Please enter a phone number');
    }
    else
    {
      var ajax = new Ajax(User.Invite.SMSInviteSent);
      var url = HTTP_BASE+"user/send_sms_invitation";
      var params = "isajax=1";
      params += "&sms="+sms.value;
      params += "&country="+document.getElementById('country').value;
      //params += "&carrier="+document.getElementById('sms_provider').value;
      DOM.Show('user_loading');
      ajax.sendPostRequest(url,params);
    }
  }
  return false;
}

User.Invite.SMSInviteSent = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    DOM.Hide('sms_form');
    DOM.Show('sms_sent');
    //document.getElementById('sms_provider').value="none";
    document.getElementById('sms_number').value="";
    User.ResetLeftNav();
  }
  else
  {
    alert("Error:\n\n"+response);
  }
}

User.Invite.SMSMore = function()
{
  DOM.Show('sms_form');
  DOM.Hide('sms_sent');
  return false;
}

/******************************************************************************/

User.Invite.SelectAllMemberContacts = function(chk)
{
  var div = document.getElementById('contactuserlist');
  if (div) var list = div.getElementsByTagName('input');
  if (list)
  {
    for (var l=0;l<list.length;l++)
    {
      if (list[l].id != 'member_selectall') list[l].checked = chk;
    }
  }
  return false;
}

User.Invite.Subscribe = function(btn)
{
  var div = document.getElementById('contactuserlist');
  if (div) var list = div.getElementsByTagName('input');
  if (list)
  {
    var ids = "";
    for (var l=0;l<list.length;l++)
    {
      if (list[l] != btn && list[l].id != 'member_selectall')
      {
        if (list[l].checked) ids += list[l].value + ",";
      }
    }
  }
  // submit ids to subscribe function
  alert("Follow functionality not yet enabled");
  return false;
}

User.Invite.Validate = {};

User.Invite.Validate.Email = function()
{
  var email = document.getElementById('contact_email');
  if (email)
  {
    if (email.value.trim().match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*$/))
    {
      return true;
    }
    else User.Invite.AddError('Please enter a valid email address');
  }
  else User.Invite.AddError('Unable to read email');
  return false;
}

/******************************************************************************/

User.Invite.SMSProviderList = {};
User.Invite.SMSProviderList.none = "";
User.Invite.SMSProviderList['711'] = "7-11 Speakout Wireless";
User.Invite.SMSProviderList.acs = "Alaska Communication Systems";
User.Invite.SMSProviderList.att = "ATT Wireless";
User.Invite.SMSProviderList.attmobility = "ATT Mobility";
User.Invite.SMSProviderList.alltel = "Alltel";
User.Invite.SMSProviderList.bell = "Bell Mobility";
User.Invite.SMSProviderList.boost = "Boost";
User.Invite.SMSProviderList.cellularone = "Celluar One";
User.Invite.SMSProviderList.centennial = "Centennial Wireless";
User.Invite.SMSProviderList.cingular = "Cingular";
User.Invite.SMSProviderList.cricket = "Cricket";
User.Invite.SMSProviderList.dobson = "Dobson";
User.Invite.SMSProviderList.fido = "Fido";
User.Invite.SMSProviderList.gci = "General Communications";
User.Invite.SMSProviderList.globalstar = "Globalstar";
User.Invite.SMSProviderList.helio = "Helio";
User.Invite.SMSProviderList.ivc = "Illinois Valley Cellular";
User.Invite.SMSProviderList.iridium = "Iridium";
User.Invite.SMSProviderList.nextel = "Nextel";
User.Invite.SMSProviderList.metropcs = "Metro PCS";
User.Invite.SMSProviderList.mts = "MTS";
User.Invite.SMSProviderList.presidentschoice = "Presidents Choice";
User.Invite.SMSProviderList.qwest = "Qwest";
User.Invite.SMSProviderList.rogers = "Rogers";
User.Invite.SMSProviderList.sasktel = "Sask Tel";
User.Invite.SMSProviderList.solo = "Solo Mobile (Not supported)";
User.Invite.SMSProviderList.sprint = "Sprint";
User.Invite.SMSProviderList.suncom = "Suncom";
User.Invite.SMSProviderList.tmobile = "T-Mobile - USA";
User.Invite.SMSProviderList.telus = "Telus Mobility";
User.Invite.SMSProviderList.thumb = "Thumb Cellular";
User.Invite.SMSProviderList.unicel = "Unicel";
User.Invite.SMSProviderList.uscellular = "US Cellular";
User.Invite.SMSProviderList.verizon = "Verizon";
User.Invite.SMSProviderList.virginusa = "Virgin Mobile - USA";
User.Invite.SMSProviderList.virginca = "Virgin Mobile - Canada";