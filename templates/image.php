<?php
/**
* Generates Image Field
* @since Easy Post Type 1.0
*/
?>
<div class="easy-post-type-image">
	<?php  echo wp_kses_post( $label ) . wp_kses_post( $description ); ?>
	<input type="hidden" id="<?php echo esc_attr( $d[ 'setting' ] ); ?>" name="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>" >

	<div class="easy-post-type-image-holder" id="<?php echo esc_attr( $d[ 'holder' ] ); ?>">
		<?php echo wp_get_attachment_image( $value, 'thumbnail' ); ?>
	</div>
</div>
<div class="easy-post-type-btn-group">
	<button type="button" data-required="<?php echo esc_attr( $json ); ?>" class="button easy-post-type-image-browse">
		<span class="wp-media-buttons-icon"></span>
		<span class="easy-post-type-image-btn-text"><?php echo esc_html( $upload_btn_text ); ?></span>
	</button>

	<button id="<?php echo esc_attr( $d[ 'delete' ] ); ?>" data-required="<?php echo esc_attr( $json ); ?>" class="easy-post-type-image-delete button <?php echo empty( $value )? esc_attr( 'hidden' ) : ''; ?>" >
		<?php echo esc_html__( 'Delete Image', 'easy-post-type' ); ?>
	</button>
</div>