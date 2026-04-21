<?php
/**
 * Plugin Name: Image Accordion
 * Description: Elementor Image Accordion widget with horizontal/vertical layouts, hover/click triggers, default active item, per-item focus and overlay settings. Includes plugin-local error log.
 * Version:     1.1.0
 * Author:      PeterGPT
 * Author URI:  https://www.menslaveAI.com
 * License:     Beta2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define paths
 */
define( 'IA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'IA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'IA_LOG_FILE', IA_PLUGIN_DIR . 'image-accordion.log' );

/**
 * Simple logger – writes to /wp-content/plugins/image-accordion/image-accordion.log
 */
if ( ! function_exists( 'ia_log' ) ) {
    function ia_log( $msg ) {
        $line = '[' . date( 'Y-m-d H:i:s' ) . '] ' . $msg . PHP_EOL;
        // Fail silently if not writable
        @error_log( $line, 3, IA_LOG_FILE );
    }
}

/**
 * Check Elementor dependency
 */
function ia_check_dependencies() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            ?>
            <div class="notice notice-warning">
                <p><strong>Image Accordion (Jeremy with ChatGPT)</strong> requires Elementor to be active.</p>
            </div>
            <?php
        } );
        ia_log( 'Elementor not loaded. Widget will not be registered.' );
        return false;
    }

    return true;
}

/**
 * Register widget with Elementor (compatible with old & new hooks)
 */
function ia_register_widget_common( $widgets_manager = null ) {
    static $done = false;

    if ( $done ) {
        return;
    }

    if ( ! ia_check_dependencies() ) {
        return;
    }

    // Load widget class
    if ( ! class_exists( 'Image_Accordion_Widget' ) ) {
        require_once IA_PLUGIN_DIR . 'includes/widget-image-accordion.php';
    }

    if ( ! $widgets_manager && class_exists( '\Elementor\Plugin' ) ) {
        $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
    }

    if ( $widgets_manager ) {
        $widgets_manager->register( new \Image_Accordion_Widget() );
        ia_log( 'Image_Accordion_Widget registered.' );
        $done = true;
    } else {
        ia_log( 'Failed to obtain widgets manager.' );
    }
}
add_action( 'elementor/widgets/register', 'ia_register_widget_common' );
add_action( 'elementor/widgets/widgets_registered', 'ia_register_widget_common' );

/**
 * Enqueue frontend assets
 * Note: only depends on jQuery, no hard dependency on elementor-frontend.
 */
function ia_enqueue_assets() {
    // Styles
    wp_register_style(
        'image-accordion-style',
        IA_PLUGIN_URL . 'assets/style.css',
        [],
        '1.1.12'
    );

    // Script – only depends on jQuery, safe on any front-end
    wp_register_script(
        'image-accordion-script',
        IA_PLUGIN_URL . 'assets/script.js',
        [ 'jquery' ],
        '1.1.1',
        true
    );

    wp_enqueue_style( 'image-accordion-style' );
    wp_enqueue_script( 'image-accordion-script' );

    ia_log( 'Assets enqueued.' );
}
add_action( 'wp_enqueue_scripts', 'ia_enqueue_assets' );
