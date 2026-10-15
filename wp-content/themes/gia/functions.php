<?php
/**
 * Define variable
 */
define('THEME_URL', get_template_directory_uri());
define('CHILD_THEME_URL', get_stylesheet_directory_uri());
add_filter('use_block_editor_for_post', '__return_false', 10);

function wpml_add_classes($classes) {
    global $sitepress;
    if( is_a($sitepress, 'SitePress') && method_exists( $sitepress, 'get_current_language' ) ) {
        $classes[] = 'wpml-lang-'.$sitepress->get_current_language();
    }
    return $classes;
}
add_action('body_class', 'wpml_add_classes');
/**
 * REQUIRED FILES
 */
//require THEME_URL . '/inc/template-tags.php';
require get_template_directory() . '/inc/post-types.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function atap_theme_support() {

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

    //ake theme available for translation.
    $language_folder = get_template_directory() . '/languages';
    load_theme_textdomain( 'atap', $language_folder );

}
add_action( 'after_setup_theme', 'atap_theme_support' );

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
function atap_register_styles() {

    $theme_version = wp_get_theme()->get( 'Version' );
    wp_enqueue_style( 'bootstrap-css', THEME_URL . '/assets/css/bootstrap.css', array(), $theme_version );
    wp_enqueue_style( 'bootstrap-select-css', THEME_URL . '/assets/css/bootstrap-select.min.css', array(), $theme_version );
    wp_enqueue_style( 'carousel-css', THEME_URL . '/assets/css/owl.carousel.css', array(), $theme_version );
    wp_enqueue_style( 'stylesheet-css', THEME_URL . '/assets/css/all.css', array(), $theme_version );
    wp_enqueue_style( 'style-css', get_stylesheet_uri(), array(), $theme_version );
    wp_enqueue_style( 'custom-css', THEME_URL . '/assets/css/custom.css', array());
    wp_enqueue_style( 'responsive-css', THEME_URL . '/assets/css/responsive.css', array());
}
add_action( 'wp_enqueue_scripts', 'atap_register_styles' );


/**
 * Register and Enqueue Scripts.
 */
function atap_register_scripts() {

    $theme_version = wp_get_theme()->get( 'Version' );
    wp_enqueue_script( 'jquery-js', THEME_URL . '/assets/js/jquery.min.js', array(), $theme_version );
    wp_enqueue_script( 'popper.min-js', THEME_URL . '/assets/js/popper.min.js', array(), $theme_version );
    wp_enqueue_script( 'bootstrap-js', THEME_URL . '/assets/js/bootstrap.min.js', array(), $theme_version );
    wp_enqueue_script( 'select-js', THEME_URL . '/assets/js/bootstrap-select.min.js', array(), $theme_version );
    wp_enqueue_script( 'matchHeight-js', THEME_URL . '/assets/js/jquery.matchHeight-min.js', array(), $theme_version );
    wp_enqueue_script( 'carousel-js', THEME_URL . '/assets/js/owl.carousel.min.js', array(), $theme_version );
    wp_enqueue_script( 'custom-js', THEME_URL . '/assets/js/custom.js', array());
    wp_script_add_data( 'async-js', 'async', true );

}
add_action( 'wp_enqueue_scripts', 'atap_register_scripts' );


/**
 * Register navigation menus uses wp_nav_menu in five places.
 */
function atap_menus() {

    $locations = array(
        'primary'  => __( 'Desktop Top Menu', 'atap' ),
        'expanded' => __( 'Desktop Main Menu', 'atap' ),
        'mobile'   => __( 'Mobile Menu', 'atap' ),
        'footer'   => __( 'Footer Menu', 'atap' ),
        'social'   => __( 'Social Menu', 'atap' ),
    );

    register_nav_menus( $locations );
}
add_action( 'init', 'atap_menus' );


/**
 * Get the information about the logo.
 *
 * @param string $html The HTML output from get_custom_logo (core function).
 * @return string
 */
function atap_get_custom_logo( $html ) {

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
add_filter( 'get_custom_logo', 'atap_get_custom_logo' );

/**
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function atap_sidebar_registration() {

	// Arguments used in all register_sidebar() calls.
	$shared_args = array(
		'before_title'  => '<h2 class="widget-title subheading heading-size-3">',
		'after_title'   => '</h2>',
		'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
		'after_widget'  => '</div></div>',
	);

	// Footer #1.
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name'        => __( 'Footer #1', 'atap' ),
				'id'          => 'sidebar-1',
				'description' => __( 'Widgets in this area will be displayed in the first column in the footer.', 'atap' ),
			)
		)
	);

	// Footer #2.
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name'        => __( 'Footer #2', 'atap' ),
				'id'          => 'sidebar-2',
				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'atap' ),
			)
		)
	);
    
    register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name'        => __( 'Language Switcher', 'atap' ),
				'id'          => 'sidebar-3',
				'description' => __( 'Language Switcher', 'atap' ),
			)
		)
	);
}

add_action( 'widgets_init', 'atap_sidebar_registration' );


/**
 * Overwrite default more tag with styling and screen reader markup.
 */
function atap_read_more_tag( $html ) {
    return preg_replace( '/<a(.*)>(.*)<\/a>/iU', sprintf( '<div class="read-more-button-wrap"><a$1><span class="faux-button">$2</span> <span class="screen-reader-text">"%1$s"</span></a></div>', get_the_title( get_the_ID() ) ), $html );
}
add_filter( 'the_content_more_link', 'atap_read_more_tag' );

function atap_news_shortcode($params) {
    $args = array(
                'post_type' => 'post',
                'cat' => $params['id'],
                'post_status' => 'publish',
                'order' => 'DESC',
                'orderby' => 'date',
                'posts_per_page' => $params['limit'],
            );
    $get_post = new WP_Query( $args );
    $html = '<div class="news_latest">';
    while ( $get_post->have_posts() ) : $get_post->the_post();  
        $html .='<div class="post-box row">';  
        $html .='   <div class="col-md-3 post-date">'.get_the_date('d F Y').'</div>';               
        $html .='   <div class="col-md-9"><a href="'.get_the_permalink().'">'.get_the_title().'</a></div>';         
        $html .='</div>';      
    endwhile;
    $html .= '</div>';
    wp_reset_postdata();
    return $html;
}
add_shortcode( 'atap_news', 'atap_news_shortcode');

function atap_carousel_shortcode($params) {
    $args = array(
                'post_type' => 'post',
                'cat' => $params['id'],
                'post_status' => 'publish',
                'order' => 'DESC',
                'orderby' => 'date',
                'posts_per_page' => $params['limit'],
            );
    $get_post = new WP_Query( $args );
    $html = '<div class="atap_carousel-'.$params['id'].' owl-carousel owl-theme cus-carousel">';
    while ( $get_post->have_posts() ) : $get_post->the_post();  
        $url = get_the_permalink();
        $post_tags = get_the_tags();
        $tags_name = $first = ''; 
        if ( $post_tags ) {
            foreach( $post_tags as $tag ) {
                $tags_name .= $first.$tag->name; 
                $first = ', ';
            }
        }
        $image_url = (get_the_post_thumbnail_url())?get_the_post_thumbnail_url():site_url().'/wp-content/uploads/no-image.jpg';

        $html .='<div class="slide_content">
                <div class="carousel-box">  
                    <a href="'.$url.'" class="boxImage" style="background-image: url('.$image_url.');"></a>
                    <div class="post-content" data-mh="carousel-group">
                        <div class="post-tags">'.$tags_name.'</div>
                        <div class="post-meta">
                            <h5><a href="'.$url.'">'.get_the_title().'</a></h5>
                        </div>
                        <div class="post-date">'.get_the_date('d F Y').'</div>
                    </div>
                </div>
              </div>';    

    endwhile;
    $html .= '</div>';
    $html .= '<script>$( document ).ready(function() {
        $(".atap_carousel-'.$params['id'].'").owlCarousel({
            nav: true,
            loop:true,
            dots: false,
            margin: 15,
            navText: [\'<i class="fal fa-arrow-left"></i>\', \'<i class="fal fa-arrow-right"></i>\'],
            responsive: {
                0: {items: 1},
                600: {items: 2},
                1000: {items: 3}
            }
        });
    });</script>';
    
    wp_reset_postdata();
    return $html;
}
add_shortcode( 'atap_carousel', 'atap_carousel_shortcode');

function customfield_slider_shortcode($params) {
    
    $html = '<div class="customfield_carousel">';
    $html .='<div class="row ">';
    if( have_rows($params['id'], 'options') ){  
       
        while( have_rows($params['id'], 'options') ) : the_row(); 
            $image = get_sub_field('image');
            $html .='<div class="col-md-4 pb-3">
                    <div class="image-box item" style="background-image: url('.$image['url'].');">  
                        <div class="background-overlay"></div>
                        <div class="fied-content">
                            <h4 class="title">'.get_sub_field('title').'</h4>
                            <a href="'.get_sub_field('learn_more').'" target="_self">
                                <span class="learn_more">'.__('Learn More', 'atap').' &nbsp; <i aria-hidden="true" class="fas fa-arrow-right"></i></span>
                            </a>
                        </div>
                    </div>
                  </div>';    
    
        endwhile;
        $html .= '</div>';
        $html .= '</div>';
        /*$limit = (isset($params['limit']))?$params['limit']:4;
        $html .= '<script>$( document ).ready(function() {                      
                $(".customfield_slider-'.$params['id'].'").owlCarousel({
                    nav: true,                  
                    dots: false,
                    margin: 10,
                    navText: [\'<i class="fal fa-arrow-left"></i>\', \'<i class="fal fa-arrow-right"></i>\'],
                    responsive: {
                        0: {items: 1},
                        600: {items: 2},
                        1000: {items: '.$limit.'}
                    }
                });              
        });</script>';*/  
    }
    return $html;
}
add_shortcode( 'customfield_slider', 'customfield_slider_shortcode');

function header_search_shortcode(){
    $html ='<div class="search_box" id="search_box"> 
                <a href="#" class="close_menu" id="close_menu_search"></a>
                <div class="search_input">
                    <form role="search" method="get" class="search-form" action="'.esc_url( home_url( '/' ) ).'"> 
                        <input name="s" type="search" value="'.get_search_query().'" size="25" class="search_text" placeholder="'.__('Search Keywords ...', 'atap').'"> 
                        <button class="sbtn" id="sbtn" type="button">sbtn</button>
                     </form>
                </div>
                <p>'.__('Hit enter to search or ESC to close', 'atap').'</p>
            </div>';
    return $html;        
}
add_shortcode( 'search-box', 'header_search_shortcode');


function apat_breadcrumbs() {
    $delimiter = '<span class="delimiter"> &nbsp / &nbsp </span>';
    $home = __('Home', 'atap'); 
    $before = '<span class="current">'; 
    $after = '</span>'; 
    if ( !is_home() && !is_front_page() || is_paged() ) {
        echo '<ul id="breadcrumbs">';
        global $post;
        $homeLink = get_bloginfo('url');
        echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if ( is_category() ) {
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
            echo $before . single_cat_title('', false) . $after;
        } 
        elseif ( is_day() ) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif ( is_month() ) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;
        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
                echo $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo $before . get_the_title() . $after;
            }
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif ( is_attachment() ) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif ( is_page() && !$post->post_parent ) {
            echo $before . get_the_title() . $after;
        } elseif ( is_page() && $post->post_parent ) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif ( is_search() ) {
            echo $before . __('Search results for ', 'atap') . get_search_query() . '"' . $after;
        } elseif ( is_tag() ) {
            echo $before .  __('Posts tagged ', 'atap') . single_tag_title('', false) . '"' . $after;
        } elseif ( is_author() ) {
            global $author;
            echo $before . __('Articles posted by ', 'atap') . $userdata->display_name . $after;
        } elseif ( is_404() ) {
            echo $before . __('Error 404', 'atap') . $after;
        }
        echo '</ul>';
    }
}
add_shortcode( 'apat_breadcrumbs', 'apat_breadcrumbs');

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
                        $out .= '<a class="more-link" href="'.  get_permalink().'">'. __('Read More', 'atap') .' &nbsp <i class="fal fa-arrow-right"></i></a>
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


function event_post_ajax(){
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
        $image_url = (get_the_post_thumbnail_url())?get_the_post_thumbnail_url():site_url().'/wp-content/uploads/no-image.jpg';
        
        $out .= '<div class="col-md-4" >
                <div class="carousel-box">  
                    <a href="'.get_permalink().'" class="boxImage" style="background-image: url('.$image_url.');"></a>
                    <div class="post-content" data-mh="carousel-group">';
                        if ( ! empty( $categories ) ) {
                            foreach($categories as $category) {
                                if($curent_id == $category->term_id) continue;
                                $out .= '<div class="post-tags">'.esc_html( $category->name ).'</div>';
                            }
                        }
                        $out .= '<div class="post-meta">
                            <h5><a href="'.get_permalink().'">'.get_the_title().'</a></h5>
                        </div>
                        <div class="post-date">'.get_the_date('d F Y').'</div>
                    </div>
                </div>
             </div>';

    endwhile;
    endif;
    wp_reset_postdata();
    die($out);
}

add_action('wp_ajax_nopriv_event_post_ajax', 'event_post_ajax');
add_action('wp_ajax_event_post_ajax', 'event_post_ajax');


add_filter( 'get_the_archive_title', function ( $title ) {
    if( is_category()) {
        $title = single_cat_title( '', false );
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    }
    return $title;
});

if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'GIA Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    )); 
}

function atap_excerpt($word_count_limit) {
    $content = wp_strip_all_tags(get_the_content() , true );
    echo wp_trim_words($content, $word_count_limit);
}


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


function show_subcat(){

    if(isset($_POST["parentId"])){
        $term_id = $_POST["parentId"];
        $text=$_POST["text"]; 
        $args = array(
              'hide_empty' => 0,
              'orderby' => 'name',
              'depth' => 1,
              'parent' => $term_id,
              'order' => 'ASC',
              'taxonomy' => 'product-cat'
            );
        $term_children = get_categories($args);
        $out .='<option value="" disabled selected>--- '.$text.' ---</option>';
        foreach ( $term_children as $child ) {
            $out .= '<option value="'.$child->term_id.'">'.$child->name.'</option>';
        } 
    }
    else{
        $out .= '';
    }
   die($out);
}

add_action('wp_ajax_nopriv_show_subcat', 'show_subcat');
add_action('wp_ajax_show_subcat', 'show_subcat');


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

function campaign_filter_product(){
  
    $paged = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
    $keyword = (isset($_POST['keyword'])) ? $_POST['keyword'] : '';
    $orderby = (isset($_POST['orderby'])) ? $_POST['orderby'] : '';
    
    $args = array(
        'post_type' => 'product',
        'tax_query' => array(                 
          array(
            'taxonomy' => 'product-type',               
            'field' => 'slug', 
            'terms' => 'campaign'
          )),
        'paged' => $paged,
        's'     => $keyword,
        'posts_per_page'=> 9
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
        
        $out .= '<div class="col-12 col-md-4 mb-4 pb-2">
                    <div class="product-item">
                        <div class="product-image">
                            <a href="'.get_the_permalink().'">
                                <img src="'.$image_url.'">
                                <span class="product-cat">Campaign</span>     
                             </a>
                        </div>
                        <div class="product-meta">';
                            $categories = get_the_terms( $product->ID, 'product-cat' );
                                foreach ($categories as $categorie) {
                                    if($categorie->parent == 0){
                                       $out .= '<div class="product-cate text-capitalize mb-2">'.$categorie->name.'</div>';
                                    }
                                }
                            $out .= '<h3 class="product-title mb-4"><a href="'.get_the_permalink().'">'. get_the_title().'</a></h3>';
                                if( get_field('campaign_duration') ){
                                            $out .= '<div class="date-stock">'.get_field('campaign_duration').'</div>';  
                                } 
                        
                           
                        $out .= '</div>
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

add_action('wp_ajax_nopriv_campaign_filter_product', 'campaign_filter_product');
add_action('wp_ajax_campaign_filter_product', 'campaign_filter_product');


add_action( 'wpcf7_init', 'postselect2' );
function postselect2() {
    wpcf7_add_form_tag( 'listproduct', 'custom_post_select2', true );
}
function custom_post_select2( $tag ) {

    $output = '<div class="position-relative select-box"><input type="search" id="brand-filter" placeholder="Please select" name="product" data-list="product-list" autocomplete="off" required />
            <label for="brand-filter" data-icon=""></label></div><datalist id="product-list"><select>';

                    if( have_rows('product_list','option') ):

                        while( have_rows('product_list','option') ) : the_row();

                             $output .= '<option value="'. get_sub_field('product_name','option') .'">'.get_sub_field('product_name','option').'</option>';

                        endwhile;

                    else :
                   
                    endif;
   
    $output .= '</select></datalist>';
    return $output;
} 
// function custom_post_select2( $tag ) {
  
//     $args=array(
//         'post_type' => 'product',
//         'posts_per_page' => -1
//      );
//     $loop = get_posts( $args );
//     $output = '<div class="position-relative select-box"><input type="search" id="brand-filter" placeholder="Please select" name="product" data-list="product-list" autocomplete="off" required />
//             <label for="brand-filter" data-icon=""></label></div><datalist id="product-list"><select>';
  
//     foreach ( $loop as $product ) {
//         $output .= '<option value="'. $product->post_title .'">'.$product->post_title.'</option>';
//     } 
//     $output .= '</select></datalist>';
//     return $output;
// } 

function add_search_form($items, $args) {
          if( $args->menu == 'main-menu' ){
          $items .= '<li class="menu-item d-flex justify-content-between align-items-center d-sm-none search-box-mb menu-item menu-item-type-taxonomy menu-item-object-category">'
                  . '<form role="search" method="get" class="search-form-mb" action="'.home_url( '/' ).'">'
                  . '<label>'
                  . '<span class="screen-reader-text">' . _x( 'Search for:', 'label' ) . '</span>'
                  . '<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search Keywords', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' ) . '" />'
                  . '</label>'
                  . '<button type="submit" class="search-submit" value="'. esc_attr_x('Search', 'submit button') .'"><i class="far fa-search"></i></button>'
                  . '</form><div class="languages-box"><span><i aria-hidden="true" class="fas fa-globe"></i>EN</span></div>'
                  . '</li>';
          }
        return $items;
}
add_filter( 'document_title_separator', 'atap_document_title_separator' );
function atap_document_title_separator( $sep ) {

    $sep = "|";

    return $sep;

}

add_action( 'wpcf7_before_send_mail', 'create_contact_form_pdf' );
function create_contact_form_pdf($cf7)
{
    //check if it is the registration form
    if ($cf7->id != 3802)
        return;
    
    //include pdf generation file//
    require_once('class/tcpdf_cf7.php');
    $submission = WPCF7_Submission::get_instance();
    if ($submission){
        $posted_data = $submission->get_posted_data();
        
        $products = array(); 
        //set default        
        $product = new stdClass();
        $product->product_name = $posted_data['product'];
        $product->number_product = $posted_data['number-of-product'][0];
        //$product->qty = $posted_data['quantity'];
        $product->qty = 1;
        $products[0] = $product;
        //set array                
        if($posted_data['product_name']){        
            for($i = 1; $i <= count($posted_data['product_name']); $i++){
                $product = new stdClass();
                $product->product_name = $posted_data['product_name'][$i];
                $product->number_product = $posted_data['number_product'][$i];
                //$product->qty = $posted_data['qty'][$i];  
                $product->qty = 1;                                               
                $products[$i] = $product;       
            }                
        }
        
        //get from config        
        $product_list = array();                      
        if( have_rows('product_list','option') ):
            while( have_rows('product_list','option') ) : the_row();
                $product = new stdClass();  
                $product->product_name = get_sub_field('product_name','option');
                $product->limit1 = get_sub_field('limit1','option');
                $product->limit2 = get_sub_field('limit2','option');
                $product->limit3 = get_sub_field('limit3','option');                                                
                $product_list[$product->product_name] = $product;                 
            endwhile;
        endif;  
        $partner_country = array();                      
        if( have_rows('partner_country','option') ):
            while( have_rows('partner_country','option') ) : the_row();
                $country = new stdClass();  
                $country->country = get_sub_field('country','option');
                $country->address_details = get_sub_field('address_details','option');                                                
                $partner_country[$country->country] = $country;                 
            endwhile;
        endif;  
        
        
        
        //render html                                
        $params = array();
        $params['$partner_country'] = '';
        if(isset($partner_country[$posted_data['partner-country'][0]]->address_details)){
            $params['$partner_country'] = nl2br($partner_country[$posted_data['partner-country'][0]]->address_details);
        }
        
        $table_calulator = '<table cellspacing="0" cellpadding="5" width= "100%">
            <tbody>
            <tr bgcolor="yellow">
            <td style="border: 1px solid #000000;" width= "5%" align="center">'.__('No', 'atap').'</td>
            <td style="border: 1px solid #000000;" width= "40%" align="center">'.__('Product Name', 'atap').'</td>
            <td style="border: 1px solid #000000;" width= "15%" align="center">'.__('Quantity', 'atap').'</td>
            <td style="border: 1px solid #000000;" width= "20%" align="center">'.__('Unit Price', 'atap').'</td>
            <td style="border: 1px solid #000000;" width= "20%" align="center">'.__('Total Price', 'atap').'</td>
            </tr>
            <tr bgcolor="yellow">
            <td style="border: 1px solid #000000;" width= "100%" colspan="5">'.__('Professional Service', 'atap').'</td>
            </tr>';  
            
        $to_currency = 'USD';
        //get price
        $i = 1;
        $subtoal = 0; 
        foreach($products as $product){  
            $item = $product_list[$product->product_name];
            //get price day
            $check = str_replace(" ", "", $product->number_product);
            switch ($check) {
                case "From1to10":
                    $arr = explode('|', $item->limit1);
                    break;
                case "From11to30":
                    $arr = explode('|', $item->limit2);
                    break;
                case "Over31":
                    $arr = explode('|', $item->limit3);
                    break;
            }

            //set price day
            if($arr){
                $price_unit = $price_total = 0;
                if(isset($arr[0]) && $arr[0]){
                    $price_unit = $arr[0];
                    
                    //currencyConverter
                    /*if($from_currency != $to_currency){
                        $price_unit = currencyConverter($price_unit, $from_currency, $to_currency); 
                    }*/
                    //get currency
                    if($posted_data['partner-country'][0] == 'Thailand'){
                        if(isset($arr[1]) && $arr[1]){
                            $price_unit = $arr[1];
                            $to_currency = 'THB';
                        }
                    }
                                                            
                    if($posted_data['partner-country'][0] == 'China'){
                        if(isset($arr[2]) && $arr[2]){
                            $price_unit = $arr[2];
                            $to_currency = 'CNY';
                        }
                    }                  
                    
                    $price_total =  $product->qty * $price_unit;
                    $subtoal += $price_total;
                }
                
            }
              
            $number_product_text = ' x '.$product->number_product.' units'; 
                
            $table_calulator .= '<tr>
                <td width= "5%" style="border: 1px solid #000000;" >'.$i.'</td>
                <td width= "40%" style="border: 1px solid #000000;" >'.$product->product_name.$number_product_text.'</td>
                <td width= "15%" style="border: 1px solid #000000;" align="right">'.$product->qty.'</td>
                <td width= "20%" style="border: 1px solid #000000;" align="right">'.number_format( $price_unit, 0, '.', ',').'</td>
                <td width= "20%" style="border: 1px solid #000000;" align="right">'.currencyFormat($price_total, $to_currency).'</td>
            </tr>';    
            $i++; 
        }
        
        //calulator
        $gst_amt = $subtoal * 7 / 100;   
        $toal = $subtoal + $gst_amt;
        
        $table_calulator .= '
            <tr>
            <td style="border-bottom: none;" width= "80%" align="right" colspan="4">'.__('Sub Total', 'atap').'</td>
            <td style="border: 1px solid #000000;" width= "20%" align="right">'.currencyFormat($subtoal, $to_currency).'</td>
            </tr>
            <tr>
            <td style="border-bottom: none;" width= "80%" align="right" colspan="4">7% GST</td>
            <td style="border: 1px solid #000000;" width= "20%" align="right">'.currencyFormat($gst_amt, $to_currency).'</td>
            </tr>
            <tr>
            <td style="border-bottom: none;" width= "80%" align="right" colspan="4"><strong>'.__('Total', 'atap').'</strong></td>
            <td style="border: 1px solid #000000;" width= "20%" align="right"><strong>'.currencyFormat($toal, $to_currency).'</strong></td>
            </tr>
            </tbody>
            </table>';
        $params['$table_calulator'] = $table_calulator;
        $params['$user_company_name'] = $posted_data['user-company-name'];
        $params['$partner_company_name'] = $posted_data['partner-company-name'];
        $params['$partner-person-name'] = $posted_data['partner-person-name'];
        $params['$partner-person-email'] = $posted_data['partner-person-email'];
        $params['$partner-person-tel'] = $posted_data['partner-person-tel'];
        $params['$date'] = date('d-M-y');
        
        //quotation_no
        global $wpdb; 
        $no = 0;
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cf7_vdata_quotation_no WHERE date = DATE(NOW()) ORDER BY no DESC LIMIT 1", OBJECT );
        if($results[0]){
            $no = $results[0]->no;
        }
        $no++;
        $params['$quotation_no'] = date('Ymd').'-'.str_pad($no, 2, '0', STR_PAD_LEFT);;
        
        // create new PDF document
        $createpdf = new CREATE_PDFCF7();
        //upload path
        $uploads = wp_upload_dir();
        define ('PDF_FILE_PATH',$uploads['basedir']);
        $fname = $createpdf->CREATE_PDFCF7Fn($params, PDF_FILE_PATH);
        //set filenames
        $pdf_filename= PDF_FILE_PATH.$fname;
        //use the same tag used in contact form 7 mail tab
        $submission->add_uploaded_file('submission_pdf', $pdf_filename);
        
        //save quotation_no
        $wpdb->insert('wp_cf7_vdata_quotation_no', array( 
                'cf7_id' => '3802',
                'date' => date('Y-m-d'), 
                'no' => $no,
                'param' => json_encode($posted_data)
            )
        );
    }
}


function currencyConverter($amount, $from_currency, $to_currency) {
    $from_to = $from_currency.'_'.$to_currency;
    $rate = file_get_contents('https://free.currconv.com/api/v7/convert?q='.$from_to.'&compact=ultra&apiKey=3706bf71d621a0f4d40d');
    $rate = json_decode($rate);
    return $amount * $rate->$from_to;
}

function currencyFormat($value, $to_currency = ''){
	$price = number_format ( (float) $value, 2, '.', ',');
	return $price.$to_currency;
}

function register_my_session(){
    if( ! session_id() ) {
        session_start();
    }
}
add_action('init', 'register_my_session');

function solutions_hover_shortcode() {
    $solutions_title = get_field('solutions_title', 'option');
    $solutions_bg = get_field('solutions_bg', 'option');
    $html = '<section class="solutions_hover" style="background-image: url('.$solutions_bg['url'].');">';
    if( have_rows('solutions_hover', 'options') ){  
        $html .= '<div class="container">'; 
        $html .= '   <h2>'.$solutions_title.'</h2>';
        $html .= '   <div class="row">';
        $html .= '       <div class="col-md-6">';
        $html .= '           <div class="row">';
        
                            $i = 0;
                            while( have_rows('solutions_hover', 'options') ) : the_row(); 
                                $html .='<div class="col-md-6">
                                        <a target="_self" class="hover_title" data-id="'.$i.'" data-mh="hover_title" href="'.get_sub_field('learn_more').'">'.get_sub_field('title').'</a>
                                    </div>';   
                                $i++; 
                            endwhile;
                            
        $html .= '          </div>';      
        $html .= '      </div>';  
        
        $html .= '       <div class="col-md-6">';
        
                            $i = 0;
                            while( have_rows('solutions_hover', 'options') ) : the_row(); 
                                $image = get_sub_field('logo');
                                $show = ($i == 0)?'show':'';
                                $html .='<div id="box-hover'.$i.'" class="box-hover '.$show.'">
                                            <h4>'.get_sub_field('title').'</h4>
                                            <p>'.get_sub_field('description').'</p>
                                            <p><img src="'.$image['url'].'" class="size-large" alt=""></p>
                                            <a target="_self" href="'.get_sub_field('learn_more').'">'.__('Learn More', 'atap').' &nbsp; <i aria-hidden="true" class="fas fa-arrow-right"></i></a>';
                                            $download_pdf = get_sub_field('download_pdf');                                            
                                            if($download_pdf){
                                                $html .='<p><a href="'.$download_pdf['url'].'">'.$download_pdf['title'].' <i class="fal fa-download"></i></a></p>'; 
                                            }
                                $html .='</div>';    
                                $i++;
                            endwhile;
 
        $html .= '      </div>';  
        $html .= '  </div>';  
        $html .= '</div>';  
    }
    $html .= '</section>';  
    return $html;
}
add_shortcode( 'solutions_hover', 'solutions_hover_shortcode');

function solutions_tech_shortcode() {
    $html = '<section class="solutions_tech">';
    if( have_rows('solutions_hover', 'options') ){  
        $html .= '<div class="container">'; 
        $html .= '   <div class="row">';
        
            while( have_rows('solutions_hover', 'options') ) : the_row(); 
                $image = get_sub_field('logo');
                $html .= '<div class="col-md-4">
                        <div class="box-item" href="'.get_sub_field('learn_more').'" target="_self">
                            <h4 data-mh="title">'.get_sub_field('title').'</h4>
                            <p data-mh="desc">'.get_sub_field('description').'</p>
                            <p data-mh="logo"><img src="'.$image['url'].'" class="size-large" alt=""></p>
                            <div data-mh="link">
                                <p ><a href="'.get_sub_field('learn_more').'" target="_self">'.__('Learn More', 'atap').' &nbsp; <i aria-hidden="true" class="fas fa-arrow-right"></i></a></p>';
                                $download_pdf = get_sub_field('download_pdf');
                                if($download_pdf){
                                    $html .='<p><a href="'.$download_pdf['url'].'">'.$download_pdf['title'].' <i class="fal fa-download"></i></a></p>'; 
                                }
                 $html .=   '</div>
                        </div>
                    </div>';    
            endwhile;
  
        $html .= '  </div>';  
        $html .= '</div>';  
    }
    $html .= '</section>';  
    return $html;
}
add_shortcode( 'solutions_tech', 'solutions_tech_shortcode');


function more_ondemand_ajax(){
    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 9;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
    $cat = (isset($_POST['cat'])) ? $_POST['cat'] : 0;
    $orderby = (isset($_POST['orderby'])) ? $_POST['orderby'] : 0;
    $s = (isset($_POST['s'])) ? $_POST['s'] : 0;

    header("Content-Type: text/html");
    $args = array(
        's' => $s,
        'post_type' => 'post',
        'post_status' => 'publish',
        'tag' => 'on-demand',
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
        $out .= '<div class="item">  
                     <div class="row">
                         <div class="col-md-9" >
            	            <h4 class="post-title">'. get_the_title() .'</h4>
            	            <div class="post-date">'. get_the_date('l, F d, Y') .'</div>        
                         </div>
                         <div class="col-md-3" >
                            <a class="btn btn-fxwidth" data-toggle="modal" data-target="#videoModal'. get_the_ID() .'">'.__('Watch', 'atap').'</a>
                         </div>
                     </div>
                 </div> 
                 <!-- Modal -->
                <div class="modal fade cus-modal" id="videoModal'.  get_the_ID() .'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <a class="close" data-dismiss="modal" aria-label="Close"><i class="eicon-close"></i></a>
                                <iframe width="100%" height="500" src="'. $youtube_embeb .'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>';

    endwhile;
    endif;
    wp_reset_postdata();
    die($out);
}
add_action('wp_ajax_nopriv_more_ondemand_ajax', 'more_ondemand_ajax');
add_action('wp_ajax_more_ondemand_ajax', 'more_ondemand_ajax');


function more_upcoming_ajax(){
    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 9;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
    $cat = (isset($_POST['cat'])) ? $_POST['cat'] : 0;
    $orderby = (isset($_POST['orderby'])) ? $_POST['orderby'] : 0;
    $s = (isset($_POST['s'])) ? $_POST['s'] : 0;

    header("Content-Type: text/html");
    $args = array(
        's' => $s,
        'post_type' => 'post',
        'post_status' => 'publish',
        'tag' => 'upcoming',
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
        $date_event = get_field('date_event', get_the_ID()); 
        $content = get_the_content(); 
        $out .= '<div class="item">     
                    <div class="row">
                        <div class="col-md-9" >
                            <h4 class="post-title">'.  get_the_title() .'</h4>
                            <div class="post-date">'.  $date_event .'</div>        
                        </div>
                        <div class="col-md-3" >
                            <a class="btn btn-fxwidth" data-toggle="modal" data-target="#detailModal'.  get_the_ID() .'" data-event_name="'. get_the_title() .'" >'.__('View Detail', 'atap').'</a>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade cus-modal" id="detailModal'.  get_the_ID() .'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <a class="close" data-dismiss="modal" aria-label="Close"><i class="eicon-close"></i></a>
                                <h4 class="post-title">'.  get_the_title() .'</h4>
                                '. $content .'
                            </div>
                        </div>
                    </div>
                </div>';

    endwhile;
    endif;
    wp_reset_postdata();
    die($out);
}
add_action('wp_ajax_nopriv_more_upcoming_ajax', 'more_upcoming_ajax');
add_action('wp_ajax_more_upcoming_ajax', 'more_upcoming_ajax');


function more_success_story_ajax(){
    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 9;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
    $cat = (isset($_POST['cat'])) ? $_POST['cat'] : 0;
    $orderby = (isset($_POST['orderby'])) ? $_POST['orderby'] : 0;
    $s = (isset($_POST['s'])) ? $_POST['s'] : 0;

    header("Content-Type: text/html");

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
        $image_url = (get_the_post_thumbnail_url())?get_the_post_thumbnail_url():site_url().'/wp-content/uploads/no-image.jpg';
        $logo = get_field('logo', get_the_ID());
        $download_pdf = get_field('download_pdf', get_the_ID());
        $out .= '<div class="col-md-4" >
                <div class="carousel-box" data-mh="news-group">
                    <a href="'.get_permalink().'" class="boxImage" style="background-image: url('.$image_url.');"></a>
                    <div class="post-content" data-mh="carousel-group">';                    
                        if ( ! empty( $categories ) ) {
                            foreach($categories as $category) {
                                $out .= '<div class="post-tags">'. esc_html( $category->name ) .'</div>';
                            }
                        }
                        
                        $out .= '<div class="post-meta"><h5>'. get_the_title().'</h5></div>
                        <div class="row">';
                        
                        if($logo):
                            foreach($logo as $row) {
                                $out .= '<div class="col-4">
                                    <div class="logo" style="background-image: url('. $row['image'] .');"></div>
                                </div>';
                            } 
                        endif;
                        
                        $out .= '</div>';
                        
                        if( $download_pdf ):
                            $out .= '<a href="'.$download_pdf['url'].'">'.__('Download PDF', 'atap').' <i class="fal fa-download"></i></a>';
                        endif;
                    $out .= '</div>
                    
                </div>
             </div>';

    endwhile;
    endif;
    wp_reset_postdata();
    die($out);
}
add_action('wp_ajax_nopriv_more_success_story_ajax', 'more_success_story_ajax');
add_action('wp_ajax_more_success_story_ajax', 'more_success_story_ajax');
