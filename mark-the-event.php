<?php
/**
 * Plugin Name:     Mark The Event
 * Description:     A Wordpress plugin for managing events
 * Version:         0.0.1
 * Author:          Mohammad Zarei
 * Author URI:      https://zareidev.ir
 * License:         GPL-2.0-or-later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     mark-the-event
 */

declare(strict_types=1);

namespace ZareiDev\MarkTheEvent;

if (! defined('WPINC')) {
    die;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Begins execution of the plugin.
 *
 * @return void
 */
function run()
{
    $plugin = new Plugin();
    $plugin->run();
}

run();