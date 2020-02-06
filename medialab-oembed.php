<?php
/**
 * Plugin Name: MediaLab oEmbed
 * Description: Register MediaLab as an oEmbed provider
 * Version: 0.1
 * Author: MediaLab Online BV
 * Author URI: https://www.medialab.co
 */
define('MEDIALAB_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('MEDIALAB_INCLUDES_DIR', MEDIALAB_PLUGIN_DIR . 'includes/');

require_once MEDIALAB_INCLUDES_DIR . 'MediaLabConfig.php';
require_once MEDIALAB_INCLUDES_DIR . 'MediaLabOembed.php';

$medialab_oembed_config = new MediaLabConfig(__FILE__);

$medialab_oembed = new MediaLabOembed($medialab_oembed_config);
