<?php
/**
* Loads post type module
* @since MEverest 1.0
*/
require_once get_parent_theme_file_path( '/modules/post-types/class-base.php' );
require_once get_parent_theme_file_path( '/modules/post-types/class-post-type.php' );
require_once get_parent_theme_file_path( '/modules/post-types/class-term-meta.php' );

$theme = new MEverest_Post_Type( 'download', array(
	'rewrite'   => array( 'slug' => 'downloads' )
) );

$theme->add_meta_box( esc_html__( 'Options', 'meverest' ), array(
	'general' => array(
		'comming_soon' => array(
			'label' => esc_html__( 'Enable Comming Soon mode.', 'meverest' ),
			'type'  => 'checkbox',
		),
		'linked_theme' => array(
			'label' => esc_html__( 'Link Theme', 'meverest' ),
			'type'  => 'text',
			'description' => esc_html__( 'Add theme id you want to link', 'meverest' )
		),
		'add_star' => array(
			'label' => esc_html__( 'Add Star on Title', 'meverest' ),
			'type' => 'checkbox'
		),
		'sub_title' => array(
			'label' => esc_html__( 'Sub Title', 'meverest' ),
			'description' => esc_html__( 'This text will be visible below the banner purchase button.', 'meverest' ),
			'type'  => 'text',
		),
		'version' => array(
			'label' => esc_html__( 'Current Version', 'meverest' ),
			'type'  => 'text'
		),
		'created_on' => array(
			'label' => esc_html__( 'Created Date', 'meverest' ),
			'type'  => 'date'
		),
		'updated_on' => array(
			'label' => esc_html__( 'Last Updated', 'meverest' ),
			'type'  => 'date'
		),
		'documentation_link' => array(
	 		'label' => esc_html__( 'Documentation Link', 'meverest' ),
	 		'type'  => 'text'
		),
		'support_link' => array(
			'label' => esc_html__( 'Support Link', 'meverest' ),
			'type'  => 'text'
		),
		'download_link' => array(
			'label' => esc_html__( 'Download Link', 'meverest' ),
			'type' => 'text',
		)
	),
	'banner' => array(
		'slider_image' => array(
			'label' => esc_html__( 'Select Image for Home Slider', 'meverest' ),
			'type'  => 'image',
		),
		'banner_image' => array(
			'label' => esc_html__( 'Select Image for Inner Page Banner', 'meverest' ),
			'type'  => 'image',
		)
	),
	'demo' => array(
		'demo_name' => array(
			'label' => esc_html__( 'Title', 'meverest' ),
			'type'  => 'text',
			'description' => esc_html__( 'This is displayed on dropdown menu in theme demo.', 'meverest' )
		),
		'demo_link' => array(
			'label' => esc_html__( 'Link', 'meverest' ),
			'type'  => 'text',
			'description' => esc_html__( 'Insert a slug. for eg. reef or reeplus-grid.', 'meverest' )
		),
		'use_linked_theme_demo' => array(
			'label' => esc_html__( 'Treat as a linked theme', 'meverest' ),
			'description' => esc_html__( 'Use case: If your linked theme is Bizplan pro, then demo for this theme will be treated as the demo of Bizplan pro.That means when you purchase this theme from demo page, Bizplan Pro will be added to cart.', 'meverest' ),
			'type'  => 'checkbox',
		)
	),
	'premium_theme' => array(
		'is_premium' => array(
			'label' => esc_html__( 'is Premium?', 'meverest' ),
			'type'  => 'checkbox'
		),
		'discount_text' => array(
			'label' => esc_html__( 'Discount Text', 'meverest' ),
			'type' => 'text'
		)
	),
	'multiple_demo' => array(
		'demos' => array(
			'label' => esc_html__( 'Add Demo', 'meverest' ),
			'type' => 'repeater',
			'media' => true,
			'fields' => array(
				'caption' => array(
					'type' => 'text',
					'placeholder' => esc_html__( 'Label', 'meverest' )
				),
				'link' => array(
					'type' => 'text',
					'placeholder' => esc_html__( 'Link', 'meverest' )
				)
			)
		)
	),
	'closer_look' => array(
		'closer_look_gallery' => array(
			'label' => esc_html__( 'Add Images', 'meverest' ),
			'type'  => 'repeater',
			'media' => true,
			'fields' => array(
				'caption' => array(
					'type' => 'text',
					'placeholder' => esc_html__( 'Label', 'meverest' )
				)	
			)
		),
	),
	'compare_table' => array(
		'compare_sub_title' => array(
			'type' => 'text',
			'label' => esc_html__( 'Sub Title', 'meverest' ),
		),
		'compare_table' => array(
			'type' => 'textarea',
			'label' => esc_html__( 'Add Markup', 'meverest' )
		)
	),
	'additional_information' => array(
		'additional_info' => array(
			'label' => esc_html__( 'Other Information', 'meverest' ),
			'type'  => 'repeater',
			'fields' => array(
				'title' => array(
					'type' => 'text',
					'label' => esc_html__( 'Title', 'meverest' )
				),
				'description' => array(
					'type' => 'textarea',
					'label' => esc_html__( 'Description', 'meverest' ) 
				)
			)
		),
	),
	'changes_log' => array(
		'change_log' => array(
			'type' => 'repeater',
			'label' => esc_html__( 'Add Change Log', 'meverest' ),
			'fields' => array(
				'title' => array(
					'type' => 'text',
					'label' => esc_html__( 'Version', 'meverest' )
				),
				'description' => array(
					'type' => 'textarea',
					'label' => esc_html__( 'Change Log', 'meverest' ) 
				)
			)
		)
	)
));


$documentation =  new MEverest_Post_Type( esc_html__( 'documentation', 'meverest' ), array(
	'supports' => array( 'title' ),
	'menu_icon' => 'dashicons-media-code',
	'rewrite'   => array( 'slug' => 'doc' ),
) );

$documentation->add_meta_box( esc_html__( 'Options', 'meverest' ), array(
	'general' => array(
		'docs' => array(
			'type' => 'repeater',
			'label' => esc_html__( 'Add New', 'meverest' ),
			'fields' => array(
				'menu' => array(
					'type' => 'text',
					'placeholder' => esc_html__( 'Menu', 'meverest' )
				),
				'heading' => array(
					'type' => 'text',
					'placeholder' => esc_html__( 'Heading', 'meverest' )
				),
				'content' => array(
					'type' => 'textarea',
					'placeholder' => esc_html__( 'Content', 'meverest' )
				)
			)
		)
	)
) );