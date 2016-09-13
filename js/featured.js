var Featured = {};

var FEATURED_ITEM_H = 126;
var FEATURED_ITEM_W = 105;
Featured.CurrentPage = 1;


Featured.Init = function(controller)
{
	Featured.Controller = controller;
	Featured.HowMany();
	Featured.GetPage();
}

Featured.GetPage = function()
{
	var url = Featured.Controller + "/featured";
	var params = "isajax=1";
	params += "&page=" + Featured.CurrentPage;
	params += "&per_page=" + Featured.TotalPerPage;
	Featured.Ajax.sendPostRequest(url,params);
}

Featured.GetResults = function(response)
{
  if(response)
  {
	var content = document.getElementById('featured_content');
  	if (content) content.innerHTML = "";
		content.innerHTML = response;
		Featured.HowMany();
  }
  else
  {
		alert(response);
  }
}

Featured.Paginate = function(controller, listpage)
{
	Featured.CurrentPage = listpage;
	Featured.Controller = controller;
	Featured.GetPage();
}

Featured.HowMany = function()
{

	var ht = DOM.BrowserHeight() - HEIGHT_ADJ;
	var headerHeight = 260;
	var footerHeight = 102;
	var ULHeight = ht - (headerHeight - footerHeight);
	var perPage = Math.floor(ULHeight/FEATURED_ITEM_H*8);
	var divided = Math.floor(perPage/8);
	var leftOver = ULHeight - (divided * FEATURED_ITEM_H);
	Featured.TotalPerPage = divided*8;
	var endULHeight = ULHeight - leftOver;
	var taglinePossibleHeight = ht - endULHeight - footerHeight;
	
	if (DOM.GetHeight('tagline') != false){
		var taglineHeight = DOM.GetHeight('tagline');
		var taglineMargin = Math.floor(taglinePossibleHeight - taglineHeight)/2;
		var obj = document.getElementById('tagline');
		if (taglineMargin > 0) {
			obj.style.marginTop = taglineMargin + "px";
			obj.style.marginBottom = taglineMargin + "px";
		}
	}
	
/*	var ht = DOM.BrowserHeight() - HEIGHT_ADJ;
	if (DOM.GetHeight('tagline')) var headerHeight = DOM.GetHeight('tagline');
	if (DOM.GetHeight('featured_header')) var headerHeight = DOM.GetHeight('featured_header') + 40;
	var footerHeight = 0;
	if (DOM.GetHeight('featured_footer')) footerHeight = DOM.GetHeight('featured_footer') + 40;*/
	
	DOM.SetHeight('featuredlist', endULHeight);
}

Featured.Rollover = function(obj,imgsrc,highlight)
{
  if (highlight) obj.src = "images/"+imgsrc+"_mo.png";
  else obj.src = "images/"+imgsrc+".png";
}

Featured.Ajax = new Ajax(Featured.GetResults);