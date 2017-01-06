<?php

/**
 * Set up admin
 *
 * @package CF_Translate
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
class CF_Translate_Admin {

	/**
	 * Slugs for resources
	 *
	 * @since 0.1.0
	 *
	 * @var stdClass
	 */
	protected $slugs;

	/**
	 * Plugin directory path
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Plugin directory url
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * Plugin version
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * CF_Translate_Admin constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param $slugs
	 * @param $path
	 * @param $url
	 * @param $version
	 */
	public function __construct( $slugs, $path, $url, $version ) {
		$this->slugs = $slugs;
		$this->path = $path;
		$this->url = $url;
		$this->version = $version;

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

	}

	/**
	 * Add submenu to Caldera Forms menu
	 *
	 * @since 0.1.0
	 *
	 * @uses "admin_menu" action
	 */
	public function add_menu(){
		add_submenu_page(
			$this->slugs->cf,
			__( 'Translations', 'caldera-forms-translation' ),
			__( 'Translations', 'caldera-forms-translation' ),
			Caldera_Forms::get_manage_cap(),
			$this->slugs->translate,
			array( $this, 'render_admin' )
		);
	}

	/**
	 * Render the main admin page
	 *
	 * @since 0.1.0
	 */
	public function render_admin(){
		include $this->path . '/views/main-page.php';
	}

	/**
	 * Register scripts/styles
	 *
	 * @since 0.1.0
	 *
	 * @uses "admin_enqueue_scripts"
	 */
	public function register(){
		//$codes_slug = $this->slugs->translate . '-language-codes';
		//wp_register_script( $codes_slug, $this->url . '/assets/js/language-codes.js' );

		//@TODO better handling for handlebars loading
		$handlebars_slug = $this->slugs->cf . '-handlebars';



		wp_enqueue_style( 'cf-grid-styles' );
		wp_enqueue_style( 'cf-form-styles' );
		wp_register_script( $handlebars_slug, CFCORE_URL . 'assets/js/handlebars.js' );
		wp_register_script( $this->slugs->translate, $this->url . '/assets/js/cf-translate.js', array(
			'jquery',
			'underscore',
			$handlebars_slug
		), $this->version, true );
		wp_register_style( $this->slugs->translate, $this->url . '/assets/css/cf-translate.css', array(

		), $this->version );

	}

	/**
	 * Load scripts/styles
	 *
	 * @since 0.1.0
	 *
	 * @param string $hook Current hook
	 *
	 * @uses "admin_enqueue_scripts"
	 */
	public function enqueue( $hook ){
		if( $this->slugs->cf . '_page_' . $this->slugs->translate == $hook ){

			//shit I need to make this easier.
			$select2_js = CFCORE_URL . 'fields/select2/js/select2.js';
			wp_enqueue_script( Caldera_Forms_Render_Assets::make_slug( $select2_js ), $select2_js, array( 'jquery' ), CFCORE_VER );
			$select2_css = CFCORE_URL . 'fields/select2/css/select2.css';
			wp_enqueue_style( Caldera_Forms_Render_Assets::make_slug( $select2_css ), $select2_css, array(), CFCORE_VER );

			wp_enqueue_script( $this->slugs->translate );
			wp_enqueue_style( $this->slugs->translate );
			wp_enqueue_style( $this->slugs->cf . '-admin-styles', CFCORE_URL . 'assets/css/admin.css', array(), CFCORE_VER );
			$this->localize();
		}

	}

	/**
	 * Localize JavaScript
	 *
	 * @since 0.1.0
	 */
	public function localize(){
        $form = $this->get_form();
        if( is_wp_error( $form ) ){
            $form = null;
        }

        $localizer = new CF_Translate_Localize( $form );
        wp_localize_script( $this->slugs->translate, 'CFTRANS', $localizer->to_array() );


	}

    /**
     * Get the form based on current HTTP request
     *
     * @since 0.1.0
     *
     * @return array|CF_Translate_Form|null|WP_Error
     */
	protected function get_form(){
		if( ! empty( $_GET[ 'form' ] ) && is_string( $_GET[ 'form' ] ) && ! empty( $_GET[ 'cftrans_nonce' ] ) ) {
			$form_id = $_GET[ 'form' ];
			$nonce   = $_GET[ 'cftrans_nonce' ];
			if( CF_Translate_AdminForm::verify_nonce( $nonce ) ){
				return  CF_Translate_Factories::get_form( $form_id );
			}
		}

		return null;

	}

}