<?php

declare( strict_types = 1 );

namespace McpManager\Admin;

use McpManager\Admin\Page\McpManagerPage;
use McpManager\ServiceProvider\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider {

	protected array $provides = [
		'admin_subscriber',
		'mcp_manager_page',
	];

	public function register(): void {
		$this->getContainer()
			->addShared( 'mcp_manager_page', McpManagerPage::class )
			->addArguments( [ 'plugin.dir', 'ability_reader' ] );

		$this->getContainer()
			->addShared( 'admin_subscriber', Subscriber::class )
			->addArgument( 'mcp_manager_page' );
	}

	public function get_subscribers(): array {
		return [ 'admin_subscriber' ];
	}
}
