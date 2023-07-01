<?php
/**
 * Plugin Main class
 *
 * @package Addons_Kit
 */
namespace Addons_Kit\Elementor;

use Elementor\Controls_Manager;
use Elementor\Elements_Manager;

final class Addons_Kit {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const version = '1.0';

    /**
	 * Plugin instance
	 */
    private static $instance = null;

	private function __construct() {
		add_action( 'init', [ $this, 'load_textdomain' ] );
	}

    /**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * @access public
     * 
     * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'addons-kit',
			false,
			dirname( ADDONS_KIT_PLUGIN_BASENAME ) . '/languages/'
		);
	}

    /**
	 * Plugin instence
	 *
	 * @access public
	 * @static
	 *
	 * @return \Affiliate_Product_Review
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

    /**
	 * Inisialize Plugin
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function init() {

        /**
         * Register custom category
        */
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_new_category' ] );
        
        /**
         * Register custom controls
        */
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_new_controls' ] );

		//add_action( 'init', [ $this, 'include_on_init' ] );

		do_action( 'addonskit_loaded' );

	}

    /**
	 *Add custom category.
	 *
     * @param $elements_manager
	 * @access public
	 *
	 * @return void
	 */
	public function add_new_category( Elements_Manager $elements_manager ) {

		$elements_manager->add_category(
			'addons_kit_category',
			[
				'title' => __( 'Addons Kit', 'addons-kit' ),
				'icon' => 'fa fa-smile-o',
			]
		);

	}

	/**
	 * Register controls
	 *
     * @access public
	 * @param Controls_Manager $controls_Manager
     * 
     * @return void
	 */
	public function register_new_controls( Controls_Manager $controls_Manager ) {

	}

}