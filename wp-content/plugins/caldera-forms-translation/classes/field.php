<?php

/**
 * Field abstraction for translatable fields
 *
 * @package CF_Translate
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
class CF_Translate_Field extends Caldera_Forms_Object {

	/** @var  string */
    protected $ID;
	/** @var  string */
    protected $caption;
	/** @var  string */
    protected $label;
	/** @var  string */
    protected $default;

	/**
	 * Get all field names
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
    public function get_field_names(){
        $vars = get_object_vars(  $this );
        return array_keys( $vars );
    }



}