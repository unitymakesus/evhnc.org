<?php
/**
 * Translate form in front-end
 *
 * @package CF_Translate
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
class  CF_Translate_Render extends CF_Translate_Filter{




	/**
	 * Translate a field
	 *
	 * @since 0.1.0
	 *
	 * @uses "caldera_forms_render_get_field" filter.
	 *
	 * @param array $field Field config
	 * @param array $form Form config
	 *
	 * @return mixed
	 */
    public function translate( $field, $form ){
        if( $this->form[ 'ID' ] == $form[ 'ID' ] ){
            $field = $this->translate_field( $field );
        }
        return $field;

    }

	/**
	 * If possible, translate a field
	 *
	 * @since 0.1.0
	 *
	 * @param array $field
	 *
	 * @return array
	 */
    protected function translate_field( $field ){
        $field_object  = $this->form->get_translator()->get_field( $this->args[ 'language' ], $field[ 'ID' ] );
        /**  @var CF_Translate_Field $field_object */
        if( is_object( $field_object ) ){
            foreach( $field_object->get_field_names() as $key ){

                if( 'ID' != $key  ){
		            $value = $field_object->$key;
		            if( ! empty( $value )  && isset( $field[ $key ] )  && $value != $field[ $key ] ) {
			            $field[ $key ] = $field_object->$key;
		            }

                }
            }

        }

        return $field;

    }



    /**
     * Add field translation hook if needed
     *
     * @since 0.1.0
     */
    protected function add_hook (){
        if ( $this->form->get_translator()->has_language( $this->args[ 'language'] )) {
			parent::add_hook();
        }

    }

}