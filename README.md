# Easy Post Types
This is a light weight framework to create Post Types, Taxonomy, Custom Fields in Posts &amp; Terms.

### Installation
1. Upload the `easy-post-types` folder to your `/wp-content/themes/` directory 
2. Activate it by including it in you functions.php
```php
require_once get_theme_file_path( '/easy-post-types/loader.php' );
````
### Example
Create a post type called `team` & add custom fields.
### Screenshot
![alt ease-post-types-screenshot](https://github.com/uzess/easy-post-types/blob/master/screenshot.jpg)

```php
$team = new Easy_Post_Type( 'team', array(
	'menu_icon'    => 'dashicons-groups',
	'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
),array(
	'menu_name' => esc_html__( 'Our Team', 'text-domain' )
));

$fields = array(
    /* Tab General */
	'general' => array(
		'comming_soon' => array(
			'label' => esc_html__( 'Enable Comming Soon mode.', 'text-domain' ),
			'type'  => 'checkbox',
		),
		'linked_theme' => array(
			'label' => esc_html__( 'Link Theme', 'text-domain' ),
			'type'  => 'text',
			'description' => esc_html__( 'Add theme id you want to link', 'text-domain' )
		),
		'add_star' => array(
			'label' => esc_html__( 'Add Star on Title', 'text-domain' ),
			'type' => 'checkbox'
		),
		'sub_title' => array(
			'label' => esc_html__( 'Sub Title', 'text-domain' ),
			'description' => esc_html__( 'This text will be visible below the banner purchase button.', 'text-domain' ),
			'type'  => 'text',
		),
		'version' => array(
			'label' => esc_html__( 'Current Version', 'text-domain' ),
			'type'  => 'text'
		),
		'created_on' => array(
			'label' => esc_html__( 'Created Date', 'text-domain' ),
			'type'  => 'date'
		),
		'updated_on' => array(
			'label' => esc_html__( 'Last Updated', 'text-domain' ),
			'type'  => 'date'
		),
		'documentation_link' => array(
	 		'label' => esc_html__( 'Documentation Link', 'text-domain' ),
	 		'type'  => 'text'
		),
		'support_link' => array(
			'label' => esc_html__( 'Support Link', 'text-domain' ),
			'type'  => 'text'
		),
		'download_link' => array(
			'label' => esc_html__( 'Download Link', 'text-domain' ),
			'type' => 'text',
		)
	),
    /* Tab Banner */
	'banner' => array(
		'slider_image' => array(
			'label' => esc_html__( 'Select Image for Home Slider', 'text-domain' ),
			'type'  => 'image',
		),
	),
    /* Tab Demo */
	'demo' => array(
		'use_linked_theme_demo' => array(
			'label' => esc_html__( 'Treat as a linked theme', 'text-domain' ),
			'description' => esc_html__( 'Use case: If your linked theme is Bizplan pro, then demo for this theme will be treated as the demo of Bizplan pro.That means when you purchase this theme from demo page, Bizplan Pro will be added to cart.', 'text-domain' ),
			'type'  => 'checkbox',
		)
	),
    /* Tab Premium Theme */
	'premium_theme' => array(
		'is_premium' => array(
			'label' => esc_html__( 'is Premium?', 'text-domain' ),
			'type'  => 'checkbox'
		)
	),
    /* Tab Multiple Demo */
	'multiple_demo' => array(
		'demos' => array(
			'label' => esc_html__( 'Add Demo', 'text-domain' ),
			'type' => 'repeater',
			'media' => true,
			'fields' => array(
				'caption' => array(
					'type' => 'text',
					'placeholder' => esc_html__( 'Label', 'text-domain' )
				),
				'link' => array(
					'type' => 'text',
					'placeholder' => esc_html__( 'Link', 'text-domain' )
				)
			)
		)
	),
    /* Tab Closer Look */
	'closer_look' => array(
		'closer_look_gallery' => array(
			'label' => esc_html__( 'Add Images', 'text-domain' ),
			'type'  => 'repeater',
			'media' => true,
			'fields' => array(
				'caption' => array(
					'type' => 'text',
					'placeholder' => esc_html__( 'Label', 'text-domain' )
				)	
			)
		),
	),
    /* Tab Compare Table */
	'compare_table' => array(
		'compare_sub_title' => array(
			'type' => 'text',
			'label' => esc_html__( 'Sub Title', 'text-domain' ),
		)
	),
    /* Tab Additional Information */
	'additional_information' => array(
		'additional_info' => array(
			'label' => esc_html__( 'Other Information', 'text-domain' ),
			'type'  => 'repeater',
			'fields' => array(
				'title' => array(
					'type' => 'text',
					'label' => esc_html__( 'Title', 'text-domain' )
				),
				'description' => array(
					'type' => 'textarea',
					'label' => esc_html__( 'Description', 'text-domain' ) 
				)
			)
		),
	),
    /* Tab Change Log */
	'changes_log' => array(
		'change_log' => array(
			'type' => 'repeater',
			'label' => esc_html__( 'Add Change Log', 'text-domain' ),
			'fields' => array(
				'title' => array(
					'type' => 'text',
					'label' => esc_html__( 'Version', 'text-domain' )
				),
				'description' => array(
					'type' => 'textarea',
					'label' => esc_html__( 'Change Log', 'text-domain' ) 
				)
			)
		)
	)
);
$team->add_meta_box( esc_html__( 'Team Options', 'text-domain' ), $fields );
```
