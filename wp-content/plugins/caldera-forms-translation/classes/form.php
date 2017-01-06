<?php

/**
 * Form abstraction with translation system
 *
 * Underlying form is exposed as array using ArrayAccess
 *
 * @package CF_Translate
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
class CF_Translate_Form implements ArrayAccess {

	/**
	 * Form config
	 *
	 * Accessible by using object as array
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
    protected $form;

    /**
     * Translation system for this form
     *
     * @since 0.1.0
     *
     * @var CF_Translate_Translator
     */
    protected $translator;

	/**
	 * CF_Translate_Form constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param array $form Form config
	 */
    public function __construct( array  $form ){
        $this->form = $this->set_translator( $form );
    }

	/**
	 * Get ID of from
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
    public function get_id(){
        return $this->form[ 'ID' ];
    }

	/**
	 * Get form config
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
    public function get_form(){
        return $this->form;
    }

	/**
	 * Save form
	 *
	 * @since 0.1.0
	 *
	 * @return bool|string
	 */
    public function save(){
        $this->form[ 'translations' ] = $this->translator;
        return Caldera_Forms_Forms::save_form( $this->form );
    }

    /**
     * Get form translator
     *
     * @since 0.1.0
     *
     * @return CF_Translate_Translator
     */
    public function get_translator(){
        return $this->translator;
    }


	/**
	 * Setup form property with proper translator added in.
	 *
	 * @since 0.1.0
	 *
	 * @param array $form Form config
	 *
	 * @return array
	 */
    private  function set_translator( $form ){
        if( ! isset( $form[ 'translations' ] ) || ! $form[ 'translations' ] instanceof  CF_Translate_Translator ){
            $form[ 'translations' ]  =  CF_Translate_Factories::new_translator( $form );

        }

	    $this->translator = $form[ 'translations' ];


        return $form;
    }

	/**
	 * @inheritdoc
	 */
    public function offsetSet($offset, $value) {

        if (is_null($offset)) {
            $this->form[] = $value;
        } else {
            $this->form[$offset] = $value;
        }

    }

	/**
	 * @inheritdoc
	 */
    public function offsetExists($offset) {
        return isset($this->form[$offset]);
    }

	/**
	 * @inheritdoc
	 */
    public function offsetUnset($offset) {
        unset($this->form[$offset]);
    }

	/**
	 * @inheritdoc
	 */
    public function offsetGet($offset) {
        return isset($this->form[$offset]) ? $this->form[$offset] : null;
    }
}