devilCMS.navigation = {
	currentCID: null,
	init: function(){
		$(window).bind('hashchange', function(){
			if(devilCMS.navigation.getCID() != devilCMS.navigation.currentCID){
				devilCMS.content.load(devilCMS.navigation.getCID());
			}
		});
		this.refresh();
		if(!devilCMS.navigation.getCID()){
			devilCMS.request.json('core/ajax/getStartCID.php', null, function(start){
				window.location.href = '#'+start.cid;
			});
		}else{ 
			devilCMS.content.load(devilCMS.navigation.getCID());
		}
	},
	
	refresh: function(){
		/*
		function countem(needle, haystack){
			for(var i in haystack) {
				if (typeof(haystack[i]) == 'object') {
					countem(needle,haystack[i]); 
				}else{
					if (needle == haystack[i]) {
					
					}
				}
			}
		}
		*/
		devilCMS.elements.navigation.right.fadeOut();
		devilCMS.elements.navigation.top.fadeOut();
		devilCMS.elements.navigation.left.fadeOut(function(){
			devilCMS.request.json('core/ajax/getNavigation.php', null, function(navigation){
				devilCMS.elements.navigation.left.empty();
				devilCMS.elements.navigation.right.empty();
				devilCMS.elements.navigation.top.empty();
				
				$.each(navigation, function(index, block){
					if(block.position != 'top'){
						var container = $('<div />').addClass('ui-widget');
						$('<div />').html(block.label).addClass('ui-widget-header ui-corner-top').appendTo(container);
						
						devilCMS.elements.navigation[block.position].append(container);
						
						var last;
						$.each(block.links, function(index, link){
							last = $('<div />').text(link.label).addClass('ui-widget-content ui-state-default').css( 'cursor', 'pointer' ).appendTo(container)
							.mouseover(function(){
								if(!$(this).hasClass('ui-state-active')){
									$(this).switchClass('ui-state-default', 'ui-state-hover');
								}
							})
							.mouseout(function(){
								if(!$(this).hasClass('ui-state-active')){
									$(this).switchClass('ui-state-hover', 'ui-state-default');
								}
							})
							.click(function(){
								$(this).addClass('ui-state-active').removeClass('ui-state-hover ui-state-default');
								devilCMS.elements.navigation.left.find('.ui-state-active').not($(this)).removeClass('ui-state-active').addClass('ui-state-default');
								devilCMS.elements.navigation.right.find('.ui-state-active').not($(this)).removeClass('ui-state-active').addClass('ui-state-default');
								window.location.href = '#'+link.cid;
							});
						});
						last.addClass('ui-corner-bottom');
					}else{
						
						
						var primaryUL = $('<ul />');
						var primaryLI = $('<li />').addClass('ui-widget-header ui-corner-all');
						
						var secondaryUL = $('<ul />');
						
						
						primaryLI.append($('<a />').attr('href', '#').text(block.label));
						
						$.each(block.links, function(index, link){
							var secondaryLI = $('<li />');
							secondaryLI.append($('<a />').attr('href', '#').text(link.label).click(function(){
								window.location.href = '#'+link.cid;
							})).appendTo(secondaryUL);
						});
						
						secondaryUL.appendTo(primaryLI);
						primaryLI.appendTo(primaryUL);
						
						primaryUL.menu({
							position: {my: "left top", at: "left bottom"},
							icons:{
								submenu: 'ui-icon-carat-1-s'
							}
						}).mouseleave(function(){
							$(this).menu("collapseAll");
						});;
						
						primaryUL.appendTo(devilCMS.elements.navigation.top);
						devilCMS.elements.navigation.top.find("a").click(function(event){
							event.preventDefault();
						});
						//devilCMS.elements.navigation.top.append(container);
					}
				});
				
				//alert($('#site_naviright').children().length);
				//alert($('#site_navileft').children().length);
				if($('#site_navileft').children().length == 0 && $('#site_naviright').children().length == 0){
					$('#site_navileft').css('width', '0px');
					$('#site_naviright').css('width', '0px');
					$('#site_mainframe').css('width', '90%');
				}else if($('#site_navileft').children().length == 0){
					$('#site_navileft').css('width', '0px');
					$('#site_naviright').css('width', '15%');
					$('#site_mainframe').css('width', '75%');
				}else if($('#site_naviright').children().length == 0){
					$('#site_navileft').css('width', '15%');
					$('#site_naviright').css('width', '0px');
					$('#site_mainframe').css('width', '75%');
				}else{
					$('#site_navileft').css('width', '15%');
					$('#site_naviright').css('width', '15%');
					$('#site_mainframe').css('width', '60%');
				}
				if($('#site_navitop').children().length > 0){
					devilCMS.elements.navigation.top.fadeIn();
				}
				
				devilCMS.elements.navigation.left.fadeIn();
				devilCMS.elements.navigation.right.fadeIn();
			});
		});
	},
	
	getCID: function(){
		var arr = window.location.href.split('#');
		var num = arr.length;
		if(num > 1){
			return(arr[num-1]);
		}else{
			return false;
		}
	}
}
