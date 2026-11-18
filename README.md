# Zumika Social Wall for Concrete CMS

[![SystemTests](https://github.com/limegreentangerine/ZumikaSocialWall/actions/workflows/SystemTests.yml/badge.svg)](https://github.com/limegreentangerine/ZumikaSocialWall/actions/workflows/SystemTests.yml)

[![CodeStandards](https://github.com/limegreentangerine/ZumikaSocialWall/actions/workflows/CodeStandards.yml/badge.svg)](https://github.com/limegreentangerine/ZumikaSocialWall/actions/workflows/CodeStandards.yml)

Zumika Social Wall is a Concrete CMS package that adds a block for showing a [Zumika](https://zumika.app) social wall on a page, plus a dashboard page that checks the site's connection to Zumika.

## Requirements

- Concrete CMS 9.5 or later
- PHP 8.4 or later
- The `class_kit` Concrete CMS package (required by this package)
- A Zumika API key
- The site must load its root `.env` file into the environment (the LGT site skeleton does this in `application/bootstrap/autoload.php` with Symfony Dotenv)

## Installation

Install the package's Composer dependencies, including `limegreentangerine/class_kit`, using the Composer repository configured for your project. Install the package in Concrete CMS using your site's normal package deployment process. Its Concrete package handle is `zumika_social_wall`.

## Configuration

The settings live in the site's `.env` file, not in the database or Concrete's file config, so the API key never ends up in `application/config`. See `.env.example`:

| Variable         | Required | Description                                                                                                             |
| ---------------- | -------- | ----------------------------------------------------------------------------------------------------------------------- |
| `ZUMIKA_API_KEY` | Yes      | Create a key in your Zumika workspace under **API & plugins**.                                                          |
| `ZUMIKA_URL`     | No       | The Zumika address. Defaults to `https://zumika.app`. Must be a plain `http(s)://host[:port][/path]`, or it is ignored. |

The values are read from `$_ENV`, `$_SERVER`, or `getenv()`, in that order, so they can also be set as real server environment variables.

Open **Dashboard > Zumika > Settings** to see which settings are populated. When a key is set, the page also tests it and lists the workspace's feeds. The page is read-only; change the values in `.env`.

## Social wall block

Add the **Zumika Social Wall** block to a page and choose:

- A feed from your Zumika workspace
- A layout (waterfall, grid, carousel, portrait carousel, large list or compact list), or the layout designed in Zumika
- How many posts to load at a time (0 uses the number set in Zumika)

The block outputs a placeholder `<div>` and loads Zumika's `embed.js` once per page, which renders the wall in the browser. The API key is only used in the dashboard and block editor; it is never sent to site visitors.

## Upgrading from 1.x

Version 2.0.0 stops storing settings in Concrete's config. After upgrading:

1. Copy the API key into `.env` as `ZUMIKA_API_KEY` (and `ZUMIKA_URL` if you had changed the address).
2. The old values in `application/config/generated_overrides/zumika_social_wall/api.php` are no longer read. Delete that file once the key is in `.env`.

The old **System & Settings > Zumika** page is removed during the upgrade and replaced by **Dashboard > Zumika > Settings**. Existing blocks keep their settings.

## Development

Install the development dependencies, then run:

```bash
composer test
composer check
composer format:check
```

`composer test` runs the PHPUnit suite. `composer check` runs PHPStan and prints findings; the configured Composer script does not fail when PHPStan reports findings.

## License

This package is proprietary. See [LICENSE](LICENSE) for the terms.
