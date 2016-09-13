FeaturedAdd = function(order,id)
{
	var featuredAdd = new Ajax(featuredAddResponse);
	var params = "isajax=1";
	params += "&"+order;
	params += "&id="+id;
	var url = "admin/featured_add";
	featuredAdd.sendPostRequest(url,params);
	return false;
}

featuredAddResponse = function(response)
{
	if(response == 'ok')
	{
		$("#featuredlist").load(location.href+" #featuredlist>*");
		$("#queuedlist").load(location.href+" #queuedlist>*");
	}
 	else
	{
		alert("Error: " + response);
	}
}

ReturnToQueue = function(order, id)
{
	$("#queue_"+id).removeAttr('class');
	var featuredRet = new Ajax(returnToQueueResponse);
	var params = "isajax=1";
	params += "&"+order;
	params += "&id="+id;
	var url = "admin/return_queue";

	featuredRet.sendPostRequest(url,params);
	return false;	
}

returnToQueueResponse = function(response)
{
	if(response == 'ok')
	{
		$("#queuedlist").load(location.href+" #queuedlist>*");
		$("#featuredlist").load(location.href+" #featuredlist>*");
	}
 	else
	{
		alert("Error: " + response);
	}
}


resetPaging = function(direction)
{
	var pagingSize = 56;
	thisPage = $(".currentPage").children("a").attr('rel');
	thisTag = $(".currentPage").children("a").attr('rel')-1;
//	thisTag = $("#tagline").attr('name');
	pageCount = $("#paging li").length;
	
	if (thisPage == undefined) {thisPage = 1;}
	//if (thisTag == undefined) {thisTag = thisPage - 1;}

	if (direction == "next"){thisPage++;thisTag++;}
	else if (direction == "prev"){thisPage--;thisTag--;}

	if ($(".page"+thisPage).length > 0)
	{
		thisPage = thisPage;
	}
	else
	{
		thisPage--;	
	}
	if((thisPage <= pageCount) && (thisPage >= 1)){
		$("#paging").empty();
		$("#featuredlist > li").removeAttr('class');
		$("#featuredlist").quickPager({
			pageSize: pagingSize,
			currentPage: thisPage,
			holder: "#paging"
		});
		GetTagline(thisTag);
	}
}

RemoveQueuedFeatured = function(order, id)
{
	var removeItem = new Ajax(removeQueuedFeaturedResponse);
	var params = "isajax=1";
	params += "&"+order;
	params += "&id="+id;
	var url = "admin/remove_queue_featured";

	removeItem.sendPostRequest(url,params);
	return false;
}

removeQueuedFeaturedResponse = function(response)
{
	if(response)
	{
		$("#queue_"+response).fadeOut(1000);
		$("#queuedlist").load(location.href+" #queuedlist>*");
		$("#featuredlist").load(location.href+" #featuredlist>*");
	}
 	else
	{
		alert("Error: Something went wrong, please try again!");
	}
}

RemoveTagline = function(order, id)
{
	var removeTag = new Ajax(removeTaglineResponse);
	var params = "isajax=1";
	params += "&"+order;
	params += "&id="+id;
	var url = "admin/remove_tagline";

	removeTag.sendPostRequest(url,params);
	return false;
}

removeTaglineResponse = function(response)
{
	if(response)
	{
		$("#tagline_"+response).fadeOut(1000);
		$("#tagline_"+response).remove();
		$("#qlist").load(location.href+" #qlist>*");
		$("#taglinelist").load(location.href+" #taglinelist>*");
	}
 	else
	{
		alert("Error: Something went wrong, please try again!");
	}
}

TaglineAdd = function()
{
	if(document.getElementById('taglineInput') != "")
	{
		var newTag = document.getElementById('taglineInput').value;
		var tagAdd = new Ajax(taglineAddResponse);
		var params = "isajax=1";
		params += "&tagline=" + newTag;
		var url = "admin/tagline_add";

		tagAdd.sendPostRequest(url,params);
		return false;
	}
	else
	{
		alert("Please enter a Tagline");
	}
}

taglineAddResponse = function(response)
{
	if(response)
	{
		var tag = response.jsonParse();
		$("#qlist").prepend("<li id='tagline_"+tag[0].id+"' name='"+tag[0].id+"' style='display:none'><div id='pos' class='number'>New</div><p>"+tag[0].tagline+"</p><div class='tag_options'><span class='tag_delete'>Delete</span></div></li>");
		$("#tagline_"+tag[0].id).fadeIn(2000);
	}
 	else
	{
		alert("Error: Something went wrong, please try again!");
	}
}

GetTagline = function(id)
{
	var getTagline = new Ajax(getTaglineResponse);
	var params = "isajax=1";
	params += "&id="+id;
	var url = "admin/get_tagline";

	getTagline.sendPostRequest(url,params);
	return false;
}

getTaglineResponse = function(response)
{
	if(response)
	{
		var tag = response.jsonParse();
		$("#tagline").empty();
		$("#tagline").attr('name', tag[0].page_id);
		$("#tagline").prepend("<p style='display:none'>"+tag[0].tagline+"</p>");
		$("#tagline p").fadeIn(2000);
	} else {

		$("#tagline").empty();
		$("#tagline").removeAttr('name');
		$("#tagline").prepend("<p style='display:none;color:#f00'>No Tagline Assigned</p>");
		$("#tagline p").fadeIn(2000);
	} 
	
}

TaglineOrder = function(order)
{
	var taglineOrder = new Ajax(taglineOrderResponse);
	var params = "isajax=1";
	params += "&"+order;
	var url = "admin/tagline_order";

	taglineOrder.sendPostRequest(url,params);
	return false;
}

taglineOrderResponse = function(response)
{
	if(response == 'ok')
	{
		
	}
 	else
	{
		alert("Error: " + response);
	}
}

bastardize = function(array) {
	var items = array;
	var str = [];
	$(items).each(function() {
		var res = this.match(/(.+)[-=_](.+)/);

		if(res) str.push((this.key || res[1]+'[]')+'='+(this.key && this.expression ? res[1] : res[2]));
	});
	return str.join('&');
}

$(document).ready(function(){ 
	
	var received = false;
	var thisPage = 1;
	var thisTag = 1;
	$("#featuredMenu").hide();
	GetTagline(thisTag);
	
	var featuredHeight = $(window).height() - 400;
	$('#featuredlist').css("height", featuredHeight);
	var taglineHeight = $(window).height() - 60;
	$('#taglines').css("min-height", taglineHeight);
	
	$('.assign_number').live('click',function(){
		var itemId = $(this).parent('div').parent('li').attr('name');
		var itemToMove = $(this).parent('div').parent('li').attr('id');
		var moveTo = $(this).prev('.newPosition').val() -1;
		var featuredList = $("#featuredlist").sortable('toArray');
		var itemToKill = featuredList.indexOf(itemToMove);
		featuredList.splice(moveTo, 0, itemToMove);
		if ($(itemToMove).parent('#queuedlist') == false){
			featuredList.splice(itemToKill, 1);
			var fina = bastardize(featuredList);
			FeaturedAdd(fina,itemId);
		} else {
			var fina = bastardize(featuredList);
			FeaturedAdd(fina,itemId);
		}
	});
	
	$('.remove, .tag_delete, .return').live('click',function(){
		var itemId = $(this).parent('div').parent('li').attr('name');
		var itemToRemove = $(this).parent('div').parent('li').attr('id');
		if ($(this).attr('class') == 'tag_delete'){var featuredList = $("#taglinelist").sortable('toArray');}
		else {var featuredList = $("#featuredlist").sortable('toArray');}
		var itemToKill = featuredList.indexOf(itemToRemove);
		featuredList.splice(itemToKill, 1);
		var order = bastardize(featuredList);
		if ($(this).attr('class') == 'remove'){RemoveQueuedFeatured(order, itemId);}
		else if ($(this).attr('class') == 'tag_delete'){RemoveTagline(order, itemId);}
		else if ($(this).attr('class') == 'return'){ReturnToQueue(order, itemId);}
		else{ alert('oops');}
	});
	
	$('#save_tag').live('click',function(){
		var itemToAdd = $(this).prev('#taglineInput').val();
		TaglineAdd(itemToAdd);
	});
	
	$("#taglinelist, #qlist").sortable({
		connectWith: '.tlists',
		placeholder: 'tag_target_highlight',
		receive: function(event, ui)
		{
			if(ui.sender.attr('id') == 'taglinelist')
			{
			 //see "stop"
			}
			else if(ui.sender.attr('id') == 'qlist')
			{
				tagorder = $("#taglinelist").sortable('serialize');
				TaglineOrder(tagorder);
				
				var i = 1;
				$("#taglinelist li").each(function(){
					$(this).find('div.number').empty();
					$(this).find('div.number').text(i++);
				});
				
				received = true;
			}
			else
			{
				received = false;
			}
		},
		stop: function(event, ui)
		{
			if(received == false && ui.item.parent('ul').attr('id') == 'taglinelist')
			{
				tagorder = $("#taglinelist").sortable('serialize');
				var i = 1; 
				$("#taglinelist li").each(function(){
					$(this).find('div.number').empty();
					$(this).find('div.number').text(i++);
				});
				TaglineOrder(tagorder);
			}
			received = false;
		}
	}).disableSelection();
	
	
	$("#queuedlist, #featuredlist").sortable({
		connectWith: '.flists',
		placeholder: 'target_highlight',
		receive: function(event, ui)
		{	
			if(ui.sender.attr('id') == 'featuredlist')
			{ 
				order = $("#featuredlist").sortable('serialize');
				var id = ui.item.attr('name');
				ReturnToQueue(order, id);
				received = true;
			}
			else if(ui.sender.attr('id') == 'queuedlist')
			{
				order = $("#featuredlist").sortable('serialize');
				var id = ui.item.attr('name');
				FeaturedAdd(order, id);	
				received = true;
			}
			else
			{
				/*resetPaging();*/
				received = false;
			}	
		},
		stop: function(event, ui)
		{
			if(received == false && ui.item.parent('ul').attr('id') == 'featuredlist')
			{
				order = $("#featuredlist").sortable('serialize');
				FeaturedAdd(order);	
			}
			received = false;
		}
	}).disableSelection();
	
	$(".next").click( function(){
		resetPaging('next');
	});
	
	$(".prev").click( function(){
		resetPaging('prev');
	});
	
});