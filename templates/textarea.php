<?php
/**
* Generates Textarea Field
* @since Easy Post Type 1.0
*/
?>
<div class="easy-post-type-textarea">
	<?php  echo wp_kses_post( $label ) . wp_kses_post( $description ); ?>
	<p>
		<textarea class="widefat" rows="7" name="<?php echo esc_attr( $id ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
	</p>
</div>