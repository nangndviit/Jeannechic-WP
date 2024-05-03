<?php
/**
 * The template for displaying admin product video gallery.
 *
 * @package CommerceKit
 * @subpackage Shoptimizer
 */

?>
<?php
$glob_auto_play = 0;
$options        = get_option( 'commercekit', array() );
$pdp_a_gallery  = isset( $options['pdp_attributes_gallery'] ) && 1 === (int) $options['pdp_attributes_gallery'] ? 1 : 0;
$pdp_gallery    = isset( $options['pdp_gallery'] ) && 1 === (int) $options['pdp_gallery'] ? 1 : 0;
$glob_auto_play = ( ( isset( $options['pdp_video_autoplay'] ) && 1 === (int) $options['pdp_video_autoplay'] ) || ! isset( $options['pdp_video_autoplay'] ) ) ? 1 : 0;
?>
<div id="cgkit-video-gallery-dialog" class="panel wc-metaboxes-wrapper hidden" style="display:none;">
	<input type="hidden" name="commercekit_video" id="commercekit_video" value="<?php echo esc_html( wp_create_nonce( 'commercekit_video' ) ); ?>" />
	<div id="cgkit-video-dialog">
		<div class="inside cgkit-formgroup">
			<div class="form-group"><br />
				<label for="cgkit-video-dialog-input"><?php esc_html_e( 'Video', 'commercegurus-commercekit' ); ?></label>
				<input id="cgkit-video-dialog-input" placeholder="<?php esc_html_e( 'Youtube, Vimeo or video url (.mp4)', 'commercegurus-commercekit' ); ?>" class="cgkit-video form-control" name="cgkit_video_dialog_input" value="" data-error="<?php esc_html_e( 'Youtube, Vimeo or video url (.mp4 format only) allowed.', 'commercegurus-commercekit' ); ?>" /><br /><br />
				<label class="cgkit-video-dialog-autoplay"><input type="checkbox" id="cgkit-video-dialog-autoplay" name="cgkit_video_dialog_autoplay" value="1" <?php echo 1 === $glob_auto_play ? 'checked="checked"' : ''; ?>/>&nbsp;<?php esc_html_e( 'Autoplay video?', 'commercegurus-commercekit' ); ?></label>
				<input type="button" id="browse-media-library" name="browse-media-library" value="<?php esc_html_e( 'Browse from Media Library', 'commercegurus-commercekit' ); ?>" data-choose="<?php esc_html_e( 'Add video to Product Gallery', 'commercegurus-commercekit' ); ?>" data-update="<?php esc_html_e( 'Add to gallery', 'commercegurus-commercekit' ); ?>" data-delete="<?php esc_html_e( 'Delete video', 'commercegurus-commercekit' ); ?>" data-text="<?php esc_html_e( 'Delete', 'commercegurus-commercekit' ); ?>"/><br /><br />
				<small class="form-text text-muted"><?php esc_html_e( 'Insert or replace the current video URL.', 'commercegurus-commercekit' ); ?></small>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var cgkit_video_dialog_title = '<?php esc_html_e( 'CommerceKit Product Gallery Video', 'commercegurus-commercekit' ); ?>';
var cgkit_video_dialog_close = '<?php esc_html_e( 'Close', 'commercegurus-commercekit' ); ?>';
var cgkit_video_dialog_remove = '<?php esc_html_e( 'Remove', 'commercegurus-commercekit' ); ?>';
var cgkit_video_dialog_save = '<?php esc_html_e( 'Save Video', 'commercegurus-commercekit' ); ?>';
var cgkit_video_dialog_auto_play = '<?php echo esc_attr( $glob_auto_play ); ?>';
</script>
