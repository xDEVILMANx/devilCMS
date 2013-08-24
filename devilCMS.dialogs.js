devilCMS.dialogs = {
	ajax: function(options){
		var defaults = {
			url: null,
			data: {},
			ajaxComplete: null,
			title: 'Dialog',
			labels: {
				cancel: 'Cancel',
				submit: 'Submit'
			},
			submit: null
		};
		options = $.extend(true, defaults, options);
		
		var div = $('<div />');
		div.dialog({
			title: 'Loading...',
			modal: true,
			create: function(){
				devilCMS.request.html(options.url, options.data, function(html){
					div.dialog('option', 'title', options.title);
					var buttons = div.dialog('option', 'buttons');
					buttons.submit.disabled = false;
					div.dialog('option', 'buttons', buttons);
					div.html(html);
					options.ajaxComplete(div);
				});
			},
			buttons: {
				submit: {
					text: options.labels.submit,
					disabled: true,
					icons: { 
			        	primary: 'ui-icon-check'
					},
			        click: function(){
			        	options.submit(div);
			        }
				},
				cancel: {
					text: options.labels.cancel,
					icons: { 
			        	primary: 'ui-icon-close'
					},
			        click: function(){
						div.dialog('close');
					}
				}
			},
			close: function(){
				div.dialog('destroy');
				div.remove();
			}
		});
	},
	
	alert : function(title, text, callback){
		var div = $('<div />').html(text);
		div.dialog({
			title: title,
			closeOnEscape: true,
			autoOpen: true,
			modal: true,
			height: 'auto',
			width: 400,
			buttons: {
				closeButton: {
					text: "Ok",
					icons: { 
			        	primary: 'ui-icon-check'
					},
			        click: function(){
						div.dialog('close');
					}
	      		}
			},
			close: function(){
				if(typeof(callback) == 'function') callback();
				div.dialog('destroy');
				div.remove();
			}
		});
	},
	
	confirm: function(title, text, callback){
		var div = $('<div />').html(text);
		var result = false;
		var confirmed = false;
		div.dialog({
			title: title,
			closeOnEscape: false,
			autoOpen: true,
			modal: true,
			height: 'auto',
			width: 400,
			buttons: {
				comfirmButton: {
					text: "Yes",
					icons: { 
			        	primary: 'ui-icon-check'
					},
			        click: function(){
			        	confirmed = true;
						div.dialog('close');
					}
				},
				cancelButton: {
					text: "No",
					icons: { 
			        	primary: 'ui-icon-close'
					},
			        click: function(){
			        	confirmed = false;
						div.dialog('close');
					}
				}
			},
			close: function(){
				if(typeof(callback) == 'function') callback(confirmed);
				div.dialog('destroy');
				div.remove();
			}
		});
	}
};
