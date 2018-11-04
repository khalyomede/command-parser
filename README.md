# Command Parser

Parse arguments, options and flags.


![PHP from Packagist](https://img.shields.io/packagist/php-v/khalyomede/command-parser.svg) ![Packagist](https://img.shields.io/packagist/v/khalyomede/command-parser.svg) ![Codeship](https://img.shields.io/codeship/b2c49c20-c29d-0136-16b1-02eaab630210.svg) ![Packagist](https://img.shields.io/packagist/l/khalyomede/command-parser.svg)


```bash
readfile composer.json --max-length 12 --quiet
```

```php
[
  "arguments" => [
    "path" => "composer.json"
  ],
  "options" => [
    "max-length" => "12"
  ],
  "flags" => [
    "quiet" => true
  ]
]
```

## Summary

- [Installation](#installation)
- [Examples](#examples)

## Installation

In the prompt command:

```bash
composer require khalyomede/command-parser:0.*
```

## Examples

_work in progress_