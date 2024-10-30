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

<div id="cf7-inbound-organizer-card-detail-close">
</div>
<span class="cf7-inbound-organizer-card-datetime"><?php echo esc_html( $date ).' '.esc_html( $time ); ?></span>
<span><?php echo esc_html( $this->tracking_statuses[ get_post_meta( $post->ID, '_meta' )[0]['tracking_status'] ] )?></span>
<h3><?php echo esc_html( get_post_meta( $post->ID, '_from_name' )[0] ); ?></strong></h3>
<p>
<?php echo esc_html( get_post_meta( $post->ID, '_from_email' )[0] ); ?><br/>
<?php echo esc_html( get_post_meta( $post->ID, '_subject' )[0] ); ?><br/>
</p>
<table class="widefat message-fields striped">
<?php 
$fieldnames = get_post_meta( $post->ID, '_fields' )[0];
foreach ( $fieldnames as $fieldname => $fieldvalue) {
    echo '<tr><td class="field-title">'.esc_html( $fieldname ).'</td><td class="field-value">'.
    esc_html( get_post_meta( $post->ID, '_field_'.$fieldname )[0] ).'</td></tr>';
};
?>
</table>
<form id="cf7-inbound-organizer-card-detail-save">
    <textarea disabled name='notes' placeholder="Notes" rows="5"><?php 
        if ( metadata_exists( 'post', $post->ID, '_cf7io_notes' ) ){
            echo esc_html( get_post_meta( $post->ID, '_cf7io_notes', true ) );
        } ?></textarea>
    <button class="button-primary"><?php echo esc_html( __( 'Save', 'cf7-inbound-organizer' ) ); ?></button>
</form>
<?php
    $color = get_post_meta( $post->ID, '_cf7io_color', true);
    if ( empty ( $color ) ) {
        $color = 5;
    }
?>

<div id="cf7-inbound-organizer-card-detail-color-palette"  data-selected="<?php echo esc_attr( $this->color_classes[$color] ); ?>">
    <table class="color-palette">
        <tbody>
            <tr>
                <td class="cf7-gray" data-index="1">&nbsp;</td>
                <td class="cf7-red" data-index="2">&nbsp;</td>
                <td class="cf7-green" data-index="3">&nbsp;</td>
                <td class="cf7-blue" data-index="4">&nbsp;</td>
                <td class="cf7-white" data-index="5" >&nbsp;</td>
                <td class="cf7-brown" data-index="6" >&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
<div id="cf7-inbound-organizer-card-detail-trash"></div>
		
