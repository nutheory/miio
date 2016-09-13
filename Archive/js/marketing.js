
$(document).ready(function(){
	
	$(".hide_ss").hide();

	$("a.image").fancybox({
		'frameWidth': 90,
		'frameHeight': 90,
		'zoomSpeedIn':	0, 
		'zoomSpeedOut':	0,
		'overlayShow':  true
	});
	
	$("a.video").click(function() {
		$.fancybox({
			'padding'		: 0,
			'autoScale'		: false,
			'overlayShow'   : true,
			'transitionIn'	: 'none',
			'transitionOut'	: 'none',
			'title'			: this.title,
			'width'		    : 920,
			'height'		: 680,
			'href'			: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
			'type'			: 'swf'
		});

		return false;
	});
});