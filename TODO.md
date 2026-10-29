# didJavaScriptFunctionRun TODO

Road map for a complete renovation of didJavaScriptFunctionRun, the PHP and
JavaScript template that tells a form handler whether the page that submitted
the form ran JavaScript.

Version 1.0.0, as published, does its work by having the browser ask the
server to create files in a public `tmp/` folder, through shell commands built
from request input. Almost every serious defect in the project traces back to
that one design: unauthenticated command execution, a reflected cross-site
scripting hole, a five-minute worker hold on every page view, a fatal error on
PHP 8, and a check that any HTTP client can pass without running JavaScript.
This file plans the work that replaces the design while keeping every existing
install working.

> IMPORTANT - **the compatibility rule.** Every change in the 1.x line must
> leave an existing install working after exactly one action: replacing the
> `didJavaScriptFunctionRun/` folder with the new one. No edit to the host
> page, no new install step, no new writable folder. The names this rule
> protects are listed in **Compatibility Contract**. A change that would break
> one of them belongs in **Out of Scope Until 2.0.0**, not in any 1.x version.
>
> IMPORTANT - **every run leaves a shippable tree.** Any run can bump the
> version and push, and whatever `main` holds is what the README's install
> one-liner downloads, so no item may leave the folder half switched over.
> Where two changes only work together, such as the token issuer and the
> token verifier, they are written as one item rather than two.
>
> NOTE - the version plan. 1.1.0 was the security hotfix, shipped as a minor
> version by the owner's choice. 1.2.0 replaces the file-based check with
> signed tokens and rewrites the browser scripts. 1.3.0 adds a shorter install
> and a configuration file. Hardening, tests, examples
> and documentation ship as patches or carry no version. 2.0.0, the version
> allowed to drop the legacy names, is not planned.
>
> NOTE - **versions without releases.** Versions are semantic and recorded in
> two places only: `CHANGELOG.md`, and the `didJavaScriptFunctionRun/VERSION`
> file that travels with every install. This project publishes no GitHub
> releases and creates no tags. Work that would need either is out of scope.

## Current

- [ ] Add a dependency-free security regression script that proves hostile ids are refused
  - From: Security Hotfix
  - **Goal**: `tests/security-regression.php`, run with
    `php tests/security-regression.php`, starts PHP's built-in server
    (`php -S 127.0.0.1:<free port>`) over a temporary document root holding
    a copy of the folder and a page built from the 1.0.0 host-page snippets.
    It sends a corpus of hostile ids to `make_random_file.php`,
    `delete_random_file.php` and the step 6 page: shell separators, command
    substitution, path traversal, encoded newlines and null bytes, over-long
    input, digits, spaces and non-ASCII. Every payload that would run a
    command tries to create a marker file outside the document root. It also
    covers the cases that verified 1.1.0 by hand: a replayed id, a `"><script>`
    value, an array-shaped field, a curl user agent, and the 30-minute prune
    keeping `README.md`.
  - **Done when**: every payload gets `400` (or `$didJavaScriptFunctionRun = 0`),
    the marker file never appears, nothing is created outside `tmp/`, and
    the script exits non-zero on any failure, so CI can call it later
    unchanged.
- [ ] Stop directory listings of `tmp/` while keeping `tmp/<id>/file.txt` readable
  - From: Security Hotfix
  - **Issue**: The browser script fetches `tmp/<id>/file.txt` over HTTP, so
    the folder has to be public. On servers with directory indexes on,
    `/didJavaScriptFunctionRun/tmp/` lists every live id.
  - **Goal**: an empty `tmp/index.html`, and a `tmp/.htaccess` holding
    `Options -Indexes` inside an `<IfModule>` guard. The prune added in 1.1.0
    already skips both names.
- [ ] Cap the number of live entries in `tmp/`
  - From: Security Hotfix
  - **Issue**: Every request with a fresh 8-letter id still creates a
    folder. The 30-minute prune bounds how long entries live, not how many
    there are, and it walks the whole folder on every request.
  - **Goal**: above 2,000 live entries, prune first. If the count is still
    over the cap, answer `503` with `Retry-After: 60` instead of creating
    another entry. The cap is a constant here and becomes the `tmp_cap`
    option in **PHP API and Configuration**.
- [ ] Stop logging the id to the browser console in `deleteTmpDidJavaScriptFunctionRun.js`
  - From: Security Hotfix
  - **Issue**: Line 51 calls `console.log(theDeleteQuery)` on every unload,
    whether or not `didJavaScriptFunctionRun_DEBUG` is set.
  - **Goal**: Log only when `didJavaScriptFunctionRun_DEBUG == 1`, matching
    the main script.
- [ ] Add a dependency-free test runner and the first unit tests
  - From: Testing and CI
  - **Goal**: `tests/run.php` finds `tests/*Test.php` files, runs every
    function whose name starts with `test_`, prints one line per test, and
    exits non-zero on any failure. A small `assert_same()` and
    `assert_true()` pair is enough. This avoids pinning PHPUnit, whose
    current releases do not run on the PHP 7.4 floor.
  - **First tests**: `clean_random_character_function()` across the
    allowlist boundary (including non-string input), the agent check (curl,
    mixed case, absent header), and variable initialisation in
    `didJavaScriptFunctionRun.php` on `GET` and `POST`.

## Renovation Goals

What "renovated" means for this project, so that every item below is judged
against the same finish line. Reference only: this section holds no items and
carries no version.

**Goals**

- No shell, no `eval`, no generated code, and no `include` of any path
  derived from a request.
- A token the server issues and signs, in place of a file the client asks
  the server to create.
- Bounded work per request: no `sleep()`, no unbounded loops, no unbounded
  disk growth.
- Runs on PHP 7.4 through the newest 8.x, on Linux and Windows servers,
  under Apache, nginx and IIS.
- Zero runtime dependencies. Copying one folder remains the entire install.
- Honest documentation: this is a low-friction signal that JavaScript ran.
  It is not a CAPTCHA, not CSRF protection, and not proof of a human.

**Non-goals**

- Stopping headless browsers, or scripted clients written specifically for
  this template. Both can run the script or call the endpoints directly. The
  optional proof-of-work in **PHP API and Configuration** raises their cost;
  nothing in 1.x claims to stop them.
- Publishing a Composer package in 1.x. A package changes how the folder is
  installed, which the compatibility rule forbids.
- Supporting forms that post to a different page from the one that renders
  them. The legacy install assumes `action` is the same page, and so does
  1.x.

**Definition of done**

- Every item in **Security Hotfix**, **Testing and CI**, **Token Engine**,
  **Browser Script Rewrite** and **Server Hardening** is archived.
- The legacy compatibility fixture passes on every CI target with the 1.0.0
  host-page snippets copied in verbatim.
- `README.md` is under 300 lines and its claims match the code.
- Version 1.2.0 or later is recorded in `CHANGELOG.md` and in
  `didJavaScriptFunctionRun/VERSION`, with an upgrade note in
  `docs/upgrading.md`. No GitHub release and no tag is part of done.

## Compatibility Contract

Everything a 1.0.0 host page or browser touches, and what each name must
still do through every 1.x version. Reference only: this section holds no
items and carries no version. Internals not listed here, such as
`template_check.php`, the localStorage key, and the inside of every PHP file,
are free to change.

| Name | Kind | Touched by | Promise through 1.x |
| --- | --- | --- | --- |
| `didJavaScriptFunctionRun/` at the document root | Folder | Install step 1 | Same name and location; every path below stays relative to it |
| `didJavaScriptFunctionRun/` at the repository root | Repository layout | The README's `curl` and `tar` one-liner | Stays at the root, so the one-liner's `--strip=2` path keeps working |
| `tmp/` writable by the web server | Permission | Install | Still the only folder that must be writable. New private state lives inside it |
| `didJavaScriptFunctionRun/VERSION` | File | Site owners checking what they run | Present from 1.1.0 on, one line holding the bare version; an install without it is 1.0.0 |
| `/didJavaScriptFunctionRun/didJavaScriptFunctionRun.js` | Script URL | Step 2 | Same URL; defines `didJavaScriptFunctionRun()` |
| `$name_of_required_form_field`, `$did_javascript_function_run_path` | PHP variables the page sets | Steps 3 and 6 | Still read, same meaning |
| `didJavaScriptFunctionRun.php` | Include | Step 3 | Same path; sends no headers and no output |
| `scripts/clean_random_character_function.php`, `clean_random_character_function()` | Require, function | Step 3 | File and function remain; the function returns an allowlisted value or `''` |
| `$didJavaScriptFunctionRunID` | PHP variable the page echoes | Step 4 | Always defined, always safe inside an HTML attribute |
| `id="didJavaScriptFunctionRunID"`, `name="didJavaScriptFunctionRunID"` | Field | Step 4 | Same id and name; posting nothing still means JavaScript did not run |
| `didJavaScriptRun`, `didJavaScriptFunctionRunCheck_randomCharacters` | sessionStorage keys | Step 4 inline script | Read and written as before; the library overwrites the copied value with a fresh token |
| `id="submitButtonDidJavaScriptRun"` | Element id | Steps 4b and 6 | Same |
| `/didJavaScriptFunctionRun/scripts/deleteTmpDidJavaScriptFunctionRun.js` | Script URL | Step 5 | Same URL; defines `checkFormSubmittedDidJavaScriptFunctionRun()` and `cleanTmpDidJavaScriptFunctionRun()` |
| `scripts/did_java_script_function_run.php` | Require | Step 6 | Same path; sets `$didJavaScriptFunctionRun` |
| `$didJavaScriptFunctionRun` | PHP variable the page tests | Step 6 | Integer `1` only for a verified submission, `0` otherwise |
| `formSubmittedDidJavaScriptFunctionRun` | sessionStorage key | Step 6 echo | Same |
| `$name_form_input`, `$check_action`, `$user_agent_did_javascript_function_run` | PHP variables the include sets | Undocumented, but visible to the page | Remain defined with the same meaning |
| `scripts/make_random_file.php`, `scripts/delete_random_file.php`, `tmp/<id>/file.txt` | HTTP endpoints | The shipped scripts only | Keep answering through 1.x, so a browser still holding a cached pre-token script (1.0.0 to 1.1.x) keeps working (see **Token Engine `->` Legacy Bridge**) |

Two rules follow from the table:

- **Fix at the library, never at the page.** When a defect is visible in a
  host page, such as the unescaped echo in step 4, the fix goes where the
  value is produced. Changing the README snippet as well is welcome, but
  never sufficient, because existing pages keep the old snippet.
- **A name that is no longer needed becomes a shim, not a deletion.** It
  keeps answering with the cheapest behaviour that is still correct, and its
  removal is listed in **Out of Scope Until 2.0.0**.

## Target Architecture

Where the renovation ends up, so that items in different sections build
toward the same shape. Reference only: this section holds no items and
carries no version.

**Layout after 1.3.0**

```text
didJavaScriptFunctionRun/
  .htaccess                          Apache: deny private paths, no listings, no-cache scripts
  VERSION                            The installed version, one line, bumped with CHANGELOG.md
  bootstrap.php                      1.3.0 single include for new installs
  config.sample.php                  Every option, documented; copy to config.php to change one
  didJavaScriptFunctionRun.js        Step 2 script, rewritten
  didJavaScriptFunctionRun.php       Step 3 include, now a thin wrapper
  lib/
    djfr.php                         All server logic, functions prefixed djfr_
    index.php                        Answers 404
  scripts/
    make_random_file.php             Token issuer, plus the legacy bridge
    did_java_script_function_run.php Step 6 verifier wrapper
    delete_random_file.php           Legacy bridge cleanup
    check_header_did_javascript_function_run.php   Thin wrapper
    clean_random_character_function.php            Thin wrapper
    deleteTmpDidJavaScriptFunctionRun.js           Step 5 shim
  tmp/
    README.md
    index.html                       Blocks listings where .htaccess is ignored
    state/                           Secret and replay store, denied to the web
```

**Request flow from 1.2.0**

1. The page is requested. The step 3 include initialises its variables. It
   sends no header and starts no session, because it runs after the host
   page has started its output. Step 4 renders the field `disabled`.
2. The browser runs the step 2 script. The script enables the field,
   requests `scripts/make_random_file.php`, and writes the returned token
   into the field.
3. The form is submitted. With JavaScript, the field posts the token.
   Without JavaScript, the field is still disabled and is not posted at all,
   which is the original 1.0.0 signal and still the cleanest one.
4. Step 6 verifies the token's format, signature, time window and single
   use, then sets `$didJavaScriptFunctionRun`.

**Token format**: `v2_<iat>_<exp>_<nonce>_<sig>`

| Part | Meaning |
| --- | --- |
| `v2` | Format version; pre-token ids have no prefix |
| `iat` | Unix time the page first got a token. Kept unchanged when a token is renewed, so renewal cannot reset the time trap |
| `exp` | Unix time this token stops being accepted |
| `nonce` | 32 hex characters from `random_bytes(16)`; recorded once used |
| `sig` | 64 hex characters, `hash_hmac('sha256', "v2\|iat\|exp\|nonce", $secret)`, compared with `hash_equals()`. The signed string joins the parts with pipes |

**Design decisions, and why**

- **No `$_SESSION`.** Step 3 runs mid-page in every 1.0.0 install, after
  output has started, so `session_start()` there cannot send its cookie.
  Session behaviour also varies more across shared hosts than anything else
  this template touches.
- **Signed rather than stored tokens.** An HMAC token needs no server state
  per page view. The only thing stored is a used nonce, and only after a
  successful submission, so disk use is bounded by real submissions and not
  by page views.
- **Private state under `tmp/state/`.** `tmp/` is the one folder every
  working 1.x install has already made writable. A new `data/` folder
  would quietly break the compatibility rule on any host where a freshly
  copied folder is not writable.
- **Token alphabet `[0-9a-f_v]`.** It passes `clean_random_character_function()`
  and `htmlspecialchars()` unchanged, so legacy code paths that clean or echo
  the value cannot corrupt it.
- **Fail closed by default.** If no secret can be read or created, the
  issuer answers `503`, the verifier reports `0`, and the reason goes to
  `error_log()`. A configuration option can switch this to fail open for
  sites that would rather take spam than lose messages.
- **PHP 7.4 syntax.** No `match`, no union types, no `str_contains()`, so
  that hosts still on 7.4 keep working. The floor is revisited only with
  2.0.0.

## Security Hotfix

Close the holes in 1.0.0 without changing how the template works: same
files, same endpoints, same file-based check, minus the shell, the generated
code, the unescaped echo and the worker hold. The core of it shipped as
1.1.0, a minor version by the owner's choice; the finished items are in
`## Complete`. What remains finishes the job: proof that the fix holds, and
the smaller exposures the design leaves.

Next version: patch.

## Testing and CI

Tests that pin down how the template behaves today before **Token Engine**
replaces its insides, plus the CI that runs them on every push. Tests come
before the rewrite on purpose: the compatibility fixture is how the rewrite
proves it kept the contract. The runtime stays dependency-free, so the test
tooling does too, apart from one optional browser suite.

Next version: patch.

- [ ] Add the legacy compatibility fixture, the test that enforces the Compatibility Contract
  - **Goal**: `tests/fixtures/legacy-page.php` holds the 1.0.0 host-page
    snippets from README steps 3, 4, 5 and 6, copied verbatim and never
    edited afterwards, around a minimal form. An integration test serves it
    from the built-in server, makes the same requests the shipped script
    makes, posts the form, and asserts `$didJavaScriptFunctionRun` is `1`.
    It then posts without the field and asserts `0`, and checks that the
    page logs no PHP notice or warning.
  - **Rule**: if this test ever needs editing to pass, the change under test
    breaks the contract. Fix the change, not the fixture.
- [ ] Add a static gate that fails on dangerous PHP constructs
  - **Goal**: `tests/no-shell.php` runs `token_get_all()` over every PHP
    file under `didJavaScriptFunctionRun/`. It fails on the backtick
    operator; on calls to `shell_exec`, `exec`, `system`, `passthru`,
    `popen`, `proc_open`, `pcntl_exec`, `eval`, `create_function` and
    `assert` with a string argument; and on any `include` or `require`
    whose argument contains a variable not built from `__DIR__`. Working
    from tokens rather than text means comments and strings are never
    false positives.
- [ ] Add a GitHub Actions workflow that runs every check on each push and pull request
  - **Goal**: `.github/workflows/ci.yml` with a matrix of `ubuntu-latest`
    and `windows-latest`, crossed with PHP 7.4 and each PHP 8 release still
    under security support, set up through `shivammathur/setup-php`. Steps:
    `php -l` over every PHP file, `php tests/run.php`,
    `php tests/no-shell.php`, `php tests/security-regression.php`, and
    `node --check` over both scripts.
  - **Note**: `.github/` can be caught by a contributor's global ignore
    rules. Add the workflow with `git add -f` and confirm with
    `git ls-files .github` that it is tracked before relying on it.
- [ ] Add an optional browser end-to-end suite
  - **Goal**: a Playwright suite under `tests/e2e/`, with its own
    `package.json` as a development dependency only, run against the
    built-in server. Scenarios: JavaScript on yields `1`; JavaScript off
    yields `0`; refresh and then submit (the step 4 alternate path that
    copies a value from sessionStorage); double submit; a tab left open past
    the token's expiry; a slow network, using route delays, where the 1.0.0
    script's 500 ms timer would fire before its first request returned.
  - **Note**: Optional by design. CI runs it in one matrix cell only, and
    the PHP suites never depend on it.

## Token Engine

Replace the file-based check with server-signed tokens, keeping every name in
**Compatibility Contract**. After this section the server stops creating a
file per page view, stops trusting the client to name one, and can tell a
real submission from a replayed one. Each item must leave the folder working
with the 1.1.x browser script still in place, because any run can bump the
version and push, and the install one-liner downloads whatever `main` holds.

Next version: minor.

### Library

- [ ] Create `lib/djfr.php`, the single home for all server logic
  - **Goal**: Plain functions prefixed `djfr_`, PHP 7.4 syntax, no global
    state other than a static config cache. It is loaded with
    `require_once __DIR__ . '/../lib/djfr.php'` (adjusted per file) from
    every entry point, so loading it twice is harmless. A direct HTTP
    request to it outputs nothing and answers `404`. Add `lib/index.php`,
    which also answers `404`.
  - **First functions**: `djfr_sanitize_id()` (the allowlist from the
    hotfix), `djfr_is_blocked_agent()`, `djfr_tmp_dir()`,
    `djfr_prune_tmp()`, and `djfr_log()`, which writes through `error_log()`
    with a `didJavaScriptFunctionRun:` prefix.
- [ ] Resolve and create the signing secret
  - **Goal**: `djfr_secret()` checks, in order: the `DJFR_SECRET`
    environment variable, a `DJFR_SECRET` constant defined in an optional
    `config.php`, then `tmp/state/secret.php`. If none exists, it creates
    `tmp/state/` and writes `secret.php` as
    `<?php return '<64 hex chars>';` from `random_bytes(32)`. The write goes
    to a temporary name followed by `rename()`, so two concurrent first
    requests cannot leave a torn file, and the loser re-reads the winner's
    secret. File mode is `0600` where the platform honours it. Add
    `tmp/state/index.php` answering `404`, and a `tmp/state/.htaccess`
    denying all access.
  - **Fail mode**: when nothing can be read or written, return `null` and
    log once per request. Callers apply the fail mode described in
    **Target Architecture**.
- [ ] Issue, renew and verify tokens
  - **Goal**: `djfr_issue_token(?string $renew = null)` builds the format in
    **Target Architecture**. With `$renew`, it accepts a token whose
    signature is valid, whose nonce is unused, and whose `exp` passed no more
    than 24 hours ago. It issues a fresh token with the same `iat` and
    records the old nonce as used.
  - `djfr_verify_token(string $token, bool $consume)` checks the format
    regex, then the signature with `hash_equals()`, then
    `now - iat >= min_seconds` (default 3), then `now <= exp` (default
    lifetime 4 hours), then single use. It returns a reason code: `ok`,
    `missing`, `malformed`, `bad_signature`, `too_fast`, `expired`,
    `replayed`, `blocked_agent` or `server_error`.
  - **Replay store**: with `$consume`, record the nonce by creating
    `tmp/state/used/<nonce>` with `fopen($path, 'x')`. Exclusive create is
    atomic, so of two concurrent submissions exactly one wins. On roughly
    one call in fifty, prune entries older than the lifetime plus the
    renewal window.
  - **Tests**: a round trip; a tampered `iat`, `exp`, nonce or signature;
    each edge of the time window; replay; renewal preserving `iat`; renewal
    refused after the window; a missing secret under both fail modes.

### Endpoints

- [ ] Switch the endpoints to tokens in one change: issuer, legacy bridge and verifier together
  - **Why one item**: once the issuer returns tokens, the 1.1.x verifier can
    no longer find a file named after the posted value, and once the
    verifier expects tokens, a 1.1.x issuer never produces one. Shipped
    separately, either half breaks every install for every visitor. They
    land in the same run with the **Legacy Bridge** items below, or not at
    all.
  - **Issuer** (`scripts/make_random_file.php`): `GET` only (`405`
    otherwise). Response headers `Content-Type: text/plain; charset=utf-8`,
    `Cache-Control: no-store` and `X-Content-Type-Options: nosniff`. The body
    is a fresh token, or a renewed one when the request carries
    `?renew=<token>`. A blocked agent gets `403`. A missing secret gets
    `503`.
  - **Verifier** (`scripts/did_java_script_function_run.php`): reads the
    already-sanitized `$didJavaScriptFunctionRunID`, verifies it with
    `$consume = true`, and sets `$didJavaScriptFunctionRun` to `1` for `ok`
    and `0` for anything else (or `1` under fail open for `server_error`).
    It stores the reason for `djfr_last_reason()`.
  - **Step 3 include** (`didJavaScriptFunctionRun.php`): verifies with
    `$consume = false`. Step 3 runs before step 6 in the same request, and
    consuming the nonce there would make step 6 report `replayed`. It sets
    `$name_form_input` and `$check_action` from that result, keeping their
    1.0.0 meaning.
  - **Done when**: the compatibility fixture from **Testing and CI** passes
    unchanged, both with the new browser script and with the 1.1.x script
    kept as `tests/fixtures/legacy-didJavaScriptFunctionRun.js`.
- [ ] Reduce `scripts/delete_random_file.php` to bridge cleanup
  - **Goal**: Validates the id as in the hotfix. Removes `tmp/<id>/` only if
    the id is a legacy bridge entry, then answers `204` with no body. It never
    touches `tmp/state/`.
- [ ] Rebuild `check_header_did_javascript_function_run.php` and `clean_random_character_function.php` as thin wrappers
  - **Goal**: Each file keeps its function name and the global variable it
    sets, and delegates to the library: `djfr_is_blocked_agent()` and
    `djfr_sanitize_id()`. Guard each function definition with
    `function_exists()`, so a page that includes both the old and new entry
    points never hits a redeclaration error.

### Legacy Bridge

- [ ] Serve tokens to cached pre-token scripts through `tmp/<id>/file.txt`
  - **Issue**: Browsers can keep the old `didJavaScriptFunctionRun.js` in
    cache after an upgrade. That script sends an 8-letter id to the issuer,
    waits 500 ms, then fetches `tmp/<id>/file.txt` and copies its content
    into the field.
  - **Goal**: when the issuer receives a query matching `^[A-Za-z]{8}$`, it
    also writes the token it issued to `tmp/<id>/file.txt`, under the TTL
    and the cap from the hotfix. The old script then copies a valid token
    into the field without knowing it. The 1.2.0 script never sends that
    query, so the bridge is only exercised by stale caches.
- [ ] Accept a bare 8-letter id only while pre-token entries still exist
  - **Goal**: a posted value matching `^[A-Za-z]{8}$` passes only when
    `tmp/<id>/file.txt` exists and holds that same id. Only pre-token code (1.0.0 to 1.1.x) ever
    wrote a file whose content equals its own folder name, so this covers
    forms that were loaded before the upgrade and submitted after it, and
    then closes on its own once the last pre-token entry expires.
- [ ] Log bridge use, so site owners can tell when 2.0.0 is safe for them
  - **Goal**: each bridge path increments a counter in
    `tmp/state/bridge-hits.php`, written with a lock and holding the count
    and the time of the last hit. `djfr_bridge_report()` returns both. The
    upgrade notes tell owners that weeks without a hit mean their visitors
    no longer depend on the bridge.

## Browser Script Rewrite

Rewrite both shipped scripts against the token flow, with the same file names,
the same global functions and the same storage keys. The 1.0.0 script has a
timing race that fails legitimate visitors on slow connections, persists an
identifier in localStorage forever, and leaks an implicit global. The
rewrite fixes all three and moves ownership of the field's value from the
inline step 4 snippet to the library.

Next version: minor.

- [ ] Rewrite `didJavaScriptFunctionRun.js` as a strict-mode module that keeps the legacy globals
  - **Issue**: The script makes one request, then fires the second after a
    fixed 500 ms `setTimeout`. If the first request has not finished in
    500 ms, the second gets a `404`, the field stays empty, and a real
    visitor is scored as "JavaScript did not run". The `for (i = 0; ...)`
    loop on line 24 also writes an undeclared global `i`.
  - **Goal**: an IIFE with `'use strict'` that makes one `fetch()` to the
    issuer, with `credentials: 'same-origin'` and `cache: 'no-store'`, and
    one retry after a short back-off. It writes the token into the field and
    into sessionStorage under `didJavaScriptFunctionRunCheck_randomCharacters`.
    The only names it puts on `window` are the legacy ones:
    `didJavaScriptFunctionRun`, `didJavaScriptFunctionRunCheck`,
    `didJavaScriptFunctionRunCheck_randomCharacters`,
    `didJavaScriptFunctionRunCheck_ID` and `didJavaScriptFunctionRun_DEBUG`.
    `didJavaScriptFunctionRun()` remains callable, and returns a Promise that
    1.0.0 callers can ignore.
- [ ] Let the library own the field's value, whichever path the step 4 snippet took
  - **Issue**: The step 4 snippet alternates. On one load it calls
    `didJavaScriptFunctionRun()`; on the next it copies a value from
    sessionStorage instead, which after 1.2.0 is an already-used token.
  - **Goal**: on `DOMContentLoaded`, if `#didJavaScriptFunctionRunID`
    exists, remove `disabled` and fetch a fresh token. A call already in
    flight from the step 4 snippet is reused rather than duplicated. The
    copied value is always overwritten, so both alternating paths end with a
    usable token.
- [ ] Renew a near-expiry token at submit time without losing the clicked button
  - **Goal**: a `submit` listener on the field's form. When the token is
    within 60 seconds of `exp`, it prevents the submission, requests
    `?renew=<token>`, writes the result, and resubmits with
    `form.requestSubmit(submitter)`. That keeps the clicked button's
    `name=value` pair, which `form.submit()` drops. Where `requestSubmit` is
    missing, it appends a hidden input carrying the submitter's name and
    value, then calls `form.submit()`. A flag stops the listener from
    intercepting its own resubmission.
- [ ] Derive the base path from the script's own URL
  - **Goal**: read `document.currentScript.src` while the script loads (the
    property is `null` inside callbacks) and resolve the issuer relative to
    it. Fall back to `/didJavaScriptFunctionRun/` when it is unavailable.
    Existing installs see no change. Installs in a subfolder now work, which
    1.0.0 could not do because of its hard-coded absolute paths.
- [ ] Stop persisting an identifier in localStorage, and guard every storage call
  - **Issue**: 1.0.0 stores its random id under
    `didJavaScriptFunctionRun_localStorage` and reuses it on every later
    visit, a long-lived per-browser identifier that the check never needed.
    Any storage call also throws where storage is blocked, such as some
    private modes and sandboxed frames, which stops the check outright.
  - **Goal**: never write that key, remove it once if it is found, and wrap
    every `localStorage` and `sessionStorage` access in `try`/`catch` so a
    blocked store degrades to "no cached value".
- [ ] Rewrite `deleteTmpDidJavaScriptFunctionRun.js` as a shim
  - **Goal**: keep `checkFormSubmittedDidJavaScriptFunctionRun()`, which
    step 6 calls, with its current behaviour of disabling the submit button
    after a submission. Keep `cleanTmpDidJavaScriptFunctionRun(forceOut)` and
    `storagePageLoadsDidJavaScriptFunctionRun()` defined as no-ops, because
    the token flow leaves nothing on the server to clean. Remove the
    `beforeunload` listener (unreliable on mobile, and pointless once there
    is no cleanup) and the click listeners attached to every input and
    button on the page. Fix the implicit global `i` on line 79.

## Server Hardening

Defenses that do not depend on the PHP code being correct: server rules,
direct-request guards, and limits on how fast the issuer can be called. Each
one has a fallback, because a rule in `.htaccess` does nothing on nginx or
IIS.

Next version: patch.

- [ ] Ship an Apache `.htaccess` for the package root
  - **Goal**: deny `lib/` and `tmp/state/`; `Options -Indexes`;
    `Cache-Control: no-cache` on both scripts, so an upgrade reaches
    browsers on their next visit instead of whenever their cache expires;
    `X-Content-Type-Options: nosniff`. Wrap each directive in an
    `<IfModule>` guard, so a server without `mod_headers` serves the folder
    instead of answering `500`. Use `Require all denied` for 2.4 with a
    `<IfModule !mod_authz_core.c>` fallback to `Deny from all`.
- [ ] Guard every private PHP file against direct requests, independently of server rules
  - **Goal**: each file under `lib/` and `tmp/state/` answers `404` with no
    body when it is the requested script rather than an include. Every
    private folder carries an `index.php` that does the same. With these in
    place, a server that ignores `.htaccess` exposes nothing beyond the
    zero-byte nonce files, which reveal only that a nonce was used.
- [ ] Add an optional per-client rate limit on the issuer
  - **Goal**: off by default. When on, a token bucket per client, keyed by
    `hash('sha256', $ip . $secret)`, so raw addresses are never written to
    disk. Stored under `tmp/state/rate/` with `flock()`, pruned like the
    replay store. Over the limit, the issuer answers `429` with
    `Retry-After`. The documentation says plainly that a reverse proxy or
    web application firewall does this better, and that `REMOTE_ADDR` is the
    proxy's address unless the server is configured to trust a forwarded
    header.

## PHP API and Configuration

A shorter install for new sites and a configuration file for every
threshold, on top of the same library, so that the six-step install keeps
working unchanged beside them. Also the optional layers a site can add when
the bare signal is not enough.

Next version: minor.

- [ ] Add `config.sample.php` and optional `config.php` loading
  - **Goal**: `djfr_config()` merges defaults with
    `<folder>/config.php`, or with the file named by the `DJFR_CONFIG`
    environment variable for owners who keep configuration outside the web
    root. Options: `min_seconds` (3), `lifetime` (14400), `renew_window`
    (86400), `fail_mode` (`'closed'`), `block_user_agents` (`['curl']`),
    `honeypot_field` (`null`), `tmp_ttl` (1800), `tmp_cap` (2000),
    `rate_limit` (`null`), `proof_of_work` (0), `debug_log` (`false`).
    Unknown keys are logged rather than ignored silently.
  - **Note**: the upgrade notes must say that a `config.php` inside the
    folder is lost if the folder is deleted and recopied. Copy the new folder
    over the old one, or use `DJFR_CONFIG`.
- [ ] Add `bootstrap.php` and a three-touch-point install
  - **Goal**: a new install becomes one `require` at the top of the page,
    `<?= djfr_field() ?>` inside the form, and `if (djfr_verified()) { ... }`
    where the submission is handled. `djfr_field()` prints the disabled
    hidden field and the script tags, using `defer`, and escapes everything
    it prints. `djfr_verified()` verifies and consumes once per request, and
    caches the result. `djfr_last_reason()` exposes the reason code. The
    bootstrap may send headers when it is included before any output, which
    the legacy include never could.
  - **Compatibility**: The six-step install keeps working on the same
    library. Both styles can coexist on one site, one page each.
- [ ] Add an optional honeypot field
  - **Goal**: with `honeypot_field` set, `djfr_field()` also renders a text
    input under that name, hidden from sight and from assistive technology
    (`aria-hidden="true"`, `tabindex="-1"`, `autocomplete="off"`, moved off
    screen rather than `display:none`, which some bots skip). A submission
    that fills it fails with reason `honeypot`.
- [ ] Add an optional proof-of-work challenge
  - **Goal**: with `proof_of_work` set to a difficulty in bits, the issuer
    adds a challenge, and the script finds a counter whose SHA-256 with the
    token has that many leading zero bits before it writes the field. The
    verifier checks the work. This raises the cost of every scripted
    submission without asking anything of a person.
  - **Constraint**: `crypto.subtle` exists only in secure contexts (HTTPS or
    localhost). On plain HTTP the script reports reason `insecure_context`
    and the site owner is told in the documentation. Default difficulty is
    kept low enough to finish in well under a second on a mid-range phone.

## Examples and Demo Page

The pages people copy from. They have to show the safe pattern, because
whatever they show ends up in production.

Next version: patch.

- [ ] Rewrite `example/feedback.php` to escape on output and to stop on validation errors
  - **Issue**: The example inherits a tutorial pattern that runs
    `htmlspecialchars()` and `stripslashes()` on input, then echoes the
    result. Data is escaped for HTML before anything else uses it, and the
    handler carries on even when validation set an error.
  - **Goal**: keep raw values for processing, escape with
    `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` at each echo, drop
    `stripslashes()`, and act only when every field validated and
    `$didJavaScriptFunctionRun === 1`. Keep the six-step install in this
    file, since it is the reference for existing sites, and bring
    `example/feedback_blank.php` in line with it.
- [ ] Add `example/feedback-bootstrap.php` showing the 1.3.0 install
  - **Goal**: the same form built on `bootstrap.php`, short enough to read at
    a glance.
- [ ] Replace the commented `mail()` call in README step 6 and the examples
  - **Issue**: `mail("...", "Web Comment", $comment, $email)` passes the
    visitor's email address as the fourth argument, which is the raw extra
    headers. Uncommented as shown, it opens the form to email header
    injection.
  - **Goal**: send to `admin@example.com`, add `Reply-To:` only after
    `filter_var($email, FILTER_VALIDATE_EMAIL)` passes and the value
    contains no `\r` or `\n`, and say in a comment why.
- [ ] Update `index.html` and `sample_didJavaScriptFunctionRun.js` to show the token flow
  - **Goal**: the demo walks through issue, field and verify, and shows both
    the six-step and the bootstrap install. Fix the instructional video link
    near line 199, which points to `javascript:void(0)` with
    `target="_blank"` and so opens a blank tab.

## Documentation

The README and the new `docs/` folder rewritten around what the template
does after the renovation. The README stays under 300 lines and file-level
internals move to `docs/`. The 1.1.0 hotfix already removed the warnings
about shell commands that no longer exist.

Next version: none. Documentation carries no version weight. When an item
here is queued into `## Current`, give it `- From: version.control = null`.

- [ ] Rewrite `README.md` under 300 lines
  - **Goal**: open with what the template is (a low-friction signal that
    JavaScript ran in the browser that submitted a form) and what it is not
    (a CAPTCHA, CSRF protection, or a barrier to headless browsers). Then a
    quick start with the bootstrap install, a link to the legacy six-step
    install, upgrading, configuration, security, and license. Move
    everything else to `docs/`.
- [ ] Move the file-by-file internals and the six-step install into `docs/`
  - **Goal**: `docs/how-it-works.md` covers the request flow, the token
    format and the design decisions from **Target Architecture**, written
    for readers rather than for the road map. `docs/legacy-install.md` holds
    the six steps verbatim apart from the step 4 escaping fix, and says they
    remain supported through 1.x.
- [ ] Write `docs/upgrading.md`
  - **Goal**: one section per version, opening with how to read the installed
    version from `didJavaScriptFunctionRun/VERSION` (no file means 1.0.0).
    1.0.0 to 1.1.0: replace the folder,
    nothing else. To 1.2.0: replace the folder; `tmp/` must stay writable
    because the secret and replay store live there; optionally set
    `DJFR_SECRET`; watch `djfr_bridge_report()`. To 1.3.0: nothing required;
    the bootstrap install is optional. Each section lists what a site owner
    can verify afterwards.
- [ ] Write `docs/configuration.md` and `docs/server-config.md`
  - **Goal**: every option with its default, its effect and an example.
    Server rules for Apache (already in `.htaccess`), nginx `location`
    blocks denying `lib/` and `tmp/state/` and turning off `autoindex`, and
    an IIS `web.config` with the same intent.
- [ ] Add `SECURITY.md`
  - **Goal**: a supported-versions table (latest 1.x only), reporting through
    GitHub private vulnerability reporting, an expected first response time,
    and a short history section filled in once the owner has published the
    1.0.0 advisory (see **User Use Only - Owner Decisions**).
- [ ] Fix spelling and code-fence labels throughout the README
  - **Goal**: the shell warnings, "here be mosters" and the
    `template_check.php` description went out with 1.1.0, along with the
    misspellings inside them. Still to fix: "severl", "accoringly",
    "hanldes", "reponse", "genereates", "ouput", "againgst", "listenr",
    "absolut", "requring", "utiilized", "repositories'", and the doubled
    `check_header_did_javascript_function_run.php.php`. Relabel fences:
    shell commands as `bash`, PHP as `php`, HTML as `html`, and the
    misspelled `markdoen` fence.
- [ ] Document what the install one-liner downloads, and how to check the version afterwards
  - **Goal**: the README says plainly that the one-liner downloads `main`,
    which every run keeps shippable, because the project publishes no
    releases. Directly after it, show how to read
    `didJavaScriptFunctionRun/VERSION` and compare it against the newest
    entry in `CHANGELOG.md`. For a reproducible install, show the same
    one-liner with a commit hash in place of `main`.

## Repository Housekeeping

Repository-level chores that make every other section easier to review. None
of it changes what an install receives.

Next version: none. Queue these with `- From: version.control = null`.

- [ ] Add `.gitattributes` and renormalise line endings in a commit of their own
  - **Goal**: `* text=auto eol=lf`, `*.bat text eol=crlf`, and `binary` for
    `*.png`, `*.jpg`, `*.mp4`, `*.psd` and `*.ai`. Then run
    `git add --renormalize .` and commit it alone, so later diffs show
    content changes rather than whole-file line-ending churn.
- [ ] Add `.editorconfig` matching the existing style
  - **Goal**: one-space indentation in PHP and JavaScript, which is what
    the code already uses; LF line endings; final newline; UTF-8.

## Video Tool Draft

Groundwork for a separate, global tool that produces short instructional
videos for repositories: a dedicated agent that learns to script Adobe After
Effects, renders the video, prepares the YouTube upload, and hands the result
to the `make-blog-post` skill. This repository's own walkthrough video is the
tool's first case study, which is why the work starts here; none of it ships
with this repository. The draft, its progress log, and its decisions live in
`.claude/draft-video-tool/`, which version control ignores. When that folder
no longer exists, the draft has moved to its permanent home and the items
below are no longer this repository's work.

Next version: none. Nothing here changes what this repository ships. When an
item here is queued into `## Current`, give it `- From: version.control = null`.

- [ ] Set up the dedicated video agent from the draft and record its first session
  - **Goal**: review `agents/video-producer.agent.md` in
    `.claude/draft-video-tool/` against the folder's `README.md`,
    `DECISIONS.md` and `PROGRESS.md`, then work the first unchecked
    `PROGRESS.md` item that needs no Adobe application, following that agent's
    rules. The agent, its progress, and its decisions stay in the draft
    folder, so they move with it.
  - **Done when**: `PROGRESS.md` has a dated log line for the session, and any
    decision it changed is noted in `DECISIONS.md`.
- [ ] Script the first After Effects building block the case study calls for
  - **Goal**: in `.claude/draft-video-tool/after-effects/jsx/`, an ES3
    ExtendScript that builds one section composition from a shot list:
    imports the clips in order, lays them end to end, and adds a composition
    marker at the start. Base it on the probe report in
    `after-effects/probe-output/` once one exists. An automated run writes
    the script and the exact command to run it, and never launches After
    Effects itself.
  - **Done when**: the script parses, follows `after-effects/scripting-notes.md`,
    and `PROGRESS.md` holds the command for the user to run.
- [ ] Draft the replacement YouTube uploader in the draft folder
  - **Goal**: `.claude/draft-video-tool/upload/upload_video.py`, following
    the replacement plan in `upload/youtube-pipeline.md`: maintained Google
    client libraries, tokens in a per-user configuration folder, `private` by
    default, and the result printed as one JSON line. An automated run reads no
    credential file and uploads nothing.
  - **Done when**: the script runs `--help` cleanly, and the plan's checklist
    in `youtube-pipeline.md` says what is still untested.
- [ ] Settle the hand-off between the video tool and `make-blog-post`
  - **Goal**: finalize `collaboration/make-blog-post.md` in the draft folder:
    the hand-off file's fields, the order of work, and the approval gates on
    both sides. Changes the post skill needs are written there as proposals,
    not made to the skill.
- [ ] **User todo**: Run the After Effects probe once on a copy of the case-study project
  - Turn on **Allow Scripts to Write Files and Access Network** in After
    Effects, then run `after-effects/jsx/probe-project.jsx` from the draft
    folder on a copy of the project. The report lands in
    `after-effects/probe-output/`, where the scripting items above read it.
- [ ] **User todo**: Choose the tool's permanent home and move the draft folder there
  - Open question Q1 in the draft's `DECISIONS.md`. Moving the folder out of
    this repository closes this section.

## Out of Scope Until 2.0.0

Changes that would break the **Compatibility Contract**, recorded so they are
not lost and not done early. Nothing here is a checkbox, so nothing here is
ever queued. Next version: major, and only when the owner decides the legacy
install has had a long enough tail.

- Removing the legacy bridge: the `tmp/<id>/file.txt` write, bare 8-letter
  id acceptance, and `delete_random_file.php`.
- Removing the shims: `clean_random_character_function()`,
  `check_header_did_javascript_function_run()`, the no-op functions in
  `deleteTmpDidJavaScriptFunctionRun.js`, and the undocumented variables
  `$name_form_input`, `$check_action` and
  `$user_agent_did_javascript_function_run`.
- Renaming files to one consistent scheme, and dropping the requirement that
  the folder sit at the document root.
- Raising the PHP floor above 7.4.
- Publishing as a Composer package with autoloading.

## User Use Only - Owner Decisions

Decisions and outward-facing actions that belong to the repository owner. A
run never works, checks or queues these. Next version: none.

- [ ] **User todo**: Review 1.1.0, then push the fix, `CHANGELOG.md` and this file together
  - This file and the changelog name the vulnerable code paths, and the
    repository is public. Publishing them in the same push as the fix keeps
    the description from going out ahead of it. The README now opens with a
    security notice telling 1.0.0 users to replace the folder.
- [ ] **User todo**: Publish a GitHub security advisory for 1.0.0 once 1.1.0 is pushed, and request a CVE through it
  - With no releases to point at, the advisory names `1.1.0` as the patched
    version and tells site owners to check `didJavaScriptFunctionRun/VERSION`.
    An install without that file is the vulnerable 1.0.0.
- [ ] **User todo**: Enable GitHub private vulnerability reporting, which `SECURITY.md` will point to
- [ ] **User todo**: Resolve README clause 4 of the use agreement against the CC0 license
  - CC0 waives the restrictions clause 4 states, which is that the work
    cannot be redistributed or relicensed as published. Keep CC0 and drop
    the clause, or change the license.
  - Clause 2 of the same agreement still warns that altering the support
    scripts could clear every file on the server, which stopped being true
    in 1.1.0.
- [ ] **User todo**: Re-record or annotate the instructional video after 1.2.0
  - It shows the six-step install and the shell warnings. Annotate it with a
    pointer to the bootstrap install and the upgrade notes, or replace it.

## Complete

- [x] Replace `clean_random_character_function()` with a strict allowlist and reject malformed ids at every endpoint
  - From: Security Hotfix
  - **Issue**: The function trims, runs `htmlspecialchars()`, then strips only
    `.`, `/`, `=`, `$` and `*`. Shell metacharacters such as `;`, `|`,
    backticks, spaces and newlines survive it, and `htmlspecialchars()` adds
    `&` and `;` of its own when it encodes `<`, `>`, `"` and `'`. The result
    is placed into shell commands in `scripts/make_random_file.php`,
    `scripts/delete_random_file.php` and
    `scripts/did_java_script_function_run.php`, so request input reaches a
    shell. On any server running 1.0.0, anyone who can load the page can run
    commands as the web server user, without logging in.
  - **Goal**: Ids are validated, not cleaned.
    - `clean_random_character_function($value)` keeps its name and signature.
      It returns `trim($value)` when that matches `^[A-Za-z0-9_]{1,160}$`, and
      `''` otherwise, including for non-string input such as an array. The
      underscore and the 160-character ceiling are there on purpose: they
      admit the signed tokens planned in **Token Engine**, so this function
      does not have to change again in the token release. A
      `function_exists()` guard lets the library and the host page both load
      the file.
    - `make_random_file.php` and `delete_random_file.php` read the id from
      `$_SERVER['QUERY_STRING']` instead of slicing `REQUEST_URI` after its
      last `?`. That slice has a second bug: with no `?` in the URL,
      `strrpos()` returns `false` and the "id" becomes the request path minus
      its first character.
    - Both endpoints require `^[A-Za-z]{8}$`, which is exactly what the
      shipped script generates, and answer `400` with an empty body when the
      id does not match, before touching the file system.
    - `did_java_script_function_run.php` validates on every path. The 1.0.0
      code skips cleaning when the value is one character long.
  - **Files**: `didJavaScriptFunctionRun/scripts/clean_random_character_function.php`,
    `didJavaScriptFunctionRun/scripts/make_random_file.php`,
    `didJavaScriptFunctionRun/scripts/delete_random_file.php`,
    `didJavaScriptFunctionRun/scripts/did_java_script_function_run.php`
  - **Done when**: an id containing anything outside the allowlist gets `400`
    from both endpoints and `$didJavaScriptFunctionRun = 0` from step 6, and
    no file or folder is created for it. The 8-letter ids the shipped script
    sends behave exactly as before.
  - **Compatibility**: No legitimate request changes behaviour, because the
    shipped script only ever sends 8 ASCII letters. Host pages that call
    `clean_random_character_function()` themselves keep working; they get an
    empty string back for input that was never valid.
  - **Verified** (1.1.0, PHP 8.5 built-in server): 15 hostile ids, covering
    shell separators, command substitution, traversal, an encoded newline
    and null byte, over-long input, digits and spaces, each got `400` from
    both endpoints and created nothing. A marker file that every
    command-running payload tried to write never appeared. An array-shaped
    `didJavaScriptFunctionRunID[]` field raises no error and scores `0`.
- [x] Remove every shell command, the generated PHP file, and the five-minute sleep loop from the PHP scripts
  - From: Security Hotfix
  - **Issue**: The scripts use the shell for work PHP does natively:
    backtick `mkdir`, `echo >` and `rm` in `make_random_file.php`,
    `shell_exec("rm -rf ...")` in `delete_random_file.php`, `rm` and `rmdir`
    through `shell_exec()` in `did_java_script_function_run.php`, and two
    `find ... -exec rm` cleanups that run on every request before any check.
    `make_random_file.php` also reads `template_check.php`, substitutes
    request input into its PHP source, writes the result to `tmp/<id>.php`
    and `include`s it, which is code generation from request input.
    Finally, every call sits in a loop of `sleep(1)` pairs for up to 150
    iterations, about 300 seconds, holding a PHP worker the whole time. On
    Linux `sleep()` does not count toward `max_execution_time`, so nothing
    cuts it short, and a few dozen requests can exhaust a PHP-FPM pool.
  - **Goal**:
    - Native calls only: `mkdir()`, `file_put_contents()` with `LOCK_EX`,
      `file_get_contents()`, `unlink()`, `rmdir()`.
    - Every path built from `__DIR__` (the tmp folder is
      `dirname(__DIR__) . '/tmp/'`), never from the current working
      directory.
    - The day-old cleanup becomes a PHP function that walks `tmp/` with
      `scandir()` and removes every entry older than 30 minutes except
      `README.md`, `index.html`, `.htaccess`, editor metadata folders named
      `_notes`, and the reserved `state` folder.
      Ages come from `lstat()` and links are removed rather than followed.
      Removing everything else also clears what 1.0.0 could leave behind,
      such as stray `tmp/<id>.php` files. It runs after the id is validated,
      not before.
    - A reused id (the shipped script keeps one in localStorage) renews its
      folder's time instead of being recreated, so an open form is not
      pruned out from under its visitor.
    - `make_random_file.php` writes `tmp/<id>/file.txt` and answers `yes`
      directly. `template_check.php` is deleted, along with the include and
      the `tmp/<id>.txt` and `tmp/<id>.php` files it needed.
    - The sleep loop is deleted. Expiry is handled by the prune on later
      requests and by `delete_random_file.php`, which the shipped script
      already calls on unload and after a submission.
  - **Files**: `didJavaScriptFunctionRun/scripts/make_random_file.php`,
    `didJavaScriptFunctionRun/scripts/delete_random_file.php`,
    `didJavaScriptFunctionRun/scripts/did_java_script_function_run.php`,
    `didJavaScriptFunctionRun/scripts/template_check.php` (deleted)
  - **Done when**: no `shell_exec`, `exec(`, `system(`, `passthru(`,
    `popen(`, `proc_open(` or backtick operator remains anywhere under
    `didJavaScriptFunctionRun/`. `make_random_file.php` returns in well under
    a second. The flow still yields `1` with JavaScript on and `0` with it
    off.
  - **Compatibility**: Same URLs, same `tmp/<id>/file.txt` location, same
    `yes` response body the script looks for. Worth a line in the changelog:
    the scripts no longer depend on POSIX tools such as `rm` and `find`, so
    they can run on Windows servers.
  - **Verified** (1.1.0): a `token_get_all()` scan finds no shell call,
    backtick or `eval` in the shipped PHP. The issuer answers in well under
    a second. A two-hour-old folder and a leftover file were pruned while
    `README.md`, an old `_notes` folder and fresh entries stayed. The full submit flow scores `1`
    with the id and `0` without it, and a replayed id scores `0`.
- [x] Make `$didJavaScriptFunctionRunID` safe to echo, closing the reflected cross-site scripting hole in step 4
  - From: Security Hotfix
  - **Issue**: `didJavaScriptFunctionRun.php` copies
    `$_POST["didJavaScriptFunctionRunID"]` into `$didJavaScriptFunctionRunID`
    unchanged. Step 4 of the README, and `example/feedback.php` line 119,
    echo that variable into `value="..."` without escaping. The form has no
    CSRF token, so a page on any other site can auto-submit a crafted value
    and run script in the host site's origin. The cleaning function is only
    applied in step 6, after the echo has already happened.
  - **Goal**: Sanitize at assignment, inside the library, so that every
    existing install is fixed by replacing the folder, with no edit to the
    host page. Set `$didJavaScriptFunctionRunID` to
    `clean_random_character_function($_POST['didJavaScriptFunctionRunID'] ?? '')`
    on every path. Separately, as defense in depth, change the README step 4
    snippet, the snippet shown in `index.html`, and `example/feedback.php` to
    `htmlspecialchars($didJavaScriptFunctionRunID, ENT_QUOTES, 'UTF-8')`.
  - **Files**: `didJavaScriptFunctionRun/didJavaScriptFunctionRun.php`,
    `README.md` (step 4 snippet only), `index.html` (the displayed step 4
    snippet), `example/feedback.php`
  - **Done when**: posting a `didJavaScriptFunctionRunID` that contains `"`,
    `<` or `>` renders `value=""`, and a normal submission renders the id
    unchanged.
  - **Verified** (1.1.0): against a page built from the 1.0.0 snippets with
    the unescaped step 4 echo left as it was, a posted `"><script>` value
    rendered `value=""` and a normal id rendered unchanged.
- [x] Fix the PHP 8 fatal error, the user-agent check, the working-directory path, and the undefined variables in the step 3 include
  - From: Security Hotfix
  - **Issue**: Four defects in files that every host page includes:
    - `didJavaScriptFunctionRun.php` lines 38 and 54 call `count()` on a
      string to drop a trailing newline. PHP 8 throws `TypeError` there,
      which is a fatal error on the host page. PHP 7 only warned and happened
      to return `1`.
    - `check_header_did_javascript_function_run()` compares
      `strpos($ua, "url")` loosely, so `false == 0` lets every user agent
      without `url` through. It also looks for the header under the exact
      name `User-Agent`, but HTTP/2 sends header names in lower case.
      `didJavaScriptFunctionRun.php` includes the file but never calls the
      function, so on the main path the check never runs at all.
    - On a `GET`, `$name_form_input` and `$check_action` are never set. Once
      the agent check is actually called, its blocked path would leave
      `$didJavaScriptFunctionRunID` undefined as well, and step 4 echoes it.
      `$name_of_required_form_field` is read without a default.
    - `$where_is_the_file_checked = "tmp/"` resolves against the host page's
      working directory, usually the document root, not against the library
      folder, so the check in this file looks in the wrong place.
  - **Goal**: `trim()` replaces both `substr(..., count(...) - 2)` calls. The
    agent check reads `$_SERVER['HTTP_USER_AGENT'] ?? ''`, which every SAPI
    provides already normalised, and matches `curl` with
    `stripos(...) !== false`. It sets the flag to exactly `0` or `1` and is
    called from `didJavaScriptFunctionRun.php`. Every variable the include is
    known to set (`$didJavaScriptFunctionRunID`, `$name_form_input`,
    `$check_action`, `$user_agent_did_javascript_function_run`) is
    initialised at the top of the include, and the tmp path is built from
    `__DIR__`. The id comparison is strict (`===`), so two numeric-looking
    strings can no longer compare equal.
  - **Files**: `didJavaScriptFunctionRun/didJavaScriptFunctionRun.php`,
    `didJavaScriptFunctionRun/scripts/check_header_did_javascript_function_run.php`
  - **Done when**: with `error_reporting(E_ALL)`, the example page loads and
    submits with no notice, warning or error on PHP 7.4 and on the newest
    PHP 8 release, for a `GET`, a `POST` with JavaScript, and a `POST`
    without it.
  - **Compatibility**: The curl check keeps its 1.0.0 intent of refusing
    requests that identify as curl. It now actually runs. It is not a
    security control, since a client only has to change its user-agent to
    get past it, and the documentation rewrite says so.
  - **Verified** (1.1.0, PHP 8.5, `E_ALL` with `display_errors` on): `GET`,
    `POST` with an id, `POST` without one, an array field and a curl-agent
    `POST` all ran with no notice, warning or error, and `feedback.php`
    loads clean. Including the four PHP files produces zero bytes of output.
    **Not yet run on PHP 7.4.** The code avoids PHP 8-only syntax, and the
    CI matrix in **Testing and CI** includes 7.4.
- [x] Create `CHANGELOG.md` with semantic versions and a `1.0.0` baseline, and add `didJavaScriptFunctionRun/VERSION`
  - From: version.control = null
  - **Issue**: The project had no changelog, no tags and no releases, and its
    last commit was more than a year old. Left to infer a release mode, the
    automation would pick dated mode, and a security fix would ship without
    a version number. The project also publishes no releases, so a site
    owner who copied the folder had no way to tell whether they run the
    vulnerable 1.0.0 or a fixed version.
  - **Goal**:
    - `CHANGELOG.md` at the root, using Keep a Changelog headings and
      stating that the project follows semantic versioning but publishes no
      GitHub releases or tags, with a `## [1.0.0] - 2024-09-04` baseline
      describing the template as published.
    - `didJavaScriptFunctionRun/VERSION`, which ships inside the folder site
      owners copy, so every install from 1.1.0 on carries its own version
      and an install without the file is 1.0.0.
    - The owner chose a minor bump for the hotfix, so it ships as `1.1.0`,
      with its changelog entry and `VERSION` written alongside the fixes.
  - **Done when**: both files exist, the changelog states semantic
    versioning explicitly so later runs read it as released mode, `VERSION`
    holds `1.1.0`, and nothing is tagged or released.
  - **Verified** (1.1.0): `CHANGELOG.md` holds `[Unreleased]`, `[1.1.0]` and
    `[1.0.0]`; `VERSION` holds `1.1.0` and a newline.
