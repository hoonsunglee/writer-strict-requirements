<?php
/**
 * Creates the Option Page for strictreq in Tools Page
 */
function strictreq_options_page_html() {

    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php

            settings_fields('strictreq_options');
            do_settings_sections('strictreq');
            submit_button(__('Save Settings', 'writer-strict-requirements'));
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'strictreq_options_page');

function strictreq_options_page() {
    add_submenu_page(
        'tools.php',
        'Writer Strict Requirements Options',
        'Strict Req Options',
        'manage_options',
        'strictreq',
        'strictreq_options_page_html'
    );
}

add_action('admin_init', 'strictreq_settings_init');

function strictreq_settings_init() {
    register_setting('strictreq_options', 'strictreq_require_featured_image');
    register_setting('strictreq_options', 'strictreq_require_category');
    register_setting('strictreq_options', 'strictreq_require_title');

    add_settings_section(
        'strictreq_settings_section',
        __('Publishing Requirements', 'writer-strict-requirements'),
        'strictreq_settings_section_callback',
        'strictreq'
    );

    add_settings_field(
        'strictreq_require_featured_image',
        __('Require Featured Image', 'writer-strict-requirements'),
        'strictreq_require_featured_image_render',
        'strictreq',
        'strictreq_settings_section'
    );

    add_settings_field(
        'strictreq_require_category',
        __('Require Category', 'writer-strict-requirements'),
        'strictreq_require_category_render',
        'strictreq',
        'strictreq_settings_section'
    );

    add_settings_field(
        'strictreq_require_title',
        __('Require Title', 'writer-strict-requirements'),
        'strictreq_require_title_render',
        'strictreq',
        'strictreq_settings_section'
    );
}

function strictreq_settings_section_callback() {
    echo esc_html('Configure the requirements for publishing posts.', 'writer-strict-requirements');
}

function strictreq_require_featured_image_render() {
    $option = get_option('strictreq_require_featured_image');
    ?>
    <input type='checkbox' name='strictreq_require_featured_image' value='<?php echo esc_attr(1); ?>' <?php checked($option, 1); ?>>
    <?php
}


function strictreq_require_category_render() {
    $option = get_option('strictreq_require_category');
    ?>
    <input type='checkbox' name='strictreq_require_category' value='<?php echo esc_attr(1); ?>' <?php checked($option, 1); ?>>
    <?php
}

function strictreq_require_title_render() {
    $option = get_option('strictreq_require_title');
    ?>
    <input type='checkbox' name='strictreq_require_title' value='<?php echo esc_attr(1); ?>' <?php checked($option, 1); ?>>
    <?php
}
?>
