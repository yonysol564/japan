<?php
// Register Branch Post Type
function branches_post_type() {

	$labels = array(
		'name'                  => _x( 'סניפים', 'Post Type General Name', 'qstheme' ),
		'singular_name'         => _x( 'סניף', 'Post Type Singular Name', 'qstheme' ),
		'menu_name'             => __( 'סניפים', 'qstheme' ),
		'name_admin_bar'        => __( 'סניפים', 'qstheme' ),
		'archives'              => __( 'Item Archives', 'qstheme' ),
		'parent_item_colon'     => __( 'Parent Item:', 'qstheme' ),
		'all_items'             => __( 'All Items', 'qstheme' ),
		'add_new_item'          => __( 'Add New Item', 'qstheme' ),
		'add_new'               => __( 'Add New', 'qstheme' ),
		'new_item'              => __( 'New Item', 'qstheme' ),
		'edit_item'             => __( 'Edit Item', 'qstheme' ),
		'update_item'           => __( 'Update Item', 'qstheme' ),
		'view_item'             => __( 'View Item', 'qstheme' ),
		'search_items'          => __( 'Search Item', 'qstheme' ),
		'not_found'             => __( 'Not found', 'qstheme' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'qstheme' ),
		'featured_image'        => __( 'Featured Image', 'qstheme' ),
		'set_featured_image'    => __( 'Set featured image', 'qstheme' ),
		'remove_featured_image' => __( 'Remove featured image', 'qstheme' ),
		'use_featured_image'    => __( 'Use as featured image', 'qstheme' ),
		'insert_into_item'      => __( 'Insert into item', 'qstheme' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'qstheme' ),
		'items_list'            => __( 'Items list', 'qstheme' ),
		'items_list_navigation' => __( 'Items list navigation', 'qstheme' ),
		'filter_items_list'     => __( 'Filter items list', 'qstheme' ),
	);
	$args = array(
		'label'                 => __( 'סניף', 'qstheme' ),
		'description'           => __( 'סניף', 'qstheme' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'revisions', ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
        'menu_icon'             => 'dashicons-image-filter',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'branch',
		'map_meta_cap'          => true,
	);
	register_post_type( 'branch', $args );

}
add_action( 'init', 'branches_post_type', 0 );
