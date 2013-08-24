devilCMS.elements = {
	init: function(callback){
		var me = this;
			me.container 		= $('#'+devilCMS.settings.ELEMENTS.ID.CONTAINER);
			me.header 			= $('#'+devilCMS.settings.ELEMENTS.ID.HEADER);
			me.navigation.left 	= $('#'+devilCMS.settings.ELEMENTS.ID.NAVIGATION.LEFT);
			me.navigation.right = $('#'+devilCMS.settings.ELEMENTS.ID.NAVIGATION.RIGHT);
			me.navigation.top 	= $('#'+devilCMS.settings.ELEMENTS.ID.NAVIGATION.TOP);
			me.mainframe 		= $('#'+devilCMS.settings.ELEMENTS.ID.MAINFRAME);
			me.content 			= $('#'+devilCMS.settings.ELEMENTS.ID.CONTENT);
			me.footer 			= $('#'+devilCMS.settings.ELEMENTS.ID.NAVIGATION.BOTTOM);
			
			me.spinner = $('<div />')
			.attr('id', 'siteLoadingIndicator')
			.addClass('ui-widge ui-widget-header ui-corner-all')
			.text('Please wait...')
			.appendTo('body');
			
			if(typeof(callback) == 'function') callback();
	},
	container : null,
	header: null,
	navigation: {
		left : null,
		right: null
	},
	mainframe: null,
	content: null,
	footer: null,
	spinner: null
}
