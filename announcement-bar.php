  <?php
  /**
   * Plugin Name: Announcement Bar
   * Description: A plugin to enable an announcement bar on all pages
   * Version: 1.0
   * Author: Joshua Clark
   */

  // Safety check: Prevent direct access
  if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }

  function ann_bar_add_banner() {
    echo '<div style="background:#663399;color:white;padding:10px;text-align:center;">
        This is an announcement bar!
    </div>';
  }
  add_action( 'wp_body_open', 'ann_bar_add_banner' );

  // Add a new settings page under "Settings"
  function ann_bar_add_settings_page() {
    add_options_page(
        'Announcement Bar Settings',   // Page title (shown on the settings page)
        'Announcement Bar',            // Menu title (shown in sidebar)
        'manage_options',              // Capability (who can access)
        'announcement-bar',            // Menu slug (unique identifier)
        'ann_bar_render_settings_page'     // Callback function to render the page
    );
  }
  add_action( 'admin_menu', 'ann_bar_add_settings_page' );

  // Render the settings page
  function ann_bar_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Announcement Bar Settings</h1>

        <form method="post" action="options.php">
            <?php

            // Default values -- TODO: Get saved options
            $enabled = 0;
            $message = '';
            $color   = '#663399';
            ?>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><?php _e( 'Announcement Bar', 'announcement-bar' ); ?></th>
                    <td>
                        <label for="announcement_bar_enabled">
                            <input type="checkbox" id="announcement_bar_enabled" name="announcement_bar_options[enabled]" value="1" <?php checked( $enabled, 1 ); ?> />
                            <?php _e( 'On', 'announcement-bar' ); ?>
                        </label>
                    </td>
                </tr>

                <tr class="announcement-bar-extra" <?php if ( ! $enabled ) echo 'style="display:none;"'; ?>>
                    <th scope="row"><?php _e( 'Announcement Text', 'announcement-bar' ); ?></th>
                    <td>
                        <input type="text" name="announcement_bar_options[message]" value="<?php echo esc_attr( $message ); ?>" class="regular-text" />
                        <p class="description">Enter the text to display in the announcement bar.</p>
                    </td>
                </tr>

                <tr class="announcement-bar-extra" <?php if ( ! $enabled ) echo 'style="display:none;"'; ?>>
                    <th scope="row"><?php _e( 'Background Color', 'announcement-bar' ); ?></th>
                    <td>
                        <input type="color" name="announcement_bar_options[color]" value="<?php echo esc_attr( $color ); ?>" />
                        <p class="description">Pick a background color for the announcement bar.</p>
                    </td>
                </tr>
            </table>

        </form>
    </div>

    <script>
    // Toggle behavior, showing settings based on checkmark
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('announcement_bar_enabled');
        const extras = document.querySelectorAll('.announcement-bar-extra');

        checkbox.addEventListener('change', function() {
            extras.forEach(row => {
                row.style.display = checkbox.checked ? '' : 'none';
            });
        });
    });
    </script>
    <?php
  }
