# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
Versions are recorded here and in `didJavaScriptFunctionRun/VERSION`. The
project does not publish GitHub releases or tags.

## [Unreleased]

## [1.1.0] - 2026-10-29

Security release. Every installation of 1.0.0 should replace its
`didJavaScriptFunctionRun` folder with this version. The pages that use the
template need no change.

### Security

- Fixed command injection in `scripts/make_random_file.php`,
  `scripts/delete_random_file.php` and
  `scripts/did_java_script_function_run.php`, where request input could reach
  shell commands. Every shell command is removed, and both endpoints now
  refuse anything other than the 8 random letters the browser script
  generates.
- Fixed reflected cross-site scripting through the `didJavaScriptFunctionRunID`
  field. `$didJavaScriptFunctionRunID` is now validated when it is set, so
  pages that echo it unescaped (install step 4) are safe without being edited.
- Removed the generation and inclusion of a PHP file built from request input.
- Removed a loop that held a PHP worker for about five minutes on every page
  view, which let a few dozen requests exhaust a server's PHP workers.
- If 1.0.0 ran on a publicly reachable server, check that server for files
  you did not create.

### Added

- `didJavaScriptFunctionRun/VERSION`, holding the installed version. A folder
  without it is 1.0.0.
- `make_random_file.php` and `delete_random_file.php` send
  `Cache-Control: no-store` and `X-Content-Type-Options: nosniff`.

### Changed

- `clean_random_character_function()` validates instead of cleaning. It
  returns the trimmed value when it holds only letters, digits and
  underscores (up to 160 characters), and an empty string otherwise.
- Random folders in `tmp/` expire after 30 minutes instead of being kept for
  up to a day, and are removed by PHP instead of by `find` and `rm`. The
  cleanup keeps `README.md` and editor metadata folders named `_notes`.
- The curl user-agent check now also runs in `didJavaScriptFunctionRun.php`,
  matches case-insensitively, and reads the user agent from `$_SERVER`.
- README step 4, the example form, and the demonstration page escape
  `$didJavaScriptFunctionRunID` when they echo it.

### Fixed

- A fatal `TypeError` on PHP 8 in `didJavaScriptFunctionRun.php`, caused by
  calling `count()` on a string.
- Undefined variable warnings in `didJavaScriptFunctionRun.php` on `GET`
  requests, and when the page sets no required field name.
- `didJavaScriptFunctionRun.php` looked for random files relative to the
  page's working directory instead of its own folder.
- `make_random_file.php` treated the request path as the random characters
  when the URL had no query string.
- The scripts no longer depend on POSIX tools, so they can run on Windows
  servers.

### Removed

- `scripts/template_check.php`.

## [1.0.0] - 2024-09-04

Initial published template.

### Added

- Six-step install for PHP forms that post to their own page: a browser
  script, a hidden `didJavaScriptFunctionRunID` field, and PHP includes that
  set `$didJavaScriptFunctionRun` to `1` when JavaScript ran in the browser
  that submitted the form, and `0` otherwise.
- File-based check: the browser asks `scripts/make_random_file.php` to create
  a random folder in `tmp/`, and `scripts/did_java_script_function_run.php`
  verifies it when the form is submitted.
- Example form (`example/feedback.php`) and demonstration page (`index.html`).
