# WP MCP Manager

A WordPress admin plugin that surfaces the [WordPress Abilities API](https://developer.wordpress.org/apis/abilities/) (introduced in WP 6.9) so site owners can discover and connect their AI tools.

![WP MCP Manager screenshot](https://github.com/user-attachments/assets/placeholder)

## The problem

WordPress 6.9 added a powerful Abilities API that lets plugins register tools for AI assistants — but there's no admin UI for it. Users have no way to:

- Know their site has an MCP endpoint
- See which tools are registered and by which plugin
- Find the URL to paste into Claude Desktop / Cursor / VS Code
- Get working connection configs for their AI client

This plugin fills that gap.

## What it does

**Tools tab** — lists every registered ability grouped by plugin namespace, with label, description, category, and behavioral annotations (readonly, destructive, idempotent).

**Categories tab** — shows all registered ability categories with their slugs and descriptions.

**Setup Guide tab** — copy-paste JSON configs for Claude Desktop, Cursor, VS Code (GitHub Copilot), and Zed, pre-filled with your site's MCP endpoint URL.

## Requirements

- WordPress 6.9+
- PHP 8.0+
- Composer

## Installation

```bash
git clone https://github.com/Miraeld/wp-mcp-manager.git
cd wp-mcp-manager
composer install --no-dev
```

Then activate **WP MCP Manager** from the WordPress Plugins screen. A new **MCP Manager** item appears in the admin sidebar.

## Architecture

Built with [League Container](https://container.thephpleague.com/) following the same patterns as production WP Media plugins:

```
src/
├── Plugin/Plugin.php                    # bootstraps container, loads subscribers
├── ServiceProvider/AbstractServiceProvider.php
├── Abilities/
│   ├── AbilityReader.php                # reads wp_get_abilities(), groups by namespace
│   └── ServiceProvider.php
└── Admin/
    ├── Subscriber.php                   # registers admin_menu hook
    ├── ServiceProvider.php
    └── Page/McpManagerPage.php
```

Each module is a service provider that declares what it provides and which subscribers to activate. Subscribers call `add_action()`/`add_filter()` in their `register()` method.

## The bigger picture

This plugin is a proof of concept for what WordPress core doesn't yet ship: a user-facing dashboard for the MCP ecosystem on your site.

The Abilities API handles the **what** (which tools exist). A separate [MCP Adapter](https://github.com/WordPress/wordpress-develop) handles the **how** (serving those tools over SSE so AI clients can connect). This plugin handles the **who** (showing users what's there and how to connect).

Think of it like the REST API screen in WP-CLI, but for AI assistants.

## Status

Proof of concept — built against WordPress 6.9 / 7.0 alpha. The Abilities API is marked `@since 6.9.0` in core. MCP adapter integration depends on `wordpress/mcp-adapter` being installed separately.

## License

GPL-2.0-or-later
