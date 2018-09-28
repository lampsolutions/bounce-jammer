<?php
defined( 'ABSPATH' ) or die();
class BOJA_MASE_Zone {
    public static function init() {
        if(is_admin()) add_action('wp_ajax_boja_zone', array('BOJA_MASE_Zone', 'wp_ajax_boja_zone'));
        if(is_admin()) add_action('wp_ajax_boja_zone_save', array('BOJA_MASE_Zone', 'wp_ajax_boja_zone_save'));

        add_action('wp_ajax_nopriv_boja_go', array('BOJA_MASE_Zone', 'wp_ajax_boja_go'));
        add_action('wp_ajax_boja_go', array('BOJA_MASE_Zone', 'wp_ajax_boja_go'));
    }

    public static function get_zone_ad_count_html($zone_identifier) {
        $zone_ads = get_option($zone_identifier);
        $zone_ads_count = empty($zone_ads) ? 0 : count(MASE_Ads_Generic::GetAds(array('ids' => (array) array_keys($zone_ads))));

        if($zone_ads_count == 1) {
            return '<span style="margin-left: 15px; line-height: 28px; background-color: #0085ba;" class="label label-warning">'.sprintf(__("%d Ad in Zone active", MASE_TEXT_DOMAIN), $zone_ads_count).'</span>';
        } elseif($zone_ads_count > 1) {
            return '<span style="margin-left: 15px; line-height: 28px; background-color: #0085ba;" class="label label-warning">'.sprintf(__("%d Ads in Zone active", MASE_TEXT_DOMAIN), $zone_ads_count).'</span>';
        }
    }

    public static function wp_ajax_boja_zone() {
        $page_id = isset($_GET['id']) && !empty($_GET['id']) ? (int) $_GET['id'] : false;
        $zone_identifier = $page_id ? BOJA_PREFIX.'zone_ads_'.$page_id : BOJA_PREFIX.'zone_ads';
        $zone_ads = get_option($zone_identifier);

        $ads = MASE_Ads_Generic::GetAds(array('post_types' => array('mase_popup_ads')));

        foreach($ads as $id => $ad) {
            $ads[$id]['activated'] = isset($zone_ads[$ad['id']]) ? true : false;
        }
        usort($ads, function($a, $b) {
            return $b['activated'] - $a['activated'];
        });
        ?>
        <form class="mase_zone_configurator_form">
            <input type="hidden" name="save_action" value="boja_zone_save" />
            <?php if($page_id) { ?>
                <input type="hidden" name="id" value="<?php echo $page_id; ?>" />
            <?php } ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php _e('Zone Configurator', MASE_TEXT_DOMAIN); ?></h4>
            </div>
            <div class="modal-body">
                <table id="mase_zone_configurator_tbl" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th width="1%" title="<?php _e('Enable Ad in Zone', MASE_TEXT_DOMAIN); ?>"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></th>
                        <th width="1%" style="text-align: center;"><?php _e('ID', MASE_TEXT_DOMAIN); ?></th>
                        <th><?php _e('Name', MASE_TEXT_DOMAIN); ?></th>
                        <th class="mase-no-sort" width="1%"></th>
                        <th><?php _e('Tags', MASE_TEXT_DOMAIN); ?></th>
                        <th><?php _e('Country', MASE_TEXT_DOMAIN); ?></th>
                        <th><?php _e('Device', MASE_TEXT_DOMAIN); ?></th>
                        <th><?php _e('URL', MASE_TEXT_DOMAIN); ?></th>
                        <?php if(MASE::$ZONE_HOURS_OF_DAY) { ?>
                            <th><?php _e('Hours of Day', MASE_TEXT_DOMAIN); ?></th>
                        <?php } ?>

                        <?php if(MASE::$ZONE_DAYS_OF_WEEK) { ?>
                            <th><?php _e('Days of Week', MASE_TEXT_DOMAIN); ?></th>
                        <?php } ?>

                        <?php if(MASE::$ZONE_WEIGHT) { ?>
                            <th><?php _e('Weight', MASE_TEXT_DOMAIN); ?></th>
                        <?php } ?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach($ads as $ad) {
                        $tags = wp_get_post_terms($ad['id'], MASE_PREFIX.'ad_tags', array('fields' => 'names'));
                        ?>
                        <tr>
                            <td>
                                <input class="mase_ad_select_chkbox" type="checkbox" name="ad[<?php echo $ad['id']; ?>][active]" value="1" <?php checked(isset($zone_ads[$ad['id']])) ?> />
                            </td>
                            <td>
                                <?php echo substr(sha1($ad['pro_id']), 0, 7); ?>
                            </td>
                            <td>
                                <?php echo $ad['name']; ?>
                            </td>
                            <td>
                                <?php
                                if(isset($ad['media_url']) && !empty($ad['media_url'])) {
                                    echo '<a href="#" class="mase_html_tooltip" data-html="'.base64_encode('<img src="'.$ad['media_url'].'"></img>').'"><span class="glyphicon glyphicon-picture"></span></a>';
                                } elseif($ad['media_type'] == 'html') {
                                    $url = get_admin_url(null, 'admin-ajax.php')."?action=mase_ad_preview&id=".$ad['id'];
                                    echo  '<a href="#" class="mase_html_tooltip" data-html="'.base64_encode('<iframe width="'.$ad['media_width'].'px" height="'.$ad['media_height'].'px" scrolling="no" frameborder="0" src="'.$url.'"></iframe>').'"><span class="glyphicon glyphicon-sound-dolby"></span></a>';
                                } elseif($ad['media_type'] == 'popup') {
                                    echo '<a href="'.$ad['target_url'].'" target="_blank"><span class="glyphicon glyphicon-picture"></span></a>';
                                } else {
                                    echo '<span class="glyphicon glyphicon-picture disabled" style="color: grey;" title="'.__('Not available', MASE_TEXT_DOMAIN).'"></span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                foreach($tags as &$tag) {
                                    $tag = '<a class="zone_search_term" data-value="'.$tag.'" href="#">'.$tag.'</a>';
                                }
                                ?>
                                <?php echo implode(", ", $tags); ?>
                            </td>
                            <td title="<?php echo implode(", ", $ad['countries']); ?>">
                                <?php
                                foreach($ad['countries'] as &$_country) {
                                    $_country = '<a class="zone_search_term" data-value="'.$_country.'" href="#">'.$_country.'</a>';
                                }
                                echo implode(", ", array_slice($ad['countries'], 0, 15));
                                if(count($ad['countries']) > 15) echo ', ...';
                                ?>
                            </td>
                            <td>
                                <?php $devices_str = array();
                                if(in_array(MASE_DEVICE_DESKTOP, $ad['device_ids'])) $devices_str[] = '<a class="zone_search_term" data-value="'.__('Desktop', MASE_TEXT_DOMAIN).'" href="#">'.__('Desktop', MASE_TEXT_DOMAIN).'</a>';
                                if(in_array(MASE_DEVICE_MOBILE, $ad['device_ids'])) $devices_str[] = '<a class="zone_search_term" data-value="'.__('Smartphone', MASE_TEXT_DOMAIN).'" href="#">'.__('Smartphone', MASE_TEXT_DOMAIN).'</a>';
                                if(in_array(MASE_DEVICE_TABLET, $ad['device_ids'])) $devices_str[] = '<a class="zone_search_term" data-value="'.__('Tablet', MASE_TEXT_DOMAIN).'" href="#">'.__('Tablet', MASE_TEXT_DOMAIN).'</a>';
                                echo implode(", ", $devices_str);
                                ?>
                            </td>
                            <td>
                                <a target="_blank" href="<?php echo $ad['target_url']; ?>"><?php echo $ad['target_url']; ?></a>
                            </td>
                            <?php if(MASE::$ZONE_HOURS_OF_DAY) { ?>
                                <?php
                                $selected_hours = array_map('intval', explode(',', $zone_ads[$ad['id']]['hours']));
                                if(empty($zone_ads[$ad['id']]['hours'])) {
                                    $selected_hours = range(0,23);
                                }
                                ?>
                                <td>
                                    <select class="mase_hours_of_day_select" multiple="multiple" name="ad[<?php echo $ad['id']; ?>][hours]" style="display: none;">
                                        <?php foreach(range(0, 23) as $hour) { ?>
                                            <option <?php selected(in_array($hour, $selected_hours)); ?> value="<?php echo $hour; ?>"> <?php printf("%02d:00", $hour); ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            <?php } ?>

                            <?php if(MASE::$ZONE_DAYS_OF_WEEK) { ?>
                                <?php
                                $selected_days = array_map('intval', explode(',', $zone_ads[$ad['id']]['days']));
                                if(empty($zone_ads[$ad['id']]['days'])) {
                                    $selected_days = range(1,7);
                                }
                                ?>
                                <td>
                                    <select class="mase_days_of_week_select" multiple="multiple" name="ad[<?php echo $ad['id']; ?>][days]" style="display: none;">
                                        <option value="1" <?php selected(in_array(1, $selected_days)); ?>><?php _e('Monday', MASE_TEXT_DOMAIN); ?></option>
                                        <option value="2" <?php selected(in_array(2, $selected_days)); ?>><?php _e('Tuesday', MASE_TEXT_DOMAIN); ?></option>
                                        <option value="3" <?php selected(in_array(3, $selected_days)); ?>><?php _e('Wednesday', MASE_TEXT_DOMAIN); ?></option>
                                        <option value="4" <?php selected(in_array(4, $selected_days)); ?>><?php _e('Thursday', MASE_TEXT_DOMAIN); ?></option>
                                        <option value="5" <?php selected(in_array(5, $selected_days)); ?>><?php _e('Friday', MASE_TEXT_DOMAIN); ?></option>
                                        <option value="6" <?php selected(in_array(6, $selected_days)); ?>><?php _e('Saturday', MASE_TEXT_DOMAIN); ?></option>
                                        <option value="7" <?php selected(in_array(7, $selected_days)); ?>><?php _e('Sunday', MASE_TEXT_DOMAIN); ?></option>
                                    </select>
                                </td>
                            <?php } ?>

                            <?php if(MASE::$ZONE_WEIGHT) { ?>
                                <td>
                                    <input type="text" class="form-control mase_ad_weight_input" style="width: 50px; display: none;" name="ad[<?php echo $ad['id']; ?>][weight]" value="<?php echo isset($zone_ads[$ad['id']]['weight']) ? intval($zone_ads[$ad['id']]['weight']) : '1' ?>" />
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', MASE_TEXT_DOMAIN); ?></button>
                <button type="submit" class="btn btn-primary "><?php _e('Save changes', MASE_TEXT_DOMAIN); ?></button>
            </div>
        </form>

        <script type="text/javascript">
            jQuery(document).ready(function() {
                datatable_options = { "search": { "caseInsensitive": true} };

                datatable_options['fnDrawCallback'] = function(oSettings) {
                    jQuery(window).trigger("zonemgr_draw_event", [ this ]);
                };

                if(mase_app.lng == "de_DE") {
                    datatable_options['language'] = jQuery.parseJSON('{"sEmptyTable":"Keine Daten in der Tabelle vorhanden","sInfo":"_START_ bis _END_ von _TOTAL_ Einträgen","sInfoEmpty":"0 bis 0 von 0 Einträgen","sInfoFiltered":"(gefiltert von _MAX_ Einträgen)","sInfoPostFix":"","sInfoThousands":".","sLengthMenu":"_MENU_ Einträge anzeigen","sLoadingRecords":"Wird geladen...","sProcessing":"Bitte warten...","sSearch":"Suchen","sZeroRecords":"Keine Einträge vorhanden.","oPaginate":{"sFirst":"Erste","sPrevious":"Zurück","sNext":"Nächste","sLast":"Letzte"},"oAria":{"sSortAscending":": aktivieren, um Spalte aufsteigend zu sortieren","sSortDescending":": aktivieren, um Spalte absteigend zu sortieren"}}')
                }
                jQuery('#mase_zone_configurator_tbl').MaseTable(datatable_options);
            } );
        </script>

        <?php
        die();
    }

    public static function wp_ajax_boja_zone_save() {
        $page_id = isset($_REQUEST['id']) && !empty($_REQUEST['id']) ? (int) $_REQUEST['id'] : false;
        $zone_identifier = $page_id ? BOJA_PREFIX.'zone_ads_'.$page_id : BOJA_PREFIX.'zone_ads';

        $zone_settings = array();

        if(isset($_REQUEST['ad']) && is_array($_REQUEST['ad'])) {
            foreach($_REQUEST['ad'] as $ad_id => $ad_config) {
                if(isset($ad_config['active']) && isset($ad_config['active']) == "1") {
                    $zone_settings[(int) $ad_id] = array(
                        'weight' => isset($_REQUEST['ad'][$ad_id]['weight']) && $_REQUEST['ad'][$ad_id]['weight'] > 0 ? intval($_REQUEST['ad'][$ad_id]['weight']) : 1,
                        'hours' => isset($_REQUEST['ad'][$ad_id]['hours']) ? implode(",", array_map('intval', explode(",", $_REQUEST['ad'][$ad_id]['hours']))) : false,
                        'days' => isset($_REQUEST['ad'][$ad_id]['days']) ? implode(",", array_map('intval', explode(",", $_REQUEST['ad'][$ad_id]['days']))) : false,
                    );
                }
            }
        }

        update_option($zone_identifier, $zone_settings);
        echo json_encode(array('status' => 'ok'));
        die();
    }

    public static function wp_ajax_boja_go() {
        $ad_block = isset($_REQUEST['ad_block']) ? (bool) $_REQUEST['ad_block'] : false;

        $page_id = isset($_REQUEST['id']) && !empty($_REQUEST['id']) ? (int) $_REQUEST['id'] : false;
        $zone_identifier = $page_id ? BOJA_PREFIX.'zone_ads_'.$page_id : BOJA_PREFIX.'zone_ads';
        $device_id = MASE::get_user_device();
        $zone_ads = get_option($zone_identifier);

        if(!empty($zone_ads)) {
            $query_args['device_id'] = $device_id;
            $country = MASE::get_user_country();
            if($country) $query_args['country'] = $country;
            $query_args['ids'] = array_keys($zone_ads);
            $connection_id = MASE_Pro::get_user_connection();
            if($connection_id) $query_args['connection_id'] = $connection_id;

            $ads = MASE_Ads_Generic::GetAds($query_args);
            if(!empty($ads)) {
                $ad = MASE_Ads_Generic::SelectZoneAd($ads, $zone_ads);
                if($ad) {
                    $target = $ad['target_url'];
                    MASE_Pro_Log::view($ad['pro_id'], $ad['media_type'], (int) MASE::get_user_device(), MASE::get_user_country(), MASE_Pro::get_user_connection(), $ad_block);
                    header('Location: '.$target);
                } else {
                    if(BOJA::$settings['mase_use_boja_fb_url']) {
                        $target = !empty(BOJA::$settings['target']) ? BOJA::$settings['target'] : get_site_url();
                        header('Location: '.$target);
                    } else {
                        echo <<<EOF
                        <html>
                        <head>
                        <title>Please wait</title>
                        </head>
                        <body>
                        <script type="text/javascript">
                            window.history.go(-3);
                        </script>
                        </body>
                        </html>
EOF;

                    }
                }

            } else {
                if(BOJA::$settings['mase_use_boja_fb_url']) {
                    $target = !empty(BOJA::$settings['target']) ? BOJA::$settings['target'] : get_site_url();
                    header('Location: '.$target);
                } else {
                    echo <<<EOF
                    <html>
                    <head>
                    <title>Please wait</title>
                    </head>
                    <body>
                    <script type="text/javascript">
                        window.history.go(-3);
                    </script>
                    </body>
                    </html>
EOF;


                }
            }
        } elseif(BOJA::$settings['mase_use_boja_fb_url']) {
            $target = !empty(BOJA::$settings['target']) ? BOJA::$settings['target'] : get_site_url();
            header('Location: '.$target);
        } else {
            echo <<<EOF
                    <html>
                    <head>
                    <title>Please wait</title>
                    </head>
                    <body>
                    <script type="text/javascript">
                        window.history.go(-3);
                    </script>
                    </body>
                    </html>
EOF;
        }
        die();
    }

}