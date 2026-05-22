<?php
/**
 * Plugin Name: WP MCP Manager
 * Plugin URI:  https://wpmedia.com/
 * Description: Discover and manage Model Context Protocol (MCP) tools available on your WordPress site. Surfaces the WordPress Abilities API for AI assistants.
 * Author:      WP Media
 * Version:     0.1.0
 * Requires at least: 6.9
 * Requires PHP: 8.0
 * Text Domain: wp-mcp-manager
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
				esc_html__( 'WP MCP Manager: Dependencies not found. Please run composer install in the plugin directory.', 'wp-mcp-manager' )
			);
		}
	);
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

use League\Container\Container;
use WPMedia\McpManager\Plugin\Plugin;

$mcp_manager = new Plugin( new Container(), __FILE__ );
add_action( 'plugins_loaded', [ $mcp_manager, 'init' ] );
