<?php
/**
 * Template for displaying the Textarea Field
 *
 * @author MoreConvert
 * @package MoreConvert Options plugin
 * @version 1.0.0
 */

/**
 * Template variables:
 *
 * @var $name                      string Field name
 * @var $class                     string Field class
 * @var $field_id                  string Field Id
 * @var $value                     string Field value
 * @var $data                      string Data attributes
 * @var $custom_attributes         string Custom attributes
 * @var $dependencies                 string Dependencies
 * @var $desc                      string Description
 * @var $field                     array Array of all field attributes
 * @var $translatable              bool
 * @var $options_id                string option id
 * @var $section                   string section name
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="d-flex gap-5">
	<textarea id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="regular-text <?php echo esc_attr( $class ); ?>"
	<?php echo wp_kses_post( $dependencies ); ?>
		<?php echo wp_kses_post( $custom_attributes ); ?>
		<?php echo isset( $data ) ? wp_kses_post( $data ) : ''; ?>><?php echo esc_textarea( $value ); ?></textarea>
	<?php if ( ! empty( $translatable ) && true === $translatable && defined( 'WPML_ST_VERSION' ) ) : ?>
		<?php
		$url = add_query_arg(
			array(
				'page'    => 'wpml-string-translation/menu/string-translation.php',
				'context' => esc_attr( 'admin_texts_' . $options_id ),
				'search'  => esc_attr( ! empty( $section ) ? '[' . esc_attr( $options_id ) . '][' . esc_attr( $section ) . ']' . esc_attr( $name ) : '[' . esc_attr( $options_id ) . ']' . esc_attr( $name ) ),
			),
			admin_url( 'admin.php' )
		);
		?>
		<a href="<?php echo esc_url( $url ); ?>" class="btn-secondary min-pad btn-translation"  target="_blank" style="text-align:center;display: inline-block;vertical-align: middle;"><span class="dashicons dashicons-translation"></span></a>
	<?php endif; ?>
</div>
<?php if ( isset( $desc ) ) : ?>
	<p class="description"><?php echo wp_kses_post( $desc ); ?></p>
<?php endif; ?>
