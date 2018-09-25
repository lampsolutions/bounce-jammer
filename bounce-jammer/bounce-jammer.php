<?php
/*
Plugin Name:        Bounce Jammer
Plugin URI:         https://www.lamp-solutions.de/
Description:        Bounce Jammer - Monetize your Bounce Rate
Version:            1.1.5
Author:             LAMP solutions GmbH
Author URI:         https://www.lamp-solutions.de/
License:            GPLv2
Text Domain:        boja
Domain Path:        /languages/
 */

defined( 'ABSPATH' ) or die();

define('BOJA_WPDIR', ABSPATH);
define('BOJA_DIR', plugin_dir_path(__FILE__));
define('BOJA_URL', plugin_dir_url(__FILE__));
define('BOJA_SLUG', plugin_basename(__FILE__));
define('BOJA_TEXTDOMAIN_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages/');
define('BOJA_PLUG_FILE', __FILE__);
define('BOJA_TEXT_DOMAIN', 'boja');
define('BOJA_PREFIX', 'boja_');

require_once(BOJA_DIR.'/lib/BOJA.php');
require_once(BOJA_DIR.'/lib/BOJA_MetaBoxes.php');
require_once(BOJA_DIR.'/lib/BOJA_Admin.php');

BOJA::init();