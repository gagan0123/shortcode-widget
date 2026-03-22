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

# Grunt tasks
lando npm install              # Install Node dependencies
lando npx grunt                # Run default task (readme + pot generation)
lando npx grunt wp_readme_to_markdown  # Convert readme.txt to README.md
lando npx grunt makepot        # Generate translation template (.pot)
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

**CI matrix:** Tests run across PHP 5.6-8.0 and WordPress 3.8 through nightly on both Travis CI and GitLab CI.

## CI/CD

- **Travis CI** (`.travis.yml`): PHPCS verification + PHPUnit test matrix
- **GitLab CI** (`.gitlab-ci.yml`): PHPCS verification + PHPUnit test matrix + manual deployment to GitLab

Both CI pipelines run a verify stage (PHPCS) before the test stage.

## i18n

- Text domain: `shortcode-widget`, domain path: `/languages`
- POT file generated via `grunt makepot`
- All user-facing strings must be wrapped in WordPress i18n functions (`__()`, `_e()`, `esc_html__()`, etc.)

## Key Conventions

- `README.md` is auto-generated from `readme.txt` — edit `readme.txt` instead
- Plugin follows WordPress widget API patterns (`WP_Widget` subclass)
- No external PHP dependencies; plugin is self-contained
- Node dependencies are dev-only (Grunt for build tasks)
- Git remotes: GitHub (`github`) and GitLab (`origin`)
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