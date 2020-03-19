<?php
/**
* Provides a framework for creating post types, taxonomies and meta field
* @since Easy Post Type 1.0
*/
# Make a separate class for Registering and dealing with custom post types.
if( !class_exists( 'Easy_Post_Type' ) ):
class Easy_Post_Type{
	
	# Stores Post Type Name.
	public $post_type;

	# Stores Post Type Arguments.
	public $post_type_args;

	#Stores Post Type Labels.
	public $post_type_labels;

	# Stores Taxonomy Name.
	public $taxonomy = array();

	# Stores Taxonomy Arguments.
	public $taxonomy_args = array();

	# Stores info about meta boxes added by user.
	public $meta_boxes = array();

	public function __construct( $name, $args = array(), $labels = array() ){
	    
	    # Initializing Variables
	    $this->post_type        = $this->uglify( $name ); 
	    $this->post_type_args   = $args;
	    $this->post_type_labels = $labels;

	    # Add action to register the post type, if the post type does not already exist
	    if( ! post_type_exists( $this->post_type ) ){
	      add_action( 'init', array( $this, 'register_post_type' ) );
	    }
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

		foreach ($this->taxonomy as $key => $tax ) {
			if( taxonomy_exists( $tax ) ){

				register_taxonomy_for_object_type( $tax, $this->post_type );
			}else{

				register_taxonomy( $tax, $this->post_type, $this->taxonomy_args[ $key ] );
			}
		}
	}

	# Add Taxonomy in init hook
	public function add_taxonomy( $name, $args = array(), $labels = array() ){

		if( ! empty( $name ) ){

			$this->taxonomy[] = $this->uglify( $name );

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
	           'show_in_rest'	   => true
            );

            $this->taxonomy_args[] = array_merge( $defaults, $args );

        	# Add the taxonomy to the post type
        	add_action( 'init', array( $this, 'register_taxonomy' ) );  
		}
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
