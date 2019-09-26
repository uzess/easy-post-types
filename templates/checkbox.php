<?php
/**
* Generates Checkbox Field
* @since Easy Post Type 1.0
*/
?>
<div class="easy-post-type-checkbox">
	<p>
		<label class="easy-post-type-label">
			<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $id ); ?>" <?php checked( '1', $value, true ); ?>>
			<?php echo esc_html( $field[ 'label' ] ); ?>
		</label>
	</p>
	<?php echo wp_kses_post( $description ); ?>
</div>
