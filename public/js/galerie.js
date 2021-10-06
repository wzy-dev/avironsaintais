$(document).ready(function(){
	var getUrlParameter = function getUrlParameter(sParam) {
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};

	var nbImgPerLineDesktop=5,
	nbImgPerLinePhone=2,
	nbImg,
	images=[],
	line;

	if($(window).width()<992){
		nbImgPerLine=nbImgPerLinePhone;
	} else {
		nbImgPerLine=nbImgPerLineDesktop;
	}

	function sizeGalerie() {
		for (var i=0;i<line;i++) {	
			
			var nbImgPerLineLast=nbImgPerLine;
			if(i==line-1 && (images.length-1)%nbImgPerLine!=0) {
				nbImgPerLineLast=(images.length-1)%nbImgPerLine;
			}

			var sLargeBe=-10,
			sLargeAf=-10;	

			for (var iLarge=1;iLarge<=nbImgPerLine;iLarge++)
			{
				if(i==line-1 && (images.length-1)%nbImgPerLine!=0) {
					sLargeBe=parseInt(sLargeBe+images[nbImgPerLineLast+nbImgPerLine*(line-1)].width/images[nbImgPerLineLast+nbImgPerLine*(line-1)].height+10);
					
					sLargeAf=parseInt(sLargeAf+images[nbImgPerLineLast+nbImgPerLine*(line-1)].width*501/images[nbImgPerLineLast+nbImgPerLine*(line-1)].height+10);
				} else {
					sLargeBe=parseInt(sLargeBe+images[iLarge+nbImgPerLine*i].width/images[iLarge+nbImgPerLine*i].height+10);
					
					sLargeAf=parseInt(sLargeAf+images[iLarge+nbImgPerLine*i].width*501/images[iLarge+nbImgPerLine*i].height+10);
				}
			}

			var large=parseInt($('#galerie').width());

			dif=large-sLargeAf;
			if($('.galerie_large:nth-child('+(i+1)+') img').length==0){
				$('#galerie').append('<div class="galerie_large">');
				for (var iN=i*nbImgPerLine+1; iN<=i*nbImgPerLine+nbImgPerLineLast; iN++) {
					$('.galerie_large:nth-child('+(i+1)+')').append('<img style="cursor:pointer" height="'+(501+dif*500/(sLargeAf-sLargeBe))+'" alt="'+images[iN].alt+'" src="'+images[iN].src+'" data-full_src="'+images[iN].full_src+'"/>');
				}
				$('#galerie').append('</div>');

				$('.galerie_large:nth-child('+(i+1)+') img').click(function(){
					$('body').prepend('<div id="popup" class="Popup" style="height:'+$(window).height()+'px;width:'+$(window).width()+'px;"><span id="close_popup" class="Popup-close icon-cross"></span><div><img class="Popup-img" src="'+$(this).data('full_src')+'"/></div></div>');	

					$('#popup').click(function(e){
						if (!$(e.target).hasClass('Popup-img')) {
							$('#popup').remove();
						}
					});
					$(document).keydown(function(event) {
						if(event.which==27){
							$('#popup').remove();
						}
					});
					$( window ).resize(function(){
						$('#popup').css('height',$(window).height()+'px');
						$('#popup').css('width',$(window).width()+'px');
					});
				});				
			}
			if($('.galerie_large:nth-child('+(i+1)+') img').length===nbImgPerLine) {
				$('.galerie_large:nth-child('+(i+1)+')').css('height',(501+dif*500/(sLargeAf-sLargeBe)));
			} else {
				$('.galerie_large:nth-child('+(i+1)+')').css('height',300);
			}
		}

	}

	function resizeGalerie(){
		for (var i=1;i<=Math.ceil((images.length-1)/line);i++) {
			if(((images.length-1)-((images.length-1)%nbImgPerLine))/nbImgPerLine<i) {
				nbImgPerLine=(images.length-1)%nbImgPerLine;
			}
			
			var sLargeBe=-10,
			sLargeAf=-10;
			for (var iLarge=1;iLarge<=nbImgPerLine;iLarge++) {
				sLargeBe=parseInt(sLargeBe+images[iLarge+(i-1)*line].width/images[iLarge+(i-1)*line].height)+10;
				sLargeAf=parseInt(sLargeAf+images[iLarge+(i-1)*line].width*501/images[iLarge+(i-1)*line].height)+10;
			}
			
			sizeGalerie(sLargeBe,sLargeAf,i,nbImgPerLine);
		}
	}

	function drawGalerie(link,target) {
		$.ajax(link)
		.done(function(data) {
			$("#loader").remove();
			images=[];
			data=JSON.parse(data);
	  		for (var i=0; i<data.length; i++) {
		    	images[i+1] = new Image();
		    	images[i+1].src='../media/cache/miniature/'+data[i].absolutePath;
		    	images[i+1].full_src='../media/cache/compression/'+data[i].absolutePath;
		    	images[i+1].alt=data[i].alt;
		    	images[i+1].id=data[i].id;
	    	}
	    	nbImg=images.length-1;

	    	line=Math.ceil(nbImg/nbImgPerLine);

			check=0;
			for (var iLoad=0; iLoad<images.length; iLoad++){
				$(images[iLoad]).on('load',function(){
					check++;
					if (check==images.length-1) {
						sizeGalerie();
					}
				});
			}

			var w = parseInt($( window ).width());	
			$( window ).resize(function(){
				//Only width
				if( w != $( window ).width() ){
					w = $( window ).width();

					if($(window).width()<992){
						nbImgPerLine=nbImgPerLinePhone;
	    				line=Math.ceil(nbImg/nbImgPerLine);						
						$('#galerie').html('');
						sizeGalerie();
						return;
					} else if ($(window).width()>=992) {
						nbImgPerLine=nbImgPerLineDesktop;
	    				line=Math.ceil(nbImg/nbImgPerLine);		
						$('#galerie').html('');
						sizeGalerie();
						return;
					}
			  	}			
			});		  
		})
		.fail(function() {
	    	alert( "error" );
	  	})
	}

	var page = getUrlParameter('page');
	if (page === undefined) {
		page = 1;
	}
	drawGalerie('api/images/'+page);
});