[![Latest Stable Version](https://poser.pugx.org/bentools/string-combinations/v/stable)](https://packagist.org/packages/bentools/string-combinations)
[![License](https://poser.pugx.org/bentools/string-combinations/license)](https://packagist.org/packages/bentools/string-combinations)
[![Build Status](https://img.shields.io/travis/bpolaszek/string-combinations/master.svg?style=flat-square)](https://travis-ci.org/bpolaszek/string-combinations)
[![Coverage Status](https://coveralls.io/repos/github/bpolaszek/string-combinations/badge.svg?branch=master)](https://coveralls.io/github/bpolaszek/string-combinations?branch=master)
[![Quality Score](https://img.shields.io/scrutinizer/g/bpolaszek/string-combinations.svg?style=flat-square)](https://scrutinizer-ci.com/g/bpolaszek/string-combinations)
[![Total Downloads](https://poser.pugx.org/bentools/string-combinations/downloads)](https://packagist.org/packages/bentools/string-combinations)

# String combinations

A simple, low-memory footprint function to generate all string combinations from a series of characters.

Installation
------------

PHP 5.6+ is required.

> composer require bentools/string-combinations

Usage
-----

I want to get all combinations with the letters `a`, `b`, `c`.

```php
require_once __DIR__ . '/vendor/autoload.php';
use function BenTools\StringCombinations\string_combinations;

foreach (string_combinations('abc') as $combination) { // Can also be string_combinations(['a', 'b', 'c'])
    echo $combination . PHP_EOL;
}
```

Output:
```
a
b
c
aa
ab
ac
ba
bb
bc
ca
cb
cc
aaa
aab
aac
aba
abb
abc
aca
acb
acc
baa
bab
bac
bba
bbb
bbc
bca
bcb
bcc
caa
cab
cac
cba
cbb
cbc
cca
ccb
ccc
```

**Array output**

_It will dump all combinations into an array._

```php
var_dump(string_combinations('abc')->asArray());
```

**Count combinations**

_It will return the number of possible combinations._

```php
var_dump(count(string_combinations('abc'))); // 39
```

**Specifying min length, max length**

```php
foreach (string_combinations('abc', $min = 2, $max = 2) as $combination) {
    echo $combination . PHP_EOL;
}
```

Output:
```
aa
ab
ac
ba
bb
bc
ca
cb
cc
```

**Using an array as first argument**

```php
foreach (string_combinations(['woof', 'meow', 'roar'], 2, 3) as $combination) {
    echo $combination . PHP_EOL;
}
```

Output:
```
woofwoof
woofmeow
woofroar
meowwoof
meowmeow
meowroar
roarwoof
roarmeow
roarroar
woofwoofwoof
woofwoofmeow
woofwoofroar
woofmeowwoof
woofmeowmeow
woofmeowroar
woofroarwoof
woofroarmeow
woofroarroar
meowwoofwoof
meowwoofmeow
meowwoofroar
meowmeowwoof
meowmeowmeow
meowmeowroar
meowroarwoof
meowroarmeow
meowroarroar
roarwoofwoof
roarwoofmeow
roarwoofroar
roarmeowwoof
roarmeowmeow
roarmeowroar
roarroarwoof
roarroarmeow
roarroarroar
```

Performance considerations
--------------------------

Because it uses [Generators](http://php.net/manual/en/language.generators.syntax.php) to generate all possible combinations, you can iterate over thousands of possibilities without losing a single MB.

This has been executed on my Core i7 personnal computer with 8GB RAM:
```php
require_once __DIR__ . '/vendor/autoload.php';
use function BenTools\StringCombinations\string_combinations;

$start = microtime(true);
$combinations = string_combinations('abcd1234');
foreach ($combinations as $c => $combination) {
    continue;
}
$end = microtime(true);

printf(
    'Generated %d combinations in %ss - Memory usage: %sMB / Peak usage: %sMB' . PHP_EOL,
    ++$c,
    round($end - $start, 3),
    round(memory_get_usage() / 1024 / 1024),
    round(memory_get_peak_usage() / 1024 / 1024)
);
```

Output:
> Generated 19173960 combinations in 5.579s - Memory usage: 0MB / Peak usage: 1MB


Tests
------------

> ./vendor/bin/phpunit


See also
--------

- [bentools/cartesian-product](https://github.com/bpolaszek/cartesian-product)
- [bentools/iterable-functions](https://github.com/bpolaszek/php-iterable-functions)