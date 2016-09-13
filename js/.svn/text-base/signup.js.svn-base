var Signup = {};

Signup.Errors = [];

Signup.SubmitForm = function()
{
  DOM.Show('user_loading');
  var signup = new Ajax(Signup.GetResults);
  var url = HTTP_BASE+"signup/submit";
  var params = "isajax=1";
	params += "&twitter_token="+document.getElementById('twitter_token').value;
	params += "&twitter_secret="+document.getElementById('twitter_secret').value;
	params += "&twitter_id="+document.getElementById('twitter_id').value;
	params += "&twitter_sn="+document.getElementById('twitter_sn').value;

  params += "&bio_name="+document.getElementById('bio_name').value.trim();
	params += "&bio_desc="+document.getElementById('bio_desc').value.trim();
	params += "&bio_url="+document.getElementById('bio_url').value.trim();
	
  params += "&twitter_push="+Forms.Ischecked('twitter_push');
	params += "&twitter_reply="+Forms.Ischecked('twitter_reply');
	params += "&twitter_share="+Forms.Ischecked('twitter_share');
  params += "&username="+document.getElementById('username').value.trim();
  params += "&email="+document.getElementById('email').value.trim();
  params += "&confirm_email="+document.getElementById('confirm_email').value.trim();
  params += "&password="+document.getElementById('password').value;
  params += "&password_confirm="+document.getElementById('password_confirm').value;
  params += "&captcha="+document.getElementById('captcha').value;
  signup.sendPostRequest(url,params);
  return false;
}

Signup.ShowErrors = function()
{
  var str = "There are errors in your form. Please check the following and try again:\n\n";
  for (var err in Signup.Errors)
  {
    str += "- " + Signup.Errors[err] + ".\n";
  }
  alert(str);
  Signup.Errors = [];
  Signup.ReloadCaptcha();
}

Signup.GetResults = function(resp)
{
  DOM.Hide('user_loading');
  var response = resp.jsonParse();
  if (response)
  {
    if (response.status=='ok')
    {
      location = "signup/confirm/signup";
    }
    else if (response.errors)
    {
      Signup.Errors = response.errors;
      Signup.ShowErrors();
    }
  }
  else alert("Unknown error:\n\n"+resp);
}

Signup.ReloadCaptcha = function()
{
  var d = new Date();
  var captcha = document.getElementById('captcha_image');
  if (captcha) captcha.src = HTTP_BASE + "securimage/securimage_show.php?x="+d.getTime();
}

Signup.TwitterNameCheck = function(twitName)
{	
  if (twitName)
  {
    if (twitName.match(/^[_a-zA-Z0-9-]{3,20}$/))
    {
      var un = new Ajax(Signup.TwitterNameResponse);
      var url = "ajax/check_name";
      var params = "isajax=1";
      params += "&name="+twitName;
      DOM.Show('user_loading');
      un.sendPostRequest(url,params);
    }
    else
    {
      alert('Twitter username is not a valid Miio username');
    }
  } 
}

Signup.TwitterNameResponse = function(response)
{  
	DOM.Hide('user_loading');
	if (response=='ok')
	{				
		Name_Free('true');
	}
	else
	{
		Name_Free('false');
	}
}

Signup.CheckName = function()
{
  function ret(response)
  {
    DOM.Hide('user_loading');
    if (response=='ok')
    {
  	  DOM.Hide('name_invalid');
  	  DOM.Show('name_valid');
    }
    else
    {
		  DOM.Hide('name_valid');
	  	DOM.Show('name_invalid');
    }
  }
  
  var username = document.getElementById('username');
  if (username)
  {
    if (username.value.trim().match(/^[_a-zA-Z0-9-]{3,20}$/))
    {
      var un = new Ajax(ret);
      var url = "ajax/check_name";
      var params = "isajax=1";
      params += "&name="+username.value.trim();
      DOM.Show('user_loading');
      un.sendPostRequest(url,params);
    }
    else
    {
      alert('Please enter a valid user name: 3-20 characters, use only letters, numbers, period, underscore, or hyphen');
    }
  }
  else alert('Error: Unable to read username');
  return false;
}

/******************************************************************************/

var Confirm = {};

Confirm.Errors = [];

Confirm.AddError = function(message)
{
  Confirm.Errors.push(message);
}

Confirm.ResendCode = function()
{
  var conf = new Ajax(Confirm.CodeResent);
  var url = HTTP_BASE+"signup/resend_confirmation_code";
  var params = "isajax=1";
  conf.sendPostRequest(url,params);
  return false;
}

Confirm.CodeResent = function(response)
{
  if (response=="ok") alert("Your confirmation code has been resent to the email you used when signing up for your account.");
  else alert("Unknown error:\n\n"+response);
}

Confirm.SubmitForm = function(loggedin)
{
  var conf = new Ajax(Confirm.GetResults);
  var url = HTTP_BASE+"signup/confirm_account";
  var params = "isajax=1";
  if (loggedin)
  {
    if (!Confirm.Validate.Code())
    {
      alert('Please enter your confirmation code.');
      return false;
    }
    params += "&confirmation_code="+document.getElementById('confirmation_code').value.trim();
  }
  else
  {
    Confirm.Validate.Username();
    Confirm.Validate.Passwd();
    //Confirm.Validate.Code();
    if (Confirm.Errors.length > 0)
    {
      Confirm.ShowErrors();
      return false;
    }
    params += "&username="+document.getElementById('username').value.trim();
    params += "&password="+document.getElementById('password').value;
    //params += "&confirmation_code="+document.getElementById('confirmation_code').value.trim();
  }
  DOM.Show('user_loading');
  conf.sendPostRequest(url,params);
}

Confirm.ShowErrors = function()
{
  var str = "There are errors in your form. Please check the following and try again:\n\n";
  for (var err in Confirm.Errors)
  {
    str += "- " + Confirm.Errors[err] + ".\n";
  }
  alert(str);
  Confirm.Errors = [];
}

Confirm.GetResults = function(response)
{
  DOM.Hide('user_loading');
  if (response=='ok')
  {
    location = "signup/setup";
  }
  else
  {
    alert(response);
  }
}

Confirm.Validate = {};

Confirm.Validate.Code = function()
{
  var code = document.getElementById('confirmation_code');
  if (code)
  {
    if (code.value.trim().length > 0)
    {
      return true;
    }
    else Confirm.AddError('Please enter your confirmation code');
  }
  else Confirm.AddError('Unable to read confirmation code');
  return false;
}

Confirm.Validate.Username = function()
{
  var username = document.getElementById('username');
  if (username)
  {
    if (username.value.trim().length > 0)
    {
      return true;
    }
    else Confirm.AddError('Please enter your User name');
  }
  else Confirm.AddError('Unable to read username');
  return false;
}

Confirm.Validate.Passwd = function()
{
  var password = document.getElementById('password');
  if (password)
  {
    if (password.value.trim().length > 0)
    {
      return true;
    }
    else Confirm.AddError('Please enter your password');
  }
  else Confirm.AddError('Unable to read password');
  return false;
}
