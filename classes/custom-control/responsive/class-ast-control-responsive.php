<?php
/**
 * Customizer Control: responsive
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A text control with validation for CSS units.
 */
class Ast_Control_Responsive extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'ast-responsive';

	/**
	 * The fields that each container row will contain.
	 *
	 * @access public
	 * @var array
	 */
	public $fields = array();

	/**
	 * The row label
	 *
	 * @access public
	 * @var array
	 */
	public $row_label = '';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		$css_uri = TWENTY17_HOOKS_URL . 'classes/custom-control/responsive/';
		$js_uri  = TWENTY17_HOOKS_URL . 'classes/custom-control/responsive/';

		wp_enqueue_script( 'ast-responsive', $js_uri . 'responsive.js', array( 'jquery', 'customize-base' ), false, true );
		wp_localize_script( 'ast-responsive', 'astL10n', $this->l10n() );
		wp_enqueue_style( 'ast-responsive-css', $css_uri . 'responsive.css', null );

	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		$val = maybe_unserialize( $this->value() );

		$this->json['value']   	= $val;
		$this->json['fields']   = $this->fields;
		$this->json['row_label']   = $this->row_label;
		$this->json['link']     = $this->get_link();
		$this->json['id']       = $this->id;
		$this->json['l10n']     = $this->l10n();
		$this->json['label']    = esc_html( $this->label );

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<label class="customizer-text customize-control-repeater">
			<# _.each( data.value, function( value, j ) { #>
			<# var field; var index = data.index; #>
			<li class="repeater-row" data-row="{{{ index }}}">

				<div class="repeater-row-header">
					<span class="repeater-row-label">{{ data.row_label }} - {{ value['action_hook'] }}</span>
					<i class="dashicons dashicons-arrow-down repeater-minimize"></i>
				</div>
				<div class="repeater-row-content" style="display: none;">
					<# _.each( data.fields, function( field, i ) { #>
						<div class="repeater-field repeater-field-{{{ field.type }}}">

							<# if ( 'number' === field.type ) { #>

								<label>
									<# if ( field.label ) { #><span class="customize-control-title">{{ field.label }}</span><# } #>
									<# if ( field.description ) { #><span class="description customize-control-description">{{ field.description }}</span><# } #>
									<input type="{{ field.type }}" class="ast-responsive-input-fields" value="{{{ value[i] }}}" data-field="{{{ field.id }}}" >
								</label>

							<# } else if ( 'hidden' === field.type ) { #>

								<input type="hidden" class="ast-responsive-input-fields" data-field="{{{ field.id }}}" <# if ( value[i] ) { #> value="{{{ value[i] }}}" <# } #> />

							<# } else if ( 'select' === field.type ) { #>

								<label>
									<# if ( field.label ) { #><span class="customize-control-title">{{ field.label }}</span><# } #>
									<# if ( field.description ) { #><span class="description customize-control-description">{{ field.description }}</span><# } #>
									<select data-field="{{{ field.id }}}" class="ast-responsive-input-fields" <# if ( ! _.isUndefined( field.multiple ) && false !== field.multiple ) { #> multiple="multiple" data-multiple="{{ field.multiple }}"<# } #>>
										<# _.each( field.choices, function( choice, key ) { #>
											<option value="{{{ key }}}" <# if ( value[i] == key ) { #> selected="selected" <# } #>>{{ choice }}</option>
										<# }); #>
									</select>
								</label>

							<# } else if ( 'textarea' === field.type ) { #>

								<# if ( field.label ) { #><span class="customize-control-title">{{ field.label }}</span><# } #>
								<# if ( field.description ) { #><span class="description customize-control-description">{{ field.description }}</span><# } #>
								<textarea rows="5" class="ast-responsive-input-fields" data-field="{{{ field.id }}}">{{ value[i] }}</textarea>

							<# } #>

						</div>
					<# }); #>
					<div>
						<a href="#" class="repeater-add-delete">Remove</a>
						<a href="#" class="repeater-add-reset">Reset</a> | <a href="#" class="repeater-add-close">Close</a> 
					</div>
				</div>
			</li>
			<# }); #>
		</label>
		<div class="repeater-add-new-wrapper">
			<button type="button" class="button button-secondary repeater-add-new">Add New Action Hook</button>
		</div>
		<?php
	}

	/**
	 * Returns an array of translation strings.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @param string|false $id The string-ID.
	 * @return string
	 */
	protected function l10n( $id = false ) {
		$translation_strings = array(
			'invalid-value' => esc_attr__( 'Invalid Value', 'astra' ),
		);
		return $translation_strings;
	}
}
