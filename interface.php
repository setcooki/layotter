<?php

 
/**
 * Load translation files
 */
add_action('plugins_loaded', 'eddditor_load_i18n');
function eddditor_load_i18n()
{
    load_plugin_textdomain('eddditor', false, basename(__DIR__) . '/languages/');
}


/**
 * Replace TinyMCE with Eddditor on Eddditor-enabled screens
 */
add_action('admin_head', 'eddditor_admin_head');
function eddditor_admin_head()
{
    if (!Eddditor::is_enabled()) {
        return;
    }

    // remove TinyMCE
    remove_post_type_support(get_post_type(), 'editor');

    // insert eddditor
    add_meta_box(
        'eddditor_wrapper', // ID
        'Eddditor', // title
        'eddditor', // callback
        get_post_type(), // post type for which to enable
        'normal', // position
        'high' // priority
    );
}


/**
 * Output backend HTML for Eddditor
 *
 * @param $post object Post object as provided by Wordpress
 */
function eddditor($post)
{
    // show a regular textarea with JSON data if debug mode is enabled for the current user role
    // otherwise create a hidden textarea to be synced with Eddditor via Javascript

    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;
    $options = get_option('eddditor_settings_general');
    $debug_mode_enabled = false;

    foreach ($user_roles as $role) {
        if ($options['debug_mode'][$role] == '1') {
            $debug_mode_enabled = true;
            break;
        }
    }

    if ($debug_mode_enabled) {
        echo '<p>';
        printf(__('Debug mode enabled: Inspect and manually edit the JSON structure generated by Eddditor. Use with caution. A faulty structure will break your page layout and content. Go to <a href="%s">Eddditor\'s settings page</a> to disable debug mode.', 'eddditor'), admin_url('options-general.php?page=eddditor-settings'));
        echo '</p>';
        echo '<textarea id="eddditor-content" name="eddditor-content" style="width: 100%; height: 320px;display: block;">' . get_post_meta($post->ID, 'eddditor_content', true) . '</textarea>';

    } else {
        echo '<textarea id="eddditor-content" name="eddditor-content" style="width: 1px; height: 1px; position: fixed; top: -999px; left: -999px;">' . get_post_meta($post->ID, 'eddditor_content', true) . '</textarea>';
    }
    
    // create a regular text area with name="content" as used by Wordpress to store post contents
    // will be filled via Javascript
    echo '<textarea id="content" name="content" style="width: 1px; height: 1px; position: fixed; top: -999px; left: -999px;">' . $post->post_content . '</textarea>';
    wp_nonce_field('eddditor_content', 'eddditor_nonce');
    
    require_once dirname(__FILE__) . '/templates/editor.php';
}


/**
 * Save JSON data generated by Eddditor when saving a post
 */
add_action('save_post', 'eddditor_save_post');
function eddditor_save_post($post_id)
{
    if (isset($_POST['eddditor-content']) and wp_verify_nonce($_POST['eddditor_nonce'], 'eddditor_content')) {
        update_post_meta($post_id, 'eddditor_content', $_POST['eddditor-content']);
    }
}


/**
 * Include saved_elements sidebar template in admin footer
 */
add_action('admin_footer-post.php', 'eddditor_admin_footer_assets');
add_action('admin_footer-post-new.php', 'eddditor_admin_footer_assets');
function eddditor_admin_footer_assets()
{
    if (Eddditor::is_enabled()) {
        require dirname(__FILE__) . '/templates/templates.php';
    }
}