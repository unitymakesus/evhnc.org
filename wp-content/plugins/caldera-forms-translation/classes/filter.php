<?php

/**
 * Filter abstraction
 *
 * Might be a bad idea, but it's sunday night and Josh is having fun.
 *
 * @package CF_Translate
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
abstract class CF_Translate_Filter {

	/**
	 * @var CF_Translate_Form
	 */
	protected $form;

	protected $args;

	public function __construct( CF_Translate_Form  $form, array $args ){
		$this->form = $form;
		$this->parse_args( $args );
		$this->add_hook();


	}

	/**
	 * Remove field translation hook
	 *
	 * @since 0.1.0
	 */
	public function remove_hook(){
		remove_filter( $this->args[ 'hook' ], array( $this, $this->args[ 'callback'] ), $this->args[ 'priority' ] );
	}

	protected function parse_args( $args ){
		$this->args = wp_parse_args( $args, array(
			'priority' => 51,
			'callback' => 'filter',
			'hook' => '',
			'language' => cf_translate_get_current_language(),
			'num_args' => 2
		));
	}


	/**
	 * Add hook
	 *
	 * @since 0.1.0
	 */
	protected function add_hook(){

		add_filter( $this->args[ 'hook' ], array( $this, $this->args[ 'callback'] ), $this->args[ 'priority' ], $this->args[ 'num_args' ] );

	}


}