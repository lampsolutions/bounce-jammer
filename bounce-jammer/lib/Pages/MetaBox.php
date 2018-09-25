<div class="boja-bs" style="">
    <div class="row">
    <div class="col-md-12">
        <input type="hidden" name="boja_save" id="boja_save" value="<?php wp_create_nonce( 'boja_save' ); ?>" />
            <table class="table table-striped" style="margin: 0;">
                <tbody>
                <tr>
                    <td><?php _e('Bounce Jammer Mode', BOJA_TEXT_DOMAIN); ?></td>
                    <td>
                        <input style="margin: 0;" type="radio" name="boja_mode" value="0" <?php checked($settings['boja_mode'], 0); ?> />
                        <?php _e('Use global Bounce Jammer Settings for this Page', BOJA_TEXT_DOMAIN); ?><br/>
                        <input style="margin: 0;" type="radio" name="boja_mode" value="1" <?php checked($settings['boja_mode'], 1); ?> />
                        <?php _e('Force Enable Bounce Jammer for this Page', BOJA_TEXT_DOMAIN); ?><br/>
                        <input style="margin: 0;" type="radio" name="boja_mode" value="2" <?php checked($settings['boja_mode'], 2); ?> />
                        <?php _e('Force Disable Bounce Jammer for this Page', BOJA_TEXT_DOMAIN); ?><br/>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Override Target URL', BOJA_TEXT_DOMAIN); ?></td>
                    <td>
                        <input style="margin: 0;" <?php disabled($settings['boja_mode'], 0); ?> type="text" class="form-control boja_mode_child" name="boja_target" value="<?php echo $settings['target'] ?>" placeholder="http://advertising.example.com" />
                        <?php _e('', BOJA_TEXT_DOMAIN); ?>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Override Redirect Timeout', BOJA_TEXT_DOMAIN); ?></td>
                    <td>
                        <div style="margin: 0; width: 140px;" class="input-group">
                            <input <?php disabled($settings['boja_mode'], 0); ?> type="text" class="form-control boja_mode_child" name="boja_timeout" value="<?php echo $settings['timeout'] ?>" placeholder="" />
                            <span class="input-group-addon"><?php _e('Seconds', BOJA_TEXT_DOMAIN) ?></span>
                        </div>

                        <?php _e('Disable the redirect if the user stayed at least X seconds on this page', BOJA_TEXT_DOMAIN); ?>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Override Redirect Percent', BOJA_TEXT_DOMAIN); ?></td>
                    <td>
                        <div style="margin: 0; width: 90px;" class="input-group">
                            <input <?php disabled($settings['boja_mode'], 0); ?> type="text" class="form-control boja_mode_child" name="boja_percent" value="<?php echo $settings['percent'] ?>" placeholder="" />
                            <span class="input-group-addon"><?php _e('%', BOJA_TEXT_DOMAIN) ?></span>
                        </div>

                        <?php _e('Redirect only some users not all, default is 100 percent', BOJA_TEXT_DOMAIN); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-12">
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
                                        <input style="margin: 0;" type="checkbox" name="enable_mase" value="1" <?php checked($settings['enable_mase']); ?> />
                                        <?php _e('Use a moreAds SE Zone for the Bounce Jammer Target URL', BOJA_TEXT_DOMAIN); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Configure Zone', BOJA_TEXT_DOMAIN); ?></td>
                                    <td>
                                        <div class="mase-bs">

                                            <p>
                                                <button data-boja="true" data-id="<?php echo get_the_ID(); ?>" class="button button-primary mase_zone_configurator">
                                                    <?php _e('Start Zone Configurator', BOJA_TEXT_DOMAIN); ?>
                                                </button>
                                                <?php
                                                if(BOJA::moreAdsSEInstalled()) {
                                                    echo BOJA_MASE_Zone::get_zone_ad_count_html(BOJA_PREFIX.'zone_ads_'.get_the_ID())
                                                    ?>

                                                    <script type="text/javascript">
                                                        jQuery(window).on('hide.bs.modal', function(e) {
                                                            jQuery('#publish').click();
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
                                        <input style="margin: 0;" type="checkbox" name="mase_use_boja_fb_url" value="1" <?php checked($settings['mase_use_boja_fb_url']); ?> />
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

    </div>
    </div>
