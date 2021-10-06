$(document).ready(function(){
	var nbImgPerLineDesktop=4,
	nbImgPerLinePhone=2,
	nbImg,
	images=[],
	line;

	if($(window).width()<992){
		nbImgPerLine=nbImgPerLinePhone;
		nbImg=4;
	} else {
		nbImgPerLine=nbImgPerLineDesktop;
		nbImg=8;
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
					$('.galerie_large:nth-child('+(i+1)+')').append('<img height="'+(501+dif*500/(sLargeAf-sLargeBe))+'" alt="'+images[iN].alt+'" src="'+images[iN].src+'" data-full_src="'+images[iN].full_src+'"/>');
				}
			}
			$('.galerie_large:nth-child('+(i+1)+')').css('height',(501+dif*500/(sLargeAf-sLargeBe)));
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
						nbImg=4;
						$('#galerie').html('');
						sizeGalerie();
						return;
					} else if ($(window).width()>=992) {
						nbImgPerLine=nbImgPerLineDesktop;
						nbImg=8;
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

	drawGalerie('api/images/index');
});