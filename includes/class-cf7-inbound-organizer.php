<?php
namespace Cf7io;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.internetmanagers.nl
 * @since      1.0.0
 *
 * @package    Cf7_inbound_organizer
 * @subpackage Cf7_inbound_organizer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cf7_inbound_organizer
 * @subpackage Cf7_inbound_organizer/includes
 * @author     De Internet Managers <robin@internetmanagers.nl>
 */
class Cf7_inbound_organizer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cf7_inbound_organizer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		if ( defined( 'CF7_inbound_organizer_VERSION' ) ) {
			$this->version = CF7_inbound_organizer_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'cf7-inbound-organizer';		
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cf7_inbound_organizer_Loader. Orchestrates the hooks of the plugin.
	 * - Cf7_inbound_organizer_i18n. Defines internationalization functionality.
	 * - Cf7_inbound_organizer_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-inbound-organizer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-inbound-organizer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cf7-inbound-organizer-admin.php';

		$this->loader = new Cf7_inbound_organizer_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cf7_inbound_organizer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Cf7_inbound_organizer_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Cf7_inbound_organizer_Admin( $this->get_plugin_name(), $this->get_version() );

		// Check whether CF7 and Flamingo are (still) active.
		$this->loader->add_action( 'init', $plugin_admin, 'check_plugins');

		// Add script and style files
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// Add additional metadata when a new message is added to Flamingo. 
		$this->loader->add_filter( 'flamingo_add_inbound', $plugin_admin, 'add_meta');
		
		// Add submenu to Flamingo menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu');
		
		// Add AJAX-action to drag & drop a lead to another column (update tracking status)
		$this->loader->add_action( 'wp_ajax_cf7io_update_message_tracking_status', $plugin_admin, 'update_message_tracking_status');
	
		// Add AJAX-action to render a lead in detail
		$this->loader->add_action( 'wp_ajax_cf7io_render_message_details', $plugin_admin, 'render_message_details');
	
		// Add AJAX-action to trash a lead
		$this->loader->add_action( 'wp_ajax_cf7io_trash_message', $plugin_admin, 'trash_message');

		// Add AJAX-action to save notes for a lead
		$this->loader->add_action( 'wp_ajax_cf7io_save_message_notes', $plugin_admin, 'save_message_notes');
		
		// Add AJAX-action to load more messages (pagination)
		$this->loader->add_action( 'wp_ajax_cf7io_load_more_messages', $plugin_admin, 'load_more_messages');

		// Add AJAX-action to refresh messages (after message movement)
		$this->loader->add_action( 'wp_ajax_cf7io_refresh_messages', $plugin_admin, 'refresh_messages');

		// Add AJAX-action to update card color
		$this->loader->add_action( 'wp_ajax_cf7io_update_message_color', $plugin_admin, 'update_message_color');

		// Add AJAX-action to add messages to Inbound Organizer received prior to plugin install
		$this->loader->add_action( 'wp_ajax_cf7io_add_messages', $plugin_admin, 'add_messages');

		// Initialize settings page
		$this->loader->add_action( 'admin_init', $plugin_admin, 'init_settings');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Cf7_inbound_organizer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
