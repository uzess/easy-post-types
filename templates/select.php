<?php
/**
* Generates Select Field
* @since Easy Post Type 1.0
*/
?>
<div class="easy-post-type-select">
	<?php  echo wp_kses_post( $label ) . wp_kses_post( $description ); ?>
	<p>
		<select name="<?php echo esc_attr( $id ); ?>" class="widefat">
			<?php foreach( $field[ 'choices' ] as $key => $option ): ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $value ); ?>>
					<?php echo esc_html( $option ); ?>
				</option>
			<?php endforeach; ?>
		</select>			
	</p>
</div>