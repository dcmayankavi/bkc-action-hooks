/**
 * File responsive.js
 *
 * Handles the responsive
 *
 * @package Astra
 */

	wp.customize.controlConstructor['ast-responsive'] = wp.customize.Control.extend({

		// When we're finished loading continue processing.
		ready: function() {

			'use strict';

			var control = this,
		    value;

			/**
			 * Add new repeater.
			 */
			this.container.on('click', '.repeater-add-new', function() {
				control.addNewRepeater();
			});

			/**
			 * Add new repeater.
			 */
			this.container.on( 'click', '.customize-control-repeater .repeater-minimize', function( e ) {
				
				e.preventDefault();
				var parent_row = jQuery(this).closest('.repeater-row');

				if( parent_row.hasClass( 'expanded' ) ) {
					parent_row.removeClass( 'expanded' );
					parent_row.find('.repeater-row-content').slideUp(100);
				} else {
					parent_row.siblings().removeClass('expanded').find('.repeater-row-content').slideUp(100);
					parent_row.addClass('expanded').find('.repeater-row-content').slideDown(100);
				}
			});

			/**
			 * Close repeater.
			 */
			this.container.on( 'click', '.customize-control-repeater .repeater-add-close', function( e ) {

				e.preventDefault();

				var parent_row = jQuery(this).closest('.repeater-row');

				if( parent_row.hasClass( 'expanded' ) ) {
					parent_row.removeClass( 'expanded' );
					parent_row.find('.repeater-row-content').slideUp(100);
				}
			});

			/**
			 * Delete repeater.
			 */
			this.container.on( 'click', '.customize-control-repeater .repeater-add-delete', function( e ) {

				e.preventDefault();

				var parant_wrapper = jQuery(this).closest('.customize-control-repeater');
				var total_row = parant_wrapper.find('.repeater-row').length;

				console.log(total_row);
				if ( total_row > 1 ) {
					jQuery(this).closest('.repeater-row').remove();
				}

				// Update value on change.
				control.updateValue();

			});

			/**
			 * Reset repeater.
			 */
			this.container.on( 'click', '.customize-control-repeater .repeater-add-reset', function( e ) {

				e.preventDefault();

				jQuery(this).closest('.repeater-row').find( 'input, select, textarea' ).each(function() {
					jQuery(this).val('');
				});

				// Update value on change.
				control.updateValue();

			});

			/**
			 * Save on change / keyup / paste
			 */
			this.container.on( 'change keyup paste', '.ast-responsive-input-fields', function() {

				// Update value on change.
				control.updateValue();
			});

			/**
			 * Refresh preview frame on blur
			 */
			this.container.on( 'blur', 'input', function() {

				value = jQuery( this ).val() || '';

				if ( value == '' ) {
					wp.customize.previewer.refresh();
				}

			});

		},

		/**
		 * Updates the sorting list
		 */
		updateValue: function() {

			'use strict';

			var control = this,
			newValue = {};

			// Set the spacing container.
			control.repeaterContainer = control.container.find( '.customize-control-repeater' ).first();

			control.repeaterContainer.find( '.repeater-row' ).each( function( i ) {

				newValue[i] = {};
				jQuery( this ).find('.ast-responsive-input-fields').each( function() {
					var input = jQuery( this ),
					item_id = input.data( 'field' ),
					item_value = input.val();

					newValue[i][item_id] = item_value;
				});

			});

			control.setting.set( newValue );
		},

		/**
		 * Add new repeater.
		 */
		addNewRepeater: function() {

			'use strict';

			var control = this,
			newValue = {};

			var first_row = control.container.find( '.customize-control-repeater .repeater-row' ).first();

			// Set the spacing container.
			if ( first_row.hasClass('expanded') ) {
				first_row.removeClass('expanded');
				first_row.find('.repeater-row-content').slideUp(100);
			}
			var clone_row = first_row.clone();
			clone_row.find( 'input, select, textarea' ).each(function() {
				jQuery(this).val('');
			});


			control.container.find( '.customize-control-repeater' ).append( clone_row );
			clone_row.find('.repeater-minimize').trigger('click');
		}
	});
