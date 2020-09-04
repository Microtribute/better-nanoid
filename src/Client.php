<?php

namespace Better\Nanoid;

class Client
{
    public const ALPHABET = '_-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var string
     */
    protected $alphabet;

    public function __construct(string $alphabet = self::ALPHABET)
    {
        $this->alphabet = $alphabet;
    }

    /**
     * Generate secure URL-friendly unique ID.
     * By default, ID will have 21 symbols to have same collisions probability
     * as UUID v4.
     *
     * @see https://github.com/ai/nanoid/blob/master/non-secure/index.js#L19
     * @param integer $size
     * @param bool $moreEntropy
     * @return string
     */
    public function produce(int $size = 32, bool $moreEntropy = false): string
    {
        return $moreEntropy ? $this->produceEntropy($size) : $this->produceNegentropy($size);
    }

    /**
     * Generates an ID using a custom generator
     *
     * @param GeneratorInterface $generator
     * @param int $size
     * @return string
     */
    public function produceUsing(GeneratorInterface $generator, int $size = 32): string
    {
        $alphabetLength = strlen($this->alphabet);

        $mask = (2 << log($alphabetLength - 1) / M_LN2) - 1;
        $step = (int) ceil(1.6 * $mask * $size / $alphabetLength);

        $id = '';

        while (true) {
            $bytes = $generator->random($step);

            for ($i = 1; $i <= $step; $i++) {
                $byte = $bytes[$i] & $mask;

                if (isset($this->alphabet[$byte])) {
                    $id .= $this->alphabet[$byte];

                    if (strlen($id) === $size) {
                        return $id;
                    }
                }
            }
        }
    }


    private function produceNegentropy(int $size): string
    {
        $id = '';

        while (1 <= $size--) {
            $rand = mt_rand() / (mt_getrandmax() + 1);
            $id .= $this->alphabet[$rand * 64 | 0];
        }

        return $id;
    }

    private function produceEntropy(int $size): string
    {
        static $defaultGenerator;

        if (!$defaultGenerator) {
            $defaultGenerator = new DefaultGenerator();
        }

        return $this->produceUsing($defaultGenerator, $size);
    }
}
