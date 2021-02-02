<?php
/**
 * Plugin Name:       WP_Plugin
 * Plugin URI:        -
 * Description:       Plugin as test
 * Version:           1.0.0
 * Requires at least: 5.4
 * Requires PHP:      8.0
 * Author:            Edvinas Sadlevicius
 * Author URI:        https://devedvinassadlevicius.azurewebsites.net
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       WP_Plugin
 * Domain Path:       /languages
 */
/*
WP_Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WP_Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WP_Plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/
/*Exit if called directly*/

defined('ABSPATH') || exit;

require __DIR__.'/vendor/autoload.php';

register_activation_hook(__FILE__, array('WP_Plugin\Main','On_Activation'));
register_deactivation_hook(__FILE__, array( 'WP_Plugin\Main', 'On_Deactivation'));
register_uninstall_hook(__FILE__,array('WP_Plugin\Main','On_Uninstall'));

const WP_Plugin_Root = __DIR__.'\\';
const WP_Plugin_Resources = WP_Plugin_Root.'\Resources';

define('WP_Plugin_Root_URL',plugin_dir_url(__FILE__));
const WP_Plugin_Resources_URL = WP_Plugin_Root_URL.'Resources/';

add_action('plugins_loaded',array('WP_Plugin\Main','Init'));