<?php
/**
 * Add PDF upload field to WordPress Posts.
 */

// 1. Register PDF meta box.
add_action('add_meta_boxes', function () {
    add_meta_box(
        'post_pdf_file',
        'PDF File',
        'render_post_pdf_meta_box',
        'post',
        'normal',
        'default'
    );
});

// 2. Render the field.
function render_post_pdf_meta_box($post) {
    wp_nonce_field('save_post_pdf_file', 'post_pdf_nonce');

    $pdf_id  = get_post_meta($post->ID, '_post_pdf_file_id', true);
    $pdf_url = $pdf_id ? wp_get_attachment_url($pdf_id) : '';

    ?>
    <div class="post-pdf-upload">
        <input
            type="hidden"
            id="post_pdf_file_id"
            name="post_pdf_file_id"
            value="<?php echo esc_attr($pdf_id); ?>"
        />

        <button type="button" class="button" id="upload_post_pdf">
            Select PDF
        </button>

        <button type="button"
                class="button"
                id="remove_post_pdf"
                <?php echo $pdf_url ? '' : 'style="display:none"'; ?>>
            Remove PDF
        </button>

        <p id="post_pdf_preview">
            <?php if ($pdf_url) : ?>
                <a href="<?php echo esc_url($pdf_url); ?>"
                   target="_blank" rel="noopener">
                    View uploaded PDF
                </a>
            <?php else : ?>
                No PDF selected.
            <?php endif; ?>
        </p>
    </div>
    <?php
}

// 3. Load WordPress Media Library.
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
        return;
    }

    $screen = get_current_screen();

    if (!$screen || $screen->post_type !== 'post') {
        return;
    }

    wp_enqueue_media();

    wp_add_inline_script('jquery', "
        jQuery(function($) {
            let pdfFrame;

            $('#upload_post_pdf').on('click', function(e) {
                e.preventDefault();

                if (pdfFrame) {
                    pdfFrame.open();
                    return;
                }

                pdfFrame = wp.media({
                    title: 'Select PDF File',
                    button: { text: 'Use this PDF' },
                    library: { type: 'application/pdf' },
                    multiple: false
                });

                pdfFrame.on('select', function() {
                    const file = pdfFrame
                        .state()
                        .get('selection')
                        .first()
                        .toJSON();

                    if (file.mime !== 'application/pdf') {
                        alert('Please select a PDF file.');
                        return;
                    }

                    $('#post_pdf_file_id').val(file.id);

                    $('#post_pdf_preview').html(
                        $('<a>', {
                            href: file.url,
                            target: '_blank',
                            rel: 'noopener',
                            text: file.filename || 'View uploaded PDF'
                        })
                    );

                    $('#remove_post_pdf').show();
                });

                pdfFrame.open();
            });

            $('#remove_post_pdf').on('click', function(e) {
                e.preventDefault();

                $('#post_pdf_file_id').val('');
                $('#post_pdf_preview').text('No PDF selected.');
                $(this).hide();
            });
        });
    ");
});

// 4. Save the PDF attachment ID.
add_action('save_post', function ($post_id) {
    if (
        !isset($_POST['post_pdf_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST['post_pdf_nonce'])),
            'save_post_pdf_file'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        wp_is_post_revision($post_id) ||
        get_post_type($post_id) !== 'post' ||
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $pdf_id = isset($_POST['post_pdf_file_id'])
        ? absint($_POST['post_pdf_file_id'])
        : 0;

    if ($pdf_id) {
        $mime = get_post_mime_type($pdf_id);

        if ($mime !== 'application/pdf') {
            return;
        }

        update_post_meta(
            $post_id,
            '_post_pdf_file_id',
            $pdf_id
        );
    } else {
        delete_post_meta($post_id, '_post_pdf_file_id');
    }
});



/**
 * Add Banner Image field to WordPress Categories.
 */

// 1. Add field when creating a category.
add_action('category_add_form_fields', function () {
    ?>
    <div class="form-field term-banner-wrap">
        <label for="category_banner_id">Banner Image</label>

        <input type="hidden"
               id="category_banner_id"
               name="category_banner_id"
               value="">

        <div id="category_banner_preview"></div>

        <button type="button"
                class="button category-banner-upload">
            Upload / Select Banner
        </button>

        <button type="button"
                class="button category-banner-remove"
                style="display:none;">
            Remove Banner
        </button>

        <p>Select a banner image from the Media Library.</p>
    </div>
    <?php
});

// 2. Add field when editing a category.
add_action('category_edit_form_fields', function ($term) {
    $image_id  = get_term_meta(
        $term->term_id,
        'category_banner_id',
        true
    );

    $image_url = $image_id
        ? wp_get_attachment_image_url($image_id, 'medium')
        : '';
    ?>
    <tr class="form-field term-banner-wrap">
        <th scope="row">
            <label for="category_banner_id">Banner Image</label>
        </th>

        <td>
            <input type="hidden"
                   id="category_banner_id"
                   name="category_banner_id"
                   value="<?php echo esc_attr($image_id); ?>">

            <div id="category_banner_preview">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>"
                         style="max-width:300px;height:auto;display:block;margin-bottom:10px;">
                <?php endif; ?>
            </div>

            <button type="button"
                    class="button category-banner-upload">
                Upload / Select Banner
            </button>

            <button type="button"
                    class="button category-banner-remove"
                    <?php echo $image_id ? '' : 'style="display:none;"'; ?>>
                Remove Banner
            </button>

            <p class="description">
                Select a banner image from the Media Library.
            </p>
        </td>
    </tr>
    <?php
});

// 3. Save the banner image ID.
add_action('created_category', 'save_category_banner_image');
add_action('edited_category', 'save_category_banner_image');

function save_category_banner_image($term_id) {
    if (!isset($_POST['category_banner_id'])) {
        return;
    }

    if (!current_user_can('manage_categories')) {
        return;
    }

    $image_id = absint(
        wp_unslash($_POST['category_banner_id'])
    );

    if ($image_id) {
        if (
            get_post_type($image_id) !== 'attachment' ||
            !wp_attachment_is_image($image_id)
        ) {
            return;
        }

        update_term_meta(
            $term_id,
            'category_banner_id',
            $image_id
        );
    } else {
        delete_term_meta($term_id, 'category_banner_id');
    }
}

// 4. Load Media Library and handle upload button.
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['edit-tags.php', 'term.php'], true)) {
        return;
    }

    $screen = get_current_screen();

    if (!$screen || $screen->taxonomy !== 'category') {
        return;
    }

    wp_enqueue_media();

    wp_add_inline_script('jquery', "
        jQuery(function($) {
            let bannerFrame;

            $(document).on('click', '.category-banner-upload', function(e) {
                e.preventDefault();

                const wrap = $(this).closest('.term-banner-wrap');
                const input = wrap.find('input[name=category_banner_id]');
                const preview = wrap.find('#category_banner_preview');
                const removeButton = wrap.find('.category-banner-remove');

                bannerFrame = wp.media({
                    title: 'Select Banner Image',
                    button: { text: 'Use this image' },
                    library: { type: 'image' },
                    multiple: false
                });

                bannerFrame.on('select', function() {
                    const image = bannerFrame
                        .state()
                        .get('selection')
                        .first()
                        .toJSON();

                    input.val(image.id);

                    const url = image.sizes && image.sizes.medium
                        ? image.sizes.medium.url
                        : image.url;

                    preview.empty().append(
                        $('<img>', {
                            src: url,
                            css: {
                                maxWidth: '300px',
                                height: 'auto',
                                display: 'block',
                                marginBottom: '10px'
                            }
                        })
                    );

                    removeButton.show();
                });

                bannerFrame.open();
            });

            $(document).on('click', '.category-banner-remove', function(e) {
                e.preventDefault();

                const wrap = $(this).closest('.term-banner-wrap');

                wrap.find('input[name=category_banner_id]').val('');
                wrap.find('#category_banner_preview').empty();

                $(this).hide();
            });
        });
    ");
});


add_action( 'init', 'faq_post_type', 0 );
function faq_post_type() {

	$labels = array(
		'name'                => _x( 'FAQs', 'Post Type General Name', 'gia' ),
		'singular_name'       => _x( 'FAQ', 'Post Type Singular Name', 'gia' ),
		'menu_name'           => __( 'FAQs', 'gia' ),
		'all_items'           => __( 'All FAQs', 'gia' ),
		'view_item'           => __( 'View FAQ', 'gia' ),
		'add_new_item'        => __( 'Add FAQ', 'gia' ),
		'add_new'             => __( 'Add FAQ', 'gia' ),
		'edit_item'           => __( 'Edit FAQ', 'gia' ),
		'update_item'         => __( 'Update FAQ', 'gia' ),
		'search_items'        => __( 'Search FAQ', 'gia' ),
		'not_found'           => __( 'Not Found', 'gia' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'gia' ),
	);

    // Set other options for Custom Post Type
	$args = array(
		'label'               => __( 'FAQs', 'gia' ),
		'description'         => __( 'FAQs', 'gia' ),
		'labels'              => $labels,
		'menu_icon'			  => 'dashicons-editor-help',
		// Features this CPT supports in Post Editor
        'supports'     => array(
            'title',
            'editor',
            'revisions',
            'elementor',
        ),
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
		'show_in_rest'        => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'faq', $args );
}

function create_faq_category_taxonomies() {
	// Add new taxonomy name affiliate-tags
	$labels = array(
		'name'              => _x( 'FAQ categories', 'taxonomy general name', 'gia' ),
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
		'show_in_rest'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'faqcat' ),
	);

	register_taxonomy( 'faqcat', array( 'faq' ), $args );
}
add_action( 'init', 'create_faq_category_taxonomies', 0 );
