<p align="center">
    <a href="https://github.com/mortenscheel/tracer/actions"><img alt="GitHub Workflow Status (master)" src="https://github.com/mortenscheel/tracer/actions/workflows/tests.yml/badge.svg"></a>
    <a href="https://packagist.org/packages/mortenscheel/tracer"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/mortenscheel/tracer"></a>
    <a href="https://packagist.org/packages/mortenscheel/tracer"><img alt="Latest Version" src="https://img.shields.io/packagist/v/mortenscheel/tracer"></a>
    <a href="https://packagist.org/packages/mortenscheel/tracer"><img alt="License" src="https://img.shields.io/packagist/l/mortenscheel/tracer"></a>
</p>
# Tracer

A PHP library for advanced stack trace handling and debugging.

## Installation

```bash
composer require mortenscheel/tracer
```

## Features

- Get detailed stack traces with clean filtering options
- Easily ignore vendor frames, specific classes, methods, files, or lines
- StackTrace is a Laravel Collection
- Format stack frames for debugging and display
- Generate editor links (see [mortenscheel/editor-links](https://github.com/mortenscheel/editor-links))
- Serializable frames for logging or error reporting

## Usage

### Basic Usage

```php
use Scheel\Tracer\StackTrace;

// Get a full stack trace
$trace = StackTrace::getTrace();

// Access the first frame
$firstFrame = $trace->first();

// Convert to array for inspection
$frames = $trace->toArray();
```

### Filtering Frames

```php
use Scheel\Tracer\StackTrace;
use Scheel\Tracer\Frame;

// Ignore vendor frames
$trace = StackTrace::getTrace()->ignoreVendor();

// Ignore specific classes
$trace = StackTrace::getTrace()->ignoreClass(SomeClass::class);

// Ignore specific class methods
$trace = StackTrace::getTrace()->ignoreClass(SomeClass::class, 'methodName');

// Ignore specific files
$trace = StackTrace::getTrace()->ignoreFile('/path/to/file.php');

// Ignore specific lines in files
$trace = StackTrace::getTrace()->ignoreFile('/path/to/file.php', 123);

// Custom filtering using the filter method
$trace = StackTrace::getTrace()->filter(function (Frame $frame): bool {
    return $frame->class !== 'ClassToIgnore';
});
```

### Working with Frames

```php
use Scheel\Tracer\StackTrace;

$frame = StackTrace::getTrace()->first();

// Get frame location as string
echo $frame->location(); // "/path/to/file.php:123"

// Convert frame to array
$frameData = $frame->toArray();
/*
[
    'file' => '/path/to/file.php',
    'line' => 123,
    'function' => 'methodName',
    'class' => 'ClassName',
    'type' => '::',
]
*/

// Generate editor links
echo $frame->editorLink(); // "phpstorm://open?file=/path/to/file.php&line=123"
```

## Running Tests

```bash
composer test
```

## License

MIT

## Author

Morten Scheel
