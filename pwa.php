<?php
/**
 * Plugin Name: PWA - DEV SEC IT
 * Description: Creates PWA Installable application. Cross platform supported! For more info visit <a href="">DEV SEC IT </a> official website
 * Plugin URI: https://devsecit.com/plugin/pwa-devsecit
 * Author: Kanai Shil
 * Version: 1.0.1
 * Author URI: https://devsecit.com 
 * Text Domain: pwa-devsecit 
 */
 
 
// Inside your plugin activation function (pwa-devsecit.php)

// Inside your plugin activation function (pwa-devsecit.php)
// Inside your plugin activation function (pwa-devsecit.php)

function activate_pwa_plugin() {
    // Collect site data
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = home_url();

    // Detect favicon from WordPress
    $favicon_url = get_site_icon_url();
    $favicon_path = $favicon_url ? parse_url($favicon_url, PHP_URL_PATH) : 'assets/favicon.ico';

    // Get the custom logo URL
    $custom_logo_url = '';
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $custom_logo_url = wp_get_attachment_image_src($custom_logo_id, 'full')[0];
    }

    // Generate manifest content
    $manifest_content = array(
        'short_name' => 'DEV SEC IT',
        'name' => 'DEV SEC IT - Custom Software Solution',
        'description' => 'Best Software Development Company',
        'orientation' => 'portrait',
        'prefer_related_applications' => false,
        'icons' => array(
            array(
                'src' => $favicon_path,
                'sizes' => '64x64 32x32 24x24 16x16',
                'type' => 'image/x-icon',
                'purpose' => 'any maskable'
            ),
            array(
                'src' => $custom_logo_url ?: 'assets/logo192.png', // Use custom logo or default path
                'type' => 'image/png',
                'sizes' => '192x192',
                'purpose' => 'any maskable'
            ),
            array(
                'src' => $custom_logo_url ?: 'assets/logo512.png', // Use custom logo or default path
                'type' => 'image/png',
                'sizes' => '512x512',
                'purpose' => 'any maskable'
            ),
            array(
                'src' => $custom_logo_url ?: 'assets/logo1024.png', // Use custom logo or default path
                'type' => 'image/png',
                'sizes' => '1024x1024',
                'purpose' => 'any maskable'
            ),
        ),
        'start_url' => '/',
        'scope' => '/',
        'display' => 'standalone',
        'theme_color' => '#181818',
        'background_color' => '#181818',
    );

    // Convert to JSON
    $manifest_json = json_encode($manifest_content, JSON_PRETTY_PRINT);

    // Save to manifest file within the plugin folder
    $manifest_path = plugin_dir_path(__FILE__) . 'assets/manifest.json';
    file_put_contents($manifest_path, $manifest_json);
    
    add_action('wp_head', function () use ($manifest_path) {
        echo '<link rel="manifest" href="' . esc_url($manifest_path) . '">';
    });
    
}

// Hook into the activation event
register_activation_hook(__FILE__, 'activate_pwa_plugin');

$manifest_path = '/wp-content/plugins/pwa-devsecit/assets/manifest.json';

 add_action('wp_head', function () use ($manifest_path) {
        echo '<link rel="manifest" href="' . esc_url($manifest_path) . '">';
        echo '<meta name="theme-color" content="#181818" >';
    });
    
    

// Inside your theme's functions.php or your plugin file
function enqueue_pwa_script() {
    // Enqueue the service worker script
    wp_enqueue_script('pwa-service-worker', plugins_url('assets/sw.js', __FILE__), array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_pwa_script');





add_action('wp_footer', function () {
    $favicon_url = get_site_icon_url();
    
        echo '<script> 
                
                 
                if (window.matchMedia("(display-mode: standalone)").matches || window.navigator.standalone === true) {
                    console.warn("Already installed");
                } else {
                console.warn("Showing button");
                    document.getElementById("installButton").style.display="block";
                }
                document.getElementById("installButton").addEventListener("click", () => {
                    // Show the install prompt when the button is clicked
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        // Wait for the user to respond to the prompt
                        deferredPrompt.userChoice.then((choiceResult) => {
                            if (choiceResult.outcome === "accepted") {
                                // The user accepted the prompt
                                console.log("User accepted the install prompt");
                            } else {
                                // The user dismissed the prompt
                                console.log("User dismissed the install prompt");
                            }
                            // Reset the deferredPrompt
                            deferredPrompt = null;
                        });
                    }
                });
                
                function dsi_install() { 
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        // Wait for the user to respond to the prompt
                        deferredPrompt.userChoice.then((choiceResult) => {
                            if (choiceResult.outcome === "accepted") {
                                // The user accepted the prompt
                                console.log("User accepted the install prompt");
                            } else {
                                // The user dismissed the prompt
                                console.log("User dismissed the install prompt");
                            }
                            // Reset the deferredPrompt
                            deferredPrompt = null;
                        });
                    }  
                }
                
                 navigator.serviceWorker.controller || navigator.serviceWorker.register("/wp-content/plugins/pwa-devsecit/assets/sw.js").then((function(r) {}));
            </script>';

        // Display the install button
        echo '<div id="installButton" style="display: none; position: fixed; bottom: 10px; right: 20px; padding: 10px; background-color: #4CAF50; color: #fff; cursor: pointer;z-index:99999; border-radius:10px" onclick="dsi_install()">
               <img src="'.$favicon_url.'" style="height:40px; width:40px; border-radius:10px; background:white" /> &nbsp;  Install App
            </div>';
    });
    
    
    
 ?>
 
 
 