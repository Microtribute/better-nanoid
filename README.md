# Nanoid-php


## Overview

Replacement of [hidehalo/nanoid-php](https://github.com/hidehalo/nanoid-php) to work with PHP 7.1+

> A tiny (179 bytes), secure URL-friendly unique string ID generator for JavaScript
>
> **Safe.** It uses cryptographically strong random APIs and guarantees a proper distribution of symbols.
>
> **Small.** Only 179 bytes (minified and gzipped). No dependencies. It uses Size Limit to control size.
>
> **Compact.** It uses more symbols than UUID (A-Za-z0-9_\-) and has the same number of unique options in just 21 symbols instead of 36.

## Install

Via Composer

``` bash
composer require better/nanoid
```

## Usage

### Normal

> The main module uses URL-friendly symbols (A-Za-z0-9_\-) and returns an ID with 21 characters (to have the same collisions probability as UUID v4).

``` php
use Better\Nanoid\Client;
use Better\Nanoid\GeneratorInterface;

$client = new Client();

# default random generator
echo $client->produce($size = 32);

# more secure generator with more entropy
echo $client->produce($size = 32, true);
```

### Custom Alphabet or Length

``` php
$client = new Client('0123456789abcdefg');
$client->produce();
```

> Alphabet must contain 256 symbols or less.
> Otherwise, the generator will not be secure.

### Custom Random Bytes Generator

``` php
# PS: anonymous class is new feature when PHP_VERSION >= 7.0

$client = new Client();

echo $client->produceUsing(new class implements GeneratorInterface {
    /**
     * @inheritDoc
     */
    public function random(int $size): string
    {
        $ret = [];
        
        while ($size--) {
            $ret[] = mt_rand(0, 255);
        }

        return $ret;
    }
});
```

> `random` callback must accept the array size and return an array with random numbers.


## Credits

- [ai](https://github.com/ai)
- [hidehalo](https://github.com/hidehalo)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
