
		//firstLoad
		$(function(){

			//show btnTop, fixBtn
			var showFlag = false;
			var btnPagetop = $('.pagetop');
			var btnFinav = $('.fixnav-sp');
			btnFinav.css('top', '-100%');

			$(window).scroll(function () {
			    if ($(this).scrollTop() >= 200) {
			        if (showFlag == false) {
			            showFlag = true;
			            btnFinav.stop().animate({'top' : '0'}, 500).addClass('active');
			             btnPagetop.stop().animate({'bottom' : '2em'}, 500);
			        }
			    } else {
			        if (showFlag) {
			            showFlag = false;
			             btnPagetop.stop().animate({'bottom' : '-100%'}, 500);
			            btnFinav.stop().animate({'top' : '-100%'}, 200).removeClass('active');
			        }
			    }
			     $(window).scrollTop() > 200 ? $('.header-main').addClass('fixed') : $('.header-main').removeClass('fixed');
			});	
	});

// SCROLLTO THE TOP
$(document).ready(function() {
	// Show or hide the sticky footer button
	$body=$('body');
	$html=$('html');
	$('.menuopen').click(function(){
	  if($body.hasClass('fix_active')) {
	    $body.removeClass('fix_active');
	    $('.fixnav').fadeOut();	
		$html.css({
			overflowY: '',
			height: '',
			margin:''
		});
	  } else {
	    $('.fixnav').fadeIn();
	    $body.addClass('fix_active');
		$html.css({
			overflowY: 'hidden',
			height: '100%',
		});		    
	  }
	  $('.box_menu li a').click(function(){
	  	$body.removeClass('fix_active');
	    $('.fixnav').fadeOut();	
		$html.css({
			overflowY: '',
			height: '',
			margin:''
		});
	  })
	});

	$('.lang_en').click(function(){
		$('#en').show();
		$('#vn').hide();
	})
	$('.lang_vn').click(function(){
		$('#vn').show();
		$('#en').hide();
	})

});

