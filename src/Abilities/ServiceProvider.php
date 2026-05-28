<?php

declare( strict_types = 1 );

namespace McpManager\Abilities;

use McpManager\ServiceProvider\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider {

	protected array $provides = [
		'ability_reader',
	];

	public function register(): void {
		$this->getContainer()->addShared( 'ability_reader', AbilityReader::class );
	}
}
