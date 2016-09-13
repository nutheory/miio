var Topic = {};

Topic.Init = function()
{
  DOM.Hide('Posts');
}

Topic.GetPosts = function(word)
{
  var url = "topics/getTopicMessages";
  var params = "isajax=1";
  params += "&filter="+word;
  params += "&istext=1";

  var getpost = new Ajax(Topic.ShowPosts);
  getpost.sendPostRequest(url,params);
  return false;
}

Topic.ShowPosts = function(response)
{
  DOM.SetHTML('trend_posts',response);
  DOM.Show('Posts'); 
}
