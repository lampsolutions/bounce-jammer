<?php

class BOJA_Admin {
    public static function init() {
        add_action( 'admin_menu' , array('BOJA_Admin', 'wp_action_admin_menu'), 0);
        add_action( 'network_admin_menu' , array('BOJA_Admin', 'wp_action_network_admin_menu'), 0);
        add_action( 'admin_enqueue_scripts', array('BOJA_Admin', 'wp_action_enqueue_scripts'), 1999 );

        BOJA_MetaBoxes::init();
    }

    public static function wp_action_admin_menu() {
        if(!function_exists('add_menu_page')) { require_once BOJA_WPDIR.'/wp-admin/includes/plugin.php'; }

        if(BOJA::$mu_settings['enable_global'] && isset(BOJA::$mu_settings['sites'][get_current_blog_id()])) {
            add_menu_page(
                __('Bounce Jammer', BOJA_TEXT_DOMAIN),
                __('Bounce Jammer', BOJA_TEXT_DOMAIN),
                'manage_options',
                BOJA_PREFIX.'menu',
                array('BOJA_Admin', 'page_settings'),
                BOJA_URL.'/static/img/icon.png',
                '81.2335'
            );

            add_submenu_page(
                BOJA_PREFIX.'menu',
                __('General', BOJA_TEXT_DOMAIN),
                __('General', BOJA_TEXT_DOMAIN),
                'manage_options',
                BOJA_PREFIX.'menu',
                array('BOJA_Admin', 'page_settings')
            );
        } elseif(!BOJA::$mu_settings['enable_global']) {
            add_menu_page(
                __('Bounce Jammer', BOJA_TEXT_DOMAIN),
                __('Bounce Jammer', BOJA_TEXT_DOMAIN),
                'manage_options',
                BOJA_PREFIX.'menu',
                array('BOJA_Admin', 'page_settings'),
                BOJA_URL.'/static/img/icon.png',
                '81.2335'
            );

            add_submenu_page(
                BOJA_PREFIX.'menu',
                __('General', BOJA_TEXT_DOMAIN),
                __('General', BOJA_TEXT_DOMAIN),
                'manage_options',
                BOJA_PREFIX.'menu',
                array('BOJA_Admin', 'page_settings')
            );
        }

    }

    public static function wp_action_network_admin_menu() {
        if(!function_exists('add_menu_page')) { require_once BOJA_WPDIR.'/wp-admin/includes/plugin.php'; }

        add_menu_page(
            __('Bounce Jammer', BOJA_TEXT_DOMAIN),
            __('Bounce Jammer', BOJA_TEXT_DOMAIN),
            'manage_options',
            BOJA_PREFIX.'menu',
            array('BOJA_Admin', 'page_settings'),
            BOJA_URL.'/static/img/icon.png',
            '81.2335'
        );

        add_submenu_page(
            BOJA_PREFIX.'menu',
            __('General', BOJA_TEXT_DOMAIN),
            __('General', BOJA_TEXT_DOMAIN),
            'manage_options',
            BOJA_PREFIX.'menu',
            array('BOJA_Admin', 'page_settings')
        );

    }

    public static function page_settings() {

        if(isset($_POST['boja_set_settings']) && $_POST['boja_set_settings']== "1" && !is_network_admin() ) {
            BOJA::$settings['enable_global'] = isset($_POST['enable_global']) ? true : false;
            BOJA::$settings['target'] = isset($_POST['target']) ? esc_url($_POST['target'], array('https', 'http')) : '';
            BOJA::$settings['timeout'] = isset($_POST['timeout']) ? (int) $_POST['timeout'] : 30;
            BOJA::$settings['percent'] = isset($_POST['percent']) ? (int) $_POST['percent'] : 100;
            BOJA::$settings['redirect_entry_page_only'] = isset($_POST['redirect_entry_page_only']) ? 1 : 0;

            // Validation
            $post_types = isset($_POST['enable_post_types']) ? $_POST['enable_post_types'] : array();
            $available_post_types = get_post_types( array( 'public' => false, 'name' => 'attachment' ), 'names', 'NOT' );
            foreach($post_types as $post_type => $enabled) {
                if(!in_array($post_type, $available_post_types)) unset($post_types[$post_type]);
            }
            BOJA::$settings['enable_post_types'] = $post_types;

            $errors = array();

            if(BOJA::$settings['percent'] > 100 || BOJA::$settings['percent'] < 0) {
                $errors[] = __('The entered Redirect Percent value is invalid. (Valid values 1-100).', BOJA_TEXT_DOMAIN);
                BOJA::$settings['percent'] = 100;
            }

            if(!filter_var(BOJA::$settings['target'], FILTER_VALIDATE_URL) && trim(BOJA::$settings['target']) != '') {
                $errors[] = __('The entered Target URL is invalid.', BOJA_TEXT_DOMAIN);
                BOJA::$settings['target'] = '';
            }
            
            update_option(BOJA_PREFIX.'settings', BOJA::$settings);
        } elseif(isset($_POST['boja_set_settings']) && $_POST['boja_set_settings']== "1" && is_network_admin() ) {
            BOJA::$mu_settings['enable_global'] = isset($_POST['enable_global']) ? true : false;
            BOJA::$mu_settings['target'] = isset($_POST['target']) ? esc_url($_POST['target'], array('https', 'http')) : '';
            BOJA::$mu_settings['timeout'] = isset($_POST['timeout']) ? (int) $_POST['timeout'] : 30;
            BOJA::$mu_settings['percent'] = isset($_POST['percent']) ? (int) $_POST['percent'] : 100;
            BOJA::$mu_settings['redirect_entry_page_only'] = isset($_POST['redirect_entry_page_only']) ? 1 : 0;

            $errors = array();

            // Validation
            if(BOJA::$mu_settings['percent'] > 100 || BOJA::$mu_settings['percent'] < 0) {
                $errors[] = __('The entered Redirect Percent value is invalid. (Valid values 1-100).', BOJA_TEXT_DOMAIN);
                BOJA::$mu_settings['percent'] = 100;
            }

            if(!filter_var(BOJA::$mu_settings['target'], FILTER_VALIDATE_URL) && trim(BOJA::$mu_settings['target']) != '') {
                $errors[] = __('The entered Target URL is invalid.', BOJA_TEXT_DOMAIN);
                BOJA::$mu_settings['target'] = '';
            }

            update_site_option(BOJA_PREFIX.'mu_settings', BOJA::$mu_settings);
        }

        if(isset($_POST['boja_set_sites']) && $_POST['boja_set_sites'] == "1" && is_network_admin()) {
            BOJA::$mu_settings['sites'] = array();
            foreach((array) $_POST['sites'] as $site_id => $enabled) {
                BOJA::$mu_settings['sites'][(int)$site_id] = 1;
            }

            update_site_option(BOJA_PREFIX.'mu_settings', BOJA::$mu_settings);
        }

        if(isset($_POST['boja_set_mase_settings']) && $_POST['boja_set_mase_settings'] == "1" ) {
            BOJA::$settings['enable_mase'] = isset($_POST['enable_mase']) && $_POST['enable_mase'] == "1" ? true : false;
            BOJA::$settings['mase_use_boja_fb_url'] = isset($_POST['mase_use_boja_fb_url']) && $_POST['mase_use_boja_fb_url'] == "1" ? true : false;
            update_option(BOJA_PREFIX.'settings', BOJA::$settings);
        }

        require_once BOJA_DIR.'/lib/Pages/Settings.php';
    }

    public static function wp_action_enqueue_scripts() {
        wp_enqueue_script(
            BOJA_PREFIX.'bootstrap_js',
            BOJA_URL.'static/js/bootstrap.min.js',
            array( 'jquery' )
        );

        wp_enqueue_script(
            BOJA_PREFIX.'boja_admin_js',
            BOJA_URL.'static/js/boja_admin.js',
            array( 'jquery' )
        );

        wp_register_style( BOJA_PREFIX.'bs', BOJA_URL.'static/css/boja-bs.css', false, '1.0.1' );
        wp_enqueue_style( BOJA_PREFIX.'bs' );

        wp_register_style( BOJA_PREFIX.'boja', BOJA_URL.'static/css/boja.css', false, '1.0.1' );
        wp_enqueue_style( BOJA_PREFIX.'boja' );
    }
}