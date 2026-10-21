
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