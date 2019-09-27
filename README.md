# Easy Post Types
This is a light weight framework to create Post Types, Taxonomy, Custom Fields in Posts &amp; Terms.

### Installation
1. Upload the `easy-post-types` folder to your `/wp-content/themes/` directory 
2. Activate it by including it in you functions.php
```php
require_once get_theme_file_path( '/easy-post-types/loader.php' );
````
### Example
Create a post type called `team`.
```php
	$team = new Easy_Post_Type( 'team', array(
		'menu_icon'    => 'dashicons-groups',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	),array(
		'menu_name' => esc_html__( 'Our Team', 'text-domain' )
	));

	$team->add_meta_box( esc_html__( 'Team Options', 'text-domain' ), array(
		'tab_1' => array(
			'title' => array(
				'label' => esc_html__( 'Title', 'text-domain' ),
				'type'  => 'text',
			),
			'page' => array(
				'label' => esc_html__( 'Add Achievement', 'text-domain' ),
				'type' => 'repeater',
				'media' => true, // Remove this if you don't need image
				'fields' => array(
					'caption' => array(
						'label' => esc_html__( 'Caption', 'text-domain' ),
						'type'  => 'text'
					)
				)
			)
		),
		'tab_2' => array(
			'description' => array(
				'label' => __( 'Description', 'text-domain' ),
				'type' => 'textarea'
			),
			'country' => array(
				'label' => __( 'Select Country', 'text-domain' ),
				'type' => 'select',
				'choices' => array(
					'nepal' => __( 'Nepal', 'text-domain' ),
					'spain' => __( 'Spain', 'text-domain' )
				)
			)
		)
	), 'normal', 'high' );
```
