var Members = {};

Members.phrases = 
  [
    "Miio is the cool new microblogging and social broadcasting service",
    "Miio is the best place to meet and connect with awesome people",
    "Everything on Miio is live as it happens",
    "You can find and participate in discussions happening right now",
    "Share photos and links",
    /*"Create polls and see what other people think",*/
    "View profiles",
    "Create and share reviews for movies, music, whatever",
    "Form groups",
    "Set alerts and track keywords and tags",
    "Search and get real time results",
    "Discover people by age, gender, ethnicity, location, interests and more",
    "Discover breaking news, topics and trends",
    "Make announcements",
    "See your stats",
    "Broadcast your opinions",
    "Promote yourself and your life",
    "Be seen and heard",
    "Discover new people, places and ideas",
    "Reach everyone with just one text message",
    "Promote your work and expand your network",
    "Free texting works with hundreds of carriers",
    "Know what's cool",
    "Gain attention",
    "Add friends from hundreds of email services and social networks",
    "Start your own news feed about whatever topic you like",
    "Subscribe to news feeds and listen to other points of view",
    "See what's happening right now",
    "Build new friendships, maintain old ones",
    "Get recommendations",
    "Brainstorm and get instant feedback",
    "Express yourself",
    "Participate, interact, respond",
    "Listen to what people are saying about your brand",
    "Learn something new",
    "Text your friends even if you are out of minutes or don't have a phone",
    "Grow your audience",
    "Be recognized",
    "Stay in the loop while on the go",
    "Integrated microblogging toolset",
    "Works with your mobile phone and on the web",
    "Never miss out on oppurtunities ever again",
    "Oh yeah, Miio is free",
    "Only thing missing is You",
    "Be one of the first to <a href='signup'>join</a>"
  ];
  
Members.Photos = [];

Members.Init = function()
{
  Members.Resize();
  window.onresize = Members.Resize;
  Members.GetPhotos();
  Members.current = 0;
  Members.text = document.getElementById('text');
  Members.text.innerHTML = Members.phrases[Members.current];
}

Members.Resize = function()
{
  var ht = DOM.BrowserHeight();
  DOM.SetHeight('members',ht-HEIGHT_ADJ_WITH_SEARCH);
  DOM.SetHeight('membertable',ht-(HEIGHT_ADJ_WITH_SEARCH+52));
}

Members.Go = function(where)
{
  if (where=='+') Members.Next();
  else if (where=='-') Members.Prev();
}

Members.Next = function()
{
  Members.GetPhotos();
  Members.current++;
  if (Members.current >= Members.phrases.length) Members.current = 0;
  Members.text.innerHTML = Members.phrases[Members.current];
}

Members.Prev = function()
{
  Members.GetPhotos();
  Members.current--;
  if (Members.current < 0) Members.current = Members.phrases.length-1;
  Members.text.innerHTML = Members.phrases[Members.current];
}

Members.GetPhotos = function()
{
  var photos = new Ajax(Members.ShowPhotos);
  var url = HTTP_BASE+"members/featured_member_photos?isajax=1";
  photos.sendRequest(url);
}

Members.ShowPhotos = function(response)
{
  var resp = response.jsonParse();
  Members.Photos = [];
  var cnt = 1;
  for (var x in resp.photos)
  {
    Members.Photos[cnt] = x;
    var img = document.getElementById('i_'+cnt);
    if (img) img.src = resp.base + resp.photos[x];
    cnt++;
  }
}