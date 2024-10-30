<?php
namespace Cf7io;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.internetmanagers.nl
 * @since      1.0.0
 *
 * @package    Cf7_inbound_organizer
 * @subpackage Cf7_inbound_organizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cf7_inbound_organizer
 * @subpackage Cf7_inbound_organizer/admin
 * @author     De Internet Managers <robin@internetmanagers.nl>
 */
class Cf7_inbound_organizer_Admin {

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
	 * The post type to display.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $post_type    The post type that will be queried and displayed.
	 */
	private $post_type;

	
	/**
	 * Which tracking status exist and must be displayed
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tracking_statuses   The statuses that will be displayed (key -> value).
	 */
	private $tracking_statuses;

	/**
	 * Which format to use for dates displayed
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $date_format   A string in the common notation for date formatting.
	 */
	private $date_format;

	/**
	 * Which format to use for times displayed
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $time_format   A string in the common notation for time formatting.
	 */
	private $time_format;

	/**
	 * How many posts (= inbound messages) to show initially and how many to add when loading more
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      integer    $post_per_page   The value that determines the posts per page
	 */
	private $posts_per_page;

	/**
	 * Array to instruct WP_Query how to sort posts
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $sorting  The array to include as sorting parameter for WP_Query
	 */
	private $sorting;
	

	/**
	 * Array that maps color choice to class names
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $color_classes  The array that maps numbers to class names for colors 
	 */
	private $color_classes;
	
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
		$this->post_type = 'flamingo_inbound';
		
		$column_options = get_option( 'cf7_inbound_organizer_columns' );
		$columns = $column_options['number_of_columns'];
		$this->tracking_statuses = array();
		for ($column = 1; $column <= $columns; $column++) {
			$column_name = array_key_exists( 'column_name_'.$column, $column_options ) ?
				$column_options['column_name_'.$column] : '';
			$this->tracking_statuses[ $column ] = $column_name;
		}
		$general_options = get_option( 'cf7_inbound_organizer_general');
		switch ( $general_options['sorting'] ) {
			case 2:
				$this->sorting = array( 'date' => 'asc' );
				break;
			case 3:
				$this->sorting = array( 'title' => 'asc' );
				break;
			default:
				$this->sorting = array( 'date' => 'desc' );
				break;
		}
		$this->date_format = get_option( 'date_format' );
		$this->time_format = get_option( 'time_format');
		$this->posts_per_page = $general_options['page_size'];
		$this->color_classes = array(
									1 => 'cf7-gray',
									2 => 'cf7-red',
									3 => 'cf7-green',
									4 => 'cf7-blue',
									5 => 'cf7-white',
									6 => 'cf7-brown' );
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
		 * defined in Cf7_inbound_organizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf7_inbound_organizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		 wp_enqueue_style( 'dashicons' );
		 wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf7-inbound-organizer-admin.css', array(), $this->version, 'all' );
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
		 * defined in Cf7_inbound_organizer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cf7_inbound_organizer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-effects-core');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-droppable');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf7-inbound-organizer-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'cf7io_ajax_obj',
								array(
									'ajax_url' => admin_url( 'admin-ajax.php' ),
									'nonce'    => wp_create_nonce( 'organize messages' ),
								)
						  );
	}

	/**
	 * Filter that adds the tracking_status to the $args that are used by Flamingo to store a message.
	 *
	 * The addition of tracking_status = 1 adds the message to the first column of the Inbound Organizer 
	 * 
	 * @since    1.0.0
	 * @param    array    $args       Contains the existing args for the message.
	 * @return 	 array	  Contains the same array plus one additional key value
	 */
	public function add_meta( $args ) {
		$args['meta']['tracking_status'] = '1';
		return $args;
	}

	/**
	 * Checks whether both Contact Forms 7 and Flamingo are active.
	 * 
	 * There is no check on version (yet).
	 * The function will wp_die when one or both of the plugins are not active. 
	 *
	 * @since    1.0.0
	 */
	public function check_plugins () {
		/* Check if Contact Forms 7 and Flamingo are active */
		if (! defined( 'WPCF7_PLUGIN' ) || ! defined ( 'FLAMINGO_PLUGIN' ) ) {	
			require_once plugin_dir_path( __FILE__ ) . '../includes/class-cf7-inbound-organizer-activator.php';
			Cf7_inbound_organizer_Activator::deactivate_plugin();
			wp_die( 
					'<strong>'.
					esc_html( 
						__( 'Cannot continue to run CF7 Inbound Organizer, it has been deactivated.', 'cf7-inbound-organizer' ) 
					).
					'</strong><br/>'. 
					sprintf( esc_html(
								__('You need to activate the plugins %s and %s. Once those plugins are active, you can reactivate CF7 Inbound Organizer.', 
									'cf7-inbound-organizer' 
								) 
							 ),
								'<a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a>',
								'<a href="https://wordpress.org/plugins/flamingo/">Flamingo</a>'
					).
					'<br/>'.
					sprintf( esc_html(
								__( 'Back to the %splugins page%s.', 'cf7-inbound-organizer' )
							 ),
							 '<a href="'.esc_url( admin_url( 'plugins.php' ) ).'">',
							 '</a>'
					)
			);
		}
	}

	/**
	 * Adds two items to the Flamingo menu.
	 *
	 * 1. The Inbound Organizer
	 * 2. The Settings for the Inbound Organizer
	 * @since    1.0.0
	 */
	public function add_menu () {
		add_submenu_page ( 'flamingo', 'CF7 Inbound Organizer', __( 'Inbound Organizer', 'cf7-inbound-organizer' ), 'flamingo_edit_inbound_messages', 'cf7-inbound-organizer-main-page', array( $this, 'render_main_page'));
		add_submenu_page ( 'flamingo', 'CF7 Inbound Organizer', __('Organizer Settings', 'cf7-inbound-organizer' ), 'flamingo_edit_inbound_messages', 'cf7-inbound-organizer-settings-page', array( $this, 'render_settings_page'));
	}
	
	/**
	 * Helper function to return 'Today' or 'Yesterday' instead of a full date.
	 *
	 * If the full date refers to today or yesterday, the date is replaced by the string 'Today' or 'Yesterday'.
	 * @since    1.0.0
	 * @param    WP_Post   $post    The post that contains the date.
	 * @param    string    $date    The date string.
	 * @return	 string	 The date string. 
	 */
	protected function friendly_date ($post, $date ) {
		$compare_date = new \DateTime( $post->post_date );
		$compare_date->setTime ( 0, 0, 0);
		$now = new \DateTime( );
		$now->setTime ( 0, 0, 0);
		switch ( $now->diff( $compare_date )->days ) {
			case 0: 
				$date = __('Today', 'cf7-inbound-organizer');
				break;
			case 1:
				$date = __('Yesterday', 'cf7-inbound-organizer');
				break;
			default:
				break;
		} 
		return $date;
	}

	/**
	 * Renders one column with messages (cards) for the main page
	 *
	 * @since    1.0.0
	 * @param  	 integer   $status      	The tracking status for which the column must be rendered.
	 * @param    string    $status_name 	The version of this plugin.
	 * @param	 integer   $paged			The page number to be rendered. Default 1.
	 * @param	 boolean   $skip_container	Whether the container div must be rendered.
	 * @param	 boolean   $skip_header		Whether the header (h2) must be rendered.
	 * @param	 boolean   $check_load_more	Whether the load more button must be rendered if applicable.
	 * @return	 echo output in HTML 
	 */
	protected function render_main_page_column ( 
					$status, 
					$status_name, 
					$paged = 1, 
					$skip_container = false,
					$skip_heading = false,
					$check_load_more = true
		) {
		global $post;
		
		/**We cannot query serialized metadata values with WP Query.
		 * So we create the serialized string value that must be in that metadata value.
		 * In this way we can use the power of WP Query for a value buried in a serialized
		 * metadata value
		 **/
		$status_serialized = substr( 
			serialize( array ( 'tracking_status' => strval ( $status ) ) ),
			10, 
			-1 
		) ;
		$query = new \WP_Query( 
			array( 
			'post_type' => $this->post_type,	
			'orderby' => $this->sorting,
			'posts_per_page' => $this->posts_per_page,
			'meta_key' => '_meta',
			'meta_value' => $status_serialized,
			'meta_compare' => 'LIKE',
			'paged' => $paged
			) 
		);
		if (! $skip_container ) {
			$columns_options = get_option( 'cf7_inbound_organizer_columns' );
			$color_class = array_key_exists( 'column_color_'.$status, $columns_options ) 
							? $this->color_classes[ $columns_options['column_color_'.$status] ] 
							: $this->color_classes[1];
			?>
			<div class="cf7-inbound-organizer-column col-<?php echo count( $this->tracking_statuses ); ?>
						<?php echo esc_attr( $color_class ); ?>" 
						data-status="<?php echo esc_attr( $status ); ?>">
			<?php
		}
		if (! $skip_heading ) {
			?>
				<h2><?php echo esc_html( $status_name ).' ('.esc_html( $query->found_posts ).')'; ?></h2>
			<?php
		}
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
						$name = get_post_meta( $post->ID, '_from_name' )[0];
						$email = get_post_meta( $post->ID, '_from_email' )[0];
						$subject = get_post_meta( $post->ID, '_subject' )[0];
						$date = date_i18n ( $this->date_format, get_post_timestamp( $post ) );
						$time = date_i18n ( $this->time_format, get_post_timestamp( $post ) );
						$date = $this->friendly_date( $post, $date );
						
						//If the color is set, use it. Otherwise white (color index 5)
						$color = get_post_meta( $post->ID, '_cf7io_color', true );
						$color_class = ( empty ( $color ) ) 
										? $this->color_classes[5] 
										: $this->color_classes[$color];
		
						//Include partial with HTML
						require plugin_dir_path(  __FILE__  ) . 'partials/cf7-inbound-organizer-admin-column-view.php';
			}
			if ( $check_load_more ) {
				if ( $query->found_posts > ( $query->post_count + ( ( $paged -1 ) * $this->posts_per_page ) ) ) {
					$new_page = $paged + 1;
					echo '<button class="button-primary" data-status="'.esc_attr( $status ).'" data-page="'.esc_attr( $new_page ).'">'.__('Load more', 'cf7-inbound-organizer').'</button>';
				}
			}
			$query->wp_reset_postdata();
		}
		if (! $skip_container ) {
			?>
			</div>
			<?php 
		}
		?>
		<div id="cf7-inbound-organizer-card-detail">
		</div>
		<div id="cf7-modal-background">
		</div>
	<?php
	}
	
	/**
	 * Renders the HTML lay-out for the Inbound Organizer and calls the function to render the columns
	 *
	 * @since    1.0.0
	 */
	public function render_main_page () {
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e('Inbound Organizer', 'cf7-inbound-organizer'); ?></h1>
			<div class="cf7-inbound-organizer-container">
			<div id="cf7-inbound-organizer-message" class="notice notice-success is-dismissible">
			</div>
			<?php
				foreach ( $this->tracking_statuses as $code => $name) {
					$this->render_main_page_column( $code, $name );	
				} 
			?>
			</div>
		</div>
		<?php	
	}

	/**
	 * ========== AJAX FUNCTIONS ==========
	 **/

	/**
	 * Stores the new tracking status of a message.
	 * 
	 * When a messages is dragged & dropped from one column to another, the new status must be stored.
	 *
	 * @since    1.0.0
	 * 
	 * Input via $_POST
	 * @param 	integer	$post_id The post (message) for which the status must be updated.
	 * @param	string 	$status	 The new tracking status for the post (message).
	 * 
	 * Return via echo 
	 * @return	string	A notification that the status has been updated.
	 */
	public function update_message_tracking_status () {
		check_ajax_referer( 'organize messages' );
		$post_id = absint( $_POST['postData']['id'] );
		$status = strval( absint( $_POST['postData']['status'] ) );
		$metadata = get_post_meta( $post_id, '_meta')[0];
		$metadata['tracking_status'] = $status;
		update_post_meta( $post_id, '_meta', $metadata );
		echo esc_html(
			sprintf( 
				__( 
					'Message from %s moved to %s.', 
					'cf7-inbound-organizer'
				  ),
				get_post_meta( $post_id, '_from_name' )[0],
				$this->tracking_statuses[ $metadata['tracking_status'] ]  
			) 
		);
		wp_die();
	}

	/**
	 * Renders the HTML for the message popup.
	 * 
	 * When a messages is clicked, a popup is displayed with the details. 
	 * This function renders the HTML for that.
	 *
	 * @since    1.0.0
	 * 
	 * Input via $_POST
	 * @param 	integer	$post_id The post (message) for which the details must be rendered.
	 * 
	 * Return via echo 
	 * @return	string	The rendered HTML.
	 */
	public function render_message_details () {
		check_ajax_referer( 'organize messages' );
		$post = get_post( absint ( $_POST['postData']['id'] ) );
		$date = date_i18n ( $this->date_format, get_post_timestamp( $post ) );
		$time = date_i18n ( $this->time_format, get_post_timestamp( $post ) );
		$date = $this->friendly_date( $post, $date );
							
		//Include partial with HTML
		require_once plugin_dir_path(  __FILE__  ) . 'partials/cf7-inbound-organizer-admin-card-view.php';
		wp_die();
	}

	/**
	 * Marks a message (post) as Trash.
	 * 
	 * When a the user clicks on the corresponding icon,
	 * a messages must be marked as Trash so that it disappears from the column.
	 *
	 * @since    1.0.0
	 * 
	 * Input via $_POST
	 * @param 	integer	$post_id The post (message) to be marked as Trash.
	 * 
	 * Return via echo 
	 * @return	string	A notification that the message has been marked as Trash.
	 */
	public function trash_message() {
		check_ajax_referer( 'organize messages' );
		$post_id = absint ( $_POST['postData']['id'] );
		wp_trash_post( $post_id );
		echo esc_html( 
						sprintf( 
							__( 'Message from %s sent to trash.',
								'cf7-inbound-organizer'  
							), 
							get_post_meta( $post_id, '_from_name' )[0] 
						) 
		);
		wp_die();
	}

	/**
	 * Saves notes for a message (post).
	 * 
	 * When a the user adds notes to a message, these must be saved.
	 * These are stored in a seperate meta data field.
	 *
	 * @since    1.0.0
	 * 
	 * Input via $_POST
	 * @param 	integer	$post_id The post (message) to which the notes belong.
	 * 
	 * Return via wp_send_json 
	 * @return	array	JSON array with the post_id for which the notes were saved.
	 */
	public function save_message_notes() {
		check_ajax_referer( 'organize messages' );
		$post_id = absint ( $_POST['postData']['id'] );
		$notes = sanitize_textarea_field( $_POST['postData']['notes'] );
		update_post_meta( $post_id, '_cf7io_notes', $notes);
		wp_send_json( array( 'result' => $post_id ) );
	}

	/**
	 * Renders the HTML for additional messages in a column.
	 * 
	 * Messages are loaded per 'page' (subset). 
	 * This function loads the next page and renders the HTML for those messages.
	 *
	 * @since    1.0.0
	 * 
	 * Input via $_POST
	 * @param 	integer	$status The column for which the next page must be loaded.
	 * @param	inreger $page 	The page (number) that must be loaded
	 * 
	 * Return via echo 
	 * @return	string	The rendered HTML for the messages in the column.
	 */
	public function load_more_messages() {
		check_ajax_referer( 'organize messages' );
		$status = absint( $_POST['postData']['status'] );
		$page = absint( $_POST['postData']['page'] );
		$this->render_main_page_column( $status, '', $page, true, true );
		wp_die();
	}

	/**
	 * Re-renders the HTML for messages in a column.
	 * 
	 * When a message is moved, trashed or changed in color, the column with the message must
	 * be refreshed to properly reflect the change.
	 *
	 * @since    1.0.0
	 * 
	 * Input via $_POST
	 * @param 	integer	$status The column for which the messages must be refreshed.
	 * @param	integer $count 	The number of messages in the column to be refreshed
	 * 
	 * Return via echo 
	 * @return	string	The rendered HTML for the messages in the column.
	 */
	public function refresh_messages() {
		check_ajax_referer( 'organize messages' );
		$status = absint( $_POST['postData']['status'] );
		$count = absint( $_POST['postData']['count'] );
		$number_of_pages = (int) ( ( $count - 1) / $this->posts_per_page ) + 1;
		for ($page = 1; $page <= $number_of_pages; $page++ ) {
			$this->render_main_page_column( 
				$status, 
				$this->tracking_statuses[ $status ], 
				$page, 
				true, 									//Skip container rendering (div)
				( $page > 1 ), 							//Skip heading (h2)
				( $page == $number_of_pages ) 			//Display load more button if applicable
			);			
		}
		wp_die();
	}
	
	/**
	 * Updates the display color for a message.
	 * 
	 * When a the user changes the display color, this must be saved.
	 * It is stored in a seperate meta data field.
	 *
	 * @since    1.0.0
	 * 
	 * Input via $_POST
	 * @param 	integer	$post_id The post (message) for which the color was changed.
	 * @param	integer $color	 The color index that was selected.	
	 * 
	 * Return via wp_send_json 
	 * @return	array	JSON array with the post id and the class name of the color that was saved.
	 */
	public function update_message_color() {
		check_ajax_referer( 'organize messages' );
		$post_id = absint ( $_POST['postData']['id'] );
		$color = absint ( $_POST['postData']['color'] );
		update_post_meta( $post_id, '_cf7io_color', $color );
		wp_send_json( array( 
						'color_class' => $this->color_classes[$color],
						'post_id' => $post_id
						   ) 
					);
	}

	/**
	 * Adds messages that were stored prior to installing / (re)activating the plugin.
	 * 
	 * Existing messages are not automatically displayed on the board, because it can be many
	 * old and irrelevant messages.
	 * This function is to import relevant (recent) existing messages to the board.
	 *
	 * @since    1.0.0
	 * 
	 * Input via $_POST
	 * @param 	integer	$days 	How days of messages from the past must be imported.
	 * @param	integer $status	Destination status (column) must be to import.
	 * 
	 * Return via echo
	 * @return	array	Notification with number of imported messages.
	 */
	public function add_messages() {
		global $post;
		check_ajax_referer( 'organize messages' );
		$days = absint ( $_POST['postData']['days'] );
		$status = absint( $_POST['postData']['column'] );
		$column_options = get_option( 'cf7_inbound_organizer_columns' );
		if ( $days > 0 && $status > 0 && $status <= $column_options['number_of_columns']) {
			$query = new \WP_Query( array ( 
				'post_type' => $this->post_type,	
				'date_query' => array( 'after' => '-'.$days.' days' ),
				'nopaging' => true
				) 
		  	);
			if ( $query->have_posts() ) {
				$add_count = 0;
				while ( $query->have_posts() ) {
					$query->the_post();
					$metadata_raw = get_post_meta( $post->ID, '_meta');
					if (! empty ( $metadata_raw ) ) {
						$metadata = $metadata_raw[0];											
						if (! array_key_exists('tracking_status', $metadata ) ) {
							$metadata['tracking_status'] = strval( $status );
							update_post_meta( $post->ID, '_meta', $metadata );
							$add_count++;
						}
					}
				}
				if ( $add_count > 0 ) {
					echo sprintf( 
						esc_html( __(
							'%s older messages added to the Inbound Organizer.', 
							'cf7-inbound-organizer'
						) ), 
						$add_count 
					);
				} else {
					echo esc_html( __( 
						'No older messages to be added to the Inbound Organizer.', 
						'cf7-inbound-organizer'
					) );
				}
			} else {
				echo esc_html( __( 
					'No older messages to be added to the Inbound Organizer.', 
					'cf7-inbound-organizer'
				) );
			}
			$query->wp_reset_postdata();
		} else {
			echo esc_html( __('Invalid input received, no messages added.', 'cf7-inbound-organizer' ) );
		}
		wp_die();
	}


	/**
	 * ============= END AJAX =============
	 */

	/**
	 * ============= SETTINGS =============
	 */
	
	/**
	 * Register settings, settings sections and fields.
	 * 
	 * There are 2 setting sections: 1 for column settings and 1 for general settings.
	 * 
	 */
	public function init_settings() {
		
		//Register column settings
		$args = array (
            'type' => 'string',
            'sanitize_callback' => array( $this, 'sanitize_columns_settings' ),
            'default' => NULL
        );
        register_setting( 
			'cf7_inbound_organizer_columns' , 
			'cf7_inbound_organizer_columns' , 
			$args 
		);
        add_settings_section ( 
			'cf7_inbound_organizer_columns',       				//Section name
            __( 'Columns', 'cf7-inbound-organizer' ),      		//Title displayed
            array( $this, 'render_explanation_columns' ),     	//Callback to echo additional explanation
            'cf7-inbound-organizer-columns-settings' 			//Page name
		);         
        
		//Create a field for the number of columns
        add_settings_field ( 
			'cf7_inbound_organizer_number_of_columns',          //ID
            __('Number of columns', 'cf7-inbound-organizer' ),  //Field label
            array( $this, 'render_number_of_columns'),  		//Callback to render field
            'cf7-inbound-organizer-columns-settings',           //Page name
            'cf7_inbound_organizer_columns');                  	//Section name

		//Create fields for the column names and colors
		$columns = get_option( 'cf7_inbound_organizer_columns' )['number_of_columns'];
		for ($column = 1; $column <= $columns; $column++) {
        	add_settings_field ( 
				'cf7_inbound_organizer_column_name_'.$column,          				  //ID
                sprintf( __( 'Column name %d', 'cf7-inbound-organizer'), $column ), //Field label
                array( $this, 'render_column_name'),  								  //Callback to render field
                'cf7-inbound-organizer-columns-settings',       					  //Page name
                'cf7_inbound_organizer_columns',									  //Section name
				array( 'column' => $column ) 										  //Pass column number to callback
			);    					
			add_settings_field ( 
				'cf7_inbound_organizer_column_color_'.$column,          			//ID
				sprintf( __( 'Column color %d', 'cf7-inbound-organizer' ), $column ),//Field label
				array( $this, 'render_column_color'),  								//Callback to render field
				'cf7-inbound-organizer-columns-settings',              				//Page name
				'cf7_inbound_organizer_columns',									//Section name
				array( 'column' => $column ) 										//Pass column number to callback	
			);
		}              	
		
		//Register general settings
		$args = array (
            'type' => 'string',
            'sanitize_callback' => array( $this, 'sanitize_general_settings' ),
            'default' => NULL
        );
        register_setting( 
			'cf7_inbound_organizer_general' , 
			'cf7_inbound_organizer_general' , 
			$args 
		);
		add_settings_section ( 
			'cf7_inbound_organizer_general',       				//Section name
            __( 'General', 'cf7-inbound-organizer' ),      		//Title displayed
            array( $this, 'render_explanation_general' ),     	//Callback to echo additional explanation
            'cf7-inbound-organizer-general-settings' 			//Page name
		);

		//Create a field for the number of columns
        add_settings_field ( 
			'cf7_inbound_organizer_sorting',             		//ID
            __('Sorting', 'cf7-inbound-organizer' ),		    //Field label
            array( $this, 'render_sorting'),  					//Callback to render field
            'cf7-inbound-organizer-general-settings',           //Page name
            'cf7_inbound_organizer_general'						//Section name
		
		);                  	

		//Create a field for the number of messsages per page
        add_settings_field ( 
			'cf7_inbound_organizer_page_size',             			//ID
            __('Page size (# messages)', 'cf7-inbound-organizer' ),	//Field label
            array( $this, 'render_page_size'),  					//Callback to render field
            'cf7-inbound-organizer-general-settings',              	//Page name
            'cf7_inbound_organizer_general'							//Section name
		);
	}

	/**
	 * Callback function for settings menu. 
	 * 
	 * It will echo the HTML for the settings page consisting of 3 tabs:
	 * 1. General
	 * 2. Column
	 * 3. Add existing messages
	 */
	public function render_settings_page () {	
	if ( current_user_can( 'flamingo_edit_inbound_messages' ) ) {
			?>
			<div class="wrap">
				<h1 class="wp-heading-inline"><?php _e('Inbound Organizer Settings', 'cf7-inbound-organizer'); ?></h1>
				<?php

				//Get the active tab from the $_GET param
  				$tab = isset($_GET['tab']) ? sanitize_text_field( $_GET['tab'] ) : null;
				?>  
				<nav class="nav-tab-wrapper">
				<a href="?page=cf7-inbound-organizer-settings-page" class="nav-tab 
				  	<?php if ( $tab === null ) { ?>nav-tab-active<?php } ?>">
					<?php echo esc_html( __( 'General', 'cf7-inbound-organizer' ) ); ?></a>
				<a href="?page=cf7-inbound-organizer-settings-page&tab=columns" class="nav-tab 
				  	<?php if ( $tab === 'columns' ) { ?>nav-tab-active<?php } ?>">
					<?php echo esc_html( __( 'Columns', 'cf7-inbound-organizer' ) ); ?></a>
				<a href="?page=cf7-inbound-organizer-settings-page&tab=add" class="nav-tab 
				  	<?php if ( $tab === 'add' ) { ?>nav-tab-active<?php } ?>">
					<?php echo esc_html( __( 'Add existing messages', 'cf7-inbound-organizer' ) ); ?></a>
				</nav>
				<div class="tab-content">
    			<?php switch ( $tab ) {
					case 'columns':
						settings_errors( 'cf7_inbound_organizer_columns' );
						?>
						<form action="options.php" method="post">
							<?php
							settings_fields( 'cf7_inbound_organizer_columns' );
							do_settings_sections( 'cf7-inbound-organizer-columns-settings' );
							submit_button( 
								__( 'Save changes', 'cf7-inbound-organizer' ), 
								'primary' 
							);
							?>
						</form>
						<?php
						break;
					case 'add':
						$column_options = get_option( 'cf7_inbound_organizer_columns' );
						?>
						<h2><?php echo esc_html( __('Add older messages', 'cf7-inbound-organizer') ); ?></h2>
						<div id="cf7-inbound-organizer-message" class="notice notice-success is-dismissible">
						</div>
						<p><?php echo esc_html( __( 'Use this page to add messages to the Inbound Organizer that were received prior to installing this plugin.', 'cf7-inbound-organizer' ) ); ?></p>
						<form>
						<table class="form-table" role="presentation">
							<tbody>
								<tr>
									<th scope="row"><?php echo esc_html( __ ( 'Add messages since', 'cf7-inbound-organizer' ) ); ?></th>
									<td><input id="add-since" size="4" type="text"> 
									<?php echo esc_html( __('days ago', 'cf7-inbound-organizer' ) ); ?></td>
								</tr>
								<tr>
									<th scope="row"><?php echo esc_html( __( 'To column', 'cf7-inbound-organizer' ) ); ?></th>
									<td>
										<select id="add-to-column">
										<?php 
										for ( $column = 1; 
											$column <= $column_options['number_of_columns']; $column++ ) {
												$column_name = 
												( array_key_exists ( 'column_name_'.$column, $column_options ) 
												&& ! empty( $column_options['column_name_'.$column] ) )
													? $column_options['column_name_'.$column] 
													: 'Column '.$column;
												echo '<option value="'.esc_attr( $column ).'">'.
												esc_html( $column_name ).'</option>';
										}
										?>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
						<p><button id="add-messages" class="button button-primary"><?php echo esc_html( __( 'Add', 'cf7-inbound-organizer' ) ); ?></button></p>
						</form>
					<?php
						break;
					default:
					settings_errors( 'cf7_inbound_organizer_general' );
						?>
						<form action="options.php" method="post">
							<?php
							settings_fields( 'cf7_inbound_organizer_general' );
							do_settings_sections( 'cf7-inbound-organizer-general-settings' );
							submit_button( 
								__( 'Save changes', 'cf7-inbound-organizer' ), 
								'primary' 
							);
							?>
						</form>
						<?php
						break;
					};
				?>
				</div>
			</div>
			<?php	
		}
	}

	/**
     * Callback function to display introductory text for columns settings page.
     * 
     */
    public function render_explanation_columns() {
		echo '<p>'.esc_html(__( 'Change the column settings here.', 'cf7-inbound-organizer' ) ).'</p>';
	 }

	 /**
     * Callback function to display introductory text for general settings page.
     * 
     */
    public function render_explanation_general() {
		echo '<p>'.esc_html(__( 'Change the general settings here.', 'cf7-inbound-organizer' ) ).'</p>';
	 }
	 

	/**
     * Callback function to sanitize columns settings after these have been submitted.
     * 
     * @param array $input Submitted setting values as key => value
     * 
     * @return array $valid Sanitized setting values as key => value
     */
    public function sanitize_columns_settings ( $input ) {    
		$valid = array();
		$options = get_option( 'cf7_inbound_organizer_columns' );

        //Number_of_columns between 2 and 5 (automatically corrected)
		$valid['number_of_columns'] = absint( $input['number_of_columns'] );
		$valid['number_of_columns'] = ( $valid['number_of_columns'] < 2 ) 
										? 2 : $valid['number_of_columns'];
		$valid['number_of_columns'] = ( $valid['number_of_columns'] > 5 ) 
										? 5 : $valid['number_of_columns'];

		//The column_name not empty, column_color between 1 and 6 (automatically corrected)
		for ($column = 1; $column <= $valid['number_of_columns']; $column++) {
			$valid['column_name_'.$column] = sanitize_text_field ( 
				substr( $input['column_name_'.$column], 0, 15 ) 
			);
			if ( empty( $valid['column_name_'.$column] ) ) {
				$valid['column_name_'.$column] = array_key_exists( 'column_name_'.$column, $options ) 
												? $options['column_name_'.$column] : '';
			}
			$valid['column_color_'.$column] = absint( $input['column_color_'.$column] );
			$valid['column_color_'.$column] = ( $valid['column_color_'.$column] < 1 ) 
												? 1 : $valid['column_color_'.$column];
			$valid['column_color'] = ( $valid['column_color_'.$column] > 6 ) 
										? 6 : $valid['column_color_'.$column];        
		}
        return $valid;
    }

	/**
	 * Renders the settings field for the number of columns
	 * 
	 * If no setting is stored, it defaults to 3.
	 */
	public function render_number_of_columns() {
		$options = get_option( 'cf7_inbound_organizer_columns' );
        $number_of_columns = array_key_exists ( 'number_of_columns', $options ) 
								? absint( $options['number_of_columns'] ) : 3 ;
        $columns = array (  
			2 => __( '2 columns', 'cf7-inbound-organizer' ), 
            3 => __( '3 columns', 'cf7-inbound-organizer'),
			4 => __( '4 columns', 'cf7-inbound-organizer'),
			5 => __( '5 columns', 'cf7-inbound-organizer')
        );

        //Initiate the dropdown field and mark the current value as selected
        echo '<select id="number_of_columns" name="cf7_inbound_organizer_columns[number_of_columns]">';
        foreach ( $columns as $column => $description ) {
            echo '<option value="'.esc_attr( $column ).'" '
                 .selected( $number_of_columns, $column, false ).'>'
                 .esc_html( $description ).'</option>';
        }
        echo "</select>";
	}

	/**
	 * Renders the settings field for the column name
	 * Defaults to Column [column number]
	 */
	public function render_column_name( array $args ) {
		$options = get_option( 'cf7_inbound_organizer_columns' );
        $name = array_key_exists( 'column_name_'.$args['column'], $options ) 
					? $options['column_name_'.$args['column']] 
					: '';
		$name = empty( $name ) 
				? sprintf( __( 'Column %s', 'cf7-inbound-organizer' ), $args['column'] )
				: $name;
		echo '<input type="text" size="15" maxlength="15" id="column_name_'.
			  esc_attr( $args['column'] ).'" 
			  name="cf7_inbound_organizer_columns[column_name_'.esc_attr( $args['column'] ).']" 
			  value="'.esc_attr( $name ).'" />';
	}

	/**
	 * Renders the settings field for the column color
	 * Defaults to color index 1, Gray
	 */
	public function render_column_color( array $args ) {
		$options = get_option( 'cf7_inbound_organizer_columns' );
        $color = array_key_exists( 'column_color_'.$args['column'], $options ) ? 
				$options['column_color_'.$args['column']] : 1;
		$modes = array ( 
				1 => __( 'Gray', 'cf7-inbound-organizer' ), 
				2 => __( 'Red', 'cf7-inbound-organizer'),
				3 => __( 'Green', 'cf7-inbound-organizer'),
				4 => __( 'Blue', 'cf7-inbound-organizer'),
				6 => __( 'Brown', 'cf7-inbound-organizer')
		);

		//Initiate the dropdown field and mark the current value as selected
		echo '<select id="column_color_'.esc_attr( $args['column'] ).
				'" name="cf7_inbound_organizer_columns[column_color_'.
				esc_attr( $args['column'] ).']">';
		foreach ( $modes as $mode => $description ) {
		echo '<option value="'.esc_attr( $mode ).'" '
				.selected( $color, $mode, false ).'
				class="'.esc_attr( $this->color_classes[$mode] ).'">'
				.esc_html( $description ).'</option>';
		}
		echo "</select>";
	}

	/**
     * Callback function to sanitize general settings after these have been submitted.
     * 
     * @param array $input Submitted setting values as key => value
     * 
     * @return array $valid Sanitized setting values as key => value
     */
	public function sanitize_general_settings ( $input ) {

		//The sorting between 1 and 3 (automatically corrected)
		$valid['sorting'] = absint( $input['sorting'] );
		$valid['sorting'] = ( $valid['sorting'] < 1 ) ? 1 : $valid['sorting'];
		$valid['sorting'] = ( $valid['sorting'] > 3 ) ? 3 : $valid['sorting'];
        if ( $valid['sorting'] != $input['sorting'] ) {
            add_settings_error(
                'cf7_inbound_organizer_general',
                'cf7_inbound_organizer_invalid_sorting', 
                'The sorting setting has been adjusted to fit in range.',
                'update'
            );
        }

		//The page_size one of the allowed values 5, 10, 15 or 20, corrected to 10 if needed
		$valid['page_size'] = in_array( absint( $input['page_size'] ), array( 5, 10, 15, 20) ) 
							? absint( $input['page_size'] ): 10;
		if ( $valid['page_size'] != $input['page_size'] ) {
			add_settings_error(
				'cf7_inbound_organizer_general',
				'cf7_inbound_organizer_invalid_page_size', 
				'The page size setting has been adjusted to fit in range.',
				'update'
			);
		}
		return $valid;

	}
	/**
	 * Renders the settings field for sorting
	 * Defaults to 1, Date / time descending
	 */
	public function render_sorting() {
		$options = get_option( 'cf7_inbound_organizer_general' );
        $sorting = array_key_exists( 'sorting', $options) ? 
					$options['sorting'] : '';
		$sorting = empty ( $sorting ) ? 1 : $sorting;
        $modes = array ( 
			1 => __( 'Date / time descending', 'cf7-inbound-organizer' ), 
            2 => __( 'Date / time ascending', 'cf7-inbound-organizer'),
			3 => __( 'Subject ascending', 'cf7-inbound-organizer')
        );

        //Initiate the dropdown field and mark the current value as selected
        echo '<select id="sorting" name="cf7_inbound_organizer_general[sorting]">';
        foreach ( $modes as $mode => $description ) {
            echo '<option value="'.esc_attr( $mode ).'" '
                 .selected( $sorting, $mode, false ).'>'
                 .esc_html( $description ).'</option>';
        }
        echo "</select>";
	}

	/**
	 * Renders the settings field for the page size
	 * Defaults to 10
	 */
	public function render_page_size() {
		$options = get_option( 'cf7_inbound_organizer_general' );
        $page_size = array_key_exists( 'page_size', $options) ? 
					$options['page_size'] : '';
		$page_size = empty ( $page_size ) ? 10 : $page_size;
        $modes = array ( 
			5 => '5', 
            10 => '10',
			15 => '15',
			20 => '20'
        );

        //Initiate the dropdown field and mark the current value as selected
        echo '<select id="page_size" name="cf7_inbound_organizer_general[page_size]">';
        foreach ( $modes as $mode => $description ) {
            echo '<option value="'.esc_attr( $mode ).'" '
                 .selected( $page_size, $mode, false ).'>'
                 .esc_html( $description ).'</option>';
        }
        echo "</select>";
	}
}
