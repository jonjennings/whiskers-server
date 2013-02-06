jQuery(document).ready(function($) {
	if (("standalone" in window.navigator) && window.navigator.standalone) {
 		$('a:not([target=_blank]), .post-utilities a').click(function() { window.location = $(this).attr('href'); 
 		return false; });
	}

    // Handle actions in the posting text box.
    $('#text').focus().keyup(function() {
        $('.count').html($(this).val().length + ' chars');
    }).keydown(function(e) {
        if ((e.ctrlKey || e.metaKey) && e.keyCode == 13) {
            $('#post-form-submit').click();
        }
    });
    
    $('.service-textarea').focus().keyup(function() {
        $(this).closest('.service-edit').find('.count').html($(this).val().length + ' chars');
    }).keydown(function(e) {
        if ((e.ctrlKey || e.metaKey) && e.keyCode == 13) {
            $('#post-form-submit').click();
        }
    });
    
    // confirm removals
    // Chet disabled wasn't working
    //$('form.remove').bind('submit', function() {
    //    confirm('Are you sure you want to remove? This will also attempt to delete the data from any linked services.');
    //});
    
	// Mobile reset article padding
	if ($('form').hasClass('posting-main') || $('div').hasClass('posting-history')) { 	
			$('.wrapper').css({'background':'rgb(237,237,237)'});
	}

	$(window).on("load resize orientationchange", function() {
		if (($(window).width()) <= 800) {
			if ($('form').hasClass('admin-forms')) { 		
				$('footer, .branding').removeClass('is-hidden');				
			}
			if ($('form').hasClass('posting-main') || $('div').hasClass('posting-history') || $('div').hasClass('add-services')) { 	
				$('footer, .branding').addClass('is-hidden');
			}
			if ($('div').hasClass('add-services')) { 	
				$('.wrapper').css({'background':'rgb(59,59,60)'});
			}	
		}
		if (($(window).width()) >= 801) {
			$('footer, .branding').removeClass('is-hidden')
			if ($('form').hasClass('posting-main') || $('div').hasClass('posting-history') || $('div').hasClass('add-services')) {
				$('body').addClass('desktop-views');
			}
			if ($('div').hasClass('add-services')) { 	
				$('.wrapper').css({'background':'rgb(237,237,237)'});
			}						
		}
	}); 		    
});