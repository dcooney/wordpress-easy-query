jQuery(document).ready(function($) {
   "use strict";    
   
   var _ewpq = {},
       output_div = $('#shortcode_output'),
       output = '[easy_query]'; 
   
   output_div.text(output); //Init the shortcode output 
       
       
      
   /*
   *  _ewpq.select2
   *  Init Select2 select replacement
   *
   *  @since 1.0.0
   */  
   _ewpq.select2 = function(){
      // Default Select2
      $('.row select, .cnkt-main select, select.jump-menu').not('.multiple').select2({});   
      
      // multiple
      $('.cnkt .categories select.multiple').select2({
         placeholder : 'Select Categories',
      });     
      $('.cnkt .tags select.multiple').select2({
         placeholder : 'Select Tags'         
      });
   };
   _ewpq.select2();
   
   
   
   // Reset all selects
   _ewpq.reset_select2 = function(){
      // Default Select2
      $('.row select, .cnkt-main select, select.jump-menu').not('.multiple').select2();   
      
      // multiple
      $('.cnkt .categories select.multiple').select2();     
      $('.cnkt .tags select.multiple').select2();
   };   
              
   
   
   /*
   *  _ewpq.buildShortcode
   *  Loop sections and build the shortcode
   *
   *  @since 1.0.0
   */     

   _ewpq.buildShortcode = function(){
      output = '[easy_query';    
      
      // ---------------------------
      // - Container Options      
      // ---------------------------
      
      var container_type = $('.container_type input[name=container_type]:checked').val();
      if(container_type !== 'ul' && container_type != undefined)
         output += ' container="'+container_type+'"'; 
      
      var container_classes = $('.container_type input[name=classes]').val();
      if(container_classes !== '' && container_classes != undefined)
         output += ' classes="'+container_classes+'"';        
      
      // ---------------------------
      // - Paging       
      // ---------------------------      
         
      var paging = $('.paging input[name=enable_paging]:checked').val();
      if(paging !== 'true' && paging != undefined){
         output += ' paging="'+paging+'"';
      }
                   
      
      // ---------------------------
      // - Template
      // ---------------------------
      
      var template = $('.template select').val(); 
      if(template != '' && template != undefined && template != 'default') 
         output += ' template="'+template+'"';       
      
      // ---------------------------
      // - Posts Per Page       
      // ---------------------------
      
      var posts_per_page = $('.posts_per_page input').val();        
      if(posts_per_page > -2 && posts_per_page != 6){
         if(posts_per_page == 0)
            output += ' posts_per_page="-1"';
         else
            output += ' posts_per_page="'+posts_per_page+'"';
      }             
      
      // ---------------------------
      // - Post Types
      // ---------------------------
      
      var post_type_count = 0;
      $('.post_types input[type=checkbox]').each(function(e){         
         if($(this).is(":checked")) {
            post_type_count++;
            if(post_type_count>1){
               output += ', ' + $(this).data('type');
            }else{
               if($(this).hasClass('changed')){
                  output += ' post_type="'+$(this).data('type')+''; 
               }              
            }
         }
      }); 
      if(post_type_count>0) 
         output += '"';
        
      // ---------------------------
      // - Post Format
      // ---------------------------
      
      var post_format = $('.post_format select').val(); 
      if(post_format != '' && post_format != undefined) 
         output += ' post_format="'+post_format+'"';
      
                 
      // ---------------------------
      // - Categories      
      // ---------------------------
      
      // IN
      var cat = $('.categories #category-select').val();              
      if(cat !== '' && cat !== undefined && cat !== null) 
         output += ' category__in="'+cat+'"';         
         
      // NOT_IN
      var cat_not_in = $('.categories #category-exclude-select').val();              
      if(cat_not_in !== '' && cat_not_in !== undefined && cat_not_in !== null) 
         output += ' category__not_in="'+cat_not_in+'"';
      
      
      // ---------------------------
      // - Tags      
      // ---------------------------
      
      var tag = $('.tags #tag-select').val();
      if(tag !== '' && tag !== undefined && tag !== null) 
         output += ' tag__in="'+tag+'"';   
         
      // NOT_IN
      var tag_not_in = $('.tags #tag-exclude-select').val();              
      if(tag_not_in !== '' && tag_not_in !== undefined && tag_not_in !== null) 
         output += ' tag__not_in="'+tag_not_in+'"';

      
      // ---------------------------
      // - Date      
      // ---------------------------
      var currentTime = new Date(),
          currentYear = currentTime.getFullYear();
      
      var dateY = $('.date input#input-year').val(); // Year          
      if(dateY  !== '' && dateY  !== undefined && dateY <= currentYear) 
         output += ' year="'+dateY+'"';   
      
      var dateM = $('.date input#input-month').val(); // Month          
      if(dateM  !== '' && dateM  !== undefined && dateM < 13) 
         output += ' month="'+dateM+'"';   
      
      var dateD = $('.date input#input-day').val(); // Day          
      if(dateD  !== '' && dateD  !== undefined && dateD < 32) 
         output += ' day="'+dateD+'"';   
      
      
      // ---------------------------
      // - Authors      
      // ---------------------------
      
      var author = $('.authors #author-select').val();              
      if(author !== '' && author !== undefined) 
         output += ' author="'+author+'"';   
      
      
      // ---------------------------
      // - Search      
      // ---------------------------
      
      var search = $('.search-term input').val();    
      search = $.trim(search);       
      if(search !== '') 
         output += ' search="'+search+'"'; 
      
      // ---------------------------
      // - Custom Arguments      
      // ---------------------------
      
      var custom_args = $('.custom-arguments input').val();    
      custom_args = $.trim(custom_args);       
      if(custom_args !== '') 
         output += ' custom_args="'+custom_args+'"'; 
         
         
      // ---------------------------
      // - Include posts      
      // ---------------------------
      
      var include = $('input#include-posts').val();    
      include = $.trim(include);       
      if(include !== ''){
         //Remove trailing comma, if present
         if(include.charAt( include.length-1 ) == ",") {
            include = include.slice(0, -1)
         }
         output += ' post__in="'+include+'"';  
      } 
         
         
      // ---------------------------
      // - Exclude posts      
      // ---------------------------
      
      var exclude = $('input#exclude-posts').val();    
      exclude = $.trim(exclude);       
      if(exclude !== ''){
         //Remove trailing comma, if present
         if(exclude.charAt( exclude.length-1 ) == ",") {
            exclude = exclude.slice(0, -1)
         }
         output += ' post__not_in="'+exclude+'"';  
      } 
         
          
      // ---------------------------
      // - Post Status      
      // ---------------------------
      var post_status = $('select#post-status').val();   
      if(post_status !== 'publish') 
         output += ' post_status="'+post_status+'"'; 
         
          
      // ---------------------------
      // - Ordering      
      // ---------------------------
      var order = $('select#post-order').val(),
         orderby = $('select#post-orderby').val();    
      if(order !== 'DESC') 
         output += ' order="'+order+'"'; 
      if(orderby !== 'date') 
         output += ' orderby="'+orderby+'"'; 
      
      
      // ---------------------------
      // - Post Offset      
      // ---------------------------
      
      var offset = $('.offset input').val();   
      if(offset > 0) 
      	output += ' offset="'+offset+'"';  
      
      
      
      output += ']';  //Close shortcode          
      output_div.text(output); 
      
      if(output  != '[easy_query]') 
      	$('.reset-shortcode-builder').show();
      else	
      	$('.reset-shortcode-builder').hide();
   }  
   
    
   /*
   *  On change events
   *
   *  @since 1.0.0
   */ 
   
   //Select 'post' by default
   $('.post_types input[type=checkbox]#chk-post').prop('checked', true).addClass('changed'); 
   
   
   $(document).on('change keyup', '.alm_element', function() {      
      $(this).addClass('changed');      

      // If post type is not selected, select 'post'.
      if(!$('.post_types input[type=checkbox]:checked').length > 0){
         $('.post_types input[type=checkbox]#chk-post').prop('checked', true);
      } 
      
      // If Tax Term Operator is not selected, select 'IN'.
      if(!$('#tax-operator-select input[type=radio]:checked').length > 0){
         $('#tax-operator-select input[type=radio]#tax-in-radio').prop('checked', true);
      }     
      
      _ewpq.buildShortcode();
   });
   
   
   $("input.numbers-only").keydown(function (e) {
      if ($.inArray(e.keyCode, [188, 46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          // Allow: Ctrl+A
         (e.keyCode == 65 && e.ctrlKey === true) || 
          // Allow: home, end, left, right, down, up
         (e.keyCode >= 35 && e.keyCode <= 40)) {
              // let it happen, don't do anything
              return;
     }
     // Ensure that it is a number and stop the keypress
     if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
         if(e.keyCode !== 188){ // If keycode is not a comma
            e.preventDefault();
         }
     } 
   });
   
   
   
   /*
   *  Jump to section, Table of contents
   *
   *  @since 1.0.0
   */ 
   
   var jumpOptions = '';
   var toc = '';
	$('.row').each(function(){
	   if(!$(this).hasClass('no-brd')){ // Special case for back 2 top on shortcode builder landing
   		var id = $(this).attr('id');
   		var title = $(this).find('h3.heading').text();
   		jumpOptions += '<option value="'+id+'">'+title+'</option>';
		}
	});
	
	
	
	/* Jump Menu */
	
	$('select.jump-menu').append(jumpOptions);
	$('select.jump-menu').change(function() {
		var pos = $(this).val();
		if(pos !== 'null'){
			$('html,body').animate({
			   scrollTop: $('#'+pos).offset().top - ($('.intro').height() - 20)
			}, 200, 'cnkt_easeInOutQuad');
		}
   });
   
   
	
	/* Table of Contents */
	$('.table-of-contents .toc').append('<option value="#">-- Jump to Option --</option>');
	$('.table-of-contents .toc').append(jumpOptions).select2();	
	
	$('.table-of-contents .toc').change(function() {
	   var pos = $(this).val();
		if(pos !== 'null'){
			$('html,body').animate({
			   scrollTop: $('#'+pos).offset().top - 46
			}, 500, 'cnkt_easeInOutQuad');
		}
   });
      
   
     	
	
   /*
   *  get_tax_terms
   *  Get taxonomy terms via ajax
   *
   *  @since 1.0.0
   */
   function get_tax_terms(tax){
      var placement = $('#tax-terms-container');
      placement.html("<p class='loading'>Fetching Terms...</p>");
		$.ajax({
			type: 'GET',
			url: window.parent.ewpq_admin_localize.ajax_admin_url,
			data: {
				action: 'ewpq_get_tax_terms',
				taxonomy: tax,
				nonce: window.parent.ewpq_admin_localize.ewpq_admin_nonce,
			},
			dataType: "html",
			success: function(data) {	
				placement.html(data);
			},
			error: function(xhr, status, error) {
				responseText.html('<p>Error - Something went wrong and the terms could not be retrieved.');
			}
		});
	}
	
	
	
	/*
   *  _ewpq.cnkt_easeInOutQuad
   *  Custom easing
   *
   *  @since 1.0.0
   */  
   
	$.easing.cnkt_easeInOutQuad = function (x, t, b, c, d) {
      if ((t/=d/2) < 1) return c/2*t*t + b;
      return -c/2 * ((--t)*(t-2) - 1) + b;
   }
   
   
   
   /*
   *  _ewpq.SelectText
   *  Click to select text
   *
   *  @since 1.0.0
   */  
   
   _ewpq.SelectText = function(element) {
       var doc = document, 
         text = doc.getElementById(element), 
         range, 
         selection;    
       if (doc.body.createTextRange) {
           range = document.body.createTextRange();
           range.moveToElementText(text);
           range.select();
       } else if (window.getSelection) {
           selection = window.getSelection();        
           range = document.createRange();
           range.selectNodeContents(text);
           selection.removeAllRanges();
           selection.addRange(range);
       }
   }
   $('#shortcode_output').click(function() {
     _ewpq.SelectText('shortcode_output');
   });
   
   
   
   /*
   *  Reset shortcode builder
   *
   *  @since 1.0.0
   */    
   
   $(document).on('click', '.reset-shortcode-builder a', function(){
      $('#easy-wp-builder-form').trigger("reset");
      _ewpq.reset_select2();
      _ewpq.buildShortcode();
      $('.post_types input[type=checkbox]#chk-post').prop('checked', true);
   }); 
   

  
});