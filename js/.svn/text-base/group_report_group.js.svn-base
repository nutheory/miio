Group.ReportGroup = {};

Group.ReportGroup.Init = function()
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

Group.ReportGroup.Count = function(e,obj)
{
  function disallow()
  {
    var st = obj.scrollTop;
    obj.value = obj.value.substr(0,140);
    obj.scrollTop = st;
  }
  
  if (window.event) key = window.event.keyCode;
  else key = e.which;
  
  if (obj.value.length > 140) disallow();
  var counter = document.getElementById('report_count');
  if (counter)
  {
    counter.innerHTML = 140-obj.value.length;
  }
}

Group.ReportGroup.FormSubmit = function()
{
  var subscription = new Ajax(Group.ReportGroup.FormReturn);
  var url = "groups/submit_report/"+Group.ID;
  var params = "isajax=1";
  params += "&spam="+((document.getElementById('report_spam').checked)?'1':'0');
  params += "&abuse="+((document.getElementById('report_abuse').checked)?'1':'0');
  params += "&obscene="+((document.getElementById('report_obscene').checked)?'1':'0');
  params += "&copyright="+((document.getElementById('report_copyright').checked)?'1':'0');
  params += "&hate="+((document.getElementById('report_hate').checked)?'1':'0');
  params += "&other="+((document.getElementById('report_other').checked)?'1':'0');
  params += "&comments="+document.getElementById('report_text').value.trim();
  DOM.Show('user_loading');
  subscription.sendPostRequest(url,params);
  return false;
}

Group.ReportGroup.FormReturn = function(response)
{
  DOM.Hide('user_loading');
  if (response=="ok")
  {
    Group.IsReported = true;
    DOM.Show('report_response');
    DOM.Hide('report_form');
    DOM.Hide('report_link');
    DOM.Hide('report_link_off');
    DOM.Show('reported_text');
  }
  else
  {
    alert("Error:\n"+response);
  }
}
