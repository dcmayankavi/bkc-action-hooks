(function($) {

    /**
     * Reload "All Action Hooks"
     */
    jQuery(document).ready(function($) {

    	var container = jQuery('#customize-header-actions'),
            button = jQuery('<button type="submit" title="' + actionHooksLocalized.btnDescription + '" class="reset-actions-hooks-button dashicons dashicons-update button-secondary button"><span class="screen-reader-text">' + actionHooksLocalized.btnTitle + '</span></button>')
            .css({
                'float': 'right',
                'margin-top': '9px',
                'margin-right': '2px',
				'padding': '0',
				'width': '28px',
				'font-size': '16px'
            });

        // Process on click.
        button.on('click', function(event) {
            event.preventDefault();

            // Enable loader.
            container.find('.spinner').addClass('is-active');

            var data = {
                action: 'reset_all_action_hooks',
                nonce: actionHooksLocalized.nonce
            };

            // Disable button.
            button.attr('disabled', 'disabled');

			// Process AJAX.
			jQuery.post(ajaxurl, data, function(result) {

				// If pass then trigger the state 'saved'.
				if ('pass' === result.data) {
					var Url = window.location.href;
					window.location.href = Url;
				}
			});
        });

        container.append(button);

        setTimeout(function(){
	        jQuery('#customize-controls').find('select.bkc-repeater-input-fields[data-field="action_hook"]').each(function(i,e){
	        	var self = $(this);
                if ( self.find('option').length <= 1 ) {
		        	var Url = window.location.href;
					window.location.href = Url;
	        	}
	        });
        	
        }, 1000);
    });

})(jQuery);
