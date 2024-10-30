<?php
namespace Cf7io;
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fired during plugin activation
 *
 * @link       https://www.internetmanagers.nl
 * @since      1.0.0
 *
 * @package    Cf7_inbound_organizer
 * @subpackage Cf7_inbound_organizer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cf7_inbound_organizer
 * @subpackage Cf7_inbound_organizer/includes
 * @author     De Internet Managers <robin@internetmanagers.nl>
 */
class Cf7_inbound_organizer_Activator {
	
	public static function deactivate_plugin() {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        deactivate_plugins( CF7_inbound_organizer_NAME );
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
	}
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		/* Check if Contact Forms 7 and Flamingo are active */
		if (! defined( 'WPCF7_PLUGIN' ) || ! defined ( 'FLAMINGO_PLUGIN' ) ) {
			self::deactivate_plugin();
			wp_die( 
					'<strong>'.
					esc_html( __( 'Cannot activate CF7 Inbound Organizer.', 'cf7-inbound-organizer' ) ).
					'</strong><br/>'.
					sprintf( 
							esc_html( __( 'You need the plugins %s and %s for this plugin to function.', 'cf7-inbound-organizer' ) ) ,
							'<a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a>',
							'<a href="https://wordpress.org/plugins/flamingo/">Flamingo</a>').
					'<br/>'.
					sprintf(
							esc_html( __( 'Back to the %splugins page%s.','cf7-inbound-organizer' ) ),
							'<a href="'.esc_url( admin_url( 'plugins.php' ) ).'">',
							'</a>'
					)
			);
		}
		if ( ! get_option( 'cf7_inbound_organizer_columns' ) ) {
			$options = array (
				'number_of_columns' => 3,
				'column_name_1' => 'Column 1',
				'column_name_2' => 'Column 2',
				'column_name_3' => 'Column 3',
				'column_name_4' => '',
				'column_name_5' => '',
				'column_color_1' => 1,
				'column_color_2' => 1,
				'column_color_3' => 1,
				'column_color_4' => 1,
				'column_color_5' => 1
			);
			update_option( 'cf7_inbound_organizer_columns', $options );
		}
		if ( ! get_option( 'cf7_inbound_organizer_general' ) ) {
			$options = array (
				'sorting' => 1,
				'page_size' => 10
			);
			update_option( 'cf7_inbound_organizer_general', $options );
		}
	}
	

}
