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
    wp_localize_script(
        'custom-js',
        'giaOtherInsurance',
        array(
            'ajaxUrl'                   => admin_url( 'admin-ajax.php' ),
            'nonce'                     => wp_create_nonce( 'gia_load_more_insurance' ),
            'managementCommitteeNonce' => wp_create_nonce( 'gia_load_more_management_committee' ),
            'companyNewsNonce'          => wp_create_nonce( 'gia_load_more_company_news' ),
            'annualReportsNonce'        => wp_create_nonce( 'gia_load_more_annual_reports' ),
        )
    );
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
    return '<form role="search" method="get" id="searchform" class="search-form apat-searchform" action="' . esc_url( home_url( '/' ) ) . '">'
        . '<input type="search" id="s" name="s" value="' . esc_attr( get_search_query() ) . '" placeholder="' . esc_attr__( 'Start typing to see instant results', 'gia' ) . '" aria-label="' . esc_attr__( 'Search for:', 'gia' ) . '" />'
        . '<button type="submit" id="searchsubmit" aria-label="' . esc_attr__( 'Search', 'gia' ) . '"><i class="fas fa-search" aria-hidden="true"></i></button>'
        . '</form>';
}
add_shortcode( 'apat_searchform', 'apat_searchform');


function gia_other_insurance_category_id( $category ) {
    if ( is_numeric( $category ) ) {
        $term = get_term( absint( $category ), 'category' );

        return ( $term && ! is_wp_error( $term ) ) ? (int) $term->term_id : 0;
    }

    $term = get_category_by_slug( sanitize_title( $category ) );

    return $term ? (int) $term->term_id : 0;
}

function gia_render_other_insurance_item( $post_id ) {
    $title = get_the_title( $post_id );
    $image = get_the_post_thumbnail(
        $post_id,
        'large',
        array(
            'class' => 'gia-other-insurance__image',
            'alt'   => $title,
        )
    );

    if ( ! $image ) {
        $image = '<div class="gia-other-insurance__image-placeholder" aria-hidden="true"></div>';
    }

    $excerpt = wp_trim_words( get_the_excerpt( $post_id ), 48, '...' );

    return '<article class="gia-other-insurance__item">'
        . '<div class="gia-other-insurance__media">' . $image . '</div>'
        . '<div class="gia-other-insurance__content">'
        . '<h3 class="gia-other-insurance__title">' . esc_html( $title ) . '</h3>'
        . '<p class="gia-other-insurance__excerpt">' . esc_html( $excerpt ) . '</p>'
        . '<a class="gia-other-insurance__read-more" href="' . esc_url( get_permalink( $post_id ) ) . '">'
        . esc_html__( 'Read More', 'gia' )
        . '</a></div></article>';
}

function apat_other_types_of_insurance( $atts ) {
    $atts = shortcode_atts(
        array(
            'category' => 'other-types-of-insurance',
        ),
        $atts,
        'apat_other_types_of_insurance'
    );
    $category_id = gia_other_insurance_category_id( $atts['category'] );

    if ( ! $category_id ) {
        return '<p class="gia-other-insurance__error" role="alert">'
            . esc_html__( 'Insurance category not found. Check the shortcode category attribute.', 'gia' )
            . '</p>';
    }

    $query = new WP_Query(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 3,
            'paged'               => 1,
            'cat'                 => $category_id,
            'ignore_sticky_posts' => true,
        )
    );

    if ( ! $query->have_posts() ) {
        wp_reset_postdata();
        return '';
    }

    $items = '';
    while ( $query->have_posts() ) {
        $query->the_post();
        $items .= gia_render_other_insurance_item( get_the_ID() );
    }
    wp_reset_postdata();

    $output  = '<div class="gia-other-insurance" data-category="' . esc_attr( $category_id ) . '" data-page="1">';
    $output .= '<div class="gia-other-insurance__items">' . $items . '</div>';

    if ( $query->max_num_pages > 1 ) {
        $output .= '<button class="gia-other-insurance__load-more" type="button">'
            . esc_html__( 'Load More', 'gia' )
            . '</button>';
    }

    return $output . '</div>';
}
add_shortcode( 'apat_other_types_of_insurance', 'apat_other_types_of_insurance');

function load_more_types_of_insurance_ajax() {
    $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'gia_load_more_insurance' ) ) {
        wp_send_json_error(
            array( 'message' => __( 'Your session has expired. Please reload the page.', 'gia' ) ),
            403
        );
    }

    $category_id = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
    $page        = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 0;

    if ( ! gia_other_insurance_category_id( $category_id ) || $page < 2 ) {
        wp_send_json_error(
            array( 'message' => __( 'Unable to load more posts.', 'gia' ) ),
            400
        );
    }

    $query = new WP_Query(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 3,
            'paged'               => $page,
            'cat'                 => $category_id,
            'ignore_sticky_posts' => true,
        )
    );

    $items = '';
    while ( $query->have_posts() ) {
        $query->the_post();
        $items .= gia_render_other_insurance_item( get_the_ID() );
    }
    wp_reset_postdata();

    wp_send_json_success(
        array(
            'html'     => $items,
            'has_more' => $query->max_num_pages > $page,
        )
    );
}
add_action('wp_ajax_nopriv_load_more_types_of_insurance_ajax', 'load_more_types_of_insurance_ajax');
add_action('wp_ajax_load_more_types_of_insurance_ajax', 'load_more_types_of_insurance_ajax');

function gia_render_company_news_items( $query ) {
    $items   = '';
    $logo_id = absint( get_theme_mod( 'custom_logo' ) );

    while ( $query->have_posts() ) {
        $query->the_post();
        $post_id  = get_the_ID();
        $title    = get_the_title( $post_id );
        $permalink = get_permalink( $post_id );
        $image    = get_the_post_thumbnail(
            $post_id,
            'large',
            array(
                'class' => 'gia-company-news__image',
                'alt'   => $title,
            )
        );

        if ( ! $image ) {
            $image = $logo_id
                ? wp_get_attachment_image(
                    $logo_id,
                    'large',
                    false,
                    array(
                        'class' => 'gia-company-news__image',
                        'alt'   => '',
                    )
                )
                : '';
        }

        if ( ! $image ) {
            $image = '<span class="gia-company-news__image-placeholder" aria-hidden="true"></span>';
        }

        $items .= '<article class="gia-company-news__item">'
            . '<a class="gia-company-news__media" href="' . esc_url( $permalink ) . '" aria-label="' . esc_attr( $title ) . '">'
            . $image
            . '</a>'
            . '<div class="gia-company-news__content">'
            . '<h2 class="gia-company-news__title"><a href="' . esc_url( $permalink ) . '">' . esc_html( $title ) . '</a></h2>'
            . '<time class="gia-company-news__date" datetime="' . esc_attr( get_the_date( DATE_W3C, $post_id ) ) . '">'
            . '<i class="far fa-calendar-alt" aria-hidden="true"></i>'
            . esc_html( get_the_date( 'M j, Y', $post_id ) )
            . '</time>'
            . '</div></article>';
    }

    return $items;
}

function gia_load_more_company_news_ajax() {
    $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'gia_load_more_company_news' ) ) {
        wp_send_json_error(
            array( 'message' => __( 'Your session has expired. Please reload the page.', 'gia' ) ),
            403
        );
    }

    $category_id = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
    $page        = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 0;
    $category    = get_term( $category_id, 'category' );

    if (
        ! $category instanceof WP_Term ||
        ! in_array( $category->slug, array( 'media-center', 'company-news' ), true ) ||
        $page < 2 ||
        $page > 100
    ) {
        wp_send_json_error(
            array( 'message' => __( 'Unable to load more company news.', 'gia' ) ),
            400
        );
    }

    $query = new WP_Query(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 20,
            'paged'               => $page,
            'cat'                 => $category_id,
            'orderby'             => 'date',
            'order'               => 'DESC',
            'ignore_sticky_posts' => true,
        )
    );

    wp_send_json_success(
        array(
            'html'     => gia_render_company_news_items( $query ),
            'has_more' => $query->max_num_pages > $page,
        )
    );
}
add_action( 'wp_ajax_nopriv_load_more_company_news_ajax', 'gia_load_more_company_news_ajax' );
add_action( 'wp_ajax_load_more_company_news_ajax', 'gia_load_more_company_news_ajax' );

function gia_render_annual_report_items( $query ) {
    $items = '';

    while ( $query->have_posts() ) {
        $query->the_post();
        $post_id = get_the_ID();
        $title   = get_the_title( $post_id );
        $image   = get_the_post_thumbnail(
            $post_id,
            'large',
            array(
                'class' => 'gia-annual-reports__image',
                'alt'   => $title,
            )
        );

        if ( ! $image ) {
            $image = '<span class="gia-annual-reports__image-placeholder" aria-hidden="true"></span>';
        }

        $pdf_id              = absint( get_post_meta( $post_id, '_post_pdf_file_id', true ) );
        $pdf_url             = $pdf_id ? wp_get_attachment_url( $pdf_id ) : '';

        $items .= '<article class="gia-annual-reports__item">'
            . '<a class="gia-annual-reports__link" href="' . esc_url( $pdf_url ) . '" aria-label="' . esc_attr( sprintf( __( 'View %s', 'gia' ), $title ) ) . '">'
            . '<span class="gia-annual-reports__media">'
            . $image
            . '<span class="gia-annual-reports__overlay"><span class="gia-annual-reports__view"><i class="fas fa-search" aria-hidden="true"></i><span>' . esc_html__( 'View More', 'gia' ) . '</span></span></span>'
            . '</span>'
            . '<span class="gia-annual-reports__title">' . esc_html( $title ) . '</span>'
            . '</a></article>';
    }

    return $items;
}

function gia_load_more_annual_reports_ajax() {
    $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'gia_load_more_annual_reports' ) ) {
        wp_send_json_error(
            array( 'message' => __( 'Your session has expired. Please reload the page.', 'gia' ) ),
            403
        );
    }

    $category_id = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
    $page        = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 0;
    $category    = get_term( $category_id, 'category' );

    if ( ! $category instanceof WP_Term || 'annual-reports' !== $category->slug || $page < 2 || $page > 100 ) {
        wp_send_json_error(
            array( 'message' => __( 'Unable to load more annual reports.', 'gia' ) ),
            400
        );
    }

    $query = new WP_Query(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 9,
            'paged'               => $page,
            'cat'                 => $category_id,
            'orderby'             => 'date',
            'order'               => 'DESC',
            'ignore_sticky_posts' => true,
        )
    );

    wp_send_json_success(
        array(
            'html'     => gia_render_annual_report_items( $query ),
            'has_more' => $query->max_num_pages > $page,
        )
    );
}
add_action( 'wp_ajax_nopriv_load_more_annual_reports_ajax', 'gia_load_more_annual_reports_ajax' );
add_action( 'wp_ajax_load_more_annual_reports_ajax', 'gia_load_more_annual_reports_ajax' );

function gia_render_management_committee_items( $query ) {
    $output = '';
    $index  = 0;

    while ( $query->have_posts() ) {
        $query->the_post();

        if ( 0 === $index % 5 ) {
            $output .= '<div class="gia-committee__row">';
        }

        $post_id = get_the_ID();
        $title   = get_the_title( $post_id );
        $image   = get_the_post_thumbnail(
            $post_id,
            'medium',
            array(
                'class' => 'gia-committee__image',
                'alt'   => $title,
            )
        );

        if ( ! $image ) {
            $image = '<span class="gia-committee__image-placeholder" aria-hidden="true"></span>';
        }

        $content = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );

        $output .= '<article class="gia-committee__item">'
            . '<button class="gia-committee__card" type="button" aria-expanded="false">'
            . $image
            . '<span class="gia-committee__name">' . esc_html( $title ) . '</span>'
            . '</button>'
            . '<template class="gia-committee__detail-source">'
            . '<div class="gia-committee__detail-image">' . $image . '</div>'
            . '<div class="gia-committee__detail-copy">'
            . '<h3 class="gia-committee__detail-title">' . esc_html( $title ) . '</h3>'
            . '<div class="gia-committee__detail-content">' . wp_kses_post( $content ) . '</div>'
            . '</div></template></article>';

        if ( 4 === $index % 5 || $index === $query->post_count - 1 ) {
            $output .= '<div class="gia-committee__detail-panel" hidden>'
                . '<button class="gia-committee__close" type="button" aria-label="' . esc_attr__( 'Close details', 'gia' ) . '">&times;</button>'
                . '<div class="gia-committee__detail-panel-content"></div></div></div>';
        }

        ++$index;
    }

    wp_reset_postdata();

    return $output;
}

function apat_management_committee( $atts ) {
    $atts = shortcode_atts(
        array(
            'cat_id' => 9,
            'limit'  => 10,
        ),
        $atts,
        'apat_management_committee'
    );
    $category_id = absint( $atts['cat_id'] );
    $limit       = absint( $atts['limit'] );
    $limit       = min( 50, $limit ? $limit : 10 );

    if ( ! $category_id || ! term_exists( $category_id, 'category' ) ) {
        return '<p class="gia-committee__error" role="alert">'
            . esc_html__( 'Management committee category not found.', 'gia' )
            . '</p>';
    }

    $query = new WP_Query(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => $limit,
            'paged'               => 1,
            'cat'                 => $category_id,
            'orderby'             => array(
                'menu_order' => 'ASC',
                'date'       => 'DESC',
            ),
            'ignore_sticky_posts' => true,
        )
    );

    if ( ! $query->have_posts() ) {
        wp_reset_postdata();
        return '';
    }

    $items = gia_render_management_committee_items( $query );
    $committee_class = 9 === $category_id ? ' gia-committee--management' : '';
    $output = '<div class="gia-committee' . esc_attr( $committee_class ) . '" data-category="' . esc_attr( $category_id ) . '" data-limit="' . esc_attr( $limit ) . '" data-page="1">'
        . '<div class="gia-committee__rows">' . $items . '</div>';

    if ( $query->max_num_pages > 1 ) {
        $output .= '<button class="gia-committee__load-more" type="button">'
            . esc_html__( 'Load More', 'gia' )
            . '</button>';
    }

    return $output . '</div>';
}
add_shortcode( 'apat_management_committee', 'apat_management_committee');


function load_more_management_committee_ajax() {
    $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'gia_load_more_management_committee' ) ) {
        wp_send_json_error(
            array( 'message' => __( 'Your session has expired. Please reload the page.', 'gia' ) ),
            403
        );
    }

    $category_id = isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0;
    $limit       = isset( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 10;
    $limit       = min( 50, $limit ? $limit : 10 );
    $page        = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 0;

    if ( ! $category_id || ! term_exists( $category_id, 'category' ) || $page < 2 || $page > 100 ) {
        wp_send_json_error(
            array( 'message' => __( 'Unable to load more committee members.', 'gia' ) ),
            400
        );
    }

    $query = new WP_Query(
        array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => $limit,
            'paged'               => $page,
            'cat'                 => $category_id,
            'orderby'             => array(
                'menu_order' => 'ASC',
                'date'       => 'DESC',
            ),
            'ignore_sticky_posts' => true,
        )
    );

    $items = gia_render_management_committee_items( $query );

    wp_send_json_success(
        array(
            'html'     => $items,
            'has_more' => $query->max_num_pages > $page,
        )
    );
}
add_action('wp_ajax_nopriv_load_more_management_committee_ajax', 'load_more_management_committee_ajax');
add_action('wp_ajax_load_more_management_committee_ajax', 'load_more_management_committee_ajax');