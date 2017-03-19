<?php

/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function send_files_register_name() {

	$labels = array(
		'name'                => __( 'Send Files', 'send-files' ),
		'singular_name'       => __( 'Send File', 'send-files' ),
		'add_new'             => _x( 'Add New', 'Add new send file.', 'send-files' ),
		'add_new_item'        => __( 'Add New', 'send-files' ),
		'edit_item'           => __( 'Edit Send File', 'send-files' ),
		'new_item'            => __( 'New Send File', 'send-files' ),
		'view_item'           => __( 'View Send File', 'send-files' ),
		'search_items'        => __( 'Search Send Files', 'send-files' ),
		'not_found'           => __( 'No Send Files found', 'send-files' ),
		'not_found_in_trash'  => __( 'No Send Files found in Trash', 'send-files' ),
		'parent_item_colon'   => __( 'Parent Send File:', 'send-files' ),
		'menu_name'           => __( 'Send Files', 'send-files' ),
	);

	$args = array(
		'labels'                   => $labels,
		'hierarchical'        => true,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-format-aside',
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'title', 'editor', 'excerpt', 'custom-fields', 'trackbacks', 'comments',
		)
	);

	register_post_type( 'sendfiles_list', $args );
}

add_action( 'init', 'send_files_register_name' );