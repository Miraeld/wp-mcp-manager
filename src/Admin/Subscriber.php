<?php

declare( strict_types = 1 );

namespace WPMedia\McpManager\Admin;

use WPMedia\McpManager\Admin\Page\McpManagerPage;

class Subscriber {

	private McpManagerPage $page;

	public function __construct( McpManagerPage $page ) {
		$this->page = $page;
	}

	public function register(): void {
		add_action( 'admin_menu', [ $this, 'add_menu' ] );
	}

	public function add_menu(): void {
		add_menu_page(
			__( 'MCP Manager', 'wp-mcp-manager' ),
			__( 'MCP Manager', 'wp-mcp-manager' ),
			'manage_options',
			'wp-mcp-manager',
			[ $this->page, 'render' ],
			'dashicons-rest-api',
			80
		);
	}
}
