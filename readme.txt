=== MCP Manager ===
Contributors: gaelrobin
Tags: mcp, ai, tools, model context protocol, abilities
Requires at least: 6.9
Tested up to: 6.9
Stable tag: 0.1.0
Requires PHP: 8.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Discover and manage MCP tools on your WordPress site. Surfaces the WordPress Abilities API for AI assistants like Claude, Cursor, and VS Code.

== Description ==

WordPress 6.9 introduced the Abilities API — a way for plugins to register tools that AI assistants can call. But there's no admin UI for it.

MCP Manager fills that gap. It gives site owners a dashboard to:

* See which MCP tools are registered on their site and which plugin registered them
* Browse all ability categories with their slugs and descriptions
* Copy ready-to-paste connection configs for Claude Desktop, Cursor, VS Code (GitHub Copilot), and Zed

= Tabs =

**Tools** — lists every registered ability grouped by plugin namespace, with label, description, category, and behavioral annotations (readonly, destructive, idempotent).

**Categories** — shows all registered ability categories with their slugs and descriptions.

**Setup Guide** — copy-paste JSON configs for Claude Desktop, Cursor, VS Code, and Zed, pre-filled with your site's MCP endpoint URL.

= Requirements =

* WordPress 6.9 or higher (Abilities API)
* PHP 8.0 or higher
* An MCP adapter plugin to serve tools over SSE (e.g. `wordpress/mcp-adapter`)

== Installation ==

1. Upload the `mcp-manager` folder to `/wp-content/plugins/`.
2. Run `composer install --no-dev` inside the plugin folder.
3. Activate **MCP Manager** from the WordPress Plugins screen.
4. A new **MCP Manager** item appears in the admin sidebar.

== Frequently Asked Questions ==

= Does this plugin expose my site to AI tools automatically? =

No. MCP Manager is read-only — it only displays what other plugins have registered via the Abilities API. Serving those tools over the network requires a separate MCP adapter plugin.

= Which WordPress version do I need? =

WordPress 6.9 or higher. The Abilities API was introduced in 6.9.

= Why do I see "MCP Adapter not detected"? =

The Setup Guide tab requires a separate plugin that serves your registered abilities over SSE (the protocol AI clients use). MCP Manager itself is just the UI layer.

== Changelog ==

= 0.1.0 =
* Initial release.
