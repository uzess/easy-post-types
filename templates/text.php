<?php
/**
* Generates Text Field
* @since Easy Post Type 1.0
*/
?>
<div class="easy-post-type-text">
	<?php  echo wp_kses_post( $label ) . wp_kses_post( $description ); ?>
	<p>
		<input type="<?php echo esc_attr( $field[ 'type' ] ); ?>" class="widefat" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $id ); ?>" placeholder="<?php echo esc_attr( $placeholder ) ?>">
	</p>
</div>