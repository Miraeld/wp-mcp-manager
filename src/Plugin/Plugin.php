<?php

declare( strict_types = 1 );

namespace McpManager\Plugin;

use League\Container\Container;

class Plugin {

	private Container $container;
	private string    $plugin_file;

	public function __construct( Container $container, string $plugin_file ) {
		$this->container   = $container;
		$this->plugin_file = $plugin_file;

		$plugin_dir = plugin_dir_path( $plugin_file );
		$plugin_url = plugin_dir_url( $plugin_file );

		$this->container->addShared( 'plugin.dir', static fn() => $plugin_dir );
		$this->container->addShared( 'plugin.url', static fn() => $plugin_url );
	}

	public function init(): void {
		$provider_classes = require dirname( $this->plugin_file ) . '/config/providers.php';
		$providers        = [];

		foreach ( $provider_classes as $class ) {
			$provider    = new $class();
			$providers[] = $provider;
			$this->container->addServiceProvider( $provider );
		}

		foreach ( $providers as $provider ) {
			if ( ! method_exists( $provider, 'get_subscribers' ) ) {
				continue;
			}
			foreach ( $provider->get_subscribers() as $subscriber_id ) {
				$subscriber = $this->container->get( $subscriber_id );
				if ( method_exists( $subscriber, 'register' ) ) {
					$subscriber->register();
				}
			}
		}
	}
}
