<?php

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	\McpManager\Abilities\ServiceProvider::class,
	\McpManager\Admin\ServiceProvider::class,
];
