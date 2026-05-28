<?php

declare( strict_types = 1 );

namespace McpManager\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider as LeagueAbstractServiceProvider;

abstract class AbstractServiceProvider extends LeagueAbstractServiceProvider {

	/** @var string[] */
	protected array $provides = [];

	public function provides( string $id ): bool {
		return in_array( $id, $this->provides, true );
	}

	/** @return string[] */
	public function get_subscribers(): array {
		return [];
	}
}
