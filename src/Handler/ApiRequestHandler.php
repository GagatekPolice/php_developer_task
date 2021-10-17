<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Handler;

use Shoper\Recruitment\Task\Model\ApiRequest;

/**
* Klasa zarządzająca żądaniami przychodzącymi do api i rozdzielająca je pomiędzy metody poszczególnych kontrolerów.
*/
class ApiRequestHandler
{
    /**
     * @var ApiRequest żądanie, który przyszło do API
     */
    private $request;

    public function __construct()
    {
        $this->request = new ApiRequest();
    }

    /**
     * Meotda służąca do przetworzenia żądania klienta
     */
    public function processRequest(): void
    {
        $this->request->validate();
    }
}