# AGENTS.md

## Project Overview

Shortcode Widget is a WordPress plugin that adds a text-like widget allowing users to write shortcodes in it. The widget supports shortcode execution via `do_shortcode()`, optional automatic paragraph formatting, and respects WordPress user capabilities for HTML filtering.

- **Version:** 1.5.3
- **Minimum WordPress:** 3.3
- **Text Domain:** `shortcode-widget`
- **License:** GPLv2

## Project Structure

```
shortcode-widget.php    # Main plugin entry point
includes/               # Core plugin classes
  class-shortcode-widget-plugin.php  # Plugin setup, hooks, text domain loading
  class-shortcode-widget.php         # Widget class (extends WP_Widget)
tests/                  # PHPUnit test suite
  bootstrap.php         # Test environment setup
  test-shortcode-widget.php  # Widget test cases
languages/              # i18n translation files (.pot)
assets/                 # Plugin icons and screenshots
bin/                    # Build and deployment scripts
.lando/                 # Lando local dev config (php.ini, mysql.cnf, xdebug.sh, wp-cli.yml)
```

## Local Development

This project uses [Lando](https://lando.dev/) for local development.

```bash
lando start          # Start the environment
lando rebuild -y     # Rebuild (runs build steps including WPCS install)
```

**WordPress admin:** https://shortcode-widget.lndo.site/wp-admin/ (`admin` / `password`)

## Build & Tooling Commands

```bash
# Linting
lando phpcs .                  # Run PHPCS against the codebase
lando phpcbf .                 # Auto-fix coding standard violations

# Or via Composer (used in CI)
composer install               # Install dev dependencies (WPCS, PHPUnit polyfills)
vendor/bin/phpcs               # Run PHPCS

# i18n
wp i18n make-pot . languages/shortcode-widget.pot --slug=shortcode-widget  # Generate .pot file
```

## Coding Standards

- WordPress Coding Standards (WPCS 3.0) enforced via PHPCS
- Configuration in `.phpcs.xml.dist`
- Text domain: `shortcode-widget`
- Class prefix: `Shortcode_Widget`
- All PHP code must pass `lando phpcs .` before committing

## Testing

PHPUnit tests live in `tests/`. Configuration is in `phpunit.xml.dist`.

```bash
# Install test environment (run once)
bash bin/install-wp-tests.sh wordpress_test root '' localhost latest

# Run tests
phpunit
```

**CI matrix:** Tests run across PHP 7.4-8.3 and WordPress 6.0 through nightly on GitHub Actions.

## CI/CD

- **GitHub Actions** (`.github/workflows/ci.yml`): PHPCS verification + PHPUnit test matrix (PHP 7.4-8.3 × WP latest/6.5/6.0/nightly)
- **GitHub Actions** (`.github/workflows/deploy.yml`): Automatic deployment to WordPress.org SVN on tag push (uses `10up/action-wordpress-plugin-deploy`)
- **Dependabot** (`.github/dependabot.yml`): Keeps GitHub Actions versions up to date

The CI workflow runs PHPCS first, then PHPUnit across the matrix (including multisite).

## i18n

- Text domain: `shortcode-widget`, domain path: `/languages`
- POT file generated via `wp i18n make-pot`
- All user-facing strings must be wrapped in WordPress i18n functions (`__()`, `_e()`, `esc_html__()`, etc.)

## Key Conventions

- `README.md` badges and header are maintained manually; content mirrors `readme.txt`
- Plugin follows WordPress widget API patterns (`WP_Widget` subclass)
- No external PHP dependencies; plugin is self-contained
- Composer dev dependencies are for CI only (WPCS, PHPUnit polyfills)
- Git remote: GitHub (`origin`)
- Main branch: `master`

## Git Commit Guidelines
- **No Co-Authored-By:** Do not add `Co-Authored-By` trailers or otherwise credit yourself in commit messages.
- **Conventional Commits:** All commit messages must follow the Conventional Commits specification. The format is:
  ```
  <type>[optional scope]: <description>

  [optional body]

  [optional footer(s)]
  ```
- **Types:**
  - `feat` — a new feature (correlates with a MINOR version bump)
  - `fix` — a bug fix (correlates with a PATCH version bump)
  - `docs` — documentation-only changes
  - `style` — formatting, whitespace, etc. (no code logic change)
  - `refactor` — code restructuring without changing behavior
  - `perf` — performance improvements
  - `test` — adding or updating tests
  - `build` — changes to build system or dependencies
  - `ci` — CI/CD configuration changes
  - `chore` — other maintenance tasks
- **Scope:** An optional noun in parentheses after the type describing the section of the codebase affected (e.g., `feat(camera):`, `fix(ui):`).
- **Description:** A short imperative summary immediately after the colon and space.
- **Body:** Optional. Separated from the description by a blank line. Provides additional context or motivation.
- **Footer(s):** Optional. Separated from the body by a blank line. Use git trailer format (`token: value` or `token #value`).
- **Breaking Changes:** Append `!` after the type/scope (e.g., `feat!:` or `refactor(api)!:`) and/or add a `BREAKING CHANGE:` footer. Breaking changes correlate with a MAJOR version bump.