<?php
/**
* Loads post type module
* @since Easy Post Type 1.0
*/
require_once get_theme_file_path( '/easy-post-types/class-base.php' );
require_once get_theme_file_path( '/easy-post-types/class-post-type.php' );
require_once get_theme_file_path( '/easy-post-types/class-term-meta.php' );

$blocks = new Easy_Post_Type( 'Rise Blocks', array(
	'menu_icon'    => 'dashicons-groups',
	'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
),array(
	'menu_name' => esc_html__( 'Blocks', 'easy-post-type' )
));

$blocks->add_meta_box( esc_html__( 'Blocks Settings', 'easy-post-type' ), array(
	'demo' => array(
		'title' => array(
			'label' => esc_html__( 'Title', 'easy-post-type' ),
			'type'  => 'text',
		),
		'description' => array(
			'label' => esc_html__( 'Description', 'easy-post-type' ),
			'type' => 'textarea'
		),
		'page' => array(
			'label' => esc_html__( 'Add Achievement', 'easy-post-type' ),
			'type' => 'repeater',
			'fields' => array(
				'page_id' => array(
					'label' => esc_html__( 'Select Page', 'easy-post-type' ),
					'type'  => 'select',
					'choices' => array(
						'1' => 'One',
						'2' => 'Two'
					)
				)
			)
		)
	)
), 'normal', 'high' );

