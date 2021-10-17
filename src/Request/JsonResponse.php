<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\Request;

use Shoper\Recruitment\Task\Constants\ApiConstants;

/**
* Klasa zwracająca
*/
class JsonResponse
{
    /**
     * @var int metoda wykonanego żądania
     */
    private $code;
    /**
     * @var mixed uri wykonanego żądania
     */
    private $message;

    public function __construct($message, int $code = ApiConstants::HTTP_NO_CONTENT)
    {
        $this->message = $message;
        $this->code = $code;

        $this->setDefaultHeaders();
        $this->displayMessage();
    }

    private function displayMessage(): void
    {
        echo json_encode($this->message);
    }

    private function setDefaultHeaders(): void
    {
        header('Content-type: application/json; charset=utf-8');
        http_response_code($this->code);
    }
}