<?php

/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @subpackage  Field_currency
 * @author      Luciano "WebCaos" Ubertini
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if ( !defined ( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( !class_exists ( 'ReduxFramework_currency' ) ) {

    /**
     * Main ReduxFramework_currency class
     *
     * @since       1.0.0
     */
    class ReduxFramework_currency {

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct ( $field = array(), $value = '', $parent ) {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;
        }

        /**
         * Field Render Function.
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render () {

            $defaults = array(
                'show' => array(
                    'title' => true,
                    'description' => true,
                    'url' => true,
                ),
                'content_title' => __ ( 'Currency', 'realty-house' )
            );

            $this->field = wp_parse_args ( $this->field, $defaults );
	        echo '<div id="currency-update-now">
				<button class="button-primary"><i class="el el-refresh"></i>'. esc_html__('Update Currency Rates Now', 'realty-house').'</button>
				<span class="message"></span>
			</div>';
            echo '<div class="redux-currency-accordion" data-new-content-title="' . esc_attr ( sprintf ( __ ( 'New %s', 'realty-house' ), $this->field[ 'content_title' ] ) ) . '">';

            $x = 0;

            $multi = ( isset ( $this->field[ 'multi' ] ) && $this->field[ 'multi' ] ) ? ' multiple="multiple"' : "";

            if ( isset ( $this->value ) && is_array ( $this->value ) && !empty ( $this->value ) ) {

                $currency = $this->value;

                foreach ( $currency as $slide ) {

                    if ( empty ( $slide ) ) {
                        continue;
                    }

                    $defaults = array(
                        'title' => '',
                        'symbol' => '',
                        'position' => ''
                    );
                    $slide = wp_parse_args ( $slide, $defaults );

                    echo '<div class="redux-currency-accordion-group"><fieldset class="redux-field" data-id="' . $this->field[ 'id' ] . '"><h3><span class="redux-currency-header">' . $slide[ 'title' ] . '</span></h3><div>';

                    echo '<ul id="' . $this->field[ 'id' ] . '-ul" class="redux-currency-list">';

                    $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'title' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'title' ] ) : __ ( 'Title', 'realty-house' );
                    echo '<li><input type="text" id="' . $this->field[ 'id' ] . '-title_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][title]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'title' ] ) . '" placeholder="' . $placeholder . '" class="full-text slide-title" /></li>';

                    $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'symbol' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'symbol' ] ) : __ ( 'Symbol', 'realty-house' );
                    echo '<li><input type="text" id="' . $this->field[ 'id' ] . '-symbol_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][symbol]' . $this->field['name_suffix'] . '" value="' . esc_attr ( $slide[ 'symbol' ] ) . '" placeholder="' . $placeholder . '" class="full-text slide-symbol" /></li>';

	                $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'position' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'position' ] ) : __ ( 'Position', 'realty-house' );
	                echo '<li>
						<label>
						<input type="checkbox" id="' . $this->field[ 'id' ] . '-position_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][position]' . $this->field['name_suffix'] . '" '. (!empty($slide[ 'position' ]) && $slide[ 'position' ] == '1' ? esc_attr('checked="checked"') : '') .' value="1" placeholder="' . $placeholder . '" class="full-text slide-position" />
						'. esc_html__('Check this field if you want to show the currency before its value' , 'realty-house') .'</label>
					</li>';
                    
                    echo '<li><input type="hidden" class="slide-sort" name="' . $this->field[ 'name' ] . '[' . $x . '][sort]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-sort_' . $x . '" value="' . (!empty($slide[ 'sort' ]) ? $slide[ 'sort' ] : '') . '" />';
                    echo '<li><a href="javascript:void(0);" class="button deletion redux-currency-remove">' . __ ( 'Delete', 'realty-house' ) . '</a></li>';
                    echo '</ul></div></fieldset></div>';
                    $x ++;
                }
            }

            if ( $x == 0 ) {
                echo '<div class="redux-currency-accordion-group"><fieldset class="redux-field" data-id="' . $this->field[ 'id' ] . '"><h3><span class="redux-currency-header">' . esc_attr ( sprintf ( __ ( 'New %s', 'realty-house' ), $this->field[ 'content_title' ] ) ) . '</span></h3><div>';


                echo '<ul id="' . $this->field[ 'id' ] . '-ul" class="redux-currency-list">';

                $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'title' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'title' ] ) : __ ( 'Title', 'realty-house' );
                echo '<li><input type="text" id="' . $this->field[ 'id' ] . '-title_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][title]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text slide-title" /></li>';

                $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'symbol' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'symbol' ] ) : __ ( 'Symbol', 'realty-house' );
                echo '<li><input type="text" id="' . $this->field[ 'id' ] . '-symbol_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][symbol]' . $this->field['name_suffix'] .'" value="" placeholder="' . $placeholder . '" class="full-text slide-symbol" /></li>';

                $placeholder = ( isset ( $this->field[ 'placeholder' ][ 'position' ] ) ) ? esc_attr ( $this->field[ 'placeholder' ][ 'position' ] ) : __ ( 'Position', 'realty-house' );
                echo '<li>
					<label for="' . $this->field[ 'id' ] . '-position_' . $x . '">
					<input type="checkbox" id="' . $this->field[ 'id' ] . '-position_' . $x . '" name="' . $this->field[ 'name' ] . '[' . $x . '][position]' . $this->field['name_suffix'] .'" value="1" placeholder="' . $placeholder . '" class="full-text slide-location" />
					'. esc_html__('Check this field if you want to show the currency before its value' , 'realty-house') .'</label>
				</li>';

                echo '<li><input type="hidden" class="slide-sort" name="' . $this->field[ 'name' ] . '[' . $x . '][sort]' . $this->field['name_suffix'] .'" id="' . $this->field[ 'id' ] . '-sort_' . $x . '" value="' . $x . '" />';
                echo '<li><a href="javascript:void(0);" class="button deletion redux-currency-remove">' . __ ( 'Delete', 'realty-house' ) . '</a></li>';
                echo '</ul></div></fieldset></div>';
            }
            echo '</div><a href="javascript:void(0);" class="button redux-currency-add button-primary" rel-id="' . $this->field[ 'id' ] . '-ul" rel-name="' . $this->field[ 'name' ] . '[title][]' . $this->field['name_suffix'] .'">' . sprintf ( __ ( 'Add %s', 'realty-house' ), $this->field[ 'content_title' ] ) . '</a><br/>';
        }

        /**
         * Enqueue Function.
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue () {
            if ( function_exists( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            } else {
                wp_enqueue_script( 'media-upload' );
            }
                
            if ($this->parent->args['dev_mode']){
                wp_enqueue_style ('redux-field-media-css');
                
                wp_enqueue_style (
                    'redux-field-currency-css',
                    ReduxFramework::$_url . 'inc/fields/currency/field_currency.css',
                    array(),
                    time (), 
                    'all'
                );
            }
            
            wp_enqueue_script(
                'redux-field-media-js',
                ReduxFramework::$_url . 'assets/js/media/media' . Redux_Functions::isMin() . '.js',
                array( 'jquery', 'redux-js' ),
                time(),
                true
            );

            wp_enqueue_script (
                'redux-field-currency-js',
                ReduxFramework::$_url . 'inc/fields/currency/field_currency' . Redux_Functions::isMin () . '.js',
                array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-sortable', 'redux-field-media-js' ),
                time (), 
                true
            );
	        wp_localize_script( 'redux-field-currency-js', 'redux_obj', array(
		        'ajaxurl'  => esc_url( admin_url( 'admin-ajax.php' ) )
	        ) );
        }
    }
}