<?php
/**
 * Define variable
 */
define('THEME_URL', get_template_directory_uri());
define('CHILD_THEME_URL', get_stylesheet_directory_uri());
add_filter('use_block_editor_for_post', '__return_false', 10);

/**
 * REQUIRED FILES
 */
//require THEME_URL . '/inc/template-tags.php';
require get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/class-gia-header-nav-walker.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function gia_theme_support() {

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Custom background color.
    add_theme_support( 'custom-background',
        array(
            'default-color' => '#231815',
        )
    );

    // Set content-width.
    global $content_width;
    if ( ! isset( $content_width ) ) {
        $content_width = 580;
    }

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Set post thumbnail size.
    set_post_thumbnail_size( 1200, 9999 );

    // Custom logo.
    $logo_width  = 120;
    $logo_height = 90;

    // If the retina setting is active, double the recommended width and height.
    if ( get_theme_mod( 'retina_logo', false ) ) {
        $logo_width  = floor( $logo_width * 2 );
        $logo_height = floor( $logo_height * 2 );
    }

    add_theme_support( 'custom-logo',
        array(
            'height'      => $logo_height,
            'width'       => $logo_width,
            'flex-height' => true,
            'flex-width'  => true,
        )
    );

    //Let WordPress manage the document title.
    // add_theme_support( 'title-tag' );

}
add_action( 'after_setup_theme', 'gia_theme_support' );

function meta_tags() {
     ?>
     <meta name="google-site-verification" content="-EvpW8MbhOClpVxxUOuEUlS5nY11x-w6SFfmasaU6O4" />
     <script async src="https://www.googletagmanager.com/gtag/js?id=UA-198314365-1"></script>
    <script>
    	window.dataLayer = window.dataLayer || [];
    	function gtag(){dataLayer.push(arguments);}
    	gtag('js', new Date());
    	gtag('config', 'UA-198314365-1');
    </script>
     <?php
}
add_action( 'wp_head', 'meta_tags', 1);

/**
 * Register and Enqueue Styles.
 */
function gia_register_styles() {

    $theme_version = wp_get_theme()->get( 'Version' );
    wp_enqueue_style( 'font-DINOT-css', THEME_URL . '/assets/webfonts/font-DINOT.css', array(), $theme_version );
    wp_enqueue_style( 'bootstrap-css', THEME_URL . '/assets/css/bootstrap.css', array(), $theme_version );
    wp_enqueue_style( 'bootstrap-select-css', THEME_URL . '/assets/css/bootstrap-select.min.css', array(), $theme_version );
    wp_enqueue_style( 'carousel-css', THEME_URL . '/assets/css/owl.carousel.css', array(), $theme_version );
    wp_enqueue_style( 'stylesheet-css', THEME_URL . '/assets/css/all.css', array(), $theme_version );
    wp_enqueue_style( 'style-css', get_stylesheet_uri(), array(), $theme_version );
    wp_enqueue_style( 'responsive-css', THEME_URL . '/assets/css/responsive.css', array());
    wp_enqueue_style( 'gia-header-css', THEME_URL . '/assets/css/gia-header.css', array(), $theme_version );
    wp_enqueue_style( 'gia-footer-css', THEME_URL . '/assets/css/gia-footer.css', array( 'gia-header-css' ), $theme_version );
}
add_action( 'wp_enqueue_scripts', 'gia_register_styles' );

/**
 * Add the theme font to Elementor's font selector.
 *
 * @param array $fonts Fonts registered with Elementor.
 * @return array
 */
function gia_elementor_additional_fonts( $fonts ) {
    $fonts['DINOT'] = 'system';
    $fonts['DINOT-Regular'] = 'system';
    $fonts['DINOT-Medium'] = 'system';
    $fonts['DINOT-Black'] = 'system';

    return $fonts;
}
add_filter( 'elementor/fonts/additional_fonts', 'gia_elementor_additional_fonts' );

/**
 * Register theme settings in the WordPress Customizer.
 *
 * @param WP_Customize_Manager $wp_customize WordPress Customizer manager.
 */
function gia_customize_register( $wp_customize ) {
    $wp_customize->add_section(
        'gia_theme_settings',
        array(
            'title'    => __( 'Theme Settings', 'gia' ),
            'priority' => 160,
        )
    );

    $wp_customize->add_setting(
        'gia_footer_copyright',
        array(
            'default'           => 'General Insurance Association of Singapore {Y}. All rights reserved.',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'gia_footer_copyright',
        array(
            'label'       => __( 'Footer Copyright Text', 'gia' ),
            'description' => __( 'Use {Y} to display the current year.', 'gia' ),
            'section'     => 'gia_theme_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'gia_header_linkedin_url',
        array(
            'default'           => 'https://www.linkedin.com/company/giasingapore',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'gia_header_linkedin_url',
        array(
            'label'       => __( 'GIA LinkedIn URL', 'gia' ),
            'description' => __( 'Used by the main navigation mega menu.', 'gia' ),
            'section'     => 'gia_theme_settings',
            'type'        => 'url',
        )
    );
}
add_action( 'customize_register', 'gia_customize_register' );

/**
 * Return the current audience section from the request path.
 *
 * @return string
 */
function gia_header_current_audience() {
    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
    $request_path = wp_parse_url( $request_uri, PHP_URL_PATH );
    $home_path = wp_parse_url( home_url( '/' ), PHP_URL_PATH );

    if ( is_string( $request_path ) && is_string( $home_path ) && '/' !== $home_path ) {
        $home_path = trailingslashit( $home_path );

        if ( 0 === strpos( $request_path, $home_path ) ) {
            $request_path = '/' . substr( $request_path, strlen( $home_path ) );
        }
    }

    if ( is_string( $request_path ) && preg_match( '#^/agents(?:/|\.html?$|$)#i', $request_path ) ) {
        return 'agents';
    }

    if ( is_string( $request_path ) && preg_match( '#^/consumers(?:/|\.html?$|$)#i', $request_path ) ) {
        return 'consumers';
    }

    return 'consumers';
}

/**
 * Return the active top-level item assigned to the primary menu location.
 *
 * @return WP_Post|null
 */
function gia_header_get_active_primary_item() {
    $locations = get_nav_menu_locations();

    if ( empty( $locations['primary'] ) ) {
        return null;
    }

    $items = wp_get_nav_menu_items( $locations['primary'] );

    if ( ! is_array( $items ) ) {
        return null;
    }

    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
    $home_path = wp_parse_url( home_url( '/' ), PHP_URL_PATH );
    $normalized_home_path = is_string( $home_path ) ? untrailingslashit( strtolower( $home_path ) ) : '';
    $normalize_path = function ( $url ) use ( $normalized_home_path ) {
        $path = wp_parse_url( $url, PHP_URL_PATH );

        if ( ! is_string( $path ) ) {
            return '';
        }

        $path = strtolower( $path );

        if (
            '' !== $normalized_home_path &&
            ( $path === $normalized_home_path || 0 === strpos( $path, $normalized_home_path . '/' ) )
        ) {
            $path = substr( $path, strlen( $normalized_home_path ) );
        }

        return untrailingslashit( $path );
    };
    $request_path = $normalize_path( $request_uri );
    $audience = gia_header_current_audience();
    $audience_item = null;
    $active_item = null;
    $active_path_length = -1;
    $primary_items_by_title = array();

    foreach ( $items as $item ) {
        if ( '0' !== (string) $item->menu_item_parent ) {
            continue;
        }

        $title_key = sanitize_title( wp_strip_all_tags( $item->title ) );
        $item_path = $normalize_path( $item->url );
        $primary_items_by_title[ $title_key ] = $item;

        if ( $title_key === $audience || ( '' !== $item_path && ( '/' . $audience === $item_path || 0 === strpos( $item_path, '/' . $audience . '/' ) ) ) ) {
            $audience_item = $item;
        }

        if (
            '' !== $item_path &&
            ( $request_path === $item_path || 0 === strpos( $request_path, trailingslashit( $item_path ) ) ) &&
            strlen( $item_path ) > $active_path_length
        ) {
            $active_item = $item;
            $active_path_length = strlen( $item_path );
        }
    }

    if ( $active_item ) {
        return $active_item;
    }

    if ( ! empty( $locations['expanded'] ) ) {
        $expanded_items = wp_get_nav_menu_items( $locations['expanded'] );

        if ( is_array( $expanded_items ) ) {
            $expanded_items_by_id = array();

            foreach ( $expanded_items as $expanded_item ) {
                $expanded_items_by_id[ (int) $expanded_item->ID ] = $expanded_item;
            }

            $active_expanded_root = null;
            $expanded_path_length = -1;

            foreach ( $expanded_items as $expanded_item ) {
                $item_path = $normalize_path( $expanded_item->url );

                if (
                    '' === $item_path ||
                    ( $request_path !== $item_path && 0 !== strpos( $request_path, trailingslashit( $item_path ) ) ) ||
                    strlen( $item_path ) <= $expanded_path_length
                ) {
                    continue;
                }

                $root_item = $expanded_item;
                $parent_id = (int) $root_item->menu_item_parent;
                $parent_depth = 0;

                while ( $parent_id && isset( $expanded_items_by_id[ $parent_id ] ) && $parent_depth < count( $expanded_items ) ) {
                    $root_item = $expanded_items_by_id[ $parent_id ];
                    $parent_id = (int) $root_item->menu_item_parent;
                    ++$parent_depth;
                }

                $active_expanded_root = $root_item;
                $expanded_path_length = strlen( $item_path );
            }

            if ( $active_expanded_root ) {
                $root_key = sanitize_title( wp_strip_all_tags( $active_expanded_root->title ) );

                if ( isset( $primary_items_by_title[ $root_key ] ) ) {
                    return $primary_items_by_title[ $root_key ];
                }

                $singular_root_key = preg_replace( '/s$/', '', $root_key );

                foreach ( $primary_items_by_title as $primary_title => $primary_item ) {
                    if ( preg_replace( '/s$/', '', $primary_title ) === $singular_root_key ) {
                        return $primary_item;
                    }
                }
            }
        }
    }

    return $audience_item;
}

/**
 * Match an expanded-menu group to the active primary menu item.
 *
 * @param array    $items Menu items.
 * @param stdClass $args  Menu arguments.
 * @return array
 */
function gia_header_filter_expanded_menu_items( $items, $args ) {
    if ( empty( $args->gia_header_filter_active_parent ) || empty( $args->theme_location ) || 'expanded' !== $args->theme_location ) {
        return $items;
    }

    $active_item = gia_header_get_active_primary_item();

    if ( ! $active_item ) {
        return array();
    }

    $active_key = sanitize_title( $active_item->title );
    $expanded_roots = array();
    $children_by_parent = array();

    foreach ( $items as $item ) {
        $parent_id = (int) $item->menu_item_parent;

        if ( 0 === $parent_id ) {
            $expanded_roots[] = $item;
        } else {
            $children_by_parent[ $parent_id ][] = $item;
        }
    }

    $matching_root = null;
    foreach ( $expanded_roots as $root ) {
        if ( sanitize_title( $root->title ) === $active_key ) {
            $matching_root = $root;
            break;
        }
    }

    if ( ! $matching_root ) {
        $singular_key = preg_replace( '/s$/', '', $active_key );

        foreach ( $expanded_roots as $root ) {
            if ( preg_replace( '/s$/', '', sanitize_title( $root->title ) ) === $singular_key ) {
                $matching_root = $root;
                break;
            }
        }
    }

    if ( ! $matching_root ) {
        return array();
    }

    $filtered_items = array();
    $append_children = function ( $parent_id, $is_promoted = false ) use ( &$append_children, &$filtered_items, $children_by_parent ) {
        if ( empty( $children_by_parent[ $parent_id ] ) ) {
            return;
        }

        foreach ( $children_by_parent[ $parent_id ] as $child ) {
            $filtered_child = clone $child;

            if ( $is_promoted ) {
                $filtered_child->menu_item_parent = '0';
            }

            $filtered_items[] = $filtered_child;
            $append_children( (int) $child->ID );
        }
    };
    $append_children( (int) $matching_root->ID, true );

    return $filtered_items;
}
add_filter( 'wp_nav_menu_objects', 'gia_header_filter_expanded_menu_items', 10, 2 );

/**
 * Add an optional uploaded icon field to each menu item in Appearance > Menus.
 *
 * @param int      $item_id Menu item ID.
 * @param WP_Post  $item    Menu item object.
 * @param int      $depth   Menu item depth.
 * @param stdClass $args    Menu arguments.
 * @param int      $id      Menu item ID.
 */
function gia_menu_item_icon_field( $item_id, $item, $depth, $args, $id ) {
    $attachment_id = absint( get_post_meta( $item_id, '_gia_menu_icon_id', true ) );
    $image_url = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'thumbnail' ) : false;
    ?>
    <p class="description description-wide gia-menu-icon-field">
        <label for="edit-menu-item-gia-icon-<?php echo esc_attr( $item_id ); ?>">
            <?php
            printf(
                /* translators: %s: menu item title. */
                esc_html__( 'Menu icon for %s', 'gia' ),
                esc_html( wp_strip_all_tags( $item->title ) )
            );
            ?>
        </label><br>
        <input
            type="hidden"
            class="edit-menu-item-gia-icon"
            id="edit-menu-item-gia-icon-<?php echo esc_attr( $item_id ); ?>"
            name="menu-item-gia-icon[<?php echo esc_attr( $item_id ); ?>]"
            value="<?php echo esc_attr( $attachment_id ); ?>"
        >
        <span class="gia-menu-icon-preview"<?php echo $image_url ? '' : ' hidden'; ?>>
            <?php if ( $image_url ) : ?>
                <img src="<?php echo esc_url( $image_url ); ?>" alt="" width="36" height="36">
            <?php endif; ?>
        </span>
        <button type="button" class="button gia-menu-icon-upload">
            <?php esc_html_e( 'Choose image', 'gia' ); ?>
        </button>
        <button type="button" class="button gia-menu-icon-remove"<?php echo $image_url ? '' : ' hidden'; ?>>
            <?php esc_html_e( 'Remove', 'gia' ); ?>
        </button>
        <span class="description"><?php esc_html_e( 'Optional. Overrides the automatic menu icon.', 'gia' ); ?></span>
    </p>
    <?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'gia_menu_item_icon_field', 10, 5 );

/**
 * Save the uploaded icon attachment ID for a menu item.
 *
 * @param int   $menu_id         Menu ID.
 * @param int   $menu_item_db_id Menu item ID.
 * @param array $args            Menu item data.
 */
function gia_save_menu_item_icon( $menu_id, $menu_item_db_id, $args ) {
    if ( ! current_user_can( 'edit_theme_options' ) || ! isset( $_POST['menu-item-gia-icon'][ $menu_item_db_id ] ) ) {
        return;
    }

    $attachment_id = absint( wp_unslash( $_POST['menu-item-gia-icon'][ $menu_item_db_id ] ) );

    if ( $attachment_id && wp_attachment_is_image( $attachment_id ) ) {
        update_post_meta( $menu_item_db_id, '_gia_menu_icon_id', $attachment_id );
    } else {
        delete_post_meta( $menu_item_db_id, '_gia_menu_icon_id' );
    }
}
add_action( 'wp_update_nav_menu_item', 'gia_save_menu_item_icon', 10, 3 );

/**
 * Expose a menu item's uploaded icon URL to the front-end header script.
 *
 * @param array    $atts     Link attributes.
 * @param WP_Post  $menu_item Menu item object.
 * @param stdClass $args     Menu arguments.
 * @param int      $depth    Menu item depth.
 * @return array
 */
function gia_menu_item_icon_link_attribute( $atts, $menu_item, $args, $depth ) {
    $attachment_id = absint( get_post_meta( $menu_item->ID, '_gia_menu_icon_id', true ) );
    $image_url = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'thumbnail' ) : false;

    if ( $image_url ) {
        $atts['data-gia-menu-icon'] = esc_url( $image_url );
    }

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'gia_menu_item_icon_link_attribute', 10, 4 );

/**
 * Load the media picker controls on the WordPress menu editor.
 *
 * @param string $hook_suffix Current admin page hook.
 */
function gia_enqueue_menu_icon_admin_assets( $hook_suffix ) {
    if ( 'nav-menus.php' !== $hook_suffix ) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'gia-menu-icon-admin',
        THEME_URL . '/assets/js/gia-menu-icon-admin.js',
        array( 'jquery', 'media-editor', 'media-views' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'admin_enqueue_scripts', 'gia_enqueue_menu_icon_admin_assets' );

/**
 * Mark the active primary menu item.
 *
 * @param array    $classes   Existing menu item classes.
 * @param WP_Post  $menu_item Menu item object.
 * @param stdClass $args      Menu arguments.
 * @return array
 */
function gia_header_audience_menu_classes( $classes, $menu_item, $args ) {
    if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
        return $classes;
    }

    $active_item = gia_header_get_active_primary_item();

    if ( $active_item && (int) $active_item->ID === (int) $menu_item->ID ) {
        $classes[] = 'is-active';
    }

    return $classes;
}
add_filter( 'nav_menu_css_class', 'gia_header_audience_menu_classes', 10, 3 );

/**
 * Return the footer menu location for the active primary audience item.
 *
 * @return string
 */
function gia_footer_menu_location() {
    $active_item = gia_header_get_active_primary_item();
    $active_audience = $active_item ? sanitize_title( $active_item->title ) : gia_header_current_audience();

    return 'agents' === $active_audience ? 'footer_agents' : 'footer_consumers';
}

/**
 * Render footer menu groups as columns, collecting top-level links without children.
 *
 * @param string $location Menu location.
 * @return string
 */
function gia_footer_render_menu_columns( $location ) {
    $locations = get_nav_menu_locations();

    if ( empty( $locations[ $location ] ) ) {
        return '';
    }

    $menu_id = (int) $locations[ $location ];
    $items   = wp_get_nav_menu_items( $menu_id );

    if ( ! is_array( $items ) ) {
        return '';
    }

    $children_by_parent = array();
    $root_items         = array();

    foreach ( $items as $item ) {
        $parent_id = (int) $item->menu_item_parent;

        if ( 0 === $parent_id ) {
            $root_items[] = $item;
        } else {
            $children_by_parent[ $parent_id ][] = $item;
        }
    }

    $group_items    = array();
    $standalone_ids = array();
    $collect_ids    = function ( $item_id ) use ( &$collect_ids, &$children_by_parent ) {
        $ids = array( $item_id );

        if ( ! empty( $children_by_parent[ $item_id ] ) ) {
            foreach ( $children_by_parent[ $item_id ] as $child ) {
                $ids = array_merge( $ids, $collect_ids( (int) $child->ID ) );
            }
        }

        return $ids;
    };

    foreach ( $root_items as $item ) {
        $item_id = (int) $item->ID;

        if ( ! empty( $children_by_parent[ $item_id ] ) ) {
            $group_items[] = $collect_ids( $item_id );
        } else {
            $standalone_ids[] = $item_id;
        }
    }

    if ( empty( $group_items ) && empty( $standalone_ids ) ) {
        return '';
    }

    $render_column = function ( $item_ids, $column_number ) use ( $menu_id ) {
        $filter_items = function ( $menu_items, $args ) use ( $item_ids ) {
            if ( empty( $args->gia_footer_item_ids ) ) {
                return $menu_items;
            }

            $included_ids = array_flip( array_map( 'intval', $args->gia_footer_item_ids ) );

            return array_values(
                array_filter(
                    $menu_items,
                    function ( $menu_item ) use ( $included_ids ) {
                        return isset( $included_ids[ (int) $menu_item->ID ] );
                    }
                )
            );
        };

        add_filter( 'wp_nav_menu_objects', $filter_items, 10, 2 );
        $column_html = wp_nav_menu(
            array(
                'menu'                => $menu_id,
                'container'           => false,
                'menu_class'          => 'gia-footer__column-menu',
                'menu_id'             => 'gia-footer-menu-column-' . (int) $column_number,
                'depth'               => 0,
                'fallback_cb'         => false,
                'echo'                => false,
                'gia_footer_item_ids' => $item_ids,
            )
        );
        remove_filter( 'wp_nav_menu_objects', $filter_items, 10 );

        return $column_html;
    };

    $columns_html  = '';
    $column_number = 1;

    foreach ( $group_items as $item_ids ) {
        $columns_html .= '<div class="gia-footer__column gia-footer__column--group">';
        $columns_html .= $render_column( $item_ids, $column_number++ );
        $columns_html .= '</div>';
    }

    if ( ! empty( $standalone_ids ) ) {
        $columns_html .= '<div class="gia-footer__column gia-footer__column--standalone">';
        $columns_html .= $render_column( $standalone_ids, $column_number );
        $columns_html .= '</div>';
    }

    $layout_class = empty( $group_items ) ? ' gia-footer__columns--single' : '';

    return '<div class="gia-footer__columns' . $layout_class . '">' . $columns_html . '</div>';
}


/**
 * Register and Enqueue Scripts.
 */
function gia_register_scripts() {

    $theme_version = wp_get_theme()->get( 'Version' );
    wp_enqueue_script( 'jquery-js', THEME_URL . '/assets/js/jquery.min.js', array(), $theme_version );
    wp_enqueue_script( 'popper.min-js', THEME_URL . '/assets/js/popper.min.js', array(), $theme_version );
    wp_enqueue_script( 'bootstrap-js', THEME_URL . '/assets/js/bootstrap.min.js', array(), $theme_version );
    wp_enqueue_script( 'select-js', THEME_URL . '/assets/js/bootstrap-select.min.js', array(), $theme_version );
    wp_enqueue_script( 'matchHeight-js', THEME_URL . '/assets/js/jquery.matchHeight-min.js', array(), $theme_version );
    wp_enqueue_script( 'carousel-js', THEME_URL . '/assets/js/owl.carousel.min.js', array(), $theme_version );
    wp_enqueue_script( 'custom-js', THEME_URL . '/assets/js/custom.js', array());
    wp_enqueue_script( 'gia-header-js', THEME_URL . '/assets/js/gia-header.js', array(), $theme_version, true );
    wp_script_add_data( 'async-js', 'async', true );

}
add_action( 'wp_enqueue_scripts', 'gia_register_scripts' );


/**
 * Register navigation menus uses wp_nav_menu in five places.
 */
function gia_menus() {

    $locations = array(
        'primary'  => __( 'Main Menu Level 1', 'gia' ),
        'expanded' => __( 'Main Menu Level 2 (grouped by Level 1)', 'gia' ),
        'mobile'   => __( 'Mobile Menu', 'gia' ),
        'footer_consumers' => __( 'Footer Consumers', 'gia' ),
        'footer_agents' => __( 'Footer Agents', 'gia' ),
    );

    register_nav_menus( $locations );
}
add_action( 'init', 'gia_menus' );


/**
 * Get the information about the logo.
 *
 * @param string $html The HTML output from get_custom_logo (core function).
 * @return string
 */
function gia_get_custom_logo( $html ) {

    $logo_id = get_theme_mod( 'custom_logo' );

    if ( ! $logo_id ) {
        return $html;
    }

    $logo = wp_get_attachment_image_src( $logo_id, 'full' );

    if ( $logo ) {
        // For clarity.
        $logo_width  = esc_attr( $logo[1] );
        $logo_height = esc_attr( $logo[2] );

        // If the retina logo setting is active, reduce the width/height by half.
        if ( get_theme_mod( 'retina_logo', false ) ) {
            $logo_width  = floor( $logo_width / 2 );
            $logo_height = floor( $logo_height / 2 );

            $search = array(
                '/width=\"\d+\"/iU',
                '/height=\"\d+\"/iU',
            );

            $replace = array(
                "width=\"{$logo_width}\"",
                "height=\"{$logo_height}\"",
            );

            // Add a style attribute with the height, or append the height to the style attribute if the style attribute already exists.
            if ( strpos( $html, ' style=' ) === false ) {
                $search[]  = '/(src=)/';
                $replace[] = "style=\"height: {$logo_height}px;\" src=";
            } else {
                $search[]  = '/(style="[^"]*)/';
                $replace[] = "$1 height: {$logo_height}px;";
            }

            $html = preg_replace( $search, $replace, $html );

        }
    }

    return $html;

}
add_filter( 'get_custom_logo', 'gia_get_custom_logo' );



function apat_breadcrumbs() {
    if ( ( is_home() || is_front_page() ) && ! is_paged() ) {
        return '';
    }

    $normalize_path = function ( $url ) {
        $path = wp_parse_url( $url, PHP_URL_PATH );
        $home_path = wp_parse_url( home_url( '/' ), PHP_URL_PATH );

        if ( ! is_string( $path ) ) {
            return '';
        }

        $path = strtolower( rawurldecode( $path ) );
        $home_path = is_string( $home_path ) ? untrailingslashit( strtolower( rawurldecode( $home_path ) ) ) : '';

        if (
            '' !== $home_path &&
            ( $path === $home_path || 0 === strpos( $path, $home_path . '/' ) )
        ) {
            $path = substr( $path, strlen( $home_path ) );
        }

        return untrailingslashit( $path );
    };
    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : home_url( '/' );
    $request_path = $normalize_path( $request_uri );
    $current_id = get_queried_object_id();
    $current_title = '';

    if ( is_search() ) {
        $current_title = sprintf(
            /* translators: %s: Search query. */
            __( 'Search results for: %s', 'gia' ),
            get_search_query()
        );
    } elseif ( is_404() ) {
        $current_title = __( 'Error 404', 'gia' );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $queried_term = get_queried_object();
        $current_title = isset( $queried_term->name ) ? $queried_term->name : '';
    } elseif ( is_post_type_archive() ) {
        $archive_post_type = get_query_var( 'post_type' );
        if ( is_array( $archive_post_type ) ) {
            $archive_post_type = reset( $archive_post_type );
        }
        $post_type_object = $archive_post_type ? get_post_type_object( $archive_post_type ) : null;
        $current_title = $post_type_object ? $post_type_object->labels->name : '';
    } elseif ( is_author() ) {
        $current_title = get_the_author_meta( 'display_name', get_queried_object_id() );
    } elseif ( is_year() ) {
        $current_title = get_the_time( 'Y' );
    } elseif ( is_month() ) {
        $current_title = get_the_time( 'F Y' );
    } elseif ( is_day() ) {
        $current_title = get_the_time( 'F j, Y' );
    } elseif ( $current_id ) {
        $current_title = get_the_title( $current_id );
    }

    $crumbs = array();
    $menu_match_found = false;
    $menu_locations = get_nav_menu_locations();
    $active_primary = gia_header_get_active_primary_item();

    if ( ! empty( $menu_locations['primary'] ) ) {
        $primary_items = wp_get_nav_menu_items( $menu_locations['primary'] );

        if ( is_array( $primary_items ) ) {
            foreach ( $primary_items as $primary_item ) {
                if (
                    '0' === (string) $primary_item->menu_item_parent &&
                    $active_primary &&
                    (int) $primary_item->ID === (int) $active_primary->ID
                ) {
                    $crumbs[] = array(
                        'title' => $primary_item->title,
                        'url'   => $primary_item->url,
                    );
                    $menu_match_found = $request_path === $normalize_path( $primary_item->url );
                    break;
                }
            }
        }
    }

    if ( ! empty( $menu_locations['expanded'] ) ) {
        $expanded_items = wp_get_nav_menu_items( $menu_locations['expanded'] );

        if ( is_array( $expanded_items ) ) {
            $expanded_items_by_id = array();
            $best_match = null;
            $best_match_path_length = -1;
            $best_match_depth = -1;

            foreach ( $expanded_items as $expanded_item ) {
                $expanded_items_by_id[ (int) $expanded_item->ID ] = $expanded_item;
            }

            foreach ( $expanded_items as $expanded_item ) {
                $item_path = $normalize_path( $expanded_item->url );
                $item_depth = 0;
                $parent_id = (int) $expanded_item->menu_item_parent;
                $parent_depth = 0;

                while ( $parent_id && isset( $expanded_items_by_id[ $parent_id ] ) && $parent_depth < count( $expanded_items ) ) {
                    $parent_item = $expanded_items_by_id[ $parent_id ];
                    ++$item_depth;
                    $parent_id = (int) $parent_item->menu_item_parent;
                    ++$parent_depth;
                }

                if (
                    '' === $item_path ||
                    ( $request_path !== $item_path && 0 !== strpos( $request_path, trailingslashit( $item_path ) ) ) ||
                    strlen( $item_path ) < $best_match_path_length ||
                    ( strlen( $item_path ) === $best_match_path_length && $item_depth <= $best_match_depth )
                ) {
                    continue;
                }

                $best_match = $expanded_item;
                $best_match_path_length = strlen( $item_path );
                $best_match_depth = $item_depth;
            }

            if ( $best_match ) {
                $menu_chain = array();
                $parent_id = (int) $best_match->ID;
                $parent_depth = 0;

                while ( $parent_id && isset( $expanded_items_by_id[ $parent_id ] ) && $parent_depth < count( $expanded_items ) ) {
                    $menu_item = $expanded_items_by_id[ $parent_id ];
                    array_unshift( $menu_chain, $menu_item );
                    $parent_id = (int) $menu_item->menu_item_parent;
                    ++$parent_depth;
                }

                foreach ( $menu_chain as $index => $menu_item ) {
                    if (
                        0 === $index &&
                        $active_primary &&
                        sanitize_title( $menu_item->title ) === sanitize_title( $active_primary->title )
                    ) {
                        continue;
                    }

                    $crumbs[] = array(
                        'title' => $menu_item->title,
                        'url'   => $menu_item->url,
                    );
                }

                $best_match_path = $normalize_path( $best_match->url );
                $menu_match_found = $menu_match_found || $request_path === $best_match_path;
            }
        }
    }

    if ( ! $menu_match_found && $current_title ) {
        if ( is_page() && $current_id ) {
            $page_ancestors = array_reverse( get_post_ancestors( $current_id ) );

            foreach ( $page_ancestors as $ancestor_id ) {
                $ancestor_title = get_the_title( $ancestor_id );
                $last_crumb = empty( $crumbs ) ? null : end( $crumbs );

                if ( ! $last_crumb || sanitize_title( $last_crumb['title'] ) !== sanitize_title( $ancestor_title ) ) {
                    $crumbs[] = array(
                        'title' => $ancestor_title,
                        'url'   => get_permalink( $ancestor_id ),
                    );
                }
            }
        } elseif ( is_single() && 'post' === get_post_type() ) {
            $categories = get_the_category( $current_id );

            if ( ! empty( $categories ) ) {
                $category = reset( $categories );
                $category_ancestors = array_reverse( get_ancestors( $category->term_id, 'category' ) );

                foreach ( $category_ancestors as $ancestor_id ) {
                    $ancestor = get_category( $ancestor_id );
                    if ( $ancestor && ! is_wp_error( $ancestor ) ) {
                        $crumbs[] = array(
                            'title' => $ancestor->name,
                            'url'   => get_category_link( $ancestor->term_id ),
                        );
                    }
                }

                $crumbs[] = array(
                    'title' => $category->name,
                    'url'   => get_category_link( $category->term_id ),
                );
            }
        }

        $current_url = '';

        if ( $current_id && is_singular() ) {
            $current_url = get_permalink( $current_id );
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $queried_term = get_queried_object();
            $term_url = $queried_term ? get_term_link( $queried_term ) : '';
            $current_url = is_wp_error( $term_url ) ? '' : $term_url;
        }

        $crumbs[] = array(
            'title' => $current_title,
            'url'   => $current_url,
        );
    }

    if ( is_paged() ) {
        $crumbs[] = array(
            'title' => sprintf(
                /* translators: %s: Page number. */
                __( 'Page %s', 'gia' ),
                number_format_i18n( get_query_var( 'paged' ) )
            ),
            'url'   => '',
        );
    }

    if ( empty( $crumbs ) ) {
        return '';
    }

    $output = '<ul id="breadcrumbs"><li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'gia' ) . '</a></li>';

    foreach ( $crumbs as $index => $crumb ) {
        $is_current = $index === count( $crumbs ) - 1;
        $output .= '<li><span class="delimiter"> &nbsp;/&nbsp; </span>';

        if ( $is_current ) {
            $output .= '<span class="current">' . esc_html( $crumb['title'] ) . '</span>';
        } elseif ( ! empty( $crumb['url'] ) ) {
            $output .= '<a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['title'] ) . '</a>';
        } else {
            $output .= '<span>' . esc_html( $crumb['title'] ) . '</span>';
        }

        $output .= '</li>';
    }

    return $output . '</ul>';
}
add_shortcode( 'apat_breadcrumbs', 'apat_breadcrumbs');


function apat_searchform() {
}
add_shortcode( 'apat_searchform', 'apat_searchform');

function more_post_ajax(){
    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 9;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
    $cat = (isset($_POST['cat'])) ? $_POST['cat'] : 0;
    $orderby = (isset($_POST['orderby'])) ? $_POST['orderby'] : 0;
    $subcat = (isset($_POST['subcat'])) ? $_POST['subcat'] : 0;
    $s = (isset($_POST['s'])) ? $_POST['s'] : 0;

    header("Content-Type: text/html");
    
    $curent_id = $cat;
    if($subcat) $cat = $subcat;
    $args = array(
        's' => $s,
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $ppp,
        'cat' => $cat,
        'paged'    => $page,
    );
    
    switch ($orderby) {
        case 'date_desc':
            $orderby = array(
                'orderby' => 'date',
                'order' => 'DESC',
            );
            break;
            
        case 'title_asc':
            $orderby = array(
                'orderby' => 'title',
                'order' => 'ASC',
            );
            break;
        
        default:
            $orderby = array(
                'orderby' => 'date',
                'order' => 'DESC',
            );
    }

    $loop = new WP_Query($args+$orderby);
    $out = '';
    if ($loop -> have_posts()) :  while ($loop -> have_posts()) : $loop -> the_post();
        $categories = get_the_category();
        $out .= '<div class="col-md-4" >
                <div class="newbox" data-mh="news-group">
                    <div class="post-date">'. get_the_date("d F Y").'</div>
                    <h4 class="post-title">'. get_the_title().'</h4>
                    <div class="post-bottom">';
                        if ( ! empty( $categories ) ) {
                            foreach($categories as $category) {
                                if($curent_id == $category->term_id) continue;
                                $color = get_field('color', $category);
                                $out .= '<span class="post-cate" style="background: '.$color.'">'.  esc_html( $category->name ).'</span>';
                            }
                        }
                        $out .= '<a class="more-link" href="'.  get_permalink().'">'. __('Read More', 'gia') .' &nbsp <i class="fal fa-arrow-right"></i></a>
                    </div>
                </div>
             </div>';

    endwhile;
    endif;
    wp_reset_postdata();
    die($out);
}

add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');


function filter_product(){
  
    $catId = (isset($_POST['id'])) ? $_POST['id'] : '';
    $paged = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
    $keyword = (isset($_POST['keyword'])) ? $_POST['keyword'] : '';
    $orderby = (isset($_POST['orderby'])) ? $_POST['orderby'] : '';
    if($catId) {
        $tax_query = array(
            array(
              'taxonomy' => 'product-cat',
              'field' => 'id',
              'terms' => $catId
            ));
    }
    else{
        $tax_query='';
    }

    $args = array(
        'post_type' => 'product',
        'tax_query' => $tax_query,
        'paged' => $paged,
        's'     => $keyword,
        'posts_per_page'=> 15
    );
    
    switch ($orderby) {
        case 'date_desc':
            $orderby = array(
                'orderby' => 'date',
                'order' => 'DESC',
            );
            break;
            
        case 'date_asc':
            $orderby = array(
                'orderby' => 'date',
                'order' => 'ASC',
            );
            break; 

        default:
            $orderby = array(
                'orderby' => 'date',
                'order' => 'DESC',
            );
    }
    $loop = new WP_Query($args+$orderby);
    if ($loop -> have_posts()) :  while ($loop -> have_posts()) : $loop -> the_post();
        global $product;
        $categories = get_the_terms( $product->ID, 'product-cat' );
        $image_url = (get_the_post_thumbnail_url())?get_the_post_thumbnail_url():site_url().'/wp-content/uploads/no-image.jpg';
        $sku = get_field('sku', $product->ID);
        
        $out .= '<div class="col-12 col-md-4 mb-4 pb-2">
                    <div class="product-item">
                        <div class="product-image">
                            <a href="'.get_the_permalink().'">
                                <img src="'.$image_url.'">';
                                foreach ($categories as $categorie) {
                                    if($categorie->parent == 0){
                                        $out .='<span class="product-cat">'.$categorie->name.'</span>';
                                    }
                                }
                        $out .= '</a>
                        </div>
                        <div class="product-meta" data-mh="product-meta">';
                            
                                $top_level_ids = [];
                               
                                foreach( $categories as $category ) {
                                    if( ! $category->parent ) {
                                        $top_level_ids[] = $category->term_id;
                                    }
                                }
                                $count=0;
                                foreach( $categories as $category ) {
                                   
                                    if(( in_array( $category->parent, $top_level_ids )) && $count==0) {

                                        $out .='<div class="product-cate mb-2">' . $category->name . '</div>';
                                        $count=1;
                                    }

                                }
                            
                            $out .= '<h3 class="product-title mb-4"><a href="'.get_the_permalink().'">'. get_the_title().'</a></h3>
                            <div class="product-range mb-2">
                                Range: SwitchBlade x908 GEN2
                            </div>
                            <div class="product-series">
                                Series: '.$sku.'
                            </div>
                        </div>
                    </div>
                </div>';
    endwhile;
    endif;
    wp_reset_postdata();
    
    $res = array();
    $res['result'] = $loop->max_num_pages;
    $res['out'] = $out;
    echo json_encode($res);
    die();
    
  
}

add_action('wp_ajax_nopriv_filter_product', 'filter_product');
add_action('wp_ajax_filter_product', 'filter_product');


function get_post_loadmore() {
    $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0; 
    $args = array(
        'post_type' => 'product',
        'offset'  =>  $offset,
        'posts_per_page'  => 6
    );
    $loop = new WP_Query($args);
    if ($loop -> have_posts()) :  while ($loop -> have_posts()) : $loop -> the_post();
        global $product;
        $categories = get_the_terms( $product->ID, 'product-cat' );
        $sku = get_field('sku', $product->ID);
        $image_url = (get_the_post_thumbnail_url())?get_the_post_thumbnail_url():site_url().'/wp-content/uploads/no-image.jpg';
        
        $out .= '<div class="col-12 col-md-6 mb-4 pb-2">
                    <div class="product-item">
                        <div class="product-image">
                            <a href="<?php the_permalink();?>">
                                <img src="'.$image_url.'">';
                                foreach ($categories as $categorie) {
                                    if($categorie->parent == 0){
                                        $out .='<span class="product-cat">'.$categorie->name.'</span>';
                                    }
                                }
                        $out .= '</a>
                        </div>
                        <div class="product-meta">';
                            
                                $top_level_ids = [];
                               
                                foreach( $categories as $category ) {
                                    if( ! $category->parent ) {
                                        $top_level_ids[] = $category->term_id;
                                    }
                                }
                                $count=0;
                                foreach( $categories as $category ) {
                                   
                                    if(( in_array( $category->parent, $top_level_ids )) && $count==0) {

                                        $out .='<div class="product-cate mb-2">' . $category->name . '</div>';
                                        $count=1;
                                    }

                                }
                            
                            $out .= '<h3 class="product-title mb-4"><a href="'.get_the_permalink().'">'. get_the_title().'</a></h3>
                            <div class="product-range mb-2">
                                Range: SwitchBlade x908 GEN2
                            </div>
                            <div class="product-series">
                                Series: '.$sku.'
                            </div>
                        </div>
                    </div>
                </div>';
    endwhile;
    endif;
    die($out); 
}
add_action('wp_ajax_loadmore', 'get_post_loadmore');
add_action('wp_ajax_nopriv_loadmore', 'get_post_loadmore');


function add_search_form($items, $args) {
          if( $args->menu == 'main-menu' ){
          $items .= '<li class="menu-item d-flex justify-content-between align-items-center d-sm-none search-box-mb menu-item menu-item-type-taxonomy menu-item-object-category">'
                  . '<form role="search" method="get" class="search-form-mb" action="'.home_url( '/' ).'">'
                  . '<label>'
                  . '<span class="screen-reader-text">' . _x( 'Search for:', 'label' ) . '</span>'
                  . '<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search Keywords', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' ) . '" />'
                  . '</label>'
                  . '<button type="submit" class="search-submit" value="'. esc_attr_x('Search', 'submit button') .'"><i class="far fa-search"></i></button>'
                  . '</form>'
                  . '</li>';
          }
        return $items;
}      