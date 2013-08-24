devilCMS.content = {
	currentModuleID: 0,
	load: function(cid, params){
		if(!params) params = {};
		if(cid == devilCMS.navigation.currentCID) return;
		
		devilCMS.content.currentModuleID = 0;
		devilCMS.navigation.currentCID = cid;
		window.location.href = '#'+cid;
		var showSpinnerHideContent = function(callback){
			$('body').css('cursor', 'progress');
			devilCMS.elements.content.fadeOut(parseInt(devilCMS.settings.CMS.FADING.SPEED), function(){
				devilCMS.elements.spinner.fadeIn(parseInt(devilCMS.settings.CMS.FADING.SPEED), function(){
					if(typeof(callback) == 'function') callback();
				});
			});
		};
		var hideSpinnerShowContent = function(callback){
			$(devilCMS.elements.spinner).fadeOut(parseInt(devilCMS.settings.CMS.FADING.SPEED), function(){
				devilCMS.elements.content.fadeIn(parseInt(devilCMS.settings.CMS.FADING.SPEED), function(){
					$('body').css('cursor', 'auto');
					if(typeof(callback) == 'function') callback();
				});
			});
		};
		devilCMS.elements.container.animate({scrollTop: 0}, devilCMS.elements.container.scrollTop() / 4 , 'linear', function(){
			showSpinnerHideContent(function(){
				devilCMS.scripts.clearCache(function(){
					devilCMS.elements.content.empty();
					$.ajax({
						type: "POST",
				    		url: devilCMS.settings.CMS.URL+'/system.php',
				    		data: params,
					  	cache: false,
					 	dataType: "json",
					  	async: true,
					  	beforeSend: function(jqXHR, settings){
			        			jqXHR.setRequestHeader("DEVILCMS_REQUESTED_TYPE", "json");
			        			jqXHR.setRequestHeader("DEVILCMS_REQUESTED_CID", cid);
			        			jqXHR.setRequestHeader("DEVILCMS_CURRENT_MODULE", devilCMS.content.currentModuleID);
			        			jqXHR.setRequestHeader("DEVILCMS_REQUESTED_FILE", 'core/ajax/getContent.php');
			      			},
			      			error: function(XMLHttpRequest, textStatus, errorThrown){
					  		alert('Error: '+XMLHttpRequest.responseText);
					  	},
					  	success: function(content){
					  		devilCMS.content.currentModuleID = content.moduleID;
					  		devilCMS.elements.content.html(content.html);
				  			devilCMS.scripts.load(content.scripts, function(){
				  				if(content.status == 'noPermission'){
				  					devilCMS.dialogs.alert('Error', 'You have no permission to view this content.');
				  				}
				  				if(content.status == 'noContent'){
				  					devilCMS.dialogs.alert('Error', 'The requested content was not found.');
				  				}
				  				hideSpinnerShowContent();
				  			});
						}
					});
				});
			});
		});
	}
}
