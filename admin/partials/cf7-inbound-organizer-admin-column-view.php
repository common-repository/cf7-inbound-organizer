<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Provide a for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.internetmanagers.nl
 * @since      1.0.0
 *
 * @package    Cf7_inbound_organizer
 * @subpackage Cf7_inbound_organizer/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="cf7-inbound-organizer-card <?php echo esc_attr( $color_class ); ?>" data-id="<?php echo esc_html( $post->ID ); ?>">
    <?php
    echo '<span class="cf7-inbound-organizer-card-datetime">'.esc_html( $date ).' '.esc_html( $time ).'</span>';
    echo '<h3>'.esc_html( $name ).'</h3>';
    echo '<p>'.esc_html( $email ).'<br/>';
    echo esc_html( $subject ).'</p>';
    ;
    ?>
    <div class="cf7-inbound-organizer-card-trash">
    </div>
</div>