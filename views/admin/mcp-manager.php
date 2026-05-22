<?php
/**
 * Admin view: MCP Manager page.
 *
 * Available variables (injected by McpManagerPage::render):
 *
 * @var array  $abilities    Grouped abilities: [ namespace => [ 'label', 'abilities' => [...] ] ]
 * @var array  $categories   Ability categories: [ slug => [ 'label', 'description' ] ]
 * @var string $rest_url     WP Abilities REST endpoint URL.
 * @var int    $total_count  Total number of registered abilities.
 * @var bool   $has_api      Whether the WP Abilities API is available.
 * @var bool   $has_adapter  Whether a WordPress MCP adapter is detected.
 * @var string $wp_version   Current WordPress version.
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$site_url = get_site_url();
?>
<div class="wrap" id="wp-mcp-manager">

<style>
#wp-mcp-manager { max-width: 1200px; }
#wp-mcp-manager h1 { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
#wp-mcp-manager .mcp-version { font-size: 13px; color: #888; font-weight: normal; background: #f0f0f0; padding: 2px 8px; border-radius: 10px; }

/* Status bar */
.mcp-status-bar { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 24px; }
.mcp-status-card { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 16px 18px; }
.mcp-status-card .card-title { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #888; margin-bottom: 6px; }
.mcp-status-card .card-value { font-size: 15px; font-weight: 600; color: #1d2327; display: flex; align-items: center; gap: 6px; }
.mcp-status-card .card-value code { font-size: 12px; background: #f6f7f7; padding: 2px 6px; border-radius: 3px; font-weight: normal; word-break: break-all; }
.mcp-status-card.card-ok { border-left: 4px solid #00a32a; }
.mcp-status-card.card-warn { border-left: 4px solid #dba617; }
.mcp-status-card.card-info { border-left: 4px solid #2271b1; }
.mcp-badge-ok { color: #00a32a; }
.mcp-badge-warn { color: #dba617; }

/* Copy button */
.mcp-copy-wrap { display: flex; align-items: center; gap: 8px; margin-top: 6px; }
.mcp-copy-wrap code { flex: 1; font-size: 11px; background: #f6f7f7; padding: 4px 8px; border-radius: 3px; border: 1px solid #e5e5e5; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 340px; display: block; }
.mcp-copy-btn { flex-shrink: 0; }

/* Tabs */
.mcp-tabs { display: flex; gap: 0; margin-bottom: 0; border-bottom: 1px solid #c3c4c7; }
.mcp-tabs a { display: block; padding: 10px 18px; text-decoration: none; color: #50575e; font-size: 14px; border: 1px solid transparent; border-bottom: none; margin-bottom: -1px; border-radius: 4px 4px 0 0; }
.mcp-tabs a:hover { color: #1d2327; background: #f6f7f7; }
.mcp-tabs a.active { background: #fff; border-color: #c3c4c7; color: #1d2327; font-weight: 600; }
.mcp-tab-panel { display: none; background: #fff; border: 1px solid #c3c4c7; border-top: none; border-radius: 0 0 4px 4px; padding: 24px; }
.mcp-tab-panel.active { display: block; }

/* Namespace groups */
.mcp-group { margin-bottom: 32px; }
.mcp-group-header { display: flex; align-items: baseline; gap: 10px; border-bottom: 2px solid #f0f0f1; padding-bottom: 8px; margin-bottom: 16px; }
.mcp-group-header h2 { margin: 0; font-size: 16px; }
.mcp-group-count { font-size: 12px; color: #888; background: #f0f0f1; padding: 2px 8px; border-radius: 10px; }

/* Ability cards grid */
.mcp-abilities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 12px; }
.mcp-ability-card { border: 1px solid #e5e5e5; border-radius: 6px; padding: 14px 16px; background: #fafafa; }
.mcp-ability-card:hover { border-color: #2271b1; background: #fff; }
.mcp-ability-name { font-size: 11px; color: #888; font-family: monospace; margin-bottom: 4px; }
.mcp-ability-label { font-size: 14px; font-weight: 600; color: #1d2327; margin-bottom: 6px; }
.mcp-ability-desc { font-size: 13px; color: #50575e; line-height: 1.5; margin-bottom: 10px; }
.mcp-ability-badges { display: flex; flex-wrap: wrap; gap: 5px; }
.mcp-badge { display: inline-flex; align-items: center; font-size: 11px; padding: 2px 8px; border-radius: 10px; font-weight: 500; }
.mcp-badge-category { background: #dceefb; color: #135e96; }
.mcp-badge-rest { background: #edfaef; color: #00a32a; }
.mcp-badge-mcp { background: #f0e6ff; color: #7c3aed; }
.mcp-badge-readonly { background: #fff8e5; color: #996800; }
.mcp-badge-destructive { background: #fce8e8; color: #cc1818; }
.mcp-badge-resource { background: #e8f0fe; color: #1a73e8; }

/* Setup guides */
.mcp-setup-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(420px, 1fr)); gap: 20px; }
.mcp-setup-card { border: 1px solid #e5e5e5; border-radius: 6px; overflow: hidden; }
.mcp-setup-card-header { background: #f6f7f7; border-bottom: 1px solid #e5e5e5; padding: 12px 16px; display: flex; align-items: center; gap: 10px; }
.mcp-setup-card-header h3 { margin: 0; font-size: 14px; }
.mcp-setup-card-body { padding: 16px; }
.mcp-setup-card-body p { margin-top: 0; font-size: 13px; color: #50575e; }
.mcp-setup-card-body .mcp-config-path { font-family: monospace; font-size: 12px; background: #f6f7f7; padding: 4px 8px; border-radius: 3px; border: 1px solid #e5e5e5; display: inline-block; margin-bottom: 10px; }
.mcp-code-block { position: relative; background: #1e1e2e; border-radius: 6px; overflow: hidden; }
.mcp-code-block pre { margin: 0; padding: 16px; overflow-x: auto; color: #cdd6f4; font-size: 12px; line-height: 1.6; }
.mcp-code-block .mcp-copy-code-btn { position: absolute; top: 8px; right: 8px; font-size: 11px; padding: 3px 8px; }

/* Empty state */
.mcp-empty { text-align: center; padding: 48px 24px; color: #888; }
.mcp-empty .dashicons { font-size: 48px; width: 48px; height: 48px; color: #c3c4c7; margin-bottom: 12px; }

/* Categories table */
.mcp-categories-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
.mcp-categories-table th { text-align: left; padding: 8px 12px; border-bottom: 2px solid #e5e5e5; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; color: #888; }
.mcp-categories-table td { padding: 10px 12px; border-bottom: 1px solid #f0f0f1; font-size: 13px; }
.mcp-categories-table tr:last-child td { border-bottom: none; }
.mcp-categories-table .cat-slug { font-family: monospace; font-size: 12px; color: #888; }
</style>

<h1>
	<span class="dashicons dashicons-rest-api" style="font-size:28px;width:28px;height:28px;color:#2271b1"></span>
	<?php esc_html_e( 'WP MCP Manager', 'wp-mcp-manager' ); ?>
	<span class="mcp-version">v0.1.0 PoC</span>
</h1>

<!-- ── Status Bar ── -->
<div class="mcp-status-bar">

	<div class="mcp-status-card <?php echo $has_api ? 'card-ok' : 'card-warn'; ?>">
		<div class="card-title"><?php esc_html_e( 'Abilities API', 'wp-mcp-manager' ); ?></div>
		<div class="card-value">
			<?php if ( $has_api ) : ?>
				<span class="dashicons dashicons-yes-alt mcp-badge-ok"></span>
				<?php
				printf(
					/* translators: %s: WordPress version */
					esc_html__( 'WordPress %s', 'wp-mcp-manager' ),
					esc_html( $wp_version )
				);
				?>
			<?php else : ?>
				<span class="dashicons dashicons-warning mcp-badge-warn"></span>
				<?php esc_html_e( 'Requires WP 6.9+', 'wp-mcp-manager' ); ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="mcp-status-card card-info">
		<div class="card-title"><?php esc_html_e( 'Registered Tools', 'wp-mcp-manager' ); ?></div>
		<div class="card-value">
			<span class="dashicons dashicons-admin-tools"></span>
			<?php echo esc_html( $total_count ); ?>
			<span style="font-weight:normal;color:#888;font-size:13px">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %d: number of plugin namespaces */
						_n( 'from %d plugin', 'from %d plugins', count( $abilities ), 'wp-mcp-manager' ),
						count( $abilities )
					)
				);
				?>
			</span>
		</div>
	</div>

	<div class="mcp-status-card <?php echo $has_adapter ? 'card-ok' : 'card-warn'; ?>">
		<div class="card-title"><?php esc_html_e( 'MCP Adapter', 'wp-mcp-manager' ); ?></div>
		<div class="card-value">
			<?php if ( $has_adapter ) : ?>
				<span class="dashicons dashicons-yes-alt mcp-badge-ok"></span>
				<?php esc_html_e( 'Detected', 'wp-mcp-manager' ); ?>
			<?php else : ?>
				<span class="dashicons dashicons-warning mcp-badge-warn"></span>
				<?php esc_html_e( 'Not installed', 'wp-mcp-manager' ); ?>
			<?php endif; ?>
		</div>
	</div>

</div>

<?php if ( $has_api ) : ?>
<div class="mcp-status-card card-info" style="margin-bottom:24px">
	<div class="card-title"><?php esc_html_e( 'REST Endpoint', 'wp-mcp-manager' ); ?></div>
	<div class="mcp-copy-wrap">
		<code id="mcp-rest-url"><?php echo esc_html( $rest_url ); ?></code>
		<button class="button button-secondary mcp-copy-btn" data-clipboard-target="#mcp-rest-url">
			<?php esc_html_e( 'Copy', 'wp-mcp-manager' ); ?>
		</button>
	</div>
</div>
<?php endif; ?>

<!-- ── Tabs ── -->
<nav class="mcp-tabs" id="mcp-tab-nav">
	<a href="#tab-tools" class="active">
		<?php
		printf(
			/* translators: %d: number of tools */
			esc_html__( 'Tools (%d)', 'wp-mcp-manager' ),
			(int) $total_count
		);
		?>
	</a>
	<a href="#tab-categories">
		<?php
		printf(
			/* translators: %d: number of categories */
			esc_html__( 'Categories (%d)', 'wp-mcp-manager' ),
			count( $categories )
		);
		?>
	</a>
	<a href="#tab-setup"><?php esc_html_e( 'Setup Guide', 'wp-mcp-manager' ); ?></a>
</nav>

<!-- ── Tools tab ── -->
<div id="tab-tools" class="mcp-tab-panel active">
	<?php if ( empty( $abilities ) ) : ?>
		<div class="mcp-empty">
			<div class="dashicons dashicons-admin-tools"></div>
			<p><?php esc_html_e( 'No abilities registered yet.', 'wp-mcp-manager' ); ?></p>
			<p style="font-size:13px">
				<?php esc_html_e( 'Plugins register tools on the wp_abilities_api_init action. Try activating a plugin that uses the WP Abilities API.', 'wp-mcp-manager' ); ?>
			</p>
		</div>
	<?php else : ?>
		<?php foreach ( $abilities as $namespace => $group ) : ?>
			<div class="mcp-group">
				<div class="mcp-group-header">
					<h2><?php echo esc_html( $group['label'] ); ?></h2>
					<span class="mcp-group-count">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %d: number of tools */
								_n( '%d tool', '%d tools', count( $group['abilities'] ), 'wp-mcp-manager' ),
								count( $group['abilities'] )
							)
						);
						?>
					</span>
				</div>

				<div class="mcp-abilities-grid">
					<?php foreach ( $group['abilities'] as $ability ) : ?>
						<div class="mcp-ability-card">
							<div class="mcp-ability-name"><?php echo esc_html( $ability['name'] ); ?></div>
							<div class="mcp-ability-label"><?php echo esc_html( $ability['label'] ); ?></div>
							<?php if ( $ability['description'] ) : ?>
								<div class="mcp-ability-desc"><?php echo esc_html( $ability['description'] ); ?></div>
							<?php endif; ?>
							<div class="mcp-ability-badges">
								<?php if ( $ability['category'] ) : ?>
									<span class="mcp-badge mcp-badge-category">
										<?php echo esc_html( $categories[ $ability['category'] ]['label'] ?? $ability['category'] ); ?>
									</span>
								<?php endif; ?>
								<?php if ( $ability['show_in_rest'] ) : ?>
									<span class="mcp-badge mcp-badge-rest">REST</span>
								<?php endif; ?>
								<?php if ( $ability['mcp_public'] ) : ?>
									<span class="mcp-badge mcp-badge-mcp">
										<?php echo esc_html( 'mcp:' . $ability['mcp_type'] ); ?>
									</span>
								<?php endif; ?>
								<?php if ( true === $ability['readonly'] ) : ?>
									<span class="mcp-badge mcp-badge-readonly">readonly</span>
								<?php endif; ?>
								<?php if ( true === $ability['destructive'] ) : ?>
									<span class="mcp-badge mcp-badge-destructive">destructive</span>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<!-- ── Categories tab ── -->
<div id="tab-categories" class="mcp-tab-panel">
	<?php if ( empty( $categories ) ) : ?>
		<div class="mcp-empty">
			<div class="dashicons dashicons-tag"></div>
			<p><?php esc_html_e( 'No ability categories registered.', 'wp-mcp-manager' ); ?></p>
		</div>
	<?php else : ?>
		<table class="mcp-categories-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Slug', 'wp-mcp-manager' ); ?></th>
					<th><?php esc_html_e( 'Label', 'wp-mcp-manager' ); ?></th>
					<th><?php esc_html_e( 'Description', 'wp-mcp-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $categories as $slug => $cat ) : ?>
					<tr>
						<td class="cat-slug"><?php echo esc_html( $slug ); ?></td>
						<td><?php echo esc_html( $cat['label'] ); ?></td>
						<td><?php echo esc_html( $cat['description'] ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>

<!-- ── Setup Guide tab ── -->
<div id="tab-setup" class="mcp-tab-panel">
	<p style="font-size:14px;color:#50575e;margin-top:0">
		<?php esc_html_e( 'Connect your WordPress site to AI assistants that support the Model Context Protocol (MCP).', 'wp-mcp-manager' ); ?>
	</p>

	<?php if ( ! $has_adapter ) : ?>
		<div class="notice notice-warning inline" style="margin-bottom:20px">
			<p>
				<strong><?php esc_html_e( 'MCP Adapter not detected.', 'wp-mcp-manager' ); ?></strong>
				<?php esc_html_e( 'Claude Desktop and Cursor use MCP over SSE, which requires a separate adapter. The configs below show what the setup would look like once the adapter is installed.', 'wp-mcp-manager' ); ?>
			</p>
		</div>
	<?php endif; ?>

	<?php
	$site_host       = wp_parse_url( $site_url, PHP_URL_HOST );
	$mcp_sse_url     = $site_url . '/wp-json/mcp/v1/sse';
	$abilities_url   = $rest_url;

	// Example auth header — in a real setup this would be an application password.
	$auth_note = esc_html__( 'Replace with your WordPress Application Password (Users → Profile → Application Passwords).', 'wp-mcp-manager' );

	$claude_config = wp_json_encode(
		[
			'mcpServers' => [
				'wordpress' => [
					'url'     => $mcp_sse_url,
					'headers' => [
						'Authorization' => 'Basic ' . base64_encode( 'your-username:xxxx-xxxx-xxxx-xxxx' ),
					],
				],
			],
		],
		JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
	);

	$cursor_config = wp_json_encode(
		[
			'mcpServers' => [
				'wordpress' => [
					'url'     => $mcp_sse_url,
					'headers' => [
						'Authorization' => 'Basic ' . base64_encode( 'your-username:xxxx-xxxx-xxxx-xxxx' ),
					],
				],
			],
		],
		JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
	);

	$vscode_config = wp_json_encode(
		[
			'mcp' => [
				'servers' => [
					'wordpress' => [
						'type'    => 'sse',
						'url'     => $mcp_sse_url,
						'headers' => [
							'Authorization' => 'Basic ' . base64_encode( 'your-username:xxxx-xxxx-xxxx-xxxx' ),
						],
					],
				],
			],
		],
		JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
	);

	$zed_config = wp_json_encode(
		[
			'context_servers' => [
				'wordpress' => [
					'url'     => $mcp_sse_url,
					'headers' => [
						'Authorization' => 'Basic ' . base64_encode( 'your-username:xxxx-xxxx-xxxx-xxxx' ),
					],
				],
			],
		],
		JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
	);
	?>

	<div class="mcp-setup-grid">

		<!-- Claude Desktop -->
		<div class="mcp-setup-card">
			<div class="mcp-setup-card-header">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" fill="#d97757"/><path d="M8 12h8M12 8v8" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
				<h3><?php esc_html_e( 'Claude Desktop', 'wp-mcp-manager' ); ?></h3>
			</div>
			<div class="mcp-setup-card-body">
				<p><?php esc_html_e( 'Edit your Claude Desktop configuration file:', 'wp-mcp-manager' ); ?></p>
				<code class="mcp-config-path">~/Library/Application Support/Claude/claude_desktop_config.json</code>
				<p style="font-size:12px;color:#888"><?php echo esc_html( $auth_note ); ?></p>
				<div class="mcp-code-block">
					<button class="button mcp-copy-code-btn" data-clipboard-code="<?php echo esc_attr( $claude_config ); ?>">
						<?php esc_html_e( 'Copy', 'wp-mcp-manager' ); ?>
					</button>
					<pre><?php echo esc_html( $claude_config ); ?></pre>
				</div>
			</div>
		</div>

		<!-- Cursor -->
		<div class="mcp-setup-card">
			<div class="mcp-setup-card-header">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#1a1a1a"/><path d="M6 6l12 6-12 6V6z" fill="#fff"/></svg>
				<h3><?php esc_html_e( 'Cursor', 'wp-mcp-manager' ); ?></h3>
			</div>
			<div class="mcp-setup-card-body">
				<p><?php esc_html_e( 'Edit your Cursor MCP configuration file:', 'wp-mcp-manager' ); ?></p>
				<code class="mcp-config-path">~/.cursor/mcp.json</code>
				<p style="font-size:12px;color:#888"><?php echo esc_html( $auth_note ); ?></p>
				<div class="mcp-code-block">
					<button class="button mcp-copy-code-btn" data-clipboard-code="<?php echo esc_attr( $cursor_config ); ?>">
						<?php esc_html_e( 'Copy', 'wp-mcp-manager' ); ?>
					</button>
					<pre><?php echo esc_html( $cursor_config ); ?></pre>
				</div>
			</div>
		</div>

		<!-- VS Code -->
		<div class="mcp-setup-card">
			<div class="mcp-setup-card-header">
				<svg width="20" height="20" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path fill="#0065a9" d="M74.9 7.4L29.1 47.5 12 34.2 0 41v18l12 6.8 17.1-13.3 45.8 40.1L100 83.5V16.5L74.9 7.4z"/></svg>
				<h3><?php esc_html_e( 'VS Code (GitHub Copilot)', 'wp-mcp-manager' ); ?></h3>
			</div>
			<div class="mcp-setup-card-body">
				<p><?php esc_html_e( 'Add to your VS Code settings.json (workspace or user):', 'wp-mcp-manager' ); ?></p>
				<code class="mcp-config-path">.vscode/settings.json</code>
				<p style="font-size:12px;color:#888"><?php echo esc_html( $auth_note ); ?></p>
				<div class="mcp-code-block">
					<button class="button mcp-copy-code-btn" data-clipboard-code="<?php echo esc_attr( $vscode_config ); ?>">
						<?php esc_html_e( 'Copy', 'wp-mcp-manager' ); ?>
					</button>
					<pre><?php echo esc_html( $vscode_config ); ?></pre>
				</div>
			</div>
		</div>

		<!-- Zed -->
		<div class="mcp-setup-card">
			<div class="mcp-setup-card-header">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#084cdf"/><text x="4" y="17" font-size="13" font-family="monospace" font-weight="bold" fill="#fff">Z</text></svg>
				<h3><?php esc_html_e( 'Zed', 'wp-mcp-manager' ); ?></h3>
			</div>
			<div class="mcp-setup-card-body">
				<p><?php esc_html_e( 'Add to your Zed settings file:', 'wp-mcp-manager' ); ?></p>
				<code class="mcp-config-path">~/.config/zed/settings.json</code>
				<p style="font-size:12px;color:#888"><?php echo esc_html( $auth_note ); ?></p>
				<div class="mcp-code-block">
					<button class="button mcp-copy-code-btn" data-clipboard-code="<?php echo esc_attr( $zed_config ); ?>">
						<?php esc_html_e( 'Copy', 'wp-mcp-manager' ); ?>
					</button>
					<pre><?php echo esc_html( $zed_config ); ?></pre>
				</div>
			</div>
		</div>

	</div><!-- .mcp-setup-grid -->

	<div style="margin-top:28px;padding:16px;background:#f6f7f7;border-radius:6px;border:1px solid #e5e5e5">
		<h3 style="margin-top:0"><?php esc_html_e( 'Available Tools', 'wp-mcp-manager' ); ?></h3>
		<p style="font-size:13px;color:#50575e">
			<?php esc_html_e( 'Once connected, the following tools will be available to the AI assistant:', 'wp-mcp-manager' ); ?>
		</p>
		<ul style="margin:0;column-count:2;column-gap:32px">
			<?php foreach ( $abilities as $ns_group ) : ?>
				<?php foreach ( $ns_group['abilities'] as $ability ) : ?>
					<li style="font-size:13px;margin-bottom:4px">
						<code style="font-size:11px"><?php echo esc_html( $ability['name'] ); ?></code>
						— <?php echo esc_html( $ability['label'] ); ?>
					</li>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</ul>
	</div>

</div><!-- #tab-setup -->

<script>
(function() {
	// Tab switching.
	const nav   = document.getElementById('mcp-tab-nav');
	const panels = document.querySelectorAll('.mcp-tab-panel');

	nav.querySelectorAll('a').forEach(function(link) {
		link.addEventListener('click', function(e) {
			e.preventDefault();
			nav.querySelectorAll('a').forEach(function(a) { a.classList.remove('active'); });
			panels.forEach(function(p) { p.classList.remove('active'); });
			link.classList.add('active');
			document.querySelector(link.getAttribute('href')).classList.add('active');
		});
	});

	// Copy to clipboard.
	function copyText(text, btn) {
		navigator.clipboard.writeText(text).then(function() {
			var orig = btn.textContent;
			btn.textContent = '✓ Copied!';
			setTimeout(function() { btn.textContent = orig; }, 2000);
		});
	}

	document.querySelectorAll('.mcp-copy-btn').forEach(function(btn) {
		btn.addEventListener('click', function() {
			var target = document.querySelector(btn.getAttribute('data-clipboard-target'));
			if (target) { copyText(target.textContent.trim(), btn); }
		});
	});

	document.querySelectorAll('.mcp-copy-code-btn').forEach(function(btn) {
		btn.addEventListener('click', function() {
			copyText(btn.getAttribute('data-clipboard-code'), btn);
		});
	});
})();
</script>

</div><!-- #wp-mcp-manager -->
