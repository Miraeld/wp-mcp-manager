<?php

declare( strict_types = 1 );

namespace WPMedia\McpManager\Abilities;

class AbilityReader {

	/**
	 * Returns all registered abilities grouped by plugin namespace.
	 *
	 * @return array<string, array{label: string, count: int, abilities: list<array>}>
	 */
	public function get_grouped(): array {
		if ( ! function_exists( 'wp_get_abilities' ) ) {
			return [];
		}

		$abilities = wp_get_abilities();
		$groups    = [];

		foreach ( $abilities as $ability ) {
			$name      = $ability->get_name();
			$namespace = strstr( $name, '/', true ) ?: $name;

			if ( ! isset( $groups[ $namespace ] ) ) {
				$groups[ $namespace ] = [
					'label'     => $this->namespace_to_label( $namespace ),
					'abilities' => [],
				];
			}

			$meta        = $ability->get_meta();
			$annotations = $meta['annotations'] ?? [];

			$groups[ $namespace ]['abilities'][] = [
				'name'        => $name,
				'label'       => $ability->get_label(),
				'description' => $ability->get_description(),
				'category'    => $ability->get_category(),
				'show_in_rest'=> ! empty( $meta['show_in_rest'] ),
				'mcp_public'  => ! empty( $meta['mcp']['public'] ),
				'mcp_type'    => $meta['mcp']['type'] ?? 'tool',
				'readonly'    => $annotations['readonly'] ?? null,
				'destructive' => $annotations['destructive'] ?? null,
				'idempotent'  => $annotations['idempotent'] ?? null,
			];
		}

		ksort( $groups );

		return $groups;
	}

	/**
	 * Returns all registered ability categories keyed by slug.
	 *
	 * @return array<string, array{label: string, description: string}>
	 */
	public function get_categories(): array {
		if ( ! function_exists( 'wp_get_ability_categories' ) ) {
			return [];
		}

		$result = [];

		foreach ( wp_get_ability_categories() as $slug => $category ) {
			$result[ $slug ] = [
				'label'       => $category->get_label(),
				'description' => $category->get_description(),
			];
		}

		return $result;
	}

	/** Returns the abilities REST endpoint URL. */
	public function get_rest_url(): string {
		return rest_url( 'wp-abilities/v1/abilities' );
	}

	/** Returns the total count of registered abilities. */
	public function get_total_count(): int {
		if ( ! function_exists( 'wp_get_abilities' ) ) {
			return 0;
		}

		return count( wp_get_abilities() );
	}

	/** Returns whether the WP Abilities API is available. */
	public function has_abilities_api(): bool {
		return function_exists( 'wp_get_abilities' );
	}

	private function namespace_to_label( string $namespace ): string {
		return ucwords( str_replace( [ '-', '_' ], ' ', $namespace ) );
	}
}
