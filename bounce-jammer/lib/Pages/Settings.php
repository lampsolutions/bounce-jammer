<div class="boja-bs" style="margin-top: 20px; width: 99%;">
    <div class="col-md-12">
        <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <button type="button" id="stars" class="btn btn-info" href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                    <div class="hidden-xs"><?php _e('General', BOJA_TEXT_DOMAIN); ?></div>
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" id="favorites" class="btn btn-default" href="#tab2" data-toggle="tab"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                    <div class="hidden-xs"><?php _e('Settings', BOJA_TEXT_DOMAIN); ?></div>
                </button>
            </div>
        </div>

        <div class="well">
            <div class="tab-content">
                <div class="tab-pane fade in active row" id="tab1">

                    <div class="col-md-6">
                        <form method="post" action="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('Bounce Jammer Status', BOJA_TEXT_DOMAIN) ?>
                                </div>
                                <div class="panel-body" style="min-height: 200px; font-size: 17px; padding: 0;">
                                    <table class="table table-striped" style="margin: 0;">
                                        <tbody>
                                        <tr>
                                            <td><?php _e('Author', BOJA_TEXT_DOMAIN); ?></td>
                                            <td>
                                                LAMP solutions GmbH
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Plugin URL', BOJA_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <a href="https://github.com/lampsolutions/bounce-jammer" target="_blank">https://github.com/lampsolutions/bounce-jammer</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Plugin Version', BOJA_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <?php
                                                $data = get_plugin_data(BOJA_PLUG_FILE);
                                                echo $data['Version'];
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('License', BOJA_TEXT_DOMAIN); ?></td>
                                            <td>
                                                GPLv2
                                            </td>
                                        </tr>

                                        <tr></tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade in row" id="tab2">
                    <?php if(!is_network_admin()) { ?>
                        <div class="col-md-6">
                            <form method="post" action="">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <?php _e('General Settings', BOJA_TEXT_DOMAIN) ?>
                                    </div>
                                    <div class="panel-body" style="font-size: 13px; min-height: 200px; padding: 0;">
                                        <?php if(!empty($errors)) { foreach($errors as $error) { ?>
                                            <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                                        <?php } } ?>

                                        <table class="table table-striped" style="margin: 0;">
                                            <tbody>
                                            <tr>
                                                <td><?php _e('Globally Enable Bounce Jammer', BOJA_TEXT_DOMAIN); ?></td>
                                                <td>
                                                    <input style="margin: 0;" type="checkbox" name="enable_global" value="1" <?php checked(BOJA::$settings['enable_global']); ?> />
                                                    <?php _e('Enable Bounce Jammer for all Pages/Posts', BOJA_TEXT_DOMAIN); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php _e('Target URL', BOJA_TEXT_DOMAIN); ?></td>
                                                <td>
                                                    <input style="margin: 0;" type="text" class="form-control" name="target" value="<?php echo BOJA::$settings['target'] ?>" placeholder="http://advertising.example.com" />
                                                    <?php _e('', BOJA_TEXT_DOMAIN); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php _e('Redirect Timeout', BOJA_TEXT_DOMAIN); ?></td>
                                                <td>
                                                    <div style="margin: 0; width: 140px;" class="input-group">
                                                        <input type="text" class="form-control" name="timeout" value="<?php echo BOJA::$settings['timeout'] ?>" placeholder="" />
                                                        <span class="input-group-addon"><?php _e('Seconds', BOJA_TEXT_DOMAIN) ?></span>
                                                    </div>

                                                    <?php _e('Disable the redirect if the user stayed at least X seconds on the page', BOJA_TEXT_DOMAIN); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php _e('Redirect Percent', BOJA_TEXT_DOMAIN); ?></td>
                                                <td>
                                                    <div style="margin: 0; width: 90px;" class="input-group">
                                                        <input type="text" class="form-control" name="percent" value="<?php echo BOJA::$settings['percent'] ?>" placeholder="" />
                                                        <span class="input-group-addon"><?php _e('%', BOJA_TEXT_DOMAIN) ?></span>
                                                    </div>

                                                    <?php _e('Redirect only some users not all, default is 100 percent.', BOJA_TEXT_DOMAIN); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php _e('Redirect Entry Page Only', BOJA_TEXT_DOMAIN); ?></td>
                                                <td>
                                                    <input style="margin: 0;" type="checkbox" name="redirect_entry_page_only" value="1" <?php checked(BOJA::$settings['redirect_entry_page_only']); ?> />
                                                    <?php _e('Redirect the user only on the entry page.', BOJA_TEXT_DOMAIN); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php _e('Bounce Jammer Override Widget', BOJA_TEXT_DOMAIN); ?></td>
                                                <td>
                                                    <?php _e('Enable Bounce Jammer Override Widget for the following Post Types:', BOJA_TEXT_DOMAIN); ?>
                                                    <br/><br/>
                                                    <?php foreach(get_post_types( array( 'public' => false, 'name' => 'attachment' ), 'names', 'NOT' ) as $post_type) { ?>
                                                        <input style="margin: 0;" type="checkbox" name="enable_post_types[<?php echo $post_type; ?>]" value="1" <?php checked(isset(BOJA::$settings['enable_post_types'][$post_type])); ?> />
                                                        <?php echo $post_type; ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <?php if($failed_geoip_upload) { ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?php _e('Failed: The selected/uploaded file is not a valid MaxMind GeoIP Database File.', BOJA_TEXT_DOMAIN); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="button-float-wrapper" style="min-height: 40px;">
                                            <button name="boja_set_settings" value="1" class="btn btn-info media-button icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-ok img-circle text-info"></span> <?php _e('Save', BOJA_TEXT_DOMAIN) ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>

                    <?php if(is_network_admin()) { ?>
                    <div class="col-md-6">
                        <form method="post" action="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('General Settings', BOJA_TEXT_DOMAIN) ?>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px; padding: 0;">
                                    <?php if(!empty($errors)) { foreach($errors as $error) { ?>
                                        <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                                    <?php } } ?>

                                    <table class="table table-striped" style="margin: 0;">
                                        <tbody>
                                        <tr>
                                            <td><?php _e('Globally Enable Bounce Jammer', BOJA_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <input style="margin: 0;" type="checkbox" name="enable_global" value="1" <?php checked(BOJA::$mu_settings['enable_global']); ?> />
                                                <?php _e('Enable Bounce Jammer for all Pages/Posts', BOJA_TEXT_DOMAIN); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Target URL', BOJA_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <input style="margin: 0;" type="text" class="form-control" name="target" value="<?php echo BOJA::$mu_settings['target'] ?>" placeholder="http://advertising.example.com" />
                                                <?php _e('', BOJA_TEXT_DOMAIN); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Redirect Timeout', BOJA_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <div style="margin: 0; width: 140px;" class="input-group">
                                                    <input type="text" class="form-control" name="timeout" value="<?php echo BOJA::$mu_settings['timeout'] ?>" placeholder="" />
                                                    <span class="input-group-addon"><?php _e('Seconds', BOJA_TEXT_DOMAIN) ?></span>
                                                </div>

                                                <?php _e('Disable the redirect if the user stayed at least X seconds on the page', BOJA_TEXT_DOMAIN); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Redirect Percent', BOJA_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <div style="margin: 0; width: 90px;" class="input-group">
                                                    <input type="text" class="form-control" name="percent" value="<?php echo BOJA::$mu_settings['percent'] ?>" placeholder="" />
                                                    <span class="input-group-addon"><?php _e('%', BOJA_TEXT_DOMAIN) ?></span>
                                                </div>

                                                <?php _e('Redirect only some users not all, default is 100 percent.', BOJA_TEXT_DOMAIN); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Redirect only on entry page', BOJA_TEXT_DOMAIN); ?></td>
                                            <td>
                                                <input style="margin: 0;" type="checkbox" name="redirect_entry_page_only" value="1" <?php checked(BOJA::$mu_settings['redirect_entry_page_only']); ?> />
                                                <?php _e('Redirect the user only on the entry page.', BOJA_TEXT_DOMAIN); ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <?php if($failed_geoip_upload) { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php _e('Failed: The selected/uploaded file is not a valid MaxMind GeoIP Database File.', BOJA_TEXT_DOMAIN); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <button name="boja_set_settings" value="1" class="btn btn-info media-button icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-ok img-circle text-info"></span> <?php _e('Save', BOJA_TEXT_DOMAIN) ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php } ?>

                    <?php if(is_network_admin()) { ?>
                        <div class="col-md-6">
                            <form method="post" action="">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <?php _e('Disable Global Multiblog Bounce Jammer Settings', BOJA_TEXT_DOMAIN) ?>
                                    </div>
                                    <div class="panel-body" style="font-size: 13px; min-height: 200px; padding: 0;">
                                        <table class="table table-striped" style="margin: 0;">
                                            <tbody>
                                            <?php foreach(wp_get_sites() as $site) { ?>
                                                <tr>
                                                    <td><?php echo $site['domain']; ?></td>
                                                    <td>
                                                        <input style="margin: 0;" type="checkbox" name="sites[<?php echo (int)$site['blog_id']; ?>]" value="1" <?php checked(isset(BOJA::$mu_settings['sites'][(int)$site['blog_id']])); ?> />
                                                        <?php _e('Enable Single Blog Bounce Jammer configuration', BOJA_TEXT_DOMAIN); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="button-float-wrapper" style="min-height: 40px;">
                                            <button name="boja_set_sites" value="1" class="btn btn-info media-button icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-ok img-circle text-info"></span> <?php _e('Save', BOJA_TEXT_DOMAIN) ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>

                    <?php if(!is_network_admin()) { ?>
                    <div class="col-md-6">
                        <form method="post" action="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php _e('moreAds SE Integration', BOJA_TEXT_DOMAIN) ?>
                                </div>
                                <div class="panel-body" style="font-size: 13px; min-height: 200px; padding: 0;">

                                        <?php if(BOJA::moreAdsSEInstalled()) { ?>
                                            <table class="table table-striped" style="margin: 0;">
                                                <tbody>
                                                    <tr>
                                                        <td><?php _e('moreads SE Zone', BOJA_TEXT_DOMAIN); ?></td>
                                                        <td>
                                                            <input style="margin: 0;" type="checkbox" name="enable_mase" value="1" <?php checked(BOJA::$settings['enable_mase']); ?> />
                                                            <?php _e('Use a moreAds SE Zone for the Bounce Jammer Target URL', BOJA_TEXT_DOMAIN); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php _e('Configure Zone', BOJA_TEXT_DOMAIN); ?></td>
                                                        <td>
                                                            <div class="mase-bs">

                                                                <p>
                                                                    <button data-boja="true" class="button button-primary mase_zone_configurator">
                                                                        <?php _e('Start Zone Configurator', BOJA_TEXT_DOMAIN); ?>
                                                                    </button>
                                                                    <?php
                                                                    if(BOJA::moreAdsSEInstalled()) {
                                                                        echo BOJA_MASE_Zone::get_zone_ad_count_html(BOJA_PREFIX.'zone_ads');
                                                                        ?>

                                                                        <script type="text/javascript">
                                                                            jQuery(window).on('hide.bs.modal', function(e) {
                                                                                jQuery('#boja_set_mase_settings').click();
                                                                            });
                                                                        </script>
                                                                    <?php } ?>
                                                                </p>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php _e('Bounce Jammer Fallback URL', BOJA_TEXT_DOMAIN); ?></td>
                                                        <td>
                                                            <input style="margin: 0;" type="checkbox" name="mase_use_boja_fb_url" value="1" <?php checked(BOJA::$settings['mase_use_boja_fb_url']); ?> />
                                                            <?php _e('Use the current Bounce Jammer URL as a Fallback if no Ad was found', BOJA_TEXT_DOMAIN); ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        <?php } else { ?>
                                                    <h4 style="margin: 10px;">
                                                        <?php _e('moreAds SE is not installed, please install moreAds SE to enable additional Features, for example Device and Country based Bounce Jammer Target URLs', BOJA_TEXT_DOMAIN) ?>
                                                    </h4>
                                        <?php } ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="button-float-wrapper" style="min-height: 40px;">
                                        <button <?php if(!BOJA::moreAdsSEInstalled()) { ?>disabled="DISABLED"<?php } ?> name="boja_set_mase_settings" id="boja_set_mase_settings" value="1" class="btn btn-info media-button icon-btn btn-sm pull-right"><span class="glyphicon btn-glyphicon glyphicon glyphicon-ok img-circle text-info"></span> <?php _e('Save', BOJA_TEXT_DOMAIN) ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php } ?>

                </div>
            </div>
        </div>

    </div>


    <script type="text/javascript">
        jQuery('button[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', jQuery(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            jQuery(".btn-pref .btn").removeClass("btn-info").addClass("btn-default");
            jQuery('button[href="' + activeTab + '"]').tab('show').removeClass("btn-default").addClass("btn-info");
        }
    </script>
</div>