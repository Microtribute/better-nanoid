<?php

namespace Better\Nanoid;

use Throwable;
use function random_bytes;

class DefaultGenerator implements GeneratorInterface
{
    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function random(int $size): array
    {
        return unpack('C*', random_bytes($size));
    }
}
