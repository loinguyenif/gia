<?php 
add_action( 'init', 'product_post_type', 0 );
function product_post_type() {

	$labels = array(
		'name'                => _x( 'Products', 'Post Type General Name', 'atap' ),
		'singular_name'       => _x( 'Products', 'Post Type Singular Name', 'atap' ),
		'menu_name'           => __( 'Products', 'atap' ),
		'all_items'           => __( 'All Products', 'atap' ),
		'view_item'           => __( 'View Product', 'atap' ),
		'add_new_item'        => __( 'Add New Product', 'atap' ),
		'add_new'             => __( 'Add New', 'atap' ),
		'edit_item'           => __( 'Edit Product', 'atap' ),
		'update_item'         => __( 'Update Product', 'atap' ),
		'search_items'        => __( 'Search Product', 'atap' ),
		'not_found'           => __( 'Not Found', 'atap' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'atap' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Products', 'atap' ),
		'description'         => __( 'Products', 'atap' ),
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
		'name'              => _x( 'Product categories', 'taxonomy general name', 'atap' ),
		'singular_name'     => _x( 'Categories', 'taxonomy singular name', 'atap' ),
		'search_items'      => __( 'Search Category', 'atap' ),
		'all_items'         => __( 'All Category', 'atap' ),
		'parent_item'       => __( 'Parent Category ', 'atap' ),
		'parent_item_colon' => __( 'Parent Category', 'atap' ),
		'edit_item'         => __( 'Edit Category', 'atap' ),
		'update_item'       => __( 'Update Category ', 'atap' ),
		'add_new_item'      => __( 'Add New Category ', 'atap' ),
		'new_item_name'     => __( 'New Category Name', 'atap' ),
		'menu_name'         => __( 'Categories', 'atap' ),
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
		'name'              => _x( 'Type', 'atap' ),
		'singular_name'     => _x( 'Type', 'atap' ),
		'search_items'      => __( 'Search Type', 'atap' ),
		'all_items'         => __( 'All Type', 'atap' ),
		'parent_item'       => __( 'Parent Type ', 'atap' ),
		'parent_item_colon' => __( 'Parent Type', 'atap' ),
		'edit_item'         => __( 'Edit Type', 'atap' ),
		'update_item'       => __( 'Update Type ', 'atap' ),
		'add_new_item'      => __( 'Add New Type ', 'atap' ),
		'new_item_name'     => __( 'New Type Name', 'atap' ),
		'menu_name'         => __( 'Type', 'atap' ),
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

add_action( 'init', 'price_list_post_type', 0 );
function price_list_post_type() {

	$labels = array(
		'name'                => _x( 'Price List', 'atap' ),
		'singular_name'       => _x( 'Price List', 'atap' ),
		'menu_name'           => __( 'Price List', 'atap' ),
		'all_items'           => __( 'All Price List', 'atap' ),
		'view_item'           => __( 'View Price List', 'atap' ),
		'add_new_item'        => __( 'Add New Price List', 'atap' ),
		'add_new'             => __( 'Add New', 'atap' ),
		'edit_item'           => __( 'Edit Price List', 'atap' ),
		'update_item'         => __( 'Update Price List', 'atap' ),
		'search_items'        => __( 'Search Price List', 'atap' ),
		'not_found'           => __( 'Not Found', 'atap' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'atap' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Price List', 'atap' ),
		'description'         => __( 'Price List', 'atap' ),
		'labels'              => $labels,
		'menu_icon'			  => 'dashicons-media-text',
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
	register_post_type( 'price_list', $args );
}

add_action( 'init', 'verification_post_type', 0 );
function verification_post_type() {

	$labels = array(
		'name'                => _x( 'Operation Verification', 'atap' ),
		'singular_name'       => _x( 'Operation Verification', 'atap' ),
		'menu_name'           => __( 'Operation Verification', 'atap' ),
		'all_items'           => __( 'All Post', 'atap' ),
		'view_item'           => __( 'View Post', 'atap' ),
		'add_new_item'        => __( 'Add New Post', 'atap' ),
		'add_new'             => __( 'Add New', 'atap' ),
		'edit_item'           => __( 'Edit Post', 'atap' ),
		'update_item'         => __( 'Update Post', 'atap' ),
		'search_items'        => __( 'Search Post', 'atap' ),
		'not_found'           => __( 'Not Found', 'atap' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'atap' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Operation Verification', 'atap' ),
		'description'         => __( 'Operation Verification', 'atap' ),
		'labels'              => $labels,
		'menu_icon'			  => 'dashicons-analytics',
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title'),
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
	register_post_type( 'verification', $args );
}

add_action( 'init', 'discontinued_product_post_type', 0 );
function discontinued_product_post_type() {

	$labels = array(
		'name'                => _x( 'Discontinued Products', 'atap' ),
		'singular_name'       => _x( 'Discontinued Products', 'atap' ),
		'menu_name'           => __( 'Discontinued Products', 'atap' ),
		'all_items'           => __( 'All Products', 'atap' ),
		'view_item'           => __( 'View Products', 'atap' ),
		'add_new_item'        => __( 'Add New Products', 'atap' ),
		'add_new'             => __( 'Add New', 'atap' ),
		'edit_item'           => __( 'Edit Products', 'atap' ),
		'update_item'         => __( 'Update Products', 'atap' ),
		'search_items'        => __( 'Search Products', 'atap' ),
		'not_found'           => __( 'Not Found', 'atap' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'atap' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Discontinued Products', 'atap' ),
		'description'         => __( 'Discontinued Products', 'atap' ),
		'labels'              => $labels,
		'menu_icon'			  => 'dashicons-media-text',
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title','thumbnail'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 7,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'discontinued-product', $args );
}