# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

WhyJustRun Clubsite is a PHP web application for orienteering clubs, built on CakePHP 2.x (PHP 5.6). It provides event management, results tracking, maps, user management, and club administration. The app runs in Docker behind nginx + php-fpm.

## Development Setup

Depends on [WhyJustRun Core](https://github.com/WhyJustRun/Core) running first.

```bash
cp .env.clubsite{.sample,}
docker compose up --build
```

App runs at http://localhost:3001.

### CakePHP Console

```bash
docker compose exec clubsite /application/app/Console/cake -app /application/app
```

### Production Asset Minification

```bash
docker compose exec clubsite /application/app/Console/minify.sh
```

Set `WJR_CLUBSITE_ENV=production` for production mode.

## Architecture

### CakePHP 2.x MVC Structure

All application code lives under `src/app/`:

- **Controllers** (`Controller/`): Standard CakePHP controllers. `AppController` sets up auth (custom `WhyJustRunAuthenticate`), CSRF protection, per-club privilege checking, and dynamic layout selection based on club config.
- **Models** (`Model/`): CakePHP models backed by MySQL. Key domain entities: Event, Club, Course, Map, Result, User, Series.
- **Views** (`View/`): `.ctp` template files organized by controller. Layouts in `View/Layouts/` — `default.ctp` is the main layout, `embed.ctp` for embeddable widgets. Custom helpers for menus, content blocks, media, geocoding, time formatting, and user display.
- **Components** (`Controller/Component/`): Custom auth via `WhyJustRunAuthenticate` (cross-app session-based SSO with the Rails Core app), plus Facebook integration, media handling, and Juicer social feed.

### Multi-Club / Multi-Layout System

The app serves multiple orienteering clubs from a single codebase. Club identity is determined by configuration (`Configure::read('Club.id')`), and layouts/privileges are scoped per-club. The layout is dynamically selected based on the club's `layout` config value.

### Authentication

Authentication is handled via cross-app sessions shared with the WhyJustRun Core Rails app. The `WhyJustRunAuthenticate` component looks up users by `cross_app_session_id` in a shared database table. There is no standalone login form in this app — login flows through the Core app.

### Frontend

Bootstrap 2/3-based UI with jQuery, Knockout.js, and various jQuery plugins. JavaScript is in `src/app/webroot/js/`, CSS in `src/app/webroot/css/`. RequireJS is used for JS module loading (`build.js` config). Production uses clean-css and r.js for minification.

### Docker Setup

- `php-fpm/`: PHP 5.6 container with ImageMagick, Node.js (for asset minification)
- `nginx/`: Serves static files and proxies PHP to php-fpm
- Source mounted at `/application/` inside containers

### Database

MySQL, configured via environment variables (`WJR_DATABASE_*`). Connection defined in `src/app/Config/database.php`.

### Supported Response Formats

Routes support `.json`, `.xml`, and `.embed` extensions (`Router::parseExtensions`).
