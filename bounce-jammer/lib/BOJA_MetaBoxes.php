<?php

class BOJA_MetaBoxes {
    public static function init() {
        add_action('add_meta_boxes', array('BOJA_MetaBoxes', 'add_meta_boxes') );
        add_action('save_post', array('BOJA_MetaBoxes', 'save_post'), 1, 2);
        add_action('before_delete_post', array('BOJA_MetaBoxes', 'delete_post'), 1, 2);
    }



    public static function add_meta_boxes() {
        foreach(BOJA::$settings['enable_post_types'] as $post_type => $true) {
            add_meta_box( BOJA_PREFIX.'override_box', __('Bounce Jammer', BOJA_TEXT_DOMAIN), array('BOJA_MetaBoxes', 'metabox'), $post_type, 'advanced', 'default', null );
        }
    }

    public static function metabox() {
        $settings = get_option(BOJA_PREFIX.'_post_settings_'.get_the_ID(), array(
            'boja_mode' => 0,
            'target' => '',
            'timeout' => 30,
            'percent' => 80,
            'enable_mase' => false,
            'mase_use_boja_fb_url' => false
        ));
        require BOJA_DIR.'/lib/Pages/MetaBox.php';
    }


    public static function save_post($post_id, $post) {
        if (!current_user_can('edit_post', $post_id)) return $post_id;

        $options = get_option(BOJA_PREFIX.'_post_settings_'.$post_id, array(
            'boja_mode' => 0,
            'target' => '',
            'timeout' => 30,
            'percent' => 80,
            'enable_mase' => false,
            'mase_use_boja_fb_url' => false
        ));


        if(isset($_POST['boja_mode'])) $options['boja_mode'] = intval($_POST['boja_mode']);
        if(isset($_POST['boja_target'])) $options['target'] = esc_url($_POST['boja_target'], array('http', 'https'));
        if(isset($_POST['boja_timeout'])) $options['timeout'] = intval($_POST['boja_timeout']);
        if(isset($_POST['boja_percent'])) $options['percent'] = intval($_POST['boja_percent']);

        $options['enable_mase'] = isset($_POST['enable_mase']) ? true : false;
        $options['mase_use_boja_fb_url'] = isset($_POST['mase_use_boja_fb_url']) ? true : false;

        if(isset($_POST['boja_mode'])) {
            update_option(BOJA_PREFIX.'_post_settings_'.$post_id, $options);
        }

        return $post_id;
    }

    public static function wp_action_delete_post($post_id) {
        delete_option(BOJA_PREFIX.'_post_settings_'.$post_id);
        return $post_id;
    }
}