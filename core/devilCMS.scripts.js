devilCMS.scripts = {
	cache: new Object(),
	load: function(scripts, callback){
		var scriptText = '';
		if(scripts.length == 0 || !scripts){
			if(typeof(callback) == 'function') callback();
		}else{
			$.each(scripts, function(index, script){
				$.ajax({
					type: "GET",
					url: devilCMS.settings.CMS.URL+'/'+script,
				  	cache: false,
				  	dataType: "text",
				  	error: function(XMLHttpRequest, textStatus, errorThrown){
				  		devilCMS.dialogs.alert(script, XMLHttpRequest.responseText);
				  		devilCMS.scripts.clearCache();
				  		if(typeof(callback) == 'function') callback();
				  		return false;
				  	},
				  	success: function(text){
				  		scriptText = scriptText+text+',';
				  		if(index == scripts.length-1){
				  			if(scriptText.substring(scriptText.length-1, scriptText.length) == ','){
				  				scriptText = scriptText.substring(0, scriptText.length-1);
				  			}
				  			$.globalEval('devilCMS.scripts.cache = { '+scriptText+' }');
				  			if(typeof(devilCMS.scripts.cache.init) != 'undefined' && typeof(devilCMS.scripts.cache.init) == 'function'){
			  					devilCMS.scripts.cache.init(callback);
				  			}else{
				  				if(typeof(callback) == 'function') callback();
				  			}
						}
				  	}
				});
		  	});
		}
	},
	clearCache: function(callback){
		if(typeof(devilCMS.scripts.cache.end) != 'undefined' && typeof(devilCMS.scripts.cache.end) == 'function'){
			devilCMS.scripts.cache.end(function(){
				delete devilCMS.scripts.cache;
  				devilCMS.scripts.cache = new Object;
  				if(typeof(callback) == 'function') callback();
			});
		}else{
  			delete devilCMS.scripts.cache;
  			devilCMS.scripts.cache = new Object;
  			if(typeof(callback) == 'function') callback();
		}
	}
}
