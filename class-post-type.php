<?php
/**
* Provides a framework for creating post types, taxonomies and meta field
* @since Easy Post Type 1.0
*/
# Make a separate class for Registering and dealing with custom post types.
if( !class_exists( 'Easy_Post_Type' ) ):
class Easy_Post_Type extends Easy_Post_Type_Base{
	
	# Stores Post Type Name.
	public $post_type;

	# Stores Post Type Arguments.
	public $post_type_args;

	#Stores Post Type Labels.
	public $post_type_labels;

	# Stores Taxonomy Name.
	public $taxonomy;

	# Stores Taxonomy Arguments.
	public $taxonomy_args;

	# Stores info about meta boxes added by user.
	public $meta_boxes = array();

	public function __construct( $name, $args = array(), $labels = array() ){
	    
	    parent::__construct();

	    # Initializing Variables
	    $this->post_type        = $this->uglify( $name ); 
	    $this->post_type_args   = $args;
	    $this->post_type_labels = $labels;

	    # Add action to register the post type, if the post type does not already exist
	    if( ! post_type_exists( $this->post_type ) ){
	      add_action( 'init', array( $this, 'register_post_type' ) );
	    }

	    # Listen for the save post hook
	    add_action( 'save_post', array( $this, 'save' ) );
	}

	# Registers Custom Post Type 
	public function register_post_type(){

	    if( isset( $this->post_type_labels[ 'menu_name' ] ) ){

	    	$plural = $this->pluralize( $this->post_type_labels[ 'menu_name' ] );
	    	$name = $this->post_type_labels[ 'menu_name' ];
	    }else{

	    	# Capitilize the words and make it plural
	    	$name   = $this->beautify( $this->post_type );
	    	$plural = $this->pluralize( $name );
	    }

	    # We set the default labels based on the post type name and plural. 
	    # We overwrite them with the given labels.

	    $defaults = array(
			'name'               => esc_html( $plural ),
			'singular_name'      => esc_html( $name ),
			'add_new'            => esc_html__( 'Add New ', 'easy-post-type' ),
			'add_new_item'       => esc_html__( 'Add New ', 'easy-post-type' ) . esc_html( $name ),
			'edit_item'          => esc_html__( 'Edit ', 'easy-post-type' ) . esc_html( $name ),
			'new_item'           => esc_html__( 'New ', 'easy-post-type' ) . esc_html( $name ),
			'all_items'          => esc_html__( 'All ', 'easy-post-type' ) . esc_html( $plural ),
			'view_item'          => esc_html__( 'View ', 'easy-post-type' ) . esc_html( $name ),
			'search_items'       => esc_html__( 'Search ', 'easy-post-type' ) . esc_html( $plural ),
			'not_found'          => esc_html__( 'No ', 'easy-post-type') . esc_html( strtolower( $plural ) ) . esc_html__( ' found', 'easy-post-type' ),
			'not_found_in_trash' => esc_html__( 'No ', 'easy-post-type') . esc_html( strtolower( $plural ) ) . esc_html__( ' found in Trash', 'easy-post-type' ), 
			'menu_name'          => esc_html( $plural )
		);

	    # merge the default labels with the labels give by user
	    $labels = array_merge( $defaults, $this->post_type_labels );

	    # Same principle as the labels. We set some defaults and overwrite them with the given arguments.
	    $defaults = array(
		      'label'             => $plural,
		      'labels'            => $labels,
		      'public'            => true,
		      'show_ui'           => true,
		      'supports'          => array( 'title', 'editor' ),
		      'show_in_nav_menus' => true,
		      '_builtin'          => false,
		      'show_in_rest'      => true,
		      'has_archive'       => true
		);

	    $args = array_merge( $defaults, $this->post_type_args );

	    # Register the post type
	    register_post_type( $this->post_type, $args );   
	}

	# Registers Taxonomy
	# Hooked @init
	public function register_taxonomy(){

		if( taxonomy_exists( $this->taxonomy ) ){

			register_taxonomy_for_object_type( $this->taxonomy, $this->post_type );
		}else{

			register_taxonomy( $this->taxonomy, $this->post_type, $this->taxonomy_args );
		}
	}

	# Add Taxonomy in init hook
	public function add_taxonomy( $name, $args = array(), $labels = array() ){

		if( ! empty( $name ) ){

			$this->taxonomy = $this->uglify( $name );

			if( isset( $labels[ 'menu_name'] ) ){
				$name   = $labels[ 'menu_name'];
	            $plural = $this->pluralize( $labels[ 'menu_name'] );
			}else{
				$name   = $this->beautify( $name );
	            $plural = $this->pluralize( $name );
			}
			
			# Default labels, overwrite them with the given labels.
			$defaults = array(
	           'name'              => esc_html( $plural ),
	           'singular_name'     => esc_html( $name ),
	           'search_items'      => esc_html__( 'Search ', 'easy-post-type' ) . esc_html( $plural ),
	           'all_items'         => esc_html__( 'All ', 'easy-post-type' ) . esc_html( $plural ),
	           'parent_item'       => esc_html__( 'Parent ', 'easy-post-type' ) . esc_html( $name ),
	           'parent_item_colon' => esc_html__( 'Parent ', 'easy-post-type' ) . esc_html( $name ),
	           'edit_item'         => esc_html__( 'Edit ', 'easy-post-type' ) . esc_html( $name ),
	           'update_item'       => esc_html__( 'Update ', 'easy-post-type' ) . esc_html( $name ),
	           'add_new_item'      => esc_html__( 'Add New ', 'easy-post-type' ) . esc_html( $name ),
	           'new_item_name'     => esc_html__( 'New ', 'easy-post-type' ) . esc_html( $name ),
	           'menu_name'         => esc_html( $name ),
            );

            $labels = array_merge( $defaults, $labels );

            # Default arguments, overwritten with the given arguments
            $defaults = array(
	           'label'             => $plural,
	           'labels'            => $labels,
	           'hierarchical'      => true,
	           'public'            => true,
	           'show_ui'           => true,
	           'show_in_nav_menus' => true,

            );

            $this->taxonomy_args = array_merge( $defaults, $args );

        	# Add the taxonomy to the post type
        	add_action( 'init', array( $this, 'register_taxonomy' ) );  
		}
	}

	# Stores all the meta boxes into the array and add it for registration.
	public function add_meta_box( $title, $fields = array(), $context = 'normal', $priority = 'default' ){

		$boxes = array(
			'id'       => $this->uglify( $title ),
			'title'    => $this->beautify( $title ),
			'fields'   => $fields,
			'context'  => $context,
			'priority' => $priority
		);

		$this->meta_boxes[] = $boxes;

		add_action( 'add_meta_boxes_' . $this->post_type, array( $this, 'register_meta_box' ) );
	}

	# Registers all the meta boxes from the array.
	public function register_meta_box(){

		if( is_array( $this->meta_boxes ) ){
			foreach( $this->meta_boxes as $meta ){

				add_meta_box( $meta[ 'id' ], $meta[ 'title' ], array( $this, 'render_meta_box' ), $this->post_type, $meta[ 'context' ], $meta[ 'priority' ], $meta[ 'fields' ] );
			}
		}
	}

	public function render_meta_box( $post, $box ){

		if( is_array( $box[ 'args' ] ) ){

			wp_nonce_field( 'easy_post_type_meta_nonce', 'name_meta_nonce' );
			$class = 'single-mode';
		?>
			<div class="easy-post-type-meta-box-tab-wrapper clearfix">
				<?php if( count( $box[ 'args' ] ) > 1 ): $class = 'tab-mode'; ?>
					<div class="easy-post-type-meta-box-tab-menu">
						<ul>
							<?php foreach( $box[ 'args' ] as $section => $fields ): ?>
								<li>
									<a href="#<?php echo esc_attr( $this->uglify( $section ) ); ?>" class="easy-post-type-rel-tab">
										<?php echo esc_html( $this->beautify( $section ) ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<div class="<?php echo esc_attr( $class ); ?> easy-post-type-meta-box-tab-content">
					<section>
					<?php $count = 0; foreach( $box[ 'args' ] as $section => $fields ): ?>
						<div id="<?php echo esc_attr( $this->uglify( $section ) ); ?>" class="<?php echo $count !== 0 ? esc_attr( 'hidden' ) : ''; ?>">
							<?php 
								foreach( $fields as $key => $field ){
									$field[ 'id' ]    = $key;
									$field[ 'post' ]  = $post;
									$field[ 'value' ] = $this->get_value( get_post_meta( $field[ 'post' ]->ID, $field[ 'id' ], true ), $field );
									$this->render_field( $field );
								} 
							?>
						</div>
					<?php $count++; endforeach; ?>
					</section>
				</div>
				<div style="clear:both"></div>
			</div>
			<?php
		}
	}

	public function save( $post_id ){
      	
      	$p = wp_unslash( $_POST );
		if ( empty( $p ) || ! isset(  $p[ 'name_meta_nonce' ] ) || ! wp_verify_nonce( $p[ 'name_meta_nonce' ], 'easy_post_type_meta_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		//Don't update on Quick Edit
		if (defined('DOING_AJAX') ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( $this->post_type === $p[ 'post_type' ] ) {

			# do stuff
			foreach( $this->meta_boxes as $meta ){
				foreach( $meta[ 'fields' ] as $section => $fields){
					foreach( $fields as $id => $field ){

						if( 'separator' == $field[ 'type' ] )
							continue;
						$value = $this->sanitize( array(
							'type'  => $field[ 'type' ],
							'value' => $p[ $id ]
						));

						update_post_meta( $post_id, $id, $value );
					} 
				}
			}
		}
	}
}
endif;

if( !function_exists( 'easy_post_type_transpose' ) ):
	/**
	* Transpose an array
	* @since Easy Post Typ 1.0
	*/
	function easy_post_type_transpose($array) {
	    array_unshift($array, null);
	    return call_user_func_array( 'array_map', $array );
	}
endif;

if( !function_exists( 'easy_post_type_get_repeater_data' ) ):
	/**
	* Get the postmeta of type repeater
	* @uses easy_post_type_transpose()
	* @since Easy Post Type 1.0
	*/
	function easy_post_type_get_repeater_data( $id ){

		if( empty( $id ) )
			return;

		$data = array();
		$repeater = get_post_meta( get_the_ID(), $id, true );

		if( is_array( $repeater ) && count( $repeater ) > 0 ){

			$fields = array_keys( $repeater );
			foreach( easy_post_type_transpose( $repeater ) as $key => $value ){
				foreach( $value as $k => $v ){
					$data[ $key ][ $fields[ $k ] ] = $v;
				}
			}
		}

		return $data;
	}
endif;