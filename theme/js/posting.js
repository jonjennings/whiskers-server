jQuery(document).ready(function($) {
    /*
        Stacks posting interface
    */
	// Dedicated service edit show/hide
	$('.posting').click(function(event) {
		event.preventDefault();
		$(this).next('.service-edit').removeClass('is-hidden');
	});  
	$('.close').click(function(event) {
		event.preventDefault();
		$(this).closest('.service-edit').addClass('is-hidden');		
	}); 
	      
	// Remove service
	$('.remove').click(function(event) {
		event.preventDefault();
		if (($(window).width()) <= 800) {
			$(this).closest('.driver').remove();
		}
		if (($(window).width()) >= 801) {
			$(this).closest('.driver').fadeOut(400, function() { $(this).remove(); });
		}		
	});

    	// reset focus, so our event is triggered.
    	// temp fix, should find workaround without blur()
    	$('textarea').blur();
    	$('#text').live('focus', function() {
		// wait for keypress
		var $text = $(this);
		
		$text.keyup(function() {
			$('.driver-text').each(function(i,e) {
			var id = $(this).attr('id'),
			do_update = true;
                	
                	// Twitter rules
                	//if (id === 'twitter_text') {
                    //	if ($(this).val().length > 140) {
                    //    		$(this).addClass('error').attr('title', 'Message cannot exceed 140 characters.');
                    //    		do_update = true;
                    //    	}
                   	//	else {
                    //		$(this).removeClass('error');
                    //		do_update = true;
                    //	}
               	//}
               	if (do_update) {
               		$(this).val($text.val()); 
                    	$(this).html($text.val());
               	}
           	});
           	$('.posting-main').find('.edit-status').html('unedited &rsaquo;');
     	});
    	});
    	   	
    	$('.service-textarea').live('focus', function() {
    		// wait for keypress
		var $text = $(this);		
		$text.keyup(function() {
			$(this).closest('.field-shim').find('.driver-text').each(function(i,e) {
				var id = $(this).attr('id'),
				do_update = true;
              		if (do_update) {
               		$(this).val($text.val()); 
                    	$(this).html($text.val());
               	}
           	});
           	$(this).closest('.field-shim').find('.edit-status').html('edited &rsaquo;');
     	});
     });    	
    	
    // post to multiple services
    $('#post').bind('submit', function() {
        var endpoint = $(this).attr('data-endpoint'),
            $drivers = $(this).find('#drivers .driver .service-textarea');

        if ($drivers.length === 0) {
            return false;
        }

if ($drivers.length !== 0) {
	
		// These values are used on the server so server knows
		// each service-specific post comes from part of the same
		// overall text. Stored in database & used to compute common key
		timestamp = ((new Date().getTime())/1000) | 0;
		parent_text = $(this).find('.all-drivers_text').val();
	
        $drivers.each(function() {
            var $this = $(this),
                $parent = $(this).parent('.driver'),
                driver = $(this).attr('data-driver'),
                text = $(this).val();

            $.ajax({
                type: 'POST',
                url: endpoint,
                data: { 
					timestamp: timestamp,
					parent_text: parent_text,
                    driver: driver, 
                    text: text 
                },
                complete: function(jqXHR, textStatus) { 
                	$this.addClass(textStatus);
                	$this.closest('.field-shim').addClass(textStatus);
                	//$this.closest('.field-shim').find('.edit-status:first').html(textStatus + '!');
                	//console.log(jqXHR);
                	//console.log(textStatus);
                },
                dataType: 'json'
            });
        });
        return false;
}
    });     

	// Mobile main posting textarea
	$(window).on("load resize orientationchange", function() {
		if (($(window).width()) <= 800) {
			var currentwidth = ($(window).width());
			var currentheight = ($(document).height());
			$('.service-edit').css({'width':(currentwidth)+'px', 'height':(currentheight)+'px'});
			$('.all-drivers_text, .service-edit textarea').css({'width':((currentwidth)-16)+'px'});
		}	
	});		 
});