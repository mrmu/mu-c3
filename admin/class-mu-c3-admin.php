<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://audilu.com
 * @since      1.0.0
 *
 * @package    Mu_C3
 * @subpackage Mu_C3/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mu_C3
 * @subpackage Mu_C3/admin
 * @author     Audi Lu <khl0327@gmail.com>
 */
class Mu_C3_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in mu-c3_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The mu-c3_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $post_type;
		if( 'c3_chart' == $post_type ) {
			wp_enqueue_style( 
				$this->plugin_name, 
				plugin_dir_url( __FILE__ ) . 'css/mu-c3-admin.css', 
				array(), 
				filemtime( (dirname( __FILE__ )) . '/css/mu-c3-admin.css' ), 
				'all' 
			);
			wp_enqueue_style( 'c3-js-css', '//cdnjs.cloudflare.com/ajax/libs/c3/0.7.11/c3.min.css', array(), '0.7.11', 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in mu-c3_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The mu-c3_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $post_type;
		if( 'c3_chart' == $post_type ) {
			wp_enqueue_script( 
				$this->plugin_name, 
				plugin_dir_url( __FILE__ ) . 'js/mu-c3-admin.js', 
				array( 'jquery' ), 
				filemtime( (dirname( __FILE__ )) . '/js/mu-c3-admin.js' ), 
				false 
			);
			wp_enqueue_script( 'd3-js', '//cdnjs.cloudflare.com/ajax/libs/d3/5.15.0/d3.min.js', array(), '5.15.0', true );
			wp_enqueue_script( 'c3-js', '//cdnjs.cloudflare.com/ajax/libs/c3/0.7.11/c3.min.js', array('d3-js'), '0.7.11', true );
		}

	}

}
