<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Request;

/**
* Klasa odpowiedzialna za reprezentację wartości parametru query żądania
*/
class QueryParameter
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getFormatedQueryParameter(): string
    {
        return "{$this->key}={$this->value}";
    }
}