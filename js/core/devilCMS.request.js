devilCMS.request = {
	post: function(dataType, file, params, callback){
		if(!params) params = {};
		var response = null;
		$.ajax({
			type: "POST",
		    url: "devilCMS/system.php",
		    data: params,
			cache: false,
			dataType: dataType,
			async: true,
			beforeSend: function(jqXHR, settings){
				jqXHR.setRequestHeader("DEVILCMS_REQUESTED_TYPE", dataType);
				jqXHR.setRequestHeader("DEVILCMS_CURRENT_MODULE", devilCMS.content.currentModuleID);
				jqXHR.setRequestHeader("DEVILCMS_REQUESTED_FILE", file);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				alert('Error: '+XMLHttpRequest.responseText);
			},
			success: function(data){
				response = data;
			},
			complete: function(){
				if(typeof(callback) == 'function') callback(response);
			}
		});
	},
	
	html: function(file, params, callback){
		devilCMS.request.post('html', file, params, callback);
	},
	
	text: function(file, params, callback){
		devilCMS.request.post('text', file, params, callback);
	},
	
	xml: function(file, params, callback){
		devilCMS.request.post('xml', file, params, callback);
	},

	json: function(file, params, callback){
		devilCMS.request.post('json', file, params, callback);
	}
}
