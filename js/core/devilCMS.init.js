var devilCMS = new Object();
devilCMS.init = function(){
	devilCMS.prototypes.init();
	devilCMS.settings.init(function(){
		devilCMS.elements.init(function(){
			devilCMS.navigation.init();
		});
	});
}
