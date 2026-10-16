<?php 
add_action( 'init', 'product_post_type', 0 );
function product_post_type() {

	$labels = array(
		'name'                => _x( 'Products', 'Post Type General Name', 'gia' ),
		'singular_name'       => _x( 'Products', 'Post Type Singular Name', 'gia' ),
		'menu_name'           => __( 'Products', 'gia' ),
		'all_items'           => __( 'All Products', 'gia' ),
		'view_item'           => __( 'View Product', 'gia' ),
		'add_new_item'        => __( 'Add New Product', 'gia' ),
		'add_new'             => __( 'Add New', 'gia' ),
		'edit_item'           => __( 'Edit Product', 'gia' ),
		'update_item'         => __( 'Update Product', 'gia' ),
		'search_items'        => __( 'Search Product', 'gia' ),
		'not_found'           => __( 'Not Found', 'gia' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'gia' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Products', 'gia' ),
		'description'         => __( 'Products', 'gia' ),
		'labels'              => $labels,
		'menu_icon'			  => 'dashicons-store',
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title','thumbnail'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 6,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'product', $args );
}

function create_product_category_taxonomies() {
	// Add new taxonomy name affiliate-tags
	$labels = array(
		'name'              => _x( 'Product categories', 'taxonomy general name', 'gia' ),
		'singular_name'     => _x( 'Categories', 'taxonomy singular name', 'gia' ),
		'search_items'      => __( 'Search Category', 'gia' ),
		'all_items'         => __( 'All Category', 'gia' ),
		'parent_item'       => __( 'Parent Category ', 'gia' ),
		'parent_item_colon' => __( 'Parent Category', 'gia' ),
		'edit_item'         => __( 'Edit Category', 'gia' ),
		'update_item'       => __( 'Update Category ', 'gia' ),
		'add_new_item'      => __( 'Add New Category ', 'gia' ),
		'new_item_name'     => __( 'New Category Name', 'gia' ),
		'menu_name'         => __( 'Categories', 'gia' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'product-cat' ),
	);

	register_taxonomy( 'product-cat', array( 'product' ), $args );
}
add_action( 'init', 'create_product_category_taxonomies', 0 );


function create_product_type_taxonomies() {
	// Add new taxonomy name affiliate-tags
	$labels = array(
		'name'              => _x( 'Type', 'gia' ),
		'singular_name'     => _x( 'Type', 'gia' ),
		'search_items'      => __( 'Search Type', 'gia' ),
		'all_items'         => __( 'All Type', 'gia' ),
		'parent_item'       => __( 'Parent Type ', 'gia' ),
		'parent_item_colon' => __( 'Parent Type', 'gia' ),
		'edit_item'         => __( 'Edit Type', 'gia' ),
		'update_item'       => __( 'Update Type ', 'gia' ),
		'add_new_item'      => __( 'Add New Type ', 'gia' ),
		'new_item_name'     => __( 'New Type Name', 'gia' ),
		'menu_name'         => __( 'Type', 'gia' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'product-type' ),
	);

	register_taxonomy( 'product-type', array( 'product' ), $args );
}
add_action( 'init', 'create_product_type_taxonomies', 0 );

