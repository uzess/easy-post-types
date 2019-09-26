<?php
/**
* Provides a framework for adding meta field  for  terms
* @since Easy Post Type 1.0
*/
if( !class_exists( 'Easy_Post_Type_Term_Meta' ) ):
	class Easy_Post_Type_Term_Meta extends Easy_Post_Type_Base{

		public $taxonomy = null;
		public $fields   = null;

		public function __construct( $tax ){

			parent::__construct();
			$this->taxonomy = $tax;

			add_action( 'created_category', array ( $this, 'save' ), 10, 2 );
			add_action( 'edited_category',  array ( $this, 'save' ), 10, 2 );
		}

		public function add_fields( $fields ){

			$this->fields = $fields;
			add_action( $this->taxonomy.'_add_form_fields', array( $this, 'render_meta' ), 10, 2 );
			add_action( $this->taxonomy.'_edit_form', array( $this, 'render_meta' ), 10, 2 );
		}

		public function render_meta( $param_1, $param_2 = null ){
			if( is_array( $this->fields ) ){

				wp_nonce_field( 'easy_post_type_meta_nonce', 'name_meta_nonce' );

				foreach ( $this->fields as $key => $field ) {
					
					$field[ 'id' ] = $key;

					if( null == $param_2 ){
						# Creating New Term
						$value = '';
					}else{
						# Editing the Term
						$value = get_term_meta( $param_1->term_id, $key, true );
					}

					$field[ 'value' ] = $this->get_value( $value, $field );

					$this->render_field( $field );
				}
			}	
		}

		public function save( $term_id, $tt_id ){

			//Don't update on Quick Edit
			if (defined('DOING_AJAX') ) {
				return $post_id;
			}
			
			$p = wp_unslash( $_POST );
			
			if ( empty( $p ) || !isset(  $p[ 'name_meta_nonce' ] ) || !wp_verify_nonce( $p[ 'name_meta_nonce' ], 'easy_post_type_meta_nonce' ) ) {
			    return;
			}

			foreach( $this->fields as $key => $field ){

				$value = $this->sanitize( array( 
					'type'  => $field[ 'type' ],
					'value' => $p[ $key ]
				) );

				update_term_meta( $term_id, $key, $value );
			}
		}
	}
endif;