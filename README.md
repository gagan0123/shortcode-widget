# Shortcode Widget

A WordPress plugin that provides a "Shortcode Widget", allowing users to execute shortcodes within a widget area. This plugin bridges the gap in the default text widget by enabling shortcode execution and offering optional paragraph formatting.

**Version**: 1.5.3
**License**: GPLv2 or later
**Tags**: Shortcode, Widget

## Project Overview

The **Shortcode Widget** plugin registers a new widget in WordPress that accepts arbitrary text and shortcodes. Unlike the standard text widget (in older WP versions), this widget processes shortcodes (`do_shortcode`) before rendering. It also provides an option to automatically add paragraphs (`wpautop`), similar to the visual editor.

This repository contains the source code for the plugin, including unit tests and build scripts.

## Tech Stack

- **Language**: PHP (Plugin logic)
- **CMS**: WordPress (Tested with WP 5.6+, supports PHP 8.2 via Lando)
- **Development Environment**: Lando (Docker-based)
- **Testing**: PHPUnit
- **Build Tools**: Node.js, Grunt (for generating `README.md` from `readme.txt` and POT files)

## Project Structure

```
.
├── assets/                 # Icons and screenshots for the WP directory
├── includes/               # Core logic
│   ├── class-shortcode-widget-plugin.php # Plugin singleton, hooks setup
│   └── class-shortcode-widget.php        # The Widget class (WP_Widget)
├── languages/              # Translation files (.pot)
├── tests/                  # PHPUnit tests
├── .lando.yml              # Lando configuration for local dev
├── Gruntfile.js            # Grunt tasks (readme conversion, i18n)
├── package.json            # Node dependencies
├── phpunit.xml.dist        # PHPUnit configuration
├── readme.txt              # WordPress.org plugin repository readme
└── shortcode-widget.php    # Main plugin entry point
```

## Installation & Setup

### For Users (Production)

1. Upload the `shortcode-widget` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Appearance > Widgets**.
4. Drag the **Shortcode Widget** to a sidebar.

### For Developers (Local Setup)

This project uses [Lando](https://lando.dev/) for a consistent local development environment.

**Prerequisites:**
- Docker
- Lando

**Steps:**

1.  Clone the repository:
    ```bash
    git clone https://github.com/gagan0123/shortcode-widget.git
    cd shortcode-widget
    ```

2.  Start the environment:
    ```bash
    lando start
    ```
    This will:
    - Spin up a PHP 8.2 / Apache / MySQL environment.
    - Install WordPress.
    - Install the `twentytwentyone` theme.
    - Link the plugin to the WordPress installation.

3.  Access the site:
    Lando will provide a URL (e.g., `https://shortcode-widget.lndo.site/`).

**Environment Variables:**
The code itself relies on standard WordPress constants (e.g., `ABSPATH`). The local environment uses standard Lando variables (see `.lando.yml`).

## Features & Usage

### Key Features
- **Shortcode Execution**: parses any shortcodes entered in the content area.
- **Content Filtering**: Option to "Automatically add paragraphs" (applies `wpautop`).
- **Unfiltered HTML**: Supports raw HTML if the user has `unfiltered_html` capability.
- **Test Shortcode**: Includes a built-in shortcode `[shortcode_widget_test]` which outputs "It works" for verification.

### Usage
1.  Add the **Shortcode Widget** to a widget area.
2.  **Title**: Enter a title for the widget (optional).
3.  **Content**: Enter text, HTML, or shortcodes (e.g., `[gallery]`, `[contact-form-7]`, or `[shortcode_widget_test]`).
4.  **Automatically add paragraphs**: Check this box to convert double line breaks to HTML paragraphs.

## Development Workflow

### Running Tests
Unit tests are written using PHPUnit and the WordPress Test Suite.

To run tests inside Lando:
```bash
# Verify the test setup (usually handled by Lando recipe or needs manual bootstrap)
# Assuming standard PHPUnit execution:
lando phpunit
```

### Build Tasks
Grunt is used for maintenance tasks.

1.  Install dependencies:
    ```bash
    lando npm install
    ```

2.  Run default tasks (Updates `README.md` from `readme.txt` and generates `.pot` file):
    ```bash
    lando grunt
    ```

*Note: The `README.md` in the root is technically a build artifact generated from `readme.txt`. However, for GitHub viewers, this document serves as the primary source of truth for the codebase structure and development.*
