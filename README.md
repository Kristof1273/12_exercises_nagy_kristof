# PHP Testing Exercises

Solutions for the PHPUnit testing exercises: first unit tests, data providers,
test dependencies, Test-Driven Development, stubs and mocks, plus the optional
"Extra Mile" tasks (integration test, code coverage, BDD-style test).

## Requirements

- PHP 8.2 or newer (CLI) — required by PHPUnit 11
- The `pdo_sqlite` extension for the integration test (the test is skipped when
  the extension is missing)
(use "sudo apt install php-sqlite3" command)
- Optional: [Xdebug](https://xdebug.org/) or [PCOV](https://github.com/krakjoe/pcov)
  for the code coverage report
- On a fresh Ubuntu / Debian / WSL install, `dom`, `xmlwriter` and `mbstring` are usually missing. They come from the `php-xml` and `php-mbstring` packages:

```bash
sudo apt update
sudo apt install php-xml php-mbstring
```  

No Composer is needed. PHPUnit is installed as a PHAR, and the classes are
loaded by the small PSR-4 autoloader in `tests/bootstrap.php`.

## Installing PHPUnit (PHAR method)

Run the following in the project root. The PHAR is listed in `.gitignore`, so
it has to be downloaded once per machine.

```bash
# Linux / macOS
wget -O phpunit.phar https://phar.phpunit.de/phpunit-11.phar

# Windows (PowerShell) or any system without wget
curl.exe -L -o phpunit.phar https://phar.phpunit.de/phpunit-11.phar
```

Check that it works:

```bash
php phpunit.phar --version
# PHPUnit 11.5.x by Sebastian Bergmann and contributors.
```

## Running the test suite

PHPUnit reads `phpunit.xml` from the project root, so no arguments are needed.

```bash
# all tests
php phpunit.phar

# all tests, printed as readable sentences
php phpunit.phar --testdox

# only one test suite
php phpunit.phar --testsuite Unit
php phpunit.phar --testsuite Integration

# only one test class or test method
php phpunit.phar --filter TaskListTest
php phpunit.phar --filter testSortByNameOrdersTheTasksAlphabetically
```

Expected result:

```text
OK (50 tests, 61 assertions)
```

## Project structure

```text
.
├── phpunit.xml                          PHPUnit configuration
├── src/                                 production code (namespace App\)
│   ├── Calculator.php
│   ├── Task.php
│   ├── TaskList.php
│   ├── PricingServiceInterface.php
│   ├── NotificationServiceInterface.php
│   ├── OrderProcessor.php
│   └── TaskRepository.php
└── tests/                               test code (namespace App\Tests\)
    ├── bootstrap.php                    PSR-4 autoloader
    ├── Unit/
    │   ├── CalculatorTest.php
    │   ├── CalculatorDataProviderTest.php
    │   ├── CalculatorDependencyTest.php
    │   ├── TaskTest.php
    │   ├── TaskListTest.php
    │   └── OrderProcessorTest.php
    └── Integration/
        └── TaskRepositoryTest.php
```

## Where to find each exercise

| Exercise | Production code | Tests |
| --- | --- | --- |
| 01 – PHPUnit setup, first unit tests (AAA) | `src/Calculator.php` | `tests/Unit/CalculatorTest.php` |
| 02 – Data providers | `src/Calculator.php` | `tests/Unit/CalculatorDataProviderTest.php` |
| 02 – Test dependencies | `src/Calculator.php` | `tests/Unit/CalculatorDependencyTest.php` |
| 03 – TDD: `Task` and `TaskList` | `src/Task.php`, `src/TaskList.php` | `tests/Unit/TaskTest.php`, `tests/Unit/TaskListTest.php` |
| 04 – Stubs and mocks | `src/OrderProcessor.php` and the two `*ServiceInterface.php` files | `tests/Unit/OrderProcessorTest.php` |
| Extra – Integration test | `src/TaskRepository.php` | `tests/Integration/TaskRepositoryTest.php` |
| Extra – BDD-style test | – | `it_throws_an_invalid_argument_exception_when_dividing_by_zero()` in `tests/Unit/CalculatorTest.php` |

All tests follow the AAA pattern; the three steps are marked with
`// Arrange`, `// Act` and `// Assert` comments. When an exception is expected,
the assert step (`expectException()`) has to come before the act step.

## Exercise 03: the TDD cycle in the commit history

`TaskList` was built test-first. Every step of the exercise is visible in the
history as a Red → Green (→ Refactor) sequence:

```bash
git log --oneline --reverse
```

| Commit prefix | Phase | State of the test suite |
| --- | --- | --- |
| `test(ex03): RED - …` | A new test is written before the implementation | fails on purpose |
| `feat(ex03): GREEN - …` | The simplest code that makes the test pass | passes |
| `refactor(ex03): …` | The code is cleaned up without changing behaviour | passes |

| Step | Red | Green | Refactor |
| --- | --- | --- | --- |
| 1. Skeletons | – | – | `chore(ex03): add Task and TaskList skeletons` |
| 2. Empty list returns `null` | TypeError: nothing is returned | `return null;` | – |
| 3. Add and retrieve one task | `null` is returned instead of the task | task kept in a single property | – |
| 4. Add and retrieve multiple tasks | always the last task is returned | storage becomes an array | tests create the list in `setUp()` |
| 5. Retrieve a task by ID | TypeError: nothing is returned | loop over the tasks | – |
| 6. Invalid arguments | no exception is thrown | guards for a negative index / ID | – |
| 7. Sorting | list order is unchanged, no exception | `sortByName()`, `sortById()`, `sortBy()` with duplicated `usort()` calls | the specific methods delegate to `sortBy()` |

Because the Red commits contain a failing test by design, the suite is green on
every commit except the ones marked `RED`.

## Extra Mile

### Integration test

`TaskRepository` saves and retrieves `Task` records through PDO.
`TaskRepositoryTest` does not use test doubles: it runs against a real SQLite
in-memory database (`sqlite::memory:`). `setUp()` opens a new connection and
creates the `tasks` table before every test, so each test starts with an empty
database.

### Code coverage

A coverage driver (Xdebug or PCOV) has to be installed. With Xdebug:

```bash
php -d xdebug.mode=coverage phpunit.phar --coverage-html coverage

# additionally collect branch and path coverage (Xdebug only)
php -d xdebug.mode=coverage phpunit.phar --coverage-html coverage --path-coverage
```

Then open `coverage/index.html` in a browser. The `coverage/` directory is
generated output and is not committed.

The first report, generated after Exercise 04 and the integration test, showed
three gaps. A test was added for each of them:

| Class | Untested code | Test that was added |
| --- | --- | --- |
| `OrderProcessor` | the guard that rejects a negative quantity | `testProcessOrderRejectsANegativeQuantityWithoutSendingAConfirmation` |
| `TaskRepository` | the `UPDATE` branch of `save()` for an ID that already exists | `testSavingATaskWithAnExistingIdUpdatesTheStoredTask` |
| `TaskList` | `getTaskById()` on an empty list and with a match on the first task | `testGetTaskByIdReturnsNullWhenTheListIsEmpty`, `testGetTaskByIdFindsTheFirstTaskInTheList` |

| Metric | Before | After |
| --- | --- | --- |
| Lines | 93.44% (57/61) | 100% (61/61) |
| Branches | 95.35% (41/43) | 100% (43/43) |
| Paths | 83.87% (26/31) | 96.77% (30/31) |

The one remaining path is in `TaskList::getTaskById()`: it is the route on which
the `foreach` loop is skipped because its subject is not iterable. The `$tasks`
property is typed as `array`, so this path cannot be reached by any test.

### BDD-style test

The division-by-zero test in `CalculatorTest` was renamed from
`testDivideByZeroThrowsAnInvalidArgumentException()` to
`it_throws_an_invalid_argument_exception_when_dividing_by_zero()` and uses the
`/** @test */` annotation instead of the `test` prefix.

PHPUnit 11 still supports annotations but reports them as deprecated, and
PHPUnit 12 no longer reads them. For that reason the method also carries the
equivalent `#[Test]` attribute; with both present PHPUnit 11 runs the test
without a deprecation notice.
