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

require_once plugin_dir_path(__FILE__) . 'announcement-bar-settings.php';

function ann_bar_is_light_color($hex)
{
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) === 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }

    $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
    return $brightness > 155;
}


function ann_bar_add_banner()
{
    // Get saved options
    $options = get_option('announcement_bar_options', array());
    $enabled = isset($options['enabled']) ? $options['enabled'] : 0;
    $message = isset($options['message']) ? $options['message'] : 'This is an announcement bar!';
    $message_key = md5($message);
    $color = isset($options['color']) ? $options['color'] : '#663399';

    if ($enabled) {
        $text_color = ann_bar_is_light_color($color) ? '#000000' : '#ffffff';
        ?>
        <style>
            #announcement-bar {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background:
                    <?php echo esc_attr($color); ?>
                ;
                color:
                    <?php echo esc_attr($text_color); ?>
                ;
                padding: 12px 24px;
                border-radius: 20px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                z-index: 9999;
                text-align: center;
                max-width: 90vw;
                min-width: 50px;
                font-size: 16px;
                line-height: 1.4;
                display: none;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }

            #announcement-close {
                background: none;
                border: none;
                color: inherit;
                font-size: 20px;
                cursor: pointer;
                margin-left: 12px;
                transition: opacity 0.2s ease;
            }

            #announcement-close:hover {
                opacity: 0.7;
            }

            /* Adjust when admin bar is visible */
            body.admin-bar #announcement-bar {
                top: 45px;
            }
        </style>
        <div id='announcement-bar'>
            <span class='announcement-text'><?php echo esc_html($message); ?></span>
            <button id='announcement-close' aria-label='Close announcement'>&times;</button>
        </div>


        <script>
            function setCookie(name, value) {
                document.cookie = `${name}=${value}; path=/; SameSite=Lax`;
            }

            function getCookie(name) {
                const cookies = document.cookie.split("; ").map(c => c.split("="));
                for (const [key, val] of cookies) {
                    if (key === name) return val;
                }
                return null;
            }

            document.addEventListener('DOMContentLoaded', function () {
                const closeBtn = document.getElementById('announcement-close');
                const bar = document.getElementById('announcement-bar');
                // Check cookie instead of local storage
                const messageKey = '<?php echo $message_key; ?>';
                const dismissed = getCookie('announcementDismissed_' + messageKey);


                if (!dismissed) {
                    bar.style.display = 'flex';
                }

                if (closeBtn && bar) {
                    closeBtn.addEventListener('click', () => {
                        bar.style.opacity = '0';
                        setTimeout(() => {
                            bar.style.display = 'none';

                            // Set session cookie
                            setCookie('announcementDismissed_' + messageKey, 'true');
                        }, 300);
                    });
                }
            });
        </script>


        <?php
    }
}
add_action('wp_head', 'ann_bar_add_banner');
?>
