<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter('wp_insert_post_empty_content', 'strictreq_check_post_requirements', 10, 2);
/**
 * Function to check if the post meets the requirements before saving.
 *
 * @param bool $maybe_empty Whether the post is considered empty.
 * @param array $postarr The post data array.
 * @return bool Whether the post should be considered empty.
 */
function strictreq_check_post_requirements($maybe_empty, $postarr) {
    // Only check on publish or future post status
    if ($postarr['post_status'] != 'publish' && $postarr['post_status'] != 'future') {
        return;
    }
    // Ensure post ID is set and valid before proceeding

    /**
     * For future reference, apparently wp_insert_post_empty_content might fire before the post ID is initialized, so we
     *  must check for that beforehand
     */
    if (!isset($postarr['ID']) || empty($postarr['ID'])) {
        return $maybe_empty; // Return original value if ID is not set
    }

    // Check if the restrictions are enabled
    $require_featured_image = get_option('strictreq_require_featured_image');
    $require_category = get_option('strictreq_require_category');
    $require_title = get_option('strictreq_require_title');

    $errors = [];

    if ($require_featured_image && !has_post_thumbnail($postarr['ID'])) {
        $errors[] = __('No Featured Image', 'writer-strict-requirements');
    }

    if ($require_category && (count($postarr['post_category']) == 1) && in_array(0, $postarr['post_category'])) {
        $errors[] = __('No Category', 'writer-strict-requirements');
    }

    if ($require_title && empty($postarr['post_title'])) {
        $errors[] = __('No Title', 'writer-strict-requirements');
    }

    if (!empty($errors)) {
        update_post_meta($postarr['ID'], '_strictreq_errors', $errors);

        // Change the post status to draft to prevent it from being published
        remove_action('save_post', 'strictreq_check_post_requirements');
        wp_update_post(array(
            'ID' => $postarr['ID'],
            'post_status' => 'draft'
        ));
        add_action('save_post', 'strictreq_check_post_requirements');

        return true; // Consider the post "empty"
    } else {
        delete_post_meta($postarr['ID'], '_strictreq_errors');

        return $maybe_empty;
    }
}

add_action('admin_notices', 'strictreq_display_errors');

function strictreq_display_errors() {
    global $post;

    // Ensure we're on the post editing screen and have a valid post object
    if (!is_admin() || !isset($post->ID)) {
        return;
    }

    // Retrieve errors from post meta
    $errors = get_post_meta($post->ID, '_strictreq_errors', true);

    if (!empty($errors)) {
        echo '<div class="notice notice-error"><p><strong>' . esc_textarea('Post could not be published due to the following errors:', 'writer-strict-requirements') . '</strong></p><ul>';
        foreach ($errors as $error) {
            echo '<li>' . esc_html($error) . '</li>';
        }
        echo '</ul></div>';

        // Remove the errors after displaying them to avoid showing them repeatedly
        delete_post_meta($post->ID, '_strictreq_errors');
    }
}

?>
