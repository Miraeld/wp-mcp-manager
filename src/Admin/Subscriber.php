<?php

declare( strict_types = 1 );

namespace McpManager\Admin;

use McpManager\Admin\Page\McpManagerPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
			__( 'MCP Manager', 'mcp-manager' ),
			__( 'MCP Manager', 'mcp-manager' ),
			'manage_options',
			'mcp-manager',
			[ $this->page, 'render' ],
			'dashicons-rest-api',
			80
		);
	}
}
