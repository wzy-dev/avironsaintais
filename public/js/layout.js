$(window).ready(function(){
	$('#menu_mobile').data('open','false');
	$('#menu_mobile ul').css('bottom',$('#menu_mobile ul').outerHeight(true)+$('#menu_open').height());
	$('#menu_open').click(function(){
		if ($('#menu_mobile').data('open')=='false') { 
			$('#menu_mobile ul').css('visibility','visible');
			$('#menu_mobile').css('height',$('#menu_mobile ul').outerHeight(true)+$('#menu_open').height());
			$('#menu_mobile ul').css('bottom',0);
			$('#menu_open span .icon-menu').css('transform','rotate(90deg)');
			$('#menu_mobile').data('open','true');
		} else if ($('#menu_mobile').data('open')=='true') { 
			$('#menu_mobile').css('height','2.99em');
			$('#menu_mobile ul').css('bottom',$('#menu_mobile ul').outerHeight(true)+$('#menu_open').height());
			$('#menu_open span .icon-menu').css('transform','rotate(0deg)');
			$('#menu_mobile ul').css('visibility','hidden');
			$('#menu_mobile').data('open','false');
		}
	});
	$( window ).resize(function(){
		if ($('#menu_mobile').data('open')=='true') { 
			$('#menu_open').trigger('click');
		}
	});

	$('#choisir_section').change(function(e){$('#horaires_large').css('left',-$(e.target).val()*100+'%')});
});

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-90176306-1', 'auto');
  ga('send', 'pageview');