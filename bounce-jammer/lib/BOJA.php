<?php

class BOJA {

    public static $settings = array();
    public static $mu_settings = array();
    public static $mase_active = false;

    public static function init() {
        BOJA::load_settings();
        add_action('plugins_loaded', array('BOJA', 'wp_action_plugins_loaded'));
        add_action('wp_head', array('BOJA', 'wp_action_wp_head'), 100);

        if(is_admin()) BOJA_Admin::init();
    }

    public static function wp_action_plugins_loaded() {
        load_plugin_textdomain(BOJA_TEXT_DOMAIN, false, BOJA_TEXTDOMAIN_PATH);
        if(BOJA::moreAdsSEInstalled()) {
            require_once BOJA_DIR.'/lib/BOJA_MASE_Zone.php';
            BOJA_MASE_Zone::init();
        }
    }

    public static function load_settings() {

        self::$settings = get_option(
            BOJA_PREFIX.'settings',
            array(
                'enable_global'  => false,
                'target' => '',
                'enable_mase' => false,
                'redirect_entry_page_only' => true,
                'mase_use_boja_fb_url' => false,
                'enable_post_types' => array('post' => 1, 'page' => 1),
                'timeout' => 30,
                'percent' => 80
            )
        );
        self::$mu_settings = get_site_option(
            BOJA_PREFIX.'mu_settings',
            array(
                'enable_global'  => false,
                'target' => '',
                'enable_mase' => false,
                'redirect_entry_page_only' => true,
                'mase_use_boja_fb_url' => false,
                'timeout' => 30,
                'percent' => 80,
                'sites' => array()
            )
        );

        // Migrate Default Value
        if(!isset(self::$settings['redirect_entry_page_only'])) self::$settings['redirect_entry_page_only'] = true;
        if(!isset(self::$mu_settings['redirect_entry_page_only'])) self::$mu_settings['redirect_entry_page_only'] = true;
    }

    public static function wp_action_wp_head() {
        $settings = BOJA::$settings;
        if(BOJA::$mu_settings['enable_global'] && !isset(BOJA::$mu_settings['sites'][get_current_blog_id()])) {
            if(!get_site_option(BOJA_PREFIX.'l'.'i'.'c')) return;
            $settings = BOJA::$mu_settings;
        } else {
            if(!get_option(BOJA_PREFIX.'l'.'i'.'c')) return;

            if($settings['enable_mase'] && BOJA::moreAdsSEInstalled()) {
                $settings['target'] = get_admin_url(null, 'admin-ajax.php')."?action=boja_go&u=".sha1(microtime(true));
            }

            $post_id = get_the_ID();
            if(!empty($post_id)) { // Look for override settings
                $override_settings = get_option(BOJA_PREFIX.'_post_settings_'.$post_id, array(
                    'boja_mode' => 0,
                    'target' => '',
                    'timeout' => 30,
                    'percent' => 80
                ));

                switch(intval($override_settings['boja_mode'])) {
                    case 0:
                        break; // Ignore
                    case 1:
                        $settings['enable_global'] = true;
                        if($override_settings['enable_mase'] && BOJA::moreAdsSEInstalled()) {
                            $settings['target'] = get_admin_url(null, 'admin-ajax.php')."?action=boja_go&u=".sha1(microtime(true)).'&id='.$post_id;
                        }
                        $settings['percent'] = !empty($override_settings['percent']) ? intval($override_settings['percent']) : $settings['percent'];
                        $settings['timeout'] = !empty($override_settings['timeout']) ? intval($override_settings['timeout']) : $settings['timeout'];
                        $settings['target'] = !empty($override_settings['target']) ? $override_settings['target'] : $settings['target'];
                        break;
                    case 2:
                        return; // Disable
                        break;
                }
            }


        }

        if(!empty($settings) && isset($settings['enable_global']) && $settings['enable_global']) {
            echo '<script type="text/javascript">';
            echo file_get_contents(BOJA_DIR.'/static/js/boja.js');
            echo 'BOJA.init("'.
                base64_encode(json_encode(array(
                    'timeout' => $settings['timeout'],
                    'percent' => $settings['percent'],
                    'target' => $settings['target'],
                    'repo' => intval($settings['redirect_entry_page_only']),
                ))).
                '");';
            echo '</script>';
        }
    }


    public static function moreAdsSEInstalled() {
        if(class_exists('MASE')) return true;
    }


}