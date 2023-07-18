<p align="center"><img src="https://www.puritylife.com/wp-content/uploads/2018/01/Purity-Life-LOGO_website-176x398.png"></p>

# Purity Life Information Centre

[![Tests](https://github.com/ZeusSystems/plic/actions/workflows/laravel.yml/badge.svg)](https://github.com/ZeusSystems/plic/actions/workflows/laravel.yml)
[![Run style format (PHP)](https://github.com/ZeusSystems/plic/actions/workflows/run-style.yml/badge.svg)](https://github.com/ZeusSystems/plic/actions/workflows/run-style.yml)

## Setup & Environment

* Laravel 9
* PHP 8.1
* Application database is MySQL 8
    * Also integrates with a DB2 instance on AS/400 and an external MySQL database for Kyolic website
* Tests are written using Pest
* Formatting is done using Laravel Pint

## Development Process

All bugfix or feature development should be completed in a separate branch.  Prefix the branch name with the ID from the associated Backlog ticket, if applicable, e.g. *363-draft-outdated-tweaks for Backlog ticket PLICNEW-363*.

A PR must be created for review and to run CI pipelines.  When creating PRs, please add a backlink to the associated Backlog ticket(s), if any, e.g.:

```markdown
[PLICNEW-123](https://zeussystem.backlog.com/view/PLICNEW-123)
```

Do the same with the Github PR in the associated the Backlog ticket(s)

### Tests

Write tests for new features, if possible.  For bugfixes, try to write a failing test and then fix the bug.

> ## **No committing directly to master/main!**

### Formatting
Make sure to format code prior to pushing to Github; if there are style violations, the CI pipeline will fail - if this happens, run the formatter and push new commits to Github.

## Running Tests

Tests are run automatically when opening a PR and on pushes to said PR.

To run the tests locally:

```shell
php artisan test
```

or

```shell
./vendor/bin/pest
```

By default, tests in the `integration` group are excluded.  To run the full test suite, you must pass the `--exclude-group=none` option when running Pest.

Keep in mind there may be costs associated with these tests, e.g. Google Map API requests, so run wisely!
