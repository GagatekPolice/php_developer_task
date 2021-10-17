<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Handler;

use Shoper\Recruitment\Task\Constants\ApiConstants;

/**
* Klasa zarządzająca żądaniami przychodzącymi do api i rozdzielająca je pomiędzy metody poszczególnych kontrolerów.
*/
class ApiRequestHandler
{
    /**
     * @var array dozwolone przez api typy przyjmowany danych w ciele żądania
     */
    const AVAILABLE_CONTENT_TYPES = ['application/json'];

    /**
     * @var array wyrażenie regularne slużące do walidacji nazwy metody
     */
    const REQUEST_PATH_REGEX = "/^[A-Za-z1-9-_]{0,64}$/";

    /**
     * @var array uri wykonanego żądania
     */
    private $requestUri;

    /**
     * @var string metoda wykonanego żądania
     */
    private $requestmMethod;

    public function __construct(array $requestUri)
    {
        $this->requestUri = $requestUri;
        $this->requestmMethod = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Meotda przydziela żądanie do odpowiedniego kontrolera i wywołuje odpowiednią metodę.
     */
    public function processRequest(): void
    {
        if (isset($_SERVER["CONTENT_TYPE"]) && !in_array($_SERVER["CONTENT_TYPE"], self::AVAILABLE_CONTENT_TYPES)) {
            throw new \Exception('Invalid content type', ApiConstants::HTTP_BAD_REQUEST);
        }

        foreach ($this->requestUri as $value) {
            if($value && !preg_match(self::REQUEST_PATH_REGEX, $value)) {
                throw new \Exception('Invalid route name: \'' . $value .'\'', ApiConstants::HTTP_BAD_REQUEST);
            }
        }

    }

}