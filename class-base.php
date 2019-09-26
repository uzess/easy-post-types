<?php
/**
* A base framework for post type and term meta creation
* @since Easy Post Type 1.0
*/

if( !class_exists( 'Easy_Post_Type_Base' ) ):

	class Easy_Post_Type_Base{

		public function __construct(){
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		}

		public function enqueue( $scripts ){

		    # Do not enqueue anything if no array is supplied.
		    if( ! is_array( $scripts ) ) return;

		    $scripts = apply_filters( 'easy_post_type_scripts' , $scripts );

		    foreach ( $scripts as $script ) {

		        # Do not try to enqueue anything if handler is not supplied.
		        if( ! isset( $script[ 'handler' ] ) )
		            continue;

		        # use get_theme_file_uri(). So that child theme can overwrite it.
		        # @link  https://developer.wordpress.org/reference/functions/get_theme_file_uri/

		        $version = null;
		        if( isset( $script[ 'version' ] ) ){
		            $version = $script[ 'version' ];
		        }

		        # Enqueue each vendor's style
		        if( isset( $script[ 'style' ] ) ){
		            
		            $path = get_theme_file_uri( '/assets/vendors/' . $script[ 'style' ] );
		            if( isset( $script[ 'absolute' ] ) ){
		                $path = $script[ 'style' ];
		            }

		            $dependency = array();
		            if( isset( $script[ 'dependency' ] ) ){
		                $dependency = $script[ 'dependency' ];
		            }
		            wp_enqueue_style( $script[ 'handler' ], $path, $dependency, $version );
		        }

		        # Enqueue each vendor's script
		        if( isset( $script[ 'script' ] ) ){

		            $path = get_theme_file_uri( '/assets/vendors/' . $script[ 'script' ] );
		            if( isset( $script[ 'absolute' ] ) ){
		                $path = $script[ 'script' ];
		            }

		            $dependency = array( 'jquery' );
		            if( isset( $script[ 'dependency' ] ) ){
		                $dependency = $script[ 'dependency' ];
		            }

		            $prefix = '';
		            if( isset( $script[ 'prefix' ] ) ){
		                $prefix = $script[ 'prefix' ];
		            }
		            wp_enqueue_script( $prefix . $script[ 'handler' ], $path, $dependency, $version, true );
		        }
		    }
		}

		public function scripts(){
			wp_enqueue_media();
			$scripts = array(
				array(
					'handler' => 'easy-post-type-style',
					'absolute' => true,
					'style'    => get_theme_file_uri( '/easy-post-types/assets/main.css' ),
				),
				array(
					'handler' => 'easy-post-type-script',
					'absolute' => true,
					'script'   => get_theme_file_uri( '/easy-post-types/assets/main.js' ),
					'dependency' => array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-selectable' )
				),
			);
			
			$this->enqueue( $scripts );

			$locale = array(
				'caption' => esc_html__( 'Caption.', 'easy-post-type' ),
				'link'    => esc_html__( 'Link.', 'easy-post-type' ),
				'confirm_delete'   => esc_html__( 'Are you sure to delete?', 'easy-post-type' ),
				'no_select_notice' => esc_html__( 'Please Select atleast an item?', 'easy-post-type' ),
				'media_title'      => esc_html__( 'Choose an Image.', 'easy-post-type' ),
				'media_btn_text'   => esc_html__( 'Insert', 'easy-post-type' ),
				'media_btn_change_text' => esc_html__( 'Change Image', 'easy-post-type' ),
				'image_upload_text' => esc_html__( 'Select Image', 'easy-post-type' ),
			);

			wp_localize_script( 'easy-post-type-script', 'EPT', $locale );
		}

		public function get_value( $value, $field ){
			if( empty( $value ) && is_array( $field ) && isset( $field[ 'default' ] ) ){
				$value = $field[ 'default' ];
			}

			return $value;
		}

		public function render_field( $field ){

			if( is_array( $field ) && isset( $field[ 'type' ] ) ){

				$id = $field[ 'id' ];
				$value = $field[ 'value' ];

				$label = '';
				if( isset( $field[ 'label' ] ) )
					$label = '<p><label class="easy-post-type-label">'. esc_html( $field[ 'label' ] ).'</label></p>';

				$description = '';
				if( isset( $field[ 'description'] ) ){
					$description = '<p><i>'.esc_html( $field[ 'description'] ).'</i></p>';
				}

				$placeholder = '';
				if( isset( $field[ 'placeholder'] ) ){
					$placeholder = $field[ 'placeholder'];
				}

				switch( $field[ 'type' ] ){

					case 'separator':
						include get_theme_file_path( 'easy-post-types/templates/separator.php' );
					break;
					case 'select':
						include get_theme_file_path( 'easy-post-types/templates/select.php' );
					break;

					case 'image':

						$d = array(
							'holder'  => 'thumb-'  . $id,
							'delete'  => 'delete-' . $id,
							'setting' => 'input-'  . $id
						);
						
						$json = json_encode( $d );
						$upload_btn_text = esc_html__( 'Change Image', 'easy-post-type' );

						if( empty( $value ) )
							$upload_btn_text = esc_html__( 'Select Image', 'easy-post-type' );

						include get_theme_file_path( 'easy-post-types/templates/image.php' );
					break;

					case 'checkbox':
						include get_theme_file_path( 'easy-post-types/templates/checkbox.php' );
					break;

					case 'textarea':
						include get_theme_file_path( 'easy-post-types/templates/textarea.php' );
						
					break;

					case 'line':
						echo '<hr/>';
					break;

					case 'repeater':
					
						$fields   = $field[ 'fields' ];
						$setting  = $id;
						$data     = $field[ 'value' ];
						$btn_text = $field[ 'label' ];

						$media = false;
						if( isset( $field[ 'media' ] ) && $field[ 'media' ] ){
							# Treat it as an extra field => type: image.
							$media = true;
							$fields = array_merge(array( 'media' => array( 'type' => 'image' ) ), $fields );
						}

						if( is_array( $fields ) && count( $fields ) > 0 ){
							include get_theme_file_path( 'easy-post-types/templates/repeater.php' );
						}
					break;

					default :
						include get_theme_file_path( 'easy-post-types/templates/text.php' );
					break;
				}
			}
		}

		public function sanitize( $field ){

			$value = $field[ 'value' ];

			switch( $field[ 'type' ] ){

				case 'text':
				case 'select':
			        $value = wp_kses_post( $value );
			    break;
			    
				case 'email':
				    $value = sanitize_email( $value );
				break;

				case 'url':
					$value = esc_url_raw( $value ); 
				break;

				case 'image':
					$value = absint( $value );
				break;

				case 'textarea':
					$value = wp_kses_post( $value );
			    break;

				case 'checkbox':
	    			$value = ! empty( $value );
	    		break;

	    		case 'repeater':

	    		break;

				default :
					
				break;
			}

			return $value;
		}

		public function beautify( $string ){
		    return ucwords( str_replace( '_', ' ', $string ) );
		}

		public function uglify( $string ){
		    return strtolower( str_replace( ' ', '_', $string ) );
		}

		public function pluralize( $string ){

			$last = $string[ strlen( $string ) - 1 ];

			if( $last == 'y' ){

			  $cut = substr( $string, 0, -1 );
			  # convert y to ies
			  $plural = $cut . 'ies';
			} elseif ( 's' == $last ) {

			  $plural = $string;
			} else{

			  # fjust attach an s
			  $plural = $string . 's';
			}

			return $plural;
		}

	}
endif;