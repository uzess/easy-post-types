<?php
/**
* Generates Repeater Fields
* @since Easy Post Type 1.0
*/
?>
<div id="easy-post-type-repeater-<?php echo esc_attr( $setting ); ?>">
	<?php echo wp_kses_post( $description ); ?>
	<div class="easy-post-type-repeater clearfix <?php echo $media ? esc_attr( 'easy-post-type-gallery' ) : ''; ?>">
		<?php
			if( $data ){
				$counter = 0; 
				foreach( $data[ array_keys($data)[0] ] as $key => $val ){
					?>
					<div class="easy-post-type-repeater-group">
						<div class="easy-post-type-icons-outer clearfix">
							<span class="handle dashicons dashicons-move"></span>
							<span class="selector dashicons dashicons-yes"></span>
						</div>
						<div class="easy-post-type-repeater-fields">
							<?php
							foreach( $fields as $id => $f ){

								$type = $f[ 'type' ];

								$label = '';
								if( isset( $f[ 'label' ] ) ){
									$label = $f[ 'label' ];
								}

								$placeholder = '';
								if( isset( $f[ 'placeholder' ] ) ){
									$placeholder = $f[ 'placeholder' ];
								}

								$name       = $setting.'['. $id. '][]';
								$cur_value  = null;

								if( isset( $data[$id] ) ){
									$cur_value = $data[$id][$counter];
								}

								switch( $type ){
									case 'image':
									?>
										<div class="media">
											<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $cur_value ); ?>">
											<?php echo wp_get_attachment_image( $cur_value, 'thumbnail' ); ?>
										</div>
									<?php
									break;
									case 'checkbox':
										/**
										* Since unchecked input is not submitted in posts, hidden input is used
										* Be careful not to have any spaces or linebreaks between this two input elements!
										* this.previousSibling will get whitespace as sibling, so it wont work.
										*/
									?>
										<p>
											<label>
												<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $cur_value ); ?>"><input type="checkbox" <?php checked( '1', $cur_value, true ); ?> value="1" onclick="this.previousSibling.value=1-this.previousSibling.value">
												<?php echo esc_html( $label ); ?>
											</label>
										</p>
									<?php
									break;

									case 'select':
									?>
										<?php if( !empty( $label ) ): ?>
										<p><label><?php echo esc_html( $label ); ?></label></p>
										<?php endif; ?>
										<p>
											<select class="widefat" name="<?php echo esc_attr( $name ); ?>">
												<?php foreach( $f[ 'choices' ] as $c_value => $c_label ): ?>
													<option value="<?php echo $c_value; ?>" <?php selected( $cur_value, $c_value ); ?>>
														<?php echo $c_label; ?>
													</option>
												<?php endforeach; ?>
											</select>
										</p>
									<?php
									break;

									case 'textarea':
									?>
										
										<?php if( !empty( $label ) ): ?>
										<p><label><?php echo esc_html( $label ); ?></label></p>
										<?php endif; ?>
										<p><textarea class="widefat" name="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_html( $placeholder ); ?>"><?php echo esc_textarea($cur_value); ?></textarea></p>
										
									<?php
									break;
									default:
									?>
										<?php if( !empty( $label ) ): ?>
											<p><label><?php echo esc_html( $label ); ?></label></p>
										<?php endif; ?>
										<p><input type="<?php echo esc_attr( $type ); ?>" class="widefat" name="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo esc_attr($cur_value); ?>"></p>
									<?php	
									break;
								}
							}
							?>
						</div>
					</div>
					<?php
					$counter++;
				}
			}
		?>
	</div>
</div>

<div class="easy-post-type-btn-group clearfix">
	<button class="easy-post-type-add-repeater button button-primary" data-setting="<?php echo esc_attr( $setting ); ?>" data-media="<?php echo esc_attr( $media ); ?>" data-fields="<?php echo esc_attr( json_encode($fields) ) ?>">
		<?php echo esc_html( $btn_text ); ?>
	</button>
	<button class="easy-post-type-del-repeater button button-delete">
		<?php esc_html_e( 'Delete', 'easy-post-type' ); ?>
	</button>
</div>