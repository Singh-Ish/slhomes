<?php

    if ( ! class_exists( 'Redux_Validation_colorrgba' ) ) {
        class Redux_Validation_colorrgba {

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since ReduxFramework 3.0.4
             */
            function __construct( $parent, $field, $value, $current ) {

                $this->parent       = $parent;
                $this->field        = $field;
                $this->field['msg'] = ( isset( $this->field['msg'] ) ) ? $this->field['msg'] : __( 'This field must be a valid color value.', 'realty-house' );
                $this->value        = $value;
                $this->current      = $current;
                //$this->validate();
            }

            //function

            /**
             * Validate Color to RGBA
             * Takes the user's input color value and returns it only if it's a valid color.
             *
             * @since ReduxFramework 3.0.3
             */
            function validate_colorrgba( $color ) {
                return $color;
                $alpha = '1.0';
                if ( $color == "transparent" ) {
                    return $hidden;
                }
                return array( 'hex' => $color, 'alpha' => $alpha );
            }

            //function

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since ReduxFramework 3.0.0
             */
            function validate() {
                $this->value = $this->validate_colorrgba( $this->value );
            }

            //function
        }

        //class
    }