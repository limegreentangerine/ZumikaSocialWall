# Changelog

All notable changes to Zumika Social Wall are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and
the project uses [Semantic Versioning](https://semver.org/).

## [2.0.0] - 2026-11-16

### Changed

- The API key and Zumika address are read from the site's `.env` file
  (`ZUMIKA_API_KEY`, `ZUMIKA_URL`) instead of Concrete's file config. Move the
  key into `.env` after upgrading; see the README.
- Settings moved from **System & Settings > Zumika** to a read-only
  **Dashboard > Zumika > Settings** page that reports which settings are
  populated and tests the connection. The old page is removed on upgrade.
- Rebuilt on the LGT package structure: the controller extends ClassKit's
  `PackageController`, and the API client extends ClassKit's
  `ConnectionController` (Guzzle) instead of using raw cURL.
- Namespace changed from `Zumika\` to `ZumikaSocialWall\`.
- The block now installs into the package's own block set.
- Requires Concrete CMS 9.5, PHP 8.4, and the `class_kit` package.

### Fixed

- The Zumika `embed.js` script now loads on pages whose block output is served
  from cache. It was added in `view()`, which Concrete skips for cached output.

### Security

- `ZUMIKA_URL` must be a plain `http(s)` address before it is used in the
  `embed.js` `<script>` tag or API requests; anything else falls back to the
  default address.
- The API key is no longer stored in `application/config`, and the dashboard
  never displays it.

### Added

- Zumika logger (channel `zumika`) for failed API requests.
- Package icon, README, `.env.example`, PHPUnit tests, PHPStan, php-cs-fixer,
  Prettier, and GitHub Actions workflows.

## [1.0.0]

### Added

- Initial release: Zumika Social Wall block and a System & Settings page for
  the API key.
