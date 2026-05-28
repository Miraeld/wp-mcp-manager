<?php
/**
 * Plugin Name: MCP Manager
 * Description: Discover and manage Model Context Protocol (MCP) tools available on your WordPress site. Surfaces the WordPress Abilities API for AI assistants.
 * Author:      Gaël Robin
 * Version:     1.0.0
 * Requires at least: 6.9
 * Requires PHP: 8.0
 * Text Domain: mcp-manager
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	add_action(
		'admin_notices',
		static function (): void {
			printf(
				'<div class="notice notice-error"><p>%s</p></div>',
				esc_html__( 'MCP Manager: Dependencies not found. Please run composer install in the plugin directory.', 'mcp-manager' )
			);
		}
	);
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

use League\Container\Container;
use McpManager\Plugin\Plugin;

$mcp_manager_plugin = new Plugin( new Container(), __FILE__ );
add_action( 'plugins_loaded', [ $mcp_manager_plugin, 'init' ] );
