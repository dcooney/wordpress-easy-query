var _ewpq_admin = _ewpq_admin || {};

jQuery(document).ready(function($) {
	"use strict"; 
		
	
	/*
	*  _ewpq_admin.copyToClipboard
	*  Copy shortcode to clipboard
	*
	*  @since 1.0.0
	*/     
	
	_ewpq_admin.copyToClipboard = function(text) {
		window.prompt ("Copy link to your clipboard: Press Ctrl + C then hit Enter to copy.", text);
	}
	
	// Copy link on shortcode builder
	$('.cta .copy').click(function(){
		var c = $('#shortcode_output').html();
		_ewpq_admin.copyToClipboard(c);
	});
	
	// Copy link on repeater templates
	$('.alm-dropdown .copy a').on('click', function(e){
		var container = $(this).closest('.repeater-wrap'), // find closet wrap
			 el = container.data('name'); // get template name
		
		var c = $('textarea', container).val(); // Get textarea val()
		_ewpq_admin.copyToClipboard(c);
	});	
	
	
	
	/*
	*  _ewpq_admin.resizeTOC
	*  Resize sidebar of shortcode builder
	*
	*  @since 1.0.0
	*/
	
	_ewpq_admin.resizeTOC = function(){      
      var tocW = $('.cnkt-sidebar').width();
      $('.table-of-contents').css('width', tocW + 'px'); 
   }
   _ewpq_admin.resizeTOC();
   
   $(window).resize(function() {
      _ewpq_admin.resizeTOC();
   });
   
   
   $(window).scroll(function(){
      _ewpq_admin.attachSidebar();
   });	
	
	
	
	/*
	*  _ewpq_admin.attachSidebar
	*  Attached sidebar to be fixed position
	*
	*  @since 1.0.0
	*/
   
   _ewpq_admin.attachSidebar = function(){
      if($('.table-of-contents').length){
         
         var scrollT = $(window).scrollTop(),
             target = 70; 
                  
         if((theTop - scrollT) < target)
            $('.table-of-contents').addClass('attached');
         else
            $('.table-of-contents').removeClass('attached');
            
      }
   }
      
   if($('.table-of-contents').length){
      $('body').scrollTop(0);
      var theTop = $('.table-of-contents').offset().top;
      _ewpq_admin.attachSidebar();
   }
	
	
	
	/*
	*  Shortcode builder controls
	*
	*  @since 1.0.0
	*/ 
	
	$('.nav-tab-wrapper a').click(function(e){
   	e.preventDefault();
	   var el = $(this),
	       tab = $('.tab-content'),
	       classname = 'nav-tab-active',
	       index = el.index();
	       
   	if(!el.hasClass('classname')){
   	   tab.hide();
   	   tab.eq(index).show();
   	   _ewpq_admin.attachSidebar();
         _ewpq_admin.resizeTOC();
      	el.addClass(classname).siblings('a').removeClass(classname);
   	}
	});
	
	
	
	/*
   *  Expand/Collapse shortcode headings
   *
   *  @since 1.0.0
   */ 
   
	$(document).on('click', '.cnkt h3.heading', function(){
		var el = $(this);
		if($(el).hasClass('open')){
			$(el).next('.expand-wrap').slideDown(100, 'cnkt_easeInOutQuad', function(){
				$(el).removeClass('open');
			});
		}else{
			$(el).next('.expand-wrap').slideUp(100, 'cnkt_easeInOutQuad', function(){
				$(el).addClass('open');
			});
		}
	});
	
	$(document).on('click', '.cnkt .toggle-all', function(){
      var el = $(this);
		if($(el).hasClass('closed')){
		   $(el).removeClass('closed');
         $('h3.heading').removeClass('open');
			$('.expand-wrap').slideDown(100, 'cnkt_easeInOutQuad');
		}else{
		   $(el).addClass('closed');
         $('h3.heading').addClass('open');
			$('.expand-wrap').slideUp(100, 'cnkt_easeInOutQuad');
		}
   });
   
   
   
   /*
   *  Back 2 Top
   *
   *  @since 2.0
   */ 
   $('.back2top a').on('click',  function(e){
      e.preventDefault();
      $('html,body').animate({ scrollTop: 0 }, 300);
      return false; 
   })
	
	
});