<?php
/**
 * Plugin Name: Announcement Bar
 * Description: A plugin to enable an announcement bar on all pages
 * Version: 1.0
 * Author: Joshua Clark
 */

// Safety check: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

function ann_bar_add_banner()
{
    // Get saved options
    $options = get_option('announcement_bar_options', array());
    $enabled = isset($options['enabled']) ? $options['enabled'] : 0;
    $message = isset($options['message']) ? $options['message'] : 'This is an announcement bar!';
    $color = isset($options['color']) ? $options['color'] : '#663399';

    if ($enabled) {
        echo '<div style="background:' . esc_attr($color) . ';color:white;padding:10px;text-align:center;">'
            . esc_html($message) .
            '</div>';
    }
}
add_action('wp_body_open', 'ann_bar_add_banner');

// Register settings
add_action('admin_init', 'ann_bar_register_settings');
function ann_bar_register_settings()
{
    register_setting(
        'announcement_bar_options_group',
        'announcement_bar_options',
        array(
            'sanitize_callback' => function ($input) {
                $output = array();
                $output['enabled'] = isset($input['enabled']) && $input['enabled'] ? 1 : 0;
                $output['message'] = sanitize_text_field($input['message']);
                $output['color'] = sanitize_hex_color($input['color']);
                return $output;
            }
        )
    );
}

// Add a new settings page under "Settings"
function ann_bar_add_settings_page()
{
    add_options_page(
        'Announcement Bar Settings',
        'Announcement Bar',
        'manage_options',
        'announcement-bar',
        'ann_bar_render_settings_page'
    );
}
add_action('admin_menu', 'ann_bar_add_settings_page');

// Render the settings page
function ann_bar_render_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Announcement Bar Settings</h1>

        <form method="post" action="options.php">
            <?php
            settings_fields('announcement_bar_options_group');

            $options = get_option('announcement_bar_options', array());
            $enabled = isset($options['enabled']) ? $options['enabled'] : 0;
            $message = isset($options['message']) ? $options['message'] : '';
            $color = isset($options['color']) ? $options['color'] : '#663399';

            ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><?php _e('Announcement Bar', 'announcement-bar'); ?></th>
                    <td>
                        <label for="announcement_bar_enabled">
                            <input type="checkbox" id="announcement_bar_enabled" name="announcement_bar_options[enabled]"
                                value="1" <?php checked($enabled, 1); ?> />
                            <?php _e('On', 'announcement-bar'); ?>
                        </label>
                    </td>
                </tr>

                <tr class="announcement-bar-extra" <?php if (!$enabled)
                    echo 'style="display:none;"'; ?>>
                    <th scope="row"><?php _e('Announcement Text', 'announcement-bar'); ?></th>
                    <td>
                        <input type="text" name="announcement_bar_options[message]"
                            value="<?php echo esc_attr($message); ?>" class="regular-text" />
                        <p class="description">Enter the text to display in the announcement bar.</p>
                    </td>
                </tr>

                <tr class="announcement-bar-extra" <?php if (!$enabled)
                    echo 'style="display:none;"'; ?>>
                    <th scope="row"><?php _e('Background Color', 'announcement-bar'); ?></th>
                    <td>
                        <input type="color" name="announcement_bar_options[color]"
                            value="<?php echo esc_attr($color); ?>" />
                        <p class="description">Pick a background color for the announcement bar.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>

    <script>
        // Toggle behavior, showing settings based on checkmark
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('announcement_bar_enabled');
            const extras = document.querySelectorAll('.announcement-bar-extra');

            checkbox.addEventListener('change', function () {
                extras.forEach(row => {
                    row.style.display = checkbox.checked ? '' : 'none';
                });
            });
        });
    </script>
    <?php
}
