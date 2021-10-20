<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Services;

class Uuid 
{
    /**
     * @var string wyrażenie regularne slużące do walidacji uuid
     */
    const UUID_REGEX = "/^[A-Za-z0-9]{8}(-[A-Za-z0-9]{4}){3}-[A-Za-z0-9]{12}$/";

    static public function isValidUuid(string $uuid): bool
    {
        return (bool) preg_match(self::UUID_REGEX, $uuid);
    }

    /**
     * Generator pseudo unikalnej wartości id encji
     */
    static public function uuid4() {
        $data = openssl_random_pseudo_bytes(16);
        assert(strlen($data) === 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}