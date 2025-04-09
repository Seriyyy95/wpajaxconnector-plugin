<?php

/*
 *
 * Plugin Name: WPConnector
 * Plugin URI: https://example.com
 * Description: Access API for Wordpress
 * Version: 1.0
 * Author: Seriyyy95
 * Author URI: https://example.com
 * */

require __DIR__ . '/vendor/autoload.php';

new WPAjaxConnector\WPAjaxConnectorPlugin\Bootstrap();