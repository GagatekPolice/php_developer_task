<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Handler;

use Shoper\Recruitment\Task\Request\ApiRequest;

/**
* Klasa zarządzająca żądaniami przychodzącymi do api oraz rozdzielająca je pomiędzy metody poszczególne kontrolery.
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
        $this->request->validate()->processControllerAction();
    }
}