# Shortcode Widget #
[![build status](https://travis-ci.com/gagan0123/shortcode-widget.svg?branch=master)](https://travis-ci.com/gagan0123/shortcode-widget) [![pipeline status](https://gitlab.com/gagan0123/shortcode-widget/badges/master/pipeline.svg)](https://gitlab.com/gagan0123/shortcode-widget/commits/master) [![coverage report](https://gitlab.com/gagan0123/shortcode-widget/badges/master/coverage.svg)](https://gitlab.com/gagan0123/shortcode-widget/commits/master)
<img src='https://github.com/gagan0123/shortcode-widget/raw/master/assets/icon-128x128.png' align='right' />

**Contributors:** [gagan0123](https://profiles.wordpress.org/gagan0123)
**Donate Link:** https://PayPal.me/gagan0123
**Tags:** Shortcode, Widget
**Requires at least:** 3.3
**Requires PHP:** 5.6
**Tested up to:** 5.6
**Stable tag:** 1.5.3
**License:** GPLv2 or later
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

Adds a text-like widget that allows you to write shortcode in it.

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

### Standard Installation
1. Add the plugin's folder in the WordPress' plugin directory.
1. Activate the plugin.
1. You are now ready to use the Shortcode Widget from the Widgets section.
1. To test the widget you can add the widget and use the shortcode "[shortcode_widget_test]", it will display "It works" on the frontend and this will confirm the widget does work.

### Developer Setup (Lando)

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

## Features & Usage

- **Shortcode Execution**: parses any shortcodes entered in the content area.
- **Content Filtering**: Option to "Automatically add paragraphs" (applies `wpautop`).
- **Unfiltered HTML**: Supports raw HTML if the user has `unfiltered_html` capability.
- **Test Shortcode**: Includes a built-in shortcode `[shortcode_widget_test]` which outputs "It works" for verification.

### Usage
1.  Add the **Shortcode Widget** to a widget area.
2.  **Title**: Enter a title for the widget (optional).
3.  **Content**: Enter text, HTML, or shortcodes (e.g., `[gallery]`, `[contact-form-7]`, or `[shortcode_widget_test]`).
4.  **Automatically add paragraphs**: Check this box to convert double line breaks to HTML paragraphs.

## Screenshots
### 1. Shortcode Widget that can be found in Widgets section
![Shortcode Widget that can be found in Widgets section](https://github.com/gagan0123/shortcode-widget/raw/master/assets/screenshot-1.png)

### 2. Adding the widget to the sidebar
![Adding the widget to the sidebar](https://github.com/gagan0123/shortcode-widget/raw/master/assets/screenshot-2.png)

### 3. Widget with the output of the shortcode
![Widget with the output of the shortcode](https://github.com/gagan0123/shortcode-widget/raw/master/assets/screenshot-3.png)

## Changelog

### 1.5.3
* Strict PHPCS ruleset adherence.
* More documentation in widget class.
* Testing with WordPress 5.6

### 1.5.2
* Some PHPCS corrections, making code adhering to WordPress coding standards.
* Replaced strip_tags function with wp_strip_all_tags.

### 1.5.1
* Unescaped title back in the code as escaping it was creating issues with other plugins.

### 1.5
* Added icon and screenshots.
* Escaping some values that could have been overridden by the translations.
* Added pot file for translations.
* Change in calling of widget_text filter with new parameter that was added in WordPress 4.4.1

### 1.4
* Updated compatibility with WordPress 4.8
* Reversed the order of changelog.

### 1.3
* Minor bug fix.
* Changed tested up to version number.
* Made it translation ready, constant was being used for text domains, silly error, I know :)

### 1.2
* Corrections in text domain and added one more string as translatable.

### 1.1
* Reflecting the changes that have been done to the default text widget over the years.

### 1.0
* Tested with WP 4.0

### 0.3
* Added a shortcode for testing the plugin '[shortcode_widget_test]'

### 0.2
* Added translation support.

### 0.1
* Added the shortcode widget.
