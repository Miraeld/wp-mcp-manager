<?php

declare( strict_types = 1 );

namespace WPMedia\McpManager\Admin\Page;

use WPMedia\McpManager\Abilities\AbilityReader;

class McpManagerPage {

	private string        $plugin_dir;
	private AbilityReader $ability_reader;

	public function __construct( string $plugin_dir, AbilityReader $ability_reader ) {
		$this->plugin_dir     = $plugin_dir;
		$this->ability_reader = $ability_reader;
	}

	public function render(): void {
		$abilities    = $this->ability_reader->get_grouped();
		$categories   = $this->ability_reader->get_categories();
		$rest_url     = $this->ability_reader->get_rest_url();
		$total_count  = $this->ability_reader->get_total_count();
		$has_api      = $this->ability_reader->has_abilities_api();
		$wp_version   = get_bloginfo( 'version' );
		$has_adapter  = $this->detect_mcp_adapter();

		include $this->plugin_dir . 'views/admin/mcp-manager.php';
	}

	/** Checks whether a known MCP adapter plugin is active. */
	private function detect_mcp_adapter(): bool {
		return has_filter( 'mcp_adapter_default_server_config' )
			|| defined( 'WP_MCP_ADAPTER_VERSION' )
			|| class_exists( 'WordPress\\MCP\\Adapter' );
	}
}
